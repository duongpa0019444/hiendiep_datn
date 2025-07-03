<?php

namespace App\Http\Controllers\client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\classes;
use App\Models\coursePayment;
use Database\Seeders\user;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function information()
    {
        $userId = Auth::user()->id;
        $unPaymentInfo = coursePayment::where('student_id', $userId)
            ->where('status', 'unpaid')
            ->get();
        return view('client.accounts.students.dashboard', compact('unPaymentInfo'));
    }

    public function schedule()
    {
        return view('client.accounts.students.schedule');
    }

    public function score()
    {
        return view('client.accounts.students.score');
    }

    public function quizz()
    {
        $studentId =  Auth::user()->id;; // hoặc truyền qua route/request
        $quizzesDone = DB::table('quiz_attempts')
            ->join('quizzes', 'quiz_attempts.quiz_id', '=', 'quizzes.id')
            ->leftJoin('users as u', 'quizzes.created_by', '=', 'u.id')
            ->where('quiz_attempts.user_id', $studentId)
            ->groupBy(
                'quizzes.id',
                'quizzes.title',
                'quizzes.status',
                'quizzes.class_id',
                'quizzes.created_by',
                'quizzes.updated_at',
                'u.name'
            )
            ->select(
                'quizzes.id',
                'quizzes.title',
                'quizzes.status',
                'quizzes.class_id',
                'quizzes.created_by',
                'quizzes.updated_at',
                'u.name as creator_name',
                DB::raw('COUNT(quiz_attempts.id) as attempt_count'),
                DB::raw('MAX(quiz_attempts.submitted_at) as last_submitted_at'),
                DB::raw('AVG(quiz_attempts.score) as avg_score')
            )
            ->orderBy('quizzes.updated_at', 'desc')
            ->get();


        $assignedQuizzes = DB::table('class_student as cs')
            ->join('classes as c', 'cs.class_id', '=', 'c.id')
            ->join('quizzes as q', 'q.class_id', '=', 'c.id')
            ->leftJoin('questions as ques', 'q.id', '=', 'ques.quiz_id')
            ->leftJoin('sentence_questions as sq', 'q.id', '=', 'sq.quiz_id')
            ->leftJoin('users as u', 'q.created_by', '=', 'u.id')
            ->where('cs.student_id', $studentId)
            ->where('q.status', 'published')
            ->groupBy(
                'q.id',
                'q.title',
                'q.status',
                'q.class_id',
                'q.created_by',
                'q.updated_at',
                'c.name',
                'u.name'
            )
            ->select(
                'q.id',
                'q.title',
                'q.status',
                'q.class_id',
                'q.created_by',
                'q.updated_at',
                'c.name as class_name',
                'u.name as creator_name',
                DB::raw('COUNT(DISTINCT ques.id) + COUNT(DISTINCT sq.id) as total_questions')
            )
            ->orderBy('q.updated_at', 'desc')
            ->get();

        return view('client.accounts.students.quizz', compact('quizzesDone', 'assignedQuizzes'));
    }


    public function account()
    {
        return view('client.accounts.students.account');
    }
}
