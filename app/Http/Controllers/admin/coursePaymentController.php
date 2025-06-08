<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

use App\Models\coursePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Exports\CoursePaymentsExport;
use Maatwebsite\Excel\Facades\Excel;
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
            if ($payment->status == 'paid') {
                $payment->payment_date = $request->payment_date ?? now();
            } else {
                $payment->payment_date = $request->payment_date;
            }
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


    public function Statistics()
    {
        $Statistics = DB::select("
            SELECT
                SUM(CASE WHEN status = 'paid' THEN amount ELSE 0 END) AS total_paid,
                SUM(CASE WHEN status = 'unpaid' THEN amount ELSE 0 END) AS total_unpaid,
                COUNT(CASE WHEN method = 'Cash' AND status = 'paid' THEN 1 END) AS cash_payment_count,
                COUNT(CASE WHEN method = 'Bank Transfer' AND status = 'paid' THEN 1 END) AS bank_transfer_payment_count
            FROM course_payments;
        ");


        return response()->json($Statistics[0]);
    }


    public function show($id)
    {
        $payment = CoursePayment::with(['user', 'class', 'course'])
            ->findOrFail($id);
        return response()->json($payment);
    }



    public function download($id)
    {
        $payment = CoursePayment::with(['user', 'class', 'course'])->findOrFail($id);

        // Kiểm tra trạng thái đã thanh toán
        if ($payment->status !== 'paid') {
            return redirect()->back()->with('error', 'Hóa đơn chỉ được xuất khi đã thanh toán.');
        }

        // Chuẩn hóa tên file
        $studentName = Str::slug($payment->user->name);
        $courseName = Str::slug($payment->course->name ?? 'unknown_course');
        $timestamp = now()->format('Ymd_His');
        // Tạo file PDF từ view
        $pdf = Pdf::loadView('admin.course_payments.coursePayment-invoice', compact('payment'));
        // Tải file
        return $pdf->download("hoa_don_{$studentName}_{$courseName}_{$timestamp}.pdf");
    }



    public function exportExcel(Request $request)
    {
        $filters = $request->only(['keyword', 'class_id', 'status_class', 'status', 'method']);
        return Excel::download(new CoursePaymentsExport($filters), 'danh_sach_thanh_toan.xlsx');
    }
}
