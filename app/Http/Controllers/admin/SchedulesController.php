<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\classes;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SchedulesController extends Controller
{
    public function index(Request $request)
    {
        $query = classes::select(
            'classes.id',
            'classes.name',
            'classes.number_of_sessions as sessions_count',
            'classes.status',
            'classes.created_at as start_date',
            'courses.name as course_name',
            'courses.description as course_description',
            DB::raw("(SELECT COUNT(*) 
              FROM schedules 
              WHERE schedules.class_id = classes.id) as scheduled_sessions"),
            DB::raw("(SELECT COUNT(DISTINCT schedules.id) 
              FROM schedules 
              JOIN attendances ON attendances.schedule_id = schedules.id
              WHERE schedules.class_id = classes.id) as attended_sessions")
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
            // return 'OK';
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
            'room'       => 'required|integer|exists:class_room,id', // Sửa room_id -> room
            'sessions'   => 'required|array|min:1',
            'sessions.*.ngay' => 'required|date_format:d/m/Y',
            'sessions.*.thu'  => 'required|string',
            'sessions.*.start_time' => 'required',
            'sessions.*.end_time'   => 'required',
            'sessions.*.room' => 'required|integer|exists:class_room,id', // Thêm validate cho từng session
        ]);

        $classId = $request->class_id;
        $teacherId = $request->teacher_id;
        $room = $request->room; // Sửa room_id -> room
        $roomName = DB::table('class_room')->where('id', $room)->value('room_name'); // Sửa room_id -> room

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

            // Kiểm tra phòng học bị trùng lịch
            $roomSession = $session['room']; // Sửa room_id -> room
            $roomConflict = DB::table('schedules')
                ->where('room', $roomSession) // Sửa room_id -> room
                ->where('date', $dateStr)
                ->where(function ($q) use ($start, $end) {
                    $q->where(function ($q2) use ($start, $end) {
                        $q2->where('start_time', '<', $end)
                            ->where('end_time', '>', $start);
                    });
                })
                ->exists();

            if ($roomConflict) {
                return response()->json([
                    'success' => false,
                    'message' => 'Phòng học đã được đặt trong thời gian này: Ngày ' . $session['ngay'] . ', tiết ' . $start . ' - ' . $end
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
                'room'           => $roomSession, // Sửa room_id -> room
                'created_at'     => now(),
            ];
        }

        DB::table('schedules')->insert($sessions);

        return response()->json(['success' => true, 'message' => 'Tạo lịch học thành công!']);
    }

    public function storeSingle(Request $request)
    {
        // Log data để debug
        Log::info('Storing single schedule', $request->all());

        // Validate dữ liệu
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'weekday' => 'required|in:Mon,Tue,Wed,Thu,Fri,Sat,Sun',
            'date' => 'required|date_format:d/m/Y',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'teacher_id' => 'required|exists:users,id',
            'room_id' => 'required|exists:class_room,id',
        ]);

        // Chuyển đổi định dạng ngày
        $dateStr = Carbon::createFromFormat('d/m/Y', $validated['date'])->format('Y-m-d');
        $start = $validated['start_time'];
        $end = $validated['end_time'];
        $classId = $validated['class_id'];
        $teacherId = $validated['teacher_id'];
        $room = $validated['room_id']; // Sử dụng 'room_id' từ validated

        // Lấy thông tin khóa học từ lớp học
        $course = DB::table('classes')
            ->join('courses', 'classes.courses_id', '=', 'courses.id')
            ->where('classes.id', $classId)
            ->select('courses.total_sessions')
            ->first();

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy khóa học liên kết với lớp.'
            ], 422);
        }

        // Đếm số buổi học hiện tại của lớp
        $currentSessionCount = DB::table('schedules')
            ->where('class_id', $classId)
            ->count();

        // Kiểm tra nếu số buổi vượt quá giới hạn của khóa học
        // if ($currentSessionCount >= $course->total_sessions) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Số buổi học đã đạt giới hạn của khóa học (' . $course->total_sessions . ' buổi).'
        //     ], 422);
        // }

        // Kiểm tra trùng lịch giáo viên
        $teacherConflict = DB::table('schedules')
            ->where('teacher_id', $teacherId)
            ->where('date', $dateStr)
            ->where(function ($q) use ($start, $end) {
                $q->where('start_time', '<', $end)
                    ->where('end_time', '>', $start);
            })
            ->exists();

        if ($teacherConflict) {
            return response()->json([
                'success' => false,
                'message' => 'Giáo viên đã có lịch dạy trùng thời gian: Ngày ' . $validated['date'] . ', tiết ' . $start . ' - ' . $end
            ], 422);
        }

        // Kiểm tra trùng lịch lớp học
        $classConflict = DB::table('schedules')
            ->where('class_id', $classId)
            ->where('date', $dateStr)
            ->where(function ($q) use ($start, $end) {
                $q->where('start_time', '<', $end)
                    ->where('end_time', '>', $start);
            })
            ->exists();

        if ($classConflict) {
            return response()->json([
                'success' => false,
                'message' => 'Lớp đã có lịch học trùng thời gian: Ngày ' . $validated['date'] . ', tiết ' . $start . ' - ' . $end
            ], 422);
        }

        // Kiểm tra trùng lịch phòng học
        $roomConflict = DB::table('schedules')
            ->where('room', $room) // Giả sử cột trong database là 'room', sửa nếu cần thành 'room_id'
            ->where('date', $dateStr)
            ->where(function ($q) use ($start, $end) {
                $q->where('start_time', '<', $end)
                    ->where('end_time', '>', $start);
            })
            ->exists();

        if ($roomConflict) {
            return response()->json([
                'success' => false,
                'message' => 'Phòng học đã được đặt trong thời gian này: Ngày ' . $validated['date'] . ', tiết ' . $start . ' - ' . $end
            ], 422);
        }

        // Kiểm tra trùng lịch học viên
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
                    'message' => 'Có học viên bị trùng lịch học với lớp khác vào ngày ' . $validated['date'] . ', tiết ' . $start . ' - ' . $end
                ], 422);
            }
        }

        // Chuẩn bị dữ liệu để lưu
        $data = [
            'class_id' => $classId,
            'day_of_week' => $validated['weekday'],
            'date' => $dateStr,
            'start_time' => $start,
            'end_time' => $end,
            'teacher_id' => $teacherId,
            'room' => $room, // Sửa thành 'room' nếu cột trong database là 'room'
            'created_at' => now(),
        ];

        // Lưu vào database
        try {
            DB::table('schedules')->insert($data);
            return response()->json([
                'success' => true,
                'message' => 'Đã thêm lịch học thành công.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error storing schedule: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi khi lưu lịch học: ' . $e->getMessage()
            ], 500);
        }
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

        // Lấy danh sách phòng học khả dụng (ưu tiên trạng thái chưa sử dụng)
        $rooms = DB::table('class_room')
            ->select('id', 'room_name as name')
            ->orderByDesc('status') // nếu status=1 là chưa sử dụng
            ->get()
            ->map(function ($room) {
                return ['id' => $room->id, 'name' => $room->name];
            });

        return response()->json([
            'class' => ['name' => $classData->class_name],
            'course' => [
                'name' => $classData->course_name,
                'total_sessions' => $classData->total_sessions
            ],
            'teachers' => $teachers,
            'rooms' => $rooms
        ]);
    }

    public function destroy($id)
    {
        $schedule = DB::table('schedules')->where('id', $id)->first();
        if (!$schedule) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy lịch học.'], 404);
        }

        // Kiểm tra có attendance liên quan không
        $attendanceCount = DB::table('attendances')
            ->where('schedule_id', $id)
            ->count();

        if ($attendanceCount > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa lịch học vì đã điểm danh!'
            ], 400);
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
        // Log data để debug
        Log::info('Updating schedule', $request->all());

        // Validate dữ liệu
        $validated = $request->validate([
            'weekday' => 'required|in:Mon,Tue,Wed,Thu,Fri,Sat,Sun',
            'date' => 'required|date_format:d/m/Y',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'teacher_id' => 'required|exists:users,id',
            'room' => 'required|exists:class_room,id',
        ], [
            'weekday.required' => 'Trường thứ trong tuần là bắt buộc.',
            'weekday.in' => 'Trường thứ trong tuần phải là một trong các giá trị: Mon, Tue, Wed, Thu, Fri, Sat, Sun.',
        ]);

        // Chuyển đổi định dạng ngày
        $dateStr = Carbon::createFromFormat('d/m/Y', $validated['date'])->format('Y-m-d');
        $start = $validated['start_time'];
        $end = $validated['end_time'];
        $teacherId = $validated['teacher_id'];
        $room = $validated['room'];
        $classId = DB::table('schedules')->where('id', $id)->value('class_id');

        if (!$classId) {
            return response()->json([
                'success' => false,
                'message' => 'Lịch học không tồn tại.'
            ], 404);
        }

        // Kiểm tra trùng lịch giáo viên
        $teacherConflict = DB::table('schedules')
            ->where('teacher_id', $teacherId)
            ->where('date', $dateStr)
            ->where('id', '!=', $id) // Loại trừ lịch hiện tại
            ->where(function ($q) use ($start, $end) {
                $q->where('start_time', '<', $end)
                    ->where('end_time', '>', $start);
            })
            ->exists();

        if ($teacherConflict) {
            return response()->json([
                'success' => false,
                'message' => 'Giáo viên đã có lịch dạy trùng thời gian: Ngày ' . $validated['date'] . ', tiết ' . $start . ' - ' . $end
            ], 422);
        }

        // Kiểm tra trùng lịch lớp học
        $classConflict = DB::table('schedules')
            ->where('class_id', $classId)
            ->where('date', $dateStr)
            ->where('id', '!=', $id) // Loại trừ lịch hiện tại
            ->where(function ($q) use ($start, $end) {
                $q->where('start_time', '<', $end)
                    ->where('end_time', '>', $start);
            })
            ->exists();

        if ($classConflict) {
            return response()->json([
                'success' => false,
                'message' => 'Lớp đã có lịch học trùng thời gian: Ngày ' . $validated['date'] . ', tiết ' . $start . ' - ' . $end
            ], 422);
        }

        // Kiểm tra trùng lịch phòng học
        $roomConflict = DB::table('schedules')
            ->where('room', $room)
            ->where('date', $dateStr)
            ->where('id', '!=', $id) // Loại trừ lịch hiện tại
            ->where(function ($q) use ($start, $end) {
                $q->where('start_time', '<', $end)
                    ->where('end_time', '>', $start);
            })
            ->exists();

        if ($roomConflict) {
            return response()->json([
                'success' => false,
                'message' => 'Phòng học đã được đặt trong thời gian này: Ngày ' . $validated['date'] . ', tiết ' . $start . ' - ' . $end
            ], 422);
        }

        // Kiểm tra trùng lịch học viên
        $studentIds = DB::table('class_student')
            ->where('class_id', $classId)
            ->whereNull('deleted_at')
            ->pluck('student_id');

        foreach ($studentIds as $studentId) {
            $studentConflict = DB::table('schedules')
                ->join('class_student', 'schedules.class_id', '=', 'class_student.class_id')
                ->where('class_student.student_id', $studentId)
                ->where('schedules.date', $dateStr)
                ->where('schedules.class_id', '!=', $classId)
                ->where('schedules.id', '!=', $id) // Loại trừ lịch hiện tại
                ->where(function ($q) use ($start, $end) {
                    $q->where('schedules.start_time', '<', $end)
                        ->where('schedules.end_time', '>', $start);
                })
                ->exists();

            if ($studentConflict) {
                return response()->json([
                    'success' => false,
                    'message' => 'Có học viên bị trùng lịch học với lớp khác vào ngày ' . $validated['date'] . ', tiết ' . $start . ' - ' . $end
                ], 422);
            }
        }

        // Chuẩn bị dữ liệu để cập nhật
        $data = [
            'day_of_week' => $validated['weekday'], // Ánh xạ weekday sang day_of_week
            'date' => $dateStr,
            'start_time' => $start,
            'end_time' => $end,
            'teacher_id' => $teacherId,
            'room' => $room,
            'updated_at' => now(),
        ];

        // Cập nhật vào database
        try {
            DB::table('schedules')->where('id', $id)->update($data);
            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật lịch học thành công.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating schedule: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi khi cập nhật lịch học: ' . $e->getMessage()
            ], 500);
        }
    }
}
