@extends('client.accounts.information')

@section('content-information')
    <div id="classroom" class="content-section">
        <h5>Lớp học được phân công</h5>

        @if ($classes->isEmpty())
            <div class="alert alert-info d-flex align-items-center" role="alert">
                <i class="bi bi-info-circle-fill me-2"></i>
                <span>Bạn chưa được phân công dạy lớp học nào.</span>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>TÊN LỚP</th>
                            <th>KHÓA HỌC</th>
                            <th>SĨ SỐ</th>
                            <th>SỐ BUỔI HỌC</th>
                            <th>TRẠNG THÁI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Group classes by class_name and course
                            $groupedClasses = $classes->groupBy(function ($class) {
                                return $class->class_name . '_' . $class->course_id;
                            });
                        @endphp

                        @foreach ($groupedClasses as $classGroup)
                            @php
                                $firstClass = $classGroup->first();
                                $totalStudents = $classGroup->sum('students_count') ?: $firstClass->students_count;
                            @endphp
                            <tr>
                                <td>
                                    <strong>{{ $firstClass->class_name }}</strong>
                                    @if (isset($firstClass->class_code) && $firstClass->class_code)
                                        <br><small class="text-muted">Mã: {{ $firstClass->class_code }}</small>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('teacher.courses.show', $firstClass->course_id) }}"
                                        class="text-decoration-none">
                                        <span class="badge bg-primary cursor-pointer hover-badge">
                                            {{ $firstClass->course_name ?? 'N/A' }}
                                            <i class="fas fa-external-link-alt ms-1" style="font-size: 0.7em;"></i>
                                        </span>
                                    </a>
                                    @if (isset($firstClass->credits) && $firstClass->credits)
                                        <br><small class="text-muted">{{ $firstClass->credits }} tín chỉ</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ $totalStudents ?? 0 }} học sinh
                                    </span>
                                </td>
                                <td class="text-center">
                                    {{ $firstClass->number_of_sessions ?? 'Chưa xác định' }}
                                </td>
                                <td>
                                    @php
                                        // Lấy trạng thái phổ biến nhất hoặc trạng thái của class đầu tiên
                                        $status = $firstClass->class_status;
                                    @endphp

                                    @if ($status == 'in_progress')
                                        <span class="badge bg-secondary">Đang hoạt động</span>
                                    @elseif($status == 'not_started')
                                        <span class="badge bg-danger">Chưa bắt đầu</span>
                                    @elseif($status == 'completed')
                                        <span class="badge bg-success">Đã hoàn thành</span>
                                    @else
                                        <span class="badge bg-light text-dark">{{ $status }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Pagination -->
        <div id="pagination-wrapper" class="flex-grow-1 mt-4">
            {{ $classes->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <style>
        .table td {
            vertical-align: middle;
        }

        .table td hr {
            margin: 0.25rem 0;
            border-color: #dee2e6;
        }

        .hover-badge:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection
