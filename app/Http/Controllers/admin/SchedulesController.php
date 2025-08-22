<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\classes;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SchedulesController extends Controller
{
    public function index(Request $request)
    {
        $query = classes::select(
            'classes.id',
            'classes.name',
            'classes.number_of_sessions as so_buoi_hoc',
            'classes.status',
            'classes.created_at as start_date',
            'courses.name as course_name',
            'courses.description as course_description',
            DB::raw("(SELECT COUNT(*) FROM class_student
            WHERE class_student.class_id = classes.id) AS so_hoc_sinh"),
        )
            ->leftJoin('courses', 'classes.courses_id', '=', 'courses.id');
        // ->join('users', 'classes.teacher_id', '=', 'users.id')

        // Search by name
        if ($request->filled('search')) {
            // Laravel biết rằng 2 điều kiện này là nằm trong cùng một nhóm, vì bạn viết chúng trong cùng một hàm (closure).
            $query->where(function ($q) use ($request) {
                $q->where('classes.name', 'like', '%' . $request->search . '%')
                    ->orWhere('courses.name', 'like', '%' . $request->search . '%');
            });
        }
        // Filter by status
        if ($request->filled('status')) {
            $query->where('classes.status', $request->status);
        }
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        // Filter by student count
        if ($request->filled('min_students')) {
            $query->having('students_count', '>=', $request->min_students);
        }

        if ($request->filled('max_students')) {
            $query->having('students_count', '<=', $request->max_students);
        }
        // Sorting
        $sort = $request->get('sort', 'created_at_desc');
        switch ($sort) {
            case 'created_at_asc':
                $query->orderBy('classes.created_at', 'asc');
                break;
            case 'name_asc':
                $query->orderBy('classes.name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('classes.name', 'desc');
                break;
            default:
                $query->orderBy('classes.created_at', 'desc');
        }

        $classes = $query->paginate(10)->appends($request->query());
        $courses = DB::table('courses')->get();
        // ...existing code...
        if ($request->ajax()) {
            return view('admin.schedules.partials.table', compact('classes'))->render();
        }
        return view('admin.schedules.index', compact('classes', 'courses'));
    }

    public function create($classId)
    {
        $class = DB::table('classes')->where('id', $classId)->first();
        if (!$class) {
            return redirect()->back()->with('error', 'Không tìm thấy lớp học.');
        }

        // Lấy thông tin khóa học của lớp
        $course = DB::table('courses')->where('id', $class->courses_id)->first();

        // Lấy danh sách giáo viên không có lịch học trùng lặp
        $teachers = User::where('role', 'teacher')
            ->whereDoesntHave('schedules', function ($query) use ($classId) {
                $query->where('class_id', $classId);
            })
            ->get();
        // dd($course->total_sessions);
        return view('admin.schedules.create', compact('class', 'teachers', 'course'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id'   => 'required|exists:classes,id',
            'sessions'   => 'required|array|min:1',
            'sessions.*.ngay' => 'required|date_format:d/m/Y',
            'sessions.*.thu'  => 'required|string',
            'sessions.*.start_time' => 'required',
            'sessions.*.end_time'   => 'required',
        ]);

        $classId = $request->class_id;
        $teacherId = $request->teacher_id;

        // Xóa lịch cũ nếu cần
        // DB::table('schedules')->where('class_id', $classId)->delete();

        $sessions = [];
        foreach ($request->sessions as $index => $session) {
            $date = \DateTime::createFromFormat('d/m/Y', $session['ngay']);
            $dateStr = $date ? $date->format('Y-m-d') : null;
            $start = $session['start_time'];
            $end = $session['end_time'];
            $thuMap = [
                'CN' => 'Sun',
                'T2' => 'Mon',
                'T3' => 'Tue',
                'T4' => 'Wed',
                'T5' => 'Thu',
                'T6' => 'Fri',
                'T7' => 'Sat',
            ];
            $dayOfWeek = $thuMap[$session['thu']] ?? $session['thu'];

            // Kiểm tra giáo viên bị trùng lịch (dù khác lớp)
            $teacherConflict = DB::table('schedules')
                ->where('teacher_id', $teacherId)
                ->where('date', $dateStr)
                ->where(function ($q) use ($start, $end) {
                    $q->where(function ($q2) use ($start, $end) {
                        $q2->where('start_time', '<', $end)
                            ->where('end_time', '>', $start);
                    });
                })
                ->exists();

            if ($teacherConflict) {
                return response()->json([
                    'success' => false,
                    'message' => 'Giáo viên đã có lịch dạy trùng thời gian: Ngày ' . $session['ngay'] . ', tiết ' . $start . ' - ' . $end
                ], 422);
            }

            // Kiểm tra lớp bị trùng lịch (dù khác giáo viên)
            $classConflict = DB::table('schedules')
                ->where('class_id', $classId)
                ->where('date', $dateStr)
                ->where(function ($q) use ($start, $end) {
                    $q->where(function ($q2) use ($start, $end) {
                        $q2->where('start_time', '<', $end)
                            ->where('end_time', '>', $start);
                    });
                })
                ->exists();

            if ($classConflict) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lớp đã có lịch học trùng thời gian: Ngày ' . $session['ngay'] . ', tiết ' . $start . ' - ' . $end
                ], 422);
            }

            // --- KIỂM TRA HỌC VIÊN BỊ TRÙNG LỊCH ---
            // Lấy danh sách học viên của lớp này
            $studentIds = DB::table('class_student')->where('class_id', $classId)->pluck('student_id');
            foreach ($studentIds as $studentId) {
                $studentConflict = DB::table('schedules')
                    ->join('class_student', 'schedules.class_id', '=', 'class_student.class_id')
                    ->where('class_student.student_id', $studentId)
                    ->where('schedules.date', $dateStr)
                    ->where('schedules.class_id', '!=', $classId) // Loại trừ lớp hiện tại
                    ->where(function ($q) use ($start, $end) {
                        $q->where('schedules.start_time', '<', $end)
                            ->where('schedules.end_time', '>', $start);
                    })
                    ->exists();

                if ($studentConflict) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Có học viên bị trùng lịch học với lớp khác vào ngày ' . $session['ngay'] . ', tiết ' . $start . ' - ' . $end
                    ], 422);
                }
            }

            $sessions[] = [
                'class_id'       => $classId,
                'date'           => $dateStr,
                'day_of_week'    => $dayOfWeek,
                'start_time'     => $start,
                'end_time'       => $end,
                'teacher_id'     => $teacherId,
                'created_at'     => now(),
            ];
        }

        DB::table('schedules')->insert($sessions);

        return response()->json(['success' => true, 'message' => 'Tạo lịch học thành công!']);
    }

    public function getClassData($classId)
    {
        // Lấy thông tin class và course trong một query
        $classData = DB::table('classes')
            ->join('courses', 'classes.courses_id', '=', 'courses.id')
            ->select('classes.name as class_name', 'courses.name as course_name', 'courses.total_sessions')
            ->where('classes.id', $classId)
            ->first();
        // Lấy danh sách teachers từ bảng users
        $teachers = DB::table('users')
            ->select('id', 'name')
            ->where('role', 'teacher')
            ->get()
            ->map(function ($user) {
                return ['id' => $user->id, 'name' => $user->name];
            });

        return response()->json([
            'class' => ['name' => $classData->class_name],
            'course' => [
                'name' => $classData->course_name,
                'total_sessions' => $classData->total_sessions
            ],
            'teachers' => $teachers
        ]);
    }

    public function destroy($id)
    {
        $schedule = DB::table('schedules')->where('id', $id)->first();
        if (!$schedule) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy lịch học.'], 404);
        }

        DB::table('schedules')->where('id', $id)->delete();

        return response()->json(['success' => true, 'message' => 'Xóa lịch học thành công!']);
    }

    public function edit($id)
    {
        $schedule = DB::table('schedules')->where('id', $id)->first();
        if (!$schedule) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy lịch học.'], 404);
        }

        return response()->json(['success' => true, 'data' => $schedule]);
    }

    public function update(Request $request, $id)
    {
        try {
            $schedule = Schedule::findOrFail($id);

            $schedule->update([
                'date' => \Carbon\Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d'),
                'start_time' => $request->start_time . ':00', // Thêm giây nếu cần
                'end_time' => $request->end_time . ':00',    // Thêm giây nếu cần
                'teacher_id' => $request->teacher_id,
            ]);

            return response()->json(['success' => true, 'message' => 'Cập nhật thành công']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()], 500);
        }
    }



}
