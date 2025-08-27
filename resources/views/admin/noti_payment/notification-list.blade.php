@extends('admin.admin')

@section('title', 'Quản lý danh sách thông báo học phí')
@section('description', 'Danh sách thông báo biến động học phí')

@section('content')
    <div class="page-content">
        <div class="container-xxl">
            <nav aria-label="breadcrumb p-0">
                <ol class="breadcrumb py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Thông báo học phí & thanh toán</li>
                </ol>
            </nav>

            <h5 class="mb-3">Danh sách thông báo biến động học phí & thanh toán</h5>

            <div class="row g-3 mb-3">
                <div class="col-xxl-2">
                    <div class="offcanvas-xxl offcanvas-start h-100" tabindex="-1" id="EmailSidebaroffcanvas"
                        aria-labelledby="EmailSidebaroffcanvasLabel">
                        <div class="card h-100 mb-0 shadow-sm border-0" data-simplebar>
                            <div class="card-body">
                                <div class=" flex-column mt-3">
                                    <a class="nav-link  py-1 active show" id="noti-all-tab" href="#">
                                        <span class="fw-bold">
                                            <i class="fas fa-inbox fs-16 me-2 align-middle"></i> Tất cả
                                        </span>
                                    </a>
                                    <a class="nav-link   py-1" id="noti-unread-tab" href="#">
                                        <span class="fw-bold d-flex justify-content-start align-items-center ">
                                            <i class="fas fa-envelope fs-16 me-2 align-middle"></i> Chưa đọc
                                            <span class="badge bg-danger-subtle text-danger ms-2 soluong-notification-2">
                                                {{ \App\Models\NotificationUser::with('notification')->where('user_id', auth()->id())->where('status', '!=', 'seen')->count() }}
                                            </span>
                                        </span>
                                    </a>
                                    <a class="nav-link  py-1" id="noti-read-tab" href="#">
                                        <i class="fas fa-envelope-open fs-16 me-2 align-middle"></i> Đã đọc
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-10">
                    <div class="card position-relative overflow-hidden h-100 shadow-sm border-0">
                        <div class="p-2">
                            <form action="{{ route('admin.noti.coursepayment.filter') }}" id="searchForm"
                                class="row g-2 align-items-center">
                                <input type="hidden" name="limit" id="limit" value="10">
                                <input type="hidden" name="status">
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-search"></i>
                                        </span>
                                        <input type="text" class="form-control" id="searchNotifications" name="keyword"
                                            placeholder="Tìm kiếm thông báo...">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-calendar-alt"></i>
                                        </span>
                                        <input type="text" id="basic-datepicker" class="form-control" name="date"
                                            placeholder="Chọn ngày">
                                    </div>
                                </div>
                                <div class="col-md-3 d-flex gap-2">
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="fas fa-search me-1"></i> Tìm kiếm
                                    </button>
                                    <button type="reset" class="btn btn-secondary btn-sm btn-reset">
                                        <i class="fas fa-undo me-1"></i> Xóa
                                    </button>
                                </div>
                            </form>
                            <!-- Action Buttons -->
                            <div class="d-flex align-items-center justify-content-between mt-3 p-2 bg-light rounded">
                                <div class="d-flex align-items-center gap-2">
                                    <input type="checkbox" id="selectAll" class="form-check-input">
                                    <label for="selectAll" class="form-check-label small mb-0">Chọn tất cả</label>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-info btn-sm mark-selected-read" disabled>
                                        <i class="fas fa-check-circle me-1"></i> Đánh dấu đã đọc
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm delete-selected" disabled>
                                        <i class="fas fa-trash-alt me-1"></i> Xóa
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="tab-content pt-0" id="email-tabContent">
                            <div class="tab-pane fade active show" id="email-all" role="tabpanel"
                                aria-labelledby="email-all-tab">
                                <div>
                                    <ul class="list-unstyled mb-0" id="notificationList">
                                        @foreach ($notifications as $notification)
                                            <li
                                                class="notification-item notification-item-list-{{ $notification->id }} {{ $notification->status == 'unseen' ? 'bg-light' : '' }} d-flex align-items-center justify-content-between p-2 border-bottom">
                                                <div class="d-flex align-items-center gap-2 flex-grow-1">
                                                    <div class="checkbox-wrapper-mail">
                                                        <input type="checkbox" id="NotificationChk{{ $notification->id }}"
                                                            class="form-check-input notification-checkbox"
                                                            value="{{ $notification->id }}">
                                                    </div>
                                                    <a href="{{ route('admin.noti.detail', $notification->id) }}"
                                                        class="notification-title text-dark text-decoration-none {{ $notification->status == 'unseen' ? 'fw-bold' : '' }}">
                                                        {{ $notification->notification->title }}
                                                    </a>
                                                </div>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="notification-date text-muted small">
                                                        {{ $notification->created_at->format('H:i - d/m/Y') }}
                                                    </div>
                                                    @if ($notification->status == 'seen')
                                                        <div class="position-relative d-inline-block"
                                                            style="width:20px; height:20px;" title="Đã xem">
                                                            <i class="fas fa-check text-success position-absolute"
                                                                style="left:0; top:0;"></i>
                                                            <i class="fas fa-check text-success position-absolute"
                                                                style="left:6px; top:0;"></i>
                                                        </div>
                                                    @else
                                                        <div class="dropdown">
                                                            <button class="btn btn-sm border-0" type="button"
                                                                data-bs-toggle="dropdown" aria-expanded="false"
                                                                onclick="event.stopPropagation();">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                <li>
                                                                    <button
                                                                        class="dropdown-item mark-as-read btn-seen-noti-all"
                                                                        data-id="{{ $notification->id }}">
                                                                        <i
                                                                            class="fas fa-check-circle me-1 text-success"></i>
                                                                        Đánh dấu là đã đọc
                                                                    </button>
                                                                </li>

                                                            </ul>
                                                        </div>
                                                    @endif
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 m-3">
                            <div id="pagination-wrapper" class="flex-grow-1">
                                {{ $notifications->links('pagination::bootstrap-5') }}
                            </div>
                            <div class="d-flex align-items-center" style="min-width: 160px;">
                                <label for="limit2" class="form-label mb-0 me-2 small">Hiển thị</label>
                                <select name="limit2" id="limit2" class="form-select form-select-sm"
                                    style="width: 100px;">
                                    <option value="10" selected>10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                        </div>
                    </div>
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
        // Initialize flatpickr for datepicker
        document.getElementById('basic-datepicker').flatpickr();

        // Reset form
        $('.btn-reset').on('click', function(e) {
            $("#searchForm")[0].reset();
            $('input[name="status"]').val(window.selectedNotificationStatus || '');
            $("#searchForm").submit();
        });

        // Handle limit change
        $('#limit2').change(function() {
            $('#searchForm #limit').val(this.value);
            $('#searchForm').submit();
        });

        // Form filter submission
        $('#searchForm').on('submit', function(e) {
            e.preventDefault();
            $('#notificationList').css({
                'opacity': '0.5'
            });
            const url = $("#searchForm").attr('action');
            $.ajax({
                url: url,
                type: 'GET',
                data: $(this).serialize(),
                success: function(response) {
                    $('#notificationList').html(renderLog(response.notifications.data));
                    $('#pagination-wrapper').html(response.pagination);
                    $('#notificationList').css({
                        'opacity': '1'
                    });
                },
                error: function(xhr) {
                    console.error('Lỗi khi tìm kiếm:', xhr.responseText);
                    showDataToast('Có lỗi xảy ra khi tìm kiếm!', 'danger');
                }
            });
        });

        // Pagination
        $(document).on('click', '#pagination-wrapper a', function(e) {
            e.preventDefault();
            $('#notificationList').css({
                'opacity': '0.5'
            });
            const url = this.href;
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    console.log(response);
                    $('#notificationList').html(renderLog(response.notifications.data));
                    $('#pagination-wrapper').html(response.pagination);
                    $('#notificationList').css({
                        'opacity': '1'
                    });
                },
                error: function(xhr) {
                    console.error('Lỗi phân trang:', xhr.responseText);
                    showDataToast('Có lỗi xảy ra khi phân trang!', 'danger');
                }
            });
        });

        // Render notifications
        function renderLog(data) {
            if (data.length === 0) {
                return `
                    <div class="alert alert-secondary d-flex align-items-center justify-content-center gap-2" style="height: 200px;">
                        <i class="fas fa-bell-slash fs-4"></i>
                        <span>Không tìm thấy kết quả</span>
                    </div>
                `;
            }
            let html = `

            `;
            data.forEach(notification => {
                html += `
                    <li class="notification-item notification-item-list-${notification.id} ${notification.status === 'unseen' ? 'bg-light' : ''} d-flex align-items-center justify-content-between p-2 border-bottom">
                        <div class="d-flex align-items-center gap-2 flex-grow-1">
                            <div class="checkbox-wrapper-mail">
                                <input type="checkbox" id="NotificationChk${notification.id}" class="form-check-input notification-checkbox" value="${notification.id}">
                            </div>
                            <a href="/admin/notification/course/payment/detail/${notification.id}" class="notification-title text-dark text-decoration-none ${notification.status === 'unseen' ? 'fw-bold' : ''}">
                                ${notification.notification.title}
                            </a>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <div class="notification-date text-muted small">
                                ${formatDateTime(notification.created_at)}
                            </div>
                            ${notification.status === 'seen' ? `
                                            <div class="position-relative d-inline-block" style="width:20px; height:20px;" title="Đã xem">
                                                <i class="fas fa-check text-success position-absolute" style="left:0; top:0;"></i>
                                                <i class="fas fa-check text-success position-absolute" style="left:6px; top:0;"></i>
                                            </div>
                                        ` : `
                                            <div class="dropdown">
                                                <button class="btn btn-sm border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false" onclick="event.stopPropagation();">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <button class="dropdown-item mark-as-read btn-seen-noti-all" data-id="${notification.id}">
                                                            <i class="fas fa-check-circle me-1 text-success"></i> Đánh dấu là đã đọc
                                                        </button>
                                                    </li>

                                                </ul>
                                            </div>
                                        `}
                        </div>
                    </li>
                `;
            });
            return html;
        }

        // Format date and time
        function formatDateTime(dateString) {
            const date = new Date(dateString);
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            return `${hours}:${minutes} - ${day}/${month}/${year}`;
        }

        // Show toast notification
        function showDataToast(text, type = 'danger') {
            const btn = document.createElement('button');
            btn.setAttribute('data-toast', '');
            btn.setAttribute('data-toast-text', text);
            btn.setAttribute('data-toast-gravity', 'top');
            btn.setAttribute('data-toast-position', 'right');
            btn.setAttribute('data-toast-duration', '5000');
            btn.setAttribute('data-toast-close', 'close');
            btn.setAttribute('data-toast-className', type);
            btn.style.display = 'none';
            document.body.appendChild(btn);

            btn.addEventListener('click', function() {
                Toastify({
                    newWindow: true,
                    text: text,
                    gravity: "top",
                    position: "right",
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

        // Tab click handler
        $(document).ready(function() {
            $('#noti-all-tab, #noti-unread-tab, #noti-read-tab').on('click', function(e) {
                e.preventDefault();
                let status = '';
                switch (this.id) {
                    case 'noti-all-tab':
                        status = '';
                        break;
                    case 'noti-unread-tab':
                        status = 'unseen';
                        break;
                    case 'noti-read-tab':
                        status = 'seen';
                        break;
                }
                window.selectedNotificationStatus = status;
                $('input[name="status"]').val(status);
                $('#noti-all-tab, #noti-unread-tab, #noti-read-tab').removeClass('active show');
                $(this).addClass('active show');
                $("#searchForm").submit();
            });

            // Select all checkbox
            $('#selectAll').on('change', function() {
                $('.notification-checkbox').prop('checked', this.checked);
                $('.mark-selected-read, .delete-selected').prop('disabled', !this.checked && $(
                    '.notification-checkbox:checked').length === 0);
            });

            // Enable/disable action buttons based on checkbox selection
            $(document).on('change', '.notification-checkbox', function() {
                const anyChecked = $('.notification-checkbox:checked').length > 0;
                $('.mark-selected-read, .delete-selected').prop('disabled', !anyChecked);
                $('#selectAll').prop('checked', $('.notification-checkbox').length === $(
                    '.notification-checkbox:checked').length);
            });

            // Xử lý click nút đánh dấu đọc
            $('.mark-selected-read').on('click', function() {
                const selectedIds = $('.notification-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                if (selectedIds.length === 0) {
                    showDataToast('Vui lòng chọn ít nhất một thông báo!', 'warning');
                    return;
                }

                $.ajax({
                    url: '/admin/notification/course/payment/updateSeenMultiple',
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        ids: selectedIds
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.success) {
                            showDataToast('Đã đánh dấu các thông báo đã đọc!', 'success');
                            selectedIds.forEach(id => {
                                const notification = response.notifications.find(n => n
                                    .id == id);
                                if (notification) {
                                    $(`.notification-item-list-${id}`).replaceWith(
                                        renderLogItem(notification));
                                }
                            });
                            const badge = document.querySelector('.soluong-notification-2');
                            if (badge) {
                                let current = parseInt(badge.innerText.trim()) || 0;
                                badge.innerText = Math.max(current - selectedIds.length, 0);
                            }
                            $('.notification-checkbox').prop('checked', false);
                            $('#selectAll').prop('checked', false);
                            $('.mark-selected-read, .delete-selected').prop('disabled', true);
                        } else {
                            showDataToast(response.error || 'Có lỗi xảy ra!', 'danger');
                        }
                    },
                    error: function() {
                        showDataToast('Có lỗi xảy ra khi đánh dấu đã đọc!', 'danger');
                    }
                });
            });

            // Delete selected notifications
            $('.delete-selected').on('click', function() {
                const selectedIds = $('.notification-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                if (selectedIds.length === 0) {
                    showDataToast('Vui lòng chọn ít nhất một thông báo!', 'warning');
                    return;
                }


                Swal.fire({
                    title: 'Bạn có chắc chắn?',
                    text: "Bạn muốn xóa các thông báo đã chọn?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Xóa',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/admin/notification/course/payment/deleteMultiple',
                            type: 'POST',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                ids: selectedIds
                            },
                            success: function(response) {
                                if (response.success) {
                                    showDataToast('Đã xóa các thông báo thành công!',
                                        'success');
                                    selectedIds.forEach(id => {
                                        $(`.notification-item-list-${id}`)
                                            .remove();
                                    });
                                    const badge = document.querySelector(
                                        '.soluong-notification-2');
                                    if (badge) {
                                        let current = parseInt(badge.innerText
                                            .trim()) || 0;
                                        badge.innerText = Math.max(current - selectedIds
                                            .length, 0);
                                    }
                                    $('.notification-checkbox').prop('checked', false);
                                    $('#selectAll').prop('checked', false);
                                    $('.mark-selected-read, .delete-selected').prop(
                                        'disabled', true);
                                } else {
                                    showDataToast(response.error || 'Có lỗi xảy ra!',
                                        'danger');
                                }
                            },
                            error: function() {
                                showDataToast('Có lỗi xảy ra khi xóa thông báo!',
                                    'danger');
                            }
                        });
                    }
                });

            });

            // Single notification mark as read
            $(document).on('click', '.btn-seen-noti-all', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                $.ajax({
                    url: '/admin/notification/course/payment/updateSeen/' + id,
                    type: 'GET',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        if (res.success) {
                            showDataToast('Đã đánh dấu đã đọc!', 'success');
                            const badge = document.querySelector('.soluong-notification-2');
                            if (badge) {
                                let current = parseInt(badge.innerText.trim()) || 0;
                                badge.innerText = Math.max(current - 1, 0);
                            }
                            $(`.notification-item-list-${res.notificationUser.id}`).replaceWith(
                                renderLogItem(res.notificationUser));
                            $(`.notification-${res.notificationUser.id}`).remove();
                            $('.soluong-notification').text(parseInt($('.soluong-notification').text()) - 1);

                        } else {
                            showDataToast(res.error || 'Có lỗi xảy ra!', 'danger');
                        }
                    },
                    error: function() {
                        showDataToast('Có lỗi xảy ra!', 'danger');
                    }
                });
            });

            // Single notification delete
            $(document).on('click', '.delete-notification', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                if (confirm('Bạn có chắc chắn muốn xóa thông báo này?')) {
                    $.ajax({
                        url: '/admin/notification/course/payment/delete/' + id,
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(res) {
                            if (res.success) {
                                showDataToast('Đã xóa thông báo thành công!', 'success');
                                $(`.notification-item-list-${id}`).remove();
                                const badge = document.querySelector('.soluong-notification-2');
                                if (badge) {
                                    let current = parseInt(badge.innerText.trim()) || 0;
                                    badge.innerText = Math.max(current - 1, 0);
                                }
                            } else {
                                showDataToast(res.error || 'Có lỗi xảy ra!', 'danger');
                            }
                        },
                        error: function() {
                            showDataToast('Có lỗi xảy ra khi xóa thông báo!', 'danger');
                        }
                    });
                }
            });
        });

        // Render single notification item
        function renderLogItem(notification) {
            return `
                <li class="notification-item notification-item-list-${notification.id} ${notification.status === 'unseen' ? 'bg-light' : ''} d-flex align-items-center justify-content-between p-2 border-bottom">
                    <div class="d-flex align-items-center gap-2 flex-grow-1">
                        <div class="checkbox-wrapper-mail">
                            <input type="checkbox" id="NotificationChk${notification.id}" class="form-check-input notification-checkbox" value="${notification.id}">
                        </div>
                        <a href="/admin/notification/course/payment/detail/${notification.id}" class="notification-title text-dark text-decoration-none ${notification.status === 'unseen' ? 'fw-bold' : ''}">
                            ${notification.notification.title}
                        </a>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div class="notification-date text-muted small">
                            ${formatDateTime(notification.created_at)}
                        </div>
                        ${notification.status === 'seen' ? `
                                        <div class="position-relative d-inline-block" style="width:20px; height:20px;" title="Đã xem">
                                            <i class="fas fa-check text-success position-absolute" style="left:0; top:0;"></i>
                                            <i class="fas fa-check text-success position-absolute" style="left:6px; top:0;"></i>
                                        </div>
                                    ` : `
                                        <div class="dropdown">
                                            <button class="btn btn-sm border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false" onclick="event.stopPropagation();">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <button class="dropdown-item mark-as-read btn-seen-noti-all" data-id="${notification.id}">
                                                        <i class="fas fa-check-circle me-1 text-success"></i> Đánh dấu là đã đọc
                                                    </button>
                                                </li>

                                            </ul>
                                        </div>
                                    `}
                    </div>
                </li>
            `;
        }
    </script>
@endpush
