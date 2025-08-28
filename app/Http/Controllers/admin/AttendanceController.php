<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\classes;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

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
                    ->where('teachers.role', '=', 'teacher');
            })
            ->join('classes', 'schedules.class_id', '=', 'classes.id')
            ->join('courses', 'classes.courses_id', '=', 'courses.id')
            ->leftJoin('class_student', 'schedules.class_id', '=', 'class_student.class_id')
            ->select(
                'classes.id as class_id',
                'classes.name as class_name',
                'schedules.id as schedule_id',
                'schedules.date',
                'schedules.start_time',
                'schedules.end_time',
                'schedules.status',
                'teachers.name as teacher_name',
                'courses.name as courses_name',
                DB::raw('COUNT(class_student.student_id) as student_count')
            )
            ->groupBy(
                'classes.id',
                'classes.name',
                'schedules.id',
                'schedules.date',
                'schedules.start_time',
                'schedules.end_time',
                'schedules.status',
                'teachers.name',
                'courses.name'
            )
            ->get()
            ->map(function ($schedule) {
                return [
                    'title' => $schedule->class_name,
                    'start' => Carbon::parse($schedule->date . ' ' . $schedule->start_time)->toIso8601String(),
                    'end' => $schedule->end_time && $schedule->end_time > $schedule->start_time
                        ? Carbon::parse($schedule->date . ' ' . $schedule->end_time)->toIso8601String()
                        : null,
                    'extendedProps' => [
                        'classId' => $schedule->class_id,
                        'scheduleId' => $schedule->schedule_id,
                        'class_name' => $schedule->class_name,
                        'courses_name' => $schedule->courses_name,
                        'teacher_name' => $schedule->teacher_name,
                        'student_count' => $schedule->student_count,
                        'status' => $schedule->status,
                        'type' => 'class'
                    ]
                ];
            });

        // ✅ TRẢ VỀ JSON CHO FETCH
        return response()->json($schedules);
    }

    // Lấy danh sách lịch học theo lớp
    public function getSchedulesByClass(Request $request, $classId)
    {
        $query = DB::table('schedules')
            ->join('users as teachers', function ($join) {
                $join->on('schedules.teacher_id', '=', 'teachers.id')
                    ->where('teachers.role', '=', 'teacher');
            })
            ->join('classes', 'schedules.class_id', '=', 'classes.id')
            ->join('courses', 'classes.courses_id', '=', 'courses.id')
            ->leftJoin('class_student', 'schedules.class_id', '=', 'class_student.class_id')
            ->select(
                'classes.name as class_name',
                'schedules.id',
                'schedules.date',
                'schedules.start_time',
                'schedules.end_time',
                'schedules.status',
                'teachers.name as teacher_name',
                'courses.name as courses_name',
                DB::raw('COUNT(class_student.student_id) as student_count'),
            )
            ->where('schedules.class_id', $classId)
            ->groupBy(
                'classes.name',
                'schedules.id',
                'schedules.date',
                'schedules.start_time',
                'schedules.end_time',
                'schedules.status',
                'teachers.name',
                'courses.name',
            )
            ->orderBy('schedules.date', 'asc')
            ->orderBy('schedules.start_time', 'asc');

        // Filter theo ngày nếu có
        if ($request->has('date') && $request->date) {
            $query->where('schedules.date', $request->date);
        }

        $schedules = $query->get()
            ->map(function ($schedule) {
                return [
                    'id' => $schedule->id,
                    'date' => $schedule->date,
                    'start_time' => $schedule->start_time,
                    'end_time' => $schedule->end_time,
                    'status' => $schedule->status,
                    'teacher_name' => $schedule->teacher_name,
                    'courses_name' => $schedule->courses_name,
                    'student_count' => $schedule->student_count,
                    'formatted_date' => Carbon::parse($schedule->date)->format('d/m/Y'),
                    'formatted_start_time' => Carbon::parse($schedule->start_time)->format('H:i'),
                    'formatted_end_time' => Carbon::parse($schedule->end_time)->format('H:i'),
                ];
            });

        return response()->json($schedules);
    }

    // Lấy thông tin chi tiết lớp
    public function getClassDetail($classId)
    {
        $classDetail = DB::table('classes')
            ->join('courses', 'classes.courses_id', '=', 'courses.id')
            ->leftJoin('class_student', 'classes.id', '=', 'class_student.class_id')
            ->select(
                'classes.id',
                'classes.name as class_name',
                'courses.name as course_name',
                DB::raw('COUNT(class_student.student_id) as total_students')
            )
            ->where('classes.id', $classId)
            ->groupBy('classes.id', 'classes.name', 'courses.name')
            ->first();

        return response()->json($classDetail);
    }

    public function attendanceClass($id)
    {
        // Lấy thông tin lịch học
        $scheduleData = DB::table('schedules')
            ->where('schedules.id', $id)
            ->leftJoin('users as teachers', 'schedules.teacher_id', '=', 'teachers.id')
            ->leftJoin('users as support_teachers', 'schedules.support_teacher', '=', 'support_teachers.id')
            ->leftJoin('classes', 'schedules.class_id', '=', 'classes.id')
            ->leftJoin('courses', 'classes.courses_id', '=', 'courses.id') // Thêm join với bảng courses
            ->select(
                'schedules.*',
                'teachers.name as teacher_name',
                'teachers.phone as teacher_phone',
                'support_teachers.name as support_teacher_name',
                'support_teachers.phone as support_teacher_phone',
                'classes.name as class_name',
                'courses.name as course_name' // Lấy tên môn học từ bảng courses
            )
            ->first();

        if (!$scheduleData) {
            abort(404, 'Lịch học không tồn tại.'); // Hoặc xử lý trường hợp không tìm thấy lịch học
        }

        // Lấy thông tin lớp học và học sinh (nếu chưa có trong $scheduleData)
        $students = collect(); // Khởi tạo mặc định
        $class = null;
        $studentUserIds = [];
        if (!isset($scheduleData->class_id)) {
            $classStudents = collect();
        } else {
            $class = DB::table('classes')
                ->where('id', $scheduleData->class_id)
                ->first();

            // Lấy danh sách học sinh từ class_student
            $studentUserIdsFromClass = DB::table('class_student')
                ->where('class_id', $scheduleData->class_id)
                ->whereNull('deleted_at')
                ->pluck('student_id')
                ->toArray();

            // Lấy thông tin học sinh từ bảng users
            $students = DB::table('users')
                ->whereIn('id', $studentUserIdsFromClass)
                ->get();
        }

        // Lấy trạng thái điểm danh của học sinh cho lịch học này
        $attendance = DB::table('attendances')
            ->where('schedule_id', $id)
            ->get()
            ->keyBy('user_id')
            ->map(function ($item) {
                return [
                    'status' => $item->status,
                    'note' => $item->note,
                ];
            });

        // Truyền dữ liệu vào view
        return view('admin.attendance.attendance-class', compact('scheduleData', 'students', 'attendance'));
    }
    public function updateSummary(Request $request)
    {
        try {
            // Validate input
            $request->validate([
                'scheduleId' => 'required|integer'
            ]);

            $scheduleId = $request->input('scheduleId');

            // Đếm tổng số học sinh nên có mặt (dựa trên bảng schedules hoặc student_schedules)
            $totalStudents = DB::table('attendances')
                ->where('schedule_id', $scheduleId)
                ->count();


            // Query để đếm số lượng theo từng status
            $summary = DB::table('attendances')
                ->select(
                    DB::raw('SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present_count'),
                    DB::raw('SUM(CASE WHEN status = "absent" THEN 1 ELSE 0 END) as absent_count'),
                    DB::raw('COUNT(*) as total_attended')
                )
                ->where('schedule_id', $scheduleId)
                ->first();

            // Tính số học sinh chưa điểm danh
            $notAttended = $totalStudents - ($summary->total_attended ?? 0);

            // Chuẩn bị dữ liệu trả về
            $data = [
                'present' => (int) ($summary->present_count ?? 0),
                'absent' => (int) ($summary->absent_count ?? 0),
                'undone' => (int) $notAttended,
                'total' => (int) ($summary->total_count ?? 0)
            ];

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Attendance summary retrieved successfully'
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function saveAttendance(Request $request)
    {
        // Ghi log toàn bộ dữ liệu đầu vào để debug
        Log::info('Attendance Request Data:', [
            'input' => $request->all(),
            'ip' => $request->ip(),
            'timestamp' => now()->toDateTimeString()
        ]);
        try {
            // Validate dữ liệu đầu vào
            $request->validate([
                'schedule_id' => 'required|exists:schedules,id',
                'attendance_data' => 'required|array',
                'attendance_data.*.student_id' => 'required|exists:users,id',
                'attendance_data.*.status' => 'required|in:present,absent,undone',
                'attendance_data.*.note' => 'nullable|string|max:500'
            ]);
            $scheduleId = $request->schedule_id;
            $attendanceData = $request->attendance_data;

            // Bắt đầu transaction
            DB::beginTransaction();

            $schedule = DB::table('schedules')->where('id', $scheduleId)->first();
            $laDiemDanhDauTien = $schedule->status == 0;
            Log::info('Schedule data: ', (array) $schedule);

            // Xóa dữ liệu điểm danh cũ (nếu có) để cập nhật lại
            Attendance::where('schedule_id', $scheduleId)->delete();

            // Lưu từng bản ghi điểm danh
            foreach ($attendanceData as $data) {
                Attendance::create([
                    'schedule_id' => $scheduleId,
                    'user_id' => $data['student_id'],
                    'status' => $data['status'],
                    'note' => $data['note'] ?? null,
                    'date' => now()->toDateString(),
                    // 'created_by' => auth()->id(),
                ]);
            }

            // Nếu là lần điểm danh đầu tiên, cập nhật status của lịch học thành 1
            if ($laDiemDanhDauTien) {
                DB::table('schedules')->where('id', $scheduleId)->update(['status' => 1]);

                // Lấy class_id từ lịch học
                $classId = $schedule->class_id;

                // Tăng số buổi đã học lên 1
                DB::table('classes')
                    ->where('id', $classId)
                    ->increment('number_of_sessions');
            }

            $class = classes::find($classId);

            $this->logAction(
                'update',
                Attendance::class,
                $scheduleId,
                Auth::user()->name . ' đã lưu điểm danh lớp: ' .$class->name
            );

            // Commit transaction
            DB::commit();


            return response()->json([
                'success' => true,
                'message' => 'Điểm danh đã được lưu thành công',
                'data' => [
                    'schedule_id' => $scheduleId,
                    'total_records' => count($attendanceData),
                    'saved_at' => now()->format('Y-m-d H:i:s')
                ]
            ]);
        } catch (ValidationException $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Lỗi khi lưu điểm danh: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi lưu điểm danh. Vui lòng thử lại.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
