@extends('client.accounts.information')

@section('title', 'Điểm danh lớp học')
@section('description', 'Quản lý điểm danh lớp học của bạn!')
@push('styles')
    {{-- <link rel="stylesheet" href="{{ asset('client/plugins/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('client/plugins/css/icofont.css') }}" />
    <link rel="stylesheet" href="{{ asset('client/plugins/css/custom.css') }}" /> --}}
    <link rel="stylesheet" href="{{ asset('client/attendance.css') }}" />
@endpush

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

    <!-- Loading Modal -->
    <div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center py-4">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mb-0">Đang xử lý...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Modal -->
    <div class="modal fade" id="previewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Xem trước kết quả điểm danh</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="previewContent">
                    <!-- Preview content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-success" onclick="saveAttendance()">Lưu điểm danh</button>
                </div>
            </div>
        </div>
    </div>

@endsection


@push('script')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer>
        console.log('Script loaded');
        let attendanceDataObj = @json($attendance);
        let attendanceData = Object.entries(attendanceDataObj).map(([studentId, data]) => ({
            student_id: Number(studentId),
            status: data.status,
            note: data.note
        }));
        // console.log('Initial attendanceData:', attendanceData);
        let scheduleId = '{{ $scheduleData->id }}';
        let totalStudents = {{ $students->count() }};
        // let loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
        let previewModal = new bootstrap.Modal(document.getElementById('previewModal'));
        let currentStudentId = null;

        // Initialize the page
        document.addEventListener('DOMContentLoaded', async function() {
            const modalEl = document.getElementById('loadingModal');
            if (modalEl) {
                loadingModal = new bootstrap.Modal(modalEl, {
                    backdrop: 'static',
                    keyboard: false
                });
            }
            
            // Đồng bộ giao diện với attendanceData
            attendanceData.forEach(item => {
                updateStudentStatus(item.student_id, item.status);
                if (item.note) {
                    updateStudentNote(item.student_id, item.note);
                }
            });
            updateSummary();
            setupEventListeners();

            console.log('Attendance system initialized');
            console.log('Initial attendanceData:', attendanceData);
        });

        function setupEventListeners() {
            // Search functionality
            document.getElementById('searchStudent').addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                filterStudents(searchTerm);
            });

            // Keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                if (e.ctrlKey) {
                    switch (e.key) {
                        case 's':
                            e.preventDefault();
                            saveAttendance();
                            break;
                        case 'a':
                            e.preventDefault();
                            markAllStatus('present');
                            break;
                    }
                }
            });
        }

        function openNoteModal(studentId, studentName) {
            // Input validation
            if (!studentId || !studentName) {
                console.error('studentId and studentName are required');
                return;
            }

            currentStudentId = studentId;
            document.getElementById('studentName').textContent = studentName;

            // Load existing note if any
            const existingData = attendanceData.find(item => item.student_id == studentId);
            const noteInput = document.getElementById('noteInput');

            if (noteInput) {
                noteInput.value = existingData ? existingData.note : '';
                // Clear any previous validation states
                noteInput.classList.remove('is-invalid');
            }

            // Show modal
            const modalElement = document.getElementById('noteModal');
            if (modalElement) {
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
                console.log(attendanceData);

                // Focus on input when modal opens
                modalElement.addEventListener('shown.bs.modal', function() {
                    noteInput?.focus();
                }, {
                    once: true
                });
            } else {
                console.error('noteModal element not found');
            }
        }


        function saveNote() {
            const note = document.getElementById('noteInput').value;

            // Find existing data or create new
            let existingIndex = attendanceData.findIndex(item => item.student_id == currentStudentId);

            if (existingIndex >= 0) {
                // Update existing note
                attendanceData[existingIndex].note = note;
            } else {
                // Add new record
                attendanceData.push({
                    student_id: parseInt(currentStudentId),
                    status: 'undone', // default status
                    note: note
                });
            }

            console.log('Current attendanceData:', attendanceData);

            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('noteModal'));
            modal.hide();

            // Optional: Show success message
            showToast('success', 'Đã lưu ghi chú thành công!');
        }

        function filterStudents(searchTerm) {
            const studentItems = document.querySelectorAll('.student-item');
            let visibleCount = 0;

            studentItems.forEach(item => {
                const name = item.dataset.name;
                const gender = item.dataset.gender;
                const isVisible = name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                    gender.toLowerCase().includes(searchTerm.toLowerCase());

                item.style.display = isVisible ? 'flex' : 'none';
                if (isVisible) visibleCount++;
            });

            document.getElementById('noResults').style.display = visibleCount === 0 ? 'block' : 'none';
        }

        // ...existing code...
        function setAttendance(studentId, status, scheduleId) {
            // Tìm xem studentId đã có trong attendanceData chưa
            const existingIndex = attendanceData.findIndex(item => item && item.student_id === parseInt(studentId));

            // Nếu đã có, cập nhật status
            if (existingIndex !== -1) {
                attendanceData[existingIndex] = {
                    student_id: parseInt(studentId),
                    status: status,
                    note: attendanceData[existingIndex].note || '' // Lấy note cũ nếu có
                };
            } else {
                // Nếu chưa có, thêm mới
                attendanceData.push({
                    student_id: parseInt(studentId),
                    status: status,
                    note: '',
                });
            }

            // Update UI
            updateStudentStatus(studentId, status);
            // updateStudentNote(studentId, status);
            updateSummary();
            showToast('success', 'Đã cập nhật trạng thái điểm danh!');

            // Log for debugging
            console.log('Updated attendanceData:', attendanceData);
        }

        function updateStudentStatus(studentId, status) {
            const studentItem = document.querySelector(`.student-item[data-id="${studentId}"]`);
            const statusSpan = studentItem.querySelector('.student-status');
            const buttons = studentItem.querySelectorAll('.status-btn');
            // Cập nhật văn bản trạng thái
            const statusText = {
                present: 'Có mặt',
                // late: 'Muộn',
                absent: 'Vắng mặt'
                // excused: 'Có phép'
            } [status] || 'Chưa điểm danh';
            statusSpan.textContent = statusText;
            statusSpan.className = `student-status ${status}`;

            // Cập nhật trạng thái nút
            buttons.forEach(btn => {
                btn.classList.remove('active');
                if (btn.classList.contains(status)) {
                    btn.classList.add('active');
                }
            });
        }

        function updateStudentNote(studentId, note) {
            const studentItem = document.querySelector(`.student-item[data-id="${studentId}"]`);
            const noteSpan = studentItem.querySelector('.student-note');
            if (noteSpan) {
                noteSpan.textContent = note || 'Chưa có ghi chú';
            }
        }

        function getStatusText(status) {
            const statusMap = {
                'present': 'Có mặt',
                'absent': 'Vắng mặt',
                // 'late': 'Muộn',
                // 'excused': 'Có phép',
                'undone': 'Chưa điểm danh'
            };
            return statusMap[status] || 'Chưa điểm danh';
        }

        function markAllStatus(status) {
            Swal.fire({
                title: 'Xác nhận',
                text: `Bạn có chắc chắn muốn đánh dấu tất cả học sinh là "${getStatusText(status)}"?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Xác nhận',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    const studentItems = document.querySelectorAll('.student-item:not([style*="display: none"])');
                    studentItems.forEach(item => {
                        const studentId = item.dataset.id;
                        setAttendance(studentId, status, scheduleId);
                    });
                    updateSummary();
                    showToast('success', 'Đã cập nhật điểm danh cho tất cả học sinh');
                }
            });
        }

        function clearAll() {
            Swal.fire({
                title: 'Xác nhận xóa',
                text: 'Bạn có chắc chắn muốn xóa tất cả dữ liệu điểm danh?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Xóa tất cả',
                cancelButtonText: 'Hủy',
                confirmButtonColor: '#dc3545'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.querySelectorAll('.student-item').forEach(item => {
                        const studentId = item.dataset.id;
                        item.querySelectorAll('.status-btn').forEach(btn => btn.classList.remove('active'));
                        const statusBadge = item.querySelector('.student-status');
                        statusBadge.className = 'student-status undone';
                        statusBadge.textContent = 'Chưa điểm danh';
                        delete attendanceData[studentId];
                    });
                    updateSummary();
                    showToast('success', 'Đã xóa tất cả dữ liệu điểm danh');
                }
            });
        }

        async function updateSummary() {
            try {
                const response = await fetch('/admin/attendance/summary', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        scheduleId: scheduleId
                    })
                });

                const result = await response.json();
                // const.log(result);
                if (result.success) {
                    // Update UI with data from the database
                    document.getElementById('presentCount').textContent = result.data.present || 0;
                    document.getElementById('absentCount').textContent = result.data.absent || 0;
                    document.getElementById('undoneCount').textContent = result.data.undone || 0;
                    // Note: totalStudents is static in the HTML and not updated here
                } else {
                    console.error('Error fetching summary:', result.message);
                    updateSummaryFromLocal(); // Fallback to local data
                }
            } catch (error) {
                console.error('Error calling summary API:', error);
                updateSummaryFromLocal(); // Fallback to local data
            }
        }

        // Thêm function resetSummaryToZero
        function updateSummaryFromLocal() {
            const stats = {
                present: 0,
                absent: 0,
                undone: 0
            };

            // Count statuses from local attendanceData
            attendanceData.forEach(item => {
                if (item && item.status) {
                    stats[item.status]++;
                }
            });

            // Update UI with local counts
            document.getElementById('presentCount').textContent = stats.present;
            document.getElementById('absentCount').textContent = stats.absent;
            document.getElementById('undoneCount').textContent = stats.undone;
        }

        function getStatusColor(status) {
            const colorMap = {
                'present': 'success',
                'absent': 'danger',
                // 'late': 'warning',
                // 'excused': 'info',
                'undone': 'secondary'
            };
            return colorMap[status] || 'secondary';
        }

        function saveAttendance() {
            const hasData = Object.keys(attendanceData).length > 0;

            if (!hasData) {
                showToast('warning', 'Chưa có dữ liệu điểm danh nào để lưu');
                return;
            }

            Swal.fire({
                title: 'Xác nhận lưu',
                text: 'Bạn có chắc chắn muốn lưu kết quả điểm danh này?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Lưu',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading(true);

                    fetch('{{ route('attendance.save') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify({
                                schedule_id: scheduleId,
                                attendance_data: attendanceData
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                console.log(attendanceData);
                                showLoading(false); // Tắt loading trước
                                Swal.fire({
                                    title: 'Thành công!',
                                    text: 'Điểm danh đã được lưu thành công',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    showLoading(false); // Tắt loading trước

                                    // attendanceData = [];
                                    updateSummary();
                                    console.log(attendanceData);
                                    // Optionally redirect or refresh
                                    // window.location.href = '{{ route('admin.dashboard') }}';
                                });
                            } else {
                                showToast('error', data.message || 'Đã xảy ra lỗi khi lưu');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showToast('error', 'Đã xảy ra lỗi khi lưu điểm danh');
                        })
                        .finally(() => {
                            showLoading(false);
                            console.log('Finally block executed');
                        });
                }
            });
        }

        function showLoading(show) {
            console.log('showLoading called with:', show);
            if (!loadingModal) {
                console.error('loadingModal is not defined');
                return;
            }
            if (show) {
                console.log('Showing modal');
                loadingModal.show();
            } else {
                console.log('Hiding modal');
                loadingModal.hide();
            }
        }

        function showToast(type, message) {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
            Toast.fire({
                icon: type,
                title: message
            });
        }


        console.log('Attendance management system loaded successfully');
    </script>
@endpush
