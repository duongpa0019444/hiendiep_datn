<?php

namespace App\Http\Controllers\client;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Models\courses;

class CourseController extends Controller
{
    public function index(){
        $data = courses::with('lessons', 'students')->get();
        return view('client.course', compact('data'));
    }
}
