<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\classes;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class AccountController extends Controller
{
    public function account()
    {
        $role = User::orderBy('id', 'desc')->paginate(10);

        $roleCounts = User::select('role', DB::raw('count(*) as total'))
            ->groupBy('role')
            ->pluck('total', 'role') // ['admin' => 10, 'teacher' => 5, ...]
            ->toArray();

        return view('admin.accounts.account', compact('roleCounts', 'role'));
    }
    public function search(Request $request)
    {
        

        $roleCounts = User::select('role', DB::raw('count(*) as total'))
            ->groupBy('role')
            ->pluck('total', 'role') // ['admin' => 10, 'teacher' => 5, ...]
            ->toArray();

        $filter = $request->query('filter');
        // kiểm tra quyền lọc của nhân viên
        if(Auth::user()->role == 'staff') {
            if($filter == 'admin' || $filter == 'staff') {
                return redirect()->route('admin.account')
                    ->with('error', 'Bạn không có quyền lọc vai trò này');
            }
        }

        $gender = $request->query('gender');
        $search = trim($request->query('queryAccount'));

        $query = User::query();

        if ($filter) {
            $query->where('role', $filter);
        }
        if ($gender) {
            $query->where('gender', $gender);
        }

        if ($search) {
            $query->where('name', 'like', "%$search%");
        }

        $role = $query->orderBy('id', 'desc')->paginate(10);

        return view('admin.accounts.account', compact('role', 'filter', 'gender', 'search', 'roleCounts'));
    }




    public function list($role, Request $request)
    {
        // kiểm tra người dùng hiện tại có quyền truy cập role = admin, nhân viên không
        if(Auth::user()->role == 'staff') {
            if($role == 'admin' || $role == 'staff') {
                return redirect()->route('admin.account')
                    ->with('error', 'Bạn không có quyền truy cập vào danh sách này');
            }
        }
        $query = User::where('role', $role);

        $sort = $request->query('sort');
        // Lọc theo thứ tự sắp xếp
        if ($sort) {
            switch ($sort) {
                case 'created_at_desc':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'created_at_asc':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                default:
                    $query->orderBy('id', 'desc'); // Mặc định sắp x
                    break;
            }
        } else {
            $query->orderBy('id', 'desc'); // Mặc định sắp xếp theo ID giảm dần
        }
        // Lọc theo giới tính
        if ($request->has('gender') && $request->gender !== null) {
            $query->where('gender', $request->gender);
        }

        if ($request->has('queryAccountRole') && $request->queryAccountRole !== null) {
            $keyword = $request->queryAccountRole;
            $query->where('name', 'like', '%' . $keyword . '%');
        }

        $users = $query->orderBy('id', 'desc')->paginate(10);

        return view('admin.accounts.list', compact('users', 'role'));
    }

    public function detail($role, $id)
    {   

        $user = User::findOrFail($id);
        if ($user->role == "student") {
            // Lấy danh sách lớp học mà user này đang học
            $classStudent = DB::table('class_student')
                ->where('student_id', $id)
                ->pluck('class_id');

            $allClasses = DB::table('classes')
                ->join('courses', 'classes.courses_id', '=', 'courses.id')
                ->whereIn('classes.id', $classStudent)
                ->select('classes.*', 'courses.name as course_name')
                ->get();

            // phân loại theo trạng thái lớp học
            $currentClasses = $allClasses->where('status', 'in_progress');
            $finishedClasses = $allClasses->where('status', 'completed');


            // Lấy điểm của user trong các lớp này
            $scores = DB::table('scores')
                ->where('student_id', $id)
                ->whereIn('class_id', $classStudent)
                ->get()
                ->groupBy('class_id');

            return view('admin.accounts.account-detail', [
                'user' => $user,
                'currentClasses' => $currentClasses,
                'finishedClasses' => $finishedClasses,
                'scores' => $scores,
            ]);
        } elseif ($user->role == "teacher") {
            // 1. Lấy lịch dạy (full)
            $schedules = DB::table('schedules')
                ->where('teacher_id', $id)
                ->whereNotNull('class_id')
                ->get();

            // 2. Lấy danh sách class_id duy nhất
            $classIdsSchedules = $schedules->pluck('class_id')->unique();

            // 3. Lấy thông tin lớp
            $classTeacher = DB::table('classes')
                ->join('courses', 'classes.courses_id', '=', 'courses.id')
                ->whereIn('classes.id', $classIdsSchedules)
                ->select(
                    'classes.id',
                    'classes.name',
                    'classes.status',
                    'classes.number_of_sessions',
                    'courses.name as course_name',
                    'courses.total_sessions'
                )
                ->get();

            // 4. Đếm số lượng học sinh từng lớp
            $countStudent = DB::table('class_student')
                ->select('class_id', DB::raw('count(student_id) as student_count'))
                ->whereIn('class_id', $classIdsSchedules)
                ->groupBy('class_id')
                ->pluck('student_count', 'class_id');

            // 5. Phân loại lớp: đang dạy / đã dạy dựa trạng thái
            $teachingClasses = $classTeacher->where('status', 'in_progress');
            $taughtClasses   = $classTeacher->where('status', 'completed');


            return view('admin.accounts.account-detail', [
                'user' => $user,
                'schedules' => $schedules,
                'countStudent' => $countStudent,
                'teachingClasses' => $teachingClasses,
                'taughtClasses' => $taughtClasses,
            ]);
        } else {
            // Trường hợp khác (admin, v.v.)
            $user = User::findOrFail($id);
            return view('admin.accounts.account-detail', compact('user', 'role'));
        }
    }


    public function add($role)
    {
        // Kiểm tra quyền của người dùng hiện tại
        if(Auth::user()->role == 'staff') {
            if($role == 'admin' || $role == 'staff') {
                return redirect()->route('admin.account')
                    ->with('error', 'Bạn không có quyền thêm người dùng với vai trò này');
            }
        }
    
      
        return view('admin.accounts.account-add');
    }

    public function store(Request $request, $role)
    {
        // Kiểm tra quyền của người dùng hiện tại
        if(Auth::user()->role == 'staff') {
            if($role == 'admin' || $role == 'staff') {
                return redirect()->route('admin.account')
                    ->with('error', 'Bạn không có quyền thêm người dùng với vai trò này');
            }
        }
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'username'       => 'required|string|max:255|unique:users',
            'email'      => 'nullable|string|email|max:255',
            'address'      => 'nullable|string|max:1000',
            'phone'     => 'nullable|digits_between:8,20',
            'password'   => 'nullable|string',
            'gender'     => 'nullable|in:boy,girl',
            'birth_date' => 'nullable|date',
            'avatar'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $role = $request->route('role');

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');

            // Lấy đuôi file gốc (jpg, png, ...)
            $extension = $file->getClientOriginalExtension();
            $filename = Str::uuid() . '.' . $extension;

            $destinationPath = public_path('uploads/avatar');
            $fullPath = $destinationPath . '/' . $filename;

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            // Giữ nguyên ảnh gốc
            $file->move($destinationPath, $filename);

            // Lưu path vào DB
            $validated['avatar'] = 'uploads/avatar/' . $filename;
        }

        $validated['role'] = $role;
        $validated['password'] = Hash::make($validated['username']);

        $user = User::create($validated);


        return redirect()->route('admin.account.list', ['role' => $role])->with('success', 'Thêm người dùng thành công');
    }

    public function edit($role, $id)
    {
         // Kiểm tra quyền của người dùng hiện tại
        if(Auth::user()->role == 'staff') {
            if($role == 'admin' || $role == 'staff') {
                return redirect()->route('admin.account')
                    ->with('error', 'Bạn không có quyền thêm người dùng với vai trò này');
            }
        }
        
        $info = User::find($id);
        return view('admin.accounts.account-edit', compact('info'));
    }

    public function update(Request $request, $role, $id)
    {
        // Kiểm tra quyền của người dùng hiện tại
        if(Auth::user()->role == 'staff') {
            if($role == 'admin' || $role == 'staff') {
                return redirect()->route('admin.account')
                    ->with('error', 'Bạn không có quyền thêm người dùng với vai trò này');
            }
        }

        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'username'   => 'required|string|max:255|unique:users,name,' . $user->id,
            'email'      => 'nullable|string||min:6',
            'address'      => 'nullable|string|max:1000',
            'phone'      => 'nullable|digits_between:8,20',
            'password'   => 'nullable|string',
            'gender'     => 'nullable|in:boy,girl',
            'birth_date' => 'nullable|date',
            'avatar'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $extension = $file->getClientOriginalExtension();
            $filename = Str::uuid() . '.' . $extension;
            $destinationPath = public_path('uploads/avatar');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $filename);
            $validated['avatar'] = 'uploads/avatar/' . $filename;
        }

        $validated['role'] = $role;
        if (!empty($request->password)) {
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route('admin.account.list', ['role' => $role])
            ->with('success', 'Cập nhật người dùng thành công');
    }



    public function delete($role, $id)
    {

        if (Auth::user()->id == $id) {
            return redirect()->back()->with('error', 'Bạn không thể xóa chính mình');
        }

        if (Auth::user()->role == 'admin') {
            $userId = User::findOrFail($id);
            if ($userId->role == 'student') {
                $classStudentCount = DB::table('class_student')->where('student_id', $id)->count();
                if ($classStudentCount > 0) {
                    return redirect()->route('admin.account.list', ['role' => $role])
                        ->with('error', 'Không thể xóa người dùng vì có liên kết với lớp học');
                }
            } elseif ($userId->role == 'teacher') {
                $scheduleCount = DB::table('schedules')->where('teacher_id', $id)->count();
                if ($scheduleCount > 0) {
                    return redirect()->route('admin.account.list', ['role' => $role])
                        ->with('error', 'Không thể xóa người dùng vì có lịch dạy');
                }
            } elseif ($userId->role == 'admin') {
                // Kiểm tra xem có phải là người dùng duy nhất không
                $adminCount = User::where('role', 'admin')->count();
                if ($adminCount <= 1) {
                    return redirect()->route('admin.account.list', ['role' => $role])
                        ->with('error', 'Không thể xóa người dùng vì đây là người dùng quản trị duy nhất');
                }
            }

            User::find($id)->delete();
            return redirect()->route('admin.account.list', ['role' => $role])->with('success', 'Xóa người dùng thành công');
        }

        if(Auth::user()->role == 'staff') {
            return redirect()->route('admin.account.list', ['role' => $role])
                ->with('error', 'Bạn không có quyền xóa người dùng');
        }
    }

    public function trash(Request $request)
    {
        $query = trim($request->query('accountTrash'));
        $role = $request->query('role');

        $trashQuery = User::onlyTrashed()->orderBy('deleted_at', 'desc');

        if ($query) {
            $trashQuery->where('name', 'like', "%$query%");
        }

        if ($role) {
            $trashQuery->where('role', $role);
        }

        $trash = $trashQuery->paginate(10);

        return view('admin.accounts.trash', compact('trash', 'query', 'role'));
    }




    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();
        return redirect()->route('admin.account.trash')->with('success', 'Khôi phục tài khoản thành công');
    }

    public function forceDelete($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->forceDelete();
        return redirect()->route('admin.account.trash')->with('success', 'Xóa vĩnh viễn tài khoản thành công');
    }
}
