{{-- filepath: resources/views/admin/schedules/create.blade.php --}}
@extends('admin.admin')

@section('title', 'Tạo lịch học mới')
@section('description', 'Tạo lịch học cho lớp: ' . $class->name)

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        #weekday-group label.active {
            background-color: #0d6efd;
            color: #fff;
        }

        #list-possible-dates {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 10px;
            max-height: 250px;
            overflow-y: auto;
        }

        #list-possible-dates label {
            cursor: pointer;
            border-radius: 6px;
            border: 1px solid #ddd;
            padding: 8px 12px;
            transition: background 0.2s;
        }
    </style>
@endpush

@section('content')
    <div class="page-content">
        <div class="container-xxl">
            <div class="card mx-auto" style="max-width:600px;">
                <div class="card-body">
                    <h4 class="mb-4">
                        Tạo lịch học cho lớp: <span class="text-primary">{{ $class->name }}</span>
                        <br>
                        <small>Khóa học: <span class="text-success">{{ $course->name }}</span></small>
                    </h4>
                    <form id="schedule-create-form" method="POST"
                        action="{{ route('admin.schedules.store', $class->id) }}">
                        @csrf

                        {{-- Step 1: Chọn thứ --}}
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
                                    @foreach ($teachers as $teacher)
                                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="button" class="btn btn-primary" id="to-step-2">Tiếp tục</button>
                        </div>

                        {{-- Step 2: Chọn ngày bắt đầu --}}
                        <div id="step-2" style="display:none;">
                            <h5>Bước 2: Chọn ngày bắt đầu</h5>
                            {{-- Hiển thị tên giáo viên đã chọn --}}
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
                            {{-- <div class="mb-3">
                            <label>Danh sách ngày phù hợp (chia theo tháng):</label>
                            <div id="list-possible-dates"></div>
                        </div> --}}
                            <div class="mb-3">
                                <label>Chọn giờ học:</label>
                                <input type="time" name="start_time" class="form-control" value="08:00" required>
                                <input type="time" name="end_time" class="form-control mt-2" value="10:00" required>
                            </div>
                            <button type="button" class="btn btn-secondary" id="back-step-1">Quay lại</button>
                            <button type="button" class="btn btn-primary" id="to-step-3">Xem trước</button>
                        </div>

                        {{-- Step 3: Preview --}}
                        <div id="step-3" style="display:none;">
                            <h5>Bước 3: Xem trước lịch học</h5>
                            {{-- Hiển thị tên giáo viên đã chọn --}}
                            <div class="mb-2">
                                <strong>Giáo viên:</strong> <span id="selected-teacher-name-3"></span>
                            </div>
                            <div class="border rounded p-3 bg-light mb-3" style="max-height:300px;overflow:auto;">
                                <div id="preview-sessions">
                                    {{-- Danh sách buổi học sẽ được render ở đây --}}
                                </div>
                            </div>
                            <button type="button" class="btn btn-secondary" id="back-step-2">Quay lại</button>
                            <button type="submit" class="btn btn-success">Xác nhận</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Hàm để định dạng ngày thành d/m/Y
        function formatDate(date) {
            let day = date.getDate().toString().padStart(2, '0');
            let month = (date.getMonth() + 1).toString().padStart(2, '0');
            let year = date.getFullYear();
            return `${day}/${month}/${year}`;
        }
        $(function() {
            // Chuyển bước 1 -> 2
            $('#to-step-2').click(function() {
                let weekdays = $('input[name="weekdays[]"]:checked').map(function() {
                    return parseInt(this.value)
                }).get();
                if (weekdays.length === 0) {
                    Swal.fire('Vui lòng chọn ít nhất một thứ!');
                    return;
                }
                $('#step-1').hide();
                $('#step-2').show();

                // Sinh danh sách ngày hợp lệ (30 ngày tiếp theo ứng với thứ đã chọn)
                let totalSessions = {{ $course->total_sessions ?? 30 }};
                let today = new Date();
                let possibleDates = [];
                let date = new Date(today);
                while (possibleDates.length < totalSessions) {
                    let weekday = date.getDay(); // 0: CN, 1: T2, ..., 6: T7
                    if (weekdays.includes(weekday)) {
                        possibleDates.push(formatDate(date));
                    }
                    date.setDate(date.getDate() + 1);
                }

                // Khởi tạo lại flatpickr mỗi lần vào step 2
                if (window.fpInstance) window.fpInstance.destroy();
                window.fpInstance = flatpickr("#calendar-start-date", {
                    dateFormat: "Y-m-d",
                    enable: possibleDates,
                    defaultDate: possibleDates[0],
                    onChange: function(selectedDates, dateStr) {
                        $('#start_date').val(dateStr);
                    }
                });
                // Gán giá trị mặc định cho input ẩn
                $('#calendar-start-date').val(possibleDates[0]);
                $('#start_date').val(possibleDates[0]);

                // Hiển thị danh sách ngày chia theo tháng
                let grouped = {};
                possibleDates.forEach(function(val) {
                    let month = val.slice(0, 7); // YYYY-MM
                    if (!grouped[month]) grouped[month] = [];
                    grouped[month].push(val);
                });
                let html = '';
                Object.keys(grouped).forEach(function(month) {
                    html +=
                        `<div><strong>Tháng ${month.slice(5,7)}/${month.slice(0,4)}</strong><div>`;
                    grouped[month].forEach(function(val) {
                        html += `<span class="badge bg-primary m-1">${val}</span>`;
                    });
                    html += '</div></div>';
                });
                $('#list-possible-dates').html(html);
            });

            // Khi chọn ngày, cập nhật input ẩn (nếu nhập tay)
            $(document).on('change', '#calendar-start-date', function() {
                $('#start_date').val($(this).val());
            });

            $('#back-step-1').click(function() {
                $('#step-2').hide();
                $('#step-1').show();
            });

            $('#to-step-3').click(function() {
                // Render preview
                let weekdays = $('input[name="weekdays[]"]:checked').map(function() {
                    return parseInt(this.value)
                }).get();
                let startDate = $('input[name="start_date"]').val();
                let startTime = $('input[name="start_time"]').val();
                let endTime = $('input[name="end_time"]').val();
                if (!startDate || weekdays.length === 0 || !startTime || !endTime) {
                    Swal.fire('Vui lòng chọn ngày bắt đầu và thứ!');
                    return;
                }
                // Tạo preview số buổi học
                let html = '';
                let buoi = 1;
                let date = new Date(startDate);
                while (buoi <= {{ $course->total_sessions ?? 30 }}) {
                    let weekday = date.getDay();
                    if (weekdays.includes(weekday)) {
                        let thu = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'][weekday];
                        let d = formatDate(date);
                        html += `<div>Buổi ${buoi}: ${thu} ${d} ${startTime}-${endTime}</div>`;
                        buoi++;
                    }
                    date.setDate(date.getDate() + 1);
                }
                $('#preview-sessions').html(html);
                let teacherName = $('#teacher_id').find('option:selected').text();
                $('#selected-teacher-name-3').text(teacherName);
                $('#step-2').hide();
                $('#step-3').show();
            });

            $('#back-step-2').click(function() {
                $('#step-3').hide();
                $('#step-2').show();
            });

            // Toggle active cho label khi chọn thứ
            $(document).on('change', '#weekday-group input[type="checkbox"]', function() {
                if ($(this).is(':checked')) {
                    $(this).closest('label').addClass('active');
                } else {
                    $(this).closest('label').removeClass('active');
                }
            });

            $('#teacher_id').on('change', function() {
                let name = $(this).find('option:selected').text();
                $('#selected-teacher-name').text(name);
            });
            // Gọi 1 lần khi load để hiển thị mặc định
            $('#teacher_id').trigger('change');
        });
    </script>
@endpush
