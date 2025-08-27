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
                    <li class="breadcrumb-item active" aria-current="page"> Quản lí
                        {{ $roles[request('role')] ?? request('role') }}
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
                <h3 class="">Danh Sách {{ $roles[request('role')] ?? request('role') }}</h3>
                <div class="gap-2">
                    <a href="{{ route('admin.account.add', ['role' => request('role')]) }}" class="btn  btn-primary">
                        <i class="fas fa-plus me-2"></i> Thêm {{ $roles[request('role')] ?? request('role') }}
                    </a>
                    @if (Auth::user()->isAdmin())
                        <a href="{{ route('admin.account.trash.list', ['role' => request('role')]) }}" class="btn btn-secondary">
                            <i class="fas fa-trash me-2"></i> Thùng rác
                        </a>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="card">
                    <div class="card-header ">
                        @if (request('role'))
                            <form id="filterAccountForm" method="GET"
                                action="{{ route('admin.account.list', request('role')) }}" class=" rounded">
                                <div class="row d-flex  align-items-center">
                                    {{-- Sắp xếp --}}
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

                                    {{-- Nhiệm vụ nhân viên --}}
                                    @if (request('role') === 'staff')
                                        <div class="col-md-2">
                                            <select name="mission" class="form-select">
                                                <option value="">Nhiệm vụ</option>
                                                <option value="train"
                                                    {{ request('mission') == 'train' ? 'selected' : '' }}>
                                                    Quản lí đào tạo
                                                </option>
                                                <option value="accountant"
                                                    {{ request('mission') == 'accountant' ? 'selected' : '' }}>
                                                    Kế toán
                                                </option>
                                            </select>
                                        </div>
                                    @endif


                                    <div class="col-md-3">
                                        <div class="position-relative">
                                            <input type="search" name="queryAccountRole" class="form-control"
                                                placeholder="Tìm {{ $roles[request('role')] ?? request('role') }}..."
                                                autocomplete="off"
                                                value="{{ request()->query('queryAccountRole') ?? '' }}">
                                            <iconify-icon icon="solar:magnifer-linear" class="search-widget-icon"
                                                style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; color: #999;"></iconify-icon>
                                        </div>
                                    </div>

                                    <div class="col-md-3 flex-fill">
                                        <button type="submit" class="btn btn-success"><i class="fas fa-search me-2"></i>
                                            Lọc</button>

                                        <button id="clearFilterListAccountBtn" type="button" class="btn btn-danger"><i
                                                class="fas fa-times me-2"></i> Xóa
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
                                        <th>Thông tin</th>
                                        <th>Mã {{ $roles[request('role')] ?? request('role') }}</th>

                                        @if (request('role') === 'student')
                                            <th>Khóa học đã đăng kí</th>
                                        @elseif (request('role') === 'teacher')
                                            <th>Lớp học được phân công</th>
                                        @else
                                            <th>Nhiệm vụ</th>
                                        @endif

                                        <th>Ngày sinh nhật</th>
                                        <th>Email</th>
                                        <th>Số điện thoại</th>
                                        <th>Địa chỉ</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($users as $data)
                                        <tr>
                                            <td>
                                                <div style="display: flex; align-items: center; gap: 10px;">
                                                    <img class="rounded"
                                                        src="{{ $data->avatar ? asset($data->avatar) : asset('icons/user-solid.svg') }}"
                                                        width="40" alt="{{ $data->name }}">
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


                                            <td>{{ $data->snake_case ?? '' }}</td>


                                            @if (request('role') === 'student')
                                                @php
                                                    // đếm số khóa học đã đăng ký
                                                    $count = DB::table('class_student')
                                                        ->where('student_id', $data->id)
                                                        ->count();
                                                @endphp
                                                <td>{{ $count ?? 0 }} lớp</td>
                                            @elseif (request('role') === 'teacher')
                                                @php
                                                    // Lấy tất cả lớp học theo lịch học
                                                    $allClasses = DB::table('schedules')
                                                        ->where('teacher_id', $data->id)
                                                        ->distinct('class_id')
                                                        ->count('class_id');
                                                @endphp
                                                <td>{{ $allClasses ?? 0 }} lớp</td>
                                            @else
                                                @if (request('role') === 'staff')
                                                    <td> @php
                                                        echo match ($data->mission) {
                                                            'train' => 'Quản lý đào tạo',
                                                            'accountant' => 'Kế toán',
                                                            default => 'Chưa có nhiệm vụ',
                                                        };
                                                    @endphp
                                                    </td>
                                                @else
                                                    <td>Quản lí toàn hệ thống</td>
                                                @endif
                                            @endif

                                            <td>
                                                {{ $data->birth_date ? \Carbon\Carbon::parse($data->birth_date)->format('d/m/Y') : '' }}
                                            </td>
                                            <td>{{ $data->email ?? '' }}</td>
                                            <td>{{ $data->phone ?? '' }}</td>
                                            <td>{{ $data->address ?? '' }}</td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    @if (request('role') == 'student' || request('role') == 'teacher')
                                                        <a href="{{ route('admin.account.detail', ['role' => request('role'), 'id' => $data->id]) }}"
                                                            class="btn btn-soft-primary btn-sm">
                                                            <iconify-icon icon="solar:eye-broken"
                                                                class="align-middle fs-18"></iconify-icon>
                                                        </a>
                                                    @endif

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
                                            <td colspan="7" class="text-center">Không tìm thấy
                                                {{ $roles[request('role')] ?? request('role') }} nào.</td>
                                        </tr>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>
                    </div> <!-- end card body -->
                    <div class="card-footer border-top">
                        <nav aria-label="Page navigation">
                            {!! $users->withQueryString()->links('pagination::bootstrap-5') !!}
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
                        </script> &copy; DỰ ÁN TỐT NGHIỆP CAO ĐẲNG FPT POLYTECHNIC  THANH HÓA<iconify-icon
                            icon="iconamoon:heart-duotone" class="fs-18 align-middle text-danger"></iconify-icon> <a
                            href="#" class="fw-bold footer-text" target="_blank">Tiger Code</a>
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
