<?php

namespace App\Http\Controllers\client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\classes;
use App\Models\coursePayment;
use App\Models\Quizzes;
use Database\Seeders\user;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;

use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function information()
    {
        if (Auth::user()->role == "student") {
            $userId = Auth::user()->id;
            $unPaymentInfo = coursePayment::where('student_id', $userId)
                ->where('status', 'unpaid')
                ->get();
            return view('client.accounts.students.dashboard', compact('unPaymentInfo'));
        } elseif (Auth::user()->role == "teacher") {

            return view('client.accounts.teachers.dashboard');
        }
    }

    public function schedule()
    {
        if (Auth::user()->role == "student") {
            return view('client.accounts.students.schedule');
        } elseif (Auth::user()->role == "teacher") {
            return view('client.accounts.teachers.schedule');
        }
    }

    public function score()
    {
        if (Auth::user()->role == "student") {
            return view('client.accounts.students.score');
        } elseif (Auth::user()->role == "teacher") {

            return view('client.accounts.teachers.score');
        }
    }

    public function quizz()
    {
        if (Auth::user()->role == "student") {
            $studentId =  Auth::user()->id;
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
                    DB::raw('COUNT(DISTINCT ques.id) + COUNT(DISTINCT sq.id) as `total_questions`')
                )
                ->orderBy('q.updated_at', 'desc')
                ->get();

            return view('client.accounts.students.quizz', compact('quizzesDone', 'assignedQuizzes'));
        } elseif (Auth::user()->role == "teacher") {

            $queryBase = DB::table('quizzes as q')
                ->leftJoin('classes as c', 'q.class_id', '=', 'c.id')
                ->leftJoin('users as u', 'q.created_by', '=', 'u.id')
                ->leftJoin('questions as ques', 'q.id', '=', 'ques.quiz_id')
                ->leftJoin('sentence_questions as sq', 'q.id', '=', 'sq.quiz_id')
                ->select(
                    'q.id',
                    'q.title',
                    'q.status',
                    'q.class_id',
                    'q.duration_minutes',
                    'q.created_by',
                    'q.updated_at',
                    'q.deleted_at',
                    'c.name as class_name',
                    'u.name as creator_name',
                    DB::raw('COUNT(DISTINCT ques.id) + COUNT(DISTINCT sq.id) as total_questions')
                )
                ->where('q.created_by', Auth::user()->id)
                ->groupBy(
                    'q.id',
                    'q.title',
                    'q.status',
                    'q.class_id',
                    'q.duration_minutes',
                    'q.created_by',
                    'q.updated_at',
                    'q.deleted_at',
                    'c.name',
                    'u.name'
                );

            // Lấy tất cả quiz
            $quizzesAll = (clone $queryBase)->whereNull('q.deleted_at')->orderBy('q.updated_at', 'desc')->get();

            // Lấy quiz đã xuất bản
            $quizzesPublished = (clone $queryBase)
                ->where('q.status', 'published')
                ->whereNull('q.deleted_at')
                ->orderBy('q.updated_at', 'desc')
                ->get();

            // Lấy quiz lưu nháp
            $quizzesDraft = (clone $queryBase)
                ->where('q.status', 'draft')
                ->whereNull('q.deleted_at')
                ->orderBy('q.updated_at', 'desc')
                ->get();

            // Lấy quiz đã xóa
            $quizzesTrashed = (clone $queryBase)
                ->whereNotNull('q.deleted_at')
                ->orderBy('q.updated_at', 'asc')
                ->get();

            return view('client.accounts.teachers.quizz', compact('quizzesAll', 'quizzesPublished', 'quizzesDraft', 'quizzesTrashed'));
        }
    }


    public function account()
    {
        if (Auth::user()->role == "student") {
            return view('client.accounts.students.account');
        } elseif (Auth::user()->role == "teacher") {

            return view('client.accounts.teachers.account');
        }
    }
}
