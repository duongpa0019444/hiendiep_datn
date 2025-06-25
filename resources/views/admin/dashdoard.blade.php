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


                                <div class="card overflow-hidden">
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

                                            <a href="#!" class="text-reset fw-semibold fs-12">Xem Thêm</a>
                                        </div>
                                    </div> <!-- end card body -->
                                </div> <!-- end card -->
                            </div> <!-- end col -->
                        @endforeach


                        @foreach ($classs as $class)
                            <div class="col-md-12">
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
                                                <span class="text-muted fs-12">Đang học</span>
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

                                            <a href="#!" class="text-reset fw-semibold fs-12">Xem Thêm</a>
                                        </div>
                                    </div> <!-- end card body -->


                                </div> <!-- end card -->
                            </div> <!-- end col -->
                        @endforeach


                        @foreach ($countPayments as $countPayment)
                            <div class="col-md-12">
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

                                            <a href="#!" class="text-reset fw-semibold fs-12">Xem Thêm</a>
                                        </div>
                                    </div> <!-- end card body -->


                                </div> <!-- end card -->
                            </div> <!-- end col -->
                        @endforeach


                    </div> <!-- end row -->
                </div> <!-- end col -->

                <div class="col-xxl-7 mt-3">
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
            <div class="card mt-2 p-2">
                <div class="table-container">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-0">Lịch học ({{ $formattedDate  }})</h4>
                        <div class="d-flex align-items-center">
                            <input type="text" id="basic-datepicker" class="form-control me-2" placeholder="Date"
                                data-provider="flatpickr" data-date-format="d/m/Y" value="{{ $formattedDate  }}">
                            <button id="todayBtn" class="btn btn-primary  today-btn">Today</button>
                        </div>
                    </div>
                    <table class="table table-centered table-hover" id="scheduleTable">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">STT</th>
                                <th scope="col">Lớp học</th>
                                <th scope="col">Môn học</th>
                                <th scope="col">Giáo viên</th>
                                <th scope="col">Thời gian</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col">Hành động</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            @foreach ($schedules as $key => $schedule)

                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $schedule->class_name }}</td>
                                    <td>{{ $schedule->course_name }}</td>
                                    <td>{{ $schedule->teacher_name }}</td>
                                    <td>{{ $schedule->time_range }}</td>
                                    <td>
                                        <span class="badge
                                            @if ($schedule->status_label === 'Chưa học') bg-warning
                                            @elseif ($schedule->status_label === 'Đang học') bg-success
                                            @elseif ($schedule->status_label === 'Đã kết thúc') bg-secondary
                                            @else bg-light text-dark
                                            @endif
                                        ">
                                            {{ $schedule->status_label }}
                                        </span>
                                    </td>

                                    <td>
                                        <button class="btn btn-outline-primary btn-sm view-details-schedule-btn"
                                                data-schedule-id="{{ $schedule->schedule_id }}"
                                                data-bs-target="#detailsModal">
                                            Xem chi tiết
                                        </button>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>

                <!-- Modal chi tiết -->
                <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="detailsModalLabel">Chi tiết lớp học</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-1">
                                    <div class="row">
                                        <div class="col-md-4 mb-2">
                                            <p><strong>Lớp học:</strong> <span id="modalClass"></span></p>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <p><strong>Môn học:</strong> <span id="modalSubject"></span></p>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <p><strong>Giáo viên:</strong> <span id="modalTeacher"></span></p>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <p><strong>Thời gian:</strong> <span id="modalTime"></span></p>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <p><strong>Trạng thái:</strong> <span id="modalStatus" class=""></span></p>
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <h4>Danh sách điểm danh</h4>
                                <table class="table table-bordered attendance-table">
                                    <thead>
                                        <tr>
                                            <th>STT</th>
                                            <th>Tên học viên</th>
                                            <th>Trạng thái</th>
                                            <th>Ghi chú</th>
                                        </tr>
                                    </thead>
                                    <tbody id="attendanceBody"></tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            </div>
                        </div>
                    </div>
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
    <script>
        $(document).ready(function() {
            // Khởi tạo mảng toàn cục
            var paid = [];
            var unpaid = [];
            var classNames = []; // Đổi tên để tránh xung đột với từ khóa

            // Hàm tải dữ liệu và cập nhật biểu đồ
            function loadClasses(courseId) {
                $.ajax({
                    url: `dashboard/chart/${courseId}`, // Đảm bảo route đúng
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        console.log('Dữ liệu từ API:', response);

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
                    colors: ["#22C55E", "#FF6C2F"], // Màu xanh lá và cam
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
                        console.log('Dữ liệu chi tiết lịch học:', response.schedule);
                        // Cập nhật nội dung modal với dữ liệu trả về
                        $('#modalClass').text(response.schedule.class_name);
                        $('#modalSubject').text(response.schedule.course_name);
                        $('#modalTeacher').text(response.schedule.teacher_name);
                        $('#modalTime').text(response.schedule.time_range);
                        $('#modalStatus').text(response.schedule.status_label);
                        $('#modalStatus').addClass(
                            'badge px-2 py-1 rounded fw-bold ' +
                            (
                                response.schedule.status_label === 'Chưa học' ? 'bg-warning text-dark' :
                                response.schedule.status_label === 'Đang học' ? 'bg-success text-white' :
                                response.schedule.status_label === 'Đã kết thúc' ? 'bg-secondary text-white' :
                                'bg-light text-dark'
                            )
                        );
                        // Cập nhật danh sách điểm danh
                        // var attendanceBody = '';
                        // response.attendance.forEach(function(attendance, index) {
                        //     attendanceBody += '<tr>' +
                        //         '<td>' + (index + 1) + '</td>' +
                        //         '<td>' + attendance.student_name + '</td>' +
                        //         '<td>' + attendance.status + '</td>' +
                        //         '<td>' + (attendance.note || 'Không có ghi chú') + '</td>' +
                        //         '</tr>';
                        // });
                        // $('#attendanceBody').html(attendanceBody);

                        // Hiển thị modal
                        $('#detailsModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error('Lỗi khi tải chi tiết lịch học:', error);
                    }
                });
            });


        document.getElementById('basic-datepicker').flatpickr();
    </script>
@endpush
