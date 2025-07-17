@extends('admin.admin')
@section('title', 'Trang admin')
@section('description', '')
@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <div class="container-xxl">


                <nav aria-label="breadcrumb p-0">
                    <ol class="breadcrumb py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Quản lí lương giáo viên</li>
                    </ol>
                </nav>

                <!-- Summary Cards -->
                <div class="row">
                    <div class="col-md-6 col-xl-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h4 class="card-title mb-2 d-flex align-items-center gap-2 fs-5">Tổng đã thanh toán
                                        </h4>
                                        <p class="text-muted fw-medium fs-22 mb-0" id="total_paid">
                                            {{ number_format(
                                                \DB::table('teacher_salaries')->where('paid', '1')->where('month', \Carbon\Carbon::now()->month)->where('year', \Carbon\Carbon::now()->year)->sum('total_salary'),
                                                0,
                                                ',',
                                                '.',
                                            ) }}
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
                                        <h4 class="card-title mb-2 d-flex align-items-center gap-2 fs-5">Tổng chưa thanh
                                            toán
                                        </h4>
                                        <p class="text-muted fw-medium fs-22 mb-0" id="total_unpaid">
                                            {{ number_format(
                                                \DB::table('teacher_salaries')->where('paid', '0')->where('month', \Carbon\Carbon::now()->month)->where('year', \Carbon\Carbon::now()->year)->sum('total_salary'),
                                                0,
                                                ',',
                                                '.',
                                            ) }}
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
                                        <h4 class="card-title mb-2 d-flex align-items-center gap-2 fs-5">Tiền thưởng </h4>
                                        <p class="text-muted fw-medium fs-22 mb-0" id="total_bank_transfer">
                                            {{ number_format(
                                                \DB::table('teacher_salaries')->where('month', \Carbon\Carbon::now()->month)->where('year', \Carbon\Carbon::now()->year)->sum('bonus'),
                                                0,
                                                ',',
                                                '.',
                                            ) }}
                                            VNĐ
                                        </p>
                                    </div>
                                    <div>
                                        <div class="avatar-md bg-success bg-opacity-10 rounded">
                                            <iconify-icon icon="solar:card-send-bold"
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
                                        <h4 class="card-title mb-2 d-flex align-items-center gap-2 fs-5">Tiền phạt
                                        </h4>
                                        <p class="text-muted fw-medium fs-22 mb-0" id="total_cash">
                                            {{ number_format(
                                                \DB::table('teacher_salaries')->where('month', \Carbon\Carbon::now()->month)->where('year', \Carbon\Carbon::now()->year)->sum('penalty'),
                                                0,
                                                ',',
                                                '.',
                                            ) }}
                                            VNĐ
                                        </p>
                                    </div>
                                    <div>
                                        <div class="avatar-md bg-danger bg-opacity-10 rounded">
                                            <iconify-icon icon="solar:card-recive-bold-duotone"
                                                class="fs-32 text-danger avatar-title"></iconify-icon>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <div class="">
                        <h4 class="card-title m-1 d-inline-block">Danh sách thanh toán lương</h4>
                        <button type="button" class="btn btn-outline-success m-1" id="loadRulesBtn"> Cá nhân </button>
                    </div>
                    <div>
                        <button type="button" class="btn btn-outline-primary m-1" id="loadSalaryBtn">
                            Tạo bảng lương
                        </button>
                        <a id="export-btn" href="" class="btn btn-outline-primary btn-sm m-1">
                            <iconify-icon icon="material-symbols:download" class="fs-20"></iconify-icon> Xuất file
                        </a>
                    </div>

                </div>
                <div id="monthInputWrapper" class="d-flex justify-content-end align-items-center mb-3"></div>




                <!-- Modal Bảng lương -->
                <div class="modal fade" id="salaryModal" tabindex="-1" aria-labelledby="salaryModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-xl"> <!-- modal-xl để đủ hiển thị bảng -->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="salaryModalLabel">Bảng lương giáo viên</h5>


                                <button type="button" class="btn btn-outline-primary btn-sm m-1" id="SaveSalaryBtn"> Lưu
                                    bảng lương</button>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                            </div>
                            <div class="modal-body">
                                <div id="salaryTableContainer">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Modal -->

                <!-- Modal Tạo Teacher Rules -->
                <div class="modal fade" id="rulesModal" tabindex="-1" aria-labelledby="rulesModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl"> <!-- modal-xl để đủ hiển thị bảng -->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="rulesModalLabel">Bảng lương giáo viên</h5>
                                <button type="button" class="btn btn-outline-primary btn-sm m-1" id="SaveRulesBtn"> Lưu
                                    bảng lương</button>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Đóng"></button>
                            </div>
                            <div class="modal-body">
                                <div id="rulesTableContainer">
                                    <label for="teacherSelect">Chọn giáo viên</label>
                                    <select id="teacherSelect" class="form-control" style="width: 100%">
                                        <option value="">-- Chọn giáo viên --</option>
                                    </select>

                                    <div id="salaryDetails" style="margin-top: 1rem; display: none;">
                                        <label>Mức lương</label>
                                        <input type="number" id="payRate" class="form-control">

                                        <label>Ngày bắt đầu</label>
                                        <input type="date" id="effectiveDate" class="form-control">
                                    </div>
                                    <div id="teacherRulesHistory" style="margin-top: 1rem; display: none;">
                                        <h6>Lịch sử bảng lương</h6>
                                        <table class="table table-bordered table-sm">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Mức lương</th>
                                                    <th>Ngày bắt đầu</th>
                                                    <th>Ngày kết thúc</th>
                                                </tr>
                                            </thead>
                                            <tbody id="rulesHistoryBody">
                                                <!-- sẽ được đổ dữ liệu -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Modal -->



                <div class="card-body p-0">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <input type="text" id="filterName" class="form-control"
                                placeholder="Lọc theo tên giáo viên">
                        </div>
                        <div class="col-md-2">
                            <input type="month" id="filterMonth" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <select id="filterStatus" class="form-select">
                                <option value="">Tất cả trạng thái</option>
                                <option value="1">Đã thanh toán</option>
                                <option value="0">Chưa thanh toán</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button id="applyFilter" class="btn btn-primary w-100">Lọc</button>

                        </div>
                        <div class="col-md-2">
                            <button id="delelyFilter" class="btn btn-success w-100">Bỏ lọc</button>
                        </div>
                    </div>

                    <div class="table-responsive table-gridjs">
                        <table class="table table-bordered table-hover table-centered align-middle">
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th>Tên giáo viên</th>
                                    <th>Thời gian</th>
                                    <th>Số giờ</th>
                                    <th>Mức lương</th>

                                    <th>Thưởng</th>
                                    <th>Phạt</th>
                                    <th>Tổng (VNĐ)</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày trả lương</th>
                                    <th>Ghi chú</th>
                                </tr>
                            </thead>
                            <tbody id="body-teacher_salaries">
                                @foreach ($salaries as $salary)
                                    <tr>
                                        <td class="text-start detail_ruler">
                                            <strong class="teacher-detail" style="cursor: pointer;"
                                                data-bs-toggle="tooltip" title="Chi tiết bảng lương">
                                                {{ $salary->teacher_name }}
                                            </strong><br>
                                            <iconify-icon icon="solar:phone-broken" class="fs-16 me-1"></iconify-icon>
                                            <span class="text-muted">{{ $salary->teacher_phone }}</span>
                                        </td>
                                        <td class="text-center">
                                            Tháng {{ $salary->month }} / {{ $salary->year }}
                                        </td>
                                        <td class="text-center">
                                            {{ $salary->total_hours }} giờ
                                        </td>
                                        <td class="text-end">
                                            {{ number_format($salary->pay_rate, 0, ',', '.') }} VNĐ/giờ
                                        </td>

                                        <td class="text-end text-success">
                                            +{{ number_format($salary->bonus, 0, ',', '.') }} VNĐ
                                        </td>
                                        <td class="text-end text-danger">
                                            -{{ number_format($salary->penalty, 0, ',', '.') }} VNĐ
                                        </td>
                                        <td class="text-end fw-bold text-success">
                                            {{ number_format($salary->total_salary, 0, ',', '.') }} VNĐ
                                        </td>
                                        <td class="text-center">
                                            <select class="form-select payment-status-select"
                                                data-salary-id="{{ $salary->id }}">
                                                <option value="0" {{ $salary->paid == 0 ? 'selected' : '' }}>
                                                    Chưa thanh toán
                                                </option>
                                                <option value="1" {{ $salary->paid == 1 ? 'selected' : '' }}>
                                                    Đã thanh toán
                                                </option>
                                            </select>
                                        </td>

                                        <td class="text-center">
                                            @if ($salary->payment_date)
                                                <span
                                                    class="badge bg-success">{{ \Carbon\Carbon::parse($salary->payment_date)->format('d/m/Y') }}</span>
                                            @else
                                                <span class="badge bg-danger">Chưa thanh toán</span>
                                            @endif
                                        </td>
                                        <td class="text-start note-cell" data-id="{{ $salary->id }}"
                                            data-note="{{ $salary->note ?? '' }}" style="cursor: pointer;">
                                            {{ $salary->note ?? 'Thêm' }}
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
                        {{-- {{ $payments->links('pagination::bootstrap-5') }} --}}
                    </div>

                    <div class="d-flex align-items-center" style="min-width: 160px;">
                        <label for="limit2" class="form-label mb-0 me-2 small">Hiển thị</label>
                        <select name="limit2" id="limit2" class="form-select form-select-sm" style="width: 100px;">
                            <option value="10" selected>10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>

                {{-- Modal thêm ghi chú --}}
                <div class="modal fade" id="noteModal" tabindex="-1" aria-labelledby="noteModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Cập nhật ghi chú</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Đóng"></button>
                            </div>
                            <div class="modal-body">
                                <textarea id="noteContent" class="form-control" rows="4" placeholder="Nhập ghi chú..."></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" id="saveNoteBtn" class="btn btn-primary">Lưu</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal Cập nhật thanh toán---------------------- --}}
                <div class="modal fade" id="modal-course-payment" tabindex="-1" aria-labelledby="editPaymentModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-light-subtle">
                                <h5 class="modal-title d-flex align-items-center gap-2" id="editPaymentModalLabel">
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
                                        <iconify-icon icon="solar:close-circle-broken" class="me-1"></iconify-icon>Đóng
                                    </button>
                                    <button type="submit" class="btn btn-primary" data-toast
                                        data-toast-text="Lưu thay đổi thành công!" data-toast-gravity="top"
                                        data-toast-position="center" data-toast-className="success"
                                        data-toast-duration="4000" class="btn btn-light ms-2 rounded-2">
                                        <iconify-icon icon="solar:check-circle-broken" class="me-1"></iconify-icon>Lưu
                                        thay đổi
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
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                <a href="" class="btn btn-primary btn-print-invoive">Xuất hóa đơn</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Modal chi tiết teacher_ruler --}}
    <div class="modal fade" id="salaryDetailModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chi tiết bảng lương</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="salaryDetailContent">
                    <!-- Nội dung ajax đổ vào đây -->
                </div>
                <button>Cập nhật</button>
            </div>
        </div>
    </div>



    </div>



    </div>
    <!-- end row -->
    <!-- ========== Footer Start ========== -->

    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // function tạo bảng lương
        function renderSalaryTable(data) {
            let html = '';

            data.forEach(salary => {
                html += `
                                            <tr>
                                                <td class="text-start">
                                                    <strong>${salary.teacher_name}</strong><br>
                                                    <span class="text-muted">${salary.teacher_phone}</span>
                                                </td>
                                                <td class="text-center">Tháng ${salary.month} / ${salary.year}</td>
                                                <td class="text-center">${salary.total_hours} giờ</td>
                                                <td class="text-end">${Number(salary.pay_rate).toLocaleString('vi-VN')} VNĐ/giờ</td>
                                                <td class="text-end text-success">+${Number(salary.bonus).toLocaleString('vi-VN')} VNĐ</td>
                                                <td class="text-end text-danger">-${Number(salary.penalty).toLocaleString('vi-VN')} VNĐ</td>
                                                <td class="text-end fw-bold text-success">${Number(salary.total_salary).toLocaleString('vi-VN')} VNĐ</td>
                                                <td class="text-center">
                                                    <select class="form-select payment-status-select" data-salary-id="${salary.id}">
                                                        <option value="0" ${salary.paid == 0 ? 'selected' : ''}>Chưa thanh toán</option>
                                                        <option value="1" ${salary.paid == 1 ? 'selected' : ''}>Đã thanh toán</option>
                                                    </select>
                                                </td>
                                                <td class="text-center">${salary.payment_date ? salary.payment_date : '<span class="badge bg-danger">Chưa thanh toán</span>'}</td>
                                                <td class="text-start">${salary.note || ''}</td>
                                            </tr>
                                        `;
            });

            $('#body-teacher_salaries').html(html);
        }



        // Modal bảng lương
        $(document).ready(function() {

            //Lọc
            $('#applyFilter').on('click', function() {
                const name = $('#filterName').val();
                const month = $('#filterMonth').val(); // sửa chỗ này: lấy từ input, không lấy từ <td>
                const status = $('#filterStatus').val();

                console.log({
                    name,
                    month,
                    status
                }); // kiểm tra giá trị thực sự gửi đi

                $.ajax({
                    url: "{{ route('admin.teacher_salaries.filter') }}",
                    type: "GET",
                    data: {
                        name: name,
                        month: month,
                        status: status
                    },
                    success: function(res) {
                        if (res.success) {
                            console.log(res.data);
                            renderSalaryTable(res.data); // xử lý HTML lại
                        }
                    },
                    error: function() {
                        alert("Lỗi khi lọc dữ liệu.");
                    }
                });
            });

            $('#delelyFilter').on('click', function() {
                window.location.href = "{{ route('admin.teacher_salaries') }} ";
            })

            $('#loadSalaryBtn').on('click', function() {
                // Thêm tháng
                const monthWrapper = $('#monthInputWrapper');

                // Nếu input chưa tồn tại thì thêm vào
                if ($('#salaryMonthInput').length === 0) {
                    const currentMonth = new Date().toISOString().slice(0, 7); // YYYY-MM
                    monthWrapper.html(`
                        <label for="salaryMonthInput" class="form-label mb-0 me-2 fw-bolder">Chọn tháng:</label>
                        <input type="month" id="salaryMonthInput" name="salary_month" class="form-control w-auto d-inline-block" value="${currentMonth}">
                        <button id="confirmLoadSalary" class="btn btn-primary ms-2">Xác nhận</button>
                    `);
                }

                // Gắn sự kiện cho nút xác nhận
                $('#confirmLoadSalary').off('click').on('click', function() {
                    const selectedMonth = $('#salaryMonthInput').val();
                    if (!selectedMonth) {
                        alert('Vui lòng chọn tháng!');

                        Swal.fire({
                            title: 'Lưu ý!',
                            text: 'Vui lòng chọn tháng',
                            icon: 'error',
                            confirmButtonClass: 'btn btn-primary w-xs mt-2',
                            buttonsStyling: false
                        }).then(() => {
                            window.location.href = "{{ route('admin.teacher_salaries') }}";
                        })
                        return;
                    }
                    $.ajax({
                        url: "{{ route('admin.teacher_salaries.data') }}",
                        type: "GET",
                        data: {
                            month: selectedMonth
                        },
                        dataType: "json",
                        success: function(res) {
                            console.log(res);
                            console.log("Dữ liệu lương nhận được:", res.data);
                            console.log(res.message);
                            if (!res.success || !res.data || res.data.length === 0) {
                                $('#salaryTableContainer').html(
                                    '<p>Không có dữ liệu lương tháng này.</p>');
                            } else {
                                console.log(res.data);
                                let html = `
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-hover align-middle text-center">
                                                        <thead class="table-primary">
                                                            <tr>
                                                                <th scope="col">Tên giáo viên</th>
                                                                <th scope="col">Mức lương / giờ</th>
                                                                <th scope="col">Số giờ</th>
                                                                <th scope="col">Thưởng</th>
                                                                <th scope="col">Phạt</th>
                                                                <th scope="col">Tổng lương</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                            `;

                                res.data.forEach(teacher => {
                                    html += `
                                                    <tr>
                                                        <td class="text-start">
                                                            <input type="hidden" name="teacher_id[]" value="${teacher.teacher_id}">
                                                            <strong>${teacher.teacher_name}</strong><br>
                                                            <small class="text-muted">${teacher.teacher_phone}</small>
                                                        </td>
                                                        <td>${Number(teacher.pay_rate).toLocaleString('vi-VN')}</td>
                                                        <td>${Number(teacher.total_hours).toFixed(2)}</td>
                                                        <td><input type="number" class="form-control text-end" name="bonus[]" value="${teacher.bonus || 0}"></td>
                                                        <td><input type="number" class="form-control text-end" name="penalty[]" value="${teacher.penalty || 0}"></td>
                                                        <td class="fw-bold text-success">${Number(teacher.total_salary).toLocaleString('vi-VN')}</td>
                                                    </tr>
                                                `;
                                });

                                html += `
                                                        </tbody>
                                                    </table>
                                                </div>
                                            `;
                                $('#salaryTableContainer').html(html);
                            }

                            // Hiển thị modal
                            const salaryModal = new bootstrap.Modal(document
                                .getElementById('salaryModal'));
                            salaryModal.show();
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                            $('#salaryTableContainer').html(
                                '<p>Lỗi khi tải dữ liệu.</p>');
                        }
                    });
                });

                $('#SaveSalaryBtn').on('click', function() {
                    let rows = $('#salaryTableContainer table tbody tr');
                    let salaries = [];

                    rows.each(function() {
                        let row = $(this).closest('tr');

                        // Nếu có hidden input cho teacher_id
                        let teacher_id = row.find('input[name="teacher_id[]"]').val();

                        // Lấy dữ liệu từ các cột hiển thị (text)
                        let pay_rate = row.find('td').eq(1).text().trim() // cột thứ 2
                        let total_hours = row.find('td').eq(2).text().trim() // cột thứ 3

                        // Lấy dữ liệu từ input bonus và penalty
                        let bonus = row.find('input[name="bonus[]"]').val();
                        let penalty = row.find('input[name="penalty[]"]').val();

                        let total_salary = row.find('td').eq(5).text().trim();
                        // Chuyển đổi dữ liệu sang định dạng số nếu cần

                        salaries.push({
                            teacher_id,
                            pay_rate,
                            total_hours,
                            bonus,
                            penalty,
                            total_salary
                        });
                    });

                    $.ajax({
                        url: "{{ route('admin.teacher_salaries.save') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            month: parseInt($('#salaryMonthInput').val().split('-')[1]),
                            year: parseInt($('#salaryMonthInput').val().split('-')[0]),
                            salaries: salaries // truyền mảng lương
                        },
                        success: function(res) {
                            if (res.success) {

                                Swal.fire({
                                    title: 'Cập nhật thành công!',
                                    text: 'cập nhật lương thành công',
                                    icon: 'success',
                                    confirmButtonClass: 'btn btn-primary w-xs mt-2',
                                    buttonsStyling: false
                                }).then(() => {
                                    window.location.href =
                                        "{{ route('admin.teacher_salaries') }}";
                                })

                            } else {
                                // alert(res.message || 'Lỗi khi lưu bảng lương.');

                                Swal.fire({
                                    title: 'Không thành công!',
                                    text: res.message,
                                    icon: 'error',
                                    confirmButtonClass: 'btn btn-primary w-xs mt-2',
                                    buttonsStyling: false
                                });
                                return;
                            }
                        },
                        error: function() {
                            alert('Lỗi kết nối khi lưu bảng lương.');
                        }
                    });
                });

            });
        });

        // JS mở bảng rules salary


        $(document).ready(function() {
            let teacherRules = {}; // 👈 Khai báo biến chứa dữ liệu lương

            $('#loadRulesBtn').on('click', function() {
                $.ajax({
                    url: "{{ route('admin.teacher-salary-rules.indexRules') }}",
                    type: "GET",
                    dataType: "json",
                    success: function(res) {
                        if (!res.success || !res.data || res.data.length === 0) {
                            $('#rulesTableContainer').html(
                                '<p>Không có dữ liệu lương tháng này.</p>');
                        } else {
                            let options = '<option value="">-- Chọn giáo viên --</option>';

                            res.data.forEach(rule => {
                                teacherRules[rule.id] = {
                                    name: rule.name,
                                    pay_rate: rule.pay_rate,
                                    effective_date: rule.effective_date
                                };

                                options +=
                                    `<option value="${rule.id}">${rule.name}</option>`;
                            });

                            $('#teacherSelect').html(options);
                        }

                        // Đặt ngoài if để luôn gán sự kiện
                        $('#teacherSelect').on('change', function() {
                            const id = $(this).val();

                            if (id && teacherRules[id]) {
                                const info = teacherRules[id];
                                $('#payRate').val(info.pay_rate);
                                $('#effectiveDate').val(info.effective_date);
                                $('#salaryDetails').show();

                                // Gọi API để lấy bảng TSR đầy đủ
                                $.ajax({
                                    url: `/admin/teacher-salary-rules/by-teacher/${id}`, // hoặc dùng route() nếu Blade xử lý được
                                    type: 'GET',
                                    success: function(res) {
                                        if (res.success && res.data.length >
                                            0) {
                                            let rows = '';
                                            res.data.forEach((item,
                                                index) => {
                                                rows += `
                                            <tr>
                                                <td>${index + 1}</td>
                                                <td>${item.pay_rate}</td>
                                                <td>${item.effective_date}</td>
                                                <td>${item.end_pay_rate || '-'}</td>
                                            </tr>`;
                                            });

                                            $('#rulesHistoryBody').html(
                                                rows);
                                            $('#teacherRulesHistory')
                                                .show();
                                        } else {
                                            $('#rulesHistoryBody').html(
                                                '<tr><td colspan="4">Không có dữ liệu</td></tr>'
                                            );
                                            $('#teacherRulesHistory')
                                                .show();
                                        }
                                    },
                                    error: function() {
                                        $('#rulesHistoryBody').html(
                                            '<tr><td colspan="4">Lỗi khi tải dữ liệu</td></tr>'
                                        );
                                        $('#teacherRulesHistory').show();
                                    }
                                });
                            } else {
                                $('#payRate').val('');
                                $('#effectiveDate').val('');
                                $('#salaryDetails').hide();
                                $('#teacherRulesHistory').hide();
                            }
                        });


                        // Hiển thị modal
                        const rulesModal = new bootstrap.Modal(document.getElementById(
                            'rulesModal'));
                        rulesModal.show();
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                        $('#rulesTableContainer').html('<p>Lỗi khi tải dữ liệu.</p>');
                    }
                });
            });

            $('#SaveRulesBtn').on('click', function() {
                const teacherId = $('#teacherSelect').val();
                const payRate = $('#payRate').val();
                const effectiveDate = $('#effectiveDate').val();

                if (!teacherId) {
                    Swal.fire({
                        title: 'Lưu ý!',
                        text: 'Vui lòng chọn giáo viên',
                        icon: 'error',
                        confirmButtonClass: 'btn btn-primary w-xs mt-2',
                        buttonsStyling: false
                    });
                    return;
                }

                if (!payRate || !effectiveDate) {
                    // alert('Vui lòng nhập đầy đủ mức lương và ngày bắt đầu.');
                    Swal.fire({
                        title: 'Lưu ý!',
                        text: 'Vui lòng nhập đầy đủ mức lương và ngày bắt đầu',
                        icon: 'error',
                        confirmButtonClass: 'btn btn-primary w-xs mt-2',
                        buttonsStyling: false
                    }).then(() => {
                        window.location.href = "{{ route('admin.teacher_salaries') }}";
                    })
                    return;
                }

                $.ajax({
                    url: "{{ route('admin.teacher-salary-rules.store') }}", // thay route đúng nếu khác
                    type: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        teacher_id: teacherId,
                        pay_rate: payRate,
                        effective_date: effectiveDate
                    },
                    success: function(res) {
                        if (res.success) {
                            console.log(res.data);
                            alert('Lưu bảng lương thành công!');

                            Swal.fire({
                                title: 'Thành công!',
                                text: 'Lưu bảng lương thành công',
                                icon: 'success',
                                confirmButtonClass: 'btn btn-primary w-xs mt-2',
                                buttonsStyling: false
                            }).then(() => {
                                window.location.href =
                                    "{{ route('admin.teacher_salaries') }}";
                            })

                        } else {
                            // alert();
                            Swal.fire({
                                title: 'Không thành công!',
                                text: res.message,
                                icon: 'error',
                                confirmButtonClass: 'btn btn-primary w-xs mt-2',
                                buttonsStyling: false
                            });
                            return;
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.mes);
                        alert('Đã xảy ra lỗi khi lưu dữ liệu.');
                    }
                });
            });
        });

        // end bảng



        // Cập nhật bonus và penalty
        $(document).on('input', 'input[name="bonus[]"], input[name="penalty[]"]', function() {
            let row = $(this).closest('tr');

            // ✅ Lấy giá trị từ các ô td hiển thị
            let pay_rate = parseFloat(row.find('td').eq(1).text().replace(/\./g, '').trim()) || 0;
            let total_hours = parseFloat(row.find('td').eq(2).text().trim()) || 0;

            // ✅ Lấy giá trị từ các input
            let bonus = parseFloat(row.find('input[name="bonus[]"]').val()) || 0;
            let penalty = parseFloat(row.find('input[name="penalty[]"]').val()) || 0;

            // ✅ Tính toán cẩn thận, tránh lỗi kiểu
            let total_salary = (pay_rate * total_hours) + bonus - penalty;

            // ✅ Cập nhật lại cột hiển thị lương
            row.find('td').eq(5).text(total_salary);
        });

        // Cập nhật tổng lương Khi người dùng thay đổi Trạng thái trả lương
        $(document).on('change', '.payment-status-select', function() {
            const select = $(this);
            const salaryId = select.data('salary-id');
            const paid = select.val();

            // Hiển thị xác nhận
            const confirmText = paid == 1 ?
                "Bạn có chắc muốn đánh dấu là Đã thanh toán?" :
                "Bạn có chắc muốn chuyển lại thành Chưa thanh toán?";

            if (!confirm(confirmText)) {
                // Quay lại trạng thái cũ nếu người dùng không xác nhận
                select.val(select.data('original'));
                return;
            }
            // Gửi AJAX để cập nhật trạng thái
            $.ajax({
                url: " {{ route('admin.teacher_salaries.upload') }}", // hoặc dùng route()
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    salary_id: salaryId,
                    paid: paid
                },
                success: function(res) {
                    if (res.success) {
                        Swal.fire({
                            title: 'Sửa thành công!',
                            text: 'Sửa trạng thái thành công',
                            icon: 'success',
                            confirmButtonClass: 'btn btn-primary w-xs mt-2',
                            buttonsStyling: false
                        }).then(() => {
                            window.location.href = "{{ route('admin.teacher_salaries') }}";
                        })

                    } else {
                        Swal.fire({
                            title: 'Không thành công!',
                            text: res.message,
                            icon: 'error',
                            confirmButtonClass: 'btn btn-primary w-xs mt-2',
                            buttonsStyling: false
                        });
                        return;
                    }
                },
                error: function() {
                    alert("Lỗi kết nối khi cập nhật trạng thái.");
                }
            });


        });

        // cập nhật note
        let currentSalaryId = null;

        $(document).on('click', '.note-cell', function() {
            currentSalaryId = $(this).data('id');
            const note = $(this).data('note');
            $('#noteContent').val(note);
            const modal = new bootstrap.Modal(document.getElementById('noteModal'));
            modal.show();
        });

        $('#saveNoteBtn').on('click', function() {
            const newNote = $('#noteContent').val();

            $.ajax({
                url: '{{ route('admin.teacher_salaries.upload') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    salary_id: currentSalaryId,
                    note: newNote
                },
                success: function(res) {
                    if (res.success) {
                        // Cập nhật trực tiếp nội dung ghi chú trên bảng
                        $(`.note-cell[data-id="${currentSalaryId}"]`).text(newNote ||
                            'Nhấn để thêm ghi chú');
                        $('#noteModal').modal('hide');

                    } else {
                        Swal.fire({
                            title: 'Cập nhật thất bại!',
                            text: 'Sửa trạng thái thất bại',
                            icon: 'error',
                            confirmButtonClass: 'btn btn-primary w-xs mt-2',
                            buttonsStyling: false
                        }).then(() => {
                            window.location.href = "{{ route('admin.teacher_salaries') }}";
                        })
                    }
                },
                error: function() {
                    alert('Lỗi khi cập nhật ghi chú.');
                }
            });
        });

        // Hiển thị thông báo khi Hover vào tên
        $(document).ready(function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });

        // Xử lý click vào tên giáo viên


        $(document).on('click', '.teacher-detail', function() {
            const salaryId = $(this).data('salary-id');

            console.log(salaryId);

            $.ajax({
                url: `./admin/teacher-salary-rules/${salaryId}/details`,
                type: 'GET',
                success: function(res) {
                    console.log(res.data);
                    if (res.success) {

                        let html = '<ul class="list-group">';
                        res.data.forEach(item => {
                            let [year, month, day] = item.effective_date.split('-');
                            html += `
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong class="fs-5">${item.name}</strong>
                                        <div class="text-muted small">
                                            <i class="bi bi-telephone"></i> ${item.phone}
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div>
                                    <span class="fw-semibold">Mức lương:</span>
                                    <span class="text-success">
                                        ${new Intl.NumberFormat('vi-VN').format(item.pay_rate)} VNĐ/giờ
                                    </span>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <span class="text-muted">
                                    Áp dụng từ: <i>${day}/${month}/${year}</i>
                                </span>
                            </li>
                        `;
                        });
                        html += '</ul>';
                        $('#salaryDetailContent').html(html);
                    } else {
                        $('#salaryDetailContent').html(`<div class="text-danger">${res.message}</div>`);
                    }
                    const modal = new bootstrap.Modal(document.getElementById('salaryDetailModal'));
                    modal.show();
                },
                error: function() {
                    $('#salaryDetailContent').html(
                        '<div class="text-danger">Lỗi khi tải chi tiết</div>');
                }
            });
        });

        // Khi người dùng nhập tên giáo viên vào input
        $('#teacherInput').on('input', function() {
            const typedName = $(this).val().toLowerCase().trim();
            let found = false;

            $('#teacherSelect option').each(function() {
                const optionText = $(this).text().toLowerCase().trim();

                if (optionText === typedName) {
                    $('#teacherSelect').val($(this).val()).trigger('change');
                    found = true;
                    return false; // thoát each
                }
            });

            if (!found) {
                $('#teacherSelect').val('');
                $('#payRate').val('');
                $('#effectiveDate').val('');
                $('#salaryDetails').hide();
            }
        });
    </script>

@endsection
