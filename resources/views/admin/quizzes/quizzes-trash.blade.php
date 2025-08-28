@extends('admin.admin')

@section('title', 'Thùng Rác Quiz')
@section('description', '')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.quizz') }}">Quản lý Quiz</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Thùng Rác</li>
                </ol>
            </nav>

            <!-- Title and Actions -->
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h4 class="card-title mb-1">Thùng Rác - Danh sách bài quiz đã xóa</h4>
                <div>
                    <a href="{{ route('admin.quizz') }}" class="btn btn-primary btn-sm">
                        <iconify-icon icon="solar:arrow-left-broken" class="fs-20"></iconify-icon> Quay lại Quản lý Quiz
                    </a>
                </div>
            </div>

            <!-- Quiz List -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card p-2">
                        <form action="{{ route('admin.quizzes.trash.filter') }}" class="row g-2 d-flex align-items-end"
                            id="searchForm">
                            <input type="hidden" name="limit" id="limit" value="10">
                            <div class="col-12 col-md-6 col-lg-4 col-xl-2">
                                <label for="keyword" class="form-label mb-1">Từ khóa</label>
                                <input type="text" name="keyword" id="keyword" class="form-control form-control-sm"
                                    placeholder="Tìm tiêu đề">
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 col-xl-2">
                                <label for="class_filter" class="form-label mb-1">Lớp</label>
                                <select name="class_id" id="class_filter" class="form-select form-select-sm" data-choices>
                                    <option value="">Tất cả</option>
                                    @foreach (\DB::table('classes')->get() as $class)
                                        <option value="{{ $class->id }}"
                                            {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 col-xl-2">
                                <label for="course_filter" class="form-label mb-1">Khóa học</label>
                                <select name="course_id" id="course_filter" class="form-select form-select-sm" data-choices>
                                    <option value="">Tất cả</option>
                                    @foreach (\DB::table('courses')->get() as $course)
                                        <option value="{{ $course->id }}"
                                            {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                            {{ $course->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 col-xl-2">
                                <label for="status_filter" class="form-label mb-1">Trạng thái</label>
                                <select name="status" id="status_filter" class="form-select form-select-sm">
                                    <option value="">Tất cả</option>
                                    <option value="draft">Nháp</option>
                                    <option value="published">Đã Xuất bản</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 col-xl-2">
                                <label for="is_public_filter" class="form-label mb-1">Công khai</label>
                                <select name="is_public" id="is_public_filter" class="form-select form-select-sm">
                                    <option value="">Tất cả</option>
                                    <option value="1">Có</option>
                                    <option value="0">Không</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 col-xl-2 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-success btn-sm w-50">Lọc</button>
                                <button type="reset" class="btn btn-danger btn-sm w-50">Xóa</button>
                            </div>
                        </form>
                    </div>
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="table-responsive table-gridjs">
                                <table class="table table-custom align-middle mb-0 table-hover table-centered"
                                    style="border-radius: 0;">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Tiêu đề</th>
                                            <th>Lớp</th>
                                            <th>Người tạo</th>
                                            <th>Thời gian</th>
                                            <th>Trạng thái</th>
                                            <th>Ngày xóa</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody id="body-quizzes">
                                        @forelse ($quizzes as $quiz)
                                            <tr>
                                                <td>
                                                    <div class="fw-bold">{{ $quiz->title }} <p class="text-danger"> {{ $quiz->status == 'published' ? '' : ' (Bản nháp)' }}</p></div>
                                                </td>
                                                <td>
                                                    @if ($quiz->is_public)
                                                        <div class="fw-bold">Tất cả</div>
                                                    @else
                                                        <div class="fw-bold">{{ $quiz->class->name ?? 'Tất cả' }}</div>
                                                        <div class="fs-6">Khóa: {{ $quiz->course->name ?? 'Tất cả' }}</div>
                                                    @endif
                                                </td>
                                                <td>{{ $quiz->creator->name ?? 'Không rõ' }}</td>
                                                <td>{{ $quiz->duration_minutes }}</td>
                                                <td>
                                                    <span
                                                        class="badge {{ $quiz->is_public ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }} py-1 px-2">
                                                        {{ $quiz->is_public ? 'Công khai' : 'Riêng tư' }}
                                                    </span>
                                                </td>
                                                <td>{{ $quiz->deleted_at->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    <div class="btn-group dropstart">
                                                        <button class="btn btn-light btn-sm dropdown-toggle"
                                                            type="button" data-bs-toggle="dropdown">
                                                            Thao tác
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <form
                                                                    action="{{ route('admin.quizzes.restore', $quiz->id) }}"
                                                                    method="POST" style="display:inline;">
                                                                    @csrf
                                                                    <button type="submit"
                                                                        class="dropdown-item text-success bg-transparent border-0 w-100 text-start btn-restore-quiz">
                                                                        <iconify-icon
                                                                            icon="solar:undo-left-broken"
                                                                            class="me-1"></iconify-icon> Khôi phục
                                                                    </button>
                                                                </form>
                                                            </li>
                                                            <li>
                                                                <form
                                                                    action="{{ route('admin.quizzes.forceDelete', $quiz->id) }}"
                                                                    method="POST" style="display:inline;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="dropdown-item text-danger bg-transparent border-0 w-100 text-start btn-force-delete-quiz">
                                                                        <iconify-icon
                                                                            icon="solar:trash-bin-minimalistic-2-broken"
                                                                            class="me-1"></iconify-icon> Xóa vĩnh viễn
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">
                                                    <div class="alert alert-warning">Không có bài quiz nào trong thùng rác</div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 m-3">
                            <div id="pagination-wrapper" class="flex-grow-1">
                                {{ $quizzes->links('pagination::bootstrap-5') }}
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
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 text-center">
                        <script>
                            document.write(new Date().getFullYear())
                        </script> © DỰ ÁN TỐT NGHIỆP CAO ĐẲNG FPT POLYTECHNIC  THANH HÓA
                        <iconify-icon icon="iconamoon:heart-duotone"
                            class="fs-18 align-middle text-danger"></iconify-icon>
                        <a href="#" class="fw-bold footer-text" target="_blank">Tiger Code</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
@endsection

@push('scripts')
    <script>
        // Reset search
        $('#searchForm').on('reset', function() {
            setTimeout(() => window.location.reload(), 10);
        });

        // Handle limit change
        $('#limit2').change(function() {
            $('#searchForm #limit').val(this.value);
            $('#searchForm').submit();
        });

        // Handle Filter
        $('#searchForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: this.action,
                type: 'GET',
                data: $(this).serialize(),
                success: function(response) {
                    $('#body-quizzes').html(renderQuizzes(response.quizzes.data));
                    $('#pagination-wrapper').html(response.pagination);
                },
                error: function(xhr) {
                    console.error('Lỗi khi tìm kiếm:', xhr.responseText);
                }
            });
        });

        // Pagination
        $(document).on('click', '#pagination-wrapper a', function(e) {
            e.preventDefault();
            const url = this.href;
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    $('#body-quizzes').html(renderQuizzes(response.quizzes.data));
                    $('#pagination-wrapper').html(response.pagination);
                },
                error: function(xhr) {
                    console.error('Lỗi phân trang:', xhr.responseText);
                }
            });
        });

        // Restore quiz
        $(document).on('click', '.btn-restore-quiz', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            const actionUrl = form.attr('action');
            Swal.fire({
                title: 'Bạn có chắc chắn?',
                text: "Bạn muốn khôi phục bài quiz này?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Vâng, khôi phục!',
                cancelButtonText: 'Không, hủy!',
                confirmButtonClass: 'btn btn-primary w-xs me-2 mt-2',
                cancelButtonClass: 'btn btn-danger w-xs mt-2',
                buttonsStyling: false,
                showCloseButton: true
            }).then(function(result) {
                if (result.isConfirmed) {
                    $.ajax({
                        url: actionUrl,
                        type: 'POST',
                        data: form.serialize(),
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function() {
                            Swal.fire('Đã khôi phục!', 'Quiz đã được khôi phục thành công.', 'success');
                            form.closest('tr').remove();
                            refreshQuizList();
                        },
                        error: function() {
                            Swal.fire('Lỗi!', 'Không thể khôi phục quiz.', 'error');
                        }
                    });
                }
            });
        });

        // Force delete quiz
        $(document).on('click', '.btn-force-delete-quiz', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            const actionUrl = form.attr('action');
            Swal.fire({
                title: 'Bạn có chắc chắn?',
                text: "Bạn sẽ xóa vĩnh viễn bài quiz này! Hành động này không thể hoàn tác!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Vâng, xóa vĩnh viễn!',
                cancelButtonText: 'Không, hủy!',
                confirmButtonClass: 'btn btn-primary w-xs me-2 mt-2',
                cancelButtonClass: 'btn btn-danger w-xs mt-2',
                buttonsStyling: false,
                showCloseButton: true
            }).then(function(result) {
                if (result.isConfirmed) {
                    $.ajax({
                        url: actionUrl,
                        type: 'POST',
                        data: form.serialize(),
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function() {
                            Swal.fire('Đã xóa!', 'Quiz đã được xóa vĩnh viễn.', 'success');
                            form.closest('tr').remove();
                            refreshQuizList();
                        },
                        error: function() {
                            Swal.fire('Lỗi!', 'Không thể xóa vĩnh viễn quiz.', 'error');
                        }
                    });
                }
            });
        });

        // Render quizzes
        function renderQuizzes(data) {
            if (data.length === 0) {
                return '<tr><td colspan="7" class="text-center"><div class="alert alert-warning">Không có bài quiz nào trong thùng rác</div></td></tr>';
            }
            let html = '';
            data.forEach(quiz => {
                html += `
                    <tr>
                        <td><div class="fw-bold">${quiz.title}  <p class="text-danger"> ${ quiz.status == 'published' ? '' : ' (Bản nháp)' }</p></div></td>
                        <td>
                            ${quiz.is_public ? `
                                <div class="fw-bold">Tất cả</div>
                            ` : `
                                <div class="fw-bold">${quiz.class?.name || 'Tất cả'}</div>
                                <div class="fs-6">Khóa: ${quiz.course?.name || 'Tất cả'}</div>
                            `}
                        </td>
                        <td>${quiz.creator?.name || 'Không rõ'}</td>
                        <td>${quiz.duration_minutes}</td>
                        <td>
                            <span class="badge ${quiz.is_public ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning'} py-1 px-2">
                                ${quiz.is_public ? 'Công khai' : 'Riêng tư'}
                            </span>
                        </td>
                        <td>${new Date(quiz.deleted_at).toLocaleString('vi-VN', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</td>
                        <td>
                            <div class="btn-group dropstart">
                                <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Thao tác
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <form action="/admin/quizzes/${quiz.id}/restore" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-success bg-transparent border-0 w-100 text-start btn-restore-quiz">
                                                <iconify-icon icon="solar:undo-left-broken" class="me-1"></iconify-icon> Khôi phục
                                            </button>
                                        </form>
                                    </li>
                                    <li>
                                        <form action="/admin/quizzes/${quiz.id}/force-delete" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger bg-transparent border-0 w-100 text-start btn-force-delete-quiz">
                                                <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="me-1"></iconify-icon> Xóa vĩnh viễn
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                `;
            });
            return html;
        }

        // Refresh quiz list
        function refreshQuizList() {
            $.ajax({
                url: '{{ route('admin.quizzes.trash.filter') }}',
                type: 'GET',
                data: $('#searchForm').serialize(),
                success: function(response) {
                    $('#body-quizzes').html(renderQuizzes(response.quizzes.data));
                    $('#pagination-wrapper').html(response.pagination);
                },
                error: function(xhr) {
                    console.error('Lỗi khi làm mới danh sách:', xhr.responseText);
                }
            });
        }
    </script>
@endpush
