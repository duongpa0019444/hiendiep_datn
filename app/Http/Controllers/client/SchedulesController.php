<?php 
    
namespace App\Http\Controllers\client;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchedulesController extends Controller
{
    public function index()
    {
        // Lấy id người dùng từ Auth
        $userId = Auth::user()->id;
            dd(Auth::user()->role);

        // Kiểm tra vai trò của người dùng
        if (Auth::user()->role == "teacher") {
            // Nếu là giáo viên, trả về view lịch học của giáo viên
            // return view('client.accounts.teachers.schedule', compact('userId'));
        }
        // Nếu là học sinh, trả về view lịch học của học sinh
        elseif (Auth::user()->role == "student") {
            // Trả về view lịch học của học sinh
            return view('client.accounts.students.schedule', compact('userId'));
        }
        // Nếu không phải là giáo viên hoặc học sinh, có thể trả về một thông báo lỗi hoặc redirect
        else {
            return redirect()->back()->with('error', 'Unauthorized access');
        }   
    }
}
