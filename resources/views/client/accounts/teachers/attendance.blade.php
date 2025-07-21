@extends('client.accounts.information')

@section('content-information')
    <div id="schedule" class="content-section">
        <div class="card">
                <div class="card-body">
                    <!-- Class Info Header -->
                    <div class="class-header">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="mb-2" id="classInfo">
                                    <i class="fas fa-graduation-cap me-2"></i>
                                    <span id="className">{{ $scheduleData->class_name ?? 'Không có lớp' }}</span>
                                </h4>
                                <p class="mb-0" id="eventInfo">
                                    <i class="fas fa-book me-2"></i>
                                    <span id="eventName">{{ $scheduleData->course_name ?? 'Không có môn học' }}</span>
                                    <br>
                                    <i class="fas fa-calendar me-2"></i>
                                    <span
                                        id="eventDate">{{ $scheduleData->date ? \Carbon\Carbon::parse($scheduleData->date)->format('d/m/Y') : 'Không có ngày' }}</span>
                                    <i class="fas fa-clock ms-3 me-2"></i>
                                    <span
                                        id="eventTime">{{ $scheduleData->start_time && $scheduleData->end_time ? \Carbon\Carbon::parse($scheduleData->start_time)->format('H:i') . ' - ' . \Carbon\Carbon::parse($scheduleData->end_time)->format('H:i') : 'Không có thời gian' }}</span>
                                </p>
                            </div>
                            <div class="col-md-4 text-end">
                                <h5 class="mb-0">
                                    <i class="fas fa-users me-2"></i>
                                    <span id="totalStudents">{{ $students->count() }} học sinh</span>
                                    @if ($scheduleData->status == 1)
                                        <span class="text-success ms-2">| Đã điểm danh</span>
                                    @else
                                        <span class="text-danger ms-2">| Chưa điểm danh</span>
                                    @endif
                                </h5>
                            </div>
                        </div>
                    </div>

                    <!-- Attendance Summary -->
                    <div class="attendance-summary">
                        <div class="row g-3">
                            <div class="col-md-4 col-sm-6">
                                <div class="summary-item">
                                    <span class="summary-number text-success" id="presentCount">-</span>
                                    <span class="summary-label">Có mặt</span>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="summary-item">
                                    <span class="summary-number text-danger" id="absentCount">-</span>
                                    <span class="summary-label">Vắng mặt</span>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="summary-item">
                                    <span class="summary-number text-secondary" id="undoneCount">-</span>
                                    <span class="summary-label">Chưa điểm danh</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="quick-actions">
                        <button class="quick-action-btn btn-success" onclick="markAllStatus('present')">
                            <i class="fas fa-check-circle me-1"></i>
                            Có mặt tất cả
                        </button>
                        <button class="quick-action-btn btn-danger" onclick="markAllStatus('absent')">
                            <i class="fas fa-times-circle me-1"></i>
                            Vắng tất cả
                        </button>
                        <button class="quick-action-btn btn-warning" onclick="clearAll()">
                            <i class="fas fa-undo me-1"></i>
                            Xóa tất cả
                        </button>
                        {{-- <button class="quick-action-btn btn-info" onclick="autoMarkPresent()">
                            <i class="fas fa-magic me-1"></i>
                            Tự động điểm danh
                        </button> --}}
                    </div>

                    <!-- Search Box -->
                    <div class="mb-4">
                        <div class="position-relative">
                            <i
                                class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                            <input type="text" class="form-control search-box" id="searchStudent"
                                placeholder="Tìm kiếm học sinh theo tên hoặc giới tính...">
                        </div>
                    </div>

                    <!-- Student List -->
                    <div class="student-list" id="studentList">
                        @foreach ($students as $student)
                            <div class="student-item" data-id="{{ $student->id }}"
                                data-name="{{ strtolower($student->name) }}"
                                data-gender="{{ strtolower($student->gender == 'female' ? 'nữ' : 'nam') }}">
                                <div class="student-avatar">
                                    <img src="{{ $student->avatar ? asset('storage/' . $student->avatar) : asset('images/default-avatar.png') }}"
                                        alt="{{ $student->name }}" class="img-fluid rounded-circle">
                                    @php
                                        $status = $attendance[$student->id]['status'] ?? 'undone';
                                        $statusText = match ($status) {
                                            'present' => 'Có mặt',
                                            'absent' => 'Vắng',
                                            // 'late' => 'Muộn',
                                            // 'excused' => 'Có phép',
                                            default => 'Chưa điểm danh',
                                        };
                                    @endphp
                                    <span class="student-status {{ $status }}">
                                        {{ $statusText }}
                                    </span>
                                </div>

                                <div class="student-info">
                                    <div class="student-name">{{ $student->name }}</div>
                                    <div class="student-id">
                                        <i class="fas fa-{{ $student->gender == 'female' ? 'venus' : 'mars' }} me-1"></i>
                                        {{ $student->gender == 'female' ? 'Nữ' : 'Nam' }}

                                        @if ($student->birth_date)
                                            | <i class="fas fa-birthday-cake me-1 text-muted"></i>
                                            {{ \Carbon\Carbon::parse($student->birth_date)->age }} tuổi
                                        @endif
                                    </div>
                                </div>

                                <div class="attendance-controls d-flex align-items-center gap-2">
                                    <button
                                        class="status-btn present {{ isset($attendance[$student->id]) && $attendance[$student->id]['status'] === 'present' ? 'active' : '' }}"
                                        onclick="setAttendance('{{ $student->id }}', 'present', '{{ $scheduleData->id }}')">
                                        <i class="fas fa-check"></i> Có mặt
                                    </button>
                                    <button
                                        class="status-btn absent {{ isset($attendance[$student->id]) && $attendance[$student->id]['status'] === 'absent' ? 'active' : '' }}"
                                        onclick="setAttendance('{{ $student->id }}', 'absent', '{{ $scheduleData->id }}')">
                                        <i class="fas fa-times"></i> Vắng mặt
                                    </button>

                                    {{-- <input type="text" class="form-control flex-grow-1" id="attendance_{{ $student->id }}" placeholder="Nhập ghi chú điểm danh..."> --}}
                                    <button class="status-btn note"
                                        onclick="openNoteModal('{{ $student->id }}', '{{ $student->name }}')">
                                        <i class="fas fa-sticky-note"></i> Ghi chú
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Modal for Note -->
                    <div class="modal fade" id="noteModal" tabindex="-1" aria-labelledby="noteModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="noteModalLabel">Ghi chú điểm danh</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Học sinh:</strong> <span id="studentName"></span></p>
                                    <textarea class="form-control" id="noteInput" rows="4" placeholder="Nhập ghi chú điểm danh..."></textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                    <button type="button" class="btn btn-primary" onclick="saveNote()">Lưu ghi
                                        chú</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- No Results Message -->
                    <div class="no-results" id="noResults" style="display: none;">
                        <i class="fas fa-search fa-3x mb-3 text-muted"></i>
                        <h5>Không tìm thấy học sinh nào</h5>
                        <p>Thử tìm kiếm với từ khóa khác</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Quay lại
                    </a>
                    {{-- <button type="button" class="btn btn-primary" onclick="previewAttendance()">
                        <i class="fas fa-eye me-1"></i>Xem trước
                    </button> --}}
                    <button type="button" class="btn btn-success" onclick="saveAttendance()">
                        <i class="fas fa-save me-1"></i>Lưu điểm danh
                    </button>
                </div>
            </div>
    </div>
@endsection
