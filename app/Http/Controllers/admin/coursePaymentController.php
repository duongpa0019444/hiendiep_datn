<?php

namespace App\Http\Controllers\admin;

use App\Events\PaymentNotificationCreated;
use App\Http\Controllers\Controller;

use App\Models\coursePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Exports\CoursePaymentsExport;
use App\Models\notification;
use App\Models\notificationCoursePayments;
use App\Models\NotificationUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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

    public function trash(Request $request)
    {
        // Lấy các bản ghi đã bị xóa mềm
        $deletedPayments = coursePayment::onlyTrashed()
            ->with([
                'user' => function ($query) {
                    $query->withTrashed();
                },
                'class' => function ($query) {
                    $query->withTrashed();
                },
                'course'
            ])
            ->orderByDesc('deleted_at')
            ->paginate(10);


        if ($request->ajax()) {
            return response()->json([
                'deletedPayments' => $deletedPayments,
                'pagination' => $deletedPayments->links('pagination::bootstrap-5')->toHtml()
            ]);
        }

        // Trả về view hiển thị thùng rác
        return view('admin.course_payments.coursePayment-trashCan', compact('deletedPayments'));
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


    public function filterTrash(Request $request)
    {
        $deletedPayments = $this->getFilteredPaymentsTrash($request);
        $deletedPayments->appends($request->all());
        return response()->json([
            'deletedPayments' => $deletedPayments,
            'pagination' => $deletedPayments->links('pagination::bootstrap-5')->toHtml()
        ]);
    }
    private function getFilteredPaymentsTrash(Request $request)
    {
        // Lọc các bản ghi đã bị soft delete
        $query = coursePayment::onlyTrashed();

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

        return $query->with(['user', 'course', 'class'])
            ->orderByDesc('created_at')
            ->paginate($request->limit ?? 10); // fallback limit
    }




    public function update(Request $request, $id)
    {

        try {

            $payment = CoursePayment::findOrFail($id);

            //Thông tin cũ
            $oldStatus = $payment->status;
            $oldMethod = $payment->method;
            $oldDate   = $payment->payment_date;

            // Thông tin mới
            $newStatus = $request->status;
            $newMethod = $request->method;
            $newDate   = $request->payment_date;

            // Gán trạng thái mới
            $payment->method = $request->method;
            $payment->status = $request->status;
            $payment->note = $request->note;

            // Xử lý ngày thanh toán
            if (empty($newDate)) {
                if ($oldStatus === 'unpaid' && $newStatus === 'paid') {
                    $payment->payment_date = now(); // từ chưa trả sang trả thì lấy ngày hiện tại
                } elseif ($oldStatus === 'paid' && $newStatus === 'unpaid') {
                    $payment->payment_date = null; // từ trả rồi sang hủy trả thì bỏ ngày
                }
                // nếu giữ nguyên thì không đổi
            } else {
                $payment->payment_date = $newDate; // user chọn ngày thì luôn lấy ngày đó
            }


            $payment->save();

            //thông tin mới
            $newStatus = $request->status;
            $newMethod = $request->method;
            $newDate   = $request->payment_date;

            $titleNotifycation = '';
            $icon = '';
            $background = '';

            // 1. Nếu có thay đổi trạng thái
            if ($oldStatus !== $newStatus) {
                if ($oldStatus === 'unpaid' && $newStatus === 'paid') {
                    // Xác nhận học phí đã thanh toán
                    $action = $newMethod === 'Cash'
                        ? 'đã xác nhận học phí nộp TIỀN MẶT'
                        : 'đã xác nhận học phí CHUYỂN KHOẢN';

                    $titleNotifycation = Auth::user()->name . ' ' . $action . ' cho học sinh ' . $payment->user->name;

                    $icon = '<i class="fas fa-money-bill-wave text-success"></i>';
                    $background = 'bg-soft-success';
                } elseif ($oldStatus === 'paid' && $newStatus === 'unpaid') {
                    // Hủy xác nhận thanh toán
                    $titleNotifycation = Auth::user()->name . ' đã chuyển trạng thái học phí của '
                        . $payment->user->name . ' về "Chưa thanh toán".';

                    $icon = '<i class="fas fa-undo text-danger"></i>';
                    $background = 'bg-soft-danger';
                } else {
                    // Trường hợp khác nếu sau này có thêm trạng thái
                    $titleNotifycation = Auth::user()->name . ' đã cập nhật trạng thái thanh toán của '
                        . $payment->user->name . ' thành "' . $newStatus . '".';

                    $icon = '<i class="fas fa-edit text-primary"></i>';
                    $background = 'bg-soft-primary';
                }

                // 2. Không đổi trạng thái, nhưng đổi phương thức
            } elseif ($oldMethod !== $newMethod) {
                $methodText = $newMethod === 'Cash' ? 'TIỀN MẶT' : 'CHUYỂN KHOẢN';

                $titleNotifycation = Auth::user()->name . ' đã cập nhật phương thức thanh toán cho '
                    . $payment->user->name . ' thành ' . $methodText . '.';

                $icon = '<i class="fas fa-exchange-alt text-warning"></i>';
                $background = 'bg-soft-warning';

                // 3. Không đổi trạng thái, không đổi phương thức, nhưng đổi ngày thanh toán
            } elseif ($oldDate !== $newDate) {
                $formattedDate = \Carbon\Carbon::parse($newDate)->format('H:i d/m/Y');
                $titleNotifycation = Auth::user()->name . ' đã cập nhật ngày thanh toán học phí của '
                    . $payment->user->name . ' thành ' . $formattedDate . '.';

                $icon = '<i class="fas fa-calendar-alt text-info"></i>';
                $background = 'bg-soft-info';

                // 4. Các thay đổi khác (ghi chú, note, v.v.)
            } else {
                $titleNotifycation = Auth::user()->name . ' đã cập nhật thông tin thanh toán của '
                    . $payment->user->name . '.';

                $icon = '<i class="fas fa-edit text-primary"></i>';
                $background = 'bg-soft-primary';
            }


            $noti = notificationCoursePayments::create([
                'title' => $titleNotifycation,
                'course_payment_id' => $id,
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
                'icon' => $icon,
                'background' => $background,
            ]);

            $notificationUser = [];

            $users = User::whereIn('role', ['admin', 'staff'])->where('id', '!=', Auth::user()->id)->get();
            foreach ($users as $user) {
                $notificationUser[] = [
                    'notification_id' => $noti->id,
                    'user_id' => $user->id,
                    'status' => 'unseen',
                    'created_at' => now()
                ];
            }
            NotificationUser::insert($notificationUser);

            $noti = $noti->fresh();
            event(new PaymentNotificationCreated($noti));

            $this->logAction(
                'update',
                coursePayment::class,
                $payment->id,
                Auth::user()->name . ' đã cập nhật thanh toán học phí: ' . $payment->id
            );
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

        $this->logAction(
            'delete',
            coursePayment::class,
            $payment->id,
            Auth::user()->name . ' đã xóa thanh toán học phí: ' . $payment->id
        );

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



    public function showPaymentStudent()
    {
        $userId = Auth::id();
        $payments = CoursePayment::with(['user', 'class', 'course'])
            ->where('student_id', $userId)
            ->where('status', 'unpaid')
            ->get();

        return response()->json($payments);
    }

    public function updatePayment(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'error' => 'Bạn cần đăng nhập để thực hiện hành động này'
            ], 401);
        }

        $request->validate([
            'paidContent' => 'required|string|max:255',
        ]);

        $id = Auth::id();
        $payments = CoursePayment::where('student_id', $id)
            ->where('status', 'unpaid')
            ->get();

        if ($payments->isEmpty()) {
            return response()->json([
                'error' => 'Không tìm thấy thanh toán chưa hoàn thành'
            ], 404);
        }

        try {
            foreach ($payments as $payment) {
                $payment->status = 'paid';
                $payment->payment_code = $request->paidContent;
                $payment->method = 'Bank Transfer';
                $payment->payment_date = now();
                $payment->save();

                // Thông báo
                $titleNotifycation = Auth::user()->name . ' đã chuyển khoản học phí.';
                $icon = '<i class="fas fa-university text-info"></i>';
                $background = 'bg-soft-info';

                $noti = notificationCoursePayments::create([
                    'title' => $titleNotifycation,
                    'icon' => $icon,
                    'background' => $background,
                    'course_payment_id' => $payment->id,
                    'created_by' => $id,
                    'updated_by' => $id
                ]);

                $users = User::whereIn('role', ['admin', 'staff'])
                    ->where('id', '!=', $id) // loại trừ người đang đăng nhập
                    ->get();

                $notificationUser = [];
                foreach ($users as $user) {
                    $notificationUser[] = [
                        'notification_id' => $noti->id,
                        'user_id' => $user->id,
                        'status' => 'unseen',
                        'created_at' => now()
                    ];
                }
                NotificationUser::insert($notificationUser);

                event(new PaymentNotificationCreated($noti));

                $this->logAction(
                    'update',
                    coursePayment::class,
                    $payment->id,
                    Auth::user()->name . ' đã cập nhật thanh toán học phí: ' . $payment->id
                );
            }

            return response()->json([
                'success' => 'Cập nhật thanh toán thành công',
                'payment_code' => $request->paidContent
            ]);
        } catch (\Exception $e) {
            Log::error('Lỗi khi cập nhật thanh toán', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'error' => 'Lỗi khi cập nhật thanh toán: ' . $e->getMessage()
            ], 500);
        }
    }
}
