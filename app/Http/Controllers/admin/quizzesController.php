<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\answers;
use App\Models\classes;
use App\Models\courses;
use App\Models\questions;
use App\Models\quizAttempts;
use App\Models\quizzes;
use App\Models\sentenceAnswers;
use App\Models\sentenceQuestions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\Validator;

class quizzesController extends Controller
{
    //
    public function index(Request $request)
    {
        $quizzes = quizzes::orderByDesc('created_at')->with(['creator', 'course', 'class'])->paginate(10);
        $statistics = DB::select("
            SELECT
                (SELECT COUNT(*) FROM quizzes WHERE deleted_at IS NULL) AS total_quizzes,
                (SELECT COUNT(*) FROM quizzes WHERE is_public = 1 AND deleted_at IS NULL) AS total_public_quizzes,
                (SELECT COUNT(*) FROM quiz_attempts) AS total_attempts,
                (SELECT COUNT(user_id)
                FROM quiz_attempts
                WHERE user_id IN (SELECT id FROM users WHERE role = 'student')) AS total_students_participated
            FROM DUAL
        ");
        // Kiểm tra nếu là AJAX request
        if ($request->ajax()) {
            return response()->json([
                'quizzes' => $quizzes,
                'pagination' => $quizzes->links('pagination::bootstrap-5')->toHtml()
            ]);
        }

        return view('admin.quizzes.quizzes-list', compact('quizzes', 'statistics'));
    }

    public function trash(Request $request)
    {

        $quizzes = quizzes::onlyTrashed()->orderByDesc('created_at')->with(['creator', 'course', 'class'])->paginate(10);
        // Kiểm tra nếu là AJAX request
        if ($request->ajax()) {
            return response()->json([
                'quizzes' => $quizzes,
                'pagination' => $quizzes->links('pagination::bootstrap-5')->toHtml()
            ]);
        }

        return view('admin.quizzes.quizzes-trash', compact('quizzes'));
    }


    public function filter(Request $request)
    {
        $quizzes = $this->getFilterQuizzes($request);
        $quizzes->appends($request->all());
        return response()->json([
            'quizzes' => $quizzes,
            'pagination' => $quizzes->links('pagination::bootstrap-5')->toHtml()
        ]);
    }

    private function getFilterQuizzes(Request $request)
    {
        $query = quizzes::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('is_public')) {
            $query->where('is_public', $request->is_public);
        }

        if ($request->filled('class_id')) {
            $query->whereHas('class', function ($q) use ($request) {
                $q->where('id', $request->class_id);
            });
        }

        if ($request->filled('course_id')) {
            $query->whereHas('course', function ($q) use ($request) {
                $q->where('id', $request->course_id);
            });
        }

        if ($request->filled('keyword')) {
            $query->where('title', 'like', '%' . $request->keyword . '%');
        }

        return $query->with(['creator', 'course', 'class'])->orderByDesc('created_at')->paginate($request->limit);
    }

    public function filterTrash(Request $request)
    {
        $quizzes = $this->getFilterTrashQuizzes($request);
        $quizzes->appends($request->all());
        return response()->json([
            'quizzes' => $quizzes,
            'pagination' => $quizzes->links('pagination::bootstrap-5')->toHtml()
        ]);
    }

    private function getFilterTrashQuizzes(Request $request)
    {
        $query = quizzes::onlyTrashed();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('is_public')) {
            $query->where('is_public', $request->is_public);
        }

        if ($request->filled('class_id')) {
            $query->whereHas('class', function ($q) use ($request) {
                $q->where('id', $request->class_id);
            });
        }

        if ($request->filled('course_id')) {
            $query->whereHas('course', function ($q) use ($request) {
                $q->where('id', $request->course_id);
            });
        }

        if ($request->filled('keyword')) {
            $query->where('title', 'like', '%' . $request->keyword . '%');
        }

        return $query->with(['creator', 'course', 'class'])->orderByDesc('created_at')->paginate($request->limit);
    }


    public function delete($id)
    {
        $quiz = Quizzes::with('creator')->findOrFail($id);
        $quiz->delete();

        // Truy vấn số câu hỏi dạng multiple + sentence
        $counts = DB::table('quizzes as q')
            ->leftJoin('questions as ques', 'q.id', '=', 'ques.quiz_id')
            ->leftJoin('sentence_questions as sq', 'q.id', '=', 'sq.quiz_id')
            ->where('q.id', $quiz->id)
            ->selectRaw('COUNT(DISTINCT ques.id) + COUNT(DISTINCT sq.id) as total_questions')
            ->first();

        $quiz->total_questions = $counts->total_questions ?? 0;

        return response()->json($quiz);
    }


    public function forceDelete($id)
    {



        try {
            DB::transaction(function () use ($id) {
                $quiz = quizzes::onlyTrashed()->findOrFail($id);



                // Lấy các câu hỏi thường (multiple choice)
                $questionIds = DB::table('questions')->where('quiz_id', $id)->pluck('id');
                if ($questionIds->isNotEmpty()) {
                    $answerIds = DB::table('answers')->whereIn('question_id', $questionIds)->pluck('id');

                    // Xóa student_answers trước
                    if ($answerIds->isNotEmpty()) {
                        DB::table('student_answers')->whereIn('answer_id', $answerIds)->delete();
                        DB::table('answers')->whereIn('id', $answerIds)->delete();
                    }

                    DB::table('questions')->whereIn('id', $questionIds)->delete();
                }

                // Lấy các câu hỏi dạng điền câu (sentence)
                $sentenceQuestionIds = DB::table('sentence_questions')->where('quiz_id', $id)->pluck('id');
                if ($sentenceQuestionIds->isNotEmpty()) {
                    DB::table('sentence_answers')->whereIn('question_id', $sentenceQuestionIds)->delete();
                    DB::table('sentence_questions')->whereIn('id', $sentenceQuestionIds)->delete();
                }

                // Xóa bài làm của học sinh
                DB::table('quiz_attempts')->where('quiz_id', $id)->delete();

                // Xóa vĩnh viễn quiz
                $quiz->forceDelete();
            });
        } catch (\Throwable $e) {
            Log::error('Lỗi xảy ra khi xóa quiz: ' . $e->getMessage());
            // hoặc log thêm chi tiết:
            Log::error($e);
        }

        return redirect()->back()->with('success', 'Đã xóa vĩnh viễn quiz và toàn bộ dữ liệu liên quan.');
    }

    public function restore(Request $request, $id)
    {
        $quizzes = quizzes::onlyTrashed()->findOrFail($id);
        $quizzes->restore();
        if ($request->ajax()) {
            $counts = DB::table('quizzes as q')
                ->leftJoin('questions as ques', 'q.id', '=', 'ques.quiz_id')
                ->leftJoin('sentence_questions as sq', 'q.id', '=', 'sq.quiz_id')
                ->where('q.id', $quizzes->id)
                ->selectRaw('COUNT(DISTINCT ques.id) + COUNT(DISTINCT sq.id) as total_questions')
                ->first();

            $quizzes->total_questions = $counts->total_questions ?? 0;
            return response()->json($quizzes);
        }
        return redirect()->back()->with('success', 'Khôi phục quiz thành công!');
    }


    public function generateUniqueAccessCode()
    {
        do {
            $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (quizzes::where('access_code', $code)->exists());

        return $code;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'duration_minutes' => 'required|integer|min:1',
            'access_code' => 'nullable|string|max:20',
            'is_public' => 'required|boolean',
            'class_id' => 'nullable|integer|exists:classes,id',
            'course_id' => 'nullable|integer|exists:courses,id',
        ], [
            'title.required' => 'Tiêu đề không được để trống.',
            'title.string' => 'Tiêu đề phải là chuỗi.',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự.',
            'description.required' => 'Mô tả không được để trống.',
            'description.string' => 'Mô tả phải là chuỗi.',
            'duration_minutes.required' => 'Thời gian làm bài là bắt buộc.',
            'duration_minutes.integer' => 'Thời gian làm bài phải là số nguyên.',
            'duration_minutes.min' => 'Thời gian làm bài phải ít nhất là 1 phút.',
            'access_code.string' => 'Mã truy cập phải là chuỗi.',
            'access_code.max' => 'Mã truy cập không được vượt quá 20 ký tự.',
            'is_public.required' => 'Vui lòng chọn loại hiển thị của quiz.',
            'is_public.boolean' => 'Trường is_public phải là giá trị đúng hoặc sai.',
            'class_id.integer' => 'Lớp học không hợp lệ.',
            'class_id.exists' => 'Lớp học được chọn không tồn tại.',
            'course_id.integer' => 'Khóa học không hợp lệ.',
            'course_id.exists' => 'Khóa học được chọn không tồn tại.',
        ]);

        // Custom rule: nếu là "Không công khai" thì phải chọn class_id hoặc course_id
        $validator->after(function ($validator) use ($request) {
            if ($request->input('is_public') == 0) {
                if (empty($request->input('class_id')) && empty($request->input('course_id'))) {
                    $validator->errors()->add('class_id', 'Bạn phải chọn ít nhất lớp học nếu quiz là riêng tư.');
                    $validator->errors()->add('course_id', 'Bạn phải chọn ít nhất khóa học nếu quiz là riêng tư.');
                }
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        $quizz = quizzes::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => 'draft',
            'duration_minutes' => $validated['duration_minutes'],
            'access_code' => $this->generateUniqueAccessCode(),
            'is_public' => $validated['is_public'],
            'class_id' => $validated['is_public'] ? null : $validated['class_id'],
            'course_id' => $validated['is_public'] ? null : $validated['course_id'],
            'created_by' => Auth::id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thêm bài quiz thành công!',
            'quiz' => $quizz,
        ], 200);
    }


    public function update(Request $request, $id)
    {
        $quiz = quizzes::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'duration_minutes' => 'required|integer|min:1',
            'access_code' => 'nullable|string|max:20',
            'is_public' => 'required|boolean',
            'class_id' => 'nullable|integer|exists:classes,id',
            'course_id' => 'nullable|integer|exists:courses,id',
        ], [
            'title.required' => 'Tiêu đề không được để trống.',
            'title.string' => 'Tiêu đề phải là chuỗi.',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự.',
            'description.required' => 'Mô tả không được để trống.',
            'description.string' => 'Mô tả phải là chuỗi.',
            'duration_minutes.required' => 'Thời gian làm bài là bắt buộc.',
            'duration_minutes.integer' => 'Thời gian làm bài phải là số nguyên.',
            'duration_minutes.min' => 'Thời gian làm bài phải ít nhất là 1 phút.',
            'access_code.string' => 'Mã truy cập phải là chuỗi.',
            'access_code.max' => 'Mã truy cập không được vượt quá 20 ký tự.',
            'is_public.required' => 'Vui lòng chọn loại hiển thị của quiz.',
            'is_public.boolean' => 'Trường is_public phải là giá trị đúng hoặc sai.',
            'class_id.integer' => 'Lớp học không hợp lệ.',
            'class_id.exists' => 'Lớp học được chọn không tồn tại.',
            'course_id.integer' => 'Khóa học không hợp lệ.',
            'course_id.exists' => 'Khóa học được chọn không tồn tại.',
        ]);

        // Custom rule: nếu là "Không công khai" thì phải chọn class_id hoặc course_id
        $validator->after(function ($validator) use ($request) {
            if ($request->input('is_public') == 0) {
                if (empty($request->input('class_id')) && empty($request->input('course_id'))) {
                    $validator->errors()->add('class_id', 'Bạn phải chọn ít nhất lớp học hoặc khóa học nếu quiz là riêng tư.');
                    $validator->errors()->add('course_id', 'Bạn phải chọn ít nhất lớp học hoặc khóa học nếu quiz là riêng tư.');
                }
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        $quiz->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'duration_minutes' => $validated['duration_minutes'],
            'access_code' => $quiz->access_code,
            'is_public' => $validated['is_public'],
            'class_id' => $validated['is_public'] ? null : $validated['class_id'],
            'course_id' => $validated['is_public'] ? null : $validated['course_id'],
            'updated_at' => now(),
        ]);

        return response()->json([
            'action' => 'edit',
            'message' => 'Cập nhật bài quiz thành công!',
            'quiz' => $quiz,
        ], 200);
    }

    public function updateStatus($id, $status)
    {
        $quiz = quizzes::findOrFail($id);
        $quiz->status = $status;
        $quiz->save();

        return response()->json([
            'message' => 'Lưu trạng thái ' . ($status == "published" ? 'xuất bản' : 'nháp') . ' bài quiz thành công!',
            'quiz' => $quiz,
        ], 200);
    }


    public function detail($id, Request $request)
    {

        $quiz = quizzes::with(['creator', 'course', 'class'])->findOrFail($id);

        $mcQuestions = questions::where('quiz_id', $id)
            ->get()
            ->map(function ($q) {
                $q->question_type = 'multiple_choice';
                return $q;
            });

        $fillQuestions = sentenceQuestions::where('quiz_id', $id)
            ->get()
            ->map(function ($q) {
                $q->question_type = 'fill_blank';
                return $q;
            });

        // Gộp lại và sắp xếp theo thời gian thêm
        $allQuestions = $mcQuestions->concat($fillQuestions)->sortBy('created_at')->values();
        $answers = answers::whereIn('question_id', $mcQuestions->pluck('id'))->get();
        // Kiểm tra nếu là AJAX request
        if ($request->ajax()) {
            return response()->json($quiz);
        }
        if (Auth::user()->role == 'admin' || Auth::user()->role == 'staff') {
            return view('admin.quizzes.quizzes-detail', compact('quiz', 'allQuestions', 'answers'));
        } elseif (Auth::user()->role == 'teacher') {
            return view('client.quizzes.quizzes-detail', compact('quiz', 'allQuestions', 'answers'));
        }
    }










    // Quản lý kết quả quizz

    // Hiển thị kết quả danh sách lớp học có học sinh làm quizz
    public function results($id, Request $request)
    {
        $quiz = quizzes::findOrFail($id);
        $statistics = DB::select("
           SELECT
            (
                SELECT COUNT(DISTINCT c.id)
                FROM class_student cs
                JOIN classes c ON c.id = cs.class_id
                JOIN quiz_attempts qa ON qa.user_id = cs.student_id
                WHERE qa.deleted_at IS NULL
                AND qa.quiz_id = ?
            ) AS total_classes_with_quiz,

            (
                SELECT COUNT(DISTINCT qa.user_id)
                FROM quiz_attempts qa
                WHERE qa.deleted_at IS NULL
                AND qa.quiz_id = ?
            ) AS total_students_attempted,

            (
                SELECT COUNT(*)
                FROM quiz_attempts qa
                WHERE qa.deleted_at IS NULL
                AND qa.quiz_id = ?
            ) AS total_attempts_for_quiz;

        ", [$id, $id, $id]);

        $classes =  DB::table('classes as c')
            ->join('courses as co', 'co.id', '=', 'c.courses_id')
            ->join('class_student as cs', 'cs.class_id', '=', 'c.id')
            ->join('quiz_attempts as qa', function ($join) use ($id) {
                $join->on('qa.user_id', '=', 'cs.student_id')
                    ->where('qa.quiz_id', '=', $id)
                    // ->whereColumn('qa.class_id', '=', 'cs.class_id')
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
            ->paginate(10);

        // Kiểm tra nếu là AJAX request
        if ($request->ajax()) {
            return response()->json([
                'classes' => $classes,
                'pagination' => $classes->links('pagination::bootstrap-5')->toHtml()
            ]);
        }

        return view('admin.quizzes.results.classes', compact('statistics', 'classes', 'quiz'));
    }


    public function filterResults(Request $request)
    {
        $classes = $this->getFilterResults($request);
        $classes->appends($request->all()); // Giữ lại bộ lọc khi phân trang

        return response()->json([
            'classes' => $classes,
            'pagination' => $classes->links('pagination::bootstrap-5')->toHtml()
        ]);
    }


    private function getFilterResults(Request $request)
    {
        $quizId = $request->quiz_id;

        $query = DB::table('classes as c')
            ->join('courses as co', 'co.id', '=', 'c.courses_id')
            ->join('class_student as cs', 'cs.class_id', '=', 'c.id')
            ->join('quiz_attempts as qa', function ($join) use ($quizId) {
                $join->on('qa.user_id', '=', 'cs.student_id')
                    ->where('qa.quiz_id', '=', $quizId)
                    ->whereNull('qa.deleted_at');
            })
            ->select([
                'c.id as class_id',
                'c.name as class_name',
                'co.name as course_name',
                DB::raw('(SELECT COUNT(*) FROM class_student cs2 WHERE cs2.class_id = c.id) as total_students'),
                DB::raw('COUNT(DISTINCT qa.user_id) as students_attempted'),
                DB::raw('COUNT(qa.id) as total_attempts'),
                'c.created_at'
            ])
            ->groupBy('c.id', 'c.name', 'co.name', 'c.created_at')
            ->orderByDesc('c.created_at');

        if ($request->filled('class_id')) {
            $query->where('c.id', $request->class_id);
        }

        if ($request->filled('course_id')) {
            $query->where('co.id', $request->course_id);
        }

        if ($request->filled('class_status')) {
            $query->where('c.status', $request->class_status);
        }

        return $query->paginate($request->limit ?? 10);
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
            ->orderBy('u.name')
            ->paginate(10);

        // Kiểm tra nếu là AJAX request
        if ($request->ajax()) {
            return response()->json([
                'students' => $students,
                'pagination' => $students->links('pagination::bootstrap-5')->toHtml()
            ]);
        }

        return view('admin.quizzes.results.classes-detail', compact('statistics', 'quiz', 'class', 'students'));
    }

    public function filterAttemptsClassStudent(Request $request)
    {
        $students = $this->getFilterAttemptsClassStudent($request);
        $students->appends($request->all()); // Giữ lại bộ lọc khi phân trang

        return response()->json([
            'students' => $students,
            'pagination' => $students->links('pagination::bootstrap-5')->toHtml()
        ]);
    }

    private function getFilterAttemptsClassStudent(Request $request)
    {
        $quizId = $request->quiz_id;
        $classId = $request->class_id;

        $query = DB::table('users as u')
            ->join('class_student as cs', 'cs.student_id', '=', 'u.id')
            ->join('quiz_attempts as qa', function ($join) use ($quizId) {
                $join->on('qa.user_id', '=', 'u.id')
                    ->where('qa.quiz_id', $quizId)
                    ->whereNull('qa.deleted_at');
            })
            ->where('u.role', 'student')
            ->where('cs.class_id', $classId)
            ->select([
                'u.id as student_id',
                'u.name as student_name',
                DB::raw("DATE_FORMAT(u.birth_date, '%d/%m/%Y') as birthday"),
                'u.gender',
                DB::raw('COUNT(qa.id) as total_attempts'),
                DB::raw('ROUND(AVG(qa.score), 2) as average_score'),
            ])
            ->groupBy('u.id', 'u.name', 'u.birth_date', 'u.gender')
            ->orderBy('u.name');

        // Tên học sinh
        if ($request->filled('keyword')) {
            $query->where('u.name', 'like', '%' . $request->keyword . '%');
        }

        // Giới tính
        if ($request->filled('gender')) {
            $query->where('u.gender', $request->gender);
        }

        // Ngày sinh
        if ($request->filled('birth_date')) {
            $query->whereDate('u.birth_date', $request->birth_date);
        }

        // Điểm trung bình
        if ($request->filled('avg_score')) {
            $query->havingRaw('ROUND(AVG(qa.score), 2) = ?', [$request->avg_score]);
        }

        // Số lượt làm bài
        if ($request->filled('attempts')) {
            $query->havingRaw('COUNT(qa.id) = ?', [$request->attempts]);
        }

        return $query->orderBy('u.name')->paginate($request->limit ?? 10);
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

        $attempts = DB::table('quiz_attempts as qa')
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
            ->orderBy('qa.started_at', 'asc')
            ->paginate(10);
        // dd($attempts);
        if ($request->ajax()) {
            return response()->json([
                'attempts' => $attempts,
                'pagination' => $attempts->links('pagination::bootstrap-5')->toHtml()
            ]);
        }
        return view('admin.quizzes.results.classes-detail-student', compact('quiz', 'class', 'student', 'statistics', 'attempts'));
    }
    public function filterResultsClassStudent(Request $request)
    {
        $attempts = $this->getFilterResultsClassStudent($request);
        $attempts->appends($request->all()); // Giữ lại bộ lọc khi phân trang

        return response()->json([
            'attempts' => $attempts,
            'pagination' => $attempts->links('pagination::bootstrap-5')->toHtml()
        ]);
    }

    private function getFilterResultsClassStudent(Request $request)
    {
        $quizId = $request->quiz_id;
        $studentId = $request->student_id;

        $query = DB::table('quiz_attempts as qa')
            ->select([
                'qa.id as attempt_id',
                'qa.user_id',
                'qa.quiz_id',
                'qa.score',
                'qa.total_correct',
                DB::raw("(
                    SELECT COUNT(*) FROM (
                        SELECT id FROM questions WHERE quiz_id = $quizId
                        UNION ALL
                        SELECT id FROM sentence_questions WHERE quiz_id = $quizId
                    ) as all_questions
                ) as total_questions"),
                DB::raw('TIMESTAMPDIFF(MINUTE, qa.started_at, qa.submitted_at) as duration_minutes'),
                DB::raw("DATE_FORMAT(qa.submitted_at, '%d/%m/%Y') as completed_date")
            ])
            ->where('qa.quiz_id', $quizId)
            ->where('qa.user_id', $studentId)
            ->whereNull('qa.deleted_at');

        // Lọc theo ngày làm bài
        if ($request->filled('completed_at')) {
            $query->whereDate('qa.submitted_at', $request->completed_at);
        }

        // Lọc theo điểm từ (>=)
        if ($request->filled('score')) {
            $query->where('qa.score', '=', $request->score);
        }

        // Lọc theo thời gian làm bài (<=)
        if ($request->filled('max_duration')) {
            $query->whereRaw('TIMESTAMPDIFF(MINUTE, qa.started_at, qa.submitted_at) <= ?', [$request->max_duration]);
        }

        return $query->orderBy('qa.started_at', 'asc')->paginate($request->limit ?? 10);
    }









    public function quizAttemptsStudentAnswer($id, $class, $student, $attemptId)
    {
        $quiz = quizzes::findOrFail($id);
        $class = classes::findOrFail($class);
        $student = DB::table('users')->where('id', $student)->where('role', 'student')->first();

        $attempt = DB::table('quiz_attempts as qa')
            ->select([
                'qa.id as attempt_id',
                'qa.user_id',
                'qa.quiz_id',
                'qa.score',
                'qa.total_correct',
                DB::raw("(
                    SELECT COUNT(*) FROM (
                        SELECT id FROM questions WHERE quiz_id = {$quiz->id}
                        UNION ALL
                        SELECT id FROM sentence_questions WHERE quiz_id = {$quiz->id}
                    ) as all_questions
                ) as total_questions"),
                DB::raw('TIMESTAMPDIFF(MINUTE, qa.started_at, qa.submitted_at) as duration_minutes'),
                DB::raw("DATE_FORMAT(qa.submitted_at, '%d/%m/%Y') as completed_date")
            ])
            ->where('qa.quiz_id', $quiz->id)
            ->where('qa.user_id', $student->id)
            ->where('qa.id', $attemptId)
            ->whereNull('qa.deleted_at')
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
        // dd($studentMcAnswers->all());
        return view('admin.quizzes.results.answer_detail', compact(
            'quiz',
            'class',
            'student',
            'attempt',
            'allQuestions',
            'answers',
            'studentMcAnswers',
            'studentSentenceAnswers'
        ));
    }



    public function getCourse($id)
    {
        $course = courses::join('classes', 'classes.courses_id', '=', 'courses.id')
            ->select('courses.*')
            ->where('classes.id', $id)
            ->first();

        return response()->json($course);
    }
}
