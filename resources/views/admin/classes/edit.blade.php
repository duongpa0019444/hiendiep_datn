{{-- filepath: d:\DATN\hiendiep_datn\resources\views\admin\classes\edit.blade.php --}}
@extends('admin.admin')

@section('title', 'Chỉnh sửa lớp học')
@section('description', 'Cập nhật thông tin lớp học')

@section('content')
    <div class="page-content">
        <div class="container-xxl">
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="mb-1">Chỉnh sửa lớp học</h3>
                    <p class="text-muted mb-0">ID: {{ $class->id }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.classes.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại danh sách
                    </a>
                </div>
            </div>

            <!-- Alert Messages -->
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
                            timer: 2500,
                            showConfirmButton: false
                        });
                    });
                </script>
            @endif

            <!-- Class Details Card -->
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.classes.update', $class->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên lớp</label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ old('name', $class->name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="courses_id" class="form-label">Khóa học</label>
                            <select class="form-select" id="courses_id" name="courses_id" required>
                                <option value="">-- Chọn khóa học --</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}"
                                        {{ $class->courses_id == $course->id ? 'selected' : '' }}>
                                        {{ $course->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="number_of_sessions" class="form-label">Số buổi học</label>
                            <input type="number" class="form-control" id="number_of_sessions" name="number_of_sessions"
                                value="{{ old('number_of_sessions', $class->number_of_sessions) }}" required min="1">
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Trạng thái</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="not_started" {{ $class->status == 'not_started' ? 'selected' : '' }}>Tạm
                                    dừng</option>
                                <option value="in_progress" {{ $class->status == 'in_progress' ? 'selected' : '' }}>Hoạt
                                    động</option>
                                <option value="completed" {{ $class->status == 'completed' ? 'selected' : '' }}>Hoàn thành
                                </option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Lưu thay đổi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
