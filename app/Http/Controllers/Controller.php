<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

abstract class Controller
{
    //
    function logAction($action, $modelType = null, $modelId = null, $description = null)
    {
        $request = \Illuminate\Http\Request::capture();
        $ip = $request->header('CF-Connecting-IP')
        ?? trim(explode(',', $request->header('X-Forwarded-For'))[0] ?? '')
        ?? $request->ip();

        DB::table('action_logs')->insert([
            'user_id' => Auth::check() ? Auth::id() : null,
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'description' => $description,
            'ip_address' => Request::ip(),
            'user_agent' => Request::header('User-Agent'),
            'created_at' => now(),
        ]);

        // answers::class;
        // Attendance::class;
        // classes::class;
        // contact::class;
        // coursePayment::class;
        // courses::class;
        // lessons::class;
        // news::class;
        // notification::class;
        // notificationCoursePayments::class;
        // Quizzes::class;
        // Schedule::class;
        // score::class;
        // teacher_salaries::class;
        // teacher_salary_rules::class;
        // topics::class;
        // user::class;


        // App\Models\answers
        // App\Models\Attendance
        // App\Models\classes
        // App\Models\contact
        // App\Models\coursePayment
        // App\Models\courses
        // App\Models\lessons
        // App\Models\news
        // App\Models\notification
        // App\Models\notificationCoursePayments
        // App\Models\Quizzes
        // App\Models\Schedule
        // App\Models\score
        // App\Models\teacher_salaries
        // App\Models\teacher_salary_rules
        // App\Models\topics
        // App\Models\user as User

    }
}
