<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\classes;
use App\Models\classStudent;
use App\Models\coursePayment;
// use App\Models\coursePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassController extends Controller
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
            return view('admin.classes.partials.table', compact('classes'))->render();
        }
        return view('admin.classes.index', compact('classes', 'courses'));
    }

    // Add other methods for creating, editing, updating, and deleting classes as needed
    public function toggleStatus($id)
    {
        $class = Classes::findOrFail($id);
        // Chuyển đổi giữa in_progress và not_started
        if ($class->status === 'in_progress') {
            $class->status = 'not_started';
        } elseif ($class->status === 'not_started') {
            $class->status = 'in_progress';
        }
        // Không cho phép đổi trạng thái nếu đã completed
        $class->save();

        return redirect()->back()->with('success', 'Trạng thái đã được cập nhật');
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'courses_id' => 'required|exists:courses,id',
            // 'number_of_sessions' => 'required|integer|min:1',
            'status' => 'required|in:in_progress,not_started,completed',
        ]);

        $class = new Classes();
        $class->name = $request->name;
        $class->courses_id = $request->courses_id;
        // $class->number_of_sessions = $request->number_of_sessions;
        $class->status = $request->status;
        $class->save();

        return redirect()->route('admin.classes.index')->with('success', 'Lớp học đã được tạo thành công');
    }

    public function show($id)
    {
        $class = classes::select(
            'classes.id',
            'classes.name',
            'classes.number_of_sessions as so_buoi_hoc',
            'classes.status',
            'classes.created_at',
            'classes.updated_at',
            'courses.name as course_name',
            'courses.description as course_description',
            DB::raw("(SELECT COUNT(*) FROM class_student
            WHERE class_student.class_id = classes.id) AS so_hoc_sinh"),
        )
            ->leftJoin('courses', 'classes.courses_id', '=', 'courses.id')
            ->where('classes.id', $id)
            ->firstOrFail();

        return view('admin.classes.show', compact('class'));
    }

    public function edit($id)
    {
        $class = classes::findOrFail($id);
        $courses = DB::table('courses')->get();

        return view('admin.classes.edit', compact('class', 'courses'));
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'courses_id' => 'required|exists:courses,id',
                'number_of_sessions' => 'required|integer|min:1',
                'status' => 'required|in:in_progress,not_started,completed',
            ]);

            $class = Classes::findOrFail($id);
            $class->name = $request->name;
            $class->courses_id = $request->courses_id;
            $class->number_of_sessions = $request->number_of_sessions;
            $class->status = $request->status;
            $class->save();

            return redirect()->route('admin.classes.index')->with('success', 'Lớp học đã được cập nhật thành công');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Lỗi validate sẽ được xử lý tự động, nhưng bạn có thể tùy chỉnh thông báo
            return redirect()->back()->withErrors($e->validator)->withInput()->with('error', 'Dữ liệu không hợp lệ, vui lòng kiểm tra lại.');
        } catch (\Exception $e) {
            // Các lỗi khác (ví dụ: lỗi cơ sở dữ liệu, không tìm thấy bản ghi)
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi cập nhật lớp học: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $class = classes::findOrFail($id);
        $class->delete();

        // Xóa mềm các bản ghi học phí liên quan
        coursePayment::where('class_id', $id)->get()->each->delete();

        // return redirect()->route('admin.classes.index')->with('success', 'Lớp học đã được xóa thành công');
        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully.'
        ]);
    }

    public function restore($id)
    {
        $class = classes::withTrashed()->findOrFail($id);
        $class->restore();

        //Khôi phụ các bản ghi học phí liên quan
        coursePayment::withTrashed()
            ->where('class_id', $id)
            ->restore();


        return redirect()->route('admin.classes.index')->with('success', 'Lớp học đã được khôi phục thành công');
    }

    public function forceDelete($id)
    {
        // Xóa cứng các khoản thanh toán liên quan trước
        coursePayment::withTrashed()
            ->where('class_id', $id)
            ->forceDelete();

        $class = classes::withTrashed()->findOrFail($id);
        $class->forceDelete();

        return redirect()->route('admin.classes.index')->with('success', 'Lớp học đã được xóa vĩnh viễn thành công');
    }

    public function students($id)
    {
        try {
            // Lấy thông tin lớp học
            $class = DB::table('classes')
                ->where('id', $id)
                ->whereNull('deleted_at')
                ->first();

            if (!$class) {
                return redirect()->back()->with('error', 'Lớp học không tồn tại hoặc đã bị xóa.');
            }

            // Lấy danh sách học sinh trong lớp
            $query = DB::table('class_student')
                ->join('users', 'class_student.student_id', '=', 'users.id')
                ->where('class_student.class_id', $id)
                ->whereNull('class_student.deleted_at') // Lọc học sinh chưa bị xóa
                ->select('users.id', 'users.name', 'users.email');

            // Debug để kiểm tra
            // dd(get_class($students), $students); // "Illuminate\Pagination\LengthAwarePaginator"

            // Xử lý tìm kiếm
            if (request('search')) {
                $search = request('search');
                $query->where(function ($q) use ($search) {
                    $q->where('users.name', 'like', "%{$search}%")
                        ->orWhere('users.email', 'like', "%{$search}%");
                });
            }

            // Xử lý sắp xếp
            $sortBy = request('sort_by', 'created_at_desc'); // Mặc định sắp xếp theo created_at giảm dần
            switch ($sortBy) {
                case 'name':
                    $query->orderBy('users.name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('users.name', 'desc');
                    break;
                case 'email':
                    $query->orderBy('users.email', 'asc');
                    break;
                case 'email_desc':
                    $query->orderBy('users.email', 'desc');
                    break;
                case 'created_at':
                    $query->orderBy('class_student.created_at', 'asc');
                    break;
                case 'created_at_desc':
                default:
                    $query->orderBy('class_student.created_at', 'desc');
                    break;
            }

            // Xử lý số bản ghi mỗi trang
            $perPage = request('per_page', 10); // Mặc định 10 bản ghi mỗi trang
            $students = $query->paginate($perPage);

            // Lấy danh sách học viên đã bị xóa mềm
            $trashedStudents = classStudent::onlyTrashed()
                ->where('class_id', $id)
                ->join('users', 'class_student.student_id', '=', 'users.id')
                ->select('users.id', 'users.name', 'users.email', 'class_student.deleted_at')
                ->get();

            // dd($trashedStudents);

            // Lấy danh sách học sinh chưa thuộc lớp này
            $studentIdsInClass = $students->pluck('id')->toArray();
            $availableStudents = DB::table('users')
                ->where('role', 'student')
                ->whereNotIn('id', $studentIdsInClass)
                ->select('id', 'name', 'email')
                ->get();

            // Lấy thông tin khóa học (nếu cần)
            $course = DB::table('courses')
                ->where('id', $class->courses_id)
                ->select('name')
                ->first();

            return view('admin.classes.students', compact('class', 'students', 'trashedStudents', 'course', 'availableStudents'));
            // ->with('success', 'Lấy thông tin lớp học thành công.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi lấy thông tin lớp học: ' . $e->getMessage());
        }
    }

    public function addStudent(Request $request, $classId)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
        ]);

        $studentId = $request->student_id;

        // Lấy tất cả lịch học của lớp này
        $classSchedules = DB::table('schedules')
            ->where('class_id', $classId)
            ->get();

        foreach ($classSchedules as $schedule) {
            // Kiểm tra học viên này có bị trùng lịch với các lớp khác không
            $conflict = DB::table('schedules')
                ->join('class_student', 'schedules.class_id', '=', 'class_student.class_id')
                ->where('class_student.student_id', $studentId)
                ->where('schedules.class_id', '!=', $classId)
                ->where('schedules.date', $schedule->date)
                ->where(function ($q) use ($schedule) {
                    $q->where('schedules.start_time', '<', $schedule->end_time)
                        ->where('schedules.end_time', '>', $schedule->start_time);
                })
                ->exists();

            if ($conflict) {
                // Trả về redirect với session lỗi
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Học viên bị trùng lịch với lớp khác vào ngày ' . \Carbon\Carbon::parse($schedule->date)->format('d/m/Y') . ', tiết ' . $schedule->start_time . ' - ' . $schedule->end_time);
            }
        }

        // Nếu không trùng, thêm vào lớp
        DB::table('class_student')->insert([
            'class_id' => $classId,
            'student_id' => $studentId,
            'created_at' => now(),
        ]);



        //Thêm vào bảng course_payments
        $classes = classes::where('classes.id', $classId)
            ->with(['course'])
            ->first();
        DB::table('course_payments')->insert([
            'class_id' => $classId,
            'student_id' => $studentId,
            'course_id' => $classes->course->id,
            'amount' => $classes->course->price,
            'status' => 'unpaid',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Học sinh đã được thêm vào lớp thành công.');
    }

    public function removeStudent($classId, $studentId)
    {
        // Xóa mềm học sinh khỏi lớp
        classStudent::where('class_id', $classId)
            ->where('student_id', $studentId)
            ->first()
            ->delete();

        //Xóa mềm học sinh khỏi bảng course_payments
        $classes = classes::where('classes.id', $classId)
            ->with(['course'])
            ->first();

        //Kiểm tra xem nếu học sinh đã đóng tiền thì ko xóa mà sẽ lưu lại
        $course_payment = coursePayment::where('class_id', $classId)
            ->where('student_id', $studentId)
            ->where('course_id', $classes->course->id)
            ->first();
        if ($course_payment->status = 'unpaid') {
            $course_payment->delete();
        }



        return redirect()->back()->with('success', 'Học sinh đã được xóa khỏi lớp thành công.');
    }

    /**
     * Khôi phục học viên đã bị xóa mềm
     */
    public function restoreStudent($classId, $studentId)
    {
        // Tìm bản ghi ClassStudent, bao gồm cả bản ghi đã xóa mềm
        $classStudent = classStudent::withTrashed()
            ->where('class_id', $classId)
            ->where('student_id', $studentId)
            ->first();

        // Kiểm tra xem bản ghi có tồn tại và đã bị xóa mềm hay không
        if (!$classStudent || !$classStudent->trashed()) {
            return redirect()->back()->with('error', 'Không tìm thấy học viên đã bị xóa.');
        }

        // Lấy tất cả lịch học của lớp này
        $classSchedules = DB::table('schedules')
            ->where('class_id', $classId)
            ->get();

        foreach ($classSchedules as $schedule) {
            // Kiểm tra học viên này có bị trùng lịch với các lớp khác không
            $conflict = DB::table('schedules')
                ->join('class_student', 'schedules.class_id', '=', 'class_student.class_id')
                ->where('class_student.student_id', $studentId)
                ->where('schedules.class_id', '!=', $classId)
                ->where('schedules.date', $schedule->date)
                ->where(function ($q) use ($schedule) {
                    $q->where('schedules.start_time', '<', $schedule->end_time)
                        ->where('schedules.end_time', '>', $schedule->start_time);
                })
                ->exists();

            if ($conflict) {
                // Trả về redirect với session lỗi
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Học viên bị trùng lịch với lớp khác vào ngày ' . \Carbon\Carbon::parse($schedule->date)->format('d/m/Y') . ', tiết ' . $schedule->start_time . ' - ' . $schedule->end_time);
            }
        }

        // Khôi phục bản ghi đã xóa mềm
        $classStudent->restore();

        //Khôi phục bản ghi course_payment
        $course_payment = coursePayment::withTrashed()
            ->where('class_id', $classId)
            ->where('student_id', $studentId)
            ->first();

        // Kiểm tra xem bản ghi có tồn tại và đã bị xóa mềm hay không
        if (!$course_payment || !$course_payment->trashed()) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin thanh toán đã bị xóa.');
        }

        $course_payment->restore();

        return redirect()->back()->with('success', 'Đã khôi phục học viên vào lớp.');
    }

    /**
     * Xóa vĩnh viễn học viên khỏi lớp
     */
    public function forceDeleteStudent($classId, $studentId)
    {
        // Tìm bản ghi ClassStudent, bao gồm cả bản ghi đã xóa mềm
        $classStudent = classStudent::withTrashed()
            ->where('class_id', $classId)
            ->where('student_id', $studentId)
            ->first();

        // Kiểm tra xem bản ghi có tồn tại và đã bị xóa mềm hay không
        if (!$classStudent || !$classStudent->trashed()) {
            return redirect()->back()->with('error', 'Không tìm thấy học viên đã bị xóa.');
        }

        // Xóa vĩnh viễn bản ghi
        $classStudent->forceDelete();

        // Xóa cứng học sinh khỏi bảng course_payments
        $classes = classes::where('classes.id', $classId)
            ->with(['course'])
            ->first();

        $course_payment = coursePayment::where('class_id', $classId)
            ->where('student_id', $studentId)
            ->where('course_id', $classes->course->id)
            ->first();

        if ($course_payment) {
            $course_payment->forceDelete();
        }


        return redirect()->back()->with('success', 'Đã xóa vĩnh viễn học viên khỏi lớp.');
    }



    // Lịch học
    public function schedules(Request $request, $id)
    {
        // Lấy thông tin lớp học
        $class = DB::table('classes')
            ->where('id', $id)
            ->whereNull('deleted_at')
            ->first();

        if (!$class) {
            return redirect()->back()->with('error', 'Lớp học không tồn tại hoặc đã bị xóa.');
        }

        // Lấy thông tin khóa học của lớp
        $course = DB::table('classes')
            ->join('courses', 'classes.courses_id', '=', 'courses.id')
            ->where('classes.id', $class->id)
            ->select('courses.name')
            ->first();

        // Lấy danh sách giáo viên cho bộ lọc
        $teachers = DB::table('users')
            ->where('role', 'teacher')
            ->select('id', 'name')
            ->get();

        // Lấy danh sách lịch học của lớp
        $query = DB::table('schedules')
            ->join('users', 'schedules.teacher_id', '=', 'users.id')
            ->where('class_id', $id);
        if ($request->filled('weekday')) {
            $query->where('day_of_week', $request->weekday);
        }

        if ($request->filled('date') && !empty($request->date)) {
            try {
                $date = \Carbon\Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');
                $query->where('date', $date);
            } catch (\Exception $e) {
                if (!$request->ajax()) {
                    return redirect()->back()->with('error', 'Ngày không hợp lệ, vui lòng nhập đúng định dạng dd/mm/yyyy.');
                }
                // Nếu là AJAX, trả về JSON lỗi
                if ($request->ajax()) {
                    return response()->json([
                        'error' => 'Ngày không hợp lệ, vui lòng nhập đúng định dạng dd/mm/yyyy.'
                    ], 422);
                }
            }
        }
        if ($request->filled('teacher')) {
            $query->where('teacher_id', $request->teacher);
        }

        $schedules = $query
            ->orderBy('start_time', 'asc')
            ->select(
                'schedules.*',
                'users.name as teacher_name'
            )
            ->paginate(10) // Phân trang, mỗi trang 10 bản ghi
            ->appends($request->query()); // Giữ lại các tham số filter khi chuyển trang

        if ($request->ajax()) {
            return view('admin.classes.partials.schedules', compact('schedules'))->render();
        }
        return view('admin.classes.schedules', compact('class', 'course', 'schedules', 'teachers'));
    }
}
