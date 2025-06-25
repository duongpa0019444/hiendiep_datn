<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn học phí</title>
    <!-- Custom CSS -->
    <style>
        @font-face {
            font-family: 'DejaVu Sans';
            font-style: normal;
            font-weight: normal;
            src: url('https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/fonts/DejaVuSans.ttf') format('truetype');
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #000;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        .invoice-container {
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
        }

        .invoice-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .invoice-header img {
            max-width: 150px;
        }

        .invoice-details,
        .student-details {
            margin-bottom: 20px;
        }

        .print-btn {
            display: block;
            margin: 20px auto;
            padding: 8px 16px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .print-btn:hover {
            background-color: #0056b3;
        }

        @media print {
            .print-btn {
                display: none;
            }

            .invoice-container {
                border: none;
                margin: 0;
                padding: 0;
            }
        }

        /* Grid layout for student details */
        .grid-container {
            display: flex;
            flex-wrap: wrap;
            margin-left: -15px;
            margin-right: -15px;
        }

        .grid-item {
            flex: 1 1;
            padding: 0 15px;
            box-sizing: border-box;
        }

        @media (min-width: 768px) {
            .grid-item {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }

        /* Table styles */
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #dee2e6;
        }

        .invoice-table th,
        .invoice-table td {
            border: 1px solid #dee2e6;
            padding: 8px;
            vertical-align: middle;
            font-size: 14px;
        }

        .invoice-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .invoice-table tfoot th {
            background-color: #f8f9fa;
        }

        .text-right {
            text-align: right;
        }

        /* Headings */
        h3 {
            font-size: 20px;
            margin: 8px 0;
            font-weight: bold;
        }

        h4 {
            font-size: 18px;
            margin: 0 0 8px;
            font-weight: bold;
        }

        h5 {
            font-size: 16px;
            margin: 0 0 8px;
            font-weight: bold;
        }

        /* HR */
        hr {
            border: 0;
            border-top: 1px solid #dee2e6;
            margin: 16px 0;
        }

        /* Paragraphs */
        p {
            margin: 0 0 4px;
            font-size: 14px;
        }

        /* Text styles */
        strong {
            font-weight: bold;
        }

        .invoice-footer {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <h3>TRUNG TÂM ĐÀO TẠO HIEN DIEP</h3>
            <p>Địa chỉ: 123 Đường ABC, Quận 1, TP. Hồ Chí Minh</p>
            <p>Hotline: 0123 456 789 | Email: contact@hiendiep.edu.vn</p>
            <hr>
            <h4>HÓA ĐƠN HỌC PHÍ</h4>
            <p>

                <strong>Ngày xuất:</strong> {{ now()->format('d/m/Y') }}
            </p>

        </div>

        <!-- Student Details -->
        <div class="student-details">
            <h5>Thông tin học viên</h5>
            <div class="grid-container">
                <div class="grid-item">
                    <p><strong>Họ và tên:</strong> {{ $payment->user->name }}</p>
                </div>
                <div class="grid-item">
                    <p><strong>Số điện thoại:</strong> {{ $payment->user->phone ?? 'Chưa cập nhật' }}</p>
                    <p><strong>Email:</strong> {{ $payment->user->email ?? 'Chưa cập nhật' }}</p>
                </div>
            </div>
        </div>

        <!-- Invoice Details -->
        <div class="invoice-details">
            <h5>Chi tiết thanh toán</h5>
            <table class="invoice-table">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Nội dung</th>
                        <th>Số tiền (VNĐ)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>
                            <strong>Khóa học:</strong> {{ $payment->course->name ?? 'Chưa xác định' }}<br>
                            <strong>Lớp:</strong> {{ $payment->class->name ?? 'Chưa xác định' }}<br>
                            <strong>Mã thanh toán:</strong> {{ $payment->id }}
                        </td>
                        <td class="text-right">{{ number_format($payment->amount, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2" class="text-right">Tổng cộng:</th>
                        <th class="text-right">{{ number_format($payment->amount, 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Payment Info -->
        <div class="payment-info">
            <h5>Thông tin thanh toán</h5>
            <p><strong>Phương thức:</strong>
                {{ $payment->method == 'Cash' ? 'Tiền mặt' : ($payment->method == 'Bank Transfer' ? 'Chuyển khoản' : '') }}
            </p>
            <p><strong>Ngày thanh toán:</strong> {{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}
            </p>
            <p><strong>Ghi chú:</strong> {{ $payment->note ?? '' }}</p>
        </div>

        <!-- Footer -->
        <div class="invoice-footer text-center mt-4">
            <p><strong>Cảm ơn quý khách đã sử dụng dịch vụ của chúng tôi!</strong></p>
            <p>Trung tâm Hien Diep</p>
        </div>


    </div>
</body>

</html>
