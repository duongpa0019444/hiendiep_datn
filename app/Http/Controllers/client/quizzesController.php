<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use App\Models\answers;
use App\Models\classes;
use App\Models\classStudent;
use App\Models\questions;
use App\Models\quizAttempts;
use App\Models\Quizzes;
use App\Models\sentenceAnswers;
use App\Models\sentenceQuestions;
use App\Models\studentAnswers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isArray;

class quizzesController extends Controller
{

    public function start($id)
    {
        $quiz = quizzes::withCount(['questions', 'sentenceQuestions'])->with(['class'])->findOrFail($id);
        // dd(Auth::user()->classStudents);


        $classStudent = DB::table('class_student')
            ->join('classes', 'class_student.class_id', '=', 'classes.id')
            ->where('class_student.student_id', Auth::user()->id)
            ->where('classes.status', 'in_progress')
            ->orderBy('classes.created_at', 'asc')
            ->select('class_student.*', 'classes.name as class_name') // chỉ lấy dữ liệu từ bảng class_student
            ->first();

        //lấy câu hỏi trắc nghiệm
        $mcQuestions = questions::where('quiz_id', $id)->get()->map(function ($q) {
            $q->question_type = 'multiple_choice';
            return $q;
        });
        //lấy câu hỏi điền từ/sắp xếp câu
        $fillQuestions = sentenceQuestions::where('quiz_id', $id)->get()->map(function ($q) {
            $q->question_type = 'fill_blank';
            return $q;
        });

        //gộp mảng
        $allQuestions = $mcQuestions->concat($fillQuestions)->sortBy('created_at')->values();
        $answers = answers::whereIn('question_id', $mcQuestions->pluck('id'))->get(); //  Lấy tất cả các đáp án trắc nghiệm cho các câu hỏi đã lấy ở trên.

        return view('client.quizzes.start-quiz', compact('classStudent', 'quiz', 'allQuestions', 'answers'));
    }

    public function showResult($quizId)
    {
        $quizAttempts = quizAttempts::where('quiz_id', $quizId)
            ->where('user_id', Auth::id())
            ->get();

        $quiz = quizzes::findOrFail($quizId);

        $status = classStudent::where('class_id', $quiz->class_id)
            ->where('student_id', Auth::user()->id)
            ->exists();

        return response()->json([
            'quizAttempts' => $quizAttempts,
            'quiz' => $quiz,
            'status' => $status
        ]);
    }


    public function resultsQuizzStudent($id, $attemptId, $studentId=null)
    {
        $quiz = quizzes::findOrFail($id);
        $studentId = $studentId ?? Auth::user()->id;
        $student = DB::table('users')->where('id', $studentId)->where('role', 'student')->first();

        $attempt = DB::table('quiz_attempts as qa')
            ->select([
                'qa.id as attempt_id',
                'qa.user_id',
                'qa.quiz_id',
                'qa.score',
                'qa.total_correct',
                'qa.total_questions',
                'qa.started_at',
                'qa.submitted_at',
                'qa.class_id',
                DB::raw('TIMESTAMPDIFF(MINUTE, qa.started_at, qa.submitted_at) as duration_minutes'),
                DB::raw("DATE_FORMAT(qa.submitted_at, '%d/%m/%Y') as completed_date")
            ])
            ->where('qa.quiz_id', $quiz->id)
            ->where('qa.user_id', $student->id)
            ->where('qa.id', $attemptId)
            ->orderBy('qa.started_at', 'asc')
            ->first();
        // dd($attempt);
        // Load câu hỏi
        $mcQuestions = questions::where('quiz_id', $id)->get()->map(function ($q) {
            $q->question_type = 'multiple_choice';
            return $q;
        });

        $fillQuestions = sentenceQuestions::where('quiz_id', $id)->get()->map(function ($q) {
            $q->question_type = 'fill_blank';
            return $q;
        });

        $allQuestions = $mcQuestions->concat($fillQuestions)->sortBy('created_at')->values();
        $answers = answers::whereIn('question_id', $mcQuestions->pluck('id'))->get();


        // Lấy câu trả lời trắc nghiệm của học sinh trong lần làm bài này
        $studentMcAnswers = DB::table('student_answers')
            ->where('attempt_id', $attemptId)
            ->get();
        // Lấy câu trả lời điền từ/sắp xếp câu
        $studentSentenceAnswers = DB::table('sentence_answers')
            ->where('attempt_id', $attemptId)
            ->get();

        return view('client.quizzes.show-result-modal', compact('quiz', 'student', 'attempt', 'allQuestions', 'answers', 'studentMcAnswers', 'studentSentenceAnswers'));
    }

    public function checkAccessCode($code)
    {
        $quiz = Quizzes::select('quizzes.*')
            ->selectRaw('(SELECT COUNT(*) FROM questions WHERE quiz_id = quizzes.id) +
                    (SELECT COUNT(*) FROM sentence_questions WHERE quiz_id = quizzes.id) AS total_questions')
            ->where('access_code', $code)
            ->where('status', 'published')
            ->where('is_public', 1)
            ->with(['creator'])
            ->first();

        return response()->json($quiz);
    }

    public function submitQuiz(Request $request, $quizId, $classId)
    {
        // dd($request->all());
        //Lấy đáp án chính xác của quizz
        $mcQuestions = questions::where('quiz_id', $quizId)->get()->map(function ($q) {
            $q->question_type = 'multiple_choice';
            return $q;
        });

        $fillQuestions = sentenceQuestions::where('quiz_id', $quizId)->get()->map(function ($q) {
            $q->question_type = 'fill_blank';
            return $q;
        });

        $allQuestions = $mcQuestions->concat($fillQuestions)->sortBy('created_at')->values();
        $answers = answers::whereIn('question_id', $mcQuestions->pluck('id'))->get();
        // dd($answers);

        $scoreStudent = 0;
        $total_correct = 0;
        $total_questions = count($allQuestions);
        $answersStudent = []; //để lưu đáp án trắc nghiệm của học sinh
        $sentenceAnswersStudent = []; //để lưu đáp án điền từ/sắp xếp câu của học sinh

        foreach ($allQuestions as $index => $question) {
            $questionId = $question->id;
            $inputName = 'q' . ($index + 1); // ví dụ q1, q2...

            $studentAnswer = $request->input($inputName); // có thể là string hoặc array

            if ($question->question_type == 'multiple_choice') {
                if ($question->type == 'single') {
                    // 1 đáp án đúng
                    $correctId = $answers->where('question_id', $questionId)->where('is_correct', 1)->pluck('id')->first(); //đáp án đúng
                    if ((int)$studentAnswer === (int)$correctId) {
                        $scoreStudent += $question->points;
                        $total_correct++;
                    }
                    $answersStudent[] = [
                        'question_id' => $questionId,
                        'answer_id' => $studentAnswer
                    ];
                } elseif ($question->type == 'multiple') {
                    // nhiều đáp án đúng
                    $correctIds = $answers->where('question_id', $questionId)->where('is_correct', 1)->pluck('id')->sort()->values()->toArray(); //đáp án đúng
                    $selectedIds = is_array($studentAnswer) ? $studentAnswer : []; //đáp án của học sinh

                    sort($selectedIds);

                    if ($correctIds === $selectedIds) {
                        $scoreStudent += $question->points;
                        $total_correct++;
                    }

                    foreach ($selectedIds as $correctId) {
                        $answersStudent[] = [
                            'question_id' => $questionId,
                            'answer_id' => $correctId
                        ];
                    }
                }
            } elseif ($question->question_type == 'fill_blank') {
                $correctAnswer = trim(strtolower($question->correct_answer));
                $studentText = trim(strtolower($studentAnswer));

                if ($correctAnswer === $studentText) {
                    $scoreStudent += $question->points;
                    $total_correct++;
                }
                $sentenceAnswersStudent[] = [
                    'question_id' => $questionId,
                    'user_answer' => $studentAnswer,
                    'is_correct' => $correctAnswer === $studentText ? 1 : 0
                ];
            }
        }

        //Lưu kết quả vào bảng quiz_attempts
        $quizAttempts = quizAttempts::create([
            'quiz_id' => $quizId,
            'user_id' => Auth::user()->id,
            'started_at'  => $request->input('started_at'),
            'submitted_at' => $request->input('submitted_at'),
            'score' => $scoreStudent,
            'total_correct' => $total_correct,
            'total_questions' => $total_questions,
            'class_id' => $classId

        ]);

        //Lưu đáp án trắc nghiệm của học sinh
        foreach ($answersStudent as &$answer) {
            $answer['attempt_id'] = $quizAttempts->id;
        }
        studentAnswers::insert($answersStudent);

        //Lưu đáp án điền từ/sắp xếp câu của học sinh
        foreach ($sentenceAnswersStudent as &$answer) {
            $answer['attempt_id'] = $quizAttempts->id;
        }
        sentenceAnswers::insert($sentenceAnswersStudent);

        $quiz = quizzes::findOrFail($quizId);

        return redirect()->route('student.quizzes.resultsQuizzComplete', $quizAttempts->id);
    }


    public function resultsQuizzComplete($quizAttemptsId)
    {
        $quizAttempts = quizAttempts::findOrFail($quizAttemptsId);
        $quiz = quizzes::findOrFail($quizAttempts->quiz_id);
        return view('client.quizzes.results-student', compact('quiz', 'quizAttempts'));
    }



    //của giáo viên
    public function results($id)
    {
        $quiz = Quizzes::findOrFail($id);

        // Truy vấn danh sách lớp đã có học sinh làm bài quiz
        $classes = DB::table('classes as c')
            ->join('courses as co', 'co.id', '=', 'c.courses_id')
            ->join('class_student as cs', 'cs.class_id', '=', 'c.id')
            ->join('quiz_attempts as qa', function ($join) use ($id) {
                $join->on('qa.user_id', '=', 'cs.student_id')
                    ->where('qa.quiz_id', '=', $id)
                    ->whereNull('qa.deleted_at');
            })
            ->select([
                'c.id as class_id',
                'c.name as class_name',
                'co.name as course_name',
                DB::raw('(SELECT COUNT(*) FROM class_student cs2 WHERE cs2.class_id = c.id) as total_students'),
                DB::raw('COUNT(DISTINCT qa.user_id) as students_attempted'),
                DB::raw('COUNT(qa.id) as total_attempts')
            ])
            ->groupBy('c.id', 'c.name', 'co.name', 'c.created_at')
            ->orderByDesc('c.created_at')
            ->get();

        $totalClassesWithQuiz = $classes->count();


        return response()->json([
            'statistics' =>  $totalClassesWithQuiz,
            'classes' => $classes,
            'quiz' => $quiz
        ]);
    }



    public function resultsClass($id, $class, Request $request)
    {


        $statistics = DB::select("
            SELECT
                c.id AS class_id,
                c.name AS class_name,
                COUNT(DISTINCT cs.student_id) AS total_students, -- Tổng số học viên trong lớp

                COUNT(DISTINCT qa.user_id) AS students_attempted, -- Số học viên trong lớp đã làm quiz

                COUNT(qa.id) AS total_attempts -- Tổng số lượt làm bài

            FROM classes c
            JOIN class_student cs ON cs.class_id = c.id
            LEFT JOIN quiz_attempts qa
                ON qa.user_id = cs.student_id
                AND qa.quiz_id = ?
                AND qa.deleted_at IS NULL

            WHERE c.id = ?
            GROUP BY c.id, c.name;

        ", [$id, $class]);

        $quiz = quizzes::findOrFail($id);
        $class = classes::findOrFail($class);

        // Lấy danh sách học sinh trong lớp đã làm quiz
        $students = DB::table('users as u')
            ->join('class_student as cs', 'cs.student_id', '=', 'u.id')
            ->join('quiz_attempts as qa', function ($join) use ($id) {
                $join->on('qa.user_id', '=', 'u.id')
                    ->where('qa.quiz_id', $id)
                    ->whereNull('qa.deleted_at');
            })
            ->where('u.role', 'student')
            ->where('cs.class_id', $class->id)
            ->select([
                'u.id as student_id',
                'u.name as student_name',
                DB::raw("DATE_FORMAT(u.birth_date, '%d/%m/%Y') as birthday"),
                'u.gender',
                DB::raw('COUNT(qa.id) as total_attempts'),
                DB::raw('ROUND(AVG(qa.score), 2) as average_score'),
            ])
            ->groupBy('u.id', 'u.name', 'u.birth_date', 'u.gender')
            ->orderBy('u.name')->get();

        // Kiểm tra nếu là AJAX request
        if ($request->ajax()) {
            return response()->json([
                'students' => $students,
                'quiz' => $quiz,
                'statistics' => $statistics[0]
            ]);
        }

    }

    public function resultsClassStudent($id, $class, $student, Request $request)
    {
        $quiz = quizzes::findOrFail($id);
        $class = classes::findOrFail($class);
        $student = DB::table('users')->where('id', $student)->where('role', 'student')->first();

        $statistics = DB::select(
            "
            SELECT
                u.id AS student_id,
                u.name AS student_name,
                COUNT(qa.id) AS total_attempts,
                ROUND(AVG(qa.score), 2) AS average_score,
                ROUND(AVG(TIMESTAMPDIFF(MINUTE, qa.started_at, qa.submitted_at)), 2) AS average_time_minutes
            FROM users u
            JOIN class_student cs ON cs.student_id = u.id
            LEFT JOIN quiz_attempts qa
                ON qa.user_id = u.id
            AND qa.quiz_id = ?
                AND qa.deleted_at IS NULL
            WHERE u.role = 'student'
                AND cs.class_id = ?
                AND u.id = ?
            GROUP BY u.id, u.name
            ORDER BY u.name;",
            [$id, $class->id, $student->id]
        );

        $quizAttempts = DB::table('quiz_attempts as qa')
            ->select([
                'qa.id as attempt_id',
                'qa.user_id',
                'qa.quiz_id',
                'qa.score',
                'qa.total_correct',
                DB::raw("(
                    SELECT COUNT(*) FROM (
                        SELECT id FROM questions WHERE quiz_id = $quiz->id
                        UNION ALL
                        SELECT id FROM sentence_questions WHERE quiz_id = $quiz->id
                    ) as all_questions
                ) as total_questions"),
                DB::raw('TIMESTAMPDIFF(MINUTE, qa.started_at, qa.submitted_at) as duration_minutes'),
                DB::raw("DATE_FORMAT(qa.submitted_at, '%d/%m/%Y') as completed_date")
            ])
            ->where('qa.quiz_id', $quiz->id)
            ->where('qa.user_id', $student->id)
            ->orderBy('qa.started_at', 'asc')->get();

        // dd($attempts);
        if ($request->ajax()) {
            return response()->json([
                'quizAttempts' => $quizAttempts,
                'quiz' => $quiz,
                'class' => $class,
                'student' => $student,
                'statistics' => $statistics
            ]);
        }
    }
}
