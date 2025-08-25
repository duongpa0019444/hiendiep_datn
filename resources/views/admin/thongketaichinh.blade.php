@extends('admin.admin')
@section('title', 'Quản lí thống kê')

@section('content')
    <div class="page-content">
        <div class="container-fluid ">

            <!-- Line Chart (Lương giáo viên) -->
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Tổng quỹ lương giáo viên theo tháng</h4>
                    <div id="salary-area-chart" class="apex-charts"></div>
                </div>
            </div>
            {{-- Tong doanh thu --}}
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Tổng doanh thu học phí theo tháng</h4>
                    <div id="revenue-line-chart" class="apex-charts"></div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">Tình trạng đóng học phí theo tháng</h4>
                    <div id="tuition-stacked-chart" class="apex-charts"></div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-3">So sánh Doanh thu & Chi phí lương (Lãi/Lỗ)</h4>
                    <div id="profit-chart" class="apex-charts"></div>
                </div>
            </div>

        </div>
    </div>


@endsection
@push('scripts')
    <script>
        
        //
        // Line chart: lương giáo viên theo tháng
        //
        // --- Data mẫu ---
        // --- Data mẫu ---
        var categories = ["Th1", "Th2", "Th3", "Th4", "Th5", "Th6", "Th7", "Th8", "Th9", "Th10", "Th11", "Th12"];
        var seriesData = [{
                name: "GV A",
                data: [90, 92, 94, 93, 95, 60, 61, 59, 58, 57, 60, 62]
            },
            {
                name: "GV B",
                data: [85, 83, 84, 86, 80, 20, 21, 30, 40, 50, 60, 79]
            },
            {
                name: "GV C",
                data: [88, 87, 89, 90, 88, 70, 72, 71, 69, 70, 60, 50]
            },
            {
                name: "GV D",
                data: [95, 96, 94, 95, 97, 80, 82, 81, 79, 80, 60, 40]
            },
            {
                name: "GV E",
                data: [80, 82, 81, 79, 80, 60, 62, 61, 59, 60, 50, 30]
            }
        ];

        //
        // Tong doah thu
        // 
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
                data: [120, 150, 180, 220, 200, 250, 270, 300, 280, 260, 310, 330] // triệu VNĐ
            }],
            xaxis: {
                categories: ["Th1", "Th2", "Th3", "Th4", "Th5", "Th6", "Th7", "Th8", "Th9", "Th10", "Th11", "Th12"],
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
                        return val + " tr"
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val + " triệu VNĐ"
                    }
                }
            },

        };

        new ApexCharts(document.querySelector("#revenue-line-chart"), options).render();

        // Biểu đồ tình trạng đóng học phí theo tháng
        var paidData = [100, 120, 130, 140, 150, 160, 170, 180, 175, 160, 190, 200]; // triệu VNĐ
        var unpaidData = [20, 15, 10, 12, 18, 22, 15, 10, 8, 12, 14, 18]; // triệu VNĐ
        var categories = ["Th1", "Th2", "Th3", "Th4", "Th5", "Th6", "Th7", "Th8", "Th9", "Th10", "Th11", "Th12"];

        // Tính % theo tháng
        var totalByMonth = paidData.map((val, i) => val + unpaidData[i]);
        var percentPaid = paidData.map((val, i) => ((val / totalByMonth[i]) * 100).toFixed(1));
        var percentUnpaid = unpaidData.map((val, i) => ((val / totalByMonth[i]) * 100).toFixed(1));

        var options = {
            chart: {
                type: 'bar',
                height: 420,
                stacked: true,
                stackType: '100%',
                toolbar: {
                    show: true
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 4
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return val.toFixed(1) + "%"; // Hiển thị % trên cột
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
                    text: "Tháng"
                }
            },
            yaxis: {
                title: {
                    text: "Tỷ lệ (%)"
                },
                max: 100
            },
            colors: ['#22c55e', '#e11d48'], // xanh = đã đóng, đỏ = chưa đóng
            tooltip: {
                y: {
                    formatter: function(val, opts) {
                        let monthIdx = opts.dataPointIndex;
                        let label = opts.seriesIndex === 0 ? "Đã đóng" : "Chưa đóng";
                        let rawValue = opts.seriesIndex === 0 ? paidData[monthIdx] : unpaidData[monthIdx];
                        return label + ": " + rawValue + " triệu (" + val.toFixed(1) + "%)";
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

        new ApexCharts(document.querySelector("#tuition-stacked-chart"), options).render();

        //
        // Stacked Area Chart (Tổng quỹ lương)
        //
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
                }
            },
            tooltip: {
                shared: true,
                y: {
                    formatter: (val) => val + " tr"
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
        new ApexCharts(document.querySelector("#salary-area-chart"), optionsArea).render();


        // Lãi lỗ
        var months = ["Th1", "Th2", "Th3", "Th4", "Th5", "Th6", "Th7", "Th8", "Th9", "Th10", "Th11", "Th12"];
        var revenue = [120, 140, 150, 160, 180, 190, 200, 210, 220, 200, 230, 240]; // triệu VNĐ
        var salary = [80, 90, 95, 100, 110, 120, 130, 150, 160, 170, 180, 200]; // triệu VNĐ
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
                enabledOnSeries: [2]
            }, // chỉ hiển thị label trên line
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
                        if (opts.seriesIndex === 2) {
                            return val + " triệu (" + (val >= 0 ? "Lãi" : "Lỗ") + ")";
                        }
                        return val + " triệu VNĐ";
                    }
                }
            },
            colors: ['#1E90FF', '#e11d48', '#22c55e'], // xanh dương = revenue, đỏ = lương, xanh lá = lãi
            legend: {
                position: 'top',
                horizontalAlign: 'center'
            },
            grid: {
                borderColor: "#f1f3fa"
            }
        };

        new ApexCharts(document.querySelector("#profit-chart"), options).render();
    </script>
@endpush
