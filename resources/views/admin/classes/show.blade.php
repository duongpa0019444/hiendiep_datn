{{-- filepath: d:\DATN\hiendiep_datn\resources\views\admin\classes\show.blade.php --}}
@extends('admin.admin')

@section('title', 'Chi tiết lớp học')
@section('description', 'Xem chi tiết lớp học')

@section('content')
    <div class="page-content">
        <div class="container-xxl">
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="mb-1">Chi tiết lớp học</h3>
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
                    <table class="table table-bordered mb-0">
                        <tr>
                            <th width="200">Tên lớp</th>
                            <td>{{ $class->name }}</td>
                        </tr>
                        <tr>
                            <th>Khóa học</th>
                            <td>{{ $class->course_name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Số buổi đã học</th>
                            <td>{{ $class->so_buoi_hoc ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Trạng thái</th>
                            <td>
                                @switch($class->status)
                                    @case('in_progress')
                                        <span class="badge bg-success">Hoạt động</span>
                                    @break

                                    @case('not_started')
                                        <span class="badge bg-warning">Chưa bắt đầu</span>
                                    @break

                                    @case('completed')
                                        <span class="badge bg-info">Hoàn thành</span>
                                    @break

                                    @default
                                        <span class="badge bg-secondary">{{ $class->status }}</span>
                                @endswitch
                            </td>
                        </tr>
                        <tr>
                            <th>Số học sinh</th>
                            <td>
                                <a href="{{ route('admin.classes.students', $class->id) }}">
                                    Danh sách ({{ $class->so_hoc_sinh ?? 0 }})
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th>Ngày tạo</th>
                            <td>{{ $class->created_at ? $class->created_at->format('d/m/Y H:i') : '' }}</td>
                        </tr>
                        <tr>
                            <th>Ngày cập nhật</th>
                            <td>{{ $class->updated_at ? $class->updated_at->format('d/m/Y H:i') : '' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
