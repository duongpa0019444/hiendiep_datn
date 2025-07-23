<?php

namespace App\Http\Controllers\client;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Models\courses;

class CourseController extends Controller
{
    public function index()
    {
        $data = courses::with('lessons', 'classes')->paginate(9);
        return view('client.course', compact('data'));
    }

    public function detail($slug, $id)
    {
        // dd($slug, $id);
        $course = courses::findOrFail($id);
        return view('client.course-detail', compact('course'));
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('client_course_search');

        $data = courses::where('name', 'like', '%' . $searchTerm . '%')
            ->orWhere('description', 'like', '%' . $searchTerm . '%')
            ->with('lessons', 'students')
            ->paginate(9) // số item/trang
            ->appends(['client_course_search' => $searchTerm]); // giữ query khi phân trang

        return view('client.course', compact('data', 'searchTerm'));
    }
     public function contact(){
        
        return view('client.contact');
    }
}
