<?php

namespace App\Http\Controllers\client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\classes;
use App\Models\coursePayment;
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
        $userId = Auth::user()->id;

        if (Auth::user()->role == "student") {
            $schedules = DB::table('schedules')
                ->join('class_student', 'schedules.class_id', '=', 'class_student.class_id')
                ->join('classes', 'schedules.class_id', '=', 'classes.id')
                ->join('courses', 'classes.courses_id', '=', 'courses.id')
                ->join('users', 'schedules.teacher_id', '=', 'users.id')
                ->where('class_student.student_id', $userId)
                ->select(
                    DB::raw('CASE schedules.day_of_week
                        WHEN "Mon" THEN "Thứ 2"
                        WHEN "Tue" THEN "Thứ 3"
                        WHEN "Wed" THEN "Thứ 4"
                        WHEN "Thu" THEN "Thứ 5"
                        WHEN "Fri" THEN "Thứ 6"
                        WHEN "Sat" THEN "Thứ 7"
                        WHEN "Sun" THEN "Chủ nhật"
                    END as day_of_week'),
                    'schedules.date',
                    'schedules.start_time',
                    'schedules.end_time',
                    'courses.name as course_name',
                    'users.name as teacher_name'
                )
                ->paginate(10);

            // Trả về view cho học sinh với dữ liệu lịch học
            return view('client.accounts.students.schedule', [
                'schedules' => $schedules
            ]);
        } elseif (Auth::user()->role == "teacher") {
            $schedules = DB::table('schedules')
                ->join('classes', 'schedules.class_id', '=', 'classes.id')
                ->join('courses', 'classes.courses_id', '=', 'courses.id')
                ->join('users', 'schedules.teacher_id', '=', 'users.id')
                ->where('schedules.teacher_id', $userId)
                ->select(
                    DB::raw('CASE schedules.day_of_week
                        WHEN "Mon" THEN "Thứ 2"
                        WHEN "Tue" THEN "Thứ 3"
                        WHEN "Wed" THEN "Thứ 4"
                        WHEN "Thu" THEN "Thứ 5"
                        WHEN "Fri" THEN "Thứ 6"
                        WHEN "Sat" THEN "Thứ 7"
                        WHEN "Sun" THEN "Chủ nhật"
                    END as day_of_week'),   
                    'schedules.id as schedule_id',
                    'schedules.date',
                    'schedules.start_time',
                    'schedules.end_time',
                    'courses.name as course_name',
                    'users.name as teacher_name'
                )
                ->paginate(10);

            return view('client.accounts.teachers.schedule', [
                'schedules' => $schedules
            ]);
        } else {
            // Xử lý trường hợp vai trò không hợp lệ
            return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập lịch học.');
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
                    DB::raw('COUNT(DISTINCT ques.id) + COUNT(DISTINCT sq.id) as total_questions')
                )
                ->orderBy('q.updated_at', 'desc')
                ->get();

            return view('client.accounts.students.quizz', compact('quizzesDone', 'assignedQuizzes'));
        } elseif (Auth::user()->role == "teacher") {

            return view('client.accounts.teachers.quizz');
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
