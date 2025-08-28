@extends('admin.admin')

@section('title', 'Trang admin')
@section('description', '')
@section('content')

    <div class="page-content">
        <div class="container-xxl">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <nav aria-label="breadcrumb p-0">
                    <ol class="breadcrumb py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.classroom.list-room') }}">Quản Lý Phòng Học</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Chi Tiết Phòng Học</li>
                    </ol>
                </nav>
                <div class="mt-3">
                    <a href="{{ route('admin.classroom.list-room') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bx bx-arrow-back"></i> Quay lại
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow-sm">
                        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <h4 class="card-title mb-0">
                                Danh sach lớp học phòng:
                                <span class="text-primary">{{ $classroom->room_name }}</span>
                            </h4>

                            <form action="{{ route('admin.classroom.detail-room', $classroom->id) }}" method="GET"
                                id="searchForm" class="row g-2 align-items-end flex-grow-1 flex-md-grow-0 col-12 col-md-6">
                                <input type="hidden" name="limit" id="limit" value="{{ request('limit', 9) }}">
                                <!-- Tên lớp -->
                                <div class="col-12 col-md-8">
                                    <label for="class_name" class="form-label mb-1 fw-semibold small">Tên lớp học</label>
                                    <select name="name" id="class_name" class="form-select form-select-sm w-100"
                                        data-choices>
                                        <option selected value=""> Tất cả các lớp </option>
                                        @foreach ($allClasses as $className)
                                            <option value="{{ $className }}"
                                                {{ request('name') == $className ? 'selected' : '' }}>
                                                {{ $className }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Nút -->
                                <div class="col-12 col-md-4 d-flex gap-2">
                                    <button type="submit" class="btn btn-success btn-sm flex-fill">
                                        <iconify-icon icon="ic:baseline-filter-alt" class="me-1"></iconify-icon> Tìm kiếm
                                    </button>
                                    <a href="{{ route('admin.classroom.detail-room', $classroom->id) }}"
                                        class="btn btn-danger btn-sm flex-fill">
                                        <iconify-icon icon="ic:round-clear" class="me-1"></iconify-icon> Xóa
                                    </a>
                                </div>
                            </form>
                        </div>

                        <!-- Bảng danh sách -->
                        <!-- Danh sách lớp dưới dạng card -->
                        <div class="card-body">
                            <div class="row g-3">
                                @forelse ($classes as $index => $class)
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="card h-100 border-1 rounded-3">
                                            <div class="card-body d-flex flex-column">

                                                <!-- Tiêu đề -->
                                                <h5 class="fw-bold mb-2 text-center">
                                                    <a href="{{ route('admin.classes.schedules', $class->id) }}" class="text-decoration-none text-primary">
                                                        <i class="bi bi-journal-bookmark me-1"></i>
                                                        {{ $index + 1 }}. {{ $class->name }}
                                                    </a>
                                                </h5>
                                                <div class="d-flex justify-content-between align-items-start">

                                                    <!-- Khóa học -->
                                                    <p class="mb-0">
                                                        <i class="bi bi-book me-1 text-secondary"></i>
                                                        <span class="fw-semibold">Khóa học:</span>
                                                        {{ $class->course->name ?? '---' }}
                                                    </p>

                                                    <!-- Trạng thái -->
                                                    <p class="mb-0">
                                                        @if ($class->status === 'in_progress')
                                                            <span class="bg-info-subtle text-info p-1 rounded">
                                                                <i class="bi bi-hourglass-split me-1"></i>Đang học
                                                            </span>
                                                        @elseif ($class->status === 'completed')
                                                            <span class="bg-success-subtle text-success p-1 rounded">
                                                                <i class="bi bi-check-circle me-1"></i>Đã hoàn thành
                                                            </span>
                                                        @elseif ($class->status === 'not_started')
                                                            <span class="bg-danger-subtle text-danger p-1 rounded">
                                                                <i class="bi bi-x-circle me-1"></i>Chưa bắt đầu
                                                            </span>
                                                        @else
                                                            <span class="bg-secondary-subtle text-secondary p-1 rounded">
                                                                <i
                                                                    class="bi bi-question-circle me-1"></i>{{ $class->status }}
                                                            </span>
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="text-center text-muted py-3 border rounded">
                                            Không có lớp nào trong phòng này
                                        </div>
                                    </div>
                                @endforelse
                            </div>


                            <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 m-3">
                                <div id="pagination-wrapper" class="flex-grow-1">
                                    {{ $classes->links('pagination::bootstrap-5') }}
                                </div>

                                <div class="d-flex align-items-center" style="min-width: 160px;">
                                    <label for="limit2" class="form-label mb-0 me-2 small">Hiển thị</label>
                                    <select name="limit2" id="limit2" class="form-select form-select-sm"
                                        style="width: 100px;">
                                        <option value="9" selected>9</option>
                                        <option value="18">18</option>
                                        <option value="45">45</option>
                                        <option value="90">90</option>
                                    </select>
                                </div>
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
                        &copy; DỰ ÁN TỐT NGHIỆP CAO ĐẲNG FPT POLYTECHNIC  THANH HÓA
                         <iconify-icon icon="iconamoon:heart-duotone" class="text-danger"></iconify-icon>
                        <a href="#" class="fw-bold footer-text" target="_blank">Tiger Code</a>
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

@push('scripts')
    <script>
        // Xử lý thay đổi số dòng hiển thị
        $('#limit2').change(function() {
            const limitValue = $(this).val();
            $('#searchForm #limit').val(limitValue);
            $('#searchForm').submit();
        });
    </script>
@endpush
