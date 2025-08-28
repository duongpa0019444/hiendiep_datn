@extends('admin.admin')

@section('title', 'Quản lý danh sách thông báo học phí')
@section('description', 'Danh sách thông báo biến động học phí')

@section('content')
    <div class="page-content">
        <div class="container-xxl">
            <nav aria-label="breadcrumb p-0">
                <ol class="breadcrumb py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Lịch sử thao tác</li>
                </ol>
            </nav>

            <h5 class="mb-3">Lịch sử tất cả thao tác</h5>

            <div class="row g-3 mb-3">
                <div class="col-xxl-2">
                    <div class="offcanvas-xxl offcanvas-start h-100" tabindex="-1" id="EmailSidebaroffcanvas"
                        aria-labelledby="EmailSidebaroffcanvasLabel">
                        <div class="card h-100 mb-0 shadow-sm border-0" data-simplebar>
                            <div class="card-body">
                                <div class="flex-column">
                                    {{-- Tất cả --}}
                                    <a class="nav-link py-1 active show" data-model-type="" href="#">
                                        <span class="fw-bold">
                                            <i class="fas fa-database fs-16 me-2 align-middle"></i> Tất cả
                                        </span>
                                    </a>

                                    <hr class="my-2 text-muted">

                                    {{-- Học tập --}}
                                    <a class="nav-link py-1" data-model-type="App\Models\User" href="#">
                                        <span class="d-flex align-items-center">
                                            <i class="fas fa-users fs-16 me-2 align-middle"></i> Người dùng
                                        </span>
                                    </a>
                                    <a class="nav-link py-1" data-model-type="App\Models\classes" href="#">
                                        <span class="d-flex align-items-center">
                                            <i class="fas fa-chalkboard-teacher fs-16 me-2 align-middle"></i> Lớp học
                                        </span>
                                    </a>
                                    <a class="nav-link py-1" data-model-type="App\Models\Classroom" href="#">
                                        <span class="d-flex align-items-center">
                                            <i class="fas fa-chalkboard-teacher fs-16 me-2 align-middle"></i> Phòng học
                                        </span>
                                    </a>
                                    <a class="nav-link py-1" data-model-type="App\Models\Schedule" href="#">
                                        <span class="d-flex align-items-center">
                                            <i class="fas fa-calendar-alt fs-16 me-2 align-middle"></i> Lịch học
                                        </span>
                                    </a>
                                    <a class="nav-link py-1" data-model-type="App\Models\courses" href="#">
                                        <span class="d-flex align-items-center">
                                            <i class="fas fa-book-open fs-16 me-2 align-middle"></i> Khóa học
                                        </span>
                                    </a>
                                    <a class="nav-link py-1" data-model-type="App\Models\lessons" href="#">
                                        <span class="d-flex align-items-center">
                                            <i class="fas fa-play-circle fs-16 me-2 align-middle"></i> Bài giảng
                                        </span>
                                    </a>
                                    <a class="nav-link py-1" data-model-type="App\Models\Attendance" href="#">
                                        <span class="d-flex align-items-center">
                                            <i class="fas fa-clipboard-check fs-16 me-2 align-middle"></i> Điểm danh
                                        </span>
                                    </a>
                                    <a class="nav-link py-1" data-model-type="App\Models\Quizzes" href="#">
                                        <span class="d-flex align-items-center">
                                            <i class="fas fa-question-circle fs-16 me-2 align-middle"></i> Quiz
                                        </span>
                                    </a>
                                    <a class="nav-link py-1" data-model-type="App\Models\score" href="#">
                                        <span class="d-flex align-items-center">
                                            <i class="fas fa-star-half-alt fs-16 me-2 align-middle"></i> Điểm số
                                        </span>
                                    </a>

                                    <hr class="my-2 text-muted">

                                    {{-- Tài chính --}}
                                    <a class="nav-link py-1" data-model-type="App\Models\coursePayment" href="#">
                                        <span class="d-flex align-items-center">
                                            <i class="fas fa-money-check-alt fs-16 me-2 align-middle"></i> Học phí & Thanh
                                            toán
                                        </span>
                                    </a>
                                    <a class="nav-link py-1" data-model-type="App\Models\teacher_salaries" href="#">
                                        <span class="d-flex align-items-center">
                                            <i class="fas fa-coins fs-16 me-2 align-middle"></i> Lương giáo viên
                                        </span>
                                    </a>

                                    <hr class="my-2 text-muted">

                                    {{-- Truyền thông & Liên hệ --}}
                                    <a class="nav-link py-1" data-model-type="App\Models\notification" href="#">
                                        <span class="d-flex align-items-center">
                                            <i class="fas fa-bell fs-16 me-2 align-middle"></i> Thông báo
                                        </span>
                                    </a>
                                    <a class="nav-link py-1" data-model-type="App\Models\contact" href="#">
                                        <span class="d-flex align-items-center">
                                            <i class="fas fa-envelope-open-text fs-16 me-2 align-middle"></i> Liên hệ
                                        </span>
                                    </a>
                                    <a class="nav-link py-1" data-model-type="App\Models\news" href="#">
                                        <span class="d-flex align-items-center">
                                            <i class="fas fa-newspaper fs-16 me-2 align-middle"></i> Bài viết
                                        </span>
                                    </a>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-10">
                    <div class="card position-relative overflow-hidden h-100 shadow-sm border-0">
                        <div class="p-2">
                            <form action="{{ route('admin.actions.log.filter') }}" id="searchForm" method="GET"
                                class="row g-2 align-items-end mb-3">
                                @csrf
                                <input type="hidden" name="limit" id="limit" value="{{ request('limit', 10) }}">
                                <input type="hidden" name="model_type" value="">
                                {{-- Từ khóa tìm kiếm --}}
                                <div class="col-md-3">
                                    <label for="keyword" class="form-label fw-bold">Từ khóa</label>
                                    <input type="text" class="form-control" name="keyword" id="keyword"
                                        placeholder="Tên người dùng, hành động...">
                                </div>

                                {{-- Khoảng thời gian --}}
                                <div class="col-md-3">
                                    <label for="date" class="form-label fw-bold">Ngày thao tác</label>
                                    <input type="text" id="basic-datepicker" class="form-control" name="date"
                                        placeholder="Chọn ngày">
                                </div>

                                {{-- Loại hành động --}}
                                <div class="col-md-3">
                                    <label for="action" class="form-label fw-bold">Hành động</label>
                                    <select name="action" id="action" class="form-select">
                                        <option value="">-- Tất cả --</option>
                                        <option value="create">Tạo</option>
                                        <option value="update">Cập nhật</option>
                                        <option value="delete">Xóa</option>
                                        {{-- Thêm action khác nếu có --}}
                                    </select>
                                </div>

                                {{-- Nút --}}
                                <div class="col-md-3 d-flex justify-content-start gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-filter me-1"></i> Lọc
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-reset" id="resetFilter">
                                        <i class="fas fa-undo me-1"></i> Đặt lại
                                    </button>
                                </div>
                            </form>

                            <!-- Action Buttons -->
                            <div class="d-flex align-items-center justify-content-between mt-3 p-2 bg-light rounded">
                                <div class="d-flex align-items-center gap-2">
                                    <input type="checkbox" id="selectAll" class="form-check-input">
                                    <label for="selectAll" class="form-check-label small mb-0">Chọn tất cả</label>
                                </div>
                                <div class="d-flex gap-2">

                                    <button type="button" class="btn btn-danger btn-sm delete-selected" disabled>
                                        <i class="fas fa-trash-alt me-1"></i> Xóa
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="tab-content pt-0" id="email-tabContent">
                            <div class="tab-pane fade active show" id="email-all" role="tabpanel"
                                aria-labelledby="email-all-tab">
                                <div>
                                    <ul class="list-unstyled mb-0" id="logList">
                                        @if ($logs->isEmpty())
                                            <div class="alert alert-secondary d-flex align-items-center justify-content-center gap-2"
                                                style="height: 200px;">
                                                <i class="fas fa-bell-slash fs-4"></i>
                                                <span>Không có lịch sử thao tác nào</span>
                                            </div>

                                        @endif
                                        @foreach ($logs as $log)
                                            <li
                                                class="log-item d-flex align-items-start justify-content-between px-3 border-bottom log-item-list-{{ $log->id }}">
                                                <!-- Bên trái: checkbox + thông tin log -->
                                                <div class="d-flex gap-1 flex-grow-1 py-2">
                                                    <!-- Checkbox -->
                                                    <div class="pt-1 me-1">
                                                        <input type="checkbox" id="LogChk{{ $log->id }}"
                                                            class="form-check-input log-checkbox"
                                                            value="{{ $log->id }}">
                                                    </div>

                                                    <!-- Nội dung -->
                                                    <div class="d-flex flex-column flex-grow-1">
                                                        <div class="fw-bold text-dark mb-1">
                                                            {{ $log->user_name ?? 'Hệ thống' }} đã {{ $log->action }}
                                                            {{ class_basename($log->model_type) }}
                                                        </div>
                                                        <div class="d-flex justify-content-start align-items-start gap-3">
                                                            <div class="text-muted small">
                                                                <i class="fas fa-info-circle me-1"></i>
                                                                {{ $log->description }}
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Bên phải: thời gian + dropdown hành động -->
                                                <div
                                                    class="text-muted small text-end d-flex justify-content-start align-items-center gap-1 py-2">
                                                    <div>
                                                        {{ \Carbon\Carbon::parse($log->created_at)->format('H:i - d/m/Y') }}
                                                    </div>
                                                    <button class="btn btn-outline-success btn-view-log"
                                                        data-id="{{ $log->id }}">
                                                        <i class="fas fa-eye"></i>
                                                    </button>

                                                    <button class="btn btn-outline-danger btn-delete-log"
                                                        data-id="{{ $log->id }}">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </div>
                                            </li>
                                        @endforeach


                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 m-3">
                            <div id="pagination-wrapper" class="flex-grow-1">
                                {{ $logs->links('pagination::bootstrap-5') }}
                            </div>
                            <div class="d-flex align-items-center" style="min-width: 160px;">
                                <label for="limit2" class="form-label mb-0 me-2 small">Hiển thị</label>
                                <select name="limit2" id="limit2" class="form-select form-select-sm"
                                    style="width: 100px;">
                                    <option value="10" selected>10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="logInfoModal" tabindex="-1" aria-labelledby="logInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="logInfoModalLabel">Thông tin log</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>

                <div class="modal-body">
                    <p><strong>Hành động:</strong> <span id="log-action"></span></p>
                    <p><strong>Người thực hiện:</strong> <span id="log-user"></span></p>
                    <p><strong>Mô tả:</strong> <span id="log-description"></span></p>
                    <p><strong>Thời gian:</strong> <span id="log-time"></span></p>
                    <p><strong>IP:</strong> <span id="log-ip"></span></p>
                    <p><strong>Quốc gia:</strong> <span id="log-country"></span> (<span id="log-country-code"></span>)</p>
                    <p><strong>Khu vực:</strong> <span id="log-region"></span></p>
                    <p><strong>Thành phố:</strong> <span id="log-city"></span></p>
                    <p><strong>ISP:</strong> <span id="log-isp"></span></p>
                    <p><strong>Tổ chức:</strong> <span id="log-org"></span></p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>

            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // Initialize flatpickr for datepicker
        document.getElementById('basic-datepicker').flatpickr();

        // Reset form
        $('.btn-reset').on('click', function(e) {
            $("#searchForm")[0].reset();
            $('input[name="status"]').val(window.selectedMenuActionLog || '');
            $("#searchForm").submit();
        });

        // Handle limit change
        $('#limit2').change(function() {
            $('#searchForm #limit').val(this.value);
            $('#searchForm').submit();
        });

        // Form filter submission
        $('#searchForm').on('submit', function(e) {
            e.preventDefault();
            $('#logList').css({
                'opacity': '0.5'
            });
            const url = $("#searchForm").attr('action');
            $.ajax({
                url: url,
                type: 'GET',
                data: $(this).serialize(),
                success: function(response) {
                    console.log('Search response:', response);
                    $('#logList').html(renderActionLog(response.logs.data));
                    $('#pagination-wrapper').html(response.pagination);
                    $('#logList').css({
                        'opacity': '1'
                    });
                },
                error: function(xhr) {
                    console.error('Lỗi khi tìm kiếm:', xhr.responseText);
                    showDataToast('Có lỗi xảy ra khi tìm kiếm!', 'danger');
                }
            });
        });

        // Pagination
        $(document).on('click', '#pagination-wrapper a', function(e) {
            e.preventDefault();
            $('#logList').css({
                'opacity': '0.5'
            });
            const url = this.href;
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    $('#logList').html(renderActionLog(response.logs.data));
                    $('#pagination-wrapper').html(response.pagination);
                    $('#logList').css({
                        'opacity': '1'
                    });
                },
                error: function(xhr) {
                    console.error('Lỗi phân trang:', xhr.responseText);
                    showDataToast('Có lỗi xảy ra khi phân trang!', 'danger');
                }
            });
        });

        // Render logs
        function renderActionLog(data) {
            if (data.length === 0) {
                return `
                    <div class="alert alert-secondary d-flex align-items-center justify-content-center gap-2" style="height: 200px;">
                        <i class="fas fa-bell-slash fs-4"></i>
                        <span>Không tìm thấy kết quả</span>
                    </div>
                `;
            }
            let html = `

            `;
            data.forEach(log => {
                const logTime = new Date(log.created_at).toLocaleTimeString('vi-VN', {
                    hour: '2-digit',
                    minute: '2-digit'
                });

                const logDate = new Date(log.created_at).toLocaleDateString('vi-VN', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });

                html += `
                    <li class="log-item log-item-list-${log.id} d-flex align-items-start justify-content-between px-3 border-bottom">
                        <div class="d-flex gap-1 flex-grow-1 py-2">
                            <div class="pt-1 me-1">
                                <input type="checkbox"
                                    class="form-check-input log-checkbox"
                                    value="${log.id}">
                            </div>

                            <div class="d-flex flex-column flex-grow-1">
                                <div class="fw-bold text-dark mb-1">
                                    ${log.user_name ?? 'Hệ thống'} đã ${log.action}
                                    ${log.model_type?.split('\\').pop()}
                                </div>
                                <div class="d-flex justify-content-start align-items-start gap-3">
                                    <div class="text-muted small">
                                        <i class="fas fa-info-circle me-1"></i> ${log.description}
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="text-muted small text-end d-flex justify-content-start align-items-center gap-1 py-2">
                            <div>${logTime} - ${logDate}</div>
                            <button class="btn btn-outline-success btn-view-log"
                                data-id="${log.id}">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-outline-danger btn-delete-log" data-id="${log.id}">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </li>
                `;
            });


            return html;
        }

        // Format date and time với số 0 trước ngày và tháng
        function formatDateTime(dateString) {
            const date = new Date(dateString);

            const day = String(date.getDate()).padStart(2, '0'); // 01 -> 09
            const month = String(date.getMonth() + 1).padStart(2, '0'); // 01 -> 12
            const year = date.getFullYear();

            const hours = String(date.getHours()).padStart(2, '0'); // 00 -> 23
            const minutes = String(date.getMinutes()).padStart(2, '0'); // 00 -> 59

            return `${hours}:${minutes} - ${day}/${month}/${year}`;
        }

        // Show toast log
        function showDataToast(text, type = 'danger') {
            const btn = document.createElement('button');
            btn.setAttribute('data-toast', '');
            btn.setAttribute('data-toast-text', text);
            btn.setAttribute('data-toast-gravity', 'top');
            btn.setAttribute('data-toast-position', 'right');
            btn.setAttribute('data-toast-duration', '5000');
            btn.setAttribute('data-toast-close', 'close');
            btn.setAttribute('data-toast-className', type);
            btn.style.display = 'none';
            document.body.appendChild(btn);

            btn.addEventListener('click', function() {
                Toastify({
                    newWindow: true,
                    text: text,
                    gravity: "top",
                    position: "right",
                    className: "bg-" + type,
                    stopOnFocus: true,
                    offset: {
                        x: 50,
                        y: 10
                    },
                    duration: 3000,
                    close: true
                }).showToast();
            });

            btn.click();
            setTimeout(() => btn.remove(), 100);
        }

        // Tab click handler
        $(document).ready(function() {

            // Sự kiện click vào menu bên trái (lọc theo model_type)
            $('[data-model-type]').on('click', function(e) {
                e.preventDefault();

                // Lấy giá trị model_type từ thuộc tính data
                let modelType = $(this).data('model-type') || '';

                // Gán vào input hidden để submit form
                $('input[name="model_type"]').val(modelType);

                // Thêm class active cho menu đang chọn
                $('[data-model-type]').removeClass('active show');
                $(this).addClass('active show');

                window.selectedMenuActionLog = modelType;
                // Submit form lọc
                $("#searchForm").submit();
            });



            // Select all checkbox
            $('#selectAll').on('change', function() {
                $('.log-checkbox').prop('checked', this.checked);
                $('.delete-selected').prop('disabled', !this.checked && $(
                    '.log-checkbox:checked').length === 0);
            });

            // Enable/disable action buttons based on checkbox selection
            $(document).on('change', '.log-checkbox', function() {
                const anyChecked = $('.log-checkbox:checked').length > 0;
                $('.delete-selected').prop('disabled', !anyChecked);
                $('#selectAll').prop('checked', $('.log-checkbox').length === $(
                    '.log-checkbox:checked').length);
            });


            // Delete selected logs
            $('.delete-selected').on('click', function() {
                const selectedIds = $('.log-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                if (selectedIds.length === 0) {
                    showDataToast('Vui lòng chọn ít nhất một thông báo!', 'warning');
                    return;
                }
                console.log('Xóa các log với ID:', selectedIds);


                Swal.fire({
                    title: 'Bạn có chắc chắn?',
                    text: "Bạn muốn xóa các thông báo đã chọn?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Xóa',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/admin/actions/log/delete',
                            type: 'POST',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                ids: selectedIds
                            },
                            success: function(response) {
                                if (response.success) {
                                    showDataToast('Đã xóa các thông báo thành công!',
                                        'success');
                                    selectedIds.forEach(id => {
                                        $(`.log-item-list-${id}`)
                                            .remove();
                                    });

                                    $('.log-checkbox').prop('checked', false);
                                    $('#selectAll').prop('checked', false);
                                    $('.delete-selected').prop(
                                        'disabled', true);
                                } else {
                                    showDataToast(response.error || 'Có lỗi xảy ra!',
                                        'danger');
                                }
                            },
                            error: function() {
                                showDataToast('Có lỗi xảy ra khi xóa thông báo!',
                                    'danger');
                            }
                        });
                    }
                });

            });


            // Single log delete
            $(document).on('click', '.btn-delete-log', function(e) {
                e.preventDefault();
                const id = $(this).data('id');

                Swal.fire({
                    title: 'Bạn có chắc chắn?',
                    text: "Bạn sẽ không thể hoàn tác hành động này!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Xóa',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        console.log('Xóa log với ID:', id);
                        $.ajax({
                            url: '/admin/actions/log/delete/' + id,
                            type: 'POST',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(res) {
                                if (res.success) {
                                    showDataToast(res.success || 'Đã xóa thành công!',
                                        'success');
                                    $(`.log-item-list-${id}`).remove();

                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Lỗi',
                                        text: res.error || 'Có lỗi xảy ra!'
                                    });
                                }
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Lỗi',
                                    text: 'Có lỗi xảy ra khi xóa thông báo!'
                                });
                            }
                        });
                    }
                });
            });


        });

        $(document).on('click', '.btn-view-log', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            console.log('Xem log với ID:', id);
            $.ajax({
                url: `/admin/actions/log/view/${id}`,
                type: 'GET',
                data: {
                    id: id
                },
                success: function(response) {
                    console.log('Xem log response:', response);
                    if (response.log) {
                         showLogModal(response);
                    } else {
                        showDataToast(response.error || 'Không tìm thấy log!', 'warning');
                    }
                },

                error: function(xhr) {
                    console.error('Lỗi khi xem log:', xhr.responseText);
                    showDataToast('Có lỗi xảy ra khi xem log!', 'danger');
                }
            });
        });


        function formatDateTime(dateString) {
            const date = new Date(dateString);
            return date.toLocaleString('vi-VN', {
                hour12: false
            });
        }

        function showLogModal(response) {
            document.getElementById('log-action').textContent = response.log.action;
            document.getElementById('log-user').textContent = response.log.user_name || 'Hệ thống';
            document.getElementById('log-description').textContent = response.log.description || 'Không có';
            document.getElementById('log-time').textContent = formatDateTime(response.log.created_at);
            document.getElementById('log-ip').textContent = response.ip_info.ip || 'Không xác định';
            document.getElementById('log-country').textContent = response.ip_info.country || 'Không xác định';
            document.getElementById('log-country-code').textContent = response.ip_info.country_code || '';
            document.getElementById('log-region').textContent = response.ip_info.region || 'Không xác định';
            document.getElementById('log-city').textContent = response.ip_info.city || 'Không xác định';
            document.getElementById('log-isp').textContent = response.ip_info.isp || 'Không xác định';
            document.getElementById('log-org').textContent = response.ip_info.org || 'Không xác định';

            // Hiển thị modal
            const modal = new bootstrap.Modal(document.getElementById('logInfoModal'));
            modal.show();
        }
    </script>
@endpush
