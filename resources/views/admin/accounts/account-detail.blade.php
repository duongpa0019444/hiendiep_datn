@php
    $roles = [
        'student' => 'Học sinh',
        'teacher' => 'Giáo viên',
        'admin' => 'Quản trị viên',
        'staff' => 'Nhân viên',
    ];

@endphp


@extends('admin.admin')
@section('title', 'Chi tiết ' . ($roles[request('role')] ?? (request('role') ?? 'người dùng')))
@section('description', '')
@section('content')
    <style>
        .table-container {
            max-height: 60vh;
            /* chỉnh cao tùy ý */
            overflow-y: auto;
        }

        .table-container thead th {
            position: sticky;
            top: 0;
            background: #fff;
            z-index: 1;
        }
    </style>
    <div class="page-content">
        <div class="container-fluid">
            <nav aria-label="breadcrumb p-0">
                <ol class="breadcrumb py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.account') }}">Quản lí người dùng</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.account.list', request('role')) }}">{{ $roles[request('role')] ?? request('role') }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $user->name ?? 'Chi tiết người dùng' }}</li>
                </ol>
            </nav>



            <div class="card-header d-flex justify-content-between align-items-center">

                <h4 class="card-title p-3">Thông tin chi tiết {{ $roles[request('role')] ?? request('role') }} </h4>

                <form method="GET" action="{{ route('admin.account.detail', [$user->role, $user->id]) }}">
                    <select name="status" class="form-select fw-semibold" style="max-width:300px;"
                        onchange="this.form.submit()">
                        @if ($user->role === 'student')
                            <option value="" {{ request('status') == '' ? 'selected' : '' }}>Tất cả lớp học</option>
                        @elseif ($user->role === 'teacher')
                            <option value="" {{ request('status') == '' ? 'selected' : '' }}>Tất cả lớp dạy</option>
                        @endif
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>Lớp đang hoạt
                            động</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Lớp đã hoàn
                            thành
                        </option>
                    </select>
                </form>


            </div> <!-- end card-header-->


            <div class="accordion" id="accordionRole">
                @if ($user->role === 'student')

                    <div id="classGrid" class="row g-3">
                        @forelse ($allClasses as $index => $class)
                            @php
                                $classScores = $scores[$class->id] ?? collect();
                            @endphp
                            <div class="col-12 col-md-6 col-lg-4 class-item">
                                <div class="card border-success shadow-sm">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h5 class="card-title">{{ $class->name }}</h5>
                                                <p class="card-text">Khóa học: {{ $class->course_name }}</p>
                                            </div>
                                            <button class="btn btn-sm btn-outline-primary mt-2" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#scoresCollapse{{ $index }}">
                                                Xem điểm
                                            </button>
                                        </div>
                                        <div class="collapse mt-3" id="scoresCollapse{{ $index }}">
                                            @if ($classScores->count())
                                                <table class="table table-bordered table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>Loại điểm</th>
                                                            <th>Điểm</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($classScores as $score)
                                                            <tr>
                                                                <td>{{ $score->score_type }}</td>
                                                                <td>{{ $score->score }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @else
                                                <p class="text-muted">Chưa có điểm</p>
                                            @endif
                                        </div>
                                    </div>
                                    <span
                                        class="position-absolute top-0 start-50 translate-middle badge rounded-pill
                                                bg-{{ $class->status === 'in_progress' ? 'warning' : 'success' }}">
                                        {{ $class->status === 'in_progress' ? 'Đang học' : 'Hoàn thành' }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted">Không có lớp nào</p>
                        @endforelse
                    </div>

                    <div class="card-footer">
                        <nav aria-label="Page navigation">
                            {!! $allClasses->withQueryString()->links('pagination::bootstrap-5') !!}
                        </nav>
                    </div>
                @elseif ($user->role === 'teacher')
                    <div id="classGrid" class="row g-3">
                        @forelse ($allClasses as $class)
                            @php
                                $done = (int) ($class->number_of_sessions ?? 0);
                                $total = (int) ($class->total_sessions ?? 0);
                                $percent = $total > 0 ? (int) round(($done / max($total, 1)) * 100) : 0;
                                $studentCount = (int) ($countStudent[$class->id] ?? 0);
                            @endphp

                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="card border-success shadow-sm h-100 position-relative">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div>
                                            <div class="d-flex justify-content-between">
                                                <h5 class="card-title d-flex align-items-center gap-2 mb-1">
                                                    <iconify-icon icon="ph:chalkboard-teacher"
                                                        width="20"></iconify-icon>
                                                    {{ $class->name }}
                                                </h5>
                                                <p class="mb-1 text-nowrap">
                                                    <iconify-icon icon="mdi:book-open-page-variant" width="18"
                                                        class="me-1"></iconify-icon>
                                                    Khóa: <strong>{{ $class->course_name }}</strong>
                                                </p>
                                            </div>
                                            <div class="d-flex justify-content-between flex-wrap">
                                                <p class="mb-1">
                                                    <iconify-icon icon="mdi:account-group-outline" width="18"
                                                        class="me-1"></iconify-icon>
                                                    Sĩ số: <strong>{{ $studentCount }}</strong>
                                                </p>
                                                <p class="mb-1 text-nowrap">
                                                    <iconify-icon icon="mdi:calendar-clock" width="18"
                                                        class="me-1"></iconify-icon>
                                                    Số buổi: <strong>{{ $done }} / {{ $total }}</strong>
                                                </p>
                                            </div>
                                        </div>

                                        <div>
                                            <div class="progress" style="height:8px;">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                    style="width: {{ $percent }}%"
                                                    aria-valuenow="{{ $percent }}" aria-valuemin="0"
                                                    aria-valuemax="100"></div>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mt-2">
                                                <small class="text-muted">{{ $percent }}% hoàn thành</small>
                                                <button type="button"
                                                    class="btn btn-outline-success btn-sm open-schedule-btn"
                                                    data-bs-toggle="modal" data-bs-target="#scheduleModal"
                                                    data-class-id="{{ $class->id }}"
                                                    data-class-teacher="{{ $schedules->first()->teacher_id }}"
                                                    data-class-name="{{ $class->name }}">
                                                    <i class="icofont-calendar me-1"></i> Xem lịch dạy
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <span
                                        class="position-absolute top-0 start-50 translate-middle badge rounded-pill {{ $class->status === 'in_progress' ? 'bg-warning text-dark' : 'bg-success' }}">
                                        {{ $class->status === 'in_progress' ? 'Đang dạy' : 'Đã dạy' }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-light border">Không có lớp nào.</div>
                            </div>
                        @endforelse
                    </div>

                    <div class="card-footer mt-2">
                        <nav aria-label="Page navigation">
                            {!! $allClasses->withQueryString()->links('pagination::bootstrap-5') !!}
                        </nav>
                    </div>




                    <!-- modal lịch học -->
                    <div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="scheduleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 id="scheduleModalLabel" class="modal-title d-flex align-items-center gap-2">
                                        <i class="icofont-calendar"></i> Lịch dạy giáo viên
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Đóng"></button>
                                </div>
                                <div class="modal-body">
                                    <div id="teacher-schedule-container" class="text-muted text-center"></div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>



            @endif
        </div>

    </div>
    <!-- end row -->
    <!-- End Container Fluid -->
    <!-- ========== Footer Start ========== -->
    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-center">
                    <script>
                        document.write(new Date().getFullYear())
                    </script> &copy; DỰ ÁN TỐT NGHIỆP CAO ĐẲNG FPT THANH HÓA<iconify-icon
                        icon="iconamoon:heart-duotone" class="fs-18 align-middle text-danger"></iconify-icon> <a
                        href="#" class="fw-bold footer-text" target="_blank">NHÓM 4</a>
                </div>
            </div>
        </div>
    </footer>

    </div>


    <script>
        $('#scheduleModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var classId = button.data('class-id');
            var teacherId = button.data('class-teacher');
            var className = button.data('class-name');
            // console.log(classId, teacherId);

            // Chuyển thứ trong tuần sang tiếng việt
            const daysMap = {
                Mon: "Thứ 2",
                Tue: "Thứ 3",
                Wed: "Thứ 4",
                Thu: "Thứ 5",
                Fri: "Thứ 6",
                Sat: "Thứ 7",
                Sun: "Chủ nhật"
            };

            var modal = $(this);
            modal.find('.modal-title').text('Lịch dạy');

            // Gửi yêu cầu AJAX để lấy lịch dạy
            $.ajax({
                url: '/admin/account/schedules/' + teacherId + '/' + classId,

                type: 'GET',
                data: {
                    class_id: classId,
                    teacher_id: teacherId,
                },
                success: function(response) {
                    // console.log(response.schedules);
                    if (response.success == true) {
                        var scheduleHtml =
                            '<div class="table-container">' +
                            '<table class="table table-bordered">' +
                            '<thead><tr>' +
                            '<th>Thứ trong tuần</th>' +
                            '<th>Ngày</th>' +
                            '<th>Giờ bắt đầu</th>' +
                            '<th>Giờ kết thúc</th>' +
                            '<th>Giáo viên hỗ trợ</th>' +
                            '<th>Trạng thái</th>' +
                            '</tr></thead><tbody>';

                        response.schedules.forEach(function(schedule) {
                            scheduleHtml += '<tr><td>' + (daysMap[schedule.day_of_week] ||
                                    schedule.day_of_week) + '</td><td>' + schedule.date +
                                '</td><td>' + schedule.start_time + '</td><td>' +
                                schedule.end_time + '</td><td>' + (schedule.support_teacher ?
                                    schedule.support_teacher : 'Không') + '</td><td>' + (
                                    schedule.status === 1 ? 'Đã dạy' : 'Chưa dạy') +
                                '</td></tr>';
                        });

                        scheduleHtml += '</tbody></table></div>';

                        $('#teacher-schedule-container').html(scheduleHtml);

                    } else {

                        $('#teacher-schedule-container').html(
                            '<p class="text-danger">Không tìm thấy lịch dạy.</p>');
                    }
                }
            });
        });
    </script>



@endsection
