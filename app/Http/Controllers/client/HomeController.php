<?php

namespace App\Http\Controllers\client;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\courses;


use App\Http\Controllers\Controller;
use App\Models\news;

class HomeController extends Controller
{
    public function index()
    {
        $featuredCourses = DB::select("
            SELECT
                co.id AS course_id,
                co.name AS course_name,
                co.image,
                co.price,
                co.total_sessions,
                (
                    SELECT u.name
                    FROM users u
                    JOIN schedules s ON s.teacher_id = u.id
                    JOIN classes c2 ON c2.id = s.class_id
                    WHERE c2.courses_id = co.id
                    LIMIT 1
                ) AS teacher_name,
                COUNT(DISTINCT cs.student_id) AS total_students,
                COUNT(DISTINCT l.id) AS total_lessons
            FROM
                courses co
            LEFT JOIN classes c ON c.courses_id = co.id AND c.deleted_at IS NULL
            LEFT JOIN class_student cs ON cs.class_id = c.id AND cs.deleted_at IS NULL
            LEFT JOIN lessons l ON l.course_id = co.id
            WHERE
                co.is_featured = 1
            GROUP BY
                co.id, co.name, co.image, co.price, co.total_sessions
            ORDER BY
                co.created_at DESC
            LIMIT 3;

        ");
        // dd($featuredCourses);

        $news = news::where('show_on_homepage', 1)
            ->where('event_type', 'news')
            ->where('publish_status', 'published')
            ->where('is_visible', 1)
            ->orderBy('created_at', 'desc')
            ->get();

        $events = news::where('show_on_homepage', 1)
            ->where('event_type', 'event')
            ->where('publish_status', 'published')
            ->where('is_visible', 1)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('client.home', compact('featuredCourses', 'news', 'events'));
    }

    public function about()
    {
        return view('client.about');
    }
}
