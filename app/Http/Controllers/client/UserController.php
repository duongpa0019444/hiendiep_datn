<?php

namespace App\Http\Controllers\client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function dashBoard(){
        return view('client.home');
    }

    public function myAccount(){
        return view('client.myAccount.index');
    }
}
