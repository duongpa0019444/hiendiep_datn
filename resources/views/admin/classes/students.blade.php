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
                                <select class="form-select" id="student_id" name="student_id" required>
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

    @push('scripts')
        <script>
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
        </script>
    @endpush
@endsection
