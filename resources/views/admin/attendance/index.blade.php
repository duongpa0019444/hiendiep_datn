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
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEventModal">
                        <i class="fas fa-plus me-2"></i>Thêm sự kiện
                    </button>
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
            <div class="filter-section">
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
            </div>

            <!-- Calendar Legend -->
            {{-- <div class="calendar-legend">
                <div class="legend-item">
                    <div class="legend-color legend-class"></div>
                    <span>Lớp học</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color legend-attendance"></div>
                    <span>Điểm danh</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color legend-exam"></div>
                    <span>Kiểm tra</span>
                </div>
            </div> --}}

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
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventDetailTitle">Chi tiết sự kiện</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="eventDetailBody">
                    <!-- Event details will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" id="attendanceBtn" style="display: none;">Điểm
                        danh</button>
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
                    return '✅'; // Đã điểm danh đầy đủ
                case 2:
                    return '⏳'; // Điểm danh đang diễn ra
                case 3:
                    return '⚠️'; // Điểm danh không đầy đủ
                default:
                    return '⭕'; // Không xác định
            }
        }

        function initializeCalendar() {
            loadSchedules(); // Chỉ gọi hàm load dữ liệu
        }

        function loadSchedules() {
            // Gọi API 
            fetch("{{ route('admin.attendance.getSchedules') }}", {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest', // Thêm header này cho Laravel
                        // Thêm header xác thực nếu cần, ví dụ:
                        // 'Authorization': 'Bearer ' + yourToken
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        // Nếu response lỗi, thử đọc nội dung JSON trả về
                        return response.json().then(errorData => {
                            throw new Error(
                                `HTTP ${response.status} - ${response.statusText}: ${JSON.stringify(errorData)}`
                            );
                        }).catch(() => {
                            // Nếu response không phải JSON (ví dụ lỗi 500)
                            throw new Error(`HTTP ${response.status} - ${response.statusText}`);
                        });
                    }
                    return response.json();
                })
                .then(schedules => {
                    // Khởi tạo FullCalendar với dữ liệu từ API
                    const calendarEl = document.getElementById('calendar');
                    calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'dayGridMonth',
                        locale: 'vi',
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
                        events: schedules, // Dữ liệu từ API
                        eventClick: function(info) {
                            showEventDetail(info.event);
                        },
                        dateClick: function(info) {
                            console.log('Clicked on: ' + info.dateStr);
                            // Có thể thêm logic tạo sự kiện mới ở đây
                        },
                        eventDidMount: function(info) {
                            // - phân loại event
                            const eventType = info.event.extendedProps.type;
                            if (eventType) {
                                info.el.classList.add(`fc-event-${eventType}`);
                            }

                            // - đánh dấu sự kiện hôm nay
                            const today = new Date();
                            const eventStart = info.event.start;
                            if (
                                eventStart.getFullYear() === today.getFullYear() &&
                                eventStart.getMonth() === today.getMonth() &&
                                eventStart.getDate() === today.getDate()
                            ) {
                                info.el.classList.add('fc-event-current');
                            }

                            // Code mới - thêm icon điểm danh
                            let icon = getAttendanceIcon(info.event.extendedProps.status);
                            let titleEl = info.el.querySelector('.fc-event-title');

                            if (titleEl) {
                                titleEl.innerHTML = `
                                    <span class="attendance-icon">${icon}</span>
                                    <span class="event-title">${titleEl.textContent}</span>
                                `;
                            }
                        },
                        eventTimeFormat: {
                            hour: '2-digit',
                            minute: '2-digit',
                            hour12: false // Định dạng 24h
                        }
                    }); // Render lịch
                    console.log('Lịch học đã được tải thành công:', schedules);
                    calendar.render();
                })
                .catch(error => {
                    console.error('Chi tiết lỗi:', error);
                    alert('Không thể tải lịch học. Lỗi: ' + error.message);

                    // Khởi tạo calendar rỗng nếu load dữ liệu thất bại
                    initializeEmptyCalendar();
                });
        }

        function initializeEmptyCalendar() {
            const calendarEl = document.getElementById('calendar');

            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'vi',
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
                events: [], // Không có sự kiện
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
            const now = new Date();
            const eventStart = new Date(event.start);
            const eventEnd = event.end ? new Date(event.end) : new Date(event.start.getTime() + 60 * 60 *
                1000); // Giả định 1 giờ nếu không có end
            // Lấy ngày hiện tại (chỉ ngày, không giờ)
            const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
            // Lấy ngày của sự kiện (chỉ ngày, không giờ)
            const eventDate = new Date(eventStart.getFullYear(), eventStart.getMonth(), eventStart.getDate());

            // console.log('Now:', now);
            // console.log('Event Start:', eventStart);
            // console.log('Event End:', eventEnd);

            let detailHtml = `
                <div class="row">
                    <div class="col-6"><strong>Thời gian:</strong></div>
                    <div class="col-6">${event.start.toLocaleString('vi-VN')}</div>
                </div>
            `;

            if (props.teacher) {
                detailHtml += `
                    <div class="row mt-2">
                        <div class="col-6"><strong>Giáo viên:</strong></div>
                        <div class="col-6">${props.teacher}</div>
                    </div>
                `;
            }

            if (props.courses) {
                detailHtml += `
                    <div class="row mt-2">
                        <div class="col-6"><strong>Khóa học:</strong></div>
                        <div class="col-6">${props.courses}</div>
                    </div>
                `;
            }

            if (props.class) {
                detailHtml += `
                    <div class="row mt-2">
                        <div class="col-6"><strong>Lớp học:</strong></div>
                        <div class="col-6">${props.class}</div>
                    </div>
                `;
            }

            // if (props.room) {
            //     detailHtml += `
        //         <div class="row mt-2">
        //             <div class="col-6"><strong>Phòng học:</strong></div>
        //             <div class="col-6">${props.room}</div>
        //         </div>
        //     `;
            // }

            if (props.students) {
                detailHtml += `
                    <div class="row mt-2">
                        <div class="col-6"><strong>Sĩ số:</strong></div>
                        <div class="col-6">${props.students} học sinh</div>
                    </div>
                `;
            }

            if (props.attended !== undefined && props.students) {
                const attendanceRate = ((props.attended / props.students) * 100).toFixed(1);
                detailHtml += `
                    <div class="row mt-2">
                        <div class="col-6"><strong>Điểm danh:</strong></div>
                        <div class="col-6">${props.attended}/${props.students} (${attendanceRate}%)</div>
                    </div>
                `;
            }

            if (props.examType) {
                detailHtml += `
                    <div class="row mt-2">
                        <div class="col-6"><strong>Loại kiểm tra:</strong></div>
                        <div class="col-6">${props.examType}</div>
                    </div>
                `;
            }

            $('#eventDetailTitle').text(event.title);
            $('#eventDetailBody').html(detailHtml);

            // console.log(props.scheduleId || 'Ngu');

            // Hiển thị nút điểm danh nếu sự kiện thuộc ngày hôm nay
            if (today.getTime() === eventDate.getTime() && props.students && props.students > 0 && props.status === 0) {
                $('#attendanceBtn').show().off('click').on('click', function() {
                    window.location.href = `/admin/attendance/schedules/${props.scheduleId || ''}`;
                });
            } else {
                $('#attendanceBtn').hide();
            }

            $('#eventDetailModal').modal('show');
        }

        function filterEvents() {
            const teacher = $('#teacherFilter').val();
            const subject = $('#subjectFilter').val();
            const classId = $('#classFilter').val();
            const eventType = $('#eventTypeFilter').val();

            // In thực tế, bạn sẽ gửi AJAX request để lấy dữ liệu đã lọc
            // Ở đây chỉ là demo filter
            let filteredEvents = loadSchedules();

            if (teacher) {
                filteredEvents = filteredEvents.filter(event =>
                    event.extendedProps.teacher === getTeacherName(teacher)
                );
            }

            if (eventType) {
                filteredEvents = filteredEvents.filter(event =>
                    event.extendedProps.type === eventType
                );
            }

            // Refresh calendar with filtered events
            calendar.removeAllEvents();
            calendar.addEventSource(filteredEvents);
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

            // Validate form
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Create new event object
            const newEvent = {
                title: formData.get('title'),
                start: formData.get('start_date') + 'T' + formData.get('start_time'),
                extendedProps: {
                    type: formData.get('type'),
                    description: formData.get('description')
                }
            };

            // Add to calendar
            calendar.addEvent(newEvent);

            // Close modal and reset form
            $('#addEventModal').modal('hide');
            form.reset();

            // In thực tế, bạn sẽ gửi AJAX request để lưu vào database
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
