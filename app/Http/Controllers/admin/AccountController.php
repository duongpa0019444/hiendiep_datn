<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\classes;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
        $search = trim($request->query('queryAccount'));

        $query = User::query();

        if ($filter) {
            $query->where('role', $filter);
        }

        if ($search) {
            $query->where('name', 'like', "%$search%");
        }

        $role = $query->orderBy('id', 'desc')->paginate(10);

        return view('admin.accounts.account', compact('role', 'filter', 'search', 'roleCounts'));
    }




    public function list($role)
    {
        $role = User::where('role', $role)->orderBy('id', 'desc')->paginate(10);


        return view('admin.accounts.list', compact('role'));
    }

    public function add()
    {
        return view('admin.accounts.account-add');
    }

    public function store(Request $request, $role)
    {

        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'username'       => 'required|string|max:255|unique:users',
            'email'      => 'nullable|string|email|max:255',
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
        $info = User::find($id);
        return view('admin.accounts.account-edit', compact('info'));
    }

    public function update(Request $request, $role, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'username'   => 'required|string|max:255|unique:users,name,' . $user->id,
            'email'      => 'nullable|string||min:6',
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

    //check người dùng có đang liên kết với các bảng khác không
    public function check($id)
    {

        $classes = DB::table('class_student')
            ->join('classes', 'class_student.class_id', '=', 'classes.id')
            ->where('class_student.student_id', $id)
            ->select('classes.id', 'classes.name')
            ->get();
        $schedules = DB::table('schedules as sd')
            ->join('classes as c', 'sd.class_id', '=', 'c.id')
            ->where('sd.teacher_id', $id)
            ->select('sd.id', 'c.name as class_name')
            ->get();

        $payments = DB::table('course_payments')
            ->where('student_id', $id)
            ->select('id', 'amount', 'method', 'note', 'created_at')
            ->get();

        $quizzes = DB::table('quiz_attempts')
            ->where('user_id', $id)
            ->select('id', 'quiz_id', 'score', 'submitted_at')
            ->get();

        return response()->json([
            'classes' => $classes,
            'payments' => $payments,
            'quizzes' => $quizzes,
            'schedules' => $schedules,
        ]);
    }


    public function delete($role, $id)
    {

        User::find($id)->delete();
        return redirect()->route('admin.account.list', ['role' => $role])->with('success', 'Xóa người dùng thành công');
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
