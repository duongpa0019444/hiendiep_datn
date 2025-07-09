<?php

namespace App\Http\Controllers\client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\coursePayment;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function information(){
        $userId = Auth::user()->id;
        $unPaymentInfo = coursePayment::where('student_id', $userId)
            ->where('status', 'unpaid')
            ->get();
        return view('client.accounts.students.dashboard', compact('unPaymentInfo'));
    }

    public function schedule(){
        return view('client.accounts.students.schedule');
    }

    public function score(){
        return view('client.accounts.students.score');
    }

    public function quizz(){
        return view('client.accounts.students.quizz');
    }

    public function account(){
        return view('client.accounts.students.account');

    }
   

}
