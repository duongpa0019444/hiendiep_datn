<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use App\Models\answers;
use App\Models\classes;
use App\Models\questions;
use App\Models\quizAttempts;
use App\Models\Quizzes;
use App\Models\sentenceQuestions;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class quizzesController extends Controller
{

    public function start(){
        return view('client.quizzes.start-quiz');
    }

    public function showResult($quizId){
        $quizAttempts = quizAttempts::where('quiz_id', $quizId)->where('user_id', Auth::user()->id)->get();
        return response()->json($quizAttempts);
    }

    public function resultsQuizzStudent($id, $attemptId){
        $quiz = quizzes::findOrFail($id);
        $student = DB::table('users')->where('id', Auth::user()->id)->where('role', 'student')->first();

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

    public function checkAccessCode($code){
        $quiz = quizzes::where('access_code', $code)->where('staus', 'published')->where('is_public', 1)->first();

    }

}
