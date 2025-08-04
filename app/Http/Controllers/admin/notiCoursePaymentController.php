<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class notiCoursePaymentController extends Controller
{

    public function index(Request $request)
    {
        $id = Auth::user()->id;
        $notifications = NotificationUser::with('notification')
            ->where('user_id', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Kiểm tra nếu là AJAX request
        if ($request->ajax()) {
            return response()->json([
                'notifications' => $notifications,
                'pagination' => $notifications->links('pagination::bootstrap-5')->toHtml()
            ]);
        }
        return view('admin.noti_payment.notification-list', compact('notifications'));
    }


    public function filter(Request $request)
    {
        $notifications = $this->getFilteredNotifications($request);

        // Giữ lại tham số lọc cho phân trang
        $notifications->appends($request->all());

        return response()->json([
            'notifications' => $notifications,
            'pagination' => $notifications->links('pagination::bootstrap-5')->toHtml()
        ]);
    }

    private function getFilteredNotifications(Request $request)
    {
        $query = NotificationUser::query()
            ->with(['notification', 'user'])
            ->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('keyword')) {
            $query->whereHas('notification', function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->keyword . '%');
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $query->where('user_id', Auth::user()->id);

        return $query->paginate($request->limit ?? 10);
    }




    public function updateNotiSeen($id)
    {
        // Lấy thông báo cho user hiện tại
        $notificationUser = NotificationUser::find($id);

        if (!$notificationUser) {
            return response()->json([
                'error' => 'Thông báo không tồn tại'
            ], 404);
        }

        if ($notificationUser->user_id !== Auth::id()) {
            return response()->json([
                'error' => 'Bạn không có quyền thao tác thông báo này'
            ], 403);
        }

        $notificationUser->status = 'seen';
        $notificationUser->save();
        $notificationUserItem = NotificationUser::with('notification')->find($id);
        return response()->json([
            'notificationUser' => $notificationUserItem,
            'success' => 'Thông báo đã được đánh dấu là đã đọc'
        ]);
    }

    public function detail($id)
    {
        $notificationUser = NotificationUser::with([
            'notification.coursePayment.user',
            'notification.coursePayment.course',
        ])->findOrFail($id);

        // Kiểm tra quyền: chỉ cho user xem thông báo của mình
        if ($notificationUser->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền xem thông báo này.');
        }

        // Lấy thông tin thanh toán từ quan hệ
        $payment = $notificationUser->notification->coursePayment;

        // Nếu chưa xem thì đánh dấu đã xem
        if ($notificationUser->status !== 'seen') {
            $notificationUser->update(['status' => 'seen']);
        }
        return view('admin.noti_payment.notification-detail', compact('notificationUser', 'payment'));
    }

    public function updateSeenMultiple(Request $request)
    {
        $ids = $request->input('ids', []);
        $updated = NotificationUser::whereIn('id', $ids)->where('user_id', Auth::user()->id)->update(['status' => 'seen']);
        if ($updated) {
            $notifications = NotificationUser::with('notification')->whereIn('id', $ids)->get();
            return response()->json(['success' => true, 'notifications' => $notifications]);
        }
        return response()->json(['success' => false, 'error' => 'Không thể cập nhật trạng thái!']);
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->input('ids', []);
        $deleted = NotificationUser::whereIn('id', $ids)->where('user_id', Auth::user()->id)->delete();
        if ($deleted) {
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'error' => 'Không thể xóa thông báo!']);
    }


}
