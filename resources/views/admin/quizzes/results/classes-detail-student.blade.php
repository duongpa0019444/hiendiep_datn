@extends('admin.admin')

@section('title', 'Kết quả các lần làm Quiz')
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
                    <li class="breadcrumb-item"><a href="{{ route('admin.quizzes.results.class', ['id' => $quiz->id, 'class' => $class->id]) }}">Học viên trong Lớp</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Kết quả các lần làm Quiz</li>
                </ol>
            </nav>

            <!-- Summary Cards -->
            <div class="row">
                <div class="col-md-6 col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h4 class="card-title mb-2 d-flex align-items-center gap-2 fs-5">Tổng lần làm bài</h4>
                                    <p class="text-muted fw-medium fs-22 mb-0">{{ $statistics[0]->total_attempts ?? 0 }} lần</p>
                                </div>
                                <div class="avatar-md bg-primary bg-opacity-10 rounded">
                                    <iconify-icon icon="solar:check-circle-broken" class="fs-32 avatar-title text-primary"></iconify-icon>
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
                                    <h4 class="card-title mb-2 d-flex align-items-center gap-2 fs-5">Điểm trung bình</h4>
                                    <p class="text-muted fw-medium fs-22 mb-0">{{ $statistics[0]->average_score ?? 0 }}</p>
                                </div>
                                <div class="avatar-md bg-success bg-opacity-10 rounded">
                                    <iconify-icon icon="solar:star-broken" class="fs-32 avatar-title text-success"></iconify-icon>
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
                                    <h4 class="card-title mb-2 d-flex align-items-center gap-2 fs-5">Thời gian làm bài trung bình</h4>
                                    <p class="text-muted fw-medium fs-22 mb-0">{{ $statistics[0]->average_time_minutes ?? 0 }} phút</p>
                                </div>
                                <div class="avatar-md bg-info bg-opacity-10 rounded">
                                    <iconify-icon icon="solar:clock-circle-broken" class="fs-32 avatar-title text-info"></iconify-icon>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Title -->
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h4 class="card-title mb-1">Các lần làm quiz học viên: ({{ $student->name }}) - ({{ $class->name }}) - ({{ $class->course->name }}) - ({{ $quiz->title }})</h4>
                <a href="{{ route('admin.quizzes.results.class', ['id' => $quiz->id, 'class' => $class->id]) }}" class="btn btn-outline-secondary btn-sm me-2">
                    <iconify-icon icon="solar:arrow-left-broken" class="fs-20"></iconify-icon> Quay lại
                </a>
            </div>

            <!-- Filter Form -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card p-2">
                        <form action="{{ route('admin.quizzes.results.class.student.filter') }}" class="row g-2 align-items-end" id="searchForm">
                            <input type="hidden" name="limit" id="limit" value="10">
                            <input type="hidden" name="quiz_id" id="quiz_id" value="{{ $quiz->id }}">
                            <input type="hidden" name="class_id" id="class_id" value="{{ $class->id }}">
                            <input type="hidden" name="student_id" id="student_id" value="{{ $student->id }}">


                            <div class="col">
                                <label for="date_filter" class="form-label mb-1">Ngày làm bài</label>
                                <input type="date" name="completed_at" id="date_filter" class="form-control form-control-sm">
                            </div>

                            <div class="col">
                                <label for="score_filter" class="form-label mb-1">Điểm từ</label>
                                <input type="number" name="score" class="form-control form-control-sm" placeholder="VD: 7">
                            </div>

                            <div class="col">
                                <label for="duration_filter" class="form-label mb-1">Thời gian làm (phút)</label>
                                <input type="number" name="max_duration" class="form-control form-control-sm" placeholder="VD: ≤ 20">
                            </div>

                            <div class="col d-flex gap-2">
                                <button type="submit" class="btn btn-success btn-sm mt-auto w-50">Lọc</button>
                                <button type="reset" class="btn btn-danger btn-sm mt-auto w-50">Xóa</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

            <!-- Quiz Attempts List -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="table-responsive table-gridjs">
                                <table class="table table-custom align-middle mb-0 table-hover table-centered" style="border-radius: 0;">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Thời gian làm bài</th>
                                            <th>Số câu đúng</th>
                                            <th>Điểm</th>
                                            <th>Ngày hoàn thành</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody id="body-quiz-attempts">
                                        @foreach ($attempts as $key => $attempt)
                                            <tr>
                                                <td><div class="fw-bold">{{ $key+1 }}</div></td>
                                                <td>{{ $attempt->duration_minutes }} phút</td>
                                                <td>{{ $attempt->total_correct }}/{{ $attempt->total_questions }}</td>
                                                <td>{{ $attempt->score }}</td>
                                                <td>{{ $attempt->completed_date }}</td>
                                                <td>
                                                    <a href="{{ route('admin.quizzes.results.class.student.attempts', ['id' => $quiz->id,'class' => $class->id, 'student' => $student->id, 'attempt' => $attempt->attempt_id]) }}"><button class="btn btn-outline-primary btn-sm">Xem chi tiết</button></a>
                                                </td>
                                            </tr>
                                        @endforeach


                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 m-3">
                            <div id="pagination-wrapper" class="flex-grow-1">
                                {{ $attempts->links('pagination::bootstrap-5') }}
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
                    $('#body-quiz-attempts').html(renderQuizAttempts(response.attempts.data));
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
                    $('#body-quiz-attempts').html(renderQuizAttempts(response.attempts.data));
                    $('#pagination-wrapper').html(response.pagination);
                },
                error: function(xhr) {
                    console.error('Lỗi phân trang:', xhr.responseText);
                }
            });
        });


         // Render classes
        function renderQuizAttempts(data) {
            if (data.length === 0) {
                return '<tr><td colspan="7" class="text-center"><div class="alert alert-warning">Không tìm thấy kết quả</div></td></tr>';
            }
            const quizId = $('#quiz_id').val();
            const classId = $('#class_id').val();
            const studentId = $('#student_id').val();
            let html = '';
            data.forEach((attempt, index) => {
                html += `
                    <tr>
                        <td><div class="fw-bold">${index + 1}</div></td>
                        <td>${attempt.duration_minutes} phút</td>
                        <td>${attempt.total_correct}/${attempt.total_questions}</td>
                        <td>${attempt.score}</td>
                        <td>${attempt.completed_date}</td>
                        <td>
                            <a href="/admin/quizzes/${quizId}/results/class/${classId}/student/${studentId}/attempts/${attempt.attempt_id}">
                                <button class="btn btn-outline-primary btn-sm">Xem chi tiết</button>
                            </a>
                        </td>
                    </tr>
                `;
            });

            return html;
        }
    </script>
@endpush
