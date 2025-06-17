{{-- filepath: resources/views/admin/classes/partials/table.blade.php --}}
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col" width="80">#</th>
                        <th scope="col">Tên lớp</th>
                        <th scope="col">Tên khóa học</th>
                        <th scope="col">Số buổi học</th>
                        <th scope="col" width="120">Trạng thái</th>
                        <th scope="col" width="150">Số học sinh</th>
                        <th scope="col" width="120">Ngày tạo</th>
                        <th scope="col" width="120">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($classes as $index => $class)
                        <tr>
                            <th scope="row" class="text-muted">
                                {{ ($classes->currentPage() - 1) * $classes->perPage() + $index + 1 }}
                            </th>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h6 class="mb-0">{{ $class->name }}</h6>
                                        @if ($class->description)
                                            <small class="text-muted">{{ Str::limit($class->description, 50) }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h6 class="mb-0">{{ $class->course_name ?? 'N/A' }}</h6>
                                        @if ($class->course_description)
                                            <small
                                                class="text-muted">{{ Str::limit($class->course_description, 50) }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-calendar-alt text-muted me-2"></i>
                                    <span>{{ $class->sessions_count ?? ($class->so_buoi_hoc ?? 0) }}</span>
                                </div>
                            </td>
                            <td data-status="{{ $class->status }}">
                                @switch($class->status)
                                    @case('in_progress')
                                        <span class="badge bg-success">Hoạt động</span>
                                    @break

                                    @case('not_started')
                                        <span class="badge bg-warning">Tạm dừng</span>
                                    @break

                                    @case('completed')
                                        <span class="badge bg-info">Hoàn thành</span>
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
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                        id="dropdownMenuButton{{ $class->id }}" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $class->id }}">
                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ route('admin.classes.show', $class->id) }}">
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
                                        @if ($class->status == 'active')
                                            <li>
                                                <form action="{{ route('admin.classes.toggle-status', $class->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="dropdown-item text-warning">
                                                        <i class="fas fa-pause me-2"></i>Tạm dừng
                                                    </button>
                                                </form>
                                            </li>
                                        @else
                                            <li>
                                                <form action="{{ route('admin.classes.toggle-status', $class->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="dropdown-item text-success">
                                                        <i class="fas fa-play me-2"></i>Kích hoạt
                                                    </button>
                                                </form>
                                            </li>
                                        @endif
                                        <li>
                                            <button type="button" class="dropdown-item text-danger"
                                                onclick="confirmDelete({{ $class->id }}, '{{ $class->name }}')">
                                                <i class="fas fa-trash me-2"></i>Xóa lớp
                                            </button>
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
