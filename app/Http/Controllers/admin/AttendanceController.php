<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index()
{
    // Lấy số lượng lớp học trong ngày hôm nay
    $todayClassCount = Schedule::whereDate('date', Carbon::today())
        ->distinct('class_id')
        ->count('class_id');

    // Tỷ lệ điểm danh
    // Lấy tất cả các bản ghi schedule hôm nay
    $todaySchedules = Schedule::whereDate('date', Carbon::today())->get();
    // Tổng số bản ghi (bao gồm tất cả status)
    $total = $todaySchedules->count();
    // Số bản ghi có mặt (present)
    $present = $todaySchedules->where('status', 1)->count();
    // Tính tỷ lệ điểm danh hôm nay
    $attendanceRate = $total > 0 ? round(($present / $total) * 100, 2) : 0;

    // Lớp cần điểm danh
    $classesNeedAttendance = $todaySchedules->where('status', 0)->count();

    return view('admin.attendance.index', compact('todayClassCount', 'attendanceRate', 'classesNeedAttendance'));
}
    // Điểm danh
    public function getSchedules(Request $request)
    {
        $schedules = DB::table('schedules')
            ->join('users as teachers', function ($join) {
                $join->on('schedules.teacher_id', '=', 'teachers.id')
                    ->where('teachers.role', '=', 'teacher'); // hoặc số
            })
            ->join('classes', 'schedules.class_id', '=', 'classes.id')
            ->join('courses', 'classes.courses_id', '=', 'courses.id')
            ->leftJoin('class_student', 'schedules.class_id', '=', 'class_student.class_id')
            ->select(
                // 'classes.id as class_id',
                'classes.name as class_name',
                'schedules.id',
                'schedules.date',
                'schedules.start_time',
                'schedules.end_time',
                'schedules.status',
                // 'schedules.type',
                'teachers.name as teacher_name',
                'courses.name as courses_name',
                // 'schedules.room',
                DB::raw('COUNT(class_student.student_id) as student_count'),
                // 'schedules.attended',
                // 'schedules.exam_type'
            )
            ->groupBy(
                // 'classes.id',
                'classes.name',
                'schedules.id',
                'schedules.date',
                'schedules.start_time',
                'schedules.end_time',
                'schedules.status',
                'teachers.name',
                'courses.name',
                // 'schedules.attended',
                // 'schedules.exam_type'
            )
            ->get()
            ->map(function ($schedule) {
                return [
                    'title' => $schedule->class_name,
                    'start' => Carbon::parse($schedule->date . ' ' . $schedule->start_time)->toIso8601String(),
                    'end' => Carbon::parse($schedule->date . ' ' . $schedule->end_time)->toIso8601String(),
                    'extendedProps' => [
                        // 'type' => $schedule->type,
                        'scheduleId' => $schedule->id,
                        'teacher' => $schedule->teacher_name,
                        'courses' => $schedule->courses_name,
                        // 'room' => $schedule->room,
                        'students' => $schedule->student_count,
                        'status' => $schedule->status,
                        // 'attended' => $schedule->attended,
                        // 'examType' => $schedule->exam_type,
                    ]
                ];
            });

        // ✅ TRẢ VỀ JSON CHO FETCH
        return response()->json($schedules);
    }

    public function attendanceClass($id)
    {

        return view('admin.attendance.attendance-class');
    }
}
