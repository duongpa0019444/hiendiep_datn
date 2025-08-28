<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle schedule-table mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width:40px;">#</th>
                        <th class="text-center" style="width:80px;">Thứ</th>
                        <th class="text-center" style="width:120px;">Ngày</th>
                        <th class="text-center" style="width:110px;">Giờ bắt đầu</th>
                        <th class="text-center" style="width:110px;">Giờ kết thúc</th>
                        <th class="text-center" style="width:160px;">Giáo viên</th>
                        <th class="text-center" style="width:110px;">Phòng học</th>
                        <th class="text-center" style="width:110px;">Trạng thái</th>
                        <th class="text-center" style="width:110px;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($schedules as $index => $schedule)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center fw-bold {{ $schedule->day_of_week == 'Sun' ? 'text-danger' : '' }}">
                                {{ [
                                    'Mon' => 'Thứ 2',
                                    'Tue' => 'Thứ 3',
                                    'Wed' => 'Thứ 4',
                                    'Thu' => 'Thứ 5',
                                    'Fri' => 'Thứ 6',
                                    'Sat' => 'Thứ 7',
                                    'Sun' => 'CN',
                                ][$schedule->day_of_week] ?? $schedule->day_of_week }}
                            </td>
                            <td class="text-center">
                                {{ \Carbon\Carbon::parse($schedule->date)->format('d/m/Y') }}</td>
                            <td class="text-center">{{ $schedule->start_time }}</td>
                            <td class="text-center">{{ $schedule->end_time }}</td>
                            <td>{{ $schedule->teacher_name ?? 'N/A' }}</td>
                            <td class="text-center">{{ $schedule->classroom ?? '' }}</td>
                            <td class="text-center">
                                {{ $schedule->status == 0 ? 'Chưa điểm danh' : ($schedule->status == 1 ? 'Đã điểm danh' : 'N/A') }}
                            </td>
                            <td class="text-center">
                                @if ($schedule->status == 0)
                                    <a href="#" class="btn btn-sm btn-outline-primary btn-edit-schedule"
                                        data-id="{{ $schedule->id }}" title="Sửa buổi học">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    @if (auth()->user()->isAdmin())
                                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-danger btn-delete-schedule"
                                                        data-id="{{ $schedule->id }}" title="Xóa buổi học">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Không có lịch học nào
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="pagination-wrapper" class="flex-grow-1">
    {{ $schedules->links('pagination::bootstrap-5') }}
</div>
