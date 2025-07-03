@extends('admin.admin')

@section('title', 'Điểm danh lớp học')
@section('description', 'Trang điểm danh lớp học')

@section('content')
    <div class="page-content">
        <div class="container-xxl">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Điểm danh lớp học</li>
                </ol>
            </nav>

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
                            <div class="col-md-3 col-sm-6">
                                <div class="summary-item">
                                    <span class="summary-number text-success" id="presentCount">0</span>
                                    <span class="summary-label">Có mặt</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="summary-item">
                                    <span class="summary-number text-danger" id="absentCount">0</span>
                                    <span class="summary-label">Vắng mặt</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="summary-item">
                                    <span class="summary-number text-warning" id="lateCount">0</span>
                                    <span class="summary-label">Muộn</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="summary-item">
                                    <span class="summary-number text-info" id="excusedCount">0</span>
                                    <span class="summary-label">Có phép</span>
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
                                            'late' => 'Muộn',
                                            'excused' => 'Có phép',
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

                                <div class="attendance-controls">
                                    <button
                                        class="status-btn present {{ isset($attendance[$student->id]) && $attendance[$student->id]['status'] === 'present' ? 'active' : '' }}"
                                        onclick="setAttendance('{{ $student->id }}', 'present', '{{ $scheduleData->id }}')">
                                        <i class="fas fa-check"></i> Có mặt
                                    </button>
                                    <button
                                        class="status-btn late {{ isset($attendance[$student->id]) && $attendance[$student->id]['status'] === 'late' ? 'active' : '' }}"
                                        onclick="setAttendance('{{ $student->id }}', 'late', '{{ $scheduleData->id }}')">
                                        <i class="fas fa-clock"></i> Muộn
                                    </button>
                                    <button
                                        class="status-btn absent {{ isset($attendance[$student->id]) && $attendance[$student->id]['status'] === 'absent' ? 'active' : '' }}"
                                        onclick="setAttendance('{{ $student->id }}', 'absent', '{{ $scheduleData->id }}')">
                                        <i class="fas fa-times"></i> Vắng
                                    </button>
                                    <button
                                        class="status-btn excused {{ isset($attendance[$student->id]) && $attendance[$student->id]['status'] === 'excused' ? 'active' : '' }}"
                                        onclick="setAttendance('{{ $student->id }}', 'excused', '{{ $scheduleData->id }}')">
                                        <i class="fas fa-user-check"></i> Có phép
                                    </button>
                                </div>
                            </div>
                        @endforeach
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
                    <button type="button" class="btn btn-info" onclick="exportAttendance()">
                        <i class="fas fa-download me-1"></i>Xuất Excel
                    </button>
                </div>
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

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer>
        console.log('Script loaded');
        // Global variables
        // Mảng lưu trữ dữ liệu điểm danh
        let attendanceData = [];
        let scheduleId = '{{ $scheduleData->id }}';
        let totalStudents = {{ $students->count() }};
        let loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
        let previewModal = new bootstrap.Modal(document.getElementById('previewModal'));

        // Initialize the page
        document.addEventListener('DOMContentLoaded', function() {
            updateSummary();
            setupEventListeners();

            console.log('Attendance system initialized');
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

        function setAttendance(studentId, status, scheduleId) {
            // Tìm xem studentId đã có trong attendanceData chưa
            const existingIndex = attendanceData.findIndex(item => item && item.student_id === parseInt(studentId));

            // Nếu đã có, cập nhật status
            if (existingIndex !== -1) {
                attendanceData[existingIndex] = {
                    student_id: parseInt(studentId),
                    status: status
                };
            } else {
                // Nếu chưa có, thêm mới
                attendanceData.push({
                    student_id: parseInt(studentId),
                    status: status
                });
            }

            // Update UI
            updateStudentStatus(studentId, status);
            updateSummary();
            showToast('success', 'Đã cập nhật trạng thái điểm danh!');
        }

        function updateStudentStatus(studentId, status) {
            const studentItem = document.querySelector(`.student-item[data-id="${studentId}"]`);
            const statusSpan = studentItem.querySelector('.student-status');
            const buttons = studentItem.querySelectorAll('.status-btn');
            // Cập nhật văn bản trạng thái
            const statusText = {
                present: 'Có mặt',
                late: 'Muộn',
                absent: 'Vắng',
                excused: 'Có phép'
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

        function getStatusText(status) {
            const statusMap = {
                'present': 'Có mặt',
                'absent': 'Vắng',
                'late': 'Muộn',
                'excused': 'Có phép',
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

        function autoMarkPresent() {
            Swal.fire({
                title: 'Tự động điểm danh',
                text: 'Hệ thống sẽ tự động đánh dấu "Có mặt" cho các học sinh chưa được điểm danh',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Thực hiện',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    const unmarkedStudents = document.querySelectorAll('.student-item .student-status.undone');
                    unmarkedStudents.forEach(statusBadge => {
                        const studentItem = statusBadge.closest('.student-item');
                        const studentId = studentItem.dataset.id;
                        setAttendance(studentId, 'present', scheduleId);
                    });
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
                    document.getElementById('lateCount').textContent = result.data.late || 0;
                    document.getElementById('excusedCount').textContent = result.data.excused || 0;
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
                late: 0,
                excused: 0
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
            document.getElementById('lateCount').textContent = stats.late;
            document.getElementById('excusedCount').textContent = stats.excused;
        }
        // function previewAttendance() {
        //     const previewContent = document.getElementById('previewContent');
        //     let html = '<div class="attendance-preview">';

        //     const stats = {
        //         present: 0,
        //         absent: 0,
        //         late: 0,
        //         excused: 0,
        //         undone: 0
        //     };

        //     document.querySelectorAll('.student-item').forEach(item => {
        //         const studentId = item.dataset.id;
        //         const studentName = item.querySelector('.student-name').textContent;
        //         const status = attendanceData[studentId]?.status || 'undone';

        //         stats[status]++;

        //         html += `
    //         <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
    //             <span>${studentName}</span>
    //             <span class="badge bg-${getStatusColor(status)}">${getStatusText(status)}</span>
    //         </div>
    //     `;
        //     });

        //     html += '</div>';
        //     html += '<div class="mt-3"><h6>Thống kê:</h6>';
        //     html +=
        //         `<p>Có mặt: ${stats.present} | Vắng: ${stats.absent} | Muộn: ${stats.late} | Có phép: ${stats.excused} | Chưa điểm danh: ${stats.undone}</p>`;
        //     html += '</div>';

        //     previewContent.innerHTML = html;
        //     previewModal.show();
        // }


        function getStatusColor(status) {
            const colorMap = {
                'present': 'success',
                'absent': 'danger',
                'late': 'warning',
                'excused': 'info',
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
                    // showLoading(true);

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
                                showLoading(false); // Tắt loading trước
                                Swal.fire({
                                    title: 'Thành công!',
                                    text: 'Điểm danh đã được lưu thành công',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    attendanceData = {};
                                    updateSummary();
                                    // Clear local draft after successful save
                                    localStorage.removeItem(`attendance_draft_${scheduleId}`);
                                    disableAutoSave(); // Dừng auto-save
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

        function exportAttendance() {
            // showLoading(true);

            fetch('{{ route('attendance.export') }}', {
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
                .then(response => {
                    if (response.ok) {
                        return response.blob();
                    }
                    throw new Error('Export failed');
                })
                .then(blob => {
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.style.display = 'none';
                    a.href = url;
                    a.download = `diem-danh-${scheduleId}-${new Date().toISOString().split('T')[0]}.xlsx`;
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                    showToast('success', 'Xuất file Excel thành công');
                })
                .catch(error => {
                    console.error('Export error:', error);
                    showToast('error', 'Đã xảy ra lỗi khi xuất file');
                })
                .finally(() => {
                    showLoading(false);
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

        // Auto-save functionality (optional)
        let autoSaveInterval;

        function enableAutoSave() {
            autoSaveInterval = setInterval(() => {
                if (Object.keys(attendanceData).length > 0) {
                    // Save draft to localStorage as backup
                    localStorage.setItem(`attendance_draft_${scheduleId}`, JSON.stringify(attendanceData));
                    console.log('Auto-saved attendance data');
                }
            }, 30000); // Auto-save every 30 seconds
        }

        function disableAutoSave() {
            if (autoSaveInterval) {
                clearInterval(autoSaveInterval);
                console.log('Auto-save disabled');
            }
        }

        function loadDraft() {
            const draftData = localStorage.getItem(`attendance_draft_${scheduleId}`);
            if (draftData) {
                // console.log(draftData);
                try {
                    const parsedData = JSON.parse(draftData);
                    // Kiểm tra dữ liệu hợp lệ
                    if (!parsedData || typeof parsedData !== 'object') {
                        throw new Error('Dữ liệu draft không hợp lệ');
                    }
                    Swal.fire({
                        title: 'Khôi phục dữ liệu',
                        text: 'Tìm thấy dữ liệu điểm danh chưa lưu. Bạn có muốn khôi phục?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Khôi phục',
                        cancelButtonText: 'Bỏ qua'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            attendanceData = parsedData;
                            // Update UI based on restored data
                            console.log(attendanceData);
                            Object.keys(attendanceData).forEach(index => {
                                updateStudentStatus(attendanceData[index].student_id, attendanceData[index]
                                    .status);
                            });
                            // updateSummary();
                            showToast('success', 'Đã khôi phục dữ liệu điểm danh');
                        } else {
                            localStorage.removeItem(`attendance_draft_${scheduleId}`);
                        }
                    });
                } catch (error) {
                    console.error('Error loading draft:', error);
                    localStorage.removeItem(`attendance_draft_${scheduleId}`);
                }
            }
        }

        // Initialize auto-save and load draft
        setTimeout(() => {
            loadDraft();
            enableAutoSave();
        }, 1000);

        // Clean up on page unload
        window.addEventListener('beforeunload', function(e) {
            if (Object.keys(attendanceData).length > 0) {
                // Save current state
                localStorage.setItem(`attendance_draft_${scheduleId}`, JSON.stringify(attendanceData));

                // Show warning if there are unsaved changes
                const hasUnsavedChanges = Object.keys(attendanceData).some(studentId => {
                    // You might want to track which changes have been saved to server
                    return true; // For now, assume all changes are unsaved
                });

                if (hasUnsavedChanges) {
                    e.preventDefault();
                    e.returnValue = 'Bạn có dữ liệu điểm danh chưa được lưu. Bạn có chắc chắn muốn rời khỏi trang?';
                }
            }
        });

        // Utility functions for statistics
        function getAttendanceStats() {
            const stats = {
                present: 0,
                absent: 0,
                late: 0,
                excused: 0,
                undone: 0
            };

            document.querySelectorAll('.student-item').forEach(item => {
                const studentId = item.dataset.id;
                const status = attendanceData[studentId]?.status || 'undone';
                stats[status]++;
            });

            return stats;
        }


        function getAttendanceRate() {
            const stats = getAttendanceStats();
            const total = totalStudents;
            const attended = stats.present + stats.late + stats.excused;
            return total > 0 ? Math.round((attended / total) * 100) : 0;
        }

        // Print functionality
        function printAttendance() {
            const stats = getAttendanceStats();
            const attendanceRate = getAttendanceRate();

            let printContent = `
            <html>
            <head>
                <title>Bảng điểm danh</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    .header { text-align: center; margin-bottom: 30px; }
                    .info { margin-bottom: 20px; }
                    .stats { background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
                    table { width: 100%; border-collapse: collapse; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    th { background-color: #f8f9fa; }
                    .status-present { background-color: #d4edda; color: #155724; }
                    .status-absent { background-color: #f8d7da; color: #721c24; }
                    .status-late { background-color: #fff3cd; color: #856404; }
                    .status-excused { background-color: #d1ecf1; color: #0c5460; }
                    .status-undone { background-color: #e2e3e5; color: #383d41; }
                </style>
            </head>
            <body>
                <div class="header">
                    <h2>BẢNG ĐIỂM DANH LỚP HỌC</h2>
                </div>
                <div class="info">
                    <p><strong>Lớp:</strong> {{ $scheduleData->class_name ?? 'N/A' }}</p>
                    <p><strong>Môn học:</strong> {{ $scheduleData->course_name ?? 'N/A' }}</p>
                    <p><strong>Ngày:</strong> {{ $scheduleData->date ? \Carbon\Carbon::parse($scheduleData->date)->format('d/m/Y') : 'N/A' }}</p>
                    <p><strong>Thời gian:</strong> {{ $scheduleData->start_time && $scheduleData->end_time ? \Carbon\Carbon::parse($scheduleData->start_time)->format('H:i') . ' - ' . \Carbon\Carbon::parse($scheduleData->end_time)->format('H:i') : 'N/A' }}</p>
                </div>
                <div class="stats">
                    <h4>Thống kê điểm danh</h4>
                    <p>Tổng số học sinh: ${totalStudents} | Tỷ lệ có mặt: ${attendanceRate}%</p>
                    <p>Có mặt: ${stats.present} | Vắng: ${stats.absent} | Muộn: ${stats.late} | Có phép: ${stats.excused} | Chưa điểm danh: ${stats.undone}</p>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tên học sinh</th>
                            <th>Giới tính</th>
                            <th>Trạng thái</th>
                            <th>Ghi chú</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

            let index = 1;
            document.querySelectorAll('.student-item').forEach(item => {
                const studentId = item.dataset.id;
                const studentName = item.querySelector('.student-name').textContent;
                const studentGender = item.querySelector('.student-id').textContent.includes('Nữ') ? 'Nữ' : 'Nam';
                const status = attendanceData[studentId]?.status || 'undone';
                const statusText = getStatusText(status);

                printContent += `
                <tr>
                    <td>${index++}</td>
                    <td>${studentName}</td>
                    <td>${studentGender}</td>
                    <td class="status-${status}">${statusText}</td>
                    <td></td>
                </tr>
            `;
            });

            printContent += `
                    </tbody>
                </table>
                <div style="margin-top: 50px; text-align: right;">
                    <p>Ngày in: ${new Date().toLocaleDateString('vi-VN')}</p>
                    <p>Người điểm danh: ________________________</p>
                    <p style="margin-top: 30px;">Chữ ký: ________________________</p>
                </div>
            </body>
            </html>
        `;

            const printWindow = window.open('', '_blank');
            printWindow.document.write(printContent);
            printWindow.document.close();
            printWindow.print();
        }

        // Add print button to quick actions
        //     document.querySelector('.quick-actions').insertAdjacentHTML('beforeend', `
    //     <button class="quick-action-btn btn-outline-dark" onclick="printAttendance()">
    //         <i class="fas fa-print me-1"></i>
    //         In bảng điểm danh
    //     </button>
    // `);

        console.log('Attendance management system loaded successfully');
    </script>
@endpush
