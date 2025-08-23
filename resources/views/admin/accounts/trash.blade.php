@extends('admin.admin')
@section('title', 'Thùng rác tài khoản')
@section('description', '')
@section('content')

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
    <div class="page-content">
        <div class="container-fluid ">
            <nav aria-label="breadcrumb p-0">
                <ol class="breadcrumb py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.account') }}">Quản lí người dùng</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Thùng rác tài khoản</li>
                </ol>
            </nav>


            <div class="row">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h4 class="card-title col-3">Thùng rác tài khoản</h4>
                        <div class="col-9 gap-2">

                            <form method="GET" action="{{ route('admin.account.trash') }}"
                                class="form-inline d-flex gap-2 align-items-center">

                                <div class="col-md-2    ">
                                    <select name="role" class="form-select" >
                                        <option value="">-- Vai trò --</option>
                                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Quản trị viên
                                        </option>
                                        <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Nhân viên
                                        </option>
                                        <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Học sinh
                                        </option>
                                        <option value="teacher" {{ request('role') == 'teacher' ? 'selected' : '' }}>Giáo viên
                                        </option>
                                        <!-- Thêm các role khác nếu cần -->
                                    </select>
                                </div>

                                 <div class="col-md-2">
                                    <select name="gender" class="form-select" >
                                        <option value="">-- Giới tính --</option>
                                        <option value="boy" {{ request('gender') == 'boy' ? 'selected' : '' }}>Nam
                                        </option>
                                        <option value="girl" {{ request('gender') == 'girl' ? 'selected' : '' }}>Nữ
                                        </option>
                                    </select>
                                </div>

                                <div class="position-relative">
                                    <input type="search" name="accountTrash" class="form-control pe-5"
                                        placeholder="Tìm tài khoản..." autocomplete="off"
                                        value="{{ request()->query('accountTrash') ?? '' }}">
                                    <iconify-icon icon="solar:magnifer-linear" class="search-widget-icon"
                                        style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; color: #999;"></iconify-icon>
                                </div>

                                <button type="submit" class="btn btn-success">Lọc</button>

                                <a href="{{ route('admin.account.trash') }}"><button class="btn btn-danger">Xóa</button></a>
                            </form>

                        </div>

                    </div> <!-- end card-header-->

                    <div class="card-body p-0">
                        <div class="px-3" data-simplebar style="max-height: 398px;">
                            <table class="table table-hover mb-0 table-centered">
                                <thead>
                                    <tr>
                                        <th>Thông tin</th>
                                        <th>Ngày sinh nhật</th>
                                        <th>Email</th>
                                        <th>Số điện thoại</th>
                                        <th>Địa chỉ</th>
                                        <th>Thời gian xóa</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($trash as $data)
                                        <tr>
                                             <td>
                                                <div style="display: flex; align-items: center; gap: 10px;">
                                                    <img class="rounded" src="{{ $data->avatar ? asset($data->avatar) : asset('icons/user-solid.svg') }}" width="40"
                                                        alt="{{ $data->name }}">
                                                    <div>
                                                        <div>{{ $data->name ?? '' }}</div>
                                                        <div style="font-size: 0.9em; color: rgb(255, 81, 0);">
                                                            @php
                                                                echo match ($data->gender) {
                                                                    'boy' => 'Nam',
                                                                    'girl' => 'Nữ',
                                                                    default => '',
                                                                };
                                                            @endphp</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td> 
                                                {{ $data->birth_date ? \Carbon\Carbon::parse($data->birth_date)->format('d/m/Y') : '' }}
                                            </td>
                                            <td>{{ $data->email ?? '' }}</td>
                                            <td>{{ $data->phone ?? '' }}</td>
                                            <td>{{ $data->address ?? '' }}</td>
                                            <td>{{ $data->deleted_at ? \Carbon\Carbon::parse($data->deleted_at)->format('H:m:s d/m/Y') : '' }}</td>
                                            <td>
                                                <div class="d-flex gap-2 align-items-center">
                                                    <form action="{{ route('admin.account.restore', $data->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success"
                                                            title="Khôi phục tài khoản">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="30"
                                                                height="20" viewBox="0 0 512 512">
                                                                <path fill="white" fill-rule="evenodd"
                                                                    d="M256 448c-97.974 0-178.808-73.383-190.537-168.183l42.341-5.293c9.123 73.734 71.994 130.809 148.196 130.809c82.475 0 149.333-66.858 149.333-149.333S338.475 106.667 256 106.667c-50.747 0-95.581 25.312-122.567 64h79.9v42.666H64V64h42.667v71.31C141.866 91.812 195.685 64 256 64c106.039 0 192 85.961 192 192s-85.961 192-192 192"
                                                                    clip-rule="evenodd" />
                                                            </svg>

                                                        </button>
                                                    </form>
                                                    <form id="deleteForm-{{ $data->id }}"
                                                        action="{{ route('admin.account.forceDelete', $data->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-sm btn-danger"
                                                            onclick="forceDelete({{ $data->id }}, '{{ $data->name }}')">
                                                            <!-- SVG Icon -->
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="30"
                                                                height="20" viewBox="0 0 24 24">
                                                                <path fill="currentColor"
                                                                    d="M12 4c-4.419 0-8 3.582-8 8s3.581 8 8 8s8-3.582 8-8s-3.581-8-8-8m3.707 10.293a.999.999 0 1 1-1.414 1.414L12 13.414l-2.293 2.293a.997.997 0 0 1-1.414 0a1 1 0 0 1 0-1.414L10.586 12L8.293 9.707a.999.999 0 1 1 1.414-1.414L12 10.586l2.293-2.293a.999.999 0 1 1 1.414 1.414L13.414 12z" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                     @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Không có dữ liệu</td>
                                        </tr>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>
                    </div> <!-- end card body -->
                    <div class="card-footer border-top">
                        <nav aria-label="Page navigation">
                            {!! $trash->links('pagination::bootstrap-5') !!}
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
        function forceDelete(userId, userName) {
            Swal.fire({
                title: `Bạn có chắc muốn xóa vĩnh viễn người dùng "${userName}"?`,
                text: `Thao tác này không thể hoàn tác.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Vâng, xóa!',
                cancelButtonText: 'Không, hủy',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-danger me-2 mt-2',
                    cancelButton: 'btn btn-secondary mt-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`deleteForm-${userId}`).submit();
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
    </script>

@endsection
