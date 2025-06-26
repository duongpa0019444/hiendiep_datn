<?php

namespace App\Http\Controllers\client;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\courses;


use App\Http\Controllers\Controller;

class HomeController extends Controller
{public function index()
{
    $featuredCourses = courses::where('is_featured', 1)->latest()->limit(6)->get(); 
    

    return view('client.home', compact('featuredCourses'));
}
}

