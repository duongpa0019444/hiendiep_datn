@extends('admin.admin')

@section('title', 'Chi tiết thông báo biến động học phí')
@section('description', 'Chi tiết thông báo biến động học phí')

@section('content')
    <div class="page-content">
        <div class="container-xxl">
              <nav aria-label="breadcrumb p-0">
                <ol class="breadcrumb py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.noti.coursepayment') }}">Thông báo học phí & thanh toán</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Chi tiết</li>
                </ol>
            </nav>
              <h5>Chi tiết thông báo biến động học phí & thanh toán</h5>
            <div class="card p-4">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <a href="{{ route('admin.noti.coursepayment') }}" class="d-flex align-items-center" role="button">
                            <i class="bx bx-arrow-back fs-18 align-middle"></i>
                            <h5 class="offcanvas-title text-truncate ms-1" id="email-readLabel">Quay lại</h5>
                        </a>
                    </div>

                    <p class="mb-0 text-muted">
                        <i class="far fa-calendar-alt me-1"></i>
                        <strong>Ngày thông báo:</strong>
                        <span id="notification-date">
                            {{ $notificationUser->created_at->format('d/m/Y') }}
                        </span>
                    </p>
                </div>
                <hr class="my-3" />

                <!-- Payment Details -->
                <div class="mb-4">
                    <h6 class="d-flex align-items-center fs-5 mb-3">
                        <i class="fas fa-wallet text-success me-2"></i> Thông tin thanh toán
                    </h6>
                    <div class="row g-3 ms-2">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <i class="fas fa-user-graduate me-2 fs-6"></i>
                                <strong>Học sinh:</strong>
                                {{ $payment->user->name ?? '---' }}
                                - (
                                {{ $payment->user->birth_date ? \Carbon\Carbon::parse($payment->user->birth_date)->format('d/m/Y') : '---' }}
                                )

                            </p>
                            <p class="mb-2">
                                <i class="fas fa-id-card me-2 fs-6"></i>
                                <strong>Lớp học:</strong>
                                {{ $payment->class->name }}
                            </p>
                            <p class="mb-2">
                                <i class="fas fa-book-open me-2 fs-6"></i>
                                <strong>Khóa học:</strong>
                                {{ $payment->course->name ?? '---' }}
                            </p>
                            <p class="mb-2">
                                <i class="fas fa-money-bill-wave me-2 fs-6"></i>
                                <strong>Số tiền:</strong>
                                {{ number_format($payment->amount, 0, ',', '.') }} VND
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <i
                                    class="fas fa-check-circle me-2 text-{{ $payment->status == 'paid' ? 'success' : 'danger' }} fs-6"></i>
                                <strong>Trạng thái thanh toán:</strong>
                                <span
                                    class="badge bg-{{ $payment->status == 'paid' ? 'success' : 'danger' }}-subtle text-{{ $payment->status == 'paid' ? 'success' : 'danger' }}">
                                    {{ $payment->status == 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                                </span>
                            </p>
                            <p class="mb-2">
                                <i class="far fa-calendar-check me-2 fs-6"></i>
                                <strong>Ngày thanh toán:</strong>
                                {{ optional($payment->payment_date)->format('d/m/Y') ?? '---' }}
                            </p>
                            <p class="mb-2">
                                <i class="fas fa-credit-card me-2 fs-6"></i>
                                <strong>Phương thức thanh toán:</strong>
                                {{ $payment->method ? ($payment->method == 'Bank Transfer' ? 'Thanh toán chuyển khoản' : 'Nộp tiền mặt') : '---' }}
                            </p>
                            <p class="mb-2">
                                <i class="fas fa-user-edit me-2 fs-6"></i>
                                <strong>Người cập nhật:</strong>
                                <span id="updated-by">
                                    {{ $notificationUser->notification->updatedBy->name }} - (
                                    {{ $notificationUser->notification->updatedBy->role == 'student' ? 'Học sinh - Tự thanh toán qua hệ thống' : 'Nhân viên' }})
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Notification Content -->
                <div class="mb-4">
                    <h6 class="d-flex align-items-center fs-5 mb-3">
                        <i class="fas fa-file-alt text-info me-2"></i> Nội dung
                    </h6>
                    <div class="card bg-light-subtle border-0 p-3">
                        <p id="notification-content" class="text-muted mb-0">
                            {{ $notificationUser->notification->title }}
                        </p>
                    </div>
                </div>

                <!-- Attachments -->
                <hr class="my-3" />

            </div>
        </div>
    </div>
@endsection
