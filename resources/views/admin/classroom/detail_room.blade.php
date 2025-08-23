@extends('admin.admin')

@section('title', 'Trang admin')
@section('description', '')
@section('content')

    <div class="page-content">
        <div class="container-xxl">
            <nav aria-label="breadcrumb p-0">
                <ol class="breadcrumb py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.classroom.list-room') }}">Quản Lý Phòng Học</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Chi Tiết Phòng Học</li>
                </ol>
            </nav>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Lịch Học Các Lớp Học Phòng: {{ $classroom->room_name }}</h4>

                        </div>

                        <!-- Bộ lọc -->
                        <div class="card-body border-bottom">
                            <form action="{{ route('admin.classroom.detail-room', $classroom->id) }}" method="GET"
                                class="row g-2 align-items-end">

                                <!-- Tên lớp -->
                                <div class="col">
                                    <label for="name" class="form-label mb-1">Tên lớp học</label>
                                    <input type="text" name="name" id="class_name"
                                        class="form-control form-control-sm" placeholder="Nhập tên lớp"
                                        value="{{ request('name') }}">
                                </div>

                                <!-- Ngày -->
                                <div class="col">
                                    <label for="date" class="form-label mb-1">Ngày</label>
                                    <input type="date" name="date" id="date" class="form-control form-control-sm"
                                        value="{{ request('date') }}">
                                </div>

                                <!-- Giờ bắt đầu -->
                                <div class="col">
                                    <label for="start_time" class="form-label mb-1">Giờ bắt đầu </label>
                                    <input type="time" name="start_time" id="start_time"
                                        class="form-control form-control-sm" value="{{ request('start_time') }}">
                                </div>

                                <!-- Giờ kết thúc -->
                                <div class="col">
                                    <label for="end_time" class="form-label mb-1">Giờ kết thúc</label>
                                    <input type="time" name="end_time" id="end_time"
                                        class="form-control form-control-sm" value="{{ request('end_time') }}">
                                </div>

                                <!-- Nút -->
                                <div class="col d-flex align-items-end gap-2">
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <iconify-icon icon="ic:baseline-filter-alt" class="me-1"></iconify-icon> Lọc
                                    </button>
                                    <a href="{{ route('admin.classroom.detail-room', $classroom->id) }}"
                                        class="btn btn-danger btn-sm">
                                        <iconify-icon icon="ic:round-clear" class="me-1"></iconify-icon> Xóa
                                    </a>
                                </div>
                            </form>

                        </div>

                        <!-- Bảng danh sách -->
                        <div class="card-body">
                            <table class="table table-hover table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">STT</th>
                                        <th scope="col">Tên Lớp Học</th>
                                        <th scope="col">Tên Khóa Học</th>
                                        <th scope="col">Trạng Thái Lớp Học</th>
                                        <th scope="col">Ngày</th>
                                        <th scope="col">Giờ Bắt Đầu</th>
                                        <th scope="col">Giờ Kết Thúc</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($schedules as $index => $schedule)
                                        @php
                                            $classObj = $schedule->class;
                                            $courseObj = $classObj?->course;
                                            $status = $classObj?->status;

                                            // Map label + badge class cho đẹp
                                            $statusMap = [
                                                'in_progress' => ['label' => 'Đang hoạt động', 'badge' => 'bg-success'],
                                                'not_started' => [
                                                    'label' => 'Tạm dừng',
                                                    'badge' => 'bg-warning text-dark',
                                                ],
                                                'completed' => ['label' => 'Đã hoàn thành', 'badge' => 'bg-secondary'],
                                            ];
                                            $st = $statusMap[$status] ?? [
                                                'label' => 'Không xác định',
                                                'badge' => 'bg-light text-dark',
                                            ];
                                        @endphp
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $classObj?->name ?? 'Không xác định' }}</td>
                                            <td>{{ $courseObj?->name ?? '—' }}</td>
                                            <td>
                                                <span class="badge {{ $st['badge'] }}">{{ $st['label'] }}</span>
                                            </td>
                                            <td>{{ $schedule->date?->format('d/m/Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">Không có lịch học</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <div class="mt-3">
                                <a href="{{ route('admin.classroom.list-room') }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bx bx-arrow-back"></i> Trở về trang Quản Lý Phòng
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <footer class="footer mt-4">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 text-center small text-muted">
                        <script>
                            document.write(new Date().getFullYear())
                        </script>
                        &copy; DỰ ÁN TỐT NGHIỆP CAO ĐẲNG FPT THANH HÓA
                        <iconify-icon icon="iconamoon:heart-duotone" class="text-danger"></iconify-icon>
                        <a href="#" class="fw-bold footer-text" target="_blank">NHÓM 4</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: '{{ session('success') }}',
                confirmButtonText: 'Đóng',
            });
        </script>
    @endif

@endsection
