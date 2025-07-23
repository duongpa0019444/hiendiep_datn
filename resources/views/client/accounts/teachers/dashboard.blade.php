@extends('client.accounts.information')

@section('content-information')
    <div id="dashboard" class="content-section active mb-4">
        <h2 class="fs-5">
            <i class="icofont-user-alt-4 me-2 text-primary"></i>
            Chào mừng {{ Auth::user()->name }} quay trở lại!
        </h2>



        <!-- Notifications Section -->
        <div class="card mb-2">
            <div class="card-header">
                <i class="icofont-notification me-1 text-white"></i>Thông Báo
            </div>
            <div class="card-body">
                <ul class="list-group">

                    @forelse ($notifications as $noti)
                        <li class="list-group-item notification-item d-flex align-items-start gap-2">
                            <div class=" text-primary">
                                <i class="icofont-info-circle"></i>
                            </div>
                            <div class="notification-content">
                                <strong class="mb-1 text-primary fw-bold d-block">{{ $noti->title }}</strong>
                                <div class="mb-2">{!! $noti->content !!}</div>
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item d-flex align-items-center text-muted">
                            <i class="icofont-warning-alt me-2"></i> Không có thông báo nào.
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>




        <!-- Lịch dạy hôm nay -->
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-light d-flex justify-content-between align-items-center py-2 px-3">
                <h6 class="mb-0 fw-semibold text-white d-flex align-items-center">
                    <i class="icofont-calendar me-2"></i> Lịch dạy hôm nay
                </h6>
                <span class="badge badge-completed">{{ now()->format('d/m/Y') }}</span>
            </div>
            <div class="card-body p-3">
                <ul class="list-group list-group-flush">
                    @foreach ($todaySchedules as $todaySchedule)
                        <li class="list-group-item d-flex align-items-start py-2">

                            <div class="d-flex justify-content-between align-items-center gap-4">
                                <div class="fw-bold ">
                                    <i class="icofont-book-alt me-1 text-secondary"></i> {{ $todaySchedule->class_name }}
                                </div>
                                <div class=" mt-1">
                                    <i class="icofont-clock-time me-1"></i> Thời gian: {{ $todaySchedule->start_time }} -
                                    {{ $todaySchedule->end_time }}
                                </div>

                            </div>
                        </li>
                    @endforeach
                    @if ($todaySchedules->isEmpty())
                        <li class="list-group-item text-muted">
                            <i class="icofont-warning-alt me-2"></i> Không có lịch dạy hôm nay.
                        </li>
                    @endif

                </ul>
            </div>
        </div>

        <!-- Current Class Section -->
        <div class="row mb-2">
            <!-- Lịch dạy sắp tới -->
            <div class="col-md-6 col-sm-12 mb-2">
                <div class="card completed-class w-100">
                    <div class="card-header">
                        <i class="icofont-ui-calendar me-2 text-white"></i> Lịch dạy sắp tới
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach ($upcomingSchedules as $upcomingSchedule)
                                <li class="list-group-item">
                                    <div class="w-100 mb-1">
                                        <i class="icofont-book-mark text-secondary me-2"></i>
                                        <strong>Lớp {{ $upcomingSchedule->class_name }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            {{ $upcomingSchedule->formatted_date }}
                                        </div>
                                        <span class="badge badge-pending">
                                            <i class="icofont-clock-time me-1"></i> {{ $upcomingSchedule->start_time }} -
                                            {{ $upcomingSchedule->end_time }}
                                        </span>
                                    </div>
                                </li>
                            @endforeach


                        </ul>
                        <a href="{{ route('client.schedule') }}" class="text-primary mt-3">
                            Xem tất cả <i class="icofont-rounded-right ms-1"></i>
                        </a>

                    </div>
                </div>
            </div>

            <!-- Tổng quan -->
            <div class="col-lg-6 col-md-12 mb-4 tongquan">
                <div class="card completed-class h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="icofont-chart-bar-graph me-2 text-white"></i> Tổng quan</span>
                        <div class="btn-group btn-group-sm mt-2 mt-sm-0" role="group" aria-label="Chuyển tháng">
                            <button type="button" class="btn btn-light " id="prevMonth">
                                <i class="icofont-rounded-left"></i>
                            </button>
                            <span class="btn btn-light" id="currentMonth">{{ \Carbon\Carbon::now()->format('m/Y') }}</span>
                            <button type="button" class="btn btn-light" id="nextMonth">
                                <i class="icofont-rounded-right"></i>
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="card text-center h-100 border-0 shadow-sm border-top border-primary">
                                    <div class="card-body p-3">
                                        <i class="icofont-graduate-alt fs-4 text-primary mb-2"></i>
                                        <strong>Tổng số lớp dạy</strong>
                                        <div class="badge badge-completed fw-bold mt-1 total_classes">
                                            {{ $overview->total_classes }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="card text-center h-100 border-0 shadow-sm border-top border-success">
                                    <div class="card-body p-3">
                                        <i class="icofont-check-circled fs-4 text-success mb-2"></i>
                                        <strong>Buổi đã dạy</strong>
                                        <div class="badge badge-completed fw-bold mt-1 total_sessions">
                                            {{ $overview->total_sessions }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="card text-center h-100 border-0 shadow-sm border-top border-warning">
                                    <div class="card-body p-3">
                                        <i class="icofont-users-social fs-4 text-warning mb-2"></i>
                                        <strong>Tổng học sinh</strong>
                                        <div class="badge badge-completed fw-bold mt-1 total_students">
                                            {{ $overview->total_students }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('script')
    <!-- Moment.js (thêm trước script sử dụng moment) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

    <script>
        $(document).ready(function() {

            // Lưu tháng hiện tại từ #currentMonth (dạng m/Y)
            let currentDate = moment($('#currentMonth').text(), 'MM/YYYY');

            function updateMonthDisplay() {
                $('#currentMonth').text(currentDate.format('MM/YYYY'));
            }

            function fetchDataByMonth(month, year) {
                // Đảm bảo div có position
                $('.tongquan').css({
                    'opacity': '0.5',
                    'pointer-events': 'none' // nếu muốn không bấm được
                });
                $.ajax({
                    url: `/teacher/dashboard/overview/${month}/${year}`, // Đổi route phù hợp
                    type: 'GET',
                    success: function(response) {
                        console.log(response);
                        Toastify({
                            text: "Lấy dữ liệu Thành công. Đã cập nhật lại dữ liệu!",
                            duration: 3000,
                            gravity: "top", // "bottom" cũng được
                            position: "right", // "left", "center"
                            backgroundColor: "#28a745", // màu xanh lá thành công
                            className: "text-white rounded px-3",
                            stopOnFocus: true,

                        }).showToast();
                        // Cập nhật các giá trị thống kê
                        $('.total_classes').text(response.total_classes);
                        $('.total_sessions').text(response.total_sessions);
                        $('.total_students').text(response.total_students);
                        $('.tongquan').css({
                            'opacity': '1',
                            'pointer-events': 'auto'
                        });


                    },
                    error: function() {
                        Toastify({
                            text: "Lỗi khi lấy dữ liệu thống kê!",
                            backgroundColor: "#dc3545",
                            icon: "❌",
                            duration: 3000
                        }).showToast();
                        $('.tongquan').css({
                            'opacity': '1',
                            'pointer-events': 'auto'
                        });

                    }
                });
            }

            $('#prevMonth').click(function() {
                currentDate = currentDate.subtract(1, 'months');
                updateMonthDisplay();
                fetchDataByMonth(currentDate.format('M'), currentDate.format('YYYY'));
            });

            $('#nextMonth').click(function() {
                currentDate = currentDate.add(1, 'months');
                updateMonthDisplay();
                fetchDataByMonth(currentDate.format('M'), currentDate.format('YYYY'));
            });
        });
    </script>
@endpush
