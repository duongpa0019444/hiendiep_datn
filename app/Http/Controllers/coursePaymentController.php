<?php

namespace App\Http\Controllers;

use App\Models\coursePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as FacadesRequest;

class coursePaymentController extends Controller
{
    //
    public function index(Request $request)
    {
        $payments = coursePayment::with(['user', 'course', 'class'])->orderByDesc('created_at')->paginate(10);

        // Kiểm tra nếu là AJAX request
        if ($request->ajax()) {
            return response()->json([
                'payments' => $payments,
                'pagination' => $payments->links('pagination::bootstrap-5')->toHtml()
            ]);
        }


        return view('admin.course_payments.coursePayment-list', compact('payments'));
    }


    public function filter(Request $request)
    {
        $payments = $this->getFilteredPayments($request);
        $payments->appends($request->all());
        return response()->json([
            'payments' => $payments,
            'pagination' => $payments->links('pagination::bootstrap-5')->toHtml()
        ]);
    }

    private function getFilteredPayments(Request $request)
    {
        $query = coursePayment::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('method')) {
            $query->where('method', $request->method);
        }

        if ($request->filled('status_class')) {
            $query->whereHas('class', function ($q) use ($request) {
                $q->where('status', $request->status_class);
            });
        }
        if ($request->filled('class_id')) {
            $query->whereHas('class', function ($q) use ($request) {
                $q->where('id', $request->class_id);
            });
        }
        if ($request->filled('keyword')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->keyword . '%');
            });
        }

        return $query->with(['user', 'course', 'class'])->orderByDesc('created_at')->paginate($request->limit);
    }


    public function update(Request $request, $id)
    {

        try {

            $payment = CoursePayment::findOrFail($id);

            $payment->payment_date = $request->payment_date;
            $payment->method = $request->method;
            $payment->status = $request->status;
            $payment->note = $request->note;

            $payment->save();

            // Trả về JSON với dữ liệu bản ghi
            return response()->json([
                'message' => 'Cập nhật thanh toán thành công',
                'payment' => $payment->load(['user', 'class', 'course'])
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Cập nhật thất bại: ' . $e->getMessage()]);
        }
    }

    public function detail($id)
    {
        $payment = coursePayment::with(['user', 'course', 'class'])->findOrFail($id);
        return response()->json([
            'payment' => $payment
        ]);
    }


    public function delete($id)
    {
        $payment = coursePayment::findOrFail($id);
        $payment->delete();
        return response()->json([
            'success' => 'Xóa thanh toán thành công'
        ]);
    }
}
