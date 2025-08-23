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
                                    <span>{{ $class->sessions_count }}</span>
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
                                    <span>{{ $class->students_count }}</span>
                                </div>
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($class->start_date ?? $class->created_at)->format('d/m/Y') }}
                                </small>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.schedules.show', $class->id) }}"
                                        class="btn btn-sm btn-info">
                                        <i class="fas fa-calendar-alt"></i> Xem lịch học
                                    </a>
                                    <a href="{{ route('admin.schedules.create', $class->id) }}"
                                        class="btn btn-sm btn-primary">
                                        <i class="fas fa-plus"></i> Tạo lịch học
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
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
