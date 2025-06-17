@extends('admin.admin')

@section('title', 'Schedules Management')
@section('description', 'Manage class schedules and sessions')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        td span.editable {
            transition: background 0.2s;
            cursor: pointer;
            position: relative;
        }

        td span.editable:hover {
            background: #e9ecef;
            border-radius: 3px;
        }
    </style>
@endpush

@section('content')
    <div class="page-content">
        <div class="container-xxl">
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="mb-1">Quản lý lịch học</h3>
                    <p class="text-muted mb-0">Tổng số: {{ $classes->total() ?? $classes->count() }} lớp học</p>
                </div>
            </div>

            <!-- Search and Filter Section -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.schedules.index') }}" class="row g-3 align-items-end mb-0"
                        id="filter-search-form">
                        <div class="col-md-3">
                            <label for="search" class="form-label">Tìm kiếm</label>
                            <input type="text" class="form-control w-100" id="search" name="search"
                                placeholder="Nhập tên lớp hoặc khóa học..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label">Trạng thái</label>
                            <select class="form-select w-100" id="status" name="status">
                                <option value="">Tất cả trạng thái</option>
                                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>Đang
                                    hoạt động</option>
                                <option value="not_started" {{ request('status') == 'not_started' ? 'selected' : '' }}>Tạm
                                    dừng</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Đã hoàn
                                    thành</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="sort" class="form-label">Sắp xếp theo</label>
                            <select class="form-select w-100" id="sort" name="sort">
                                <option value="created_at_desc"
                                    {{ request('sort') == 'created_at_desc' ? 'selected' : '' }}>Mới nhất</option>
                                <option value="created_at_asc" {{ request('sort') == 'created_at_asc' ? 'selected' : '' }}>
                                    Cũ nhất</option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên A-Z
                                </option>
                                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Tên Z-A
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-outline-primary w-100">
                                <i class="fas fa-search"></i> Tìm kiếm
                            </button>
                            <button type="button" class="btn btn-outline-secondary w-100" id="reset-filter-btn">
                                <i class="fas fa-times"></i> Xóa lọc
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Alert Messages -->
            @if (session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công!',
                            text: '{{ session('success') }}',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    });
                </script>
            @endif

            @if (session('error'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi!',
                            text: '{{ session('error') }}',
                            timer: 2500,
                            showConfirmButton: false
                        });
                    });
                </script>
            @endif

            <!-- Classes Table -->
            @if ($classes->isEmpty())
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-graduation-cap fa-3x text-muted"></i>
                    </div>
                    <h5 class="text-muted">Chưa có lớp học nào</h5>
                    <p class="text-muted">Hãy tạo lớp học đầu tiên của bạn</p>
                    {{-- <a href="{{ route('admin.classes.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tạo lớp mới
                    </a> --}}
                </div>
            @else
                <div id="schedules-table-wrapper">
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" width="80">#</th>
                                            <th scope="col">Tên lớp</th>
                                            <th scope="col">Tên khóa học</th>
                                            <th scope="col">Số buổi học</th>
                                            <th scope="col" width="120">Trạng thái</th>
                                            <th scope="col" width="150">Số học sinh</th>
                                            <th scope="col" width="120">Ngày tạo</th>
                                            <th scope="col" width="120">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($classes as $index => $class)
                                            <tr id="row-{{ $class->id }}">
                                                <th scope="row" class="text-muted">
                                                    {{ ($classes->currentPage() - 1) * $classes->perPage() + $index + 1 }}
                                                </th>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div>
                                                            <h6 class="mb-0">{{ $class->name }}</h6>
                                                            @if ($class->description)
                                                                <small
                                                                    class="text-muted">{{ Str::limit($class->description, 50) }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div>
                                                            <h6 class="mb-0">{{ $class->course_name ?? 'N/A' }}</h6>
                                                            @if ($class->course_description)
                                                                <small
                                                                    class="text-muted">{{ Str::limit($class->course_description, 50) }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-calendar-alt text-muted me-2"></i>
                                                        <span>{{ $class->sessions_count ?? ($class->so_buoi_hoc ?? 0) }}</span>
                                                    </div>
                                                </td>
                                                <td data-status="{{ $class->status }}">
                                                    @switch($class->status)
                                                        @case('in_progress')
                                                            <span class="badge bg-success">Hoạt động</span>
                                                        @break

                                                        @case('not_started')
                                                            <span class="badge bg-warning">Tạm dừng</span>
                                                        @break

                                                        @case('completed')
                                                            <span class="badge bg-info">Hoàn thành</span>
                                                        @break

                                                        @default
                                                            <span class="badge bg-secondary">{{ $class->status }}</span>
                                                    @endswitch
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-users text-muted me-2"></i>
                                                        <span>{{ $class->students_count ?? ($class->so_hoc_sinh ?? 0) }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        {{ \Carbon\Carbon::parse($class->start_date ?? $class->created_at)->format('d/m/Y') }}
                                                    </small>
                                                </td>
                                                <td>
                                                    {{-- <div class="dropdown">
                                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                            type="button" id="dropdownMenuButton"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                            <li>
                                                                <a class="dropdown-item"
                                                                    href="{{ route('admin.classes.schedules', $class->id) }}">
                                                                    <i class="fas fa-calendar-alt"></i> Xem lịch học
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item create-schedule-btn"
                                                                    href="#" data-class-id="{{ $class->id }}">
                                                                    <i class="fas fa-plus"></i> Tạo lịch học
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div> --}}
                                                    <div class="d-flex gap-1">
                                                        <a href="{{ route('admin.classes.schedules', $class->id) }}"
                                                            class="btn btn-sm btn-outline-info btn-icon">
                                                            <i class="fas fa-calendar-alt"></i> Lịch học
                                                        </a>
                                                        <a href="#"
                                                            class="btn btn-sm btn-outline-primary btn-icon create-schedule-btn"
                                                            data-class-id="{{ $class->id }}">
                                                            <i class="fas fa-plus"></i> Tạo lịch
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center text-muted py-4">
                                                        Không có dữ liệu phù hợp
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div id="pagination-wrapper" class="flex-grow-1">
                            {{ $classes->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                @endif

            </div>

            {{-- Loading --}}
            <div id="ajax-loading"
                style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;z-index:2000;background:rgba(255,255,255,0.5);align-items:center;justify-content:center;">
                <div class="spinner-border text-primary" role="status"></div>
            </div>

            {{-- Thông báo Toast --}}
            <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 2000">
                <div id="success-toast" class="toast align-items-center text-bg-success border-0" role="alert">
                    <div class="d-flex">
                        <div class="toast-body"></div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto"
                            data-bs-dismiss="toast"></button>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="scheduleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="scheduleModalLabel">Tạo lịch học</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="card-body">
                                <h4 class="mb-4">
                                    Tạo lịch học cho lớp: <span class="text-primary" id="class-name"></span>
                                    <br>
                                    <small>Khóa học: <span class="text-success" id="course-name"></span></small>
                                </h4>
                                <form id="schedule-create-form" method="POST"
                                    action="{{ route('admin.schedules.store') }}">
                                    @csrf
                                    <input type="hidden" name="class_id" id="class-id">

                                    <!-- Step 1: Chọn thứ -->
                                    <div id="step-1">
                                        <h5>Bước 1: Chọn các thứ trong tuần</h5>
                                        <div class="mb-3">
                                            <div class="d-flex gap-2 flex-wrap" id="weekday-group">
                                                @foreach (['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'] as $i => $thu)
                                                    <label class="btn btn-outline-primary mb-2">
                                                        <input type="checkbox" name="weekdays[]" value="{{ $i }}"
                                                            autocomplete="off"> {{ $thu }}
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="teacher_id" class="form-label">Chọn giáo viên</label>
                                            <select name="teacher_id" id="teacher_id" class="form-select" required>
                                                <option value="">-- Chọn giáo viên --</option>
                                                <!-- Teachers will be populated dynamically -->
                                            </select>
                                        </div>
                                        <button type="button" class="btn btn-primary" id="to-step-2">Tiếp tục</button>
                                    </div>

                                    <!-- Step 2: Chọn ngày bắt đầu -->
                                    <div id="step-2" style="display:none;">
                                        <h5>Bước 2: Chọn ngày bắt đầu</h5>
                                        <div class="mb-2">
                                            <strong>Giáo viên:</strong> <span id="selected-teacher-name"></span>
                                        </div>
                                        <div class="mb-3">
                                            <label>Chọn ngày bắt đầu:</label>
                                            <input type="text" id="calendar-start-date" class="form-control mb-2"
                                                placeholder="Chọn hoặc nhập ngày bắt đầu" readonly>
                                            <input type="hidden" name="start_date" id="start_date" required>
                                            <small class="text-muted">Chỉ có thể chọn các ngày phù hợp với thứ đã chọn.</small>
                                        </div>
                                        <div class="mb-3">
                                            <label>Chọn giờ học:</label>
                                            <input type="time" name="start_time" class="form-control" value="08:00"
                                                required>
                                            <input type="time" name="end_time" class="form-control mt-2" value="10:00"
                                                required>
                                        </div>
                                        <button type="button" class="btn btn-secondary" id="back-step-1">Quay lại</button>
                                        <button type="button" class="btn btn-primary" id="to-step-3">Xem trước</button>
                                    </div>

                                    <!-- Step 3: Preview -->
                                    <div id="step-3" style="display:none;">
                                        <h5>Bước 3: Xem trước lịch học</h5>
                                        <div class="mb-2">
                                            <strong>Giáo viên:</strong> <span id="selected-teacher-name-3"></span>
                                        </div>
                                        <div class="border rounded p-3 bg-light mb-3" style="max-height:300px;overflow:auto;">
                                            <div id="preview-sessions">
                                                <!-- Danh sách buổi học sẽ được render ở đây -->
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-secondary" id="back-step-2">Quay lại</button>
                                        <button type="submit" class="btn btn-success">Xác nhận</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        {{-- <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        </div> --}}
                    </div>
                </div>
            </div>

        @endsection

        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
            <script>
                $(document).ready(function() {
                    // Hiệu ứng loading
                    function showLoading() {
                        $('#ajax-loading').show();
                    }

                    function hideLoading() {
                        $('#ajax-loading').hide();
                    }

                    // Toast Bootstrap
                    function showSuccessToast(msg) {
                        $('#success-toast .toast-body').text(msg);
                        var toast = new bootstrap.Toast(document.getElementById('success-toast'));
                        toast.show();
                    }

                    // Xử lý submit form tìm kiếm/bộ lọc
                    $('#filter-search-form').on('submit', function(e) {
                        e.preventDefault();
                        fetchClasses($(this).serialize());
                    });

                    // Xử lý phân trang ajax
                    $(document).on('click', '#schedules-table-wrapper .pagination a', function(e) {
                        e.preventDefault();
                        let url = $(this).attr('href');
                        let params = url.split('?')[1];
                        fetchClasses(params);
                    });

                    function fetchClasses(params) {
                        $.ajax({
                            url: "{{ route('admin.schedules.index') }}",
                            type: 'GET',
                            data: params,
                            beforeSend: function() {
                                $('#schedules-table-wrapper').css('opacity', '0.5');
                            },
                            success: function(data) {
                                // Render lại phần bảng và phân trang
                                // let html = $(data).find('#classes-table-wrapper').html();
                                $('#schedules-table-wrapper').html(data);
                                // console.log('Class status:', $(this).data('status'));
                                $('#schedules-table-wrapper').css('opacity', '1');
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Lỗi!',
                                    text: 'Đã xảy ra lỗi, vui lòng thử lại!',
                                    confirmButtonText: 'Đóng'
                                });
                                $('#schedules-table-wrapper').css('opacity', '1');
                            }
                        });
                    }

                    // Xử lý nút xóa lọc
                    $('#reset-filter-btn').on('click', function() {
                        $('#filter-search-form')[0].reset();
                        fetchClasses('');
                    });


                    // Xử lý mở modal và lấy dữ liệu lớp
                    $(document).on('click', '.create-schedule-btn', function(e) {
                        e.preventDefault();
                        let classId = $(this).data('class-id');
                        let form = $('#schedule-create-form');

                        // Set form action and class_id
                        // form.attr('action', "{{ url('admin/schedules/store') }}/" + classId);
                        form.find('#class-id').val(classId);

                        // Check lỗi
                        // console.log("{{ url('admin/classes') }}/" + classId);
                        // if (!classId) {
                        //     console.error("classId is undefined or empty");
                        //     return;
                        // }

                        // Fetch class data
                        $.ajax({
                            url: "{{ url('admin/classes') }}/" + classId + "/data",
                            type: 'GET',
                            // beforeSend: showLoading,
                            success: function(data) {
                                hideLoading();
                                // Populate class and course names
                                $('#class-name').text(data.class.name);
                                $('#course-name').text(data.course.name);

                                // Lưu số buổi học vào biến toàn cục
                                totalSessions = data.course.total_sessions ? parseInt(data.course
                                    .total_sessions) : 30;

                                // Populate teachers
                                let teacherOptions = '<option value="">-- Chọn giáo viên --</option>';
                                data.teachers.forEach(function(teacher) {
                                    teacherOptions +=
                                        `<option value="${teacher.id}">${teacher.name}</option>`;
                                });
                                form.find('#teacher_id').html(teacherOptions);

                                // Initialize teacher name
                                form.find('#teacher_id').trigger('change');

                                // Show modal
                                $('#scheduleModal').modal('show');
                            },
                            error: function(xhr, status, error) {
                                hideLoading();
                                console.log("Status: ", status);
                                console.log("Error: ", error);
                                console.log("Response: ", xhr.responseText);
                                Swal.fire('Lỗi', 'Không thể tải dữ liệu lớp học, vui lòng thử lại! ' +
                                    xhr.status, 'error');
                            }
                        });
                    });
                    // Chuyển bước 1 -> 2
                    $('#schedule-create-form').on('click', '#to-step-2', function() {
                        let form = $('#schedule-create-form');
                        let totalSessions = 30; // mặc định
                        let weekdays = form.find('input[name="weekdays[]"]:checked').map(function() {
                            return parseInt(this.value);
                        }).get();
                        let teacherId = form.find('#teacher_id').val();

                        if (weekdays.length === 0) {
                            Swal.fire('Vui lòng chọn ít nhất một thứ!');
                            return;
                        }
                        if (!teacherId) {
                            Swal.fire('Vui lòng chọn giáo viên!');
                            return;
                        }

                        form.find('#step-1').hide();
                        form.find('#step-2').show();

                        // Hiển thị tên giáo viên ở bước 2
                        let teacherName = form.find('#teacher_id option:selected').text();
                        $('#selected-teacher-name').text(teacherName);

                        // Sinh danh sách ngày hợp lệ
                        let possibleDates = [];
                        let today = new Date();
                        let date = new Date(today);
                        while (possibleDates.length < totalSessions) {
                            let weekday = date.getDay();
                            if (weekdays.includes(weekday)) {
                                possibleDates.push(date.toISOString().slice(0, 10));
                            }
                            date.setDate(date.getDate() + 1);
                        }

                        // Khởi tạo Flatpickr
                        if (window.fpInstance) window.fpInstance.destroy();
                        window.fpInstance = flatpickr(form.find('#calendar-start-date')[0], {
                            dateFormat: "Y-m-d",
                            enable: possibleDates,
                            defaultDate: possibleDates[0],
                            onChange: function(selectedDates, dateStr) {
                                form.find('#start_date').val(dateStr);
                            }
                        });
                        form.find('#calendar-start-date').val(possibleDates[0]);
                        form.find('#start_date').val(possibleDates[0]);
                    });

                    // Chuyển bước 2 -> 3
                    $('#schedule-create-form').on('click', '#to-step-3', function() {
                        let form = $('#schedule-create-form');
                        // Lấy tên giáo viên
                        let teacherName = form.find('#teacher_id option:selected').text();
                        $('#selected-teacher-name-3').text(teacherName);

                        // Render preview
                        let weekdays = $('input[name="weekdays[]"]:checked').map(function() {
                            return parseInt(this.value)
                        }).get();
                        let startDate = form.find('input[name="start_date"]').val();
                        let startTime = form.find('input[name="start_time"]').val();
                        let endTime = form.find('input[name="end_time"]').val();
                        // Validate ngày và giờ
                        if (!startDate) {
                            Swal.fire('Vui lòng chọn ngày bắt đầu!');
                            return;
                        }
                        if (!startTime || !endTime) {
                            Swal.fire('Vui lòng chọn giờ học!');
                            return;
                        }
                        if (startTime >= endTime) {
                            Swal.fire('Giờ bắt đầu phải nhỏ hơn giờ kết thúc!');
                            return;
                        }
                        // Tạo preview số buổi học
                        let html = `
                                <table class="table table-bordered table-sm align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Buổi</th>
                                            <th>Thứ</th>
                                            <th>Ngày</th>
                                            <th>Giờ bắt đầu</th>
                                            <th>Giờ kết thúc</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                            `;
                        let buoi = 1;
                        let date = new Date(startDate);
                        while (buoi <= totalSessions) {
                            let weekday = date.getDay();
                            if (weekdays.includes(weekday)) {
                                let thu = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'][weekday];
                                let d = date.getDate().toString().padStart(2, '0') + '/' +
                                    (date.getMonth() + 1).toString().padStart(2, '0') + '/' +
                                    date.getFullYear();
                                // Tạo div với các span
                                html += `
                                    <tr class="schedule-item" data-id="${buoi}">
                                        <td class="text-primary fw-bold buoi">${buoi}</td>
                                        <td>
                                            <span class="thu editable" data-type="thu" style="cursor:pointer;" title="Nhấp để sửa">
                                                ${thu} <i class="fas fa-pen text-muted ms-1" style="font-size:0.85em;"></i>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="ngay editable" data-type="ngay" style="cursor:pointer;" title="Nhấp để sửa">
                                                ${d} <i class="fas fa-pen text-muted ms-1" style="font-size:0.85em;"></i>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="start-time editable" data-type="start" style="cursor:pointer;" title="Nhấp để sửa">
                                                ${startTime} <i class="fas fa-pen text-muted ms-1" style="font-size:0.85em;"></i>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="end-time editable" data-type="end" style="cursor:pointer;" title="Nhấp để sửa">
                                                ${endTime} <i class="fas fa-pen text-muted ms-1" style="font-size:0.85em;"></i>
                                            </span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-danger btn-remove-session" title="Xóa buổi này">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    `;
                                buoi++;
                            }
                            date.setDate(date.getDate() + 1);
                        }
                        $('#preview-sessions').html(html);

                        form.find('#step-2').hide();
                        form.find('#step-3').show();
                    });

                    // Quay lại bước 1
                    $('#schedule-create-form').on('click', '#back-step-1', function() {
                        let form = $('#schedule-create-form');
                        form.find('#step-2').hide();
                        form.find('#step-1').show();
                    });

                    // Quay lại bước 2 từ bước 3
                    $('#schedule-create-form').on('click', '#back-step-2', function() {
                        let form = $('#schedule-create-form');
                        form.find('#step-3').hide();
                        form.find('#step-2').show();
                    });

                    // Clean up when modal is closed
                    $(document).on('hidden.bs.modal', '#scheduleModal', function() {
                        let form = $(this).find('form')[0];
                        if (form) form.reset();
                        $(this).find('#step-1').show();
                        $(this).find('#step-2, #step-3').hide();
                        $(this).find('#weekday-group input[type="checkbox"]').closest('label').removeClass(
                            'active');
                        if (window.fpInstance) {
                            window.fpInstance.destroy();
                        }
                    });

                    // Khi nhấp vào trường muốn sửa
                    $('#preview-sessions').on('click', 'td:not(:first-child)', function() {
                        // Lấy span bên trong td
                        let $span = $(this).find('span.editable');
                        if ($span.length === 0 || $span.find('input,select').length) return;

                        let type = $span.data('type');
                        let value = $span.text().trim();
                        let input;

                        if (type === 'thu') {
                            input = $('<select class="form-select form-select-sm"></select>');
                            ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'].forEach(function(thu) {
                                input.append(
                                    `<option value="${thu}" ${thu === value ? 'selected' : ''}>${thu}</option>`
                                );
                            });
                        } else if (type === 'ngay') {
                            let parts = value.split('/');
                            let dateVal = parts.length === 3 ?
                                `${parts[2]}-${parts[1].padStart(2, '0')}-${parts[0].padStart(2, '0')}` : '';
                            input = $(
                                `<input type="date" class="form-control form-control-sm" value="${dateVal}">`);
                        } else {
                            input = $(`<input type="time" class="form-control form-control-sm" value="${value}">`);
                        }

                        $span.html(input);
                        input.focus();

                        input.on('blur keydown', function(e) {
                            if (e.type === 'keydown' && e.key === 'Enter') {
                                e.preventDefault();
                            }
                            if (e.type === 'blur' || (e.type === 'keydown' && e.key === 'Enter')) {
                                let newValue = input.val();
                                if (type === 'ngay' && newValue) {
                                    let d = new Date(newValue);
                                    newValue = d.toLocaleDateString('vi-VN');
                                }
                                if (type === 'thu') {
                                    newValue = input.find('option:selected').val();
                                }
                                if (!newValue) {
                                    $span.html(
                                        `${value} <i class="fas fa-pen text-muted ms-1" style="font-size:0.85em;"></i>`
                                    );
                                    Swal.fire('Lỗi', 'Vui lòng nhập giá trị hợp lệ!', 'error');
                                    return;
                                }
                                $span.html(
                                    `${newValue} <i class="fas fa-pen text-muted ms-1" style="font-size:0.85em;"></i>`
                                );
                            }
                        });
                    });
                });

                $('#preview-sessions').on('click', '.btn-remove-session', function() {
                    $(this).closest('tr.schedule-item').remove();
                    updateSessionOrder();
                });

                function updateSessionOrder() {
                    let $rows = $('#preview-sessions tr.schedule-item');
                    if ($rows.length === 0) {
                        $('#preview-sessions tbody').html(
                            `<tr><td colspan="6" class="text-center text-muted">Không còn buổi học nào</td></tr>`
                        );
                    } else {
                        $rows.each(function(index) {
                            $(this).find('.buoi').text(index + 1);
                        });
                    }
                }

                $('#preview-sessions').on('click', '.btn-remove-session', function() {
                    $(this).closest('tr.schedule-item').remove();
                    updateSessionOrder();
                });

                // Xử lý submit form tạo lịch học
                $('#schedule-create-form').on('submit', function(e) {
                    e.preventDefault();

                    // Lấy dữ liệu form cơ bản
                    let formData = $(this).serializeArray();
                    let sessions = [];
                    let url = $(this).attr('action'); // Lấy action từ form

                    // Log để kiểm tra
                    console.log('URL gửi yêu cầu:', url);
                    console.log('Dữ liệu gửi đi:', formData);
                    console.log('Dữ liệu dạng object:', $(this).serializeArray());

                    // Lặp qua từng buổi học trong preview
                    $('#preview-sessions tr.schedule-item').each(function() {
                        let buoi = $(this).find('.buoi').text().trim();
                        let thu = $(this).find('.thu').text().trim();
                        let ngay = $(this).find('.ngay').text().trim();
                        let start_time = $(this).find('.start-time').text().trim();
                        let end_time = $(this).find('.end-time').text().trim();

                        // Kiểm tra dữ liệu
                        if (!buoi || !thu || !ngay || !start_time || !end_time) {
                            Swal.fire('Lỗi', 'Dữ liệu buổi học không đầy đủ!', 'error');
                            return false;
                        }
                        if (!/^[0-2][0-9]:[0-5][0-9]$/.test(start_time) || !/^[0-2][0-9]:[0-5][0-9]$/.test(
                                end_time)) {
                            Swal.fire('Lỗi', 'Giờ bắt đầu hoặc kết thúc không hợp lệ!', 'error');
                            return false;
                        }
                        if (!/^\d{1,2}\/\d{1,2}\/\d{4}$/.test(ngay)) {
                            Swal.fire('Lỗi', 'Ngày không hợp lệ!', 'error');
                            return false;
                        }

                        sessions.push({
                            buoi,
                            thu,
                            ngay,
                            start_time,
                            end_time
                        });
                    });

                    // Nếu không còn buổi nào thì báo lỗi
                    if (sessions.length === 0) {
                        Swal.fire('Lỗi', 'Bạn phải có ít nhất 1 buổi học!', 'error');
                        return;
                    }

                    // Gộp dữ liệu gửi lên server
                    let data = {};
                    formData.forEach(field => {
                        data[field.name] = field.value;
                    });
                    data.sessions = sessions;

                    // Log dữ liệu ra console để kiểm tra
                    console.log('Dữ liệu gửi lên:', data);

                    // Gửi AJAX
                    $.ajax({
                        url: $(this).attr('action'),
                        type: 'POST',
                        data: JSON.stringify(data),
                        contentType: 'application/json',
                        beforeSend: function() {
                            console.log('Đang gửi yêu cầu tới:', url);
                        },
                        success: function(response) {
                            $('#scheduleModal').modal('hide');
                            $('#schedule-create-form')[0].reset();
                            Swal.fire('Thành công', 'Lịch học đã được thêm thành công!', 'success');
                            fetchClasses('');
                        },
                        error: function(xhr, status, error) {
                            let msg = `Đã xảy ra lỗi, mã lỗi: ${xhr.status}`;
                            if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.message) {
                                msg = xhr.responseJSON.message;
                            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                msg = xhr.responseJSON.message;
                            }
                            Swal.fire('Lỗi', msg, 'error');
                        }
                    });
                });

                //Parameter
                $(document).on('click', '.btn-delete-class', function() {
                    const button = this;
                    const id = button.getAttribute('data-id');
                    Swal.fire({
                        title: 'Bạn có chắc chắn muốn xóa?',
                        text: "Hành động này không thể hoàn tác!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Xóa',
                        cancelButtonText: 'Hủy',
                        confirmButtonClass: 'btn btn-primary w-xs me-2 mt-2',
                        cancelButtonClass: 'btn btn-danger w-xs mt-2',
                        buttonsStyling: false,
                        showCloseButton: false
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            fetch('/admin/classes/' + id, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                            .getAttribute('content'),
                                        'Accept': 'application/json'
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire({
                                            title: 'Đã xóa!',
                                            text: 'Lớp học đã được xóa.',
                                            icon: 'success',
                                            confirmButtonClass: 'btn btn-primary w-xs mt-2',
                                            buttonsStyling: false
                                        }).then(() => {
                                            document.getElementById(`row-${id}`).remove();
                                        });
                                    } else {
                                        Swal.fire({
                                            title: 'Lỗi!',
                                            text: data.message || 'Có lỗi xảy ra.',
                                            icon: 'error',
                                            confirmButtonClass: 'btn btn-primary mt-2',
                                            buttonsStyling: false
                                        });
                                    }
                                })
                                .catch(error => {
                                    Swal.fire({
                                        title: 'Lỗi!',
                                        text: 'Không thể xóa lớp học.',
                                        icon: 'error',
                                        confirmButtonClass: 'btn btn-primary mt-2',
                                        buttonsStyling: false
                                    });
                                });
                        }
                    });
                });
            </script>
        @endpush
