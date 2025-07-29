<?php
namespace App\Http\Controllers;

use App\Models\contact;
// use App\Events\SupportRequestCreated;
use Illuminate\Http\Request;

class SupportRequestController extends Controller
{

   public function store(Request $request)
{
    $data = $request->validate([
        'pl_content' => 'required|in:khiếu nại,góp ý,hỗ trợ,đăng ký',
        'name'       => 'required|string|max:100',
        'phone'      => 'required|digits_between:9,15',
        'message'    => 'required|string|max:1000',
    ], [
        'pl_content.required' => 'Vui lòng chọn phân loại.',
        'pl_content.in'       => 'Phân loại không hợp lệ.',
        'name.required'       => 'Vui lòng nhập tên.',
        'phone.required'      => 'Vui lòng nhập số điện thoại.',
        'phone.digits_between'=> 'Số điện thoại phải từ 9 đến 15 chữ số.',
        'message.required'    => 'Vui lòng nhập nội dung tin nhắn.',
    ]);

    $data['status'] = 'đợi xử lý';

    Contact::create($data);

    return redirect()->back()->with('success', 'Tin nhắn đã gửi thành công !');
}


}
?>
