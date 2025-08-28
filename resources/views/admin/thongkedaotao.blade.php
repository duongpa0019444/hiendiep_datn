@extends('admin.admin')
@section('title', 'Quản lí thống kê')

@section('content')
    <div class="page-content">
        <div class="container-fluid ">

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Thống kê đào tạo</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between">
                <h4>Thống kê đào tạo</h4>
                <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
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
            </div>
            <!-- Biểu đồ -->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-0">Số lượng học sinh đăng ký theo khóa theo tháng</h4>
                        <button class="btn btn-sm btn-primary btn-export-student-enroll-chart">
                            <i class="fas fa-file-export me-1"></i> Xuất báo cáo
                        </button>
                    </div>
                    <div id="student-enroll-chart" class="apex-charts"></div>
                </div>

                <div class="card-body">
                    <h4 class="card-title mb-3">Top 3 khóa có nhiều học sinh nhất theo tháng</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered text-center" id="top3-table">
                            <thead class="table-light">
                                <tr>
                                    <th>Tháng</th>
                                    <th>Top 1</th>
                                    <th>Top 2</th>
                                    <th>Top 3</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>


            <!-- Horizontal Bar Chart -->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <h4 class="card-title">Thống kê số buổi dạy của giáo viên theo tháng</h4>
                        <button class="btn btn-sm btn-primary btn-export-teaching-report">
                            <i class="fas fa-file-export me-1"></i> Xuất báo cáo
                        </button>
                    </div>
                    <div id="teaching-chart" class="apex-charts"></div>
                </div>
            </div>


            <!-- Bubble Chart -->
            {{-- <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Tỷ lệ chuyên cần trung bình của giáo viên theo tháng</h4>
                    <div id="attendance-chart" class="apex-charts"></div>
                </div>

            </div> --}}

            <div class="card">
                <div class="card-body">

                    <div class="d-flex justify-content-between mb-3">
                        <h4 class="card-title">Tổng hợp tình trạng lớp học</h4>
                        <button class="btn btn-sm btn-primary btn-export-class-report">
                            <i class="fas fa-file-export me-1"></i> Xuất báo cáo
                        </button>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div id="class-status-pie" class="apex-charts mt-4"></div>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="text-center">Top 3 khóa nhiều lớp nhất</h5>
                                    <div id="top-courses-bar" class="apex-charts"></div>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="text-center">Top 3 khóa ít lớp nhất</h5>
                                    <div id="bottom-courses-bar" class="apex-charts"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered text-center align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Khóa học</th>
                                    <th style="color:#3b82f6">Lớp Chưa bắt đầu</th>
                                    <th style="color:#9333ea">Lớp Đang diễn ra</th>
                                    <th style="color:#f97316">Lớp Đã hoàn thành</th>
                                    <th><b>Tổng</b></th>
                                </tr>
                            </thead>
                            <tbody id="class-status-table-body">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title">Thống kê số lượng học sinh trong từng lớp</h4>
                        <button class="btn btn-sm btn-primary btn-export-student-class-chart">
                            <i class="fas fa-file-export me-1"></i> Xuất báo cáo
                        </button>
                    </div>
                    <div id="student-class-bar" class="apex-charts"></div>
                    <div id="pagination-student-classes" class="card-footer border-top">

                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title">Thống kê điểm trung bình các lớp</h4>
                        <button class="btn btn-sm btn-primary btn-export-avgscore-class-chart">
                            <i class="fas fa-file-export me-1"></i> Xuất báo cáo
                        </button>
                    </div>
                    <div id="avg-score-bar" class="apex-charts"></div>
                    <div id="pagination-diemtb-lop" class="card-footer border-top">

                    </div>
                </div>

            </div>



        </div>
    </div>


@endsection

@push('scripts')
    <script>
        // Khởi tạo năm hiện tại từ hệ thống
        var year = new Date().getFullYear();
        var $currentYearEl = $('#currentYear');

        // Biến lưu trữ biểu đồ để có thể destroy khi vẽ lại
        var charThongKeHsDK = null;
        var chartTongHsClass = null;
        var chartDiemTbLop = null;
        var chartBuoiDay = null;
        var chartClassStatusPie = null;
        var chartTopCoursesBar = null;
        var chartBottomCoursesBar = null;


        // Hàm load dữ liệu từ API
        function loadDashboardData(year) {
            $currentYearEl.text(year);

            // Gọi API lấy Số lượng học sinh đăng ký theo khóa theo tháng
            $.ajax({
                url: `/admin/thong-ke-hs-dk/${year}`,
                type: 'GET',
                success: function(res) {
                    console.log(res);
                    // ExcelcharThongKeHsDK = res;
                    renderThongKeHsDK(res);
                },
                error: function(xhr) {
                    console.error("Lỗi khi lấy tổng quỹ lương:", xhr.responseText);
                }
            });

            // Gọi API lấy buổi dạy của giáo viên
            $.ajax({
                url: `/admin/thong-ke-buoi-day/${year}`,
                type: 'GET',
                success: function(res) {
                    renderBuoiDay(res.data);
                },
                error: function(xhr) {
                    console.error("Lỗi khi lấy tổng quỹ lương:", xhr.responseText);
                }
            });

            // Gọi API lấy buổi dạy của giáo viên
            $.ajax({
                url: `/admin/thong-ke-trang-thai-lop/${year}`,
                type: 'GET',
                success: function(res) {
                    renderClassStatus(res);
                },
                error: function(xhr) {
                    console.error("Lỗi khi lấy tổng quỹ lương:", xhr.responseText);
                }
            });


            // Gọi API lấy tổng học sinh trong lớp học
            $.ajax({
                url: `/admin/sl-hs-theo-lop/${year}`,
                type: 'GET',
                success: function(res) {
                    renderTongHsClass(res);
                    // Phân trang
                    $('#pagination-student-classes').html(res.pagination);
                },
                error: function(xhr) {
                    console.error("Lỗi khi lấy tổng quỹ lương:", xhr.responseText);
                }
            });

            // Gọi API lấy điểm trung bình theo lớp
            $.ajax({
                url: `/admin/diem-tb-theo-lop/${year}`,
                type: 'GET',
                success: function(res) {
                    renderDiemTbLop(res);
                    // Phân trang
                    $('#pagination-diemtb-lop').html(res.pagination);
                },
                error: function(xhr) {
                    console.error("Lỗi khi lấy tổng quỹ lương:", xhr.responseText);
                }
            });


        }

        // Sự kiện khi click "Năm trước"
        $('#prevYear').on('click', function() {
            year--;
            loadDashboardData(year);
        });

        // Sự kiện khi click "Năm sau"
        $('#nextYear').on('click', function() {
            year++;
            loadDashboardData(year);
        });

        // Gọi API lấy dữ liệu đầu tiên khi load trang
        $(document).ready(function() {
            loadDashboardData(year);
        });



        // Thống kê học sinh đăng kí học theo khóa theo tháng tại trung tâm
        function renderThongKeHsDK(data = null) {
            var months = ["Tháng 1", "Tháng 2", "Tháng 3", "Tháng 4", "Tháng 5", "Tháng 6", "Tháng 7", "Tháng 8", "Tháng 9",
                "Tháng 10", "Tháng 11", "Tháng 12"
            ];
            var seriesData = data.data;

            var options = {
                chart: {
                    type: "line",
                    height: 500,
                    toolbar: {
                        show: true
                    }
                },
                stroke: {
                    width: 3,
                    curve: "smooth"
                },
                dataLabels: {
                    enabled: false
                },
                series: seriesData,
                xaxis: {
                    categories: months,
                    title: {
                        text: "Tháng"
                    }
                },
                yaxis: {
                    title: {
                        text: "Số lượng học sinh"
                    },
                    min: 0,
                    max: 100
                },
                tooltip: {
                    shared: true,
                    y: {
                        formatter: (val) => val + " học sinh"
                    }
                },
                legend: {
                    position: "top",
                    horizontalAlign: "center"
                },
                colors: ["#ef5f5f", "#22c55e", "#3b82f6", "#fbbf24", "#6366f1", "#a855f7", "#d97706", "#ff8c00",
                    "#ff5722",
                    "#ff4081"
                ]
            };
            // Nếu đã có chart → destroy trước khi tạo mới
            if (charThongKeHsDK !== null) {
                charThongKeHsDK.destroy();
            }

            charThongKeHsDK = new ApexCharts(document.querySelector("#student-enroll-chart"), options);
            charThongKeHsDK.render();




            // Bảng phụ: Top 3 khóa theo tháng ---------------------

            let tbody = document.querySelector("#top3-table tbody");
            let rows = "";

            data.top3Courses.forEach((top3) => {
                rows += `
                    <tr>
                        <td><b>${top3.thang}</b></td>
                        <td>${top3.Top1 || ""}</td>
                        <td>${top3.Top2 || ""}</td>
                        <td>${top3.Top3 || ""}</td>
                    </tr>`;
            });

            tbody.innerHTML = rows;



        }

        // Xuất báo cáo số học đk theo khóa học theo tháng
        $(document).on('click', '.btn-export-student-enroll-chart', function(e) {
            e.preventDefault();

            
            console.log(year);
            Swal.fire({
                title: 'Đang xử lý...',
                text: 'Vui lòng chờ trong giây lát',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            window.location.href = `/admin/xuat-bao-cao/hoc-sinh-dang-ky/${year}`;
            Swal.close();
        });



        // Stacked Bar Chart: số buổi dạy theo giáo viên / tháng
        function renderBuoiDay(data = null) {
            var months = ["Th1", "Th2", "Th3", "Th4", "Th5", "Th6", "Th7", "Th8", "Th9", "Th10", "Th11", "Th12"];
            var seriesData = data;

            // Biểu đồ stacked
            var options = {
                chart: {
                    type: 'bar',
                    height: 500,
                    stacked: true,
                    toolbar: {
                        show: true
                    }
                },
                plotOptions: {
                    bar: {
                        columnWidth: '55%',
                        borderRadius: 3
                    }
                },
                series: seriesData,
                xaxis: {
                    categories: months,
                    title: {
                        text: "Tháng"
                    }
                },
                yaxis: {
                    title: {
                        text: "Số buổi dạy"
                    }
                },
                colors: ['#1E90FF', '#22c55e', '#f97316', '#e11d48', '#9333ea'],
                tooltip: {
                    y: {
                        formatter: function(val, opts) {
                            let total = seriesData.reduce((acc, s) => acc + s.data[opts.dataPointIndex], 0);
                            let percent = ((val / total) * 100).toFixed(1);
                            return val + " buổi (" + percent + "% tổng tháng)";
                        }
                    }
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'center'
                }
            };

            // Nếu đã có chart → destroy trước khi tạo mới
            if (chartBuoiDay !== null) {
                chartBuoiDay.destroy();
            }

            chartBuoiDay = new ApexCharts(document.querySelector("#teaching-chart"), options);
            chartBuoiDay.render();
        }

        // Xuất báo cáo số học đk theo khóa học theo tháng
        $(document).on('click', '.btn-export-teaching-report', function(e) {
            e.preventDefault();

            
            console.log(year);
            Swal.fire({
                title: 'Đang xử lý...',
                text: 'Vui lòng chờ trong giây lát',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            window.location.href = `/admin/xuat-bao-cao/so-buoi-day-giao-vien/${year}`;
            Swal.close();
        });



        //Tổng hợp tình trạng lớp học
        function renderClassStatus(data) {

            // Biểu đồ hình tròn: Tình trạng lớp học
            var totals = data.statusClasses; // [Chưa bắt đầu, Đang diễn ra, Đã hoàn thành]
            var pieOptions = {
                chart: {
                    type: 'pie',
                    height: 380,
                    toolbar: {
                        show: true
                    }
                },
                labels: data.labelsClasses,
                series: totals,
                colors: ['#3b82f6', '#9333ea', '#f97316'],
                dataLabels: {
                    formatter: function(val, opts) {
                        let total = opts.w.globals.series[opts.seriesIndex];
                        return val.toFixed(1) + "% (" + total + " lớp)";
                    }
                },
                legend: {
                    position: 'bottom'
                }
            };
            // Nếu đã có chart → destroy trước khi tạo mới
            if (chartClassStatusPie !== null) {
                chartClassStatusPie.destroy();
            }

            chartClassStatusPie = new ApexCharts(document.querySelector("#class-status-pie"), pieOptions);
            chartClassStatusPie.render();



            // Lấy top 3 & bottom 3
            var top3 = data.top3;
            var bottom3 = data.bottom3;

            var dataTop3 = data.dataTop3;
            var dataBottom3 = data.dataBottom3;


            // Chart: Top 3 khóa học nhiều lớp nhất
            var topBarOptions = {
                chart: {
                    type: 'bar',
                    height: 'auto'
                },
                series: [{
                    name: 'Số lớp',
                    data: dataTop3
                }],
                xaxis: {
                    categories: top3
                },
                colors: ['#10b981'],
                dataLabels: {
                    enabled: true,
                    formatter: function(val) {
                        return val + " lớp";
                    }
                }
            };
            // Nếu đã có chart → destroy trước khi tạo mới
            if (chartTopCoursesBar !== null) {
                chartTopCoursesBar.destroy();
            }

            chartTopCoursesBar = new ApexCharts(document.querySelector("#top-courses-bar"), topBarOptions);
            chartTopCoursesBar.render();


            // Bottom 3 khóa học ít lớp nhất
            var bottomBarOptions = {
                chart: {
                    type: 'bar',
                    height: 'auto'
                },
                series: [{
                    name: 'Số lớp',
                    data: dataBottom3
                }],
                xaxis: {
                    categories: bottom3
                },
                colors: ['#ef4444'],
                dataLabels: {
                    enabled: true
                }
            };
            // Nếu đã có chart → destroy trước khi tạo mới
            if (chartBottomCoursesBar !== null) {
                chartBottomCoursesBar.destroy();
            }

            chartBottomCoursesBar = new ApexCharts(document.querySelector("#bottom-courses-bar"), bottomBarOptions);
            chartBottomCoursesBar.render();



            //Render bảng liệt kê lớp học theo khóa với trạng thái
            let tbody = document.querySelector("#class-status-table-body");
            let rows = "";

            // reset totals
            let totalsStatusClass = [0, 0, 0];
            let totalsTong = 0;

            data.statusClassesCourses.forEach(item => {
                // ép kiểu số để tránh cộng chuỗi
                let lopChuaBatDau = Number(item.lop_chua_bat_dau) || 0;
                let lopDangDienRa = Number(item.lop_dang_dien_ra) || 0;
                let lopDaHoanThanh = Number(item.lop_da_hoan_thanh) || 0;
                let tong = Number(item.tong_lop) || 0;

                // cộng dồn totals
                totalsStatusClass[0] += lopChuaBatDau;
                totalsStatusClass[1] += lopDangDienRa;
                totalsStatusClass[2] += lopDaHoanThanh;
                totalsTong += tong;

                rows += `
                    <tr>
                        <td><b>${item.course_name}</b></td>
                        <td style="color:#3b82f6">${lopChuaBatDau}</td>
                        <td style="color:#9333ea">${lopDangDienRa}</td>
                        <td style="color:#f97316">${lopDaHoanThanh}</td>
                        <td><b>${tong}</b></td>
                    </tr>
                `;
            });

            // Thêm dòng tổng cộng
            rows += `
                <tr class="table-secondary">
                    <td><b>Tổng cộng lớp</b></td>
                    <td style="color:#3b82f6"><b>${totalsStatusClass[0]}</b></td>
                    <td style="color:#9333ea"><b>${totalsStatusClass[1]}</b></td>
                    <td style="color:#f97316"><b>${totalsStatusClass[2]}</b></td>
                    <td><b>${totalsTong}</b></td>
                </tr>
            `;

            tbody.innerHTML = rows;


        }

        // Xuất báo cáo tổng hợp tình trạng lớp học
        $(document).on('click', '.btn-export-class-report', function(e) {
            e.preventDefault();

            
            console.log(year);
            Swal.fire({
                title: 'Đang xử lý...',
                text: 'Vui lòng chờ trong giây lát',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            window.location.href = `/admin/xuat-bao-cao/tong-tinh-trang-lop-hoc/${year}`;
            Swal.close();
        });

        



        // Tổng học sinh trong lớp học
        function renderTongHsClass(data = null) {

            // Tách dữ liệu ra cho ApexCharts
            const counts = data.counts;
            const slicedNames = data.labels;

            // Cấu hình biểu đồ ApexCharts
            const options = {
                chart: {
                    type: 'bar',
                    height: 400
                },
                plotOptions: {
                    bar: {
                        horizontal: true,
                        borderRadius: 4
                    }
                },
                series: [{
                    name: 'Số học sinh',
                    data: counts
                }],
                xaxis: {
                    categories: slicedNames,
                    title: {
                        text: 'Số học sinh'
                    }
                },
                colors: ['#3b82f6'],
                dataLabels: {
                    enabled: true,
                    formatter: function(val) {
                        return val + " HS"; // hiển thị thêm "HS" sau số
                    }
                },
                legend: {
                    show: false
                }
            };

            // Nếu đã có chart → destroy trước khi render mới
            if (chartTongHsClass !== null) {
                chartTongHsClass.destroy();
            }

            chartTongHsClass = new ApexCharts(document.querySelector('#student-class-bar'), options);
            chartTongHsClass.render();
        }
        //Phân trang
        $(document).on('click', '#pagination-student-classes a', function(e) {
            e.preventDefault();
            const url = this.href;
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    renderTongHsClass(response);
                    //Cập nhật phân trang
                    $('#pagination-student-classes').html(response.pagination);
                },
                error: function(xhr) {
                    console.error('Lỗi phân trang:', xhr.responseText);
                }
            });
        });

        // Xuất báo cáo số học sinh trong lớp
        $(document).on('click', '.btn-export-student-class-chart', function(e) {
            e.preventDefault();

            
            console.log(year);
            Swal.fire({
                title: 'Đang xử lý...',
                text: 'Vui lòng chờ trong giây lát',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            window.location.href = `/admin/xuat-bao-cao/so-hoc-sinh-trong-lop/${year}`;
            Swal.close();
        });




        // Điểm trung bình các Lớp học
        function renderDiemTbLop(data = null) {

            // Dữ liệu mẫu
            let ScoreTB = data.scores;
            let className = data.labels;

            // Gán màu theo điểm trung bình
            let barColors = ScoreTB.map(score => {
                if (score >= 8.5) return "#10b981"; // xanh lá
                if (score >= 7.0) return "#3b82f6"; // xanh dương
                if (score >= 5.0) return "#f97316"; // cam
                return "#ef4444"; // đỏ
            });

            const options = {
                chart: {
                    type: 'bar',
                    height: 400
                },
                plotOptions: {
                    bar: {
                        columnWidth: '55%',
                        borderRadius: 6,
                        distributed: true
                    }
                },
                series: [{
                    name: "Điểm trung bình",
                    data: ScoreTB
                }],
                xaxis: {
                    categories: className,
                    title: {
                        text: "Lớp"
                    }
                },
                yaxis: {
                    title: {
                        text: "Điểm trung bình"
                    },
                    min: 0,
                    max: 10
                },
                colors: barColors, // áp dụng màu theo điểm
                dataLabels: {
                    enabled: true,
                    formatter: val => val.toFixed(1)
                },
                legend: {
                    show: false
                },
                tooltip: {
                    y: {
                        formatter: val => val.toFixed(1) + " điểm"
                    }
                }
            };


            // Nếu đã có chart → destroy trước khi render mới
            if (chartDiemTbLop !== null) {
                chartDiemTbLop.destroy();
            }

            chartDiemTbLop = new ApexCharts(document.querySelector("#avg-score-bar"), options);
            chartDiemTbLop.render();
        }

        //Phân trang
        $(document).on('click', '#pagination-diemtb-lop a', function(e) {
            e.preventDefault();
            const url = this.href;
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    renderDiemTbLop(response);
                    //Cập nhật phân trang
                    $('#pagination-diemtb-lop').html(response.pagination);
                },
                error: function(xhr) {
                    console.error('Lỗi phân trang:', xhr.responseText);
                }
            });
        });

        // Xuất báo cáo tổng hợp tình trạng lớp học
        $(document).on('click', '.btn-export-avgscore-class-chart', function(e) {
            e.preventDefault();

            
            console.log(year);
            Swal.fire({
                title: 'Đang xử lý...',
                text: 'Vui lòng chờ trong giây lát',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            window.location.href = `/admin/xuat-bao-cao/diem-trung-binh-cac-lop/${year}`;
            Swal.close();
        });



        // Line Chart: Tỷ lệ chuyên cần giáo viên theo tháng
        // function renderTyLeChuyenCan() {
        //     var months = ["Th1", "Th2", "Th3", "Th4", "Th5", "Th6", "Th7", "Th8", "Th9", "Th10", "Th11", "Th12"];
        //     var seriesData = [{
        //             name: "GV A",
        //             data: [90, 89, 92, 91, 93, 85, 82, 81, 80, 79, 78, 76]
        //         },
        //         {
        //             name: "GV B",
        //             data: [88, 87, 86, 90, 88, 70, 72, 71, 70, 71, 60, 55]
        //         },
        //         {
        //             name: "GV C",
        //             data: [95, 97, 94, 96, 95, 80, 82, 81, 79, 80, 70, 60]
        //         },
        //         {
        //             name: "GV D",
        //             data: [85, 86, 87, 88, 83, 20, 21, 30, 40, 50, 60, 79]
        //         },
        //         {
        //             name: "GV E",
        //             data: [80, 82, 81, 79, 80, 20, 21, 25, 40, 55, 70, 85]
        //         }
        //     ];

        //     // Tính trung bình toàn trung tâm
        //     var avg = months.map((_, i) => {
        //         let sum = seriesData.reduce((acc, s) => acc + s.data[i], 0);
        //         return (sum / seriesData.length).toFixed(1);
        //     });

        //     //
        //     // Biểu đồ chuyên cần giáo viên
        //     //
        //     var options = {
        //         chart: {
        //             type: 'line',
        //             height: 450,
        //             toolbar: {
        //                 show: true
        //             }
        //         },
        //         stroke: {
        //             width: 3,
        //             curve: 'smooth'
        //         },
        //         dataLabels: {
        //             enabled: false
        //         },
        //         series: [...seriesData, {
        //             name: "Trung bình trung tâm",
        //             data: avg
        //         }],
        //         xaxis: {
        //             categories: months,
        //             title: {
        //                 text: "Tháng"
        //             }
        //         },
        //         yaxis: {
        //             title: {
        //                 text: "Tỷ lệ chuyên cần (%)"
        //             },
        //             min: 0,
        //             max: 100
        //         },
        //         annotations: {
        //             yaxis: [{
        //                 y: 80,
        //                 borderColor: '#ff0000',
        //                 label: {
        //                     borderColor: '#ff0000',
        //                     style: {
        //                         color: '#fff',
        //                         background: '#ff0000'
        //                     },
        //                     text: 'Chuẩn 80%'
        //                 }
        //             }]
        //         },
        //         tooltip: {
        //             shared: true,
        //             y: {
        //                 formatter: function(val) {
        //                     return val + "% " + (val >= 80 ? "(Đạt chuẩn)" : "(Dưới chuẩn)");
        //                 }
        //             }
        //         },
        //         colors: ['#1E90FF', '#22c55e', '#f97316', '#9333ea', '#e11d48', '#000000'],
        //         legend: {
        //             position: 'top',
        //             horizontalAlign: 'center'
        //         },
        //         markers: {
        //             size: 4
        //         }
        //     };

        //     new ApexCharts(document.querySelector("#attendance-chart"), options).render();
        // }
        // renderTyLeChuyenCan();
    </script>
@endpush
