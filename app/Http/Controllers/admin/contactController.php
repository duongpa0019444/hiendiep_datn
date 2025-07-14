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
//    public function contact(Request $request)
// {
//     $query = Contact::with('staff')
//         ->when($request->filled('status'), function ($q) use ($request) {
//             $q->where('status', $request->status);
//         })
//         ->when($request->filled('search'), function ($q) use ($request) {
//             $q->where(function ($sub) use ($request) {
//                 $sub->where('name', 'like', '%' . $request->search . '%')
//                     ->orWhere('phone', 'like', '%' . $request->search . '%')
//                     ->orWhere('pl_content', 'like', '%' . $request->search . '%');
//             });
//         });

//     $contacts = $query->orderBy('created_at', 'desc')
//         ->paginate(10)
//         ->withQueryString();

//     // 🔽 Thêm dòng này:
//     $staffs = User::where('role', 'staff')->get();

//     return view('admin.contact', compact('contacts', 'staffs'));
// }

public function contact(Request $request)
{
    $query = Contact::with('staff')
        ->when(isset($request->status) && $request->status !== '', function ($q) use ($request) {
            $q->where('status', (int) $request->status);
        })
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
    // Xóa tin nhắn hỗ trợ

    // Hiển thị chi tiết tin nhắn hỗ trợ theo id từng tin nhắn
    public function contactDetail($id)
    {
        $contact = Contact::with('staff')->findOrFail($id);
        return view('admin.contactDetail', compact('contact'));
    }
    public function approve($id)
{
    $contact = Contact::findOrFail($id);

    $contact->assigned_to = Auth::id(); // Gán nhân viên hiện tại
    $contact->status = 1; // Đã xử lý
    $contact->save();

    return redirect()->route('admin.contactDetail', $id)->with('success', 'Đã hỗ trợ tin nhắn này.');
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
