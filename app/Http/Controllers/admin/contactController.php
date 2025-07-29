<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class ContactController extends Controller
{
    // Hiển thị danh sách tin nhắn hỗ trợ
    public function contact(Request $request)
    {
        $query = Contact::with('staff')
            // Lọc trạng thái
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('status', $request->status);
            })

            // Lọc phân loại
            ->when($request->filled('pl_content'), function ($q) use ($request) {
                $q->where('pl_content', $request->pl_content);
            })

            // Lọc theo nhân viên xử lý
            ->when($request->filled('assigned_to'), function ($q) use ($request) {
                $q->where('assigned_to', $request->assigned_to);
            })

            // Lọc theo ngày bắt đầu
            ->when($request->filled('from_date'), function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->from_date);
            })

            // Lọc theo ngày kết thúc
            ->when($request->filled('to_date'), function ($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->to_date);
            })

            // Tìm kiếm tên/sđt/phân loại
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where(function ($sub) use ($request) {
                    $sub->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('phone', 'like', '%' . $request->search . '%')
                        ->orWhere('pl_content', 'like', '%' . $request->search . '%');
                });
            });

        $contacts = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $staffs = User::where('role', 'staff')->get();

        return view('admin.contact', compact('contacts', 'staffs'));
    }


    // Hiển thị chi tiết tin nhắn hỗ trợ theo id từng tin nhắn
    public function contactDetail($id)
    {
        $contact = Contact::with('staff')->findOrFail($id);
        return view('admin.contactDetail', compact('contact'));
    }
    public function approve($id)
    {
        $contact = Contact::findOrFail($id);
        if($contact->status == 0){
            $contact->assigned_to = Auth::id(); // Gán nhân viên hiện tại
            $contact->status = 1; // Đã xử lý
            $contact->save();
            return redirect()->route('admin.contactDetail', $id)->with('success', 'Đã hỗ trợ thông tin này.');
        }else{
            return redirect()->route('admin.contactDetail', $id)->with('error', 'Đã có người hỗ trợ thông tin này.');

        }

    }

    public function reject($id)
    {
        $contact = Contact::findOrFail($id);

        $contact->status = 2; // Ví dụ: 2 = không hỗ trợ
        $contact->save();

        return redirect()->route('admin.contactDetail', $id)->with('success', 'Đã từ chối hỗ trợ.');
    }
    public function delete($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return redirect()->route('admin.contact')->with('success', 'Xóa tin nhắn thành công.');
    }
}
