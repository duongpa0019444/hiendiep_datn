@extends('client.accounts.information')

@section('content-information')
    <div id="classroom" class="content-section">
        <h2>Lớp học</h2>

        @if ($classes->isEmpty())
            <div class="alert alert-info d-flex align-items-center" role="alert">
                <i class="bi bi-info-circle-fill me-2"></i>
                <span>Bạn chưa tham gia lớp học nào.</span>
            </div>
        @else
            @php
                // Group classes by class_id to merge schedules
                $groupedClasses = $classes->groupBy('class_id');
                // Map day names to Vietnamese
                $dayNames = [
                    'Mon' => 'T2',
                    'Tue' => 'T3', 
                    'Wed' => 'T4',
                    'Thu' => 'T5',
                    'Fri' => 'T6',
                    'Sat' => 'T7',
                    'Sun' => 'CN'
                ];
            @endphp
            
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Tên lớp</th>
                            <th>Khóa học</th>
                            <th>Giảng viên</th>
                            <th>Số học viên</th>
                            <th>Lịch học</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($groupedClasses as $classId => $classSchedules)
                            @php
                                $firstClass = $classSchedules->first();
                                // Get unique schedules
                                $uniqueSchedules = $classSchedules->filter(function($item) {
                                    return !empty($item->day_of_week);
                                })->unique(function($item) {
                                    return $item->day_of_week . '-' . $item->start_time . '-' . $item->end_time;
                                });
                            @endphp
                            <tr>
                                <td><strong>{{ $firstClass->class_name }}</strong></td>
                                <td>
                                    <a href="{{ route('teacher.courses.show', $firstClass->course_id) }}"
                                        class="text-decoration-none">
                                        <span class="badge bg-primary cursor-pointer hover-badge">
                                            {{ $firstClass->course_name ?? 'N/A' }}
                                            <i class="fas fa-external-link-alt ms-1" style="font-size: 0.7em;"></i>
                                        </span>
                                    </a>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $firstClass->teacher_name ?: 'Chưa có giảng viên' }}</strong>
                                        @if($firstClass->teacher_email)
                                            <br>
                                            <small class="text-muted">{{ $firstClass->teacher_email }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-users me-1"></i>
                                        {{ $firstClass->students_count }} học viên
                                    </span>
                                </td>
                                <td>
                                    @if($uniqueSchedules->count() > 0)
                                        <div class="schedule-container">
                                            @php
                                                // dd($uniqueSchedules);
                                                // Ensure days are mapped to Vietnamese
                                                $daysList = $uniqueSchedules->map(function($schedule) use ($dayNames) {
                                                    $dayKey = ucfirst(strtolower($schedule->day_of_week)); // Normalize to match dayNames keys
                                                    return isset($dayNames[$dayKey]) ? $dayNames[$dayKey] : $schedule->day_of_week;
                                                })->unique()->implode(', ');
                                            @endphp
                                            <div class="schedule-days">
                                                <strong>{{ $daysList ?: 'Không xác định' }}</strong>
                                            </div>
                                            <div class="schedule-times mt-1">
                                                <small class="text-muted">
                                                    {{ date('H:i', strtotime($uniqueSchedules->first()->start_time)) }} - 
                                                    {{ date('H:i', strtotime($uniqueSchedules->first()->end_time)) }}
                                                </small>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">Chưa có lịch</span>
                                    @endif
                                </td>
                                <td>
                                    @switch($firstClass->class_status)
                                        @case('in_progress')
                                            <span class="badge bg-success">Đang hoạt động</span>
                                            @break
                                        @case('not_started')
                                            <span class="badge bg-secondary">Chưa bắt đầu</span>
                                            @break
                                        @case('completed')
                                            <span class="badge bg-primary">Đã hoàn thành</span>
                                            @break
                                        @default
                                            <span class="badge bg-light text-dark">{{ $firstClass->class_status }}</span>
                                    @endswitch
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        
        <!-- Phân trang -->
        @if($classes->hasPages())
            <div id="pagination-wrapper" class="d-flex justify-content-center mt-4">
                {{ $classes->links('pagination::bootstrap-5') }}
            </div>
        @endif

        <!-- Thống kê nhanh -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    @php
                        $uniqueClassCount = $classes->unique('class_id')->count();
                    @endphp
                    Tổng cộng: <strong>{{ $uniqueClassCount }}</strong> lớp học
                    @if($classes->count() > 0)
                        ({{ $classes->count() }} bản ghi lịch học)
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .table th {
            font-weight: 600;
            border-top: none;
        }
        
        .btn-group-sm > .btn {
            padding: 0.25rem 0.5rem;
        }
        
        .badge {
            font-size: 0.75em;
        }
        
        .schedule-container {
            padding: 0.5rem;
            background-color: #f8f9fa;
            border-radius: 0.375rem;
        }
        
        .schedule-days {
            font-weight: 600;
            color: #0d6efd;
        }
        
        .schedule-times {
            font-size: 0.85em;
        }
        
        .content-section h2 {
            margin-bottom: 1.5rem;
            color: #495057;
        }
        
        .table-responsive {
            border-radius: 0.375rem;
            overflow: hidden;
        }
        
        @media (max-width: 768px) {
            .btn-group {
                flex-direction: column;
            }
            
            .table-responsive {
                font-size: 0.875rem;
            }
            
            .schedule-days {
                font-size: 0.9em;
            }
            
            .schedule-times {
                font-size: 0.8em;
            }
        }
    </style>
@endsection