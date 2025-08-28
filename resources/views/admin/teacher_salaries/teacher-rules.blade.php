@extends('admin.admin')
@section('title', 'Chi tiết lương giáo viên')
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
                    <h3 class="mb-1">Chi tiết lương giáo viên </h3>
                    <form method="GET" action="{{ route('admin.teacher_salaries.detail') }}" class="row mb-2 mt-1">
                        <div class="col-md-3">
                            <label for="sort" class="form-label">Sắp xếp theo</label>
                            <select class="form-select" id="sort" name="sort">
                                <option value="created_at_desc" {{ request('sort') == 'created_at_desc' ? 'selected' : '' }}>Mới
                                    nhất
                                </option>
                                <option value="created_at_asc" {{ request('sort') == 'created_at_asc' ? 'selected' : '' }}>
                                    Cũ nhất</option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>
                                    Tên A-Z
                                </option>
                                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Tên Z-A
                                </option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="keyword" class="form-label">Tìm tên giáo viên</label>
                            <input type="text" name="keyword" id="keyword" class="form-control"
                                placeholder="Nhập tên giáo viên" value="{{ request('keyword') }}">
                        </div>

                        <div class="col-md-3">
                            <label for="month" class="form-label">Chọn tháng</label>
                            <input type="month" name="month" id="month" class="form-control"
                                value="{{ request('month', \Carbon\Carbon::now()->format('Y-m')) }}">
                        </div>

                        <div class="col-md-3 d-flex align-items-end gap-2">
                            <div class="flex-fill">
                                <button type="submit" class="btn btn-primary w-100">Lọc</button>
                            </div>
                            <div class="flex-fill">
                                <a href="{{ route('admin.teacher_salaries.detail') }}" class="btn btn-success w-100">Bỏ
                                    lọc</a>
                            </div>
                        </div>


                    </form>

                    <table class="table table-bordered table-striped mx-2">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Tên giáo viên</th>
                                <th>Mức lương</th>
                                <th>Ngày áp dụng</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $index => $user)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ number_format($user->pay_rate ?? 0, 0, ',', '.') }} VND</td>
                                    <td>{{ $user->effective_date ? \Carbon\Carbon::parse($user->effective_date)->format('d/m/Y') : '-' }}
                                    </td>
                                    <td>
                                        <button class="btn btn-primary btn-sm"
                                            onclick="openSalaryModal('{{ $user->id }}', '{{ $user->name }}')">
                                            <iconify-icon icon="mdi:card-account-details-outline"
                                                class="fs-16"></iconify-icon>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Không có dữ liệu lương trong tháng này</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="card-footer border-top">
                    <nav aria-label="Page navigation">
                        {!! $users->links('pagination::bootstrap-5') !!}
                    </nav>
                </div>

                <!-- Modal: Xem & cập nhật lương giáo viên -->
                <div class="modal fade" id="teacherSalaryModal" tabindex="-1" aria-labelledby="salaryModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Lịch sử bảng lương - <span id="modalTeacherName"></span></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Đóng"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Lịch sử lương -->
                                <div>
                                    <h6>Lịch sử lương</h6>
                                    <table class="table table-bordered table-sm">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Mức lương</th>
                                                <th>Ngày áp dụng</th>
                                                <th>Ngày kết thúc</th>
                                            </tr>
                                        </thead>
                                        <tbody id="salaryHistoryBody">
                                            <!-- Dữ liệu đổ bằng JS -->
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Form upload lương mới -->
                                <hr>
                                <h6>Thêm mức lương mới</h6>
                                <form id="uploadSalaryForm">
                                    <input type="hidden" name="teacher_id" id="modalTeacherId">
                                    <div class="mb-3">
                                        <label>Mức lương</label>
                                        <input type="text" class="form-control format-currency" name="pay_rate" required
                                            min="0">
                                    </div>
                                    <div class="mb-3">
                                        <label>Tháng áp dụng</label>
                                        <input type="month" class="form-control" id="monthPicker" required>
                                        <input type="hidden" name="effective_date" id="effectiveDate">
                                    </div>
                                    <div class="text-end">
                                        <button type="submit" data-bs-dismiss="modal"
                                            class="btn btn-outline-secondary">Đóng</button>
                                        <button type="submit" class="btn btn-outline-primary">Lưu lương mới</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Modal -->




            </div>



        </div>
        <!-- end row -->
        <!-- ========== Footer Start ========== -->

    </div>
    <scrip src="https://code.jquery.com/jquery-3.6.0.min.js"></scrip>

@endsection
@push('scripts')
    <script>
        function formatDate(dateStr) {
            if (!dateStr) return '';
            const date = new Date(dateStr);
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();
            return `${day}/${month}/${year}`;
        }

        function openSalaryModal(teacherId, teacherName) {
            document.getElementById('modalTeacherName').textContent = teacherName;
            document.getElementById('modalTeacherId').value = teacherId;
            document.querySelector('#uploadSalaryForm').reset();

            loadSalaryHistory(teacherId); // Gọi load bảng lịch sử

            const modalEl = document.getElementById('teacherSalaryModal');
            const modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
            modalInstance.show();
        }


        // Handle form submit
        document.getElementById('uploadSalaryForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = this;
            const formData = new FormData(form);
            const url = `{{ route('admin.teacher_salary.store') }}`;

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        Swal.fire({
                            title: 'Cập nhật!',
                            text: 'Cập nhật lương thành công!',
                            icon: 'success',
                            confirmButtonClass: 'btn btn-primary w-xs mt-2',
                            buttonsStyling: false
                        }).then(() => {
                            const teacherId = form.teacher_id.value;
                            loadSalaryHistory(teacherId);
                        })

                    } else {
                        Swal.fire({
                            title: 'Cập nhật!',
                            text: res.message,
                            icon: 'error',
                            confirmButtonClass: 'btn btn-primary w-xs mt-2',
                            buttonsStyling: false
                        })
                    }
                })
                .catch(() => alert('Lỗi khi gửi yêu cầu'));
        });

        function loadSalaryHistory(teacherId) {
            const url = `{{ route('admin.teacher-salary-rules.byTeacher', ':id') }}`.replace(':id', teacherId);
            const salaryBody = document.getElementById('salaryHistoryBody');
            salaryBody.innerHTML = '<tr><td colspan="4">Đang tải...</td></tr>';

            fetch(url)
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        const rules = res.data;
                        if (rules.length === 0) {
                            salaryBody.innerHTML = '<tr><td colspan="4" class="text-center">Chưa có dữ liệu</td></tr>';
                        } else {
                            salaryBody.innerHTML = '';
                            rules.forEach((rule, index) => {
                                salaryBody.innerHTML += `
                         <tr>
                            <td>${index + 1}</td>
                            <td>${Number(rule.pay_rate).toLocaleString()} VND</td>
                            <td>${formatDate(rule.effective_date)}</td>
                            <td>${formatDate(rule.end_pay_rate)}</td>
                        </tr>
                        `;
                            });
                        }
                    }
                });
        }

        document.getElementById('monthPicker').addEventListener('change', function() {
            const selected = this.value; // dạng "2025-08"
            if (selected) {
                document.getElementById('effectiveDate').value = selected + '-01'; // luôn set ngày 01
            }
        });
         document.addEventListener("DOMContentLoaded", function () {
                let formatter = new Intl.NumberFormat('vi-VN');

                document.querySelectorAll(".format-currency").forEach(function (input) {
                    input.addEventListener("input", function () {
                        // chỉ giữ số
                        let raw = this.value.replace(/\D/g, "");
                        // lưu lại số thô để submit
                        this.dataset.raw = raw;
                        // hiển thị có dấu phẩy
                        this.value = raw ? formatter.format(raw) : "";
                    });

                    // khi submit form -> đổi thành số thô
                    input.form.addEventListener("submit", function () {
                        input.value = input.dataset.raw || "";
                    });
                });
                
            });
       
    </script>
@endpush
