<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\classes;
use App\Models\orders;
use Illuminate\Http\Request;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\DB;

class dashboardController extends Controller
{
    //
    public function dashBoard(HttpRequest $request)
    {
        $users = DB::select("
                    SELECT
                COUNT(*) AS total_users,
                SUM(CASE WHEN role = 'student' THEN 1 ELSE 0 END) AS total_students,
                SUM(CASE WHEN role = 'teacher' THEN 1 ELSE 0 END) AS total_teachers,
                SUM(CASE WHEN role = 'staff' THEN 1 ELSE 0 END) AS total_staff,
                SUM(CASE WHEN role = 'admin' THEN 1 ELSE 0 END) AS total_admins
            FROM `users`;");

        $classs = DB::select("
                    SELECT
            (SELECT COUNT(*) FROM `classes`) AS total_classes,
            (SELECT COUNT(*) FROM `classes` WHERE status = 'in_progress') AS total_classes_in_progress,
            (SELECT COUNT(*) FROM `classes` WHERE status = 'completed') AS total_classes_completed,
            (SELECT COUNT(*) FROM `classes` WHERE status = 'not_started') AS total_classes_unstarted;
        ");


        $countPayments = DB::select("
                        SELECT
            (SELECT COALESCE(SUM(amount), 0)
            FROM `course_payments`
            WHERE `status` = 'paid') AS total_revenue,

            (SELECT COUNT(*)
            FROM `course_payments`
            WHERE `status` = 'paid') AS total_paid_records,

            (SELECT COUNT(*)
            FROM `course_payments`
            WHERE `status` = 'unpaid') AS total_unpaid_records;

        ");

        $courses = DB::table('courses')->get();

        return view('admin.dashdoard', compact('users', 'classs', 'countPayments', 'courses'));
    }



    public function chart($id)
    {
        $course_id = $id ? $id : 0; // Mặc định là 0 (Tất cả khóa học)
        $classes_informations = classes::query()
            ->select('classes.name as class_name', 'classes.id')
            ->selectRaw('COALESCE((SELECT COUNT(DISTINCT cs.student_id) FROM class_student cs WHERE cs.class_id = classes.id AND EXISTS (SELECT 1 FROM course_payments cp WHERE cp.student_id = cs.student_id AND cp.class_id = cs.class_id AND cp.status = \'paid\')), 0) as paid_students')
            ->selectRaw('COALESCE((SELECT COUNT(DISTINCT cs.student_id) FROM class_student cs WHERE cs.class_id = classes.id AND NOT EXISTS (SELECT 1 FROM course_payments cp WHERE cp.student_id = cs.student_id AND cp.class_id = cs.class_id AND cp.status = \'paid\')), 0) as unpaid_students')
            ->where('status', '=', 'in_progress')
            ->when($course_id != 0, function ($query) use ($course_id) {
                return $query->where('courses_id', $course_id);
            })
            ->orderBy('name')
            ->paginate(10);

        return response()->json([
            'data' => $classes_informations,
            'pagination' => $classes_informations->links('pagination::bootstrap-5')->render()

        ]);
    }
}
