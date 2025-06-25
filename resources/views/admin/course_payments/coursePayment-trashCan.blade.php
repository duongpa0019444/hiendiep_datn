@extends('admin.admin')

@section('title', 'Thùng rác - Thanh toán học phí')
@section('description', 'Danh sách các bản ghi thanh toán học phí đã xóa')
@section('content')
    <div class="page-content">
        <!-- Start Container Fluid -->
        <div class="container-xxl">
            <nav aria-label="breadcrumb p-0">
                <ol class="breadcrumb py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.course_payments') }}">Học phí & Thanh toán</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Thùng rác</li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between mb-1">
                <h4 class="card-title mb-1">Thùng rác - Thanh toán học phí</h4>
                <div class="d-flex gap-2 align-items-center">
                    <a href="{{ route('admin.course_payments') }}" class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1">
                        <iconify-icon icon="mdi:arrow-left" class="fs-20 me-1"></iconify-icon> <span>Quay lại</span>
                    </a>
                </div>
            </div>

            <!-- Trash Payment List -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card p-2">
                        <form method="GET" action="{{ route('admin.course_payments.trash.filter') }}"
                            class="row g-2 d-flex align-items-end" id="searchForm">
                            <input type="hidden" name="limit" id="limit" value="10">

                            <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                                <label for="keyword" class="form-label mb-1">Từ khóa</label>
                                <input type="text" name="keyword" id="keyword" class="form-control form-control-sm"
                                    placeholder="Tên học sinh">
                            </div>

                            <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                                <label for="class_filter" class="form-label mb-1">Lớp</label>
                                <select name="class_id" id="class_filter" class="form-select form-select-sm" data-choices>
                                    <option value="">Tất cả</option>
                                    @foreach (\DB::table('classes')->get() as $class)
                                        <option value="{{ $class->id }}"
                                            {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                                <label for="status_filter" class="form-label mb-1">Trạng thái thanh toán</label>
                                <select name="status" id="status_filter" class="form-select form-select-sm">
                                    <option value="">Tất cả</option>
                                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                                    <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Chưa thanh toán</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-6 col-lg-4 col-xl-3 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-success btn-sm w-50">Lọc</button>
                                <button type="reset" class="btn btn-danger btn-sm w-50">Xóa</button>
                            </div>
                        </form>
                    </div>

                    <div class="card" style="border-radius: 0;">
                        <div class="card-body p-0">
                            <div class="table-responsive table-gridjs">
                                <table class="table rounded align-middle mb-0 table-hover table-centered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Tên học sinh</th>
                                            <th>Lớp</th>
                                            <th>Ngày thanh toán</th>
                                            <th>Số tiền (VNĐ)</th>
                                            <th>Trạng thái</th>
                                            <th>Phương thức</th>
                                            <th>Ghi chú</th>
                                        </tr>
                                    </thead>
                                    <tbody id="body-coursePapyment">
                                        @foreach ($deletedPayments as $payment)
                                            <tr data-payment-id="{{ $payment->id }}">
                                                <td>
                                                    <div class="fw-bold">{{ $payment->user->name }}</div>
                                                    <div class="fs-6">{{ $payment->user->email }}</div>
                                                </td>
                                                <td>
                                                    <div class="fw-bold">{{ $payment->class->name }}</div>
                                                    <div class="fs-6">Khóa: {{ $payment->course->name }}</div>
                                                </td>
                                                <td>
                                                    {{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('H:i d/m/Y') : '' }}
                                                </td>
                                                <td class="text-end">{{ number_format($payment->amount, 0, ',', '.') }}</td>
                                                <td>
                                                    <span class="badge {{ $payment->status == 'paid' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }} py-1 px-2">
                                                        {{ $payment->status == 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                                                    </span>
                                                </td>
                                                <td class="{{ $payment->method == 'Cash' ? 'text-success' : 'text-info' }} py-1 px-2">
                                                    {{ $payment->method == 'Cash' ? 'Tiền mặt' : '' }}
                                                    {{ $payment->method == 'Bank Transfer' ? 'Chuyển khoản' : '' }}
                                                </td>
                                                <td>{{ $payment->note ?? '' }}</td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 m-3">
                            <div id="pagination-wrapper" class="flex-grow-1">
                                {{ $deletedPayments->links('pagination::bootstrap-5') }}
                            </div>

                            <div class="d-flex align-items-center" style="min-width: 160px;">
                                <label for="limit2" class="form-labelFloating mb-0 me-2 small">Hiển thị</label>
                                <select name="limit2" id="limit2" class="form-select form-select-sm"
                                    style="width: 100px;">
                                    <option value="10" selected>10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Container Fluid -->

        <!-- Footer -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 text-center">
                        <script>
                            document.write(new Date().getFullYear())
                        </script> © DỰ ÁN TỐT NGHIỆP CAO ĐẲNG FPT THANH HÓA
                        <iconify-icon icon="iconamoon:heart-duotone"
                            class="fs-18 align-middle text-danger"></iconify-icon>
                        <a href="#" class="fw-bold footer-text" target="_blank">NHÓM 4</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
@endsection

@push('scripts')
    <script>
        const csrfToken = '{{ csrf_token() }}';

        // Reset search
        $('#searchForm').on('reset', function(e) {
            setTimeout(function() {
                window.location.reload();
            }, 10);
        });

        // Handle limit change
        $('#limit2').change(function() {
            const limitValue = $(this).val();
            $('#searchForm #limit').val(limitValue);
            $('#searchForm').submit();
        });

        // Handle search form submission
        $('#searchForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                type: 'GET',
                data: $(this).serialize(),
                success: function(response) {
                    $('#body-coursePapyment').html(renderCoursePayment(response.deletedPayments.data));
                    $('#pagination-wrapper').html(response.pagination);
                },
                error: function(xhr) {
                    console.error('Lỗi khi tìm kiếm:', xhr.responseText);
                    Swal.fire({
                        title: 'Lỗi!',
                        text: 'Không thể tìm kiếm. Vui lòng thử lại.',
                        icon: 'error',
                        confirmButtonClass: 'btn btn-primary w-xs mt-2',
                        buttonsStyling: false
                    });
                }
            });
        });

        // Render deleted payments
        function renderCoursePayment(data) {
            if (data.length === 0) {
                return '<tr><td colspan="8" class="text-center"><div class="alert alert-warning">Không tìm thấy bản ghi nào trong thùng rác</div></td></tr>';
            }

            let html = '';
            data.forEach((payment, index) => {
                html += `
                <tr data-payment-id="${payment.id}">
                    <td>
                        <div class="fw-bold">${payment.user?.name || ''}</div>
                        <div class="fs-6">${payment.user?.email || ''}</div>
                    </td>
                    <td>
                        <div class="fw-bold">${payment.class?.name || ''}</div>
                        <div class="fs-6">Khóa: ${payment.course?.name || ''}</div>
                    </td>
                    <td>${formatDateTime(payment.payment_date)}</td>
                    <td class="text-end">${Number(payment.amount).toLocaleString('vi-VN')}</td>
                    <td>
                        <span class="badge ${payment.status === 'paid' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning'} py-1 px-2">
                            ${payment.status === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán'}
                        </span>
                    </td>
                    <td class="${payment.method === 'Cash' ? 'text-success' : 'text-info'} py-1 px-2">
                        ${payment.method === 'Cash' ? 'Tiền mặt' : (payment.method === 'Bank Transfer' ? 'Chuyển khoản' : '')}
                    </td>
                    <td>${payment.note ?? ''}</td>

                </tr>`;
            });

            return html;
        }

        // Format datetime
        function formatDateTime(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            const pad = num => num.toString().padStart(2, '0');
            const hours = pad(date.getHours());
            const minutes = pad(date.getMinutes());
            const day = pad(date.getDate());
            const month = pad(date.getMonth() + 1);
            const year = date.getFullYear();
            return `${hours}:${minutes} ${day}/${month}/${year}`;
        }

        // Handle pagination
        $(document).on('click', '#pagination-wrapper a', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    $('#body-coursePapyment').html(renderCoursePayment(response.deletedPayments.data));
                    $('#pagination-wrapper').html(response.pagination);
                },
                error: function(xhr) {
                    console.error('Lỗi phân trang:', xhr.responseText);
                    Swal.fire({
                        title: 'Lỗi!',
                        text: 'Không thể tải trang. Vui lòng thử lại.',
                        icon: 'error',
                        confirmButtonClass: 'btn btn-primary w-xs mt-2',
                        buttonsStyling: false
                    });
                }
            });
        });

        // Handle restore payment
        $(document).on('click', '.btn-restore-course-payment', function() {
            const coursePaymentId = $(this).data('coursepayment_id');
            Swal.fire({
                title: 'Khôi phục bản ghi?',
                text: "Bạn có chắc chắn muốn khôi phục bản ghi này?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Vâng, khôi phục!',
                cancelButtonText: 'Không, hủy!',
                confirmButtonClass: 'btn btn-primary w-xs me-2 mt-2',
                cancelButtonClass: 'btn btn-danger w-xs mt-2',
                buttonsStyling: false
            }).then(function(result) {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/course-payments/${coursePaymentId}/restore`,
                        type: 'POST',
                        data: { _token: csrfToken },
                        success: function(response) {
                            Swal.fire({
                                title: 'Đã khôi phục!',
                                text: response.success || 'Bản ghi đã được khôi phục thành công.',
                                icon: 'success',
                                confirmButtonClass: 'btn btn-primary w-xs mt-2',
                                buttonsStyling: false
                            });
                            $(`tr[data-payment-id="${coursePaymentId}"]`).remove();
                            if ($('#body-coursePapyment tr').length === 0) {
                                $('#body-coursePapyment').html('<tr><td colspan="8" class="text-center"><div class="alert alert-warning">Không tìm thấy bản ghi nào trong thùng rác</div></td></tr>');
                            }
                        },
                        error: function(xhr) {
                            console.error('Lỗi khi khôi phục:', xhr.responseText);
                            Swal.fire({
                                title: 'Lỗi!',
                                text: xhr.responseJSON?.error || 'Không thể khôi phục bản ghi.',
                                icon: 'error',
                                confirmButtonClass: 'btn btn-primary w-xs mt-2',
                                buttonsStyling: false
                            });
                        }
                    });
                }
            });
        });

        // Handle permanent delete
        $(document).on('click', '.btn-permanent-delete-coursepayment', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            const actionUrl = form.attr('action');
            Swal.fire({
                title: 'Xóa vĩnh viễn?',
                text: "Bạn sẽ không thể khôi phục bản ghi này!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Vâng, xóa vĩnh viễn!',
                cancelButtonText: 'Không, hủy!',
                confirmButtonClass: 'btn btn-primary w-xs me-2 mt-2',
                cancelButtonClass: 'btn btn-danger w-xs mt-2',
                buttonsStyling: false
            }).then(function(result) {
                if (result.isConfirmed) {
                    $.ajax({
                        url: actionUrl,
                        type: 'POST',
                        data: form.serialize(),
                        success: function(response) {
                            Swal.fire({
                                title: 'Đã xóa!',
                                text: response.success || 'Bản ghi đã được xóa vĩnh viễn.',
                                icon: 'success',
                                confirmButtonClass: 'btn btn-primary w-xs mt-2',
                                buttonsStyling: false
                            });
                            form.closest('tr').remove();
                            if ($('#body-coursePapyment tr').length === 0) {
                                $('#body-coursePapyment').html('<tr><td colspan="8" class="text-center"><div class="alert alert-warning">Không tìm thấy bản ghi nào trong thùng rác</div></td></tr>');
                            }
                        },
                        error: function(xhr) {
                            console.error('Lỗi khi xóa:', xhr.responseText);
                            Swal.fire({
                                title: 'Lỗi!',
                                text: xhr.responseJSON?.error || 'Không thể xóa bản ghi.',
                                icon: 'error',
                                confirmButtonClass: 'btn btn-primary w-xs mt-2',
                                buttonsStyling: false
                            });
                        }
                    });
                }
            });
        });
    </script>
@endpush
