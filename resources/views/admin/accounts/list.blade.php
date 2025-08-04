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

            @if (session('error'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Toastify({
                            text: "{{ session('error') }}",
                            gravity: "top",
                            position: "center",
                            className: "error",
                            duration: 4000,
                            style: {
                                background: "red", // 👈 đổi màu nền
                            }
                        }).showToast();
                    });
                </script>
            @endif

            <div class="d-flex justify-content-between align-items-center w-100 mb-2">
                <h3 class="">Danh sách </h3>
                <a href="{{ route('admin.account.add', ['role' => request('role')]) }}" class="btn  btn-primary">
                    Thêm {{ $roles[request('role')] ?? request('role') }}
                </a>
            </div>

            <div class="row">
                <div class="card">
                    <div class="card-header ">
                        @if (request('role'))
                            <form id="filterAccountForm" method="GET"
                                action="{{ route('admin.account.list', request('role')) }}" class=" rounded p-2">
                                <div class="row g-2 align-items-end  flex-end">
                                    {{-- Phân loại --}}
                                    <div class="col-md-2">
                                        <select name="gender" class="form-select">
                                            <option value="">Giới tính</option>
                                            <option value="boy" {{ request('gender') == 'boy' ? 'selected' : '' }}>Nam
                                            </option>
                                            <option value="girl" {{ request('gender') == 'girl' ? 'selected' : '' }}>Nữ
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <select class="form-select" id="sort" name="sort">
                                            <option value="created_at_desc"
                                                {{ request('sort') == 'created_at_desc' ? 'selected' : '' }}>Mới nhất
                                            </option>
                                            <option value="created_at_asc"
                                                {{ request('sort') == 'created_at_asc' ? 'selected' : '' }}>
                                                Cũ nhất</option>
                                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>
                                                Tên A-Z
                                            </option>
                                            <option value="name_desc"
                                                {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Tên Z-A
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative">
                                            <input type="search" name="queryAccountRole" class="form-control"
                                                placeholder="Tìm học sinh..." autocomplete="off"
                                                value="{{ request()->query('queryAccountRole') ?? '' }}">
                                            <iconify-icon icon="solar:magnifer-linear" class="search-widget-icon"
                                                style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; color: #999;"></iconify-icon>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-success w-100">Lọc</button>
                                    </div>

                                    <div class="col-md-2 ">
                                        <button id="clearFilterListAccountBtn" type="button"
                                            class="btn btn-danger w-100">Xóa
                                            Lọc</button>
                                    </div>


                                </div>
                            </form>
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
                                                        <iconify-icon icon="solar:eye-broken"
                                                            class="align-middle fs-18"></iconify-icon>
                                                    </a>

                                                    <a href="{{ route('admin.account.edit', ['role' => request('role'), 'id' => $data->id]) }}"
                                                        class="btn btn-soft-primary btn-sm"><iconify-icon
                                                            icon="solar:pen-2-broken"
                                                            class="align-middle fs-18"></iconify-icon></a>

                                                   @if (auth()->user()->isAdmin())
                                                         <a href="#" class="btn btn-soft-danger btn-sm"
                                                        onclick="showDeleteConfirm({{ $data->id }}, '{{ $data->name }}', '{{ request('role') }}')">
                                                        <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"
                                                            class="align-middle fs-18"></iconify-icon></a>
                                                   @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Không tìm thấy {{ $roles[request('role')] ?? request('role') }} nào.</td>
                                        </tr>
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
        document.getElementById('clearFilterListAccountBtn').addEventListener('click', function() {
            const form = document.getElementById('filterAccountForm');

            // Xóa tất cả input/select trong form
            form.reset();

            // Redirect về URL gốc không có query
            window.location.href = "{{ route('admin.account.list', ['role' => request('role')]) }}";

        });

        function showDeleteConfirm(id, name, role) {
            Swal.fire({
                title: 'Xác nhận xóa',
                text: `Bạn có chắc chắn muốn xóa người dùng "${name}" không?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `/admin/account/delete/${role}/${id}`;
                }
            });
        }
    </script>



@endsection
