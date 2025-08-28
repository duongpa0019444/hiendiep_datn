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
                    </div>
                    <div>
                        <button type="button" class="btn btn-outline-primary m-1" id="loadSalaryBtn">
                            Tạo bảng lương
                        </button>
                        <button type="button" class="btn btn-outline-primary m-1" onclick="printSalaryTable()">
                            <iconify-icon icon="mdi:printer" class="fs-20 align-middle me-1"></iconify-icon>
                            In bảng lương
                        </button>
                        <script>
                            let isLocked = {{ $isLocked ? 'true' : 'false' }};
                        </script>
                        <button type="button" class="btn btn-outline-danger m-1" id="lockSalaryBtn">
                            <iconify-icon icon="mdi:lock" class="fs-20 align-middle me-1"></iconify-icon>
                            Chốt bảng lương
                        </button>
                        @if (Auth::user()->isAdmin())
                            <button type="button" class="btn btn-outline-success m-1 d-none" id="unlockSalaryBtn">
                                <iconify-icon icon="mdi:lock-open" class="fs-20 align-middle me-1"></iconify-icon>
                                Mở khóa bảng lương
                            </button>
                        @endif
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



                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                            </div>
                            <div class="modal-body">
                                <div id="salaryTableContainer">

                                </div>
                                <div class="text-end">
                                    <button type="button" data-bs-dismiss="modal"
                                        class="btn btn-outline-secondary btn-sm m-1">Đóng</button>
                                    <button type="button" class="btn btn-outline-primary btn-sm m-1" id="SaveSalaryBtn">
                                        Lưu
                                        bảng lương</button>
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
                    <div id="printableSalaryTable">

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
                                        <tr data-month="{{ $salary->month }}" data-year="{{ $salary->year }}">
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

                                            <td class="text-end text-success bonus" data-salary-id="{{ $salary->id }}"
                                                data-bonus="{{ $salary->bonus }}" data-penalty="{{ $salary->penalty }}"
                                                data-active="{{ $salary->active }}"
                                                style="cursor: {{ $salary->active == 1 ? 'not-allowed' : 'pointer' }}">
                                                +{{ number_format($salary->bonus, 0, ',', '.') }} VNĐ
                                            </td>
                                            <td class="text-end text-danger penalty" data-salary-id="{{ $salary->id }}"
                                                data-penalty="{{ $salary->penalty }}" data-bonus="{{ $salary->bonus }}"
                                                data-active="{{ $salary->active }}"
                                                style="cursor: {{ $salary->active == 1 ? 'not-allowed' : 'pointer' }}">
                                                -{{ number_format($salary->penalty, 0, ',', '.') }} VNĐ
                                            </td>
                                            <td class="text-end fw-bold text-success total"
                                                data-salary-id="{{ $salary->id }}">
                                                {{ number_format($salary->total_salary, 0, ',', '.') }} VNĐ
                                            </td>
                                            <td class="text-center status-cell">
                                                @if ($salary->active == 1)
                                                    <span class="badge bg-secondary">Đã chốt</span>
                                                @else
                                                    <select class="form-select payment-status-select"
                                                        data-salary-id="{{ $salary->id }}"
                                                        data-original="{{ $salary->paid }}">
                                                        <option value="0" {{ $salary->paid == 0 ? 'selected' : '' }}>
                                                            Chưa thanh toán</option>
                                                        <option value="1" {{ $salary->paid == 1 ? 'selected' : '' }}>
                                                            Đã thanh toán</option>
                                                    </select>
                                                @endif
                                            </td>

                                            <td class="text-center payment-date-cell"
                                                data-salary-id="{{ $salary->id }}">
                                                @if ($salary->payment_date)
                                                    <span class="badge bg-success">
                                                        {{ \Carbon\Carbon::parse($salary->payment_date)->format('d/m/Y') }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger">Chưa thanh toán</span>
                                                @endif
                                            </td>
                                            <td class="note-cell" data-id="{{ $salary->id }}"
                                                data-note="{{ $salary->note }}" data-active="{{ $salary->active }}"
                                                style="cursor: {{ $salary->active == 1 ? 'not-allowed' : 'pointer' }}">
                                                {{ $salary->note ?: 'Thêm' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- end table-responsive -->
                </div>

                <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 m-3">
                    <div id="pagination-wrapper" class="flex-grow-1">
                        {{ $salaries->links('pagination::bootstrap-5') }}
                    </div>

                    {{-- <div class="d-flex align-items-center" style="min-width: 160px;">
                        <label for="limit2" class="form-label mb-0 me-2 small">Hiển thị</label>
                        <select name="limit2" id="limit2" class="form-select form-select-sm" style="width: 100px;">
                            <option value="10" selected>10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div> --}}
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
    <!-- Modal Chốt bảng lương -->
    <div class="modal fade" id="lockSalaryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chốt bảng lương</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="lockMonth" class="form-label">Tháng</label>
                        <select id="lockMonth" class="form-select">
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}">{{ $m }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="lockYear" class="form-label">Năm</label>
                        <select id="lockYear" class="form-select">
                            @for ($y = date('Y'); $y >= 2020; $y--)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-danger" id="confirmLockSalaryBtn">
                        <iconify-icon icon="mdi:lock"></iconify-icon> Chốt
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="unlockSalaryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Mở khóa bảng lương</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="unlockMonth" class="form-label">Tháng</label>
                        <select id="unlockMonth" class="form-select">
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}">{{ $m }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="unlockYear" class="form-label">Năm</label>
                        <select id="unlockYear" class="form-select">
                            @for ($y = date('Y'); $y >= 2020; $y--)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-success" id="confirmUnlockSalaryBtn">
                        <iconify-icon icon="mdi:lock-open"></iconify-icon> Mở khóa
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal  -->
    <div class="modal fade" id="salaryModalBonusPenalty" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="salaryModalLabelBonusPenalty">Cập nhật</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <form id="salaryForm">
                        <input type="hidden" id="salaryId">
                        <input type="hidden" id="salaryType">
                        <div class="mb-3">
                            <label for="amount" class="form-label">Nhập số tiền</label>
                            <input type="text" class="form-control" id="amount" required min="0"
                                step="1">
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" id="saveSalaryBtnBonusPenalty">Lưu</button>
                </div>

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
        function formatNumber(num) {
            return new Intl.NumberFormat('vi-VN').format(num);
        }
        // function tạo bảng lương
        function renderSalaryTable(data) {
            let html = '';

            data.forEach(salary => {
                html += `
                                            <tr data-month="${salary.month}" 
                                            data-year="${salary.year}">
                                                <td class="text-start">
                                                    <strong>${salary.teacher_name}</strong><br> 
                                                    <iconify-icon icon="solar:phone-broken" class="fs-16 me-1"></iconify-icon>
                                                    <span class="text-muted">${salary.teacher_phone}</span>
                                                </td>

                                                <td class="text-center">Tháng ${salary.month} / ${salary.year}</td>
                                                <td class="text-center">${salary.total_hours} giờ</td>
                                                <td class="text-end">${Number(salary.pay_rate).toLocaleString('vi-VN')} VNĐ/giờ</td>
                                                <td class="text-end text-success bonus" data-salary-id="${salary.id}" data-bonus="${salary.bonus}" data-penalty="${salary.penalty}"   data-active="${salary.active}"  style="cursor: ${salary.active == 1 ? 'not-allowed' : 'pointer'}">+${Number(salary.bonus).toLocaleString('vi-VN')} VNĐ</td>
                                                <td class="text-end text-danger penalty"  data-salary-id="${salary.id}" data-bonus="${salary.bonus}" data-penalty="${salary.penalty}"   data-active="${salary.active}"  style="cursor: ${salary.active == 1 ? 'not-allowed' : 'pointer'}">-${Number(salary.penalty).toLocaleString('vi-VN')} VNĐ</td>
                                                <td class="text-end fw-bold text-success total" data-salary-id="${salary.id}">${Number(salary.total_salary).toLocaleString('vi-VN')} VNĐ</td>

                                                <td class="text-center status-cell">
                                                    ${salary.active == 1 
                                                        ? '<span class="badge bg-secondary">Đã chốt</span>' 
                                                        : `<select class="form-select payment-status-select" data-salary-id="${salary.id}" data-original="${salary.paid}" >
                                                                    <option value="0" ${salary.paid == 0 ? 'selected' : ''}>Chưa thanh toán</option>
                                                                    <option value="1" ${salary.paid == 1 ? 'selected' : ''}>Đã thanh toán</option>
                                                            </select>`
                                                    }
                                                </td>

                                                <td class="text-center payment-date-cell" data-salary-id="${salary.id}">
                                                    ${salary.paid == 1 && salary.payment_date 
                                                        ? `<span class="badge bg-success">${moment(salary.payment_date).format('DD/MM/YYYY')}</span>` 
                                                        : '<span class="badge bg-danger">Chưa thanh toán</span>'
                                                    }
                                                </td>

                                                <td class="note-cell"
                                                    data-id="${salary.id}"
                                                    data-note="${salary.note}"
                                                    data-active="${salary.active}"
                                                    style="cursor: ${salary.active == 1 ? 'not-allowed' : 'pointer'};">
                                                    ${salary.note || 'Thêm'}
                                                </td>
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

                            if (res.isLocked == 1) {
                                $('#lockSalaryBtn').addClass('d-none');
                                $('#unlockSalaryBtn').removeClass('d-none');
                            } else {
                                $('#lockSalaryBtn').removeClass('d-none');
                                $('#unlockSalaryBtn').addClass('d-none');
                            }
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
                                                        <td>${Number(teacher.total_hours)}</td>
                                                        <td><input type="text" class="form-control text-end" name="bonus[]" value="${Number(teacher.bonus || 0).toLocaleString('vi-VN')}"></td>
                                                        <td><input type="text" class="form-control text-end" name="penalty[]" value="${Number(teacher.penalty || 0).toLocaleString('vi-VN')}"></td>
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
                        let pay_rate = parseFloat(row.find('td').eq(1).text().replace(/\D/g,
                            '')) || 0;
                        let total_hours = parseFloat(row.find('td').eq(2).text().replace(
                            /\D/g, '')) || 0;

                        // Lấy dữ liệu từ input bonus và penalty (bỏ dấu phân cách)
                        let bonus = parseFloat(row.find('input[name="bonus[]"]').val()
                            .replace(/,/g, '').replace(/\./g, '')) || 0;
                        let penalty = parseFloat(row.find('input[name="penalty[]"]').val()
                            .replace(/,/g, '').replace(/\./g, '')) || 0;

                        // Lấy total salary (cũng bỏ định dạng trước khi parse)
                        let total_salary = parseFloat(row.find('td').eq(5).text().replace(
                            /,/g, '').replace(/\./g, '')) || 0;


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


        const numberFormatter = new Intl.NumberFormat('vi-VN');

        function toMoneyNumber(str) {
            if (!str) return 0;
            return parseInt(str.replace(/\D/g, ''), 10) || 0;
        }

        // Chuyển từ "4,00" => 4.00  (giữ số thập phân)
        function toHourNumber(str) {
            if (!str) return 0;
            return parseFloat(str.replace(',', '.')) || 0;
        }
        $(document).on('input', 'input[name="bonus[]"], input[name="penalty[]"]', function() {
            let input = this;

            // Lấy vị trí con trỏ hiện tại
            let cursorPos = input.selectionStart;

            // Lấy giá trị raw (chỉ số)
            let rawValue = input.value.replace(/\D/g, '');

            // Format lại
            let formattedValue = rawValue ? numberFormatter.format(rawValue) : '';

            // Tính toán độ chênh lệch độ dài
            let diff = formattedValue.length - input.value.length;

            // Gán lại giá trị
            input.value = formattedValue;

            // Cập nhật lại vị trí con trỏ
            input.setSelectionRange(cursorPos + diff, cursorPos + diff);

            // --- Tính lại lương ---
            let row = $(this).closest('tr');

            // Lấy dữ liệu
            let pay_rate = toMoneyNumber(row.find('td').eq(1).text()); // lương/giờ
            let total_hours = toHourNumber(row.find('td').eq(2).text()); // số giờ
            let bonus = toMoneyNumber(row.find('input[name="bonus[]"]').val());
            let penalty = toMoneyNumber(row.find('input[name="penalty[]"]').val());

            // Tính toán
            let total_salary = (pay_rate * total_hours) + bonus - penalty;

            console.log({
                pay_rate,
                total_hours,
                bonus,
                penalty,
                total_salary
            });

            // Ghi lại vào đúng cột tổng lương (kiểm tra index đúng với HTML)
            row.find('td').eq(5).text(numberFormatter.format(total_salary));
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

            Swal.fire({
                title: confirmText,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Vâng, tiếp tục',
                cancelButtonText: 'Không, hủy',
                confirmButtonClass: 'btn btn-danger w-xs me-2 mt-2',
                cancelButtonClass: 'btn btn-secondary w-xs mt-2',
                buttonsStyling: false,
            }).then((result) => {
                if (!result.isConfirmed) {
                    select.val(select.data('original'));
                    return;
                }

                $.ajax({
                    url: "{{ route('admin.teacher_salaries.upload') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        salary_id: salaryId,
                        paid: paid
                    },
                    success: function(res) {
                        if (res.success) {
                            select.data('original', res.paid);
                            Swal.fire({
                                title: 'Sửa thành công!',
                                text: 'Sửa trạng thái thành công',
                                icon: 'success',
                                confirmButtonClass: 'btn btn-primary w-xs mt-2',
                                buttonsStyling: false
                            }).then(() => {
                                const cell = $('.payment-date-cell[data-salary-id="' +
                                    salaryId + '"]');

                                if (res.paid == 1 && res.payment_date) {
                                    // Nếu đã thanh toán
                                    const formattedDate = moment(res.payment_date)
                                        .format('DD/MM/YYYY');
                                    cell.html('<span class="badge bg-success">' +
                                        formattedDate + '</span>');
                                } else {
                                    // Nếu chuyển lại chưa thanh toán
                                    cell.html(
                                        '<span class="badge bg-danger">Chưa thanh toán</span>'
                                        );
                                }
                            });
                        } else {
                            Swal.fire({
                                title: 'Không thành công!',
                                text: res.message,
                                icon: 'error',
                                confirmButtonClass: 'btn btn-primary w-xs mt-2',
                                buttonsStyling: false
                            });

                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Lỗi!',
                            text: 'Lỗi kết nối khi cập nhật trạng thái.',
                            icon: 'error',
                            confirmButtonClass: 'btn btn-primary w-xs mt-2',
                            buttonsStyling: false
                        });
                    }
                });
            });



        });

        // cập nhật note
        let currentSalaryId = null;

        $(document).on('click', '.note-cell', function() {
            // kiểm tra trạng thái chốt lương
            if ($(this).data('active') == 1) {
                // Có thể show thông báo nếu muốn
                Swal.fire({
                    title: 'Bảng lương đã chốt!',
                    text: 'Không thể thêm hoặc sửa ghi chú.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return; // dừng không cho mở modal
            }

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

        // Xử lý click vào tên giáo viên



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


        // hàm in bảng lương
        function printSalaryTable() {
            const contents = document.getElementById('printableSalaryTable').innerHTML;
            const newWin = window.open('', '', 'width=900,height=700');

            newWin.document.write(`
            <html>
                <head>
                    <style>
                        body { font-family: DejaVu Sans, sans-serif; padding: 20px; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        th, td { border: 1px solid #333; padding: 8px; text-align: center; }
                        th { background-color: #f0f0f0; }
                    </style>
                </head>
                <body>
                    <h3 style="text-align:center;">BẢNG LƯƠNG GIÁO VIÊN</h3>
                    ${contents}
                </body>
            </html>
        `);

            newWin.document.close();
            newWin.focus();
            newWin.print();
            newWin.close();
        }


        function toggleSalaryButtons(isLocked) {
            if (isLocked) {
                $('#lockSalaryBtn').addClass('d-none');
                $('#unlockSalaryBtn').removeClass('d-none');
            } else {
                $('#unlockSalaryBtn').addClass('d-none');
                $('#lockSalaryBtn').removeClass('d-none');
            }
        }
        toggleSalaryButtons(isLocked);

        // $(document).ready(function () {
        //     toggleSalaryButtons(isLocked);
        // });

        $('#lockSalaryBtn').on('click', function() {
            $('#lockSalaryModal').modal('show');
        });

        // Khi bấm xác nhận chốt
        $('#confirmLockSalaryBtn').on('click', function() {
            let month, year;

            // Lấy giá trị từ input filter
            let filterVal = $('#filterMonth').val(); // dạng "2025-08"

            if (filterVal) {
                // Nếu có chọn ở bộ lọc thì tách ra
                let parts = filterVal.split('-');
                year = parts[0];
                month = parseInt(parts[1], 10); // bỏ số 0 ở trước
            } else {
                // Nếu không có thì lấy tháng/năm hiện tại
                let today = new Date();
                month = today.getMonth() + 1; // getMonth trả 0-11
                year = today.getFullYear();
            }

            Swal.fire({
                title: `Bạn có chắc muốn chốt bảng lương tháng ${month}/${year}?`,
                text: "Sau khi chốt sẽ không thể chỉnh sửa.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Chốt ngay',
                cancelButtonText: 'Hủy',
                confirmButtonClass: 'btn btn-primary w-xs mt-2',
                cancelButtonClass: 'btn btn-secondary w-xs mt-2',
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('admin.teacher_salaries.lock') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            month: month,
                            year: year
                        },
                        success: function(res) {
                            if (res.success) {
                                Swal.fire({
                                    title: 'Thành công!',
                                    text: res.message,
                                    icon: 'success',
                                    confirmButtonClass: 'btn btn-primary w-xs mt-2',
                                    buttonsStyling: false
                                }).then(() => {
                                    // Reload bảng lương bằng ajax
                                    renderSalaryTable(res.data);
                                    toggleSalaryButtons(true);
                                    $('#lockSalaryModal').modal('hide'); // đóng modal
                                });
                            } else {
                                Swal.fire({
                                    title: 'Cập nhật thất bại!',
                                    text: res.message || 'Sửa trạng thái thất bại',
                                    icon: 'error',
                                    confirmButtonClass: 'btn btn-primary w-xs mt-2',
                                    buttonsStyling: false
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                title: 'Cập nhật thất bại!',
                                text: 'Có lỗi xảy ra khi chốt bảng lương.',
                                icon: 'error',
                                confirmButtonClass: 'btn btn-primary w-xs mt-2',
                                buttonsStyling: false
                            });
                        }
                    });
                }
            });

        });

        $('#unlockSalaryBtn').on('click', function() {
            $('#unlockSalaryModal').modal('show');
        });

        // Khi bấm xác nhận mở khóa
        $('#confirmUnlockSalaryBtn').on('click', function() {
            let month, year;

            // Lấy giá trị từ input filter
            let filterVal = $('#filterMonth').val(); // dạng "2025-08"

            if (filterVal) {
                // Nếu có chọn ở bộ lọc thì tách ra
                let parts = filterVal.split('-');
                year = parts[0];
                month = parseInt(parts[1], 10); // bỏ số 0 ở trước
            } else {
                // Nếu không có thì lấy tháng/năm hiện tại
                let today = new Date();
                month = today.getMonth() + 1; // getMonth trả 0-11
                year = today.getFullYear();
            }

            Swal.fire({
                title: `Bạn có chắc muốn mở khóa bảng lương tháng ${month}/${year}?`,
                text: "Sau khi mở khóa có thể chỉnh sửa lại.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Mở khóa ngay',
                cancelButtonText: 'Hủy',
                confirmButtonClass: 'btn btn-success w-xs mt-2',
                cancelButtonClass: 'btn btn-secondary w-xs mt-2',
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('admin.teacher_salaries.unlock') }}', // route mới
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            month: month,
                            year: year
                        },
                        success: function(res) {
                            if (res.success) {
                                Swal.fire({
                                    title: 'Thành công!',
                                    text: res.message,
                                    icon: 'success',
                                    confirmButtonClass: 'btn btn-primary w-xs mt-2',
                                    buttonsStyling: false
                                }).then(() => {
                                    // Reload bảng lương bằng ajax
                                    renderSalaryTable(res.data);
                                    toggleSalaryButtons(false);

                                    $('#unlockSalaryModal').modal('hide');
                                });
                            } else {
                                Swal.fire({
                                    title: 'Thất bại!',
                                    text: res.message || 'Mở khóa thất bại',
                                    icon: 'error',
                                    confirmButtonClass: 'btn btn-primary w-xs mt-2',
                                    buttonsStyling: false
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                title: 'Lỗi!',
                                text: 'Có lỗi xảy ra khi mở khóa bảng lương.',
                                icon: 'error',
                                confirmButtonClass: 'btn btn-primary w-xs mt-2',
                                buttonsStyling: false
                            });
                        }
                    });
                }
            });
        });
        $('#lockSalaryModal').on('show.bs.modal', function() {
            let filterVal = $('#filterMonth').val();
            let month, year;

            if (filterVal) {
                let parts = filterVal.split('-');
                year = parts[0];
                month = parseInt(parts[1], 10);
            } else {
                let today = new Date();
                month = today.getMonth() + 1;
                year = today.getFullYear();
            }

            // Gán vào select trong modal
            $('#lockMonth').val(month);
            $('#lockYear').val(year);
        });
        $('#unlockSalaryModal').on('show.bs.modal', function() {
            let filterVal = $('#filterMonth').val();
            let month, year;

            if (filterVal) {
                let parts = filterVal.split('-');
                year = parts[0];
                month = parseInt(parts[1], 10);
            } else {
                let today = new Date();
                month = today.getMonth() + 1;
                year = today.getFullYear();
            }

            // Gán vào select trong modal
            $('#unlockMonth').val(month);
            $('#unlockYear').val(year);
        });

        $(document).ready(function() {
            // Khi click vào bonus hoặc penalty
            $("#amount").on("input", function() {
                let raw = $(this).val();

                // Chuyển chuỗi về số nguyên (bỏ ký tự . , VNĐ ...)
                let num = toMoneyNumber(raw);

                // Định dạng lại với dấu chấm ngăn cách nghìn
                $(this).val(num ? numberFormatter.format(num) : "");
            });

            $(document).on("click", ".bonus, .penalty", function() {
                // kiểm tra trạng thái chốt lương
                if ($(this).data('active') == 1) {
                    // Có thể show thông báo nếu muốn
                    Swal.fire({
                        title: 'Bảng lương đã chốt!',
                        text: 'Không thể thêm hoặc sửa thưởng/phạt.',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                    return; // dừng không cho mở modal
                }

                let salaryId = $(this).data("salary-id"); // lấy id từ data-salary-id
                let type = $(this).hasClass("bonus") ? "bonus" : "penalty"; // loại
                let bonus = $(this).data("bonus") || 0; // lấy data-bonus
                let penalty = $(this).data("penalty") || 0; //

                bonus = parseInt(bonus); // => 100000
                penalty = parseInt(penalty); // => 50000
                console.log({
                    salaryId,
                    type,
                    bonus,
                    penalty
                });
                // Gán dữ liệu vào input hidden trong modal
                $("#salaryId").val(salaryId);
                $("#salaryType").val(type);

                $("#amount").val(
                    type === "bonus" ? formatNumber(bonus) : formatNumber(penalty)
                );
                // Đặt tiêu đề modal cho rõ ràng
                $("#salaryModalLabelBonusPenalty").text(
                    type === "bonus" ? "Cập nhật thưởng" : "Cập nhật phạt"
                );

                // Hiện modal (Bootstrap 5)
                let modal = new bootstrap.Modal(document.getElementById("salaryModalBonusPenalty"));
                modal.show();
            });

            // Khi bấm Lưu
            $("#saveSalaryBtnBonusPenalty").on("click", function() {
                let salaryId = $("#salaryId").val();
                let type = $("#salaryType").val();
                let amount = toMoneyNumber($("#amount").val());


                console.log(amount)
                $.ajax({
                    url: "/admin/update-salary", // Route Laravel
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        salary_id: salaryId,
                        type: type,
                        amount: amount
                    },
                    success: function(response) {


                        // Cập nhật lại text của <td> ngay sau khi lưu
                        let td = $("." + type + "[data-salary-id='" + salaryId + "']");
                        let total = $(".total" + "[data-salary-id='" + salaryId + "']");
                        if (type === "bonus") {
                            td.text("+" + response.new_value + " VNĐ"); // cập nhật data-bonus
                            total.text(response.new_total + " VNĐ"); // cập nhật tổng lương
                        } else {
                            td.text("-" + response.new_value + " VNĐ"); // cập nhật data-penalty
                            total.text(response.new_total + " VNĐ"); // cập nhật tổng lương
                        }
                        // Đóng modal
                        $("#salaryModalBonusPenalty").modal("hide");
                    },
                    error: function() {
                        alert("Có lỗi xảy ra!");
                        console.log(amount)
                    }
                });
            });
        });
    </script>

@endsection
