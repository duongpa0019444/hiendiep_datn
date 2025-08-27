@extends('admin.admin')
@section('title', 'Trang admin')
@section('description', '')
@section('content')

    <style>
        .btn-group .btn.active {
            background-color: #0d6efd;
            color: white;
            font-weight: bold;
        }

        #roleButtons .role-btn {
            min-width: 120px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        #roleButtons .role-btn.active {
            background-color: #0d6efd;
            color: white;
            border-color: #0d6efd;
        }

        #roleButtons .role-btn:hover {
            opacity: 0.9;
        }
    </style>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Toastify({
                    text: "{{ session('success') }}",
                    gravity: "top",
                    position: "center",
                    className: "success",
                    duration: 4000
                }).showToast();
            });
        </script>
    @endif
    <div class="page-content">

        <div class="container-fluid">
            <div class="container-xxl">
                <nav aria-label="breadcrumb p-0">
                    <ol class="breadcrumb py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Quản lí thông báo</li>
                    </ol>
                </nav>
                <div class="row">
                    <div class="col-lg-6 col-12">
                        <h4>📢 Gửi thông báo</h4>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('admin.notifications.seed') }}">
                            @csrf
                            <input type="hidden" name="created_by" value="{{ Auth::user()->id }}">
                            <div class="mb-3">
                                <label class="form-label">Tiêu đề</label>
                                <input type="text" name="title" class="form-control" value="{{ old('title') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nội dung</label>
                                <textarea class="form-control bg-light-subtle mb-3 ckeditor" id="content" name="content" rows="7"
                                    placeholder="Nội dung thông báo"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Gửi đến</label>
                                <select name="target_role" class="form-select" required>
                                    <option value="all">Toàn hệ thống</option>
                                    <option value="student">Học sinh</option>
                                    <option value="teacher">Giáo viên</option>
                                    @if (auth()->user()->isAdmin())
                                        <option value="staff">Nhân viên</option>
                                    @endif
                                    <option value="class">Lớp</option>
                                </select>
                                <div class="mb-3 d-none mt-2" id="class-select-wrapper">
                                    <label class="form-label">Chọn lớp</label>
                                    <select name="class_id" class="form-select">
                                        <option value="">-- Chọn lớp --</option>
                                        @foreach ($classes as $class)
                                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- ✅ THÊM: Thời gian bắt đầu -->
                                <div class="mt-2">
                                    <label class="form-label">Thời gian bắt đầu hiệu lực</label>
                                    <input type="datetime-local" name="start_time" class="form-control" value="{{ old('start_time') }}">
                                </div>

                                <!-- ✅ THÊM: Thời gian kết thúc -->
                                <div class="mt-2 mb-2">
                                    <label class="form-label">Thời gian kết thúc hiệu lực</label>
                                    <input type="datetime-local" name="end_time" class="form-control" value="{{ old('end_time') }}">
                                </div>

                            <button type="submit" class="btn btn-primary w-100">Gửi thông báo</button>
                        </form>
                    </div>
                    <div class="col-lg-6 col-12">
                        <h4>Danh sách thông báo</h4>
                        <div class="mb-3 d-flex gap-2  " id="roleButtons">
                            <button type="button" class="btn btn-outline-secondary  btn-role active" data-role="">Tất
                                cả</button>
                            <button type="button" class="btn btn-outline-success  btn-role" data-role="teacher">Giáo
                                viên</button>
                            <button type="button" class="btn btn-outline-info  btn-role" data-role="student">Học
                                sinh</button>
                           @if (auth()->user()->isAdmin())
                                 <button type="button" class="btn btn-outline-warning  btn-role" data-role="staff">Nhân
                                viên</button>
                            @endif
                            <button type="button" class="btn btn-outline-primary  btn-role " data-role="class">
                                Lớp</button>
                        </div>
                        <div id="notificationsContainer">
                            @foreach ($notis as $noti)
                                <div class="card mb-3 shadow-sm">
                                    <div class="row g-0 align-items-center">

                                        <div class="col-md-10">
                                            <div class="card-body py-2">
                                                <span class="fw-bold">{{ $noti->title }}</span>
                                                <span class="text-muted fs-6 fst-italic">— {{ $noti->name }}, lúc
                                                    {{ \Carbon\Carbon::parse($noti->created_at)->diffForHumans() }}</span>
                                                </h5>
                                                <p class="card-text text-muted mb-0">{!! $noti->content !!}</p>
                                            </div>
                                        </div>
                                        <div class="col-2 text-end pe-3">
                                            <button class="btn btn-sm btn-outline-danger delete-noti-btn"
                                                data-id="{{ $noti->id }}">
                                                Xóa
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            @endforeach
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

    <script>
        function renderNotifications(data) {
            let html = '';

            if (data.length === 0) {
                html = '<p class="text-muted">Không có thông báo nào.</p>';
            } else {
                data.forEach(noti => {

                    console.log(noti.id)
                    const formattedTime = new Date(noti.created_at).toLocaleString('vi-VN', {
                        hour: '2-digit',
                        minute: '2-digit',
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric'
                    });

                    html += `
                <div class="card mb-3 shadow-sm">
                    <div class="row g-0 align-items-center">

                        <div class="col-md-10">
                            <div class="card-body py-2">
                               <span class="fw-bold">${noti.title}</span>
                                <span class="text-muted fs-6 fst-italic">— ${noti.name}, lúc ${formattedTime}</span>
                                <p class="card-text text-muted mb-0">${noti.content}</p>
                            </div>
                        </div>
                        <div class="col-md-2 text-end pe-3">
                            <button class="btn btn-sm btn-outline-danger delete-noti-btn" data-id="${noti.id}">
                                Xóa                            </button>
                         </div>
                    </div>
                </div>
            `;
                });
            }

            $('#notificationsContainer').html(html);
        }



        $(document).on('click', '.btn-role', function() {
            // Xóa lớp active khỏi tất cả nút
            $('.btn-role').removeClass('active');

            // Gán lớp active cho nút được click
            $(this).addClass('active');

            const role = $(this).data('role');

            $.ajax({
                url: "{{ route('admin.notifications.filter') }}",
                type: "GET",
                data: {
                    role: role
                },
                success: function(res) {
                    if (res.success) {
                        renderNotifications(res.data); // Hàm hiển thị lại dữ liệu
                    }
                },
                error: function() {
                    alert("Lỗi khi lọc dữ liệu.");
                }
            });
        });

        $(document).on('click', '.delete-noti-btn', function() {
            const button = $(this);
            const notiId = button.data('id');

            // if (!confirm('Bạn có chắc muốn xóa thông báo này?')) return;

            Swal.fire({
                title: `Bạn có chắc muốn xóa thông báo này?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Vâng, xóa!',
                cancelButtonText: 'Không, hủy',
                confirmButtonClass: 'btn btn-danger w-xs me-2 mt-2',
                cancelButtonClass: 'btn btn-secondary w-xs mt-2',
                buttonsStyling: false,
            }).then(function(result) {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "{{ route('admin.notifications.delete') }}",
                        method: "POST",
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: notiId
                        },
                        success: function(res) {
                            if (res.success) {
                                button.closest('.row').remove(); // Xóa khỏi giao diện

                                Swal.fire({
                                    title: 'Thành công!',
                                    text: res.messege,
                                    icon: 'success',
                                    confirmButtonClass: 'btn btn-primary w-xs mt-2',
                                    buttonsStyling: false
                                }).then(() => {
                                    window.location.href =
                                        "{{ route('admin.notifications') }}";
                                })


                            } else {
                                Swal.fire({
                                    title: 'Không thành công!',
                                    text: res.message,
                                    icon: 'error',
                                    confirmButtonClass: 'btn btn-primary w-xs mt-2',
                                    buttonsStyling: false
                                });
                                return;
                            }
                        },
                        error: function() {
                            alert('Lỗi kết nối khi xóa');
                        }
                    });

                } else {
                    Swal.fire({
                        title: 'Đã hủy',
                        text: 'Thông báo chưa bị xóa.',
                        icon: 'info',
                        confirmButtonClass: 'btn btn-primary mt-2',
                        buttonsStyling: false
                    });
                }
            });





        });

        $(document).ready(function() {
            const $roleSelect = $('select[name="target_role"]');
            const $classWrapper = $('#class-select-wrapper');

            $roleSelect.on('change', function() {
                if ($(this).val() === 'class') {
                    $classWrapper.removeClass('d-none');
                } else {
                    $classWrapper.addClass('d-none');
                }
            });
        });
    </script>

@endsection
