@extends('client.accounts.information')

@section('content-information')
    <div id="dashboard" class="content-section active mb-4">
        <h2 class="fs-5">Chào mừng {{ Auth::user()->name }} quay trở lại!</h2>

        <i class="icofont-exclamation-circle text-primary"></i> Ở đây bạn có thể xem thông tin lịch, điểm số, làm bài quiz và
        quản lý thông tin cá nhân.</p>

        <!-- Notifications Section -->
        <div class="card mb-2">
            <div class="card-header d-flex align-items-center">
                <i class="icofont-notification fs-5 me-2 text-warning"></i> Thông Báo
            </div>
            <div class="card-body">
                <ul class="list-group">
                    @if (count($unPaymentInfo) > 0)
                        <li class="list-group-item notification-item d-flex justify-content-between align-items-center flex-wrap"
                            id="infomation-payment" style="cursor: pointer">
                            <div class="notification-content d-flex align-items-start gap-2">
                                <i class="icofont-warning-alt text-danger"></i>
                                <div>
                                    <strong class="text-danger">Thông báo đóng học phí</strong>
                                    <p class="mb-0 text-muted">
                                        Vui lòng thanh toán học phí cho khóa hiện tại đúng hạn.
                                    </p>
                                </div>
                            </div>
                            <span class="qr-code-button text-primary btn-showQr-payment">
                                <i class="icofont-qr-code me-1"></i> Hoàn thành
                            </span>
                        </li>
                    @endif



                    @forelse ($notifications as $noti)
                        <li class="list-group-item notification-item">
                            <div class="notification-content">
                                <strong class="text-primary">{{ $noti->title }}</strong>
                                <div class="mb-2">{!! $noti->content !!}</div>

                            </div>
                        </li>
                    @empty
                        <li class="list-group-item">Không có thông báo nào.</li>
                    @endforelse
                </ul>
                <!-- Phân trang -->
                {{-- <div class="mt-3">
                    {{ $notifications->links() }}
                </div> --}}
            </div>
        </div>


        <!-- Current Class Section -->
        <div class="row mb-2">
            <div class="col-md-6 mb-2">
                <div class="card current-class">

                    <div class="card-header">
                        <i class="icofont-calendar me-2"></i> Lớp Đang Học
                    </div>
                    @foreach ($classes as $class)
                        <div class="card-body">
                            <h6 class="card-title">
                                {{ $class->class_name ?? '' }}
                                <span class="badge badge-pending ms-2">Đang học</span>
                            </h6>
                            <p class="card-text">
                                <i class="icofont-book-alt me-2"></i> Khóa học:
                                <strong>{{ $class->course_name ?? '' }}</strong>
                            </p>
                            <p class="card-text">
                                <i class="icofont-teacher me-2"></i> Giảng viên:
                                {{ $class->teacher_name ?? '' }}
                            </p>

                        </div>
                    @endforeach

                </div>

            </div>

            <div class="col-md-6">
                <div class="card completed-class">

                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="icofont-chart-bar-graph me-2 text-white"></i>Học tập</span>
                        <select name="class" id="dashboardHoctap" class="form-control text-black w-50 m-0">
                            @foreach ($classes as $index => $class)
                                <option {{ $index == 0 ? 'selected' : '' }} value="{{ $class->class_id }}">
                                    {{ $class->class_name }}</option>
                            @endforeach

                        </select>
                    </div>

                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="card text-center h-100 border-0 shadow-sm border-top border-success">
                                    <div class="card-body p-3">
                                        <i class="icofont-check fs-5 text-success mb-2"></i>
                                        <strong>Đã học</strong>
                                        <div class="badge bg-success text-white fw-bold mt-1 p-1 w-100" id="dahoc">
                                            {{ $hoctaps->student_sessions_present ?? 0 }} buổi</div>
                                    </div>
                                </div>
                            </div>

                            {{-- <div class="col-6">
                                <div class="card text-center h-100 border-0 shadow-sm border-top border-danger">
                                    <div class="card-body p-3">
                                        <i class="icofont-close-line fs-5 text-danger mb-2"></i>
                                        <strong>Nghỉ không phép</strong>
                                        <div class="badge bg-danger text-white fw-bold mt-1 p-1 w-100" id="nghikhongphep">
                                            {{ $hoctaps->student_sessions_absent }} buổi</div>
                                    </div>
                                </div>
                            </div> --}}

                            <div class="col-6">
                                <div class="card text-center h-100 border-0 shadow-sm border-top border-warning">
                                    <div class="card-body p-3">
                                        <i class="icofont-clock-time fs-5 text-warning mb-2"></i>
                                        <strong>Nghỉ</strong>
                                        <div class="badge bg-warning text-dark fw-bold mt-1 p-1 w-100" id="denmuon">
                                            {{ $hoctaps->student_sessions_absent ?? 0 }} buổi</div>
                                    </div>
                                </div>
                            </div>

                            {{-- <div class="col-6">
                                <div class="card text-center h-100 border-0 shadow-sm border-top border-info">
                                    <div class="card-body p-3">
                                        <i class="icofont-exclamation-circle fs-5 text-info mb-2"></i>
                                        <strong>Nghỉ có phép</strong>
                                        <div class="badge bg-info text-white fw-bold mt-1 p-1 w-100" id="cophep">
                                            {{ $hoctaps->student_sessions_excused }} buổi</div>
                                    </div>
                                </div>
                            </div> --}}


                        </div>
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
                url: '/student/course-payments/infomation',
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
        if (infomationPayment.length > 0) {
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
                    url: '/student/course-payments/infomation',
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
                        url: '/student/course-payments/updatePayment',
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
                    showAlert('Thanh toán thành công!','Cảm ơn bạn đã thanh toán. Giao dịch của bạn đã được xử lý thành công.', () => {
                        window.location.reload();
                    });



                } else {
                    console.log("Chưa thấy thanh toán.");
                }
            } catch (error) {
                console.error("Fetch error:", error);
            }
        }


        $('#dashboardHoctap').on('change', function() {

            var classId = $(this).val();
            $.ajax({
                url: `/student/dashboard/hoctaps/${classId}`,
                method: 'GET',
                success: function(response) {

                    console.log('Dữ liệu nhận được:', response);
                    $("#cophep").html(`${response.student_sessions_excused} buổi`)
                    $("#denmuon").html(`${response.student_sessions_late} buổi`)
                    $("#nghikhongphep").html(`${response.student_sessions_absent} buổi`)
                    $("#dahoc").html(`${response.student_sessions_present} buổi`)
                    Toastify({
                        text: `<i class="icofont-check-circled me-2 text-white"></i> Đã lấy được thông tin!`,
                        gravity: "top",
                        position: "right",
                        className: "success",
                        duration: 2000,
                        escapeMarkup: false
                    }).showToast();

                },
                error: function(xhr) {
                    console.log('Lỗi:', xhr.responseText);
                }
            });
        });
    </script>
@endpush
