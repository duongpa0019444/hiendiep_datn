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
    'pl_content' => 'required',
    'name' => 'required',
    'phone' => 'required',
    'message' => 'required',
]);

// Gán status mặc định
$data['status'] = 'đợi xử lý';

$support = Contact::create($data);

return back()->with('success', 'Tin nhắn đã gửi. Chúng tôi sẽ hỗ trợ bạn sớm!');

        

        $support = contact::create($data);

        // broadcast(new SupportRequestCreated($support))->toOthers();

        return back()->with('success', 'Tin nhắn đã gửi. Chúng tôi sẽ hỗ trợ bạn sớm!');
    }

    // public function handle(Request $request, $id)
    // {
        
    //     $support = contact::findOrFail($id);

    //     if ($support->assigned_to) {
    //         return response()->json(['message' => 'Yêu cầu đã có người nhận xử lý!'], 409);
    //     }

    //     $support->assigned_to = auth()->id();
    //     $support->status = 'đã xử lý';
    //     $support->save();

        
    //     return response()->json(['message' => 'Bạn đã nhận xử lý yêu cầu này!']);
    // }
}
?>