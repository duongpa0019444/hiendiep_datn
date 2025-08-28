@extends('admin.admin')

@section('title', 'Quản lí lớp học')
@section('description', 'Manage your classes here.')

@section('content')
    <div class="page-content">
        <div class="container-xxl">
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="mb-1">Quản lý lớp học</h3>
                    <p class="text-muted mb-0">Tổng số: {{ $classes->total() ?? $classes->count() }} lớp học</p>
                </div>
                <div class="d-flex gap-2">
                    {{-- <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal"
                        data-bs-target="#filterModal">
                        <i class="fas fa-filter"></i> Bộ lọc
                    </button> --}}
                    <a href="" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addClassModal">
                        <i class="fas fa-plus"></i> Thêm lớp mới
                    </a>
                    <!-- Modal Thêm lớp mới -->
                    <div class="modal fade" id="addClassModal" tabindex="-1" aria-labelledby="addClassModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('admin.classes.store') }}" method="POST">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addClassModalLabel">Thêm lớp mới</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Tên lớp -->
                                        <div class="mb-3">
                                            <label for="class-name" class="form-label">Tên lớp</label>
                                            <input type="text" class="form-control" id="class-name" name="name"
                                                required>
                                        </div>
                                        <!-- Danh sách khóa học -->
                                        <div class="mb-3">
                                            <label for="course-id" class="form-label">Khóa học</label>
                                            <select class="form-select" id="course-id" name="courses_id" required>
                                                <option value="">-- Chọn khóa học --</option>
                                                @foreach ($courses as $course)
                                                    <option value="{{ $course->id }}">{{ $course->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!-- Trạng thái -->
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Trạng thái</label>
                                            <select class="form-select" id="status" name="status" required>
                                                <option value="in_progress">Hoạt động</option>
                                                <option value="not_started">Chưa bắt đầu</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Đóng</button>
                                        <button type="submit" class="btn btn-primary">Lưu</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filter Section -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.classes.index') }}" class="row g-3 align-items-end mb-0"
                        id="filter-search-form">
                        <div class="col-md-3">
                            <label for="search" class="form-label">Tìm kiếm</label>
                            <input type="text" class="form-control" id="search" name="search"
                                placeholder="Nhập tên lớp hoặc khóa học..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label">Trạng thái</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">Tất cả trạng thái</option>
                                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>Đang
                                    hoạt động
                                </option>
                                <option value="not_started" {{ request('status') == 'not_started' ? 'selected' : '' }}>Chưa
                                    bắt đầu
                                </option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Đã hoàn
                                    thành</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="sort" class="form-label">Sắp xếp theo</label>
                            <select class="form-select" id="sort" name="sort">
                                <option value="created_at_desc"
                                    {{ request('sort') == 'created_at_desc' ? 'selected' : '' }}>Mới nhất</option>
                                <option value="created_at_asc" {{ request('sort') == 'created_at_asc' ? 'selected' : '' }}>
                                    Cũ nhất</option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên A-Z
                                </option>
                                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Tên Z-A
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-outline-primary w-100">
                                <i class="fas fa-search"></i> Tìm kiếm
                            </button>
                            <button type="button" class="btn btn-outline-secondary w-100" id="reset-filter-btn">
                                <i class="fas fa-times"></i> Xóa lọc
                            </button>
                        </div>
                    </form>
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

            <!-- Classes Table -->
            @if ($classes->isEmpty())
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-graduation-cap fa-3x text-muted"></i>
                    </div>
                    <h5 class="text-muted">Chưa có lớp học nào</h5>
                    <p class="text-muted">Hãy tạo lớp học đầu tiên của bạn</p>
                    <a href="" class="btn btn-primary mb-3" data-bs-toggle="modal"
                        data-bs-target="#addClassModal">
                        <i class="fas fa-plus"></i> Thêm lớp mới
                    </a>
                </div>
            @else
                <div id="classes-table-wrapper">
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" width="80">#</th>
                                            <th scope="col">Tên lớp</th>
                                            <th scope="col">Tên khóa học</th>
                                            <th scope="col">Số buổi đã học</th>
                                            <th scope="col" width="120">Trạng thái</th>
                                            <th scope="col" width="150">Số học sinh</th>
                                            <th scope="col" width="120">Ngày tạo</th>
                                            <th scope="col" width="120">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($classes as $index => $class)
                                            <tr id="row-{{ $class->id }}">
                                                <th scope="row" class="text-muted">
                                                    {{ ($classes->currentPage() - 1) * $classes->perPage() + $index + 1 }}
                                                </th>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div>
                                                            <h6 class="mb-0">{{ $class->name }}</h6>
                                                            @if ($class->description)
                                                                <small
                                                                    class="text-muted">{{ Str::limit($class->description, 50) }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div>
                                                            <h6 class="mb-0">{{ $class->course_name ?? 'N/A' }}</h6>
                                                            @if ($class->course_description)
                                                                <small class="text-muted">{!! Str::limit($class->course_description, 50) !!}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-calendar-alt text-muted me-2"></i>
                                                        <span>{{ $class->number_of_sessions ?? 0 }}</span>
                                                    </div>
                                                </td>
                                                <td data-status="{{ $class->status }}">
                                                    @switch($class->status)
                                                        @case('in_progress')
                                                            <span class="badge bg-soft-success text-success">Đang hoạt động</span>
                                                        @break

                                                        @case('not_started')
                                                            <span class="badge bg-soft-warning text-warning">Chưa bắt đầu</span>
                                                        @break

                                                        @case('completed')
                                                            <span class="badge bg-soft-info text-info">Đã hoàn thành</span>
                                                        @break

                                                        @default
                                                            <span class="badge bg-secondary">{{ $class->status }}</span>
                                                    @endswitch
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-users text-muted me-2"></i>
                                                        <span>{{ $class->students_count ?? ($class->so_hoc_sinh ?? 0) }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        {{ \Carbon\Carbon::parse($class->start_date ?? $class->created_at)->format('d/m/Y') }}
                                                    </small>
                                                </td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                            type="button" id="dropdownMenuButton{{ $class->id }}"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </button>
                                                        <ul class="dropdown-menu"
                                                            aria-labelledby="dropdownMenuButton{{ $class->id }}">
                                                            <li>
                                                                <a class="dropdown-item"
                                                                    href="{{ route('admin.classes.detail', $class->id) }}">
                                                                    <i class="fas fa-eye me-2"></i>Xem chi tiết
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item"
                                                                    href="{{ route('admin.classes.edit', $class->id) }}">
                                                                    <i class="fas fa-edit me-2"></i>Chỉnh sửa
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item"
                                                                    href="{{ route('admin.classes.students', $class->id) }}">
                                                                    <i class="fas fa-users me-2"></i>Quản lý học sinh
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <hr class="dropdown-divider">
                                                            </li>
                                                            @if ($class->status == 'in_progress')
                                                                <li>
                                                                    <form
                                                                        action="{{ route('admin.classes.toggle-status', $class->id) }}"
                                                                        method="POST" class="d-inline">
                                                                        @csrf
                                                                        @method('PATCH')
                                                                        <button type="submit"
                                                                            class="dropdown-item text-warning">
                                                                            <i class="fas fa-pause me-2"></i>Chưa bắt đầu
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                            @elseif ($class->status == 'not_started')
                                                                <li>
                                                                    <form
                                                                        action="{{ route('admin.classes.toggle-status', $class->id) }}"
                                                                        method="POST" class="d-inline">
                                                                        @csrf
                                                                        @method('PATCH')
                                                                        <button type="submit"
                                                                            class="dropdown-item text-success">
                                                                            <i class="fas fa-play me-2"></i> Hoạt động
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                            @endif
                                                            {{-- Không cho phép thao tác với lớp đã hoàn thành --}}
                                                            <li>
                                                                {{-- <form
                                                                    action="{{ route('admin.classes.destroy', $class->id) }}"
                                                                    method="POST" style="display:inline;"
                                                                    id="sweetalert-params">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="dropdown-item text-danger"
                                                                        data-id="{{ $class->id }}">
                                                                        <i class="fas fa-trash"></i> Xóa lớp
                                                                    </button>
                                                                </form> --}}
                                                                @if (auth()->user()->isAdmin())
                                                                    <button type="button"
                                                                        class="dropdown-item text-danger btn-delete-class"
                                                                        data-id="{{ $class->id }}">
                                                                        <i class="fas fa-trash me-2"></i>Xóa lớp
                                                                    </button>
                                                                @endif
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center text-muted py-4">
                                                        Không có dữ liệu phù hợp
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div id="pagination-wrapper" class="flex-grow-1">
                            {{ $classes->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                @endif

            </div>

            {{-- Loading --}}
            <div id="ajax-loading"
                style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;z-index:2000;background:rgba(255,255,255,0.5);align-items:center;justify-content:center;">
                <div class="spinner-border text-primary" role="status"></div>
            </div>

            {{-- Thông báo Toast --}}
            <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 2000">
                <div id="success-toast" class="toast align-items-center text-bg-success border-0" role="alert">
                    <div class="d-flex">
                        <div class="toast-body"></div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto"
                            data-bs-dismiss="toast"></button>
                    </div>
                </div>
            </div>

        @endsection

        @push('scripts')
            <script>
                $(document).ready(function() {
                    // Hiệu ứng loading
                    function showLoading() {
                        $('#ajax-loading').show();
                    }

                    function hideLoading() {
                        $('#ajax-loading').hide();
                    }

                    // Toast Bootstrap
                    function showSuccessToast(msg) {
                        $('#success-toast .toast-body').text(msg);
                        var toast = new bootstrap.Toast(document.getElementById('success-toast'));
                        toast.show();
                    }

                    // Xử lý submit form tìm kiếm/bộ lọc
                    $('#filter-search-form').on('submit', function(e) {
                        e.preventDefault();
                        fetchClasses($(this).serialize());
                    });

                    // Xử lý phân trang ajax
                    $(document).on('click', '#classes-table-wrapper .pagination a', function(e) {
                        e.preventDefault();
                        let url = $(this).attr('href');
                        let params = url.split('?')[1];
                        fetchClasses(params);
                    });

                    function fetchClasses(params) {
                        $.ajax({
                            url: "{{ route('admin.classes.index') }}",
                            type: 'GET',
                            data: params,
                            beforeSend: function() {
                                $('#classes-table-wrapper').css('opacity', '0.5');
                            },
                            success: function(data) {
                                // Render lại phần bảng và phân trang
                                // let html = $(data).find('#classes-table-wrapper').html();
                                $('#classes-table-wrapper').html(data);
                                // console.log('Class status:', $(this).data('status'));
                                $('#classes-table-wrapper').css('opacity', '1');
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Lỗi!',
                                    text: 'Đã xảy ra lỗi, vui lòng thử lại!',
                                    confirmButtonText: 'Đóng'
                                });
                                $('#classes-table-wrapper').css('opacity', '1');
                            }
                        });
                    }

                    // Xử lý nút xóa lọc
                    $('#reset-filter-btn').on('click', function() {
                        $('#filter-search-form')[0].reset();
                        fetchClasses('');
                    });

                });

                // $('#add-class-form').on('submit', function(e) {
                //     e.preventDefault();
                //     let formData = $(this).serialize();
                //     $.ajax({
                //         url: $(this).attr('action'),
                //         type: 'POST',
                //         data: formData,
                //         success: function(response) {
                //             $('#addClassModal').modal('hide');
                //             $('#add-class-form')[0].reset();
                //             // Hiển thị thông báo thành công
                //             alert('Lớp học đã được thêm thành công!');
                //             // Tải lại danh sách lớp học
                //             fetchClasses('');
                //         },
                //         error: function(xhr) {
                //             alert('Đã xảy ra lỗi, vui lòng thử lại!');
                //         }
                //     });
                // });

                //Parameter
                $(document).on('click', '.btn-delete-class', function() {
                    const button = this;
                    const id = button.getAttribute('data-id');
                    Swal.fire({
                        title: 'Bạn có chắc chắn muốn xóa?',
                        text: "Hành động này không thể hoàn tác!",
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
                            fetch('/admin/classes/' + id, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                            .getAttribute('content'),
                                        'Accept': 'application/json'
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire({
                                            title: 'Đã xóa!',
                                            text: 'Lớp học đã được xóa.',
                                            icon: 'success',
                                            confirmButtonClass: 'btn btn-primary w-xs mt-2',
                                            buttonsStyling: false
                                        }).then(() => {
                                            document.getElementById(`row-${id}`).remove();
                                        });
                                    } else {
                                        Swal.fire({
                                            title: 'Lỗi!',
                                            text: data.message || 'Có lỗi xảy ra.',
                                            icon: 'error',
                                            confirmButtonClass: 'btn btn-primary mt-2',
                                            buttonsStyling: false
                                        });
                                    }
                                })
                                .catch(error => {
                                    Swal.fire({
                                        title: 'Lỗi!',
                                        text: 'Không thể xóa lớp học.',
                                        icon: 'error',
                                        confirmButtonClass: 'btn btn-primary mt-2',
                                        buttonsStyling: false
                                    });
                                });
                        }
                    });
                });
            </script>
        @endpush
