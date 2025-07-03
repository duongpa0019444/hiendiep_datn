@extends('client.accounts.information')

@section('content-information')
    <div id="dashboard" class="content-section active mb-4">
        <h2 class="fs-5">Chào mừng {{ Auth::user()->name }} quay trở lại!</h2>
        <p>Ở đây bạn có thể xem thông tin lịch, điểm số, làm bài quiz và quản lý thông tin cá nhân.</p>

        <!-- Notifications Section -->
        <div class="card mb-5">
            <div class="card-header">Thông Báo</div>
            <div class="card-body">
                <ul class="list-group">
                    @if (count($unPaymentInfo) > 0)
                        <li class="list-group-item notification-item d-flex justify-content-between align-items-center flex-wrap"
                            id="infomation-payment" style="cursor: pointer">
                            <div class="notification-content">
                                <strong class="text-danger">Thông báo đóng học phí<i class="fas fa-qrcode"></i></strong>
                                <p class="mb-0 text-muted">Vui lòng thanh toán học phí cho khóa hiện tại trước ngày
                                    {{ now()->addDays(7)->format('d/m/Y') }}.</p>
                            </div>
                            <span class="qr-code-button text-primary btn-showQr-payment">Hoàn thành <i
                                    class='fi fi-ss-QR'></i></span>
                        </li>
                    @endif

                    <li class="list-group-item notification-item">
                        <div class="notification-content">
                            <strong>Thay đổi lịch học</strong> - {{ now()->subDays(2)->format('d/m/Y') }}
                            <p class="mb-0 text-muted">Lớp A1-1 sẽ chuyển sang giờ học mới: Thứ 3, 5, 7 - 18:00-20:00.</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Current Class Section -->
        <div class="row mb-2">
            <div class="col-md-6 mb-2">
                <div class="card current-class">
                    <div class="card-header">Lớp Đang Học</div>
                    <div class="card-body">
                        <h5 class="card-title">Lớp {{ $className ?? 'A1-1' }}
                            <span class="badge badge-pending ms-2">Đang học</span>
                        </h5>
                        <p class="card-text">Khóa học: <strong>{{ $courseName ?? 'Tiếng Anh Cơ Bản' }}</strong></p>
                        <p class="card-text">Giảng viên: {{ $teacherName ?? 'Nguyễn Văn A' }}</p>
                        <p class="card-text">Thời gian: {{ $schedule ?? 'Thứ 2, 4, 6 - 18:00-20:00' }}</p>
                        <a href="#" class="btn-infomation-myaccount btn-sm mt-3">Xem chi tiết</a>

                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card completed-class">
                    <div class="card-header">Lớp Đã Hoàn Thành</div>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <strong>Lớp A0-1</strong> - Hoàn thành: {{ $completedDate1 ?? '15/10/2024' }}
                                <span class="badge badge-completed float-end">Hoàn thành</span>
                            </li>
                            <li class="list-group-item">
                                <strong>Lớp A0-2</strong> - Hoàn thành: {{ $completedDate2 ?? '20/12/2024' }}
                                <span class="badge badge-completed float-end">Hoàn thành</span>
                            </li>

                        </ul>
                        <a href="#" class="btn-infomation-myaccount btn-sm mt-3">Xem tất cả</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
@push('script')
    <script>
        //Hàm render dữ liệu thông tin thanh toán trong modal thông báo
        function renderPaymentInfo() {
            $.ajax({
                url: 'student/course-payments/infomation',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    let html = response.map((item, index) => `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.course?.name ?? ''}</td>
                            <td>${item.class?.name ?? ''}</td>
                            <td>${formatCurrency(item.amount)} VNĐ</td>
                        </tr>
                    `).join('');

                    $('#body-infomation-payment-student').html(html);
                },
                error: function(xhr, status, error) {
                    console.error('Lỗi khi tải dữ liệu:', error);
                }
            });
        }



        $("#infomation-payment").on('click', function() {
            renderPaymentInfo();
            showModal('customModal');
        });

        // Hàm định dạng số tiền theo kiểu VNĐ
        function formatCurrency(amount) {
            if (!amount) return '0 VNĐ';
            return Number(amount).toLocaleString('vi-VN');
        }



        //kiểm tra xem có thông báo thanh toán hay khôn g, nếu có thì hiển thị modal
        const infomationPayment = $("#infomation-payment");
        if(infomationPayment.length > 0){
            // Nếu chưa từng hiển thị, thì hiển thị modal thông báo đóng học phí
            if (!localStorage.getItem('customModalShown')) {
                renderPaymentInfo();
                showModal('customModal');
                localStorage.setItem('customModalShown', 'true');
            }
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            modal.classList.remove('d-block');
            modal.style.display = 'none';
        }

        function showModal(id) {
            const modal = document.getElementById(id);
            modal.classList.add('d-block');
            modal.style.display = 'block';
        }




        $('.btn-close-qr').on('click', function() {
            closeModal('qrModal');
            renderPaymentInfo();
            showModal('customModal');
        })


        // Xử lý thanh toán -------
        const MY_BANK = {
            BANK_ID: "MB",
            ACCOUNT_NO: "89101712200541"
        };

        let intervalId;

        // Hàm mã hóa HTML để ngăn XSS
        const escapeHTML = (str) => {
            const div = document.createElement('div');
            div.textContent = str;
            return div.innerHTML;
        };


        const qrModal = document.getElementById('qrModal');
        const qrCode = document.getElementById('qrCode');
        const qrContent = document.getElementById('qrContent');
        const qrAmount = document.getElementById('qrAmount');

        //xử lý khi click vào nút xem mã qr
        $('.btn-showQr-payment').on('click', function(e) {
            console.log('clicked');
            if (e.target.classList.contains('btn-showQr-payment')) {
                $.ajax({
                    url: 'student/course-payments/infomation',
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {

                        console.log(response);
                        if (Array.isArray(response) && response.length > 0) {
                            const timestamp = Date.now();

                            // Giả sử tất cả cùng một student
                            const studentId = response[0].student_id;

                            // Gộp các class và course id
                            const combinedInfo = response.map(item =>
                                `CL${item.class.id}KH${item.course.id}`).join('');

                            // Tổng tiền
                            const totalAmount = response.reduce((sum, item) => {
                                return sum + parseFloat(item.amount);
                            }, 0);


                            // PaidContent gộp
                            const paidContent = `HS${studentId}${combinedInfo}${timestamp}`;

                            // Tạo QR
                            const QR =
                                `https://img.vietqr.io/image/${MY_BANK.BANK_ID}-${MY_BANK.ACCOUNT_NO}-qr_only.png?amount=${totalAmount}&addInfo=${encodeURIComponent(paidContent)}`;

                            // Gán vào UI
                            qrContent.textContent = paidContent;
                            qrAmount.textContent = `${totalAmount.toLocaleString('vi-VN')} VND`;
                            qrCode.src = QR;
                            qrCode.alt = `Mã QR thanh toán nhiều khóa học`;
                            // Bắt đầu check thanh toán
                            if (intervalId) clearInterval(intervalId);
                            intervalId = setInterval(() => checkPaid(paidContent, totalAmount), 5000);

                        } else {
                            alert('Không có khóa học nào chưa thanh toán.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Lỗi khi tải dữ liệu:', error);
                    }
                });

            }

            closeModal('customModal');

            showModal('qrModal');


        });



        async function checkPaid(paidContent, paidPrice) {
            try {
                const response = await fetch(
                    'https://script.googleusercontent.com/macros/echo?user_content_key=AehSKLj3kc-5HM1eRaj9XJ0DX8CIxoqUhKc42ouELAzJz6lT2bW4X9qJYKNYiqKHO5mqKu_714NeYNXmSmBo-Gf_nDUCWj5Cro5VmJ4Wvxpb_aTGdgcYvhi7XTUewV5ARyI2DxxRw1_-ilcM_nXlAWPmda_EHcmSh3b7fJUsCgSpmiD2sPn3_jfkXG6ZQms1FhB_c86iqHoTM0X3smMEj8mU1pclJQYG66I6J7Dumt0cLvSIOLeva5ucitQS-HqsizzioG8YctNbBTSP8JCn4eUm085ZR_FaSylveWuYw8au&lib=MBlCDu2vJDrNVNiMMN8g_X253yzHoFhMw'
                );
                if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                const data = await response.json();
                console.log(data);

                if (!data?.data?.length) {
                    console.log("Chưa thấy thanh toán ok.");
                    return;
                }

                const matched = data.data.find(item =>
                    item['Mô tả']?.includes(paidContent) && item['Giá trị'] >= paidPrice
                );

                if (matched) {
                    clearInterval(intervalId);

                    // Ẩn modal
                    const qrModal = document.getElementById('qrModal');
                    const modalInstance = bootstrap.Modal.getInstance(qrModal);
                    closeModal('qrModal');

                    $.ajax({
                        url: 'student/course-payments/updatePayment',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: {
                            paidContent: paidContent,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            console.log(response);
                        }
                    });

                    // Hiển thị popup demo
                    showAlert('Thanh toán thành công!',
                        'Cảm ơn bạn đã thanh toán. Giao dịch của bạn đã được xử lý thành công.', () => {
                            console.log('Đã đóng popup');
                        });



                } else {
                    console.log("Chưa thấy thanh toán.");
                }
            } catch (error) {
                console.error("Fetch error:", error);
            }
        }
    </script>
@endpush
