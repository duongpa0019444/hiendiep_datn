@extends('admin.admin')

@section('title', 'Quản lý điểm danh')
@section('description', 'Quản lý điểm danh cho các lớp học')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- FullCalendar CSS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />
    <style>
        .fc-toolbar-title {
            font-size: 1.25rem !important;
            font-weight: 600;
        }

        .fc-button {
            background-color: #6c5ce7 !important;
            border-color: #6c5ce7 !important;
            color: white !important;
        }

        .fc-button:hover {
            background-color: #5a4fcf !important;
            border-color: #5a4fcf !important;
        }

        .fc-button:disabled {
            background-color: #e9ecef !important;
            border-color: #dee2e6 !important;
            color: #6c757d !important;
        }

        .fc-event {
            border-radius: 4px;
            padding: 2px 6px;
            font-size: 12px;
        }

        .fc-event-class {
            background-color: #28a745 !important;
            border-color: #28a745 !important;
            color: #fff !important;
        }

        .fc-event-attendance {
            background-color: #17a2b8 !important;
            border-color: #17a2b8 !important;
            color: #fff !important;
        }

        .fc-event-exam {
            background-color: #dc3545 !important;
            border-color: #dc3545 !important;
            color: #fff !important;
        }

        .fc-daygrid-day-number {
            color: #495057;
            font-weight: 500;
        }

        .fc-day-today {
            background-color: rgba(108, 92, 231, 0.1) !important;
        }

        .calendar-legend {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .legend-color {
            width: 16px;
            height: 16px;
            border-radius: 3px;
        }

        .legend-class {
            background-color: #28a745;
        }

        .legend-attendance {
            background-color: #17a2b8;
        }

        .legend-exam {
            background-color: #dc3545;
        }

        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .calendar-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #6c5ce7;
        }

        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
        }

        /* Sự kiện quá khứ */
        .fc-event-past {
            background-color: #bdc3c7;
            /* Xám nhạt */
            border-color: #bdc3c7;
            opacity: 0.7;
            text-decoration: line-through;
        }

        /* Sự kiện hiện tại */
        .fc-event-current {
            background-color: #f39c12 !important;
            /* Cam để nổi bật */
            border-color: #f39c12;
            border-width: 2px;
            animation: pulse 1.5s infinite;
        }

        /* Sự kiện tương lai */
        .fc-event-future {
            background-color: #2ecc71;
            /* Xanh lá nhạt */
            border-color: #2ecc71;
            opacity: 0.9;
        }

        /* Hiệu ứng nhấp nháy cho sự kiện hiện tại */
        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        /* Nếu muốn màu sắc nổi bật hơn khi là sự kiện hiện tại */
        .fc-event-class.fc-event-current {
            background-color: #27ae60 !important;
        }

        .fc-event-attendance.fc-event-current {
            background-color: #16a085 !important;
        }

        .fc-event-exam.fc-event-current {
            background-color: #c0392b !important;
        }

        /* CSS cho bảng lịch học */
        .table-row-clickable {
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .table-row-clickable:hover {
            background-color: #f8f9fa !important;
        }

        .table-row-clickable:active {
            background-color: #e9ecef !important;
        }

        /* CSS cho modal */
        .modal-lg {
            max-width: 900px;
        }

        .badge {
            font-size: 0.75rem;
        }

        .table th {
            background-color: #f8f9fa;
            border-top: none;
            font-weight: 600;
        }

        .table td {
            vertical-align: middle;
        }

        /* CSS để ẩn thời gian trong calendar */
        .fc-event-time {
            display: none !important;
        }

        .fc-event-title {
            font-weight: 500;
            font-size: 0.9rem;
        }

        .attendance-icon {
            margin-right: 4px;
            font-size: 0.8rem;
        }

        .fc-event-main-content {
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
    </style>
@endpush

@section('content')
    <div class="page-content">
        <div class="container-xxl">
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="mb-1">Quản lý điểm danh</h3>
                    <p class="text-muted mb-0">Lịch học và điểm danh theo thời gian</p>
                </div>
                <div>
                    {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEventModal">
                        <i class="fas fa-plus me-2"></i>Thêm sự kiện
                    </button> --}}
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="stats-cards">
                <div class="stat-card">
                    <div class="stat-number">{{ $todayClassCount }}</div>
                    <div class="stat-label">Lớp học hôm nay</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $attendanceRate }}%</div>
                    <div class="stat-label">Tỷ lệ điểm danh</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $classesNeedAttendance }}</div>
                    <div class="stat-label">Lớp cần điểm danh</div>
                </div>
                {{-- <div class="stat-card">
                    <div class="stat-number">3</div>
                    <div class="stat-label">Bài kiểm tra</div>
                </div> --}}
            </div>

            <!-- Filter Section -->
            {{-- <div class="filter-section">
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label">Giáo viên</label>
                        <select class="form-select" id="teacherFilter">
                            <option value="">Tất cả giáo viên</option>
                            <option value="1">Nguyễn Văn A</option>
                            <option value="2">Trần Thị B</option>
                            <option value="3">Lê Văn C</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Môn học</label>
                        <select class="form-select" id="subjectFilter">
                            <option value="">Tất cả môn học</option>
                            <option value="1">Toán học</option>
                            <option value="2">Văn học</option>
                            <option value="3">Tiếng Anh</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Lớp học</label>
                        <select class="form-select" id="classFilter">
                            <option value="">Tất cả lớp</option>
                            <option value="1">Lớp 10A1</option>
                            <option value="2">Lớp 10A2</option>
                            <option value="3">Lớp 11B1</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Loại sự kiện</label>
                        <select class="form-select" id="eventTypeFilter">
                            <option value="">Tất cả</option>
                            <option value="class">Lớp học</option>
                            <option value="attendance">Điểm danh</option>
                            <option value="exam">Kiểm tra</option>
                        </select>
                    </div>
                </div>
            </div> --}}

            <!-- Calendar Legend -->
            <div class="calendar-legend">
                <div class="legend-item">
                    ✅
                    <span>Đã hoàn thành</span>
                </div>
                <div class="legend-item">
                    ❌
                    <span>Chưa điểm danh</span>
                </div>
                <div class="legend-item">
                    ⏳
                    <span>Đang tiến hành</span>
                </div>
            </div>

            <!-- Calendar Container -->
            <div class="calendar-container">
                <div id="calendar"></div>
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
        </div>
    </div>

    <!-- Add Event Modal -->
    <div class="modal fade" id="addEventModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm sự kiện mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addEventForm">
                        <div class="mb-3">
                            <label class="form-label">Tiêu đề</label>
                            <input type="text" class="form-control" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Loại sự kiện</label>
                            <select class="form-select" name="type" required>
                                <option value="class">Lớp học</option>
                                <option value="attendance">Điểm danh</option>
                                <option value="exam">Kiểm tra</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Ngày bắt đầu</label>
                                <input type="date" class="form-control" name="start_date" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Thời gian</label>
                                <input type="time" class="form-control" name="start_time" required>
                            </div>
                        </div>
                        <div class="mb-3 mt-3">
                            <label class="form-label">Mô tả</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary" onclick="addEvent()">Thêm sự kiện</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Detail Modal -->
    <div class="modal fade" id="eventDetailModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventDetailTitle">Lịch học trong ngày</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="eventDetailBody">
                    <!-- Class info and schedules list will be loaded here -->
                    <div id="classInfo" class="mb-4">
                        <!-- Class information will be loaded here -->
                    </div>
                    <div id="schedulesList">
                        <!-- Schedules list will be loaded here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- FullCalendar JS -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    <script>
        let calendar;

        $(document).ready(function() {
            initializeCalendar();

            // Filter event listeners
            $('#teacherFilter, #subjectFilter, #classFilter, #eventTypeFilter').on('change', function() {
                filterEvents();
            });
        });

        // Hàm lấy icon theo trạng thái
        function getAttendanceIcon(status) {
            switch (status) {
                case 0:
                    return '❌'; // Chưa điểm danh
                case 1:
                    return '✅'; // Đã hoàn thành
                case 2:
                    return '⏳'; // Đang tiến hành
                default:
                    return '⭕'; // Không xác định
            }
        }

        function initializeCalendar() {
            loadSchedules(); // Gọi hàm load dữ liệu
        }

        function loadSchedules() {
            fetch("{{ route('admin.attendance.getSchedules') }}", {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(errorData => {
                            throw new Error(
                                `HTTP ${response.status} - ${response.statusText}: ${JSON.stringify(errorData)}`
                                );
                        }).catch(() => {
                            throw new Error(`HTTP ${response.status} - ${response.statusText}`);
                        });
                    }
                    return response.json();
                })
                .then(schedules => {
                    // Log dữ liệu thô từ API để debug
                    console.log('Dữ liệu thô từ API:', schedules);

                    // Chuẩn hóa và lọc dữ liệu sự kiện
                    const processedEvents = schedules.map(event => {
                        // Nếu API đã trả về đúng ngày (YYYY-MM-DD), không cần chỉnh lại múi giờ
                        // Nếu event.start là '2025-04-29', giữ nguyên
                        let isoDate = event.start;
                        // Nếu event.start là dạng ISO có thời gian, chỉ lấy phần ngày
                        if (typeof isoDate === 'string' && isoDate.includes('T')) {
                            isoDate = isoDate.split('T')[0];
                        }
                        return {
                            ...event,
                            start: isoDate, // Chỉ lấy ngày
                            allDay: true,
                            extendedProps: {
                                ...event.extendedProps,
                                originalStart: event.start
                            }
                        };
                    });

                    // Loại bỏ các sự kiện trùng lặp dựa trên ngày và tiêu đề
                    const uniqueEvents = [];
                    const eventKeys = new Set();
                    processedEvents.forEach(event => {
                        const key = `${event.start}_${event.title}_${event.extendedProps.classId || ''}`;
                        if (!eventKeys.has(key)) {
                            eventKeys.add(key);
                            uniqueEvents.push(event);
                        }
                    });

                    // Log dữ liệu đã xử lý để debug
                    console.log('Dữ liệu sự kiện đã xử lý:', uniqueEvents);

                    // Khởi tạo FullCalendar
                    const calendarEl = document.getElementById('calendar');
                    calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'dayGridMonth',
                        locale: 'vi',
                        timeZone: 'Asia/Ho_Chi_Minh',
                        headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,timeGridWeek,timeGridDay'
                        },
                        buttonText: {
                            today: 'Hôm nay',
                            month: 'Tháng',
                            week: 'Tuần',
                            day: 'Ngày'
                        },
                        height: 'auto',
                        events: uniqueEvents,
                        eventClick: function(info) {
                            showEventDetail(info.event);
                        },
                        dateClick: function(info) {
                            console.log('Clicked on: ' + info.dateStr);
                        },
                        eventTimeFormat: {
                            hour: '2-digit',
                            minute: '2-digit',
                            hour12: false
                        },
                        eventDisplay: 'block',
                        eventContent: function(arg) {
                            const icon = getAttendanceIcon(arg.event.extendedProps.status);
                            return {
                                html: `
                                        <div class="fc-event-main-content">
                                            <span class="attendance-icon">${icon}</span>
                                            <span class="event-title">${arg.event.title}</span>
                                        </div>
                                    `
                            };
                        },
                        eventDidMount: function(info) {
                            const eventType = info.event.extendedProps.type;
                            if (eventType) {
                                info.el.classList.add(`fc-event-${eventType}`);
                            }

                            const today = new Date();
                            const eventStart = new Date(info.event.start);
                            today.setHours(0, 0, 0, 0);
                            eventStart.setHours(0, 0, 0, 0);

                            if (eventStart < today) {
                                info.el.classList.add('fc-event-past');
                            } else if (
                                eventStart.getFullYear() === today.getFullYear() &&
                                eventStart.getMonth() === today.getMonth() &&
                                eventStart.getDate() === today.getDate()
                            ) {
                                info.el.classList.add('fc-event-current');
                            } else {
                                info.el.classList.add('fc-event-future');
                            }
                        }
                    });
                    calendar.render();
                })
                .catch(error => {
                    console.error('Chi tiết lỗi:', error);
                    alert('Không thể tải lịch học. Lỗi: ' + error.message);
                    initializeEmptyCalendar();
                });
        }

        function initializeEmptyCalendar() {
            const calendarEl = document.getElementById('calendar');
            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'vi',
                timeZone: 'Asia/Ho_Chi_Minh', // Chỉ định múi giờ
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                buttonText: {
                    today: 'Hôm nay',
                    month: 'Tháng',
                    week: 'Tuần',
                    day: 'Ngày'
                },
                height: 'auto',
                events: [],
                dateClick: function(info) {
                    console.log('Clicked on: ' + info.dateStr);
                }
            });
            calendar.render();
        }

        // Hàm tiện ích để refresh calendar
        function refreshCalendar() {
            if (calendar) {
                calendar.destroy();
            }
            loadSchedules();
        }

        // Hiển thị chi tiết sự kiện khi người dùng click vào sự kiện
        function showEventDetail(event) {
            const props = event.extendedProps || {};
            const classId = props.classId;
            const eventDate = new Date(event.start).toISOString().split('T')[0];

            if (!classId) {
                console.error('Không tìm thấy classId');
                return;
            }

            $('#eventDetailTitle').text('Đang tải...');
            $('#classInfo').html(
                '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Đang tải thông tin...</div>');
            $('#schedulesList').html('');
            $('#eventDetailModal').modal('show');

            fetch(`/admin/attendance/class/${classId}/detail`)
                .then(response => response.json())
                .then(classDetail => {
                    $('#eventDetailTitle').text(
                        `Lớp: ${classDetail.class_name} - ${new Date(eventDate).toLocaleDateString('vi-VN')}`);
                    const classInfoHtml = `
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary">Thông tin lớp</h6>
                                <p><strong>Tên lớp:</strong> ${classDetail.class_name}</p>
                                <p><strong>Môn học:</strong> ${classDetail.course_name}</p>
                                <p><strong>Sĩ số:</strong> ${classDetail.total_students} học sinh</p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-primary">Ngày học</h6>
                                <p><strong>Ngày:</strong> ${new Date(eventDate).toLocaleDateString('vi-VN')}</p>
                                <p><strong>Thứ:</strong> ${new Date(eventDate).toLocaleDateString('vi-VN', { weekday: 'long' })}</p>
                            </div>
                        </div>
                    `;
                    $('#classInfo').html(classInfoHtml);
                })
                .catch(error => {
                    console.error('Lỗi khi tải thông tin lớp:', error);
                    $('#classInfo').html('<div class="alert alert-danger">Lỗi khi tải thông tin lớp</div>');
                });

            fetch(`/admin/attendance/class/${classId}/schedules?date=${eventDate}`)
                .then(response => response.json())
                .then(schedules => {
                    console.log('Lịch học trong ngày:', schedules);
                    const daySchedules = schedules.filter(schedule => schedule.date === eventDate);
                    if (daySchedules.length === 0) {
                        $('#schedulesList').html(
                            '<div class="alert alert-info">Không có lịch học nào trong ngày này</div>');
                        return;
                    }

                    let schedulesHtml = `
                        <h6 class="text-primary mb-3">Danh sách lịch học trong ngày</h6>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Thời gian</th>
                                        <th>Giáo viên</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                    `;

                    daySchedules.forEach(schedule => {
                        const statusBadge = getScheduleStatusBadge(schedule.status);
                        const isClickable = schedule.status === 0;
                        schedulesHtml += `
                            <tr class="${isClickable ? 'table-row-clickable' : ''}" 
                                ${isClickable ? `onclick="goToAttendance(${schedule.id})"` : ''}>
                                <td>${schedule.formatted_start_time} - ${schedule.formatted_end_time}</td>
                                <td>${schedule.teacher_name}</td>
                                <td>${statusBadge}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="goToAttendance(${schedule.id})">Điểm danh</button>
                                </td>
                            </tr>
                        `;
                    });

                    schedulesHtml += `
                                </tbody>
                            </table>
                        </div>
                    `;
                    $('#schedulesList').html(schedulesHtml);
                })
                .catch(error => {
                    console.error('Lỗi khi tải danh sách lịch học:', error);
                    $('#schedulesList').html('<div class="alert alert-danger">Lỗi khi tải danh sách lịch học</div>');
                });
        }

        function goToAttendance(scheduleId) {
            window.location.href = `/admin/attendance/schedules/${scheduleId}`;
        }

        function getScheduleStatusBadge(status) {
            switch (status) {
                case 0:
                    return '<span class="badge bg-warning">Chưa điểm danh</span>';
                case 1:
                    return '<span class="badge bg-success">Đã điểm danh</span>';
                case 2:
                    return '<span class="badge bg-info">Đang diễn ra</span>';
                default:
                    return '<span class="badge bg-secondary">Không xác định</span>';
            }
        }

        function filterEvents() {
            const teacher = $('#teacherFilter').val();
            const subject = $('#subjectFilter').val();
            const classId = $('#classFilter').val();
            const eventType = $('#eventTypeFilter').val();

            fetch("{{ route('admin.attendance.getSchedules') }}", {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                })
                .then(response => response.json())
                .then(schedules => {
                    let filteredEvents = schedules;

                    if (teacher) {
                        filteredEvents = filteredEvents.filter(event => event.extendedProps.teacher === getTeacherName(
                            teacher));
                    }
                    if (subject) {
                        filteredEvents = filteredEvents.filter(event => event.extendedProps.subject === subject);
                    }
                    if (classId) {
                        filteredEvents = filteredEvents.filter(event => event.extendedProps.classId === classId);
                    }
                    if (eventType) {
                        filteredEvents = filteredEvents.filter(event => event.extendedProps.type === eventType);
                    }

                    calendar.removeAllEvents();
                    calendar.addEventSource(filteredEvents);
                });
        }

        function getTeacherName(id) {
            const teachers = {
                '1': 'Nguyễn Văn A',
                '2': 'Trần Thị B',
                '3': 'Lê Văn C'
            };
            return teachers[id] || '';
        }

        function addEvent() {
            const form = document.getElementById('addEventForm');
            const formData = new FormData(form);

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const newEvent = {
                title: formData.get('title'),
                start: formData.get('start_date') + 'T' + formData.get('start_time'),
                extendedProps: {
                    type: formData.get('type'),
                    description: formData.get('description')
                }
            };

            calendar.addEvent(newEvent);
            $('#addEventModal').modal('hide');
            form.reset();

            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: 'Sự kiện đã được thêm vào lịch',
                timer: 2000,
                showConfirmButton: false
            });
        }
    </script>
@endpush
