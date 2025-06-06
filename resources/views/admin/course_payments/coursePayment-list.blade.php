@extends('admin.admin')

@section('title', 'Quản lý thanh toán học phí')
@section('description', 'Danh sách học sinh và trạng thái thanh toán học phí')
@section('content')
    <div class="page-content">
        <!-- Start Container Fluid -->
        <div class="container-xxl">
            <!-- Summary Cards -->
            <div class="row mt-1">
                <div class="col-md-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h4 class="card-title mb-2 d-flex align-items-center gap-2 fs-5">Tổng đã thanh toán</h4>
                                    <p class="text-muted fw-medium fs-22 mb-0">
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
                                    <h4 class="card-title mb-2 d-flex align-items-center gap-2 fs-5">Tổng chưa thanh toán
                                    </h4>
                                    <p class="text-muted fw-medium fs-22 mb-0">
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
                                    <p class="text-muted fw-medium fs-22 mb-0">
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
                                    <p class="text-muted fw-medium fs-22 mb-0">
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
            <h4 class="card-title mb-1">Danh sách thanh toán học phí</h4>

            <!-- Payment List -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header p-2">
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
                                    <select name="class_id" id="class_filter" class="form-select form-select-sm"
                                        data-choices>
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
                                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Đã thanh
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
                                        <option value="Cash" {{ request('method') == 'Cash' ? 'selected' : '' }}>Tiền mặt
                                        </option>
                                    </select>
                                </div>

                                <div class="col-12 col-md-6 col-lg-4 col-xl-2 d-flex align-items-end gap-2">
                                    <button type="submit" class="btn btn-success btn-sm w-50">Lọc</button>
                                    <button type="reset" class="btn btn-danger btn-sm w-50">Xóa</button>
                                </div>
                            </form>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive table-gridjs">
                                <table class="table align-middle mb-0 table-hover table-centered">
                                    <thead class="bg-light-subtle">
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
                                                    <div class="fs-6">Khóa: {{ $payment->course->name }}</div>
                                                </td>
                                                <td>
                                                    {{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('H:i d/m/Y') : '' }}
                                                </td>

                                                </td>
                                                <td>{{ number_format($payment->amount, 0, ',', '.') }}</td>
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
                                                            <li><a class="dropdown-item" href="#"><iconify-icon
                                                                        icon="solar:eye-broken"
                                                                        class="me-1"></iconify-icon> Xem</a></li>
                                                            <li data-bs-target="#modal-course-payment">
                                                                <button
                                                                    class="dropdown-item text-warning btn-edit-course-payment"
                                                                    data-coursePayment_id="{{ $payment->id }}"><iconify-icon
                                                                        icon="solar:pen-2-broken"
                                                                        class="me-1"></iconify-icon> Sửa</button>
                                                            </li>
                                                            <li>
                                                                <form action="" method="post">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button class="dropdown-item text-danger"
                                                                        id="sweetalert-params">
                                                                        <iconify-icon
                                                                            icon="solar:trash-bin-minimalistic-2-broken"
                                                                            class="me-1"></iconify-icon> Xóa
                                                                    </button>
                                                                </form>
                                                            </li>
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



                        {{-- Modal---------------------- --}}
                        <div class="modal fade" id="modal-course-payment" tabindex="-1"
                            aria-labelledby="editPaymentModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable">
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
                                        <div class="modal-body" id="modal-body-courser-payment">

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
                    <td>${Number(payment.amount).toLocaleString('vi-VN')}</td>
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
                                <li><a class="dropdown-item" href="#"><iconify-icon icon="solar:eye-broken" class="me-1"></iconify-icon> Xem</a></li>
                                 <li data-bs-target="#modal-course-payment">
                                    <button
                                        class="dropdown-item text-warning btn-edit-course-payment"
                                        data-coursePayment_id="${payment.id}"><iconify-icon
                                            icon="solar:pen-2-broken"
                                            class="me-1"></iconify-icon> Sửa</button>
                                </li>
                                <li>
                                    <form action="" method="post">
                                        <input type="hidden" name="_token" value="${csrfToken}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="dropdown-item text-danger">
                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="me-1"></iconify-icon> Xóa
                                        </button>
                                    </form>
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

        // Hàm định dạng thời gian cho input datetime-local
        function formatDateTimeForInput(dateString) {
            if (!dateString) return '';

            const date = new Date(dateString);
            const pad = num => num.toString().padStart(2, '0');

            const year = date.getFullYear();
            const month = pad(date.getMonth() + 1);
            const day = pad(date.getDate());
            const hours = pad(date.getHours());
            const minutes = pad(date.getMinutes());

            return `${year}-${month}-${day}T${hours}:${minutes}`;
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
                    <div class="mb-3">
                        <label class="form-label fw-bold d-flex align-items-center gap-2">
                            <iconify-icon icon="solar:user-broken" class="text-info"></iconify-icon>
                            Người dùng
                        </label>
                        <div class="card bg-light-subtle border-0 p-2">
                            <div class="fw-bold">${response.payment.user?.name || 'N/A'}</div>
                            <div class="text-muted small">${response.payment.user?.email || 'N/A'}</div>
                            <input type="hidden" name="user_id" value="${response.payment.user?.id || ''}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold d-flex align-items-center gap-2">
                            <iconify-icon icon="solar:notebook-broken" class="text-success"></iconify-icon>
                            Lớp học
                        </label>
                        <div class="card bg-light-subtle border-0 p-2">
                            <div class="fw-bold">${response.payment.class?.name || 'N/A'}</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold d-flex align-items-center gap-2">
                            <iconify-icon icon="solar:book-broken" class="text-primary"></iconify-icon>
                            Khóa học
                        </label>
                        <div class="card bg-light-subtle border-0 p-2">
                            <div class="fw-bold">${response.payment.course?.name || 'N/A'}</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold d-flex align-items-center gap-2">
                            <iconify-icon icon="solar:money-bag-broken" class="text-warning"></iconify-icon>
                            Số tiền
                        </label>
                        <div class="card bg-light-subtle border-0 p-2">
                            <div class="fw-bold">
                                ${response.payment.amount ? Number(response.payment.amount).toLocaleString('vi-VN') + ' VNĐ' : '0 VNĐ'}
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
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

                    <div class="mb-3">
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

                    <div class="mb-3">
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

                    <div class="mb-3">
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
                                <td>${Number(payment.amount).toLocaleString('vi-VN')}</td>
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
                                            <li><a class="dropdown-item" href="#"><iconify-icon icon="solar:eye-broken" class="me-1"></iconify-icon> Xem</a></li>
                                            <li>
                                                <button class="dropdown-item text-warning btn-edit-course-payment" data-coursepayment_id="${payment.id}" data-bs-target="#modal-course-payment">
                                                    <iconify-icon icon="solar:pen-2-broken" class="me-1"></iconify-icon> Sửa
                                                </button>
                                            </li>
                                            <li>
                                                <form action="" method="post">
                                                    <input type="hidden" name="_token" value="${csrfToken}">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="me-1"></iconify-icon> Xóa
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            `);
                        }
                    }

                    $('#modal-course-payment').modal('hide');
                },
                error: function(xhr) {
                    console.error('Lỗi khi cập nhật:', xhr.responseText);
                    alert('Có lỗi xảy ra khi cập nhật: ' + xhr.responseText);
                }
            });
        });


        //Parameter
        if (document.getElementById("sweetalert-params"))
            document.getElementById("sweetalert-params").addEventListener("click", function(e) {
                e.preventDefault();
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
                            url: $(this).attr('href'),
                            type: 'DELETE',
                            success: function(response) {
                                Swal.fire({
                                    title: 'Đã xóa!',
                                    text: response.success,
                                    icon: 'success',
                                    confirmButtonClass: 'btn btn-primary w-xs mt-2',
                                    buttonsStyling: false
                                })
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
    </script>
@endpush
