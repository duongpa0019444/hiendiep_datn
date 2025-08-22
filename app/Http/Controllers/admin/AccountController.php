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
        $query = User::orderBy('id', 'desc');

        // Nếu là nhân viên ko hiển thị các tài khoản admin, staff
        if (Auth::user()->role == 'staff') {
            $query->whereNotIn('role', ['admin', 'staff']);
        }

        // phân trang lại sau đk
        $role = $query->paginate(10);

        $roleCounts = User::select('role', DB::raw('count(*) as total'))
            ->groupBy('role')
            ->pluck('total', 'role') // ['admin' => 10, 'teacher' => 5, ...]
            ->toArray();

        return view('admin.accounts.account', compact('roleCounts', 'role'));
    }
    public function search(Request $request)
    {
        // Số lượng tài khoản
        $roleCounts = User::select('role', DB::raw('count(*) as total'))
            ->groupBy('role')
            ->pluck('total', 'role')
            ->toArray();

        $query = User::query();
        // kiểm tra nếu là nhân viên thì ko lọc ra tk admin và nhân viên
        if (Auth::user()->role === 'staff') {
            $query->whereNotIn('role', ['admin', 'staff']);
        }

        $filter = $request->query('filter');
        $gender = $request->query('gender');
        $search = trim($request->query('queryAccount'));
        $sort = $request->query('sort');

        // kiểm tra quyền lọc của nhân viên
        if (Auth::user()->role == 'staff') {
            if ($filter == 'admin' || $filter == 'staff') {
                return redirect()->route('admin.account')
                    ->with('error', 'Bạn không có quyền lọc vai trò này');
            }
        }


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

        if ($filter) {
            $query->where('role', $filter)
                    ->orWhere('mission', $filter);
        }
        if ($gender) {
            $query->where('gender', $gender);
        }

        if ($search) {
            $query->where('name', 'like', "%$search%");

            // Nếu người dùng hiện tại là staff thì loại bỏ admin và staff khỏi kết quả tìm kiếm
            if (Auth::user()->role === 'staff') {
                $query->whereNotIn('role', ['admin', 'staff']);
            }
        }


        $role = $query->orderBy('id', 'desc')->paginate(10);

        return view('admin.accounts.account', compact('role', 'filter', 'gender', 'search', 'roleCounts'));
    }




    public function list($role, Request $request)
    {
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

        // Lọc theo nhiệm vụ của nhân viên
        if ($request->has('mission') && $request->mission !== null) {
            $query->where('mission', $request->mission);
        }

        if ($request->has('queryAccountRole') && $request->queryAccountRole !== null) {
            $keyword = $request->queryAccountRole;
            $query->where('name', 'like', '%' . $keyword . '%')
                ->orWhere('snake_case', 'like', '%' . $keyword . '%' );
        }

        $users = $query->orderBy('id', 'desc')->paginate(10);

        return view('admin.accounts.list', compact('users', 'role'));
    }

    public function detail($role, $id)
    {

        $user = User::findOrFail($id);
        if ($user->role === 'student') {
            // 1. Lấy thông tin học sinh
            $classIds = DB::table('class_student')
                ->where('student_id', $id)
                ->pluck('class_id');
            // 2. Lấy thông tin lớp học dựa trên class_id
            $query = DB::table('classes')
                ->join('courses', 'classes.courses_id', '=', 'courses.id')
                ->whereIn('classes.id', $classIds)
                ->select('classes.*', 'courses.name as course_name');

            // 3. Lấy điểm của học sinh trong các lớp học
            $scores = DB::table('scores')
                ->where('student_id', $id)
                ->whereIn('class_id', $classIds)
                ->get()
                ->groupBy('class_id');

            // Filter theo request('status')
            $status = request('status');
            if ($status === 'in_progress') {
                $query->where('status', 'in_progress');
            } elseif ($status === 'completed') {
                $query->where('status', 'completed');
            }
            $allClasses = $query->paginate(12);

            return view('admin.accounts.account-detail', [
                'user' => $user,
                'allClasses' => $allClasses,
                'scores' => $scores,
            ]);
        } elseif ($user->role == "teacher") {
            // 1) Lấy lịch dạy (để dùng modal/khác nếu cần)
            $schedules = DB::table('schedules')
                ->where('teacher_id', $id)
                ->whereNotNull('class_id')
                ->get();

            // 2) class_id duy nhất
            $classIds = $schedules->pluck('class_id')->unique();

            // 3) Query lớp + filter theo request('status')
            $query = DB::table('classes')
                ->join('courses', 'classes.courses_id', '=', 'courses.id')
                ->whereIn('classes.id', $classIds)
                ->select(
                    'classes.id',
                    'classes.name',
                    'classes.status',       // in_progress | completed
                    'classes.number_of_sessions',
                    'courses.total_sessions',
                    'courses.name as course_name'
                );

            $status = request('status'); // all|in_progress|completed
            if ($status === 'in_progress') {
                $query->where('classes.status', 'in_progress');
            } elseif ($status === 'completed') {
                $query->where('classes.status', 'completed');
            }

            $allClasses = $query->paginate(6); // đã lọc server-side

            // 4) Sĩ số mỗi lớp
            $countStudent = DB::table('class_student')
                ->select('class_id', DB::raw('count(student_id) as student_count'))
                ->whereIn('class_id', $classIds)
                ->groupBy('class_id')
                ->pluck('student_count', 'class_id');

            return view('admin.accounts.account-detail', [
                'user' => $user,
                'schedules' => $schedules,
                'allClasses' => $allClasses,
                'countStudent' => $countStudent,
            ]);
        } else {
            // Trường hợp khác (admin, v.v.)
            $user = User::findOrFail($id);
            return view('admin.accounts.account-detail', compact('user', 'role'));
        }
    }
    public function schedules($teacher_id, $class_id)
    {

        $schedules = DB::table('schedules')
            ->where('teacher_id', $teacher_id)
            ->where('class_id', $class_id)
            ->get();

        return response()->json([
            'success' => true,
            'schedules' => $schedules,
        ]);

    }

    // Thêm người dùng
    public function accountAdd()
    {
        // Kiểm tra quyền của người dùng hiện tại
        if (Auth::user()->role == 'staff') {
                return redirect()->route('admin.account')
                    ->with('error', 'Bạn không có quyền thực hiện thao tác này');
        }


        return view('admin.accounts.add-user');
    }

    public function accountStore(Request $request)
    {
        // Kiểm tra quyền của người dùng hiện tại
        if (Auth::user()->role == 'staff') {
                return redirect()->route('admin.account')
                    ->with('error', 'Bạn không có quyền thực hiện thao tác này');
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
            'role'      => 'required|in:student,teacher,addmin,staff',
            'mission'   =>  'nullable|in:train,accountant'
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
        // mã tăng tự động 01 -> 00, theo định dạng 
        $validated['snake_case'] = User::nextSequentialCode($request->role);

        // Mã hóa mật khẩu
        $validated['password'] = Hash::make($validated['username']);
        $validated['birth_date'] = $validated['birth_date'] ?: null;

        $user = User::create($validated);


        return redirect()->route('admin.account')->with('success', 'Thêm người dùng thành công');
    }


    // Thêm học sinh giáo viên
    public function add($role)
    {
        // Kiểm tra quyền của người dùng hiện tại
        if (Auth::user()->role == 'staff') {
            if ($role == 'admin' || $role == 'staff') {
                return redirect()->route('admin.account')
                    ->with('error', 'Bạn không có quyền thêm người dùng với vai trò này');
            }
        }


        return view('admin.accounts.account-add');
    }

    public function store(Request $request, $role)
    {
        // Kiểm tra quyền của người dùng hiện tại
        if (Auth::user()->role == 'staff') {
            if ($role == 'admin' || $role == 'staff') {
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
            'mission'   =>  'nullable|in:train,accountant'
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
        // mã tăng tự động 01 -> 00, theo định dạng 
        $validated['snake_case'] = User::nextSequentialCode($role);

        // Mã hóa mật khẩu
        $validated['role'] = $role;
        $validated['password'] = Hash::make($validated['username']);
        $validated['birth_date'] = $validated['birth_date'] ?: null;

        $user = User::create($validated);


        return redirect()->route('admin.account.list', ['role' => $role])->with('success', 'Thêm người dùng thành công');
    }

    public function edit($role, $id)
    {
        // Kiểm tra quyền của người dùng hiện tại
        if (Auth::user()->role == 'staff') {
            if ($role == 'admin' || $role == 'staff') {
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
        if (Auth::user()->role == 'staff') {
            if ($role == 'admin' || $role == 'staff') {
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
            'mission'   =>  'nullable|in:train,accountant'
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
        // Nếu nhập mk, ngược lại giữ nguyên mk
        if (!empty($request->password)) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        if(in_array($role, ['teacher', 'student'])){
            return redirect()->route('admin.account.list', ['role' => $role])
            ->with('success', 'Cập nhật người dùng thành công');
        }else{
            return redirect()->route('admin.account')->with('success', 'Sửa người dùng thành công');
        }
        
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
            if (in_array($role, ['student', 'teacher'])) {
                return redirect()->route('admin.account.list', ['role' => $role])->with('success', 'Xóa người dùng thành công');
            } else {
                return redirect()->route('admin.account');
            }
        }

        if (Auth::user()->role == 'staff') {
            return redirect()->route('admin.account.list', ['role' => $role])
                ->with('error', 'Bạn không có quyền xóa người dùng');
        }
    }

    public function trash(Request $request)
    {
        $query = trim($request->query('accountTrash'));
        $role = $request->query('role');
        $gender = $request->query('gender');

        $trashQuery = User::onlyTrashed()->orderBy('deleted_at', 'desc');

        if ($query) {
            $trashQuery->where('name', 'like', "%$query%");
        }

        if ($role) {
            $trashQuery->where('role', $role);
        }

        if ($gender) {
            $trashQuery->where('gender', $gender);
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
