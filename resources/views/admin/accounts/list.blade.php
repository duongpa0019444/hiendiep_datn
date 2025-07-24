@php
    $roles = [
        'student' => 'Học sinh',
        'teacher' => 'Giáo viên',
        'admin' => 'Quản trị viên',
        'staff' => 'Nhân viên',
    ];
@endphp

@extends('admin.admin')
@section('title', 'Quản lí ' . ($roles[request('role')] ?? (request('role') ?? 'người dùng')))
@section('description', '')
@section('content')

    <div class="page-content">
        <div class="container-fluid">
            <nav aria-label="breadcrumb p-0">
                <ol class="breadcrumb py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.account') }}">Quản lí người dùng</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $roles[request('role')] ?? request('role') }}
                    </li>
                </ol>
            </nav>

            @if (session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const toastElement = document.createElement('div');
                        toastElement.setAttribute('data-toast', '');
                        toastElement.setAttribute('data-toast-text', "{{ session('success') }}");
                        toastElement.setAttribute('data-toast-gravity', 'top');
                        toastElement.setAttribute('data-toast-position', 'center');
                        toastElement.setAttribute('data-toast-className', 'success');
                        toastElement.setAttribute('data-toast-duration', '4000');
                        document.body.appendChild(toastElement);

                        // Kích hoạt toast (nếu thư viện bạn đang dùng có hàm gọi)
                        if (typeof Toastify !== 'undefined') {
                            Toastify({
                                text: toastElement.getAttribute('data-toast-text'),
                                gravity: toastElement.getAttribute('data-toast-gravity'),
                                position: toastElement.getAttribute('data-toast-position'),
                                className: toastElement.getAttribute('data-toast-className'),
                                duration: parseInt(toastElement.getAttribute('data-toast-duration'))
                            }).showToast();
                        }
                    });
                </script>
            @endif


            <div class="row">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Danh sách </h4>
                        @if (request('role'))
                            <div class="d-flex justify-content-between gap-3 align-items-center">
                                <form method="GET" action="{{ route('admin.account.list', request('role')) }}">
                                    <div class="position-relative">
                                        <input type="search" name="queryAccountRole" class="form-control"
                                            placeholder="Tìm học sinh..." autocomplete="off"
                                            value="{{ request()->query('queryAccountRole') ?? '' }}">
                                        <iconify-icon icon="solar:magnifer-linear" class="search-widget-icon"
                                            style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; color: #999;"></iconify-icon>
                                    </div>
                                </form>


                                <a href="{{ route('admin.account.add', ['role' => request('role')]) }}"
                                    class="btn  btn-primary">
                                    Thêm {{ $roles[request('role')] ?? request('role') }}
                                </a>
                            </div>
                        @endif

                    </div> <!-- end card-header-->
                    <div class="card-body p-0">
                        <div class="px-3">
                            <table class="table table-hover mb-0 table-centered">
                                <thead>
                                    <tr>
                                        <th>Ảnh đại diện</th>
                                        <th>Tên</th>
                                        <th>Giới tính</th>
                                        <th>Ngày sinh nhật</th>
                                        <th>Email</th>
                                        <th>Số điện thoại</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($users as $data)
                                        <tr>
                                            <td><img class="rounded" src="{{ asset($data->avatar) }}" width="50"
                                                    alt="{{ $data->name }}">
                                            </td>
                                            <td>{{ $data->name }}</td>
                                            <td>{{ $data->gender }}</td>
                                            <td>{{ \Carbon\Carbon::parse($data->birth_date)->format('d/m/Y') }}</td>
                                            <td>{{ $data->email }}</td>
                                            <td>{{ $data->phone }}</td>
                                            <td>
                                                <div class="d-flex gap-2">

                                                    <a href="{{ route('admin.account.detail', ['role' => request('role'), 'id' => $data->id]) }}"
                                                        class="btn btn-soft-primary btn-sm">
                                                        <iconify-icon   
                                                            icon="solar:eye-broken"
                                                            class="align-middle fs-18"></iconify-icon>
                                                    </a>

                                                    <a href="{{ route('admin.account.edit', ['role' => request('role'), 'id' => $data->id]) }}"
                                                        class="btn btn-soft-primary btn-sm"><iconify-icon
                                                            icon="solar:pen-2-broken"
                                                            class="align-middle fs-18"></iconify-icon></a>

                                                    <a href="#" class="btn btn-soft-danger btn-sm"
                                                        onclick="showDeleteConfirm({{ $data->id }}, '{{ $data->name }}', '{{ request('role') }}')">
                                                        <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"
                                                            class="align-middle fs-18"></iconify-icon></a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <div class="col-12">
                                            <div class="alert mt-3 alert-warning text-center" role="alert">
                                                Không tìm thấy {{ $roles[request('role')] ?? request('role') }} nào
                                            </div>
                                        </div>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>
                    </div> <!-- end card body -->
                    <div class="card-footer border-top">
                        <nav aria-label="Page navigation">
                            {!! $users->links('pagination::bootstrap-5') !!}
                        </nav>
                    </div>
                </div>

            </div>
        </div>
        <!-- end row -->
        <!-- End Container Fluid -->
        <!-- ========== Footer Start ========== -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 text-center">
                        <script>
                            document.write(new Date().getFullYear())
                        </script> &copy; DỰ ÁN TỐT NGHIỆP CAO ĐẲNG FPT THANH HÓA<iconify-icon
                            icon="iconamoon:heart-duotone" class="fs-18 align-middle text-danger"></iconify-icon> <a
                            href="#" class="fw-bold footer-text" target="_blank">NHÓM 4</a>
                    </div>
                </div>
            </div>
        </footer>

    </div>

    <script>
        function showDeleteConfirm(userId, userName, role) {
            $.ajax({
                url: `{{ route('admin.account.check', '') }}/${userId}`,

                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                },
                success: function(data) {
                    let msg = `⚠️ ${role} "${userName}" đang có dữ liệu: \n\n`;

                    if (data.classes.length)
                        msg += `Lớp : ${data.classes.map(c => c.name).join(', ')}\n`;
                    if (data.payments.length)
                        msg += `Và Thanh toán: ${data.payments.length} khoản\n`;
                    if (data.quizzes.length)
                        msg += `Và Bài quiz: ${data.quizzes.length} lần\n`;
                    if (data.schedules.length)
                        msg += `Lịch dạy: ${data.schedules.length} buổi \n`;
                    // nếu trùng 1 lớp 3 buổi thì sao

                    if (data.classes.length || data.payments.length || data.quizzes.length || data.schedules
                        .length) {
                        Swal.fire({
                            title: `Bạn có chắc muốn xóa ${role}?`,
                            text: msg,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Vâng, xóa!',
                            cancelButtonText: 'Không, hủy',
                            confirmButtonClass: 'btn btn-danger w-xs me-2 mt-2',
                            cancelButtonClass: 'btn btn-secondary w-xs mt-2',
                            buttonsStyling: false,
                        }).then(function(result) {
                            if (result.isConfirmed) {
                                window.location.href = "{{ url('/admin/account/delete') }}/" + role +
                                    "/" + userId;

                            } else {
                                Swal.fire({
                                    title: 'Đã hủy',
                                    text: 'Người dùng chưa bị xóa.',
                                    icon: 'info',
                                    confirmButtonClass: 'btn btn-primary mt-2',
                                    buttonsStyling: false
                                });
                            }
                        });
                    } else {
                        Swal.fire({
                            title: `Bạn có chắc muốn xóa ${role}?`,
                            text: `Thao tác này không thể hoàn tác.`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Vâng, xóa!',
                            cancelButtonText: 'Không, hủy',
                            confirmButtonClass: 'btn btn-danger w-xs me-2 mt-2',
                            cancelButtonClass: 'btn btn-secondary w-xs mt-2',
                            buttonsStyling: false,
                        }).then(function(result) {
                            if (result.isConfirmed) {
                                window.location.href = "{{ url('/admin/account/delete') }}/" + role +
                                    "/" + userId;

                            } else {
                                Swal.fire({
                                    title: 'Đã hủy',
                                    text: 'Người dùng chưa bị xóa.',
                                    icon: 'info',
                                    confirmButtonClass: 'btn btn-primary mt-2',
                                    buttonsStyling: false
                                });
                            }
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        title: 'Lỗi kết nối!',
                        text: 'Không thể kiểm tra liên kết người dùng.',
                        icon: 'error',
                        confirmButtonClass: 'btn btn-danger mt-2',
                        buttonsStyling: false
                    });
                    console.error(xhr.responseText);
                }
            });
        }
    </script>



@endsection
