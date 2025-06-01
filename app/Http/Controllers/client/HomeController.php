<?php

namespace App\Http\Controllers\client;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index(){
        // $data = DB::table('san_phams')->select('*')->get();
        return view('client/home');
    }
}
