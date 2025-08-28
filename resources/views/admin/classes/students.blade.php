{{-- filepath: resources/views/admin/classes/students.blade.php --}}
@extends('admin.admin')

@section('title', 'Danh sách học viên')
@section('description', 'Quản lý học viên trong lớp')

@section('content')
    <div class="page-content">
        <div class="container-xxl">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="mb-1">Danh sách học viên lớp: {{ $class->name }}</h3>
                    <p class="text-muted mb-0">Khóa học: {{ $course->name ?? 'N/A' }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.classes.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại danh sách lớp
                    </a>
                    <!-- Nút mở modal thêm học viên -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                        <i class="fas fa-user-plus"></i> Thêm học viên
                    </button>
                    <!-- Nút mở modal khôi phục học viên -->
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                        data-bs-target="#restoreStudentModal">
                        <i class="fas fa-undo"></i> Khôi phục học viên
                    </button>
                </div>
            </div>

            <!-- Thông báo -->
            @if (session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công!',
                            text: '{{ session('success') }}',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    });
                </script>
            @endif
            @if (session('error'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi!',
                            text: '{{ session('error') }}',
                            confirmButtonText: 'OK', // Nút OK
                            showConfirmButton: true, // Hiển thị nút
                        });
                    });
                </script>
            @endif

            <!-- Bộ lọc -->
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-filter"></i> Bộ lọc tìm kiếm
                        </h5>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="toggleFilter">
                            <i class="fas fa-chevron-down" id="filterIcon"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" id="filterContent">
                    <form method="GET" action="{{ route('admin.classes.students', $class->id) }}" class="row g-3">
                        <div class="col-md-4">
                            <label for="search" class="form-label">Tìm kiếm</label>
                            <input type="text" class="form-control" id="search" name="search"
                                placeholder="Tên hoặc email..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="sort_by" class="form-label">Sắp xếp theo</label>
                            <select class="form-select" id="sort_by" name="sort_by">
                                <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Tên (A-Z)
                                </option>
                                <option value="name_desc" {{ request('sort_by') == 'name_desc' ? 'selected' : '' }}>Tên
                                    (Z-A)</option>
                                <option value="email" {{ request('sort_by') == 'email' ? 'selected' : '' }}>Email (A-Z)
                                </option>
                                <option value="email_desc" {{ request('sort_by') == 'email_desc' ? 'selected' : '' }}>Email
                                    (Z-A)</option>
                                <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Thêm
                                    gần nhất</option>
                                <option value="created_at_desc"
                                    {{ request('sort_by') == 'created_at_desc' ? 'selected' : '' }}>Thêm cũ nhất</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="per_page" class="form-label">Hiển thị</label>
                            <select class="form-select" id="per_page" name="per_page">
                                <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Tìm kiếm
                                </button>
                                <a href="{{ route('admin.classes.students', $class->id) }}"
                                    class="btn btn-outline-secondary">
                                    <i class="fas fa-times"></i> Xóa bộ lọc
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Thông tin tổng quan -->
            {{-- <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card border-left-primary">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Tổng số học viên</h6>
                                    <h4 class="mb-0 text-primary">{{ $students->total() ?? $students->count() }}</h4>
                                </div>
                                <div class="flex-shrink-0">
                                    <i class="fas fa-users fa-2x text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-left-info">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Kết quả hiển thị</h6>
                                    <h4 class="mb-0 text-info">
                                        @if ($students instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                            {{ $students->firstItem() ?? 0 }}-{{ $students->lastItem() ?? 0 }} /
                                            {{ $students->total() }}
                                        @else
                                            {{ $students->count() }}
                                        @endif
                                    </h4>
                                </div>
                                <div class="flex-shrink-0">
                                    <i class="fas fa-list fa-2x text-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}

            <!-- Danh sách học viên -->
            <div class="card">
                <div class="card-body">
                    @if ($students->isEmpty())
                        <div class="text-center text-muted py-4">Chưa có học viên nào trong lớp này.</div>
                    @else
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Họ tên</th>
                                    <th>Email</th>
                                    <th style="width: 120px;">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($students as $i => $student)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $student->name }}</td>
                                        <td>{{ $student->email }}</td>
                                        <td>
                                            <form
                                                action="{{ route('admin.classes.remove-student', [$class->id, $student->id]) }}"
                                                method="POST" class="d-inline delete-student-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-user-minus"></i> Xóa
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
                <div id="pagination-wrapper" class="flex-grow-1">
                    {{ $students->links('pagination::bootstrap-5') }}
                </div>
            </div>

            <!-- Modal thêm học viên -->
            <div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('admin.classes.add-student', $class->id) }}" method="POST"
                        class="modal-content">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="addStudentModalLabel">Thêm học viên vào lớp</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="student_id" class="form-label">Chọn học viên</label>
                                <select class="form-select" id="student_id" name="student_id" required data-choices>
                                    <option value="">-- Chọn học viên --</option>
                                    @foreach ($availableStudents as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-user-plus"></i> Thêm
                            </button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal khôi phục học viên -->
    <div class="modal fade" id="restoreStudentModal" tabindex="-1" aria-labelledby="restoreStudentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="restoreStudentModalLabel">Khôi phục học viên đã xóa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if (isset($trashedStudents) && $trashedStudents->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Họ tên</th>
                                        <th>Email</th>
                                        <th>Ngày xóa</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($trashedStudents as $trashedStudent)
                                        <tr>
                                            <td>{{ $trashedStudent->name }}</td>
                                            <td>{{ $trashedStudent->email }}</td>
                                            <td>{{ $trashedStudent->deleted_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <form
                                                    action="{{ route('admin.classes.restore-student', [$class->id, $trashedStudent->id]) }}"
                                                    method="POST" class="d-inline restore-student-form">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="fas fa-undo"></i> Khôi phục
                                                    </button>
                                                </form>
                                                <form
                                                    action="{{ route('admin.classes.force-delete-student', [$class->id, $trashedStudent->id]) }}"
                                                    method="POST" class="d-inline force-delete-student-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-trash"></i> Xóa vĩnh viễn
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-info-circle fa-2x mb-3"></i>
                            <p>Không có học viên nào đã bị xóa khỏi lớp này.</p>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Toggle bộ lọc
                $('#toggleFilter').click(function() {
                    $('#filterContent').slideToggle();
                    $('#filterIcon').toggleClass('fa-chevron-down fa-chevron-up');
                });

                // Ẩn/hiện bộ lọc theo trạng thái
                @if (!request()->hasAny(['search', 'sort_by', 'per_page']))
                    $('#filterContent').hide();
                    $('#filterIcon').removeClass('fa-chevron-up').addClass('fa-chevron-down');
                @else
                    $('#filterIcon').removeClass('fa-chevron-down').addClass('fa-chevron-up');
                @endif

                // Tooltip
                $('[data-bs-toggle="tooltip"]').tooltip();

                // Auto submit khi thay đổi sort hoặc per_page
                $('#sort_by, #per_page').change(function() {
                    $(this).closest('form').submit();
                });

                // Enter để search
                $('#search').keypress(function(e) {
                    if (e.which == 13) {
                        $(this).closest('form').submit();
                    }
                });

                // Khởi tạo Choices.js cho dropdown thêm học viên
                const studentSelect = document.getElementById('student_id');
                if (studentSelect) {
                    new Choices(studentSelect, {
                        searchEnabled: true,
                        itemSelectText: '',
                        shouldSort: false,
                        placeholder: true,
                        placeholderValue: '-- Chọn học viên --',
                        searchPlaceholderValue: 'Tìm kiếm học viên...',
                        noResultsText: 'Không tìm thấy kết quả',
                        noChoicesText: 'Không còn lựa chọn nào',
                        maxItemCount: 5,
                        renderSelectedChoices: 'always',
                        searchResultLimit: 10,
                        removeItemButton: false
                    });
                }
            });
            // Xác nhận xóa học viên
            $(document).on('submit', '.delete-student-form', function(e) {
                e.preventDefault();
                let form = this;
                Swal.fire({
                    title: 'Bạn có chắc chắn muốn xóa học viên này khỏi lớp?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Xóa',
                    cancelButtonText: 'Hủy',
                    confirmButtonClass: 'btn btn-primary w-xs me-2 mt-2',
                    cancelButtonClass: 'btn btn-danger w-xs mt-2',
                    buttonsStyling: false,
                    showCloseButton: false
                }).then(function(result) {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            // Xác nhận khôi phục học viên
            $(document).on('submit', '.restore-student-form', function(e) {
                e.preventDefault();
                let form = this;
                Swal.fire({
                    title: 'Bạn có chắc chắn muốn khôi phục học viên này?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Khôi phục',
                    cancelButtonText: 'Hủy',
                    confirmButtonClass: 'btn btn-success w-xs me-2 mt-2',
                    cancelButtonClass: 'btn btn-secondary w-xs mt-2',
                    buttonsStyling: false,
                    showCloseButton: false
                }).then(function(result) {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            // Xác nhận xóa vĩnh viễn học viên
            $(document).on('submit', '.force-delete-student-form', function(e) {
                e.preventDefault();
                let form = this;
                Swal.fire({
                    title: 'CẢNH BÁO!',
                    text: 'Bạn có chắc chắn muốn xóa vĩnh viễn học viên này? Hành động này không thể hoàn tác!',
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonText: 'Xóa vĩnh viễn',
                    cancelButtonText: 'Hủy',
                    confirmButtonClass: 'btn btn-danger w-xs me-2 mt-2',
                    cancelButtonClass: 'btn btn-secondary w-xs mt-2',
                    buttonsStyling: false,
                    showCloseButton: false
                }).then(function(result) {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        </script>
    @endpush
@endsection
