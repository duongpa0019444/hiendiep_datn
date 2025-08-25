@extends('admin.admin')
@section('title', 'Quản lí thống kê')

@section('content')
    <div class="page-content">
        <div class="container-fluid ">
            <div class="d-flex justify-content-between">
                <h3>Thống kê đào tạo</h3>
                <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                    <!-- Nút chuyển năm -->
                    <button type="button" class="btn btn-sm btn-outline-secondary d-flex align-items-center" id="prevYear">
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
                    <h4 class="card-title mb-3">Số lượng học sinh đăng ký theo khóa theo tháng</h4>
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
                    <h4 class="card-title mb-3">Thống kê số buổi dạy của giáo viên theo tháng</h4>
                    <div id="teaching-chart" class="apex-charts"></div>
                </div>
            </div>


            <!-- Bubble Chart -->
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Tỷ lệ chuyên cần trung bình của giáo viên theo tháng</h4>
                    <div id="attendance-chart" class="apex-charts"></div>
                </div>

                {{-- <div class="card-body">
                    <h4 class="card-title mb-3">Giáo viên dưới chuẩn 80% theo tháng</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered text-center" id="below-standard-table">
                            <thead class="table-light">
                                <tr>
                                    <th>Tháng</th>
                                    <th>Giáo viên dưới chuẩn</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div> --}}
            </div>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Tổng hợp tình trạng lớp học</h4>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div id="class-status-pie" class="apex-charts mt-4"></div>
                        </div>
                        <div class="col-md-6">
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
                            <tbody>
                                @php
                                    $data = [
                                        'Khóa 1' => [1, 1, 0],
                                        'Khóa 2' => [1, 1, 1],
                                        'Khóa 3' => [2, 1, 1],
                                        'Khóa 4' => [3, 6, 1],
                                        'Khóa 5' => [1, 2, 1],
                                        'Khóa 6' => [0, 2, 1],
                                        'Khóa 7' => [1, 2, 0],
                                        'Khóa 8' => [1, 1, 1],
                                        'Khóa 9' => [0, 1, 2],
                                        'Khóa 10' => [0, 0, 1],
                                        'Khóa 11' => [0, 0, 0],
                                        'Khóa 12' => [0, 0, 0],
                                    ];
                                    $totals = [0, 0, 0];
                                @endphp

                                @foreach ($data as $course => $values)
                                    @php
                                        $sum = array_sum($values);
                                        $totals[0] += $values[0];
                                        $totals[1] += $values[1];
                                        $totals[2] += $values[2];
                                    @endphp
                                    <tr>
                                        <td><b>{{ $course }}</b></td>
                                        <td style="color:#3b82f6">{{ $values[0] }}</td>
                                        <td style="color:#9333ea">{{ $values[1] }}</td>
                                        <td style="color:#f97316">{{ $values[2] }}</td>
                                        <td><b>{{ $sum }}</b></td>
                                    </tr>
                                @endforeach

                                <tr class="table-secondary">
                                    <td><b>Tổng cộng lớp</b></td>
                                    <td style="color:#3b82f6"><b>{{ $totals[0] }}</b></td>
                                    <td style="color:#9333ea"><b>{{ $totals[1] }}</b></td>
                                    <td style="color:#f97316"><b>{{ $totals[2] }}</b></td>
                                    <td><b>{{ array_sum($totals) }}</b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Thống kê số lượng học sinh trong từng lớp</h4>
                    <div id="student-class-bar" class="apex-charts"></div>
                    <div class="d-flex justify-content-center mt-3">
                        <button id="prevPageClass" class="btn btn-outline-primary btn-sm me-2">⬅ Trước</button>
                        <span id="pageInfoClass"></span>
                        <button id="nextPageClass" class="btn btn-outline-primary btn-sm ms-2">Tiếp ➡</button>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Thống kê điểm trung bình các khóa học</h4>
                    <div id="avg-score-bar" class="apex-charts"></div>
                    <div class="d-flex justify-content-center mt-3">
                        <button id="prevPageCourse" class="btn btn-outline-primary btn-sm me-2">⬅ Trước</button>
                        <span id="pageInfoCourse"></span>
                        <button id="nextPageCourse" class="btn btn-outline-primary btn-sm ms-2">Tiếp ➡</button>
                    </div>
                </div>

            </div>



        </div>
    </div>


@endsection

@push('scripts')
    <script>
        // Thống kê học sinh đăng kí học theo khóa theo tháng tại trung tâm
        //
        function thongKeHsDK() {
            var months = ["Tháng 1", "Tháng 2", "Tháng 3", "Tháng 4", "Tháng 5", "Tháng 6", "Tháng 7", "Tháng 8", "Tháng 9",
                "Tháng 10", "Tháng 11", "Tháng 12"
            ];
            var seriesData = [{
                    name: "Khóa học 1",
                    data: [28, 29, 33, 36, 32, 32, 33, 34, 35, 37, 38, 30]
                },
                {
                    name: "Khóa học 2",
                    data: [12, 11, 14, 18, 17, 13, 13, 12, 11, 10, 9, 8]
                },
                {
                    name: "Khóa học 3",
                    data: [72, 71, 74, 78, 77, 73, 73, 72, 71, 70, 69, 68]
                },
                {
                    name: "Khóa học 4",
                    data: [40, 41, 44, 48, 47, 43, 43, 42, 41, 40, 39, 38]
                },
                {
                    name: "Khóa học 5",
                    data: [50, 51, 54, 58, 57, 53, 53, 52, 51, 50, 49, 48]
                },
                {
                    name: "Khóa học 6",
                    data: [10, 51, 24, 38, 87, 93, 13, 22, 41, 50, 47, 78]
                },
                {
                    name: "Khóa học 7",
                    data: [35, 36, 37, 40, 38, 39, 40, 42, 41, 39, 38, 37]
                },
                {
                    name: "Khóa học 8",
                    data: [22, 23, 25, 26, 27, 29, 28, 30, 31, 32, 33, 34]
                },
                {
                    name: "Khóa học 9",
                    data: [18, 19, 20, 22, 21, 23, 24, 26, 27, 28, 29, 30]
                },
                {
                    name: "Khóa học 10",
                    data: [12, 14, 15, 17, 18, 20, 22, 23, 24, 25, 27, 28]
                }
            ];

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
            new ApexCharts(document.querySelector("#student-enroll-chart"), options).render();

            //
            // Bảng phụ: Top 3 khóa theo tháng
            //
            function buildTop3Table() {
                let tbody = document.querySelector("#top3-table tbody");
                tbody.innerHTML = "";

                months.forEach((month, idx) => {
                    // Lấy data theo tháng idx
                    let values = seriesData.map(s => ({
                        name: s.name,
                        value: s.data[idx]
                    }));
                    // Sort giảm dần
                    values.sort((a, b) => b.value - a.value);
                    // Lấy top 3
                    let top3 = values.slice(0, 3);

                    let row = `<tr>
                <td><b>${month}</b></td>
                <td>${top3[0].name}: ${top3[0].value}</td>
                <td>${top3[1].name}: ${top3[1].value}</td>
                <td>${top3[2].name}: ${top3[2].value}</td>
            </tr>`;
                    tbody.innerHTML += row;
                });
            }

            buildTop3Table();
        }
        thongKeHsDK();



        //
        // Stacked Bar Chart: số buổi dạy theo giáo viên / tháng
        //
        var months = ["Th1", "Th2", "Th3", "Th4", "Th5", "Th6", "Th7", "Th8", "Th9", "Th10", "Th11", "Th12"];
        var seriesData = [{
                name: "GV A",
                data: [8, 10, 12, 9, 11, 6, 20, 15, 16, 12, 21, 50]
            },
            {
                name: "GV B",
                data: [6, 7, 9, 8, 10, 5, 18, 12, 10, 11, 13, 12]
            },
            {
                name: "GV C",
                data: [10, 11, 13, 12, 14, 9, 22, 16, 6, 5, 7, 6]
            },
            {
                name: "GV D",
                data: [4, 6, 5, 7, 6, 3, 10, 8, 6, 3, 10, 8]
            },
            {
                name: "GV E",
                data: [7, 8, 9, 8, 9, 4, 15, 11, 10, 12, 9, 11]
            }
        ];

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
            // annotations: {
            //     yaxis: [{
            //         y: 15,
            //         borderColor: '#ff0000',
            //         label: {
            //             borderColor: '#ff0000',
            //             style: {
            //                 color: '#fff',
            //                 background: '#ff0000'
            //             },
            //             text: 'Chuẩn 15 buổi'
            //         }
            //     }]
            // },
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

        new ApexCharts(document.querySelector("#teaching-chart"), options).render();





        //
        // Line Chart: Tỷ lệ chuyên cần giáo viên theo tháng
        //
        var months = ["Th1", "Th2", "Th3", "Th4", "Th5", "Th6", "Th7", "Th8", "Th9", "Th10", "Th11", "Th12"];
        var seriesData = [{
                name: "GV A",
                data: [90, 89, 92, 91, 93, 85, 82, 81, 80, 79, 78, 76]
            },
            {
                name: "GV B",
                data: [88, 87, 86, 90, 88, 70, 72, 71, 70, 71, 60, 55]
            },
            {
                name: "GV C",
                data: [95, 97, 94, 96, 95, 80, 82, 81, 79, 80, 70, 60]
            },
            {
                name: "GV D",
                data: [85, 86, 87, 88, 83, 20, 21, 30, 40, 50, 60, 79]
            },
            {
                name: "GV E",
                data: [80, 82, 81, 79, 80, 20, 21, 25, 40, 55, 70, 85]
            }
        ];

        // Tính trung bình toàn trung tâm
        var avg = months.map((_, i) => {
            let sum = seriesData.reduce((acc, s) => acc + s.data[i], 0);
            return (sum / seriesData.length).toFixed(1);
        });

        //
        // Biểu đồ chuyên cần giáo viên
        //
        var options = {
            chart: {
                type: 'line',
                height: 450,
                toolbar: {
                    show: true
                }
            },
            stroke: {
                width: 3,
                curve: 'smooth'
            },
            dataLabels: {
                enabled: false
            },
            series: [...seriesData, {
                name: "Trung bình trung tâm",
                data: avg
            }],
            xaxis: {
                categories: months,
                title: {
                    text: "Tháng"
                }
            },
            yaxis: {
                title: {
                    text: "Tỷ lệ chuyên cần (%)"
                },
                min: 0,
                max: 100
            },
            annotations: {
                yaxis: [{
                    y: 80,
                    borderColor: '#ff0000',
                    label: {
                        borderColor: '#ff0000',
                        style: {
                            color: '#fff',
                            background: '#ff0000'
                        },
                        text: 'Chuẩn 80%'
                    }
                }]
            },
            tooltip: {
                shared: true,
                y: {
                    formatter: function(val) {
                        return val + "% " + (val >= 80 ? "(Đạt chuẩn)" : "(Dưới chuẩn)");
                    }
                }
            },
            colors: ['#1E90FF', '#22c55e', '#f97316', '#9333ea', '#e11d48', '#000000'],
            legend: {
                position: 'top',
                horizontalAlign: 'center'
            },
            markers: {
                size: 4
            }
        };

        new ApexCharts(document.querySelector("#attendance-chart"), options).render();

        // //
        // // Bảng phụ: liệt kê GV dưới chuẩn 80% từng tháng
        // //
        // function buildBelowStandardTable() {
        //     let tbody = document.querySelector("#below-standard-table tbody");
        //     tbody.innerHTML = "";

        //     months.forEach((month, idx) => {
        //         let below = seriesData.filter(s => s.data[idx] < 80)
        //             .map(s => `${s.name} (${s.data[idx]}%)`);
        //         let row = `<tr>
    //         <td><b>${month}</b></td>
    //         <td>${below.length > 0 ? below.join(", ") : "Tất cả đạt chuẩn ✅"}</td>
    //     </tr>`;
        //         tbody.innerHTML += row;
        //     });
        // }

        // buildBelowStandardTable();




        // SIMPLE PIE CHART
        //
        // Pie chart trạng thái lớp học, khóa học 
        var pieOptions = {
            chart: {
                type: 'pie',
                height: 250,
                toolbar: {
                    show: true
                }
            },
            labels: ['Chưa bắt đầu', 'Đang diễn ra', 'Đã hoàn thành'],
            series: [{{ $totals[0] }}, {{ $totals[1] }}, {{ $totals[2] }}],
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
        new ApexCharts(document.querySelector("#class-status-pie"), pieOptions).render();

        // Top 3 khóa học
        var rawData = Object.entries(@json($data))
            .map(([k, v]) => [k, v.reduce((a, b) => a + b, 0)])
            .sort((a, b) => b[1] - a[1]);

        var top3 = rawData.slice(0, 3);
        var bottom3 = rawData.slice(-3);

        // Bar chart: Top 3 khóa nhiều lớp nhất
        var topBarOptions = {
            chart: {
                type: 'bar',
                height: 250
            },
            series: [{
                name: 'Số lớp',
                data: top3.map(d => d[1])
            }],
            xaxis: {
                categories: top3.map(d => d[0])
            },
            colors: ['#10b981'],
            dataLabels: {
                enabled: true
            }
        };
        new ApexCharts(document.querySelector("#top-courses-bar"), topBarOptions).render();

        // Bar chart: Top 3 khóa ít lớp nhất
        var bottomBarOptions = {
            chart: {
                type: 'bar',
                height: 250
            },
            series: [{
                name: 'Số lớp',
                data: bottom3.map(d => d[1])
            }],
            xaxis: {
                categories: bottom3.map(d => d[0])
            },
            colors: ['#ef4444'],
            dataLabels: {
                enabled: true
            }
        };
        new ApexCharts(document.querySelector("#bottom-courses-bar"), bottomBarOptions).render();




        // Tổng học sinh trong lớp học
        //
        // Giả sử có 50 lớp
        function tongHsClass() {
            const pageSize = 10; // số lớp mỗi trang hiển thị trên biểu đồ
            let currentPage = 1; // trang hiện tại
            let labels = []; // mảng tên lớp
            let counts = []; // mảng số học sinh
            let chart = null; // instance của ApexCharts


            async function loadData() {
                const res = await fetch('/admin/sl-hs-theo-lop');
                const data = await res.json();
                labels = data.labels; // truyển dữ liệu trả về vào mảng labels
                counts = data.counts; // truyển dữ liệu trả về vào mảng counts
                renderChart(currentPage);
            }

            // Hàm vẽ lại biểu đồ theo page hiện tại
            function renderChart(page) {
                const start = (page - 1) * pageSize; // Cắt dữ liệu theo page hiện tại (mỗi trang 10 lớp)
                const end = start + pageSize;
                const slicedNames = labels.slice(start, end);
                const slicedCounts = counts.slice(start, end);

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
                        data: slicedCounts
                    }],
                    xaxis: {
                        categories: slicedNames,
                        title: {
                            text: 'Số học sinh'
                        }
                    },
                    colors: '#3b82f6',
                    dataLabels: {
                        enabled: true
                    },
                    legend: {
                        show: false,
                    },
                };

                // Xóa biểu đồ cũ và render biểu đồ mới
                document.querySelector('#student-class-bar').innerHTML = '';
                chart = new ApexCharts(document.querySelector('#student-class-bar'), options);
                chart.render();

                // Cập nhật số trang
                document.getElementById('pageInfoClass').innerText =
                    `Trang ${page}/${Math.ceil(labels.length / pageSize)}`;
            }
            // Disable nút nếu chỉ có 1 trang hoặc ở đầu/cuối
            document.getElementById('prevPageClass').disabled = currentPage <= 1;
            document.getElementById('nextPageClass').disabled = currentPage >= Math.ceil(labels.length / pageSize);

            // Event click nút Prev
            document.getElementById('prevPageClass').addEventListener('click', () => {
                if (currentPage > 1) {
                    currentPage--;
                    renderChart(currentPage);
                }
            });

            // Event click nút Next
            document.getElementById('nextPageClass').addEventListener('click', () => {
                if (currentPage < Math.ceil(labels.length / pageSize)) {
                    currentPage++;
                    renderChart(currentPage);
                }
            });


            // Gọi API khi load trang
            loadData();
        }
        tongHsClass();




        // Điểm trung bình các khóa học
        // Tạo 50 khóa học

        function diemTbLop() {
            const benchmark = 5.0;
            const pageSize = 10;
            let currentPage = 1;
            let labels = [];
            let scores = [];

            async function loadData() {
                const res = await fetch('/admin/diem-tb-theo-lop');
                const data = await res.json();
                labels = data.labels;
                scores = data.scores;
                renderChart(currentPage);
            }

            function renderChart(page) {
                const start = (page - 1) * pageSize;
                const end = start + pageSize;
                const pageLabels = labels.slice(start, end);
                const pageScores = scores.slice(start, end);

                const maxScore = Math.max(...pageScores);
                const minScore = Math.min(...pageScores);
                const colors = pageScores.map(s => {
                    if (s === maxScore) return "#10b981";
                    if (s === minScore) return "#ef4444";
                    return s >= benchmark ? "#3b82f6" : "#f59e0b";
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
                        data: pageScores
                    }],
                    xaxis: {
                        categories: pageLabels,
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
                    fill: {
                        colors: colors
                    },
                    dataLabels: {
                        enabled: true,
                        formatter: val => val.toFixed(1)
                    },
                    annotations: {
                        yaxis: [{
                            y: benchmark,
                            borderColor: '#e11d48',
                            label: {
                                borderColor: '#e11d48',
                                style: {
                                    color: '#fff',
                                    background: '#e11d48'
                                },
                                text: `Chuẩn ${benchmark}`
                            }
                        }]
                    }
                };

                document.querySelector("#avg-score-bar").innerHTML = "";
                new ApexCharts(document.querySelector("#avg-score-bar"), options).render();

                const totalPages = Math.ceil(labels.length / pageSize);
                document.getElementById("pageInfoCourse").innerText = `Trang ${page}/${totalPages}`;
                document.getElementById("prevPageCourse").disabled = page <= 1;
                document.getElementById("nextPageCourse").disabled = page >= totalPages;
            }

            document.getElementById("prevPageCourse").addEventListener("click", () => {
                if (currentPage > 1) {
                    currentPage--;
                    renderChart(currentPage);
                }
            });
            document.getElementById("nextPageCourse").addEventListener("click", () => {
                if (currentPage < Math.ceil(labels.length / pageSize)) {
                    currentPage++;
                    renderChart(currentPage);
                }
            });

            loadData();
        }
        diemTbLop();
    </script>
@endpush
