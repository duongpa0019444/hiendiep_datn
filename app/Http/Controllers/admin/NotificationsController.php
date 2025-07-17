<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\classes;
use App\Models\notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationsController extends Controller
{
    public function index(){
       $notis = DB::table('notifications as n')
                ->join('users as u', 'u.id', '=', 'n.created_by')
                ->select(
                    'n.*',
                    'u.name',
                    'u.avatar'
                )
                ->orderBy('n.created_at', 'desc') // Sắp xếp theo thời gian tạo mới nhất
                ->get();
        $classes = classes::all();
        return view('admin.notifications.index', compact('notis', 'classes'));
    }

    public function filter(Request $request)
        {
            $role = $request->role;
            $query = Notification::query()
                                ->join('users as u', 'u.id', '=', 'notifications.created_by')
                                ->select('notifications.*', 'u.name', 'u.avatar');

            if ($role) {
                $query->where('target_role', $role); // cột lưu vai trò trong DB
            }
            $notis = $query->latest()->get();

            return response()->json(['success' => true, 'data' => $notis]);
        }

   public function seed(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'target_role' => 'required|in:all,student,teacher,staff,class',
        // 'class_id' => 'required_if:target_role,class|exists:classes,id',
    ]);

    notification::create([
        'title' => $request->title,
        'content' => $request->content,
        'target_role' => $request->target_role,
        'class_id' => $request->target_role === 'class' ? $request->class_id : null,
        'created_by' => $request->created_by,
    ]);

    return redirect()->back()->with('success', 'Thông báo đã được gửi thành công.');
}



        public function destroy(Request $request)
        {
             $noti = notification::find($request->id);
            if (!$noti) {
                return response()->json(['success' => false, 'message' => 'Không tìm thấy thông báo']);
            }
             $noti->delete();
            return response()->json(['success' => true, 'messege'=> 'Xóa thành công']);
        }

}
