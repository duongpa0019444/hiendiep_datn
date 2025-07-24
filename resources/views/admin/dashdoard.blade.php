@extends('admin.admin')


@section('title', 'Trang admin')
@section('description', '')
@section('content')


    <div class="page-content">

        <!-- Start Container Fluid -->
        <div class="container-fluid">
            <nav aria-label="breadcrumb p-0">
                <ol class="breadcrumb py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Trang Dashboard</a></li>
                </ol>
            </nav>
            <!-- Start here.... -->
            <div class="row mt-2">
                <div class="col-xxl-5">

                    <div class="row">

                        <h4 class="card-title mb-1">Tổng quan (Overview)</h4>

                        @foreach ($users as $user)
                            <div class="col-md-12">

                                <div class="card overflow-hidden" data-aos="fade-right" data-aos-delay="300">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="avatar-md bg-soft-primary rounded">
                                                    <iconify-icon icon="ic:baseline-people-alt"
                                                        class="avatar-title fs-32 text-primary"></iconify-icon>
                                                </div>
                                            </div> <!-- end col -->
                                            <div class="col-8 text-end">
                                                <p class="text-muted mb-0 text-truncate">Tổng Số Người Dùng</p>
                                                <h3 class="text-dark mt-1 mb-0">{{ $user->total_users }}</h3>
                                            </div> <!-- end col -->
                                        </div> <!-- end row-->
                                        <div class="d-flex align-items-center justify-content-between mt-2 flex-wrap">
                                            <div class="">
                                                <span class="text-success"> <i class="bx bxs-up-arrow fs-12"></i>
                                                    {{ $user->total_students }}</span>
                                                <span class="text-muted fs-12">Học sinh</span>
                                            </div>
                                            <div class="">
                                                <span class="text-success"> <i class="bx bxs-up-arrow fs-12"></i>
                                                    {{ $user->total_teachers }}</span>
                                                <span class="text-muted fs-12">Giáo viên</span>
                                            </div>
                                            <div class="">
                                                <span class="text-success"> <i class="bx bxs-up-arrow fs-12"></i>
                                                    {{ $user->total_staff }}</span>
                                                <span class="text-muted fs-12">Nhân viên</span>
                                            </div>

                                        </div>
                                    </div> <!-- end card body -->
                                    <div class="card-footer py-1 bg-light bg-opacity-50">
                                        <div class="d-flex align-items-center justify-content-between">

                                            <div>
                                                <span class="text-primary"> <i class="bx bxs-up-arrow fs-12"></i>
                                                    {{ $user->total_admins }}</span>
                                                <span class="text-muted fs-12">Người quản trị</span>
                                            </div>

                                            <a href="{{ route('admin.account') }}" class="text-reset fw-semibold fs-12">Xem
                                                Thêm</a>
                                        </div>
                                    </div> <!-- end card body -->
                                </div> <!-- end card -->
                            </div> <!-- end col -->
                        @endforeach


                        @foreach ($classs as $class)
                            <div class="col-md-12" data-aos="fade-right" data-aos-delay="500">
                                <div class="card overflow-hidden">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="avatar-md bg-soft-primary rounded">
                                                    <iconify-icon icon="mdi:school"
                                                        class="avatar-title fs-32 text-primary"></iconify-icon>
                                                </div>
                                            </div> <!-- end col -->
                                            <div class="col-8 text-end">
                                                <p class="text-muted mb-0 text-truncate">Tổng Số lớp học</p>
                                                <h3 class="text-dark mt-1 mb-0">{{ $class->total_classes }}</h3>
                                            </div> <!-- end col -->
                                        </div> <!-- end row-->
                                        <div class="d-flex justify-content-between mt-1">
                                            <div class="col-lg-4 col-md-6 col-sm-12">
                                                <span class="text-success"> <i class="bx bxs-up-arrow fs-12"></i>
                                                    {{ $class->total_classes_in_progress }}</span>
                                                <span class="text-muted fs-12">Đang hoạt động</span>
                                            </div>

                                            <div class="col-lg-4 col-md-6 col-sm-12">
                                                <span class="text-primary"> <i class="bx bxs-up-arrow fs-12"></i>
                                                    {{ $class->total_classes_completed }}</span>
                                                <span class="text-muted fs-12">Đã kết thúc</span>
                                            </div>

                                            <div class="col-lg-4 col-md-6 col-sm-12">
                                                <span class="text-primary"> <i class="bx bxs-up-arrow fs-12"></i>
                                                    {{ $class->total_classes_unstarted }}</span>
                                                <span class="text-muted fs-12">Chưa bắt đầu</span>
                                            </div>
                                        </div>
                                    </div> <!-- end card body -->
                                    <div class="card-footer py-1 bg-light bg-opacity-50">
                                        <div class="d-flex align-items-center justify-content-between flex-wrap">

                                            <a href="{{ route('admin.classes.index') }}"
                                                class="text-reset fw-semibold fs-12">Xem Thêm</a>
                                        </div>
                                    </div> <!-- end card body -->


                                </div> <!-- end card -->
                            </div> <!-- end col -->
                        @endforeach


                        @foreach ($countPayments as $countPayment)
                            <div class="col-md-12" data-aos="fade-right" data-aos-delay="700">
                                <div class="card overflow-hidden">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="avatar-md bg-soft-primary rounded">
                                                    <iconify-icon icon="mdi:currency-usd"
                                                        class="avatar-title fs-32 text-primary"></iconify-icon>
                                                </div>
                                            </div> <!-- end col -->
                                            <div class="col-8 text-end">
                                                <p class="text-muted mb-0 text-truncate">Tổng doanh thu học phí</p>
                                                <h3 class="text-dark mt-1 mb-0">
                                                    {{ number_format($countPayment->total_revenue, 0, ',', '.') }} VNĐ</h3>

                                            </div> <!-- end col -->
                                        </div> <!-- end row-->
                                        <div class="d-flex justify-content-between mt-1">
                                            <div class="col-lg-6 col-md-12 col-sm-12">
                                                <span class="text-success"> <i class="bx bxs-up-arrow fs-12"></i>
                                                    {{ $countPayment->total_paid_records }}</span>
                                                <span class="text-muted fs-12">Lượt đã đóng tiền</span>
                                            </div>
                                            <div class="col-lg-6 col-md-12 col-sm-12">
                                                <span class="text-primary"> <i class="bx bxs-up-arrow fs-12"></i>
                                                    {{ $countPayment->total_unpaid_records }}</span>
                                                <span class="text-muted fs-12">Lượt chưa đóng tiền</span>
                                            </div>
                                        </div>

                                    </div> <!-- end card body -->
                                    <div class="card-footer py-1 bg-light bg-opacity-50">
                                        <div class="d-flex align-items-center justify-content-between flex-wrap">

                                            <a href="{{ route('admin.course_payments') }}"
                                                class="text-reset fw-semibold fs-12">Xem Thêm</a>
                                        </div>
                                    </div> <!-- end card body -->


                                </div> <!-- end card -->
                            </div> <!-- end col -->
                        @endforeach


                    </div> <!-- end row -->
                </div> <!-- end col -->

                <div class="col-xxl-7 mt-3" data-aos="fade-up" data-aos-delay="400">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="card-title col-xl-5">Thống kê nộp tiền học</h4>
                                <form class="col-xl-5">
                                    <select class="form-control w-100" id="courseSelect" data-choices data-choices-groups
                                        data-placeholder="Select Categories" name="where">
                                        <option value="">Tất cả các khóa học</option>
                                        @foreach ($courses as $course)
                                            <option value="{{ $course->id }}">{{ $course->name }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            </div> <!-- end card-title-->

                            <div id="grouped-bar" class="apex-charts text-white"></div>

                            <div id="pagination-wrapper" class="card-footer border-top">

                            </div>
                        </div> <!-- end card body -->
                    </div> <!-- end card -->
                </div> <!-- end col -->
            </div> <!-- end row -->

            <div class="card">

                <div class="d-flex align-items-center justify-content-center gap-2 col-xxl-12 mt-2" data-aos="fade-up"
                    data-aos-delay="300">
                    <!-- Nút chuyển năm -->
                    <button type="button" class="btn btn-sm btn-outline-secondary d-flex align-items-center"
                        id="prevYear">
                        <i class="me-1">&laquo;</i> Năm trước
                    </button>

                    <!-- Hiển thị năm hiện tại -->
                    <span id="currentYear" class="px-3 py-1 bg-light rounded border fw-semibold text-primary">
                        2025
                    </span>

                    <!-- Nút chuyển năm -->
                    <button type="button" class="btn btn-sm btn-outline-secondary d-flex align-items-center"
                        id="nextYear">
                        Năm sau <i class="ms-1">&raquo;</i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-xxl-7" data-aos="fade-right" data-aos-delay="300">
                        <div class="">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h4 class="card-title">Thống kê doanh thu</h4>
                                </div> <!-- end card-title-->
                                <div dir="ltr">
                                    <div id="dash-performance-chart" class="apex-charts"></div>
                                </div>
                            </div> <!-- end card body -->
                        </div> <!-- end card -->
                    </div> <!-- end col -->
                    <div class="col-xxl-5" data-aos="fade-left" data-aos-delay="300">
                        <div class="">
                            <div class="card-body">
                                <h4 class="card-title mb-3  anchor" id="simple_donut">Doanh thu theo khóa học</h4>
                                <div dir="ltr">
                                    <div id="simple-donut" class="apex-charts"></div>
                                </div>
                            </div>
                            <!-- end card body-->

                        </div>
                    </div>
                </div>
            </div>


            <div class="card mt-2 p-2" data-aos="fade-up" data-aos-delay="300">
                <div class="table-container table-responsive">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-0 title-date-schedules">Lịch học ({{ $formattedDate }})</h4>
                        <div class="d-flex align-items-center">
                            <input type="text" id="basic-datepicker" class="form-control me-2 " placeholder="Date"
                                data-provider="flatpickr" data-date-format="d/m/Y" value="{{ $formattedDate }}">
                            <button id="todayBtn" class="btn btn-primary  today-btn"
                                data-today="{{ $formattedDate }}">Today</button>
                        </div>
                    </div>
                    <table class="table table-centered table-hover " id="scheduleTable">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">STT</th>
                                <th scope="col">Lớp học</th>
                                <th scope="col">Môn học</th>
                                <th scope="col">Giáo viên</th>
                                <th scope="col">Thời gian</th>
                                <th scope="col">Trạng thái buổi học</th>
                                <th scope="col">Trạng thái điểm danh</th>
                                <th scope="col">Hành động</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            @if ($schedules->isEmpty())
                                <tr>
                                    <td colspan="8" class="text-center text-muted fst-italic">
                                        <iconify-icon icon="mdi:calendar-remove" class="me-1 text-danger"
                                            style="vertical-align: middle;"></iconify-icon>
                                        Không có lịch học nào.
                                    </td>
                                </tr>
                            @else
                                @foreach ($schedules as $key => $schedule)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $schedule->class_name }}</td>
                                        <td>{{ $schedule->course_name }}</td>
                                        <td>{{ $schedule->teacher_name }}</td>
                                        <td>{{ $schedule->time_range }}</td>
                                        <td class="text-center">
                                            <span
                                                class="badge p-1
                                            @if ($schedule->status_label === 'Chưa học') bg-warning
                                            @elseif ($schedule->status_label === 'Đang học') bg-success
                                            @elseif ($schedule->status_label === 'Đã kết thúc') bg-secondary
                                            @else bg-light text-dark @endif
                                        ">
                                                {{ $schedule->status_label }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="badge p-1
                                            @if ($schedule->schedule_status === 0) bg-info
                                            @elseif ($schedule->schedule_status === 1) bg-success
                                            @else bg-secondary @endif
                                        ">
                                                {{ $schedule->schedule_status == 0 ? 'Chưa điểm danh' : 'Đã điểm danh' }}
                                            </span>
                                        <td>
                                            <button class="btn btn-outline-primary btn-sm view-details-schedule-btn"
                                                data-schedule-id="{{ $schedule->schedule_id }}"
                                                data-bs-target="#detailsModal">
                                                Xem nhanh
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>



            </div>



        </div>


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
        <!-- ========== Footer End ========== -->

    </div>


@endsection
@push('scripts')
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script>
        $(document).ready(function() {
            // Khởi tạo mảng toàn cục
            var paid = [];
            var unpaid = [];
            var classNames = []; // Đổi tên để tránh xung đột với từ khóa

            // Hàm tải dữ liệu và cập nhật biểu đồ
            function loadClasses(courseId) {
                $.ajax({
                    url: `/admin/dashboard/chart/${courseId}`, // Đảm bảo route đúng
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {


                        // Xóa dữ liệu cũ
                        paid = [];
                        unpaid = [];
                        classNames = [];

                        // Lấy dữ liệu từ response
                        response.data.data.forEach(element => {
                            paid.push(element.paid_students);
                            unpaid.push(element.unpaid_students);
                            classNames.push(element.class_name);
                        });
                        $('#pagination-wrapper').html(response.pagination);
                        // Cập nhật biểu đồ
                        updateChart();
                    },
                    error: function(xhr, status, error) {
                        console.error('Lỗi khi tải dữ liệu:', error);
                    }
                });
            }

            //Phân  trang
            $("#pagination-wrapper").on("click", "a", function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                if (url) {
                    loadClassesByUrl(url);
                }
            });

            function loadClassesByUrl(url) {
                $.ajax({
                    url: url,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        // ... giống như loadClasses ở trên ...
                        console.log('Dữ liệu từ API 2:', response);
                        paid = [];
                        unpaid = [];
                        classNames = [];
                        response.data.data.forEach(element => {
                            paid.push(element.paid_students);
                            unpaid.push(element.unpaid_students);
                            classNames.push(element.class_name);
                        });
                        $('#pagination-wrapper').html(response.pagination);
                        updateChart();
                    },
                    error: function(xhr, status, error) {
                        console.error('Lỗi khi tải dữ liệu:', error);
                    }
                });
            }


            // Hàm cập nhật biểu đồ
            function updateChart() {
                var options = {
                    chart: {
                        height: 450,
                        type: "bar",
                        toolbar: {
                            show: false,
                        },
                    },
                    plotOptions: {
                        bar: {
                            horizontal: true,
                            dataLabels: {
                                position: "top",
                            },
                        },
                    },
                    dataLabels: {
                        enabled: true,
                        offsetX: -6,
                        style: {
                            fontSize: "12px",
                            colors: ["#fff"],
                        },
                    },
                    colors: ["#543EE8", "#5d7186"], // Màu xanh lá và cam
                    stroke: {
                        show: true,
                        width: 1,
                        colors: ["#fff"],
                    },
                    series: [{
                            name: "Đã đóng",
                            data: paid,
                        },
                        {
                            name: "Chưa đóng",
                            data: unpaid,
                        }
                    ],
                    xaxis: {
                        categories: classNames
                    },
                    legend: {
                        offsetY: 5,
                    },
                    states: {
                        hover: {
                            filter: "none",
                        },
                    },
                    grid: {
                        borderColor: "#f1f3fa",
                        padding: {
                            bottom: 5,
                        },
                    },
                };

                // Kiểm tra nếu chart đã được khởi tạo
                if (typeof chart !== 'undefined') {
                    chart.updateSeries([{
                        name: "Đã đóng",
                        data: paid
                    }, {
                        name: "Chưa đóng",
                        data: unpaid
                    }]);
                    chart.updateOptions({
                        xaxis: {
                            categories: classNames
                        }
                    });
                } else {
                    // Khởi tạo biểu đồ nếu chưa có
                    chart = new ApexCharts(document.querySelector("#grouped-bar"), options);
                    chart.render();
                }
            }

            // Gọi hàm lần đầu khi trang tải (Tất cả khóa học)
            loadClasses(0);

            // Xử lý sự kiện khi thay đổi khóa học
            $('#courseSelect').on('change', function() {
                var courseId = $(this).val() ? parseInt($(this).val()) : 0;
                loadClasses(courseId);
            });
        });






        // Xử lý sự kiện click vào nút "Xem chi tiết"
        $(document).on('click', '.view-details-schedule-btn', function() {
            var scheduleId = $(this).data('schedule-id');

            // Gửi yêu cầu AJAX để lấy thông tin chi tiết
            $.ajax({
                url: `/admin/dashboard/schedules/${scheduleId}/views`,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    console.log('Dữ liệu chi tiết lịch học:', response.attendances);
                    // Cập nhật nội dung modal với dữ liệu trả về
                    $('#modalClass').text(response.schedule.class_name);
                    $('#modalSubject').text(response.schedule.course_name);
                    $('#modalTeacher').text(response.schedule.teacher_name);
                    $('#modalTime').text(response.schedule.time_range);
                    $('#modalStatus').text(response.schedule.status_label);
                    $('#modalStatus').addClass(
                        'badge px-2 py-1 rounded fw-bold ' +
                        (
                            response.schedule.status_label === 'Chưa học' ?
                            'bg-warning text-white' :
                            response.schedule.status_label === 'Đang học' ?
                            'bg-success text-white' :
                            response.schedule.status_label === 'Đã kết thúc' ?
                            'bg-secondary text-white' :
                            'bg-light text-dark'
                        )
                    );
                    // Cập nhật danh sách điểm danh
                    var attendanceBody = '';
                    response.attendances.forEach(function(attendance, index) {
                        // Gán tên hiển thị & class theo trạng thái
                        let statusClass = '';
                        let statusText = '';

                        switch (attendance.attendance_status) {
                            case 'present':
                                statusText = 'Có mặt';
                                statusClass = 'bg-success text-white';
                                break;
                            case 'absent':
                                statusText = 'Vắng mặt';
                                statusClass = 'bg-danger text-white';
                                break;
                            case 'late':
                                statusText = 'Đi muộn';
                                statusClass = 'bg-warning text-dark';
                                break;
                            case 'excused':
                                statusText = 'Có phép';
                                statusClass = 'bg-info text-white';
                                break;
                            default:
                                statusText = 'Chưa rõ';
                                statusClass = 'bg-light text-dark';
                                break;
                        }

                        attendanceBody += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${attendance.student_name}</td>
                                <td><span class="badge ${statusClass} p-1">${statusText}</span></td>
                                <td>${attendance.attendance_note || 'Không có ghi chú'}</td>
                            </tr>`;
                    });


                    //kiểm tra nếu không có điểm danh nào
                    if (response.schedule.schedule_status == 0) {
                        attendanceBody =
                            '<tr><td colspan="4" class="text-center bg-info text-white">Chưa điểm danh!</td></tr>';
                    }
                    $('#attendanceBody').html(attendanceBody);

                    $('#detailsModal').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error('Lỗi khi tải chi tiết lịch học:', error);
                }
            });
        });


        $("#todayBtn").on('click', function() {
            const date = $(this).data('today');
            $("#basic-datepicker").val(date);
            $("#basic-datepicker").trigger('change');

        })

        //Xử lý lọc ngày
        $('#basic-datepicker').on('change', function() {
            var selectedDate = $(this).val();
            if (selectedDate) {
                // Chuyển đổi định dạng ngày sang định dạng YYYY-MM-DD
                var dateParts = selectedDate.split('/');
                var formattedDate = dateParts[2] + '-' + dateParts[1] + '-' + dateParts[0];
                // Gửi yêu cầu AJAX để lấy lịch học theo ngày đã chọn
                $.ajax({
                    url: `/admin/dashboard/schedules/date/${formattedDate}`,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        $(".title-date-schedules").html('Lịch học (' + response.formattedDate + ')')
                        renderSchedules(response.schedules);
                    },
                    error: function(xhr, status, error) {
                        console.error('Lỗi khi tải lịch học:', error);
                    }
                });
            }
        });

        //Hàm render lịch học
        function renderSchedules(schedules) {
            var tableBody = $('#tableBody');
            tableBody.empty(); // Xóa nội dung cũ

            schedules.forEach(function(schedule, index) {
                var statusLabel = '';
                if (schedule.status_label === 'Chưa học') {

                    statusLabel = '<span class="badge bg-warning text-white p-1">' + schedule.status_label +
                        '</span>';
                } else if (schedule.status_label === 'Đang học') {
                    statusLabel = '<span class="badge bg-success text-white p-1">' + schedule.status_label +
                        '</span>';
                } else if (schedule.status_label === 'Đã kết thúc') {
                    statusLabel = '<span class="badge bg-secondary text-white p-1">' + schedule.status_label +
                        '</span>';
                } else {
                    statusLabel = '<span class="badge bg-light text-dark p-1">' + schedule.status_label + '</span>';
                }

                var attendanceStatus = schedule.schedule_status == 0 ? 'Chưa điểm danh' : 'Đã điểm danh';
                var attendanceBadgeClass = schedule.schedule_status == 0 ? 'bg-info text-white' :
                    'bg-success text-white';

                tableBody.append(
                    `<tr>
                            <td>${index + 1}</td>
                            <td>${schedule.class_name}</td>
                            <td>${schedule.course_name}</td>
                            <td>${schedule.teacher_name}</td>
                            <td>${schedule.time_range}</td>
                            <td class="text-center">${statusLabel}</td>
                            <td class="text-center"><span class="badge ${attendanceBadgeClass} p-1">${attendanceStatus}</span></td>
                            <td><button class="btn btn-outline-primary btn-sm view-details-schedule-btn" data-schedule-id="${schedule.schedule_id}" data-bs-target="#detailsModal">Xem nhanh</button></td>
                        </tr>`
                );
            });
        }




        // xử lý phần liên hệ
        const pusher = new Pusher("YOUR_PUSHER_KEY", {
            cluster: "YOUR_PUSHER_CLUSTER",
            authEndpoint: "/broadcasting/auth",
            auth: {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }
        });

        const channel = pusher.subscribe('presence-staff-support');

        channel.bind('App\\Events\\SupportRequestCreated', function(data) {
            if ({{ auth()->check() && auth()->user()->role == 'staff' ? 'true' : 'false' }}) {
                if (confirm(
                        `Yêu cầu mới từ ${data.support.name} (${data.support.phone}) - ${data.support.pl_content}: ${data.support.message}\nBạn nhận xử lý?`
                    )) {
                    fetch(`/contact-support/${data.support.id}/handle`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(res => res.json())
                        .then(resp => alert(resp.message))
                        .catch(() => alert('Không thể nhận xử lý yêu cầu!'));
                }
            }
        });


        //Bắt đầu lấy dữ liệu khi tải lại trang thgeo năm hiện tại
        $(document).ready(function() {
            // Khởi tạo năm hiện tại từ hệ thống
            let year = new Date().getFullYear();
            let $currentYearEl = $('#currentYear');
            let pieChart = null;
            let chartRevenue = null;


            // Cập nhật giao diện và gọi API lần đầu
            $currentYearEl.text(year);
            getChartRevenue(year);
            getChartRevenueCourse(year);
            // Sự kiện khi click "Năm trước"
            $('#prevYear').on('click', function() {
                year--;
                $currentYearEl.text(year);
                getChartRevenue(year);
                getChartRevenueCourse(year);

            });

            // Sự kiện khi click "Năm sau"
            $('#nextYear').on('click', function() {
                year++;
                $currentYearEl.text(year);
                getChartRevenue(year);
                getChartRevenueCourse(year);

            });

            // Hàm gọi API lấy dữ liệu biểu đồ
            function getChartRevenue(year) {
                $.ajax({
                    url: `/admin/dashboard/chart/revenue/${year}`,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        charDoanhThu(response.revenues, response.students);
                    },
                    error: function(xhr, status, error) {
                        console.error('Lỗi khi tải dữ liệu:', error);
                    }
                });
            }

            // Hàm gọi API lấy dữ liệu biểu đồ pie
            function getChartRevenueCourse(year) {
                $.ajax({
                    url: `/admin/dashboard/chart/revenueCourse/${year}`,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        console.log(`Dữ liệu khoa ${year}:`, response);
                        chartPie(response.courses, response.revenues);
                    },
                    error: function(xhr, status, error) {
                        console.error('Lỗi khi tải dữ liệu:', error);
                    }
                });
            }


            //Hàm render biểu đồ thống kê doanh thu
            function charDoanhThu(doanhthu, hocsinh) {
                var options = {
                    series: [{
                            name: "Tổng doanh thu",
                            type: "bar",
                            data: doanhthu,
                        },
                        {
                            name: "Học sinh",
                            type: "area",
                            data: hocsinh,
                        },
                    ],
                    chart: {
                        height: 313,
                        type: "line",
                        toolbar: {
                            show: false,
                        },
                    },
                    stroke: {
                        dashArray: [0, 0],
                        width: [0, 2],
                        curve: 'smooth'
                    },
                    fill: {
                        opacity: [1, 1],
                        type: ['solid', 'gradient'],
                        gradient: {
                            type: "vertical",
                            inverseColors: false,
                            opacityFrom: 0.5,
                            opacityTo: 0,
                            stops: [0, 90]
                        },
                    },
                    markers: {
                        size: [0, 0],
                        strokeWidth: 2,
                        hover: {
                            size: 4,
                        },
                    },
                    xaxis: {
                        categories: [
                            "Tháng 1", "Tháng 2", "Tháng 3", "Tháng 4",
                            "Tháng 5", "Tháng 6", "Tháng 7", "Tháng 8",
                            "Tháng 9", "Tháng 10", "Tháng 11", "Tháng 12",
                        ],
                        axisTicks: {
                            show: false,
                        },
                        axisBorder: {
                            show: false,
                        },
                    },
                    yaxis: {
                        min: 0,
                        axisBorder: {
                            show: false,
                        }
                    },
                    grid: {
                        show: true,
                        strokeDashArray: 3,
                        xaxis: {
                            lines: {
                                show: false,
                            },
                        },
                        yaxis: {
                            lines: {
                                show: true,
                            },
                        },
                        padding: {
                            top: 0,
                            right: -2,
                            bottom: 0,
                            left: 10,
                        },
                    },
                    legend: {
                        show: true,
                        horizontalAlign: "center",
                        offsetX: 0,
                        offsetY: 5,
                        markers: {
                            width: 9,
                            height: 9,
                            radius: 6,
                        },
                        itemMargin: {
                            horizontal: 10,
                            vertical: 0,
                        },
                    },
                    plotOptions: {
                        bar: {
                            columnWidth: "30%",
                            barHeight: "70%",
                            borderRadius: 3,
                        },
                    },
                    colors: ["#543EE8", "#22c55e"],
                    tooltip: {
                        shared: true,
                        y: [{
                                formatter: function(y) {
                                    if (typeof y !== "undefined") {
                                        return y.toLocaleString('vi-VN') + " VND";
                                    }
                                    return y;
                                },
                            },
                            {
                                formatter: function(y) {
                                    if (typeof y !== "undefined") {
                                        return y.toLocaleString('vi-VN') + " học sinh";
                                    }
                                    return y;
                                },
                            },
                        ],
                    },
                };



                // Nếu đã có chart → destroy trước khi tạo mới
                if (chartRevenue !== null) {
                    chartRevenue.destroy();
                }

                chartRevenue = new ApexCharts(document.querySelector("#dash-performance-chart"), options);
                chartRevenue.render();
            }





            // Biểu đồ tròn thể hiện doanh thu của từng khóa
            function chartPie(courseName, totalPrice) {
                var colors = ["#7f56da", "#1c84ee", "#ff6c2f", "#4ecac2", "#f9b931", ];
                var options = {
                    chart: {
                        height: 320,
                        type: 'donut',
                    },
                    series: totalPrice,
                    legend: {
                        show: true,
                        position: 'bottom',
                        horizontalAlign: 'center',
                        verticalAlign: 'middle',
                        floating: false,
                        fontSize: '14px',
                        offsetX: 0,
                        offsetY: 7
                    },

                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(val);
                                // Ví dụ: 1500000 → 1.500.000 ₫
                            }
                        }
                    },
                    labels: courseName,
                    colors: colors,
                    responsive: [{
                        breakpoint: 600,
                        options: {
                            chart: {
                                height: 240
                            },
                            legend: {
                                show: false
                            },
                        }
                    }]
                }


                // Nếu đã có chart → destroy trước khi tạo mới
                if (pieChart !== null) {
                    pieChart.destroy();
                }

                pieChart = new ApexCharts(document.querySelector("#simple-donut"), options);
                pieChart.render();
            }

        });

        document.getElementById('basic-datepicker').flatpickr();
        AOS.init({
            duration: 1000, // Thời gian chạy hiệu ứng (ms)
            once: true, // Chạy 1 lần hay mỗi lần cuộn đến
        });
    </script>
@endpush
