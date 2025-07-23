<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\classes;
use App\Models\notification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationsController extends Controller
{
public function index(){
    $now = Carbon::now(); // dùng đúng alias đã import

    $notis = DB::table('notifications as n')
        ->join('users as u', 'u.id', '=', 'n.created_by')
        ->select('n.*', 'u.name', 'u.avatar')
        ->where(function ($q) use ($now) {
            $q->whereNull('n.start_time')->orWhere('n.start_time', '<=', $now);
        })
        ->where(function ($q) use ($now) {
            $q->whereNull('n.end_time')->orWhere('n.end_time', '>=', $now);
        })
        ->orderBy('n.created_at', 'desc')
        ->get();

    $classes = classes::all();
    return view('admin.notifications.index', compact('notis', 'classes'));
}


    public function filter(Request $request)
{
    $role = $request->role;
    $now = now();

    $query = Notification::query()
        ->join('users as u', 'u.id', '=', 'notifications.created_by')
        ->select('notifications.*', 'u.name', 'u.avatar')
        ->where(function ($q) use ($now) {
            $q->whereNull('start_time')->orWhere('start_time', '<=', $now);
        })
        ->where(function ($q) use ($now) {
            $q->whereNull('end_time')->orWhere('end_time', '>=', $now);
        });

    if ($role) {
        $query->where('target_role', $role);
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
        'class_id' => 'required_if:target_role,class|nullable|exists:classes,id',
        'start_time' => 'nullable|date',
        'end_time' => 'nullable|date|after_or_equal:start_time',
    ]);

    notification::create([
        'title' => $request->title,
        'content' => $request->content,
        'target_role' => $request->target_role,
        'class_id' => $request->target_role === 'class' ? $request->class_id : null,
        'start_time' => $request->start_time,
        'end_time' => $request->end_time,
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
