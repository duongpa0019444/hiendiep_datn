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

                 <div class="card-body">
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
                </div>
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
                                    <th style="color:#3b82f6">Chưa bắt đầu</th>
                                    <th style="color:#9333ea">Đang diễn ra</th>
                                    <th style="color:#f97316">Đã hoàn thành</th>
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
                                    <td><b>Tổng cộng</b></td>
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
                    <h5 class="card-title anchor" id="datalables">
                        Biểu đồ thống kế số lượng học sinh trong từng lớp<a class="anchor-link" href="#datalables">#</a>
                    </h5>

                    <div id="datalables-bar" class="apex-charts"></div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title anchor" id="distributed">Biểu đồ thống kê điểm trung bình của các lớp học</h4>
                    <div dir="ltr">
                        <div id="distributed-column" class="apex-charts"></div>
                    </div>
                </div>
                <!-- end card body-->
            </div>



        </div>
    </div>


@endsection

@push('scripts')
    <script>
        // Thống kê học sinh đăng kí học theo khóa theo tháng tại trung tâm
        //
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
            colors: ["#ef5f5f", "#22c55e", "#3b82f6", "#fbbf24", "#6366f1", "#a855f7", "#d97706", "#ff8c00", "#ff5722",
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
        // Biểu đồ
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

        //
        // Bảng phụ: liệt kê GV dưới chuẩn 80% từng tháng
        //
        function buildBelowStandardTable() {
            let tbody = document.querySelector("#below-standard-table tbody");
            tbody.innerHTML = "";

            months.forEach((month, idx) => {
                let below = seriesData.filter(s => s.data[idx] < 80)
                    .map(s => `${s.name} (${s.data[idx]}%)`);
                let row = `<tr>
                <td><b>${month}</b></td>
                <td>${below.length > 0 ? below.join(", ") : "Tất cả đạt chuẩn ✅"}</td>
            </tr>`;
                tbody.innerHTML += row;
            });
        }

        buildBelowStandardTable();


        // SIMPLE PIE CHART
        //
        // Pie chart trạng thái toàn trung tâm
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


        // COLUMN CHART WITH DATALABELS
        //
        var colors = ["#4ecac2"];
        var options = {
            chart: {
                height: 380,
                type: 'bar',
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    borderRadius: 10,
                    dataLabels: {
                        position: 'top', // top, center, bottom
                    },
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return val + "";
                },
                offsetY: -25,
                style: {
                    fontSize: '12px',
                    colors: ["#304758"]
                }
            },
            colors: colors,
            legend: {
                show: true,
                horizontalAlign: "center",
                offsetX: 0,
                offsetY: -5,
            },
            series: [{
                name: 'Số lượng lớp',
                data: [2, 3, 4, 10, 4, 3, 3, 2, 1, 0, 0, 0]
            }],
            xaxis: {
                categories: ["Khóa học 1", "Khóa học 2", "Khóa học 3", "Khóa học 4", "Khóa học 5", "Khóa học 6",
                    "Khóa học 7", "Khóa học 8", "Khóa học 9", "Khóa học 10", "Khóa học 11", "Khóa học 12"
                ],
                position: 'top',
                labels: {
                    offsetY: 0,
                },
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                },
                crosshairs: {
                    fill: {
                        type: 'gradient',
                        gradient: {
                            colorFrom: '#D8E3F0',
                            colorTo: '#BED1E6',
                            stops: [0, 100],
                            opacityFrom: 0.6,
                            opacityTo: 0.5,
                        }
                    }
                },
                tooltip: {
                    enabled: true,
                    offsetY: -10,
                }
            },
            fill: {
                gradient: {
                    enabled: false,
                    shade: 'light',
                    type: "horizontal",
                    shadeIntensity: 0.25,
                    gradientToColors: undefined,
                    inverseColors: true,
                    opacityFrom: 1,
                    opacityTo: 1,
                    stops: [50, 0, 100, 100]
                },
            },
            yaxis: {
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false,
                },
                labels: {
                    show: false,
                    formatter: function(val) {
                        return val + "";
                    }
                }

            },
            title: {
                text: 'Số lượng lớp theo khóa học',
                floating: true,
                offsetY: 360,
                align: 'center',
                style: {
                    color: '#444'
                }
            },
            grid: {
                row: {
                    colors: ['transparent', 'transparent'], // takes an array which will be repeated on columns
                    opacity: 0.2
                },
                borderColor: '#f1f3fa'
            }
        }
        var chart = new ApexCharts(
            document.querySelector("#datalabels-column"),
            options
        );
        chart.render();


        // CUSTOM DATALABELS BAR
        //

        var colors = [
            "#1c84ee",
            "#53389f",
            "#7f56da",
            "#ff86c8",
            "#ef5f5f",
            "#ff6c2f",
            "#f9b931",
            "#22c55e",
            "#040505",
            "#4ecac2",
        ];
        var options = {
            chart: {
                height: 500,
                type: "bar",
                toolbar: {
                    show: false,
                },
            },
            plotOptions: {
                bar: {
                    barHeight: "100%",
                    distributed: true,
                    horizontal: true,
                    dataLabels: {
                        position: "bottom",
                    },
                },
            },
            colors: colors,
            dataLabels: {
                enabled: true,
                textAnchor: "start",
                style: {
                    colors: ["#fff"],
                },
                formatter: function(val, opt) {
                    return opt.w.globals.labels[opt.dataPointIndex] + ":  " + val;
                },
                offsetX: 0,
                dropShadow: {
                    enabled: false,
                },
            },
            series: [{
                data: [400, 430, 448, 470, 540, 580, 690, 1100, 1200, 1380],
            }, ],
            stroke: {
                width: 0,
                colors: ["#fff"],
            },
            xaxis: {
                categories: [
                    "Lớp học 1",
                    "Lớp học 2",
                    "Lớp học 3",
                    "Lớp học 4",
                    "Lớp học 5",
                    "Lớp học 6",
                    "Lớp học 7",
                    "Lớp học 8",
                    "Lớp học 9",
                    "Lớp học 10",

                ],
            },
            yaxis: {
                labels: {
                    show: false,
                },
            },
            grid: {
                borderColor: "#f1f3fa",
            },

            tooltip: {
                theme: "dark",
                x: {
                    show: false,
                },
                y: {
                    title: {
                        formatter: function() {
                            return "";
                        },
                    },
                },
            },
        };

        var chart = new ApexCharts(document.querySelector("#datalables-bar"), options);

        chart.render();


        //
        // DISTRIBUTED COLUMN CHART
        //
        // var apiData = [{
        //         class: "Lớp A1",
        //         avg: 7.5
        //     },
        //     {
        //         class: "Lớp Summer 2025",
        //         avg: 8.2
        //     },
        //     {
        //         class: "Toeic Beginner",
        //         avg: 6.9
        //     },
        //     {
        //         class: "IELTS Prep",
        //         avg: 8.8
        //     },
        //     {
        //         class: "Giao tiếp A2",
        //         avg: 5.9
        //     },
        //     {
        //         class: "Kids English",
        //         avg: 7.1
        //     },
        //     {
        //         class: "Lớp B1",
        //         avg: 6.3
        //     },
        //     {
        //         class: "Summer Kids",
        //         avg: 9.0
        //     },
        //     {
        //         class: "Lớp C1",
        //         avg: 7.8
        //     },
        //     {
        //         class: "Toeic Advanced 1",
        //         avg: 8.5
        //     },
        //     {
        //         class: "Toeic Advanced 2",
        //         avg: 8.5
        //     },
        //     {
        //         class: "Toeic Advanced",
        //         avg: 8.5
        //     },
        //     {
        //         class: "Toeic Advanced",
        //         avg: 8.5
        //     },
        //     {
        //         class: "Toeic Advanced",
        //         avg: 8.5
        //     },
        //     {
        //         class: "Toeic Advanced",
        //         avg: 8.5
        //     },
        //     {
        //         class: "Toeic Advanced",
        //         avg: 8.5
        //     },
        //     {
        //         class: "Toeic Advanced",
        //         avg: 8.5
        //     },
        //     {
        //         class: "Toeic Advanced",
        //         avg: 8.5
        //     },
        //     {
        //         class: "Toeic Advanced",
        //         avg: 8.5
        //     },
        //     {
        //         class: "Toeic Advanced",
        //         avg: 8.5
        //     },
        //     {
        //         class: "Toeic Advanced",
        //         avg: 8.5
        //     },
        //     {
        //         class: "Toeic Advanced",
        //         avg: 8.5
        //     },
        //     {
        //         class: "Toeic Advanced",
        //         avg: 8.5
        //     },
        //     {
        //         class: "Toeic Advanced",
        //         avg: 8.5
        //     },
        //     {
        //         class: "Toeic Advanced",
        //         avg: 8.5
        //     },
        //     {
        //         class: "Toeic Advanced",
        //         avg: 8.5
        //     },
        //     {
        //         class: "Toeic Advanced",
        //         avg: 8.5
        //     },
        //     {
        //         class: "Toeic Advanced",
        //         avg: 8.5
        //     },
        //     {
        //         class: "Toeic Advanced",
        //         avg: 8.5
        //     },
        //     {
        //         class: "Toeic Advanced",
        //         avg: 8.5
        //     },
        //     {
        //         class: "Toeic Advanced",
        //         avg: 8.5
        //     },
        //     {
        //         class: "Toeic Advanced",
        //         avg: 8.5
        //     },
        //     {
        //         class: "Toeic Advanced",
        //         avg: 8.5
        //     },
        //     {
        //         class: "Toeic Advanced",
        //         avg: 8.5
        //     },
        //     {
        //         class: "ABc",
        //         avg: 8.5
        //     },
        //     // … bạn có thể thêm tới 50+ lớp
        // ];

        // var categories = apiData.map(item => item.class);
        // var values = apiData.map(item => item.avg);


        var colors = ['#1c84ee', '#53389f', '#7f56da', '#ff86c8', '#ef5f5f', '#ff6c2f', '#f9b931', '#22c55e'];
        var options = {
            chart: {
                height: 380,
                type: 'bar',
                toolbar: {
                    show: false
                },
                events: {
                    click: function(chart, w, e) {
                        console.log(chart, w, e)
                    }
                },
            },
            colors: colors,
            plotOptions: {
                bar: {
                    columnWidth: '45%',
                    distributed: true
                }
            },
            dataLabels: {
                enabled: false,
            },
            series: [{
                data: [1.5, 2, 3, 4, 5.1, 6, 7, 8, 9.4, 10]
            }],
            yaxis: [{
                title: {
                    text: 'Điểm trung bình'
                }
            }],
            xaxis: {
                categories: ['Khóa học 1', 'Khóa học 2', 'Khóa học 3', 'Khóa học 4', 'Khóa học 5', 'Khóa học 6',
                    'Khóa học 7', 'Khóa học 8', 'Khóa học 9', 'Khóa học 10'
                ],
                labels: {
                    style: {
                        colors: colors,
                        fontSize: '14px'
                    }
                }
            },
            legend: {
                offsetY: 7
            },
            grid: {
                row: {
                    colors: ['transparent', 'transparent'], // takes an array which will be repeated on columns
                    opacity: 0.2
                },
                borderColor: '#f1f3fa'
            }
        }
        var chart = new ApexCharts(
            document.querySelector("#distributed-column"),
            options
        );
        chart.render();
    </script>
@endpush
