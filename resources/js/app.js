import './bootstrap';
window.addEventListener('load', function () {
    if (window.Echo) {

        Echo.channel('admin-notifications')
            .listen('PaymentNotificationCreated', (e) => {
                showToast('success', 'Có thông báo học phí mới!');
            });



    } else {
        console.error("Echo chưa sẵn sàng!");
    }
});

function showToast(type, message) {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-center',
        showConfirmButton: false,
        timer: 5000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });
    Toast.fire({
        icon: type,
        title: message
    });
}


