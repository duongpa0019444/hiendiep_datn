@extends('admin.admin')

@section('title', 'Quản lý Quiz')
@section('description', '')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Quản lý Quiz</li>
                </ol>
            </nav>

            <!-- Summary Cards -->
            <div class="row">
                <div class="col-md-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h4 class="card-title mb-2 d-flex align-items-center gap-2 fs-5">Tổng bài quiz</h4>
                                    <p class="text-muted fw-medium fs-22 mb-0">{{ $statistics[0]->total_quizzes }} bài</p>
                                </div>
                                <div class="avatar-md bg-primary bg-opacity-10 rounded">
                                    <iconify-icon icon="solar:book-broken"
                                        class="fs-32 avatar-title text-primary"></iconify-icon>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h4 class="card-title mb-2 d-flex align-items-center gap-2 fs-5">Bài công khai</h4>
                                    <p class="text-muted fw-medium fs-22 mb-0">{{ $statistics[0]->total_public_quizzes }}
                                        bài</p>
                                </div>
                                <div class="avatar-md bg-success bg-opacity-10 rounded">
                                    <iconify-icon icon="solar:global-broken"
                                        class="fs-32 avatar-title text-success"></iconify-icon>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h4 class="card-title mb-2 d-flex align-items-center gap-2 fs-5">Lượt làm bài</h4>
                                    <p class="text-muted fw-medium fs-22 mb-0">{{ $statistics[0]->total_attempts }} lượt</p>
                                </div>
                                <div class="avatar-md bg-info bg-opacity-10 rounded">
                                    <iconify-icon icon="solar:check-circle-broken"
                                        class="fs-32 avatar-title text-info"></iconify-icon>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h4 class="card-title mb-2 d-flex align-items-center gap-2 fs-5">Học sinh tham gia</h4>
                                    <p class="text-muted fw-medium fs-22 mb-0">
                                        {{ $statistics[0]->total_students_participated }} học sinh</p>
                                </div>
                                <div class="avatar-md bg-warning bg-opacity-10 rounded">
                                    <iconify-icon icon="solar:user-broken"
                                        class="fs-32 avatar-title text-warning"></iconify-icon>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Title and Actions -->
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h4 class="card-title mb-1">Danh sách bài quiz rèn luyện</h4>
                <div class="d-flex align-items-center justify-content-center">
                    <div class="col-auto me-1">
                        <a href="{{ route('admin.quizzes.trash') }}"
                            class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
                            <iconify-icon icon="mdi:trash-can-outline" class="fs-20"></iconify-icon>
                            <span>Thùng rác</span>
                        </a>
                    </div>

                    <button class="btn btn-primary btn-sm btn-add" id="btn-add-quizz" data-bs-target="#modal-add-quiz">
                        <iconify-icon icon="material-symbols:add-circle" class="fs-5 me-1"></iconify-icon> Thêm bài quiz
                    </button>
                </div>
            </div>

            <!-- Quiz List -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card p-2">
                        <form action="{{ route('admin.quizzes.filter') }}" class="row g-2 d-flex align-items-end"
                            id="searchForm">
                            <input type="hidden" name="limit" id="limit" value="10">
                            <div class="col-12 col-md-6 col-lg-4 col-xl-2">
                                <label for="keyword" class="form-label fw-bold mb-1">Từ khóa</label>
                                <input type="text" name="keyword" id="keyword" class="form-control form-control-sm"
                                    placeholder="Tìm tiêu đề">
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 col-xl-2">
                                <label for="class_filter" class="form-label fw-bold mb-1">Lớp</label>
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
                                <label for="course_filter" class="form-label fw-bold mb-1">Khóa học</label>
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
                                <label for="status_filter" class="form-label fw-bold mb-1">Trạng thái</label>
                                <select name="status" id="status_filter" class="form-select form-select-sm">
                                    <option value="">Tất cả</option>
                                    <option value="draft">Nháp</option>
                                    <option value="published">Đã Xuất bản</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 col-xl-2">
                                <label for="is_public_filter" class="form-label fw-bold mb-1">Công khai</label>
                                <select name="is_public" id="is_public_filter" class="form-select form-select-sm">
                                    <option value="">Tất cả</option>
                                    <option value="1">Có</option>
                                    <option value="0">Không</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 col-xl-2 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-success btn-sm w-50">
                                    <iconify-icon icon="material-symbols:filter-alt" class="me-1"></iconify-icon> Lọc
                                </button>

                                <button type="reset" class="btn btn-danger btn-sm w-50">
                                    <iconify-icon icon="material-symbols:delete" class="me-1"></iconify-icon> Xóa
                                </button>

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
                                            <th>Mã truy cập</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody id="body-quizzes">
                                        @if (isset($quizzes) && $quizzes->isEmpty())
                                            <tr>
                                                <td colspan="7" class="text-center text-muted py-5">
                                                    <div class="d-flex flex-column align-items-center justify-content-center"
                                                        style="min-height: 10px;">
                                                        <iconify-icon icon="ant-design:inbox-outlined"
                                                            style="font-size: 40px;" class="mb-2 text-secondary">
                                                        </iconify-icon>
                                                        <span>Không có bài quiz nào phù hợp với tiêu chí tìm kiếm.</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        @else
                                            @foreach ($quizzes as $quiz)
                                                <tr>
                                                    <td>
                                                        <div class="fw-bold">{{ $quiz->title }} <p class="text-danger">
                                                                {{ $quiz->status == 'published' ? '' : ' (Bản nháp)' }}</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if ($quiz->is_public)
                                                            <div class="fw-bold">Tất cả</div>
                                                        @else
                                                            <div class="fw-bold">{{ $quiz->class->name ?? 'Tất cả' }}
                                                            </div>
                                                            <div class="fs-6">Khóa:
                                                                {{ $quiz->course->name ?? 'Tất cả' }}
                                                            </div>
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
                                                    <td>{{ $quiz->access_code ?? '-' }}</td>
                                                    <td>
                                                        <div class="btn-group dropstart">
                                                            <button class="btn btn-light btn-sm dropdown-toggle"
                                                                type="button" data-bs-toggle="dropdown">
                                                                Thao tác
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li><a href="{{ route('admin.quizzes.detail', $quiz->id) }}"
                                                                        class="dropdown-item text-info"
                                                                        data-quiz-id="{{ $quiz->id }}"><iconify-icon
                                                                            icon="solar:eye-broken"
                                                                            class="me-1"></iconify-icon> Chi tiết</a>
                                                                </li>
                                                                <li><button
                                                                        class="dropdown-item text-warning btn-edit-quiz"
                                                                        data-bs-target="#modal-add-quiz"
                                                                        data-quiz-id="{{ $quiz->id }}"><iconify-icon
                                                                            icon="solar:pen-2-broken"
                                                                            class="me-1"></iconify-icon> Sửa</button>
                                                                </li>
                                                                <li>
                                                                    <form
                                                                        action="{{ route('admin.quizzes.delete', $quiz->id) }}"
                                                                        method="POST" style="display:inline;">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="button"
                                                                            class="dropdown-item text-danger bg-transparent border-0 w-100 text-start btn-delete-quiz">
                                                                            <iconify-icon
                                                                                icon="solar:trash-bin-minimalistic-2-broken"
                                                                                class="me-1"></iconify-icon> Xóa
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                                <li><a class="dropdown-item"
                                                                        href="{{ route('admin.quizzes.results', $quiz->id) }}"><iconify-icon
                                                                            icon="solar:chart-broken"
                                                                            class="me-1"></iconify-icon> Xem kết quả</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
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

            <!-- Modal Thêm/Sửa Quiz -->
            <div class="modal fade" id="modal-add-quiz" tabindex="-1" aria-labelledby="addQuizModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                        <div class="modal-header custom-modal-header bg-light-subtle">
                            <h5 class="modal-title d-flex align-items-center gap-2" id="addQuizModalLabel">
                                <iconify-izcon icon="solar:pen-2-broken" class="text-primary"></iconify-izcon>
                                <span id="modal-title-text">Thêm bài quiz</span>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <form id="quiz-form" action="{{ route('admin.quizzes.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="_method" id="form-method" value="POST">
                            <input type="hidden" name="quiz_id" id="quiz-id">
                            <div class="modal-body d-flex justify-content-between flex-wrap"
                                style="overflow-y: scroll; height: 60vh">
                                <div id="error-container" class="col-12 mb-3" style="display: none;"></div>
                                <div class="mb-3 col-12 col-md-12 p-2">
                                    <label for="quiz_title" class="form-label fw-bold">Tiêu đề</label>
                                    <input type="text" class="form-control form-control-sm" id="quiz_title"
                                        name="title" placeholder="Nhập tiêu đề bài quiz">
                                </div>
                                <div class="mb-3 col-12 col-md-6 p-2">
                                    <label for="description" class="form-label fw-bold">Mô tả</label>
                                    <input type="text" class="form-control form-control-sm" id="description"
                                        name="description" placeholder="Nhập mô tả bài quiz">
                                </div>
                                <div class="mb-3 col-12 col-md-6 p-2">
                                    <label for="duration" class="form-label fw-bold">Thời gian (phút)</label>
                                    <input type="number" class="form-control form-control-sm" id="duration"
                                        name="duration_minutes" placeholder="Nhập thời gian" min="1">
                                </div>

                                <div class="mb-3 col-12 col-md-6 p-2">
                                    <label class="form-label fw-bold">Trạng thái công khai</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="is_public" id="is_public_1"
                                            value="1">
                                        <label class="form-check-label" for="is_public_1">Công khai</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="is_public" id="is_public_0"
                                            value="0" checked>
                                        <label class="form-check-label" for="is_public_0">Không công khai</label>
                                    </div>
                                </div>
                                <div class="mb-3 col-12 col-md-6 p-2 class-course-fields" id="class_field">
                                    <label for="class_id" class="form-label fw-bold">Lớp học</label>
                                    <select class="form-select form-select-sm" id="class_id" name="class_id"
                                        data-choices>
                                        <option value="">Chọn lớp</option>
                                        @foreach (\DB::table('classes')->get() as $class)
                                            <option value="{{ $class->id }}" data-course="{{ $class->courses_id }}">
                                                {{ $class->name }}</option>
                                        @endforeach

                                    </select>
                                </div>
                                <div class="mb-3 col-12 col-md-6 p-2 class-course-fields" id="course_field">
                                    <label for="course_id" class="form-label fw-bold">Khóa học</label>
                                    <select class="form-select form-select-sm" id="course_id" name="course_id"
                                        data-choices>
                                        <option value="">Chọn khóa học</option>
                                        @foreach (\DB::table('courses')->get() as $course)
                                            <option value="{{ $course->id }}">{{ $course->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 text-muted small d-flex align-items-center mt-2">
                                    <iconify-icon icon="mdi:information" class="me-2 text-warning"></iconify-icon>

                                    Quiz chỉ có thể được tạo cho "một lớp" hoặc "một khóa học" hoặc để "công khai"
                                    không
                                    đồng thời nhiều đối tượng!
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary custom-btn-cancel"
                                    data-bs-dismiss="modal">
                                    <iconify-icon icon="solar:close-circle-broken" class="me-1"></iconify-icon> Đóng
                                </button>
                                <button type="submit" class="btn btn-primary custom-btn-submit">
                                    <iconify-icon icon="solar:check-circle-broken" class="me-1"></iconify-icon> Lưu
                                </button>
                            </div>
                        </form>
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
                        </script> © DỰ ÁN TỐT NGHIỆP CAO ĐẲNG FPT THANH HÓA
                        <iconify-icon icon="iconamoon:heart-duotone"
                            class="fs-18 align-middle text-danger"></iconify-icon>
                        <a href="#" class="fw-bold footer-text" target="_blank">NHÓM 4</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
@endsection

@push('scripts')
    <script>
        const publicRadio = $('#is_public_1');
        const privateRadio = $('#is_public_0');
        const classField = $('#class_field');
        const courseField = $('#course_field');
        const classSelect = $('#class_id');
        const courseSelect = $('#course_id');
        const form = $('#quiz-form');
        const modalTitle = $('#modal-title-text');
        const formMethod = $('#form-method');
        const quizIdInput = $('#quiz-id');
        const errorContainer = $('#error-container');

        function toggleFields() {
            if (publicRadio.prop('checked')) {
                classField.hide();
                courseField.hide();

            } else {
                classField.show();
                courseField.show();
            }
        }

        publicRadio.on('change', toggleFields);
        privateRadio.on('change', toggleFields);
        toggleFields();



        // Mở modal Thêm quiz
        $('#btn-add-quizz').on('click', function() {
            $("#modal-add-quiz").modal("show");
            if (window.myChoicesInstance) {
                window.myChoicesInstance.destroy();
            }

            window.myChoicesInstance = new Choices('#my-select', {
                searchEnabled: true,
                itemSelectText: '',
                shouldSort: false
            });

            form.attr('action', '{{ route('admin.quizzes.store') }}');
            formMethod.val('POST');
            modalTitle.text('Thêm bài quiz');
            quizIdInput.val('');
            form[0].reset();
            $('#is_public_0').prop('checked', true); // chọn mặc định là private
            $('#course_id').val('').prop('disabled', false);
            toggleFields();
            errorContainer.hide().empty();


        });




        $(document).ready(function() {
            $('#class_id').on('change', function() {
                const classId = $(this).val();
                if (classId) {
                    $('#course_field').hide();

                } else {
                    $('#course_field').show();
                }
            });

            $('#course_id').on('change', function() {
                const courseId = $(this).val();
                if (courseId) {
                    $('#class_field').hide();

                } else {
                    $('#class_field').show();
                }
            });
        });


        // Mở modal Sửa quiz
        $(document).on('click', '.btn-edit-quiz', function() {
            $("#modal-add-quiz").modal("show");
            const quizId = $(this).data('quiz-id');
            quizIdInput.val(quizId);
            form.attr('action', `/admin/quizzes/${quizId}/update`);
            formMethod.val('PUT');
            modalTitle.text('Sửa bài quiz');
            errorContainer.hide().empty();

            $.ajax({
                url: `/admin/quizzes/${quizId}/detail`,
                method: 'GET',
                success: function(data) {
                    console.log(data)
                    $('#quiz_title').val(data.title);
                    $('#description').val(data.description);
                    $('#duration').val(data.duration_minutes);
                    $('#access_code').val(data.access_code || '');
                    $(`#is_public_${data.is_public}`).prop('checked', true);
                    classSelect.val(data.class_id || '');
                    courseSelect.val(data.course_id || '');
                    toggleFields();
                },
                error: function() {
                    Swal.fire('Lỗi!', 'Không thể tải dữ liệu quiz.', 'error');
                }
            });
        });


        $('#quiz-form').on('submit', function(e) {
            e.preventDefault();
            $('#course_id').prop('disabled', false);
            const form = $(this);
            const actionUrl = form.attr('action');
            const method = form.find('input[name="_method"]').val() || form.attr('method');
            console.log(form.serialize());
            errorContainer.hide().empty();

            $.ajax({
                url: actionUrl,
                method: method, // Không cần kiểm tra PUT → POST nữa nếu server xử lý _method
                data: form.serialize(),
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        window.location.href = `/admin/quizzes/${response.quiz.id}/detail`;
                    }
                    if (response.action === 'edit') {
                        Swal.fire({
                            title: 'Sửa thành công!',
                            text: 'Bài quiz đã được sửa thành công.',
                            icon: 'success',
                            confirmButtonClass: 'btn btn-primary w-xs mt-2',
                            buttonsStyling: false
                        }).then(() => {
                            refreshQuizList();
                        })
                    }
                    form[0].reset(); // reset đúng
                    $('#modal-add-quiz').modal('hide');
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        let errorHtml = '<div class="alert alert-danger"><ul>';
                        for (let field in errors) {
                            errors[field].forEach(msg => {
                                errorHtml += `<li>${msg}</li>`;
                            });
                        }
                        errorHtml += '</ul></div>';
                        errorContainer.html(errorHtml).show(); // đúng cú pháp jQuery
                    } else {
                        Swal.fire({
                            title: 'Lỗi!',
                            text: 'Đã có lỗi xảy ra. Vui lòng thử lại.',
                            icon: 'error',
                            confirmButtonClass: 'btn btn-primary w-xs mt-2',
                            buttonsStyling: false
                        });
                    }
                }
            });
        });

















        // Reset search
        $('#searchForm').on('reset', function() {
            setTimeout(() => window.location.reload(), 10);
        });

        // Handle limit change
        $('#limit2').change(function() {
            $('#searchForm #limit').val(this.value);
            $('#searchForm').submit();
        });

        //Hàm Filter
        $('#searchForm').on('submit', function(e) {
            e.preventDefault();
            $('.table').css({
                'opacity': '0.5',
                'pointer-events': 'none' // nếu muốn không bấm được
            });
            $.ajax({
                url: this.action,
                type: 'GET',
                data: $(this).serialize(),
                success: function(response) {
                    $('#body-quizzes').html(renderQuizzes(response.quizzes.data));
                    $('#pagination-wrapper').html(response.pagination);
                    $('.table').css({
                        'opacity': '1',
                        'pointer-events': 'none' // nếu muốn không bấm được
                    });
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

        // Delete quiz
        $(document).on('click', '.btn-delete-quiz', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            const actionUrl = form.attr('action');
            Swal.fire({
                title: 'Bạn có chắc chắn?',
                text: "Bài quiz sẽ được đưa vào thùng rác!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Vâng, xóa nó!',
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
                            Swal.fire('Đã xóa!', 'Quiz đã được xóa thành công.',
                                'success');
                            form.closest('tr').remove();
                        },
                        error: function() {
                            Swal.fire('Lỗi!', 'Không thể xóa quiz.', 'error');
                        }
                    });
                }
            });
        });

        // Render quizzes
        function renderQuizzes(data) {
            if (data.length === 0) {
                return '<tr><td colspan="7" class="text-center"><div class="alert alert-warning">Không tìm thấy kết quả</div></td></tr>';
            }
            let html = '';
            data.forEach(quiz => {
                html += `
                        <tr>
                            <td>
                                <div class="fw-bold">${ quiz.title } <p class="text-danger">
                                    ${ quiz.status == 'published' ? '' : ' (Bản nháp)' }</p>
                                </div>
                            </td>
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
                            <td>${quiz.access_code || '-'}</td>
                            <td>
                                <div class="btn-group dropstart">
                                    <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        Thao tác
                                    </button>
                                    <ul class="dropdown-menu">
                                         <li><a href="/admin/quizzes/${quiz.id}/detail"
                                                                    class="dropdown-item text-info"
                                                                    data-quiz-id="${quiz.id}"><iconify-icon
                                                                        icon="solar:eye-broken"
                                                                        class="me-1"></iconify-icon> Chi tiết</a></li>
                                        <li><a class="dropdown-item text-warning btn-edit-quiz" href="#" data-bs-toggle="modal" data-bs-target="#modal-add-quiz" data-quiz-id="${quiz.id}"><iconify-icon icon="solar:pen-2-broken" class="me-1"></iconify-icon> Sửa</a></li>
                                        <li>
                                            <form action="/admin/quizzes/${quiz.id}/delete" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="dropdown-item text-danger bg-transparent border-0 w-100 text-start btn-delete-quiz">
                                                    <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="me-1"></iconify-icon> Xóa
                                                </button>
                                            </form>
                                        </li>
                                        <li><a class="dropdown-item" href="#"><iconify-icon icon="solar:chart-broken" class="me-1"></iconify-icon> Xem kết quả</a></li>
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
                url: '{{ route('admin.quizzes.filter') }}',
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
