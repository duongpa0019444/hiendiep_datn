<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\classes;
use App\Models\NotificationUser;
use App\Models\orders;
use Illuminate\Http\Request;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
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

        //lấy ngày hôm nay
        $date = Carbon::today();

        // Định dạng lại để gán vào input
        $formattedDate = $date->format('d/m/Y');

        // Lấy lịch học theo ngày
        $schedules = DB::table('schedules as s')
            ->join('classes as c', 's.class_id', '=', 'c.id')
            ->join('courses as co', 'c.courses_id', '=', 'co.id')
            ->join('users as u', 's.teacher_id', '=', 'u.id')
            ->select([
                's.id as schedule_id',
                's.status as schedule_status',
                'c.name as class_name',
                'co.name as course_name',
                'u.name as teacher_name',
                DB::raw("CONCAT(s.start_time, ' - ', s.end_time) as time_range"),
                DB::raw("
                    CASE
                        WHEN s.date > CURDATE() THEN 'Chưa học'
                        WHEN s.date = CURDATE() AND NOW() < s.start_time THEN 'Chưa học'
                        WHEN s.date = CURDATE() AND NOW() BETWEEN s.start_time AND s.end_time THEN 'Đang học'
                        WHEN s.date < CURDATE() OR (s.date = CURDATE() AND NOW() > s.end_time) THEN 'Đã kết thúc'
                        ELSE 'Không xác định'
                    END as status_label
                ")
            ])
            ->whereDate('s.date', $date)
            ->orderBy('s.start_time')
            ->get();

        $courses = DB::table('courses')->get();

        return view('admin.dashdoard', compact('users', 'classs', 'countPayments', 'courses', 'schedules', 'formattedDate'));
    }



    public function chart($id)
    {
        $course_id = $id ? $id : 0; // Mặc định là 0 (Tất cả khóa học)
        $classes_informations = classes::query()
            ->select('classes.name as class_name', 'classes.id')
            ->selectRaw('COALESCE((SELECT COUNT(DISTINCT cs.student_id) FROM class_student cs WHERE cs.class_id = classes.id AND EXISTS (SELECT 1 FROM course_payments cp WHERE cp.student_id = cs.student_id AND cp.class_id = cs.class_id AND cp.status = \'paid\')), 0) as paid_students')
            ->selectRaw('COALESCE((SELECT COUNT(DISTINCT cs.student_id) FROM class_student cs WHERE cs.class_id = classes.id AND NOT EXISTS (SELECT 1 FROM course_payments cp WHERE cp.student_id = cs.student_id AND cp.class_id = cs.class_id AND cp.status = \'paid\')), 0) as unpaid_students')
            ->where(function ($query) {
                $query->where('status', 'in_progress')
                    ->orWhere('status', 'not_started');
            })
            ->when($course_id != 0, function ($query) use ($course_id) {
                return $query->where('courses_id', $course_id);
            })
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('class_student as cs')
                    ->whereColumn('cs.class_id', 'classes.id');
            })
            ->orderBy('name')
            ->paginate(6);


        return response()->json([
            'data' => $classes_informations,
            'pagination' => $classes_informations->links('pagination::bootstrap-5')->render()

        ]);
    }



    public function getSchedulesViews($id)
    {


        // Lấy lịch học theo ngày
        $schedule = DB::table('schedules as s')
            ->join('classes as c', 's.class_id', '=', 'c.id')
            ->join('courses as co', 'c.courses_id', '=', 'co.id')
            ->join('users as u', 's.teacher_id', '=', 'u.id')
            ->select([
                's.id as schedule_id',
                'c.name as class_name',
                's.status as schedule_status',
                'co.name as course_name',
                'u.name as teacher_name',
                DB::raw("CONCAT(s.start_time, ' - ', s.end_time) as time_range"),
                DB::raw("
                    CASE
                        WHEN s.date > CURDATE() THEN 'Chưa học'
                        WHEN s.date = CURDATE() AND NOW() < s.start_time THEN 'Chưa học'
                        WHEN s.date = CURDATE() AND NOW() BETWEEN s.start_time AND s.end_time THEN 'Đang học'
                        WHEN s.date < CURDATE() OR (s.date = CURDATE() AND NOW() > s.end_time) THEN 'Đã kết thúc'
                        ELSE 'Không xác định'
                    END as status_label
                ")
            ])
            ->where('s.id', $id)
            ->orderBy('s.start_time')
            ->get();

        //Lấy điểm danh của lịch học
        $attendance = DB::table('attendances')
            ->join('users', 'attendances.user_id', '=', 'users.id')
            ->select([
                'attendances.id as attendance_id',
                'users.name as student_name',
                'attendances.status as attendance_status',
                'attendances.created_at as attendance_time',
                'attendances.note as attendance_note'
            ])
            ->where('schedule_id', $id)
            ->get();

        return response()->json([
            'schedule' => $schedule[0],
            'attendances' => $attendance
        ]);
    }



    public function getSchedulesByDate($date)
    {
        $carbonDate = Carbon::parse($date); // Ép kiểu string -> Carbon

        $schedule = DB::table('schedules as s')
            ->join('classes as c', 's.class_id', '=', 'c.id')
            ->join('courses as co', 'c.courses_id', '=', 'co.id')
            ->join('users as u', 's.teacher_id', '=', 'u.id')
            ->select([
                's.id as schedule_id',
                'c.name as class_name',
                's.status as schedule_status',
                'co.name as course_name',
                'u.name as teacher_name',
                DB::raw("CONCAT(s.start_time, ' - ', s.end_time) as time_range"),
                DB::raw("
                    CASE
                        WHEN s.date > CURDATE() THEN 'Chưa học'
                        WHEN s.date = CURDATE() AND NOW() < s.start_time THEN 'Chưa học'
                        WHEN s.date = CURDATE() AND NOW() BETWEEN s.start_time AND s.end_time THEN 'Đang học'
                        WHEN s.date < CURDATE() OR (s.date = CURDATE() AND NOW() > s.end_time) THEN 'Đã kết thúc'
                        ELSE 'Không xác định'
                    END as status_label
                ")
            ])
            ->whereDate('s.date', $date)
            ->orderBy('s.start_time')
            ->get();

        return response()->json([
            'schedules' => $schedule,
            'formattedDate' => $carbonDate->format('d/m/Y')
        ]);
    }






    //Hàm lấy doanh thu và học sinh
    public function chartRevenue($year)
    {

        $RevenueStudent = DB::select("
            SELECT
                ? AS year,
                m.month,
                COALESCE(SUM(cp.amount), 0) AS total_revenue,
                COALESCE(COUNT(DISTINCT cp.student_id), 0) AS total_students
            FROM (
                SELECT 1 AS month UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6
                UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL SELECT 12
            ) AS m
            LEFT JOIN course_payments cp
                ON MONTH(cp.payment_date) = m.month
                AND YEAR(cp.payment_date) = ?
                AND cp.status = 'paid'
            GROUP BY m.month
            ORDER BY m.month
        ", [$year, $year]);

        $revenues = [];
        $students = [];
        foreach ($RevenueStudent as $value) {
            $revenues[] = $value->total_revenue;
            $students[] = $value->total_students;
        }

        return response()->json([
            'revenues' => $revenues,
            'students' => $students
        ]);
    }


    public function chartRevenueCourse($year)
    {
        $data = DB::select("
            SELECT
                YEAR(cp.payment_date) AS year,
                c.id AS course_id,
                c.name AS course_name,
                COALESCE(SUM(cp.amount), 0) AS total_revenue
            FROM
                courses c
            LEFT JOIN
                course_payments cp ON c.id = cp.course_id
                AND cp.status = 'paid'
                AND cp.payment_date IS NOT NULL
                AND YEAR(cp.payment_date) = ?
            GROUP BY
                YEAR(cp.payment_date),
                c.id,
                c.name
            ORDER BY
                year,
                c.id
        ", [$year]);

        $revenues = [];
        $courses = [];
        foreach ($data as $value) {
            $revenues[] = (int) $value->total_revenue;;
            $courses[] = $value->course_name;
        }


        return response()->json([
            'revenues' => $revenues,
            'courses' => $courses
        ]);
    }

}
