
import './bootstrap';
function showToast(type, message) {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        showCloseButton: true,
        background: '#fff',
        customClass: {
            popup: 'modern-toast',
            title: 'modern-toast-title',
            icon: 'modern-toast-icon'
        },
        didOpen: (toast) => { }
    });

    Toast.fire({
        icon: type,
        iconHtml: '<i class="fas fa-bell fs-5"></i>',
        title: message
    });
}



// Tạo phần tử button ảo có data-toast-* và click nó
function showDataToast(text, type = 'danger') {
    const btn = document.createElement('button');
    btn.setAttribute('data-toast', '');
    btn.setAttribute('data-toast-text', text);
    btn.setAttribute('data-toast-gravity', 'top');
    btn.setAttribute('data-toast-position', 'center');
    btn.setAttribute('data-toast-duration', '5000');
    btn.setAttribute('data-toast-close', 'close');
    btn.setAttribute('data-toast-className', type);
    btn.style.display = 'none';
    document.body.appendChild(btn);

    // Gắn lại sự kiện bằng tay (copy logic từ ToastNotification)
    btn.addEventListener('click', function () {
        Toastify({
            newWindow: true,
            text: text,
            gravity: "top",
            position: "center",
            className: "bg-" + type,
            stopOnFocus: true,
            offset: {
                x: 50,
                y: 10
            },
            duration: 3000,
            close: true
        }).showToast();
    });

    btn.click();
    setTimeout(() => btn.remove(), 100);
}




window.addEventListener('load', function () {

    if (window.Echo) {

        Echo.channel('admin-notifications')
            .listen('PaymentNotificationCreated', (e) => {
                console.log(e);
                $('#searchForm').submit();
                renderStatistics();
                if (e.notification.created_by === window.currentUserId) {
                    // Không hiển thị thông báo cho chính người tạo
                    return;
                }

                showToast('success', 'Có thông báo học phí mới!');

                // Tăng số lượng thông báo hiển thị
                const badge = document.querySelector('.soluong-notification');
                if (badge) {
                    let current = parseInt(badge.innerText.trim());
                    if (isNaN(current)) current = 0;
                    badge.innerText = current + 1;
                }

                //định dạng lại thời gian
                 function parseCustomDate(dateStr) {
                    // "24/08/2025 14:56:56"
                    const [day, month, yearAndTime] = dateStr.split("/");
                    const [year, time] = yearAndTime.split(" ");
                    return new Date(`${year}-${month}-${day}T${time}`);
                }

                const createdAt = parseCustomDate(e.notification.created_at);
                const formattedDate = createdAt.toLocaleString("vi-VN", {
                    hour: "2-digit",
                    minute: "2-digit",
                    day: "2-digit",
                    month: "2-digit",
                    year: "numeric"
                });


                const html = `
                    <div class="dropdown-item py-3 border-bottom text-wrap position-relative">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm me-2">
                                    <span class="avatar-title ${e.notification.background} fs-20 rounded-circle">
                                        ${e.notification.icon}
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 pe-4">
                                <a href="#" class="mb-1 fw-semibold fs-6">
                                    ${e.notification.title}
                                </a>
                                <p class="mb-0 text-muted text-wrap fs-6">
                                    Thời gian: ${formattedDate}<br>
                                </p>
                            </div>

                            <!-- Dấu ba chấm và dropdown -->
                            <div class="dropdown position-absolute top-0 end-0 mt-2 me-2">
                                <button class="btn btn-sm border-0" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false"
                                    onclick="event.stopPropagation();">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">

                                    <li >
                                        <button class="dropdown-item mark-as-read fs-6 btn-seen-noti"
                                            data-id="${e.notification.id}">
                                            <i class="fas fa-check me-1 text-success"></i> Đánh dấu
                                            là đã đọc
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                `;

                // Append vào danh sách
                $("#notifications-list .simplebar-content").prepend(html);


            });


    } else {
        console.error("Echo chưa sẵn sàng!");
    }

});

//Hàm sử lý đánh đấu đã đọc
$(document).on('click', '.btn-seen-noti', function (e) {
    e.preventDefault();
    e.stopPropagation();
    let id = $(this).data('id');
    console.log('Đánh dấu đã đọc thông báo ID:', id);
    $.ajax({
        url: '/admin/notification/course/payment/updateSeen/' + id,
        type: 'GET',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (res) {
            if (res.success) {
                showDataToast('Đã dánh dấu đã đọc!', 'success')

                $('.notification-' + id).fadeOut(200, function () {
                    $(this).remove();

                    // Giữ dropdown mở lại sau khi xóa
                    const dropdown = bootstrap.Dropdown.getInstance(
                        document.getElementById('page-header-notifications-dropdown')
                    );
                    dropdown.show();
                });


                // giảm số lượng thông báo hiển thị
                const badge = document.querySelector('.soluong-notification');
                const badge2 = document.querySelector('.soluong-notification-2');

                if (badge) {
                    let current = parseInt(badge.innerText.trim());
                    if (isNaN(current)) current = 0;
                    badge.innerText = current - 1;
                }
                if (badge2) {
                    let current = parseInt(badge2.innerText.trim());
                    if (isNaN(current)) current = 0;
                    badge2.innerText = current - 1;
                }

            } else {
                showDataToast(res.error);
            }
        },
        error: function (err) {
            showDataToast('Có lỗi xảy ra');
        }
    });
});

