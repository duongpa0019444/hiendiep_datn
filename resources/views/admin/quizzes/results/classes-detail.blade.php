@extends('admin.admin')

@section('title', 'Kết quả Học viên')
@section('description', '')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.quizz') }}">Quản lý Quiz</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.quizzes.results', ['id' => $quiz->id]) }}">Kết quả theo Lớp</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Học viên trong Lớp</li>
                </ol>
            </nav>

            <!-- Summary Cards -->
            <div class="row">
                <div class="col-md-6 col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h4 class="card-title mb-2 d-flex align-items-center gap-2 fs-5">Tổng học sinh trong lớp</h4>
                                    <p class="text-muted fw-medium fs-22 mb-0">{{ $statistics[0]->total_students ?? 0 }} học sinh</p>
                                </div>
                                <div class="avatar-md bg-primary bg-opacity-10 rounded">
                                    <iconify-icon icon="solar:user-broken" class="fs-32 avatar-title text-primary"></iconify-icon>
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
                                    <p class="text-muted fw-medium fs-22 mb-0">{{ $statistics[0]->students_attempted ?? 0 }} học sinh</p>
                                </div>
                                <div class="avatar-md bg-success bg-opacity-10 rounded">
                                    <iconify-icon icon="solar:users-group-two-rounded-broken" class="fs-32 avatar-title text-success"></iconify-icon>
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
                                    <p class="text-muted fw-medium fs-22 mb-0">{{ $statistics[0]->total_attempts ?? 0 }} lượt</p>
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
                <h4 class="card-title mb-1">Học viên lớp: ({{ $class->name }}) - (Khóa: {{ $class->course->name }}) - ({{ $quiz->title }})</h4>
                <a href="{{ route('admin.quizzes.results', ['id' => $quiz->id]) }}" class="btn btn-outline-secondary btn-sm me-2">
                    <iconify-icon icon="solar:arrow-left-broken" class="fs-20"></iconify-icon> Quay lại
                </a>
            </div>

            <!-- Filter Form -->
            <div class="row">
                <div class="col-12">
                    <div class="card p-3">
                        <form action="{{ route('admin.quizzes.results.class.student.filter') }}" class="row g-3 align-items-end" id="searchForm">
                            <input type="hidden" name="limit" id="limit" value="10">
                            <input type="hidden" name="quiz_id" id="quiz_id" value="{{ $quiz->id }}">
                            <input type="hidden" name="class_id" id="class_id" value="{{ $class->id }}">
                            <!-- Tên học sinh -->
                            <div class="col">
                                <label for="keyword" class="form-label mb-1">Tên học sinh</label>
                                <input type="text" name="keyword" id="keyword" class="form-control form-control-sm" placeholder="Nhập tên">
                            </div>

                            <!-- Giới tính -->
                            <div class="col">
                                <label for="gender" class="form-label mb-1">Giới tính</label>
                                <select name="gender" id="gender" class="form-select form-select-sm">
                                    <option value="">Tất cả</option>
                                    <option value="male">Nam</option>
                                    <option value="female">Nữ</option>
                                    <option value="other">Khác</option>
                                </select>
                            </div>

                            <!-- Ngày sinh -->
                            <div class="col">
                                <label for="birth_date" class="form-label mb-1">Ngày sinh</label>
                                <input type="date" name="birth_date" id="birth_date" class="form-control form-control-sm">
                            </div>

                            <!-- Điểm trung bình -->
                            <div class="col">
                                <label for="avg_score" class="form-label mb-1">Điểm TB</label>
                                <input type="number" name="avg_score" id="avg_score" class="form-control form-control-sm" placeholder="= điểm">
                            </div>

                            <!-- Lượt làm bài -->
                            <div class="col">
                                <label for="attempts" class="form-label mb-1">Lượt làm</label>
                                <input type="number" name="attempts" id="attempts" class="form-control form-control-sm" placeholder="= số lượt">
                            </div>

                            <!-- Nút lọc -->
                            <div class="col d-flex gap-2">
                                <button type="submit" class="btn btn-success btn-sm w-50">Lọc</button>
                                <button type="reset" class="btn btn-danger btn-sm w-50">Xóa</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>




            <!-- Student List -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="table-responsive table-gridjs">
                                <table class="table table-custom align-middle mb-0 table-hover table-centered" style="border-radius: 0;">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Tên học viên</th>
                                            <th>Ngày sinh</th>
                                            <th>Giới tính</th>
                                            <th>Số lần làm bài</th>
                                            <th>Điểm trung bình</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody id="body-students-results-quizz">
                                        @foreach ($students as $student)
                                            <tr>
                                                <td><div class="fw-bold">{{ $student->student_name }}</div></td>
                                                <td>{{ $student->birthday }}</td>
                                                <td>{{ $student->gender }}</td>
                                                <td>{{ $student->total_attempts }}</td>
                                                <td>
                                                    @php
                                                        $score = $student->average_score;
                                                        $badgeClass = 'text-muted'; // mặc định

                                                        if ($score >= 8.5) {
                                                            $badgeClass = 'text-success fw-bold'; // Điểm cao
                                                        } elseif ($score >= 6.5) {
                                                            $badgeClass = 'text-primary'; // Khá
                                                        } elseif ($score >= 5) {
                                                            $badgeClass = 'text-warning'; // Trung bình
                                                        } else {
                                                            $badgeClass = 'text-danger'; // Kém
                                                        }
                                                    @endphp
                                                    <span class="{{ $badgeClass }}">{{ $score }}</span>
                                                </td>
                                                                                                <td>
                                                    <a href="{{ route('admin.quizzes.results.class.student', ['id' => $quiz->id,'class' => $class->id, 'student' => $student->student_id]) }}" class="btn btn-outline-info btn-sm"><iconify-icon icon="solar:eye-broken" class="me-1"></iconify-icon> Xem chi tiết</a>
                                                </td>
                                            </tr>
                                        @endforeach


                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 m-3">
                            <div id="pagination-wrapper" class="flex-grow-1">
                                {{ $students->links('pagination::bootstrap-5') }}
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
                    $('#body-students-results-quizz').html(renderStudentsResultsQuizz(response.students.data));
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
                    $('#body-students-results-quizz').html(renderStudentsResultsQuizz(response.students.data));
                    $('#pagination-wrapper').html(response.pagination);
                },
                error: function(xhr) {
                    console.error('Lỗi phân trang:', xhr.responseText);
                }
            });
        });


         // Render classes
        function renderStudentsResultsQuizz(data) {
            if (data.length === 0) {
                return '<tr><td colspan="7" class="text-center"><div class="alert alert-warning">Không tìm thấy kết quả</div></td></tr>';
            }
            const quizId = $('#quiz_id').val();
            const classId = $('#class_id').val();
            let html = '';
            data.forEach(student => {
                html += `
                    <tr>
                        <td><div class="fw-bold">${student.student_name}</div></td>
                        <td>${student.birthday}</td>
                        <td>${student.gender}</td>
                        <td>${student.total_attempts}</td>
                        <td>
                            <span class="${student.average_score >= 8.5 ? 'text-success fw-bold' : student.average_score >= 6.5 ? 'text-primary' : student.average_score >= 5 ? 'text-warning' : 'text-danger'}">
                                ${student.average_score}
                            </span>
                        </td>
                        <td>
                            <a href="/admin/quizzes/${quizId}/results/class/${classId}/student/${student.student_id}" class="btn btn-outline-info btn-sm">
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
