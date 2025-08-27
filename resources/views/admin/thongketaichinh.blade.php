@extends('admin.admin')
@section('title', 'Quản lí thống kê tài chính')

@section('content')
    <div class="page-content">
        <div class="container-fluid ">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Thống kê tài chính</li>
                </ol>
            </nav>
            <div class="col-12 d-flex align-items-center justify-content-between">
                <h4 class="card-title mb-1">Thống kê tài chính</h4>
                <div class="d-flex align-items-center justify-content-center gap-2 mt-2">
                    <!-- Nút chuyển năm -->
                    <button type="button" class="btn btn-sm btn-outline-secondary d-flex align-items-center"
                        id="prevYear">
                        <i class="me-1">&laquo;</i> Năm trước
                    </button>
                    <!-- Hiển thị năm hiện tại -->
                    <span id="currentYear" class="px-3 py-1 bg-light rounded border fw-semibold text-primary">
                        {{ date('Y') }}
                    </span>
                    <!-- Nút chuyển năm -->
                    <button type="button" class="btn btn-sm btn-outline-secondary d-flex align-items-center"
                        id="nextYear">
                        Năm sau <i class="ms-1">&raquo;</i>
                    </button>
                </div>
            </div>

            <!-- Line Chart (Lương giáo viên) -->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-0">Tổng quỹ lương giáo viên theo tháng</h4>
                        <button class="btn btn-sm btn-primary btn-export-salary-area-chart">
                            <i class="fas fa-file-export me-1"></i> Xuất báo cáo
                        </button>
                    </div>
                    <div id="salary-area-chart" class="apex-charts"></div>
                </div>
            </div>


            {{-- Tong doanh thu --}}
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-3">Tổng doanh thu học phí theo tháng</h4>
                        <button class="btn btn-sm btn-primary btn-export-revenue-line-chart">
                            <i class="fas fa-file-export me-1"></i> Xuất báo cáo
                        </button>
                    </div>
                    <div id="revenue-line-chart" class="apex-charts"></div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-3">Tình trạng đóng học phí theo lớp</h4>
                        <button class="btn btn-sm btn-primary btn-export-tuition-stacked-chart">
                            <i class="fas fa-file-export me-1"></i> Xuất báo cáo
                        </button>
                    </div>
                    <div id="tuition-stacked-chart" class="apex-charts"></div>
                    <div id="pagination-hocphi" class="card-footer border-top">

                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-3">So sánh Doanh thu & Chi phí lương (Lãi/Lỗ)</h4>
                        <button class="btn btn-sm btn-primary btn-export-profit-chart">
                            <i class="fas fa-file-export me-1"></i> Xuất báo cáo
                        </button>
                    </div>
                    <div id="profit-chart" class="apex-charts"></div>
                </div>
            </div>

        </div>

        <!-- ========== Footer Start ========== -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 text-center">
                        <script>
                            document.write(new Date().getFullYear())
                        </script> &copy; DỰ ÁN TỐT NGHIỆP CAO ĐẲNG FPT POLYTECHNIC THANH HÓA <iconify-icon
                            icon="iconamoon:heart-duotone" class="fs-18 align-middle text-danger"></iconify-icon> <a
                            href="#" class="fw-bold footer-text" target="_blank">Tiger Code</a>
                    </div>
                </div>
            </div>
        </footer>
        <!-- ========== Footer End ========== -->
    </div>


@endsection
@push('scripts')
    <script>
        // Gọi hàm vẽ biểu đồ khi trang được tải
        $(document).ready(function() {
            // Khởi tạo năm hiện tại từ hệ thống
            var year = new Date().getFullYear();
            var $currentYearEl = $('#currentYear');

            // Biến lưu trữ biểu đồ để có thể destroy khi vẽ lại
            var CharTongLuong = null;
            var CharDoanhThu = null;
            var CharHocPhi = null;
            var CharLaiLo = null;

            // Hàm load dữ liệu từ API
            function loadDashboardData(year) {
                $currentYearEl.text(year);

                // Gọi API lấy tổng quỹ lương
                $.ajax({
                    url: `/admin/statistics/finance/tong-quy-luong/${year}`,
                    type: 'GET',
                    success: function(res) {
                        renderTongQuyLuong(res.data); // res.data là array giống dataNewTongLuong
                    },
                    error: function(xhr) {
                        console.error("Lỗi khi lấy tổng quỹ lương:", xhr.responseText);
                    }
                });

                // Gọi API lấy doanh thu
                $.ajax({
                    url: `/admin/statistics/finance/tong-doanh-thu/${year}`,
                    type: 'GET',
                    success: function(res) {
                        renderTongDoanhThu(res.data); // res.data là array 12 tháng
                    },
                    error: function(xhr) {
                        console.error("Lỗi khi lấy doanh thu:", xhr.responseText);
                    }
                });

                // Gọi API lấy học phí
                $.ajax({
                    url: `/admin/statistics/finance/hoc-phi-lop/${year}`,
                    type: 'GET',
                    success: function(res) {
                        RenderHocPhi(res.data.data);
                        $('#pagination-hocphi').html(res.pagination);
                    },
                    error: function(xhr) {
                        console.error("Lỗi khi lấy học phí:", xhr.responseText);
                    }
                });

                // Gọi API lấy lãi lỗ
                $.ajax({
                    url: `/admin/statistics/finance/lai-lo/${year}`,
                    type: 'GET',
                    success: function(res) {
                        console.log(res.data);
                        renderLaiLo(res.data);
                    },
                    error: function(xhr) {
                        console.error("Lỗi khi lấy lãi lỗ:", xhr.responseText);
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


            function renderTongQuyLuong(data = null) {
                var categories = ["Th1", "Th2", "Th3", "Th4", "Th5", "Th6", "Th7", "Th8", "Th9", "Th10", "Th11",
                    "Th12"
                ];
                var seriesData = data ?? [];

                var optionsArea = {
                    chart: {
                        type: 'area',
                        height: 420,
                        stacked: true,
                        toolbar: {
                            show: true
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 2
                    },
                    series: seriesData,
                    xaxis: {
                        categories: categories,
                        title: {
                            text: "Tháng"
                        }
                    },
                    yaxis: {
                        title: {
                            text: "Tổng quỹ lương (triệu VNĐ)"
                        },
                        labels: {
                            formatter: function(val) {
                                // Định dạng số theo locale VN và thêm hậu tố
                                return val.toLocaleString('vi-VN') + " tr";
                            }
                        }
                    },
                    tooltip: {
                        shared: true,
                        y: {
                            formatter: function(val) {
                                return val.toLocaleString('vi-VN') + " VNĐ";
                            }
                        }
                    },
                    legend: {
                        position: 'top'
                    },
                    colors: ['#1E90FF', '#22c55e', '#f97316', '#e11d48', '#9333ea'],
                    grid: {
                        borderColor: "#f1f3fa"
                    }
                };

                if (CharTongLuong !== null) {
                    CharTongLuong.destroy();
                }
                CharTongLuong = new ApexCharts(document.querySelector("#salary-area-chart"), optionsArea);
                CharTongLuong.render();
            }
            // Xuất báo cáo tổng quỹ lương
            $(document).on('click', '.btn-export-salary-area-chart', function(e) {
                e.preventDefault();

                let year = new Date().getFullYear();
                console.log(year);
                Swal.fire({
                    title: 'Đang xử lý...',
                    text: 'Vui lòng chờ trong giây lát',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: `/admin/statistics/finance/xuat-bao-cao/tong-quy-luong/${year}`,
                    type: 'GET',
                    xhrFields: {
                        responseType: 'blob' // nhận file Excel
                    },
                    success: function(blob) {
                        Swal.close(); // tắt loading

                        // Tải file xuống
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = `bao_cao_quy_luong_${year}.xlsx`;
                        document.body.appendChild(a);
                        a.click();
                        a.remove();
                    },
                    error: function(xhr) {
                        Swal.fire("Lỗi", "Không thể xuất báo cáo", "error");
                        console.error("Lỗi khi lấy tổng quỹ lương:", xhr.responseText);
                    }
                });
            });



            function renderTongDoanhThu(data = null) {
                var options = {
                    chart: {
                        type: 'line',
                        height: 400,
                        toolbar: {
                            show: true
                        }
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 3
                    },
                    markers: {
                        size: 5
                    },
                    dataLabels: {
                        enabled: false
                    },
                    series: [{
                        name: "Doanh thu",
                        data: data ?? []
                    }],
                    xaxis: {
                        categories: ["Th1", "Th2", "Th3", "Th4", "Th5", "Th6", "Th7", "Th8", "Th9", "Th10",
                            "Th11", "Th12"
                        ],
                        title: {
                            text: "Tháng"
                        }
                    },
                    yaxis: {
                        title: {
                            text: "Doanh thu (triệu VNĐ)"
                        },
                        labels: {
                            formatter: function(val) {
                                return val.toLocaleString('vi-VN') + " tr";
                            }
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return val.toLocaleString('vi-VN') + " triệu VNĐ";
                            }
                        }
                    }
                };

                if (CharDoanhThu !== null) {
                    CharDoanhThu.destroy();
                }
                CharDoanhThu = new ApexCharts(document.querySelector("#revenue-line-chart"), options);
                CharDoanhThu.render();
            }
            // Xuất báo cáo tổng doanh thu học phí
            $(document).on('click', '.btn-export-revenue-line-chart', function(e) {
                e.preventDefault();

                let year = new Date().getFullYear();
                console.log(year);
                Swal.fire({
                    title: 'Đang xử lý...',
                    text: 'Vui lòng chờ trong giây lát',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: `/admin/statistics/finance/xuat-bao-cao/doanh-thu/${year}`,
                    type: 'GET',
                    xhrFields: {
                        responseType: 'blob' // nhận file Excel
                    },
                    success: function(blob) {
                        Swal.close(); // tắt loading

                        // Tải file xuống
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = `bao_cao_doanh_thu_${year}.xlsx`;
                        document.body.appendChild(a);
                        a.click();
                        a.remove();
                    },
                    error: function(xhr) {
                        Swal.fire("Lỗi", "Không thể xuất báo cáo", "error");
                        console.error("Lỗi khi lấy tổng quỹ lương:", xhr.responseText);
                    }
                });
            });




            // Biểu đồ tình trạng đóng học phí theo lớp
            function RenderHocPhi(data = null) {

                // Lấy tên lớp
                var categories = data.map(item => item.class_name);

                // Tổng tiền (VNĐ)
                var paidData = data.map(item => parseFloat(item.total_paid_amount));
                var unpaidData = data.map(item => parseFloat(item.total_unpaid_amount));

                // Tổng số học sinh
                var paidCount = data.map(item => parseInt(item.paid_students));
                var unpaidCount = data.map(item => parseInt(item.unpaid_students));

                // Tính % theo tiền
                var totalByClass = paidData.map((val, i) => val + unpaidData[i]);
                var percentPaid = paidData.map((val, i) => ((val / totalByClass[i]) * 100).toFixed(1));
                var percentUnpaid = unpaidData.map((val, i) => ((val / totalByClass[i]) * 100).toFixed(1));

                var options = {
                    chart: {
                        type: 'bar',
                        height: 500,
                        stacked: true,
                        stackType: '100%',
                        toolbar: {
                            show: true
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            borderRadius: 4
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        formatter: function(val) {
                            return val.toFixed(1) + "%";
                        }
                    },
                    series: [{
                            name: "Đã đóng",
                            data: percentPaid
                        },
                        {
                            name: "Chưa đóng",
                            data: percentUnpaid
                        }
                    ],
                    xaxis: {
                        categories: categories,
                        title: {
                            text: "Lớp học"
                        }
                    },
                    yaxis: {
                        title: {
                            text: "Tỷ lệ (%)"
                        },
                        max: 100
                    },
                    colors: ['#22c55e', '#e11d48'],
                    tooltip: {
                        y: {
                            formatter: function(val, opts) {
                                let idx = opts.dataPointIndex;
                                let raw = opts.seriesIndex === 0 ? paidData[idx] : unpaidData[idx];
                                let count = opts.seriesIndex === 0 ? paidCount[idx] : unpaidCount[idx];

                                // format số tiền với dấu phân cách hàng nghìn
                                let formattedMoney = raw.toLocaleString('vi-VN', {
                                    minimumFractionDigits: 0
                                });

                                return `${formattedMoney} VNĐ (${val.toFixed(1)}%) | ${count} học sinh`;
                            }
                        }
                    },
                    legend: {
                        position: 'top',
                        horizontalAlign: 'center'
                    },
                    fill: {
                        opacity: 0.9
                    }
                };

                if (CharHocPhi !== null) {
                    CharHocPhi.destroy();
                }
                CharHocPhi = new ApexCharts(document.querySelector("#tuition-stacked-chart"), options);
                CharHocPhi.render();
            }
            // Xuất báo cáo tình trạng đóng học phí theo lớp
            $(document).on('click', '.btn-export-tuition-stacked-chart', function(e) {
                e.preventDefault();

                let year = new Date().getFullYear();
                console.log(year);
                Swal.fire({
                    title: 'Đang xử lý...',
                    text: 'Vui lòng chờ trong giây lát',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: `/admin/statistics/finance/xuat-bao-cao/hoc-phi-lop/${year}`,
                    type: 'GET',
                    xhrFields: {
                        responseType: 'blob' // nhận file Excel
                    },
                    success: function(blob) {
                        Swal.close(); // tắt loading

                        // Tải file xuống
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = `bao_cao_hoc_phi_theo_lop_nam_${year}.xlsx`;
                        document.body.appendChild(a);
                        a.click();
                        a.remove();
                    },
                    error: function(xhr) {
                        Swal.fire("Lỗi", "Không thể xuất báo cáo", "error");
                        console.error("Lỗi khi lấy tổng quỹ lương:", xhr.responseText);
                    }
                });
            });



            //Biểu đồ Lãi lỗ
            function renderLaiLo(data = null) {
                var months = ["Th1", "Th2", "Th3", "Th4", "Th5", "Th6", "Th7", "Th8", "Th9", "Th10", "Th11",
                    "Th12"
                ];
                var revenue = data.map(item => parseFloat(item.tong_doanh_thu)); // triệu VNĐ
                var salary = data.map(item => parseFloat(item.tong_luong_gv)); // triệu VNĐ
                var profit = revenue.map((val, idx) => val - salary[idx]);

                var options = {
                    chart: {
                        height: 420,
                        type: 'line',
                        toolbar: {
                            show: true
                        }
                    },
                    stroke: {
                        width: [0, 0, 3],
                        curve: 'smooth'
                    },
                    dataLabels: {
                        enabled: true,
                        enabledOnSeries: [2] // chỉ hiển thị label trên line
                    },
                    series: [{
                            name: 'Doanh thu',
                            type: 'column',
                            data: revenue
                        },
                        {
                            name: 'Chi phí lương',
                            type: 'column',
                            data: salary
                        },
                        {
                            name: 'Lãi/Lỗ',
                            type: 'line',
                            data: profit
                        }
                    ],
                    xaxis: {
                        categories: months,
                        title: {
                            text: "Tháng"
                        }
                    },
                    yaxis: [{
                            title: {
                                text: "Doanh thu & Chi phí (triệu VNĐ)"
                            }
                        },
                        {
                            opposite: true,
                            title: {
                                text: "Lãi/Lỗ (triệu VNĐ)"
                            }
                        }
                    ],
                    tooltip: {
                        shared: true,
                        y: {
                            formatter: function(val, opts) {
                                // format số với dấu chấm ngăn cách nghìn
                                let formatted = val.toLocaleString('vi-VN', {
                                    minimumFractionDigits: 0
                                });

                                if (opts.seriesIndex === 2) {
                                    // series Lãi/Lỗ
                                    return formatted + " triệu (" + (val >= 0 ? "Lãi" : "Lỗ") + ")";
                                }
                                return formatted + " triệu VNĐ";
                            }
                        }
                    },
                    colors: ['#1E90FF', '#e11d48',
                        '#22c55e'
                    ], // xanh dương = revenue, đỏ = lương, xanh lá = lãi
                    legend: {
                        position: 'top',
                        horizontalAlign: 'center'
                    },
                    grid: {
                        borderColor: "#f1f3fa"
                    }
                };

                // Nếu đã có chart → destroy trước khi tạo mới
                if (CharLaiLo !== null) {
                    CharLaiLo.destroy();
                }

                CharLaiLo = new ApexCharts(document.querySelector("#profit-chart"), options);
                CharLaiLo.render();
            }
            // Xuất báo cáo lãi lỗ
            $(document).on('click', '.btn-export-profit-chart', function(e) {
                e.preventDefault();

                let year = new Date().getFullYear();
                console.log(year);
                Swal.fire({
                    title: 'Đang xử lý...',
                    text: 'Vui lòng chờ trong giây lát',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: `/admin/statistics/finance/xuat-bao-cao/lai-lo/${year}`,
                    type: 'GET',
                    xhrFields: {
                        responseType: 'blob' // nhận file Excel
                    },
                    success: function(blob) {
                        Swal.close(); // tắt loading

                        // Tải file xuống
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = `bao_cao_lai_lo_nam_${year}.xlsx`;
                        document.body.appendChild(a);
                        a.click();
                        a.remove();
                    },
                    error: function(xhr) {
                        Swal.fire("Lỗi", "Không thể xuất báo cáo", "error");
                        console.error("Lỗi khi lấy tổng quỹ lương:", xhr.responseText);
                    }
                });
            });




            //Phân trang học phí
            $(document).on('click', '#pagination-hocphi a', function(e) {
                e.preventDefault();
                const url = this.href;
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        RenderHocPhi(response.data.data);
                        //Cập nhật phân trang
                        $('#pagination-hocphi').html(response.pagination);
                    },
                    error: function(xhr) {
                        console.error('Lỗi phân trang:', xhr.responseText);
                    }
                });
            });



        });
    </script>
@endpush
