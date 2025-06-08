<?php

namespace App\Http\Controllers\client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function information(){
        return view('client.accounts.students.dashboard');
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

    public function myAccount(){
        return view('client.myAccount.index');
    }
}
