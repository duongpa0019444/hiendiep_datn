<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class loginController extends Controller
{
    //
    public function login(Request $request)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $request->validate([
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:6',
            ], [
                'email.required' => 'Email không được để trống!',
                'email.string' => 'Email phải là dạng chuỗi!',
                'email.email' => 'Email không đúng định dạng!',
                'email.max' => 'Email không được vượt quá 255 ký tự!',

                'password.required' => 'Mật khẩu không được để trống!',
                'password.string' => 'Mật khẩu phải là dạng chuỗi!',
                'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự!',
            ]);


            $user = User::where('email', '=', $request->input('email'))->first();

            if ($user && Hash::check($request->input('password'), $user->password)) {
                // dd($user);   
                Auth::login($user);
                // sau khi đăng nhập nên chuyển về trang chủ
                if($user->role === 'admin' || $user->role === 'staff'){
                    return redirect()->route('admin.dashboard')->with('success', 'Đăng nhập thành công!');
                }else{
                    return redirect()->route('home')->with('success', 'Đăng nhập thành công!');

                }

            } else {
                return redirect()->back()->with('error', 'Sai email hoặc mật khẩu!');
            }
        } else {
            return view('auth.login');
        }
    }

    public function register(Request $request)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate dữ liệu đầu vào
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'phone' => 'required|string|regex:/^[0-9]{10,15}$/|unique:users,phone',
                'password' => 'required|string|min:6|confirmed',
            ], [
                'name.required' => 'Tên không được để trống!',
                'name.string' => 'Tên phải là dạng chuỗi!',
                'name.max' => 'Tên không được vượt quá 255 ký tự!',

                'email.required' => 'Email không được để trống!',
                'email.string' => 'Email phải là dạng chuỗi!',
                'email.email' => 'Email không đúng định dạng!',
                'email.max' => 'Email không được vượt quá 255 ký tự!',
                'email.unique' => 'Email đã được sử dụng!',

                'phone.required' => 'Số điện thoại không được để trống!',
                'phone.string' => 'Số điện thoại phải là dạng chuỗi!',
                'phone.regex' => 'Số điện thoại phải chứa 10-15 chữ số!',
                'phone.unique' => 'Số điện thoại đã được sử dụng!',

                'password.required' => 'Mật khẩu không được để trống!',
                'password.string' => 'Mật khẩu phải là dạng chuỗi!',
                'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự!',
                'password.confirmed' => 'Xác nhận mật khẩu không khớp!',
            ]);

            // Tạo người dùng mới
            $user = User::create([
                'name' => $request->input('name'),
                'phone' => $request->input('phone'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'role' => 'student'

            ]);

           
            // Đăng nhập tự động sau khi đăng ký (tùy chọn)
            // Auth::login($user);
            if ($user && Hash::check($request->input('password'), $user->password)) {
                // dd($user);   
                Auth::login($user);
                // sau khi đăng nhập nên chuyển về trang chủ
                return redirect()->route('home')->with('success', 'Đăng nhập thành công!');
            }

            // Redirect về trang thành công
            return redirect()->route('auth.login')->with('success', 'Đăng ký thành công!');
        } else {
            return view('auth.register');
        }
    }

     public function logout()
    {
        session()->forget('user');
        return redirect()->route('home')->with('success', 'Đăng xuất thành công!');
    }
  

    public function showForgotPasswordForm()
    {   
        
        return view('auth/forgot-password');
    }

    // Gửi email chứa liên kết đặt lại mật khẩu
    public function sendResetLinkEmail(Request $request)
    {
        // Validate dữ liệu
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // // Gửi liên kết reset
        // $status = Password::sendResetLink(
        //     $request->only('email')
        // );
         // Trả )về phản hồi
        // return $status === Password::RESET_LINK_SENT
        //             ? back()->with('status', 'Liên kết đặt lại mật khẩu đã được gửi đến email của bạn!')
        //             : back()->withErrors(['email' => 'Không thể gửi liên kết. Vui lòng kiểm tra lại email.']);

        // Tạo mã số ngẫu nhiên (6 chữ số)
        $token = sprintf("%06d", mt_rand(100000, 999999));

        // Lưu mã số vào bảng password_reset_tokens
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => $token,
                'created_at' => now()
            ]
        );
            session(['reset_email' => $request->email]);
        // Gửi email chứa mã số
        try {
            Mail::raw("Mã số đặt lại mật khẩu của bạn là: $token", function ($message) use ($request) {
                $message->to($request->email)
                        ->subject('Mã số đặt lại mật khẩu');
            });

            return redirect()->route('password.reset')->with('success', 'Mã số đã được gửi đến email của bạn!');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Không thể gửi email. Vui lòng thử lại sau.']);
        }

       
    }

    // Hiển thị form đặt lại mật khẩu
    public function showResetPasswordForm()
    {   
        
        return view('auth/reset-password');
    }

    // Xử lý đặt lại mật khẩu
    public function resetPassword(Request $request)
    {
        // Validate dữ liệu
        $request->validate([
            'token' => 'required|digits:6',
            'password' => 'required|min:8|confirmed',
        ]);

        // Lấy email từ session
        $email = session('reset_email');
        if (!$email) {
            return back()->withErrors(['token' => 'Phiên làm việc đã hết hạn. Vui lòng yêu cầu mã số mới.']);
        }

        // Kiểm tra mã số
        $reset = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->where('token', $request->token)
            ->first();

        if (!$reset) {
            return back()->withErrors(['token' => 'Mã số không hợp lệ hoặc đã hết hạn.']);
        }

        // Kiểm tra thời hạn token (10 phút)
        if ($reset->created_at && now()->diffInMinutes($reset->created_at) > 10) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            session()->forget('reset_email');
            return back()->withErrors(['token' => 'Mã số đã hết hạn. Vui lòng yêu cầu mã số mới.']);
        }

        // Cập nhật mật khẩu
        $user = \App\Models\User::where('email', $email)->first();
        if (!$user) {
            return back()->withErrors(['token' => 'Người dùng không tồn tại.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Xóa token và session
        DB::table('password_reset_tokens')->where('email', $email)->delete();
        session()->forget('reset_email');

        // Trả về phản hồi
        return redirect()->route('auth.login')->with('success', 'Mật khẩu đã được đặt lại thành công!');
    }
}
