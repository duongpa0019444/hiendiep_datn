@extends('admin.admin')

@section('title', 'Quản lý thanh toán học phí')
@section('description', 'Danh sách học sinh và trạng thái thanh toán học phí')
@section('content')
    <div class="page-content">
        <!-- Start Container Fluid -->
        <div class="container-xxl">


            <nav aria-label="breadcrumb p-0">
                <ol class="breadcrumb py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Học phí & Thanh toán</li>
                </ol>
            </nav>

            <!-- Summary Cards -->
            <div class="row">
                <div class="col-md-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h4 class="card-title mb-2 d-flex align-items-center gap-2 fs-5">Đã thanh toán</h4>
                                    <p class="text-muted fw-medium fs-22 mb-0" id="total_paid">
                                        {{ number_format(\DB::table('course_payments')->where('status', 'paid')->sum('amount'), 0, ',', '.') }}
                                        VNĐ
                                    </p>
                                </div>
                                <div>
                                    <div class="avatar-md bg-success bg-opacity-10 rounded">
                                        <iconify-icon icon="solar:money-bag-broken"
                                            class="fs-32 text-success avatar-title"></iconify-icon>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h4 class="card-title mb-2 d-flex align-items-center gap-2 fs-5">Chưa thanh toán
                                    </h4>
                                    <p class="text-muted fw-medium fs-22 mb-0" id="total_unpaid">
                                        {{ number_format(\DB::table('course_payments')->where('status', 'unpaid')->sum('amount'), 0, ',', '.') }}
                                        VNĐ
                                    </p>
                                </div>
                                <div>
                                    <div class="avatar-md bg-warning bg-opacity-10 rounded">
                                        <iconify-icon icon="solar:money-bag-broken"
                                            class="fs-32 text-warning avatar-title"></iconify-icon>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h4 class="card-title mb-2 d-flex align-items-center gap-2 fs-5">Thanh toán tiền mặt
                                    </h4>
                                    <p class="text-muted fw-medium fs-22 mb-0" id="total_cash">
                                        {{ \DB::table('course_payments')->where('method', 'Cash')->count() }}
                                    </p>
                                </div>
                                <div>
                                    <div class="avatar-md bg-info bg-opacity-10 rounded">
                                        <iconify-icon icon="solar:card-broken"
                                            class="fs-32 text-info avatar-title"></iconify-icon>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h4 class="card-title mb-2 d-flex align-items-center gap-2 fs-5">Chuyển khoản </h4>
                                    <p class="text-muted fw-medium fs-22 mb-0" id="total_bank_transfer">
                                        {{ \DB::table('course_payments')->where('method', 'Bank Transfer')->count() }}
                                    </p>
                                </div>
                                <div>
                                    <div class="avatar-md bg-primary bg-opacity-10 rounded">
                                        <iconify-icon icon="solar:card-broken"
                                            class="fs-32 text-primary avatar-title"></iconify-icon>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between mb-1">
                <h4 class="card-title mb-1">Danh sách thanh toán học phí</h4>
                <div class="d-flex gap-2 align-items-center">
                    <a id="" href="{{ route('admin.course_payments.trash') }}" class="btn btn-outline-danger btn-sm d-flex align-items-center gap-1">
                        <iconify-icon icon="mdi:trash-can-outline" class="fs-20 me-1"></iconify-icon> <span>Thùng rác</span>
                    </a>

                    <a id="export-btn" href="#" class="btn btn-primary btn-sm">
                        <iconify-icon icon="material-symbols:download" class="fs-20"></iconify-icon> Xuất file
                    </a>
                </div>

            </div>

            <!-- Payment List -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card p-2">
                        <form method="GET" action="{{ route('admin.course_payments.filter') }}"
                            class="row g-2 d-flex align-items-end" id="searchForm">
                            <input type="hidden" name="limit" id="limit" value="10">

                            <div class="col-12 col-md-6 col-lg-4 col-xl-2">
                                <label for="keyword" class="form-label mb-1">Từ khóa</label>
                                <input type="text" name="keyword" id="keyword" class="form-control form-control-sm"
                                    placeholder="Tên học sinh">
                            </div>

                            <div class="col-12 col-md-6 col-lg-4 col-xl-2">
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

                            <div class="col-12 col-md-6 col-lg-4 col-xl-2">
                                <label for="status_class_filter" class="form-label mb-1">Trạng thái lớp học</label>
                                <select name="status_class" id="status_class_filter" class="form-select form-select-sm">
                                    <option value="">Tất cả</option>
                                    <option value="in_progress"
                                        {{ request('status_class') == 'in_progress' ? 'selected' : '' }}>Đang hoạt động
                                    </option>
                                    <option value="completed"
                                        {{ request('status_class') == 'completed' ? 'selected' : '' }}>Đã hoàn thành
                                    </option>
                                    <option value="not_started"
                                        {{ request('status_class') == 'not_started' ? 'selected' : '' }}>Chưa bắt đầu
                                    </option>
                                </select>
                            </div>

                            <div class="col-12 col-md-6 col-lg-4 col-xl-2">
                                <label for="status_filter" class="form-label mb-1">Trạng thái thanh toán</label>
                                <select name="status" id="status_filter" class="form-select form-select-sm">
                                    <option value="">Tất cả</option>
                                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Đã
                                        thanh
                                        toán
                                    </option>
                                    <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Chưa
                                        thanh toán</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-6 col-lg-4 col-xl-2">
                                <label for="method_filter" class="form-label mb-1">Phương thức thanh toán</label>
                                <select name="method" id="method_filter" class="form-select form-select-sm">
                                    <option value="">Tất cả</option>
                                    <option value="Bank Transfer"
                                        {{ request('method') == 'Bank Transfer' ? 'selected' : '' }}>Chuyển khoản
                                    </option>
                                    <option value="Cash" {{ request('method') == 'Cash' ? 'selected' : '' }}>Tiền
                                        mặt
                                    </option>
                                </select>
                            </div>

                            <div class="col-12 col-md-6 col-lg-4 col-xl-2 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-success btn-sm w-50">Lọc</button>
                                <button type="reset" class="btn btn-danger btn-sm w-50">Xóa</button>
                            </div>
                        </form>
                    </div>

                    <div class="card" style="border-radius: 0;">

                        <div class="card-body p-0">
                            <div class="table-responsive table-gridjs">
                                <table class="table  rounded align-middle mb-0 table-hover table-centered" >
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Tên học sinh</th>
                                            <th>Lớp</th>
                                            <th>Ngày thanh toán</th>
                                            <th>Số tiền (VNĐ)</th>
                                            <th>Trạng thái</th>
                                            <th>Phương thức</th>
                                            <th>Ghi chú</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody id="body-coursePapyment">
                                        @foreach ($payments as $payment)
                                            <tr data-payment-id="{{ $payment->id }}">
                                                <td>
                                                    <div class="fw-bold">{{ $payment->user->name }}</div>
                                                    <div class="fs-6">{{ $payment->user->email }}</div>
                                                </td>
                                                <td>
                                                    <div class="fw-bold">{{ $payment->class->name }}</div>
                                                    {{-- <div class="fs-6">Khóa: {{ $payment->courses->name }}</div> --}}
                                                </td>
                                                <td>
                                                    {{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('H:i d/m/Y') : '' }}
                                                </td>

                                                </td>
                                                <td class="text-end">{{ number_format($payment->amount, 0, ',', '.') }}</td>
                                                <td>
                                                    <span
                                                        class="badge {{ $payment->status == 'paid' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }} py-1 px-2">
                                                        {{ $payment->status == 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                                                    </span>
                                                </td>
                                                <td
                                                    class="{{ $payment->method == 'Cash' ? 'text-success' : 'text-info' }} py-1 px-2">
                                                    {{ $payment->method == 'Cash' ? 'Tiền mặt' : '' }}
                                                    {{ $payment->method == 'Bank Transfer' ? 'Chuyển khoản' : '' }}
                                                </td>
                                                <td>{{ $payment->note ?? '' }}</td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-light btn-sm dropdown-toggle"
                                                            type="button" data-bs-toggle="dropdown">
                                                            Thao tác <iconify-icon icon="tabler:caret-down-filled"
                                                                class="ms-1"></iconify-icon>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            @if ($payment->status == 'paid')
                                                                <li data-bs-target="#modal-printCoursePayment">
                                                                    <button class="dropdown-item btn-invoice-coursePayment"
                                                                        data-coursePayment_id="{{ $payment->id }}"><iconify-icon
                                                                            icon="solar:eye-broken"
                                                                            class="me-1"></iconify-icon> Xem hóa
                                                                        đơn</button>
                                                                </li>
                                                            @endif


                                                            <li data-bs-target="#modal-course-payment">
                                                                <button
                                                                    class="dropdown-item text-warning btn-edit-course-payment"
                                                                    data-coursePayment_id="{{ $payment->id }}"><iconify-icon
                                                                        icon="solar:pen-2-broken"
                                                                        class="me-1"></iconify-icon> Sửa</button>
                                                            </li>
                                                            {{-- <li>
                                                                <form
                                                                    action="{{ route('admin.course_payments.delete', $payment->id) }}"
                                                                    method="post">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button
                                                                        class="dropdown-item text-danger sweetalert-params-coursepayment"
                                                                        type="button">
                                                                        <iconify-icon
                                                                            icon="solar:trash-bin-minimalistic-2-broken"
                                                                            class="me-1"></iconify-icon> Xóa
                                                                    </button>
                                                                </form>
                                                            </li> --}}
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- end table-responsive -->
                        </div>

                        <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 m-3">
                            <div id="pagination-wrapper" class="flex-grow-1">
                                {{ $payments->links('pagination::bootstrap-5') }}
                            </div>

                            <div class="d-flex align-items-center" style="min-width: 160px;">
                                <label for="limit2" class="form-label mb-0 me-2 small">Hiển thị</label>
                                <select name="limit2" id="limit2" class="form-select form-select-sm"
                                    style="width: 100px;">
                                    <option value="10" selected>10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                        </div>



                        {{-- Modal Cập nhật thanh toán---------------------- --}}
                        <div class="modal fade" id="modal-course-payment" tabindex="-1"
                            aria-labelledby="editPaymentModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-light-subtle">
                                        <h5 class="modal-title d-flex align-items-center gap-2"
                                            id="editPaymentModalLabel">
                                            <iconify-icon icon="solar:pen-2-broken" class="text-primary"></iconify-icon>
                                            Cập nhật thanh toán
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <form action="" method="post">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body d-flex justify-content-between flex-wrap bg"
                                            id="modal-body-courser-payment">

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <iconify-icon icon="solar:close-circle-broken"
                                                    class="me-1"></iconify-icon>Đóng
                                            </button>
                                            <button type="submit" class="btn btn-primary" data-toast
                                                data-toast-text="Lưu thay đổi thành công!" data-toast-gravity="top"
                                                data-toast-position="center" data-toast-className="success"
                                                data-toast-duration="4000" class="btn btn-light ms-2 rounded-2">
                                                <iconify-icon icon="solar:check-circle-broken"
                                                    class="me-1"></iconify-icon>Lưu thay đổi
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>



                        {{-- Modal Hiển thị hóa đơn --}}
                        <div class="modal fade" id="modal-printCoursePayment" tabindex="-1"
                            aria-labelledby="editPaymentModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                                <div class="modal-content">

                                    <div class="modal-body">
                                        <div class="d-flex justify-content-end">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="invoice-container">


                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Đóng</button>
                                        <a href="" class="btn btn-primary btn-print-invoive">Xuất hóa đơn</a>
                                    </div>
                                </div>
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


        //Hàm xử lý khi click xem hóa đơn
        $(document).on('click', '.btn-invoice-coursePayment', function() {
            const coursePaymentId = $(this).data('coursepayment_id');

            console.log('Course Payment ID:', coursePaymentId);
            $.ajax({
                url: `/admin/course-payments/${coursePaymentId}/show`,
                type: 'GET',
                success: function(response) {
                    console.log('Response:', response);
                    const payment = response;
                    $('.invoice-container').html(`
                <div class="invoice-header text-center">
                    <h3 class="mt-2">TRUNG TÂM ĐÀO TẠO HIEN DIEP</h3>
                    <p>Địa chỉ: 123 Đường Trịnh Kiểm, P. Đông Vệ, TP. Thanh Hóa</p>
                    <p>Hotline: 0123 456 789 | Email: contact@hiendiep.edu.vn</p>
                    <hr>
                    <h4>HÓA ĐƠN HỌC PHÍ</h4>
                    <p>Ngày xuất: <strong>${new Date().toLocaleDateString('vi-VN')}</strong></p>
                </div>
                <div class="student-details">
                    <h5>Thông tin học viên</h5>
                    <div class="grid-container">
                        <div class="grid-item">
                            <p><strong>Họ và tên:</strong> ${payment.user?.name || 'N/A'}</p>
                        </div>
                        <div class="grid-item">
                            <p><strong>Số điện thoại:</strong> ${payment.user?.phone || 'N/A'}</p>
                            <p><strong>Email:</strong> ${payment.user?.email || 'N/A'}</p>
                        </div>
                    </div>
                </div>
                <div class="invoice-details">
                    <h5>Chi tiết thanh toán</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">STT</th>
                                <th class="text-center">Nội dung</th>
                                <th class="text-center">Số tiền (VNĐ)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>
                                    <strong>Khóa học:</strong> ${payment.course?.name || 'N/A'}<br>
                                    <strong>Lớp:</strong> ${payment.class?.name || 'N/A'}<br>
                                    <strong>Mã thanh toán:</strong> ${payment.payment_code || 'N/A'}
                                </td>
                                <td class="text-end">${Number(payment.amount || 0).toLocaleString('vi-VN')}</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2" class="text-end">Tổng cộng:</th>
                                <td class="text-end">${Number(payment.amount || 0).toLocaleString('vi-VN')}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="payment-info">
                    <h5>Thông tin thanh toán</h5>
                    <p><strong>Phương thức:</strong> ${payment.method === 'Cash' ? 'Tiền mặt' : (payment.method === 'Bank Transfer' ? 'Chuyển khoản' : 'N/A')}</p>
                    <p><strong>Ngày thanh toán:</strong> ${payment.payment_date ? new Date(payment.payment_date).toLocaleString('vi-VN') : 'N/A'}</p>
                    <p><strong>Ghi chú:</strong> ${payment.note || ''}</p>
                </div>
                <div class="invoice-footer text-center mt-4">
                    <p><strong>Cảm ơn quý khách đã sử dụng dịch vụ của chúng tôi!</strong></p>
                    <p>Trung tâm Hien Diep</p>
                </div>
            `);
                    $('.btn-print-invoive').attr('href',
                        `/admin/course-payments/${coursePaymentId}/download`);
                    $('#modal-printCoursePayment').modal('show');
                },
                error: function(xhr) {
                    console.error('Lỗi khi tải hóa đơn:', xhr.responseText);
                    Swal.fire({
                        title: 'Lỗi!',
                        text: xhr.responseJSON?.error ||
                            'Không thể tải hóa đơn. Vui lòng thử lại.',
                        icon: 'error',
                        confirmButtonClass: 'btn btn-primary w-xs mt-2',
                        buttonsStyling: false
                    });
                }
            });
        });



        //reset search
        $('#searchForm').on('reset', function(e) {
            setTimeout(function() {
                window.location.reload();
            }, 10);
        });

        // Xử lý thay đổi số dòng hiển thị
        $('#limit2').change(function() {
            const limitValue = $(this).val();
            $('#searchForm #limit').val(limitValue);
            $('#searchForm').submit();
        });

        // Xử lý bộ lọc
        $('#searchForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                type: 'GET',
                data: $(this).serialize(),
                success: function(response) {
                    console.log(response);
                    $('#body-coursePapyment').html(renderCoursePayment(response.payments.data));
                    $('#pagination-wrapper').html(response.pagination);

                },
                error: function(xhr) {
                    console.error('Lỗi khi tìm kiếm:', xhr.responseText);
                }
            });
        });



        // Hàm tạo danh sách thanh toán khóa học
        function renderCoursePayment(data) {
            if (data.length === 0)
                return '<tr><td colspan="11" class="text-center"><div class="alert alert-warning">Không tìm thấy kết quả</div></td></tr>';

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
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                Thao tác <iconify-icon icon="tabler:caret-down-filled" class="ms-1"></iconify-icon>
                            </button>
                            <ul class="dropdown-menu">

                                ${payment.status === 'paid' ? `
                                        <li data-bs-target="#modal-printCoursePayment">
                                            <button class="dropdown-item btn-invoice-coursePayment" data-coursePayment_id="${ payment.id }">
                                                <iconify-icon icon="solar:eye-broken"class="me-1"></iconify-icon> Xem hóa đơn
                                            </button>
                                        </li>` : ''}

                                <li data-bs-target="#modal-course-payment">
                                    <button class="dropdown-item text-warning btn-edit-course-payment" data-coursePayment_id="${ payment.id }">
                                        <iconify-icon icon="solar:pen-2-broken" class="me-1"></iconify-icon> Sửa
                                    </button>
                                </li>

                            </ul>
                        </div>
                    </td>
                </tr>

                `;
            });

            return html;
        }

        // Hàm định dạng thời gian cho hiển thị
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


        // Xử lý phân trang
        $(document).on('click', '#pagination-wrapper a', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    $('#body-coursePapyment').html(renderCoursePayment(response.payments.data));
                    $('#pagination-wrapper').html(response.pagination);
                },
                error: function(xhr) {
                    console.error('Lỗi phân trang:', xhr.responseText);
                }
            });
        });


        //Hàm xử lý render dữ liệu lên modal khi ấn nút sửa
        $(document).on('click', '.btn-edit-course-payment', function() {
            const coursePaymentId = $(this).data('coursepayment_id');
            console.log(coursePaymentId);
            // Gọi AJAX để lấy dữ liệu
            $.ajax({
                url: `course-payments/${coursePaymentId}/detail`,
                type: 'GET',
                success: function(response) {
                    console.log(response.payment);
                    $('#modal-body-courser-payment').html(
                        `
                    <div class="mb-1  p-2 col-lg-12 col-md-12 col-sm-12">
                        <label class="form-label fw-bold d-flex align-items-center gap-2">
                            <iconify-icon icon="solar:user-broken" class="text-info"></iconify-icon>
                            Người dùng
                        </label>
                        <div class="card bg-light border-0 p-2">
                            <div class="fw-bold">${response.payment.user?.name || 'N/A'}</div>
                            <div class="text-muted small">${response.payment.user?.email || 'N/A'}</div>
                            <input type="hidden" name="user_id" value="${response.payment.user?.id || ''}">
                        </div>
                    </div>

                    <div class="mb-1  p-2 col-lg-6 col-md-12 col-sm-12">
                        <label class="form-label fw-bold d-flex align-items-center gap-2">
                            <iconify-icon icon="solar:notebook-broken" class="text-success"></iconify-icon>
                            Lớp học
                        </label>
                        <div class="card bg-light border-0 p-2">
                            <div class="fw-bold">${response.payment.class?.name || 'N/A'}</div>
                        </div>
                    </div>

                    <div class="mb-1  p-2 col-lg-6 col-md-12 col-sm-12">
                        <label class="form-label fw-bold d-flex align-items-center gap-2">
                            <iconify-icon icon="solar:book-broken" class="text-primary"></iconify-icon>
                            Khóa học
                        </label>
                        <div class="card bg-light border-0 p-2">
                            <div class="fw-bold">${response.payment.course?.name || 'N/A'}</div>
                        </div>
                    </div>

                    <div class="mb-1  p-2 col-lg-6 col-md-12 col-sm-12">
                        <label class="form-label fw-bold d-flex align-items-center gap-2">
                            <iconify-icon icon="solar:money-bag-broken" class="text-warning"></iconify-icon>
                            Số tiền
                        </label>
                        <div class="card bg-light border-0 p-2">
                            <div class="fw-bold">
                                ${response.payment.amount ? Number(response.payment.amount).toLocaleString('vi-VN') + ' VNĐ' : '0 VNĐ'}
                            </div>
                        </div>
                    </div>

                    <div class="mb-1 p-2 col-lg-6 col-md-12 col-sm-12">
                        <label class="form-label fw-bold d-flex align-items-center gap-2">
                            <iconify-icon icon="solar:check-circle-broken" class="text-success"></iconify-icon>
                            Trạng thái
                        </label>
                        <div class="input-group">
                            <select name="status" id="status-${response.payment.id}" class="form-control">
                                <option value="paid" ${response.payment.status === 'paid' ? 'selected' : ''}>
                                    Đã thanh toán
                                </option>
                                <option value="unpaid" ${response.payment.status === 'unpaid' ? 'selected' : ''}>
                                    Chưa thanh toán
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-1 p-2 col-lg-6 col-md-12 col-sm-12">
                        <label for="payment_date" class="form-label fw-bold d-flex align-items-center gap-2">
                            <iconify-icon icon="solar:calendar-broken" class="text-info"></iconify-icon>
                            Ngày thanh toán
                        </label>
                        <div class="input-group">
                            <input
                                type="datetime-local"
                                class="form-control"
                                id="payment_date"
                                name="payment_date"
                                value="${response.payment.payment_date ? new Date(response.payment.payment_date).toISOString().slice(0, 16) : ''}"
                            >
                        </div>
                    </div>

                    <div class="mb-1 p-2 col-lg-6 col-md-12 col-sm-12">
                        <label for="method-${response.payment.id}" class="form-label fw-bold d-flex align-items-center gap-2">
                            <iconify-icon icon="solar:card-broken" class="text-primary"></iconify-icon>
                            Phương thức thanh toán
                        </label>
                        <div class="input-group">
                            <select class="form-control" id="method-${response.payment.id}" name="method">
                                <option value="">
                                </option>
                                <option value="Cash" ${response.payment.method === 'Cash' ? 'selected' : ''}>
                                    Tiền mặt
                                </option>
                                <option value="Bank Transfer" ${response.payment.method === 'Bank Transfer' ? 'selected' : ''}>
                                    Chuyển khoản
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-1 p-2 col-12">
                        <label for="note" class="form-label fw-bold d-flex align-items-center gap-2">
                            <iconify-icon icon="solar:notes-broken" class="text-secondary"></iconify-icon>
                            Ghi chú
                        </label>
                        <div class="input-group">
                            <textarea class="form-control" id="note" name="note" rows="4">${response.payment.note || ''}</textarea>
                        </div>
                    </div>
                `
                    );
                    $('#modal-course-payment .modal-dialog-scrollable .modal-body')[0].style.maxHeight =
                        'calc(100vh - 250px)';
                    $('#modal-course-payment').modal('handleUpdate');
                    // Sau khi render nội dung vào modal
                    const updateUrl = `/admin/course-payments/${response.payment.id}/update`;
                    $('#modal-course-payment form').attr('action', updateUrl);
                    $('#modal-course-payment').modal('show'); // Mở modal sau khi render

                },
                error: function() {
                    alert('Có lỗi xảy ra khi lấy dữ liệu.');
                }

            });
        });




        $("#modal-course-payment form").on('submit', function(e) {
            e.preventDefault();
            const url = $(this).attr('action');
            var formData = $(this).serialize();
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                success: function(response) {

                    // Cập nhật chỉ hàng vừa chỉnh sửa
                    if (response.payment) {
                        const payment = response.payment;
                        const row = $(`tr[data-payment-id="${payment.id}"]`);
                        if (row.length) {
                            row.html(`
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
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Thao tác <iconify-icon icon="tabler:caret-down-filled" class="ms-1"></iconify-icon>
                                        </button>
                                        <ul class="dropdown-menu">

                                            ${payment.status === 'paid' ? `

                                                    <li data-bs-target="#modal-printCoursePayment">
                                                        <button class="dropdown-item btn-invoice-coursePayment" data-coursePayment_id="${ payment.id }">
                                                            <iconify-icon icon="solar:eye-broken"class="me-1"></iconify-icon> Xem hóa đơn
                                                        </button>
                                                    </li>` : ''}


                                            <li data-bs-target="#modal-course-payment">
                                                <button class="dropdown-item text-warning btn-edit-course-payment" data-coursePayment_id="${ payment.id }">
                                                    <iconify-icon icon="solar:pen-2-broken" class="me-1"></iconify-icon> Sửa
                                                </button>
                                            </li>

                                        </ul>
                                    </div>
                                </td>
                            `);
                        }
                    }

                    $('#modal-course-payment').modal('hide');
                    renderStatistics();
                },
                error: function(xhr) {
                    console.error('Lỗi khi cập nhật:', xhr.responseText);
                    alert('Có lỗi xảy ra khi cập nhật: ' + xhr.responseText);
                }
            });
        });


        //Hàm xử lý xóa dữ liệu
        $(document).on('click', '.sweetalert-params-coursepayment', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            const actionUrl = form.attr('action');
            Swal.fire({
                title: 'Bạn có chắc chắn?',
                text: "Bạn sẽ không thể hoàn tác hành động này!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Vâng, xóa nó!',
                cancelButtonText: 'Không, hủy!',
                confirmButtonClass: 'btn btn-primary w-xs me-2 mt-2',
                cancelButtonClass: 'btn btn-danger w-xs mt-2',
                buttonsStyling: false,
                showCloseButton: false
            }).then(function(result) {

                if (result.value) {

                    $.ajax({
                        url: actionUrl,
                        type: 'POST',
                        data: form.serialize(),
                        success: function(response) {
                            Swal.fire({
                                title: 'Đã xóa!',
                                text: response.success,
                                icon: 'success',
                                confirmButtonClass: 'btn btn-primary w-xs mt-2',
                                buttonsStyling: false
                            })
                            form.closest('tr').remove();
                            renderStatistics();
                        },

                    })

                } else if (
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    Swal.fire({
                        title: 'Đã hủy',
                        text: 'Dữ liệu của bạn vẫn an toàn :)',
                        icon: 'error',
                        confirmButtonClass: 'btn btn-primary mt-2',
                        buttonsStyling: false
                    })
                }
            });
        });



        //Hàm xử lý render lại các hộp thống kê
        function renderStatistics() {
            const total_bank_transfer = $('#total_bank_transfer');
            const total_cash = $('#total_cash');
            const total_unpaid = $('#total_unpaid');
            const total_paid = $('#total_paid');

            $.ajax({
                url: '/admin/course-payments/statistics',
                type: 'GET',
                success: function(response) {
                    console.log('thống kê:' + response);

                    total_unpaid.text(formatCurrency(response.total_unpaid) + 'VNĐ');
                    total_paid.text(formatCurrency(response.total_paid) + 'VNĐ');
                    total_bank_transfer.text(formatCurrency(response.bank_transfer_payment_count));
                    total_cash.text(formatCurrency(response.cash_payment_count));
                }
            })

        }


        // Hàm định dạng số tiền theo kiểu VNĐ
        function formatCurrency(amount) {
            if (!amount) return '0 VNĐ';
            return Number(amount).toLocaleString('vi-VN');
        }


        //xử lý nút xuất file
        // Xử lý nút xuất file
        $('#export-btn').on('click', function(e) {
            e.preventDefault();
            // Lấy dữ liệu từ form lọc
            let formData = $('#searchForm').serialize();
            console.log(formData);
            // Tạo URL với các tham số lọc
            let exportUrl = '{{ route('admin.course_payments.export') }}?' + formData;
            // Chuyển hướng đến URL xuất file
            window.location.href = exportUrl;
        });
    </script>
@endpush
