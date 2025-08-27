@extends('admin.admin')

@section('title', 'Kết quả Quiz theo Lớp')
@section('description', '')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.quizz') }}">Quản lý Quiz</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Kết quả theo Lớp</li>
                </ol>
            </nav>

            <!-- Summary Cards -->
            <div class="row">
                <div class="col-md-6 col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h4 class="card-title mb-2 d-flex align-items-center gap-2 fs-5">Tổng số lớp</h4>
                                    <p class="text-muted fw-medium fs-22 mb-0">{{ $statistics[0]->total_classes_with_quiz }} lớp</p>
                                </div>
                                <div class="avatar-md bg-primary bg-opacity-10 rounded">
                                    <iconify-icon icon="solar:buildings-broken" class="fs-32 avatar-title text-primary"></iconify-icon>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h4 class="card-title mb-2 d-flex align-items-center gap-2 fs-5">Học sinh tham gia</h4>
                                    <p class="text-muted fw-medium fs-22 mb-0">{{ $statistics[0]->total_students_attempted }} học sinh</p>
                                </div>
                                <div class="avatar-md bg-success bg-opacity-10 rounded">
                                    <iconify-icon icon="solar:user-broken" class="fs-32 avatar-title text-success"></iconify-icon>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h4 class="card-title mb-2 d-flex align-items-center gap-2 fs-5">Tổng lượt làm bài</h4>
                                    <p class="text-muted fw-medium fs-22 mb-0">{{ $statistics[0]->total_attempts_for_quiz }} lượt</p>
                                </div>
                                <div class="avatar-md bg-info bg-opacity-10 rounded">
                                    <iconify-icon icon="solar:check-circle-broken" class="fs-32 avatar-title text-info"></iconify-icon>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Title -->
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h4 class="card-title mb-1">Danh sách lớp có học viên làm quiz: {{  $quiz->title }}</h4>
                <a href="{{ route('admin.quizz') }}" class="btn btn-outline-secondary btn-sm me-2">
                    <iconify-icon icon="solar:arrow-left-broken" class="fs-20"></iconify-icon> Quay lại
                </a>
            </div>

            <!-- Filter Form -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card p-2">
                        <form action="{{ route('admin.quizzes.filter.reults.class') }}" class="row g-2 d-flex align-items-end" id="searchForm">
                            <input type="hidden" name="limit" id="limit" value="10">
                            <input type="hidden" name="quiz_id" id="quiz_id" value="{{ $quiz->id }}">
                            <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                                <label for="class_filter" class="form-label mb-1">Lớp học</label>
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
                            <div class="col-12 col-md-6 col-lg-4 col-xl-3">
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
                            <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                                <label for="class_status" class="form-label mb-1">Trạng thái lớp học</label>
                                <select name="class_status" id="class_status" class="form-select form-select-sm">
                                    <option value="">Tất cả</option>
                                    <option value="completed">Đã hoàn thành</option>
                                    <option value="in_progress">Đang hoạt động</option>
                                    <option value="not_started">Chưa bắt đầu</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 col-xl-3 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-success btn-sm w-50">Lọc</button>
                                <button type="reset" class="btn btn-danger btn-sm w-50">Xóa</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Class List -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="table-responsive table-gridjs">
                                <table class="table table-custom align-middle mb-0 table-hover table-centered" style="border-radius: 0;">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Tên lớp</th>
                                            <th>Khóa học</th>
                                            <th>Số học sinh làm bài</th>
                                            <th>Tổng lượt làm bài</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody id="body-classes">
                                        @foreach ($classes as $class)
                                            <tr>
                                                <td><div class="fw-bold">{{ $class->class_name }}</div></td>
                                                <td><div class="fw-bold">{{ $class->course_name }}</div></td>
                                                <td>{{ $class->students_attempted }}/{{ $class->total_students }} (học sinh)</td>
                                                <td>{{ $class->total_attempts }}</td>
                                                <td>
                                                    <a href="{{ route('admin.quizzes.results.class', ['id' => $quiz->id, 'class' => $class->class_id]) }}" class="btn btn-outline-info btn-sm"><iconify-icon icon="solar:eye-broken" class="me-1"></iconify-icon> Xem chi tiết</a>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 m-3">
                             <div id="pagination-wrapper" class="flex-grow-1">
                                {{ $classes->links('pagination::bootstrap-5') }}
                            </div>
                            <div class="d-flex align-items-center" style="min-width: 160px;">
                                <label for="limit2" class="form-label mb-0 me-2 small">Hiển thị</label>
                                <select name="limit2" id="limit2" class="form-select form-select-sm" style="width: 100px;">
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
                        <iconify-icon icon="iconamoon:heart-duotone" class="fs-18 align-middle text-danger"></iconify-icon>
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

        //Hàm Filter
        $('#searchForm').on('submit', function(e) {
            e.preventDefault();
            console.log('Đang tìm kiếm:'+  $(this).serialize());
            $.ajax({
                url: this.action,
                type: 'GET',
                data: $(this).serialize(),
                success: function(response) {
                    console.log('Kết quả tìm kiếm:', response);
                    $('#body-classes').html(renderClasses(response.classes.data));
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
                    $('#body-classes').html(renderClasses(response.classes.data));
                    $('#pagination-wrapper').html(response.pagination);
                },
                error: function(xhr) {
                    console.error('Lỗi phân trang:', xhr.responseText);
                }
            });
        });


         // Render classes
        function renderClasses(data) {
            if (data.length === 0) {
                return '<tr><td colspan="7" class="text-center"><div class="alert alert-warning">Không tìm thấy kết quả</div></td></tr>';
            }
            const quizId = $('#quiz_id').val();
            let html = '';
            data.forEach(classes => {
                html += `
                    <tr>
                        <td><div class="fw-bold">${classes.class_name}</div></td>
                        <td><div class="fw-bold">${classes.course_name}</div></td>
                        <td>${classes.students_attempted}/${classes.total_students} (học sinh)</td>
                        <td>${classes.total_attempts}</td>
                        <td>
                            <a href="/admin/quizzes/${quizId}/results/class/${classes.class_id}" class="btn btn-outline-info btn-sm">
                                <iconify-icon icon="solar:eye-broken" class="me-1"></iconify-icon> Xem chi tiết
                            </a>
                        </td>
                    </tr>
                    `;
            });
            return html;
        }
    </script>
@endpush
