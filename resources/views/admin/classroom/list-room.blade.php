@extends('admin.admin')

@section('title', 'Trang danh sách phòng học')
@section('description', '')
@section('content')
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: '{{ session('success') }}',
                confirmButtonText: 'OK'

            });
        </script>
        {{-- Xoa session thong bao --}}
        {{ session()->forget('success') }}
    @endif
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: '{{ session('error') }}',
                confirmButtonText: 'OK'
            });
        </script>
        {{ session()->forget('error') }}
    @endif

    <div class="page-content">
        <div class="container-xxl">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb px-3 py-1 rounded ">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Quản lí phòng học</li>
                    </ol>
                </nav>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-xl-4">
                    <div class="card border-0 shadow-lg rounded-3">
                        <div class="card-body d-flex align-items-center justify-content-around px-4">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-2">
                                <i class="bi bi-building fs-2 text-primary"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1 text-dark">Tổng Phòng Học</h5>
                                <p class="mb-0 fs-4 fw-semibold text-primary">{{ $totalRooms ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-4">
                    <div class="card border-0 shadow-lg rounded-3">
                        <div class="card-body d-flex align-items-center justify-content-around px-4">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-2">
                                <i class="bi bi-door-open fs-2 text-warning"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1 text-dark">Được phép Sử Dụng</h5>
                                <p class="mb-0 fs-4 fw-semibold text-warning">{{ $roomsInUse ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-4">
                    <div class="card border-0 shadow-lg rounded-3">
                        <div class="card-body d-flex align-items-center justify-content-around px-4">
                            <div class="bg-success bg-opacity-10 rounded-circle p-2">
                                <i class="bi bi-door-closed fs-2 text-success"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1 text-dark">Chưa được sử dụng</h5>
                                <p class="mb-0 fs-4 fw-semibold text-success">{{ $roomsEmpty ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-2 mb-2">

                <!-- Bộ lọc -->
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <form action="{{ route('admin.classroom.list-room') }}" method="GET" id="searchForm"
                                class="row g-2 align-items-end">

                                <input type="hidden" name="limit" id="limit" value="{{ request('limit', 10) }}">
                                <!-- Từ khóa -->
                                <div class="col">
                                    <label for="name" class="form-label mb-1">Từ khóa</label>
                                    <input type="text" name="room_name" id="room_name"
                                        class="form-control form-control-sm" placeholder="Tên phòng học"
                                        value="{{ request('room_name') }}">
                                </div>

                                <!-- Trạng thái -->
                                <div class="col">
                                    <label for="status" class="form-label mb-1">Trạng thái phòng</label>
                                    <select name="status" id="status" class="form-select form-select-sm">
                                        <option value="">Tất cả</option>
                                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Chưa được
                                            sử dụng
                                        </option>
                                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Được phép
                                            sử dụng
                                        </option>
                                    </select>
                                </div>

                                <!-- Sức chứa -->
                                <div class="col">
                                    <label for="capacity" class="form-label mb-1">Sức chứa</label>
                                    <input type="number" name="capacity" id="capacity"
                                        class="form-control form-control-sm" placeholder="Sức chứa"
                                        value="{{ request('capacity') }}">
                                </div>

                                <!-- Nút Lọc & Xóa -->
                                <div class="col-auto d-flex gap-1">
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <iconify-icon icon="ic:baseline-filter-alt" class="me-1"></iconify-icon> Lọc
                                    </button>
                                    <a href="{{ route('admin.classroom.list-room') }}" class="btn btn-danger btn-sm">
                                        <iconify-icon icon="ic:round-clear" class="me-1"></iconify-icon> Xóa
                                    </a>
                                </div>

                                <!-- Nút thêm -->
                                <div class="col-auto">
                                    <a href="{{ route('admin.classroom.create') }}" class="btn btn-primary btn-sm">Thêm
                                        phòng học</a>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>

                <!-- Bảng dữ liệu -->
                <div class="col-xl-12">
                    <div class="card">
                        <div class="table-responsive table-gridjs">
                            <table class="table align-middle mb-0 table-hover table-centered">
                                <thead class="table-light">
                                    <tr>
                                        <th>STT</th>
                                        <th>Tên Phòng Học</th>
                                        <th>Trạng Thái Phòng</th>
                                        <th>Sức Chứa </th>
                                        <th>Ghi chú </th>
                                        <th>Ngày tạo </th>
                                        <th>Ngày cập nhật </th>
                                        <th>Hành Động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($classrooms as $key => $room)
                                        <tr>
                                            <td>{{ $classrooms->firstItem() + $key }}</td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span class="fw-bold">{{ $room->room_name }}</span>
                                                    <small class="text-muted">{{ $room->classes_count }} lớp đang
                                                        học</small>
                                                </div>
                                            </td>

                                            <td>
                                                @if ($room->status == 0)
                                                    <small class="bade bg-secondary-subtle text-secondary rounded p-1">Chưa được
                                                        sử dụng</small>
                                                @else
                                                    <small class="bade bg-success-subtle text-success rounded p-1">Được phép sử
                                                        dụng</small>
                                                @endif
                                            </td>


                                            <td>{{ $room->capacity ?? '-' }}</td>
                                            <td>{{ $room->note ?? '' }}</td>
                                            <td>{{ $room->created_at ? $room->created_at->format('d/m/Y H:i') : '' }}</td>
                                            <td>{{ $room->updated_at ? $room->updated_at->format('d/m/Y H:i') : '' }}</td>

                                            <!-- Hiển thị giờ bắt đầu và kết thúc sử dụng -->
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-light btn-sm dropdown-toggle" type="button"
                                                        data-bs-toggle="dropdown">
                                                        Thao tác <iconify-icon icon="tabler:caret-down-filled"
                                                            class="ms-1"></iconify-icon>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a href="{{ route('admin.classroom.detail-room', $room->id) }}"
                                                                class="dropdown-item text-primary"><iconify-icon
                                                                    icon="solar:eye-broken"
                                                                    class="me-1"></iconify-icon>Chi tiết</a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ route('admin.classroom.edit', $room->id) }}"
                                                                class="dropdown-item text-warning">
                                                                <iconify-icon icon="solar:pen-2-broken"
                                                                    class="me-1"></iconify-icon>Sửa
                                                            </a>
                                                        </li>

                                                        <li>
                                                            <form id="delete-form-{{ $room->id }}"
                                                                action="{{ route('admin.classroom.delete', $room->id) }}"
                                                                method="POST" style="display:inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="button" class="dropdown-item text-danger"
                                                                    data-status="{{ $room->status_text }}"
                                                                    data-has-class="{{ \App\Models\Schedule::where('room', $room->room_name)->exists() ? '1' : '0' }}"
                                                                    onclick="confirmDelete({{ $room->id }}, this)">
                                                                    <iconify-icon
                                                                        icon="solar:trash-bin-minimalistic-2-broken"
                                                                        class="align-middle fs-18 me-1"></iconify-icon>
                                                                    Xóa
                                                                </button>


                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Không có dữ liệu</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 m-3">
                                <div id="pagination-wrapper" class="flex-grow-1">
                                    {{ $classrooms->links('pagination::bootstrap-5') }}
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
        function confirmDelete(id, btn) {
            let status = btn.getAttribute('data-status');
            let hasClass = btn.getAttribute('data-has-class');

            if (hasClass === '1') {
                Swal.fire({
                    icon: 'error',
                    title: 'Không thể xóa!',
                    text: 'Phòng đã có lớp được xếp, không thể xóa.',
                    confirmButtonText: 'OK'
                });
                return;
            }

            if (status === 'Đang sử dụng') {
                Swal.fire({
                    icon: 'error',
                    title: 'Không thể xóa!',
                    text: 'Phòng đang sử dụng, không thể xóa.',
                    confirmButtonText: 'OK'
                });
                return;
            }

            Swal.fire({
                title: 'Bạn chắc chắn muốn xoá?',
                text: 'Hành động này không thể hoàn tác.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Vâng, xoá!',
                cancelButtonText: 'Huỷ',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }

         // Xử lý thay đổi số dòng hiển thị
        $('#limit2').change(function() {
            const limitValue = $(this).val();
            $('#searchForm #limit').val(limitValue);
            $('#searchForm').submit();
        });
    </script>
@endpush
