<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class AccountController extends Controller
{
    public function account()
    {
        $roleCounts = User::select('role', DB::raw('count(*) as total'))
            ->groupBy('role')
            ->pluck('total', 'role') // ['admin' => 10, 'teacher' => 5, ...]
            ->toArray();


        return view('admin.accounts.account', compact('roleCounts'));
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
            'name'       => 'required|string|max:255|unique:users',
            'email'      => 'required|email|max:255|unique:users',
            'phone' => 'required|digits_between:8,20',
            'password'   => 'required|string|min:6',
            'gender'     => 'required|in:boy,girl',
            'birth_date' => 'required|date',
            'avatar'     => 'required|image|mimes:jpg,jpeg,png|max:2048',
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
        $validated['password'] = Hash::make($validated['password']);

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
            'name'       => 'required|string|max:255|unique:users,name,' . $user->id,
            'email'      => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone'      => 'required|digits_between:8,20',
            'password'   => 'required|string|min:6',
            'gender'     => 'required|in:boy,girl',
            'birth_date' => 'required|date',
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
        User::find($id)->delete();
        return redirect()->route('admin.account.list', ['role' => $role])->with('success', 'Xóa người dùng thành công');
    }
}
