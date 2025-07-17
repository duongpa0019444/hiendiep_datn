@extends('client.accounts.information')

@section('content-information')
    <div id="quizzes" class="content-section">

        <!-- Header Section -->
        <div class="quiz-container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="mb-2">🎯 Quản lý Quiz của bạn</h4>
                    <p class="mb-0 opacity-75 text-light">Tham gia quiz, theo dõi tiến độ và xem kết quả học tập</p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="d-flex justify-content-end align-items-center">
                        <div class="me-3">
                            <small class="opacity-75">Hoàn thành</small>
                            <div class="h4 mb-0">{{ $quizzesDone->count() }}</div>
                        </div>
                        <div>
                            <small class="opacity-75">Được giao</small>
                            <div class="h4 mb-0">{{ $assignedQuizzes->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <ul class="nav quiz-tabs" id="quizTabs">
            <li class="nav-item">
                <a class="nav-link active" href="#quiz-code" data-bs-toggle="tab">
                    <i class="fas fa-keyboard me-2"></i>Quiz (nhập mã)
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#assigned" data-bs-toggle="tab">
                    <i class="fas fa-tasks me-2"></i>Được giao
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#completed" data-bs-toggle="tab">
                    <i class="fas fa-check-circle me-2"></i>Hoàn thành
                </a>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Tab 1: Quiz Code Input -->
            <div class="tab-pane fade show active" id="quiz-code">
                <div class="quiz-container text-center">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <h4 class="mb-3">🔑 Tham gia Quiz bằng mã</h4>
                            <p class="mb-4 opacity-75 text-light">Nhập mã quiz mà giáo viên đã cung cấp để tham gia</p>
                            <div class="mb-3">
                                <input type="text" class="form-control quiz-code-input"
                                    placeholder="Nhập mã quiz (VD: ABC123)" style="text-transform: uppercase;">
                            </div>
                            <button class="btn btn-join">
                                <i class="fas fa-sign-in-alt me-2"></i>Tham gia Quiz
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 2: Assigned Quizzes -->
            <div class="tab-pane fade" id="assigned">
                <!-- Search Box -->
                <div class="search-box">
                    <input type="text" class="form-control" placeholder="Tìm kiếm quiz được giao...">
                    <i class="fas fa-search"></i>
                </div>

                <div class="quiz-scroll-container">
                    <button class="scroll-nav-btn left" id="scrollLeftAssigned">
                        <i class="icofont-rounded-left"></i>
                    </button>
                    <button class="scroll-nav-btn right" id="scrollRightAssigned">
                        <i class="icofont-rounded-right"></i>
                    </button>
                    <div class="quiz-cards-wrapper" id="quizCardsWrapperAssigned">
                        <div class="quiz-cards-row px-1">
                            @foreach ($assignedQuizzes as $quiz)
                                <div class="quiz-card-item">
                                    <div class="card quiz-card h-100">
                                        <div class="card-body">
                                            <span class="status-badge bg-primary text-white">Được giao</span>

                                            <div class="question-count">
                                                <i class="icofont-question-circle me-1"></i>
                                                {{ $quiz->total_questions }} câu hỏi
                                            </div>

                                            <h5 class="quiz-title">{{ $quiz->title }}</h5>

                                            <div class="quiz-meta">
                                                <i class="icofont-teacher"></i> Giáo viên: {{ $quiz->creator_name }}
                                            </div>

                                            <div class="quiz-meta mb-3">
                                                <i class="icofont-clock-time"></i> Ngày giao:
                                                {{ \Carbon\Carbon::parse($quiz->updated_at)->format('d/m/Y') }}
                                            </div>

                                            <div class="quiz-footer">
                                                <a href="{{ route('student.quizzes.start', ['id' => $quiz->id]) }}"
                                                    class="btn btn-outline-primary-quiz quiz-action-btn">
                                                    <i class="icofont-play-alt-1 me-1"></i> Bắt đầu
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 3: Completed Quizzes -->
            <div class="tab-pane fade" id="completed">
                <!-- Search Box -->
                <div class="search-box">
                    <input type="text" class="form-control" placeholder="Tìm kiếm quiz đã hoàn thành...">
                    <i class="fas fa-search"></i>
                </div>

                <!-- Completed Quiz Cards with Horizontal Scroll -->
                <div class="quiz-scroll-container">
                    <button class="scroll-nav-btn left" id="scrollLeftCompleted">
                        <i class="icofont-rounded-left"></i>
                    </button>
                    <button class="scroll-nav-btn right" id="scrollRightCompleted">
                        <i class="icofont-rounded-right"></i>
                    </button>
                    <div class="quiz-cards-wrapper" id="quizCardsWrapperCompleted">
                        <div class="quiz-cards-row px-1">
                            @foreach ($quizzesDone as $quiz)
                                <div class="quiz-card-item">
                                    <div class="card quiz-card h-100">
                                        <a href="{{ route('student.quizzes.showResult', $quiz->id) }}"
                                            class="card-body quiz-result-completed">
                                            <span class="status-badge bg-success text-white">Hoàn thành</span>

                                            <div class="question-count">
                                                <i class="icofont-question-circle me-1"></i>
                                                {{ $quiz->attempt_count }} lần làm
                                            </div>

                                            <h5 class="quiz-title">{{ $quiz->title }}</h5>

                                            <div class="quiz-meta mb-3">
                                                <i class="icofont-calendar"></i>
                                                Hoàn thành:
                                                {{ \Carbon\Carbon::parse($quiz->last_submitted_at)->format('d/m/Y') }}
                                            </div>

                                            <div class="quiz-footer">
                                                <div class="accuracy-badge accuracy-high">
                                                    Điểm trung bình: {{ number_format($quiz->avg_score, 1) }}
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<!-- Modal Thông Tin khi ấn tìm kiếm bằng mã Quiz -->
<div class="modal fade" id="quizInfoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thông tin Quiz</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-black" id="quiz-info-body">

            </div>

            <div class="modal-footer">
                <div class="quiz-footer">
                    <a href="" class="btn btn-outline-primary-quiz quiz-action-btn-search">
                        <i class="icofont-play-alt-1 me-1"></i> Bắt đầu
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const scrollWrapperAssigned = document.getElementById('quizCardsWrapperAssigned');
            const scrollLeftBtnAssigned = document.getElementById('scrollLeftAssigned');
            const scrollRightBtnAssigned = document.getElementById('scrollRightAssigned');

            if (scrollWrapperAssigned && scrollLeftBtnAssigned && scrollRightBtnAssigned) {
                const scrollAmount = 340;


                scrollLeftBtnAssigned.addEventListener('click', function() {
                    scrollWrapperAssigned.scrollBy({
                        left: -scrollAmount,
                        behavior: 'smooth'
                    });
                });

                scrollRightBtnAssigned.addEventListener('click', function() {
                    scrollWrapperAssigned.scrollBy({
                        left: scrollAmount,
                        behavior: 'smooth'
                    });
                });


                // Cuộn bằng cách kéo chuột
                let isDownAssigned = false;
                let startXAssigned;
                let scrollLeftStartAssigned;

                scrollWrapperAssigned.addEventListener('mousedown', (e) => {
                    isDownAssigned = true;
                    scrollWrapperAssigned.style.cursor = 'grabbing';
                    startXAssigned = e.pageX - scrollWrapperAssigned.offsetLeft;
                    scrollLeftStartAssigned = scrollWrapperAssigned.scrollLeft;
                });

                scrollWrapperAssigned.addEventListener('mouseleave', () => {
                    isDownAssigned = false;
                    scrollWrapperAssigned.style.cursor = 'grab';
                });

                scrollWrapperAssigned.addEventListener('mouseup', () => {
                    isDownAssigned = false;
                    scrollWrapperAssigned.style.cursor = 'grab';
                });

                scrollWrapperAssigned.addEventListener('mousemove', (e) => {
                    if (!isDownAssigned) return;
                    e.preventDefault();
                    const x = e.pageX - scrollWrapperAssigned.offsetLeft;
                    const walk = (x - startXAssigned) * 2;
                    scrollWrapperAssigned.scrollLeft = scrollLeftStartAssigned - walk;
                });

                // Touch events for mobile
                scrollWrapperAssigned.addEventListener('touchstart', (e) => {
                    startXAssigned = e.touches[0].pageX - scrollWrapperAssigned.offsetLeft;
                    scrollLeftStartAssigned = scrollWrapperAssigned.scrollLeft;
                });

                scrollWrapperAssigned.addEventListener('touchmove', (e) => {
                    if (!startXAssigned) return;
                    const x = e.touches[0].pageX - scrollWrapperAssigned.offsetLeft;
                    const walk = (x - startXAssigned) * 2;
                    scrollWrapperAssigned.scrollLeft = scrollLeftStartAssigned - walk;
                });
            }

            // Initialize scroll functionality for Completed tab
            const scrollWrapperCompleted = document.getElementById('quizCardsWrapperCompleted');
            const scrollLeftBtnCompleted = document.getElementById('scrollLeftCompleted');
            const scrollRightBtnCompleted = document.getElementById('scrollRightCompleted');

            if (scrollWrapperCompleted && scrollLeftBtnCompleted && scrollRightBtnCompleted) {
                const scrollAmount = 340;

                // Scroll left button
                scrollLeftBtnCompleted.addEventListener('click', function() {
                    scrollWrapperCompleted.scrollBy({
                        left: -scrollAmount,
                        behavior: 'smooth'
                    });
                });

                // Scroll right button
                scrollRightBtnCompleted.addEventListener('click', function() {
                    scrollWrapperCompleted.scrollBy({
                        left: scrollAmount,
                        behavior: 'smooth'
                    });
                });

                let isDownCompleted = false;
                let startXCompleted;
                let scrollLeftStartCompleted;

                scrollWrapperCompleted.addEventListener('mousedown', (e) => {
                    isDownCompleted = true;
                    scrollWrapperCompleted.style.cursor = 'grabbing';
                    startXCompleted = e.pageX - scrollWrapperCompleted.offsetLeft;
                    scrollLeftStartCompleted = scrollWrapperCompleted.scrollLeft;
                });

                scrollWrapperCompleted.addEventListener('mouseleave', () => {
                    isDownCompleted = false;
                    scrollWrapperCompleted.style.cursor = 'grab';
                });

                scrollWrapperCompleted.addEventListener('mouseup', () => {
                    isDownCompleted = false;
                    scrollWrapperCompleted.style.cursor = 'grab';
                });

                scrollWrapperCompleted.addEventListener('mousemove', (e) => {
                    if (!isDownCompleted) return;
                    e.preventDefault();
                    const x = e.pageX - scrollWrapperCompleted.offsetLeft;
                    const walk = (x - startXCompleted) * 2;
                    scrollWrapperCompleted.scrollLeft = scrollLeftStartCompleted - walk;
                });

                // Touch events for mobile
                scrollWrapperCompleted.addEventListener('touchstart', (e) => {
                    startXCompleted = e.touches[0].pageX - scrollWrapperCompleted.offsetLeft;
                    scrollLeftStartCompleted = scrollWrapperCompleted.scrollLeft;
                });

                scrollWrapperCompleted.addEventListener('touchmove', (e) => {
                    if (!startXCompleted) return;
                    const x = e.touches[0].pageX - scrollWrapperCompleted.offsetLeft;
                    const walk = (x - startXCompleted) * 2;
                    scrollWrapperCompleted.scrollLeft = scrollLeftStartCompleted - walk;
                });
            }

            // Quiz code input formatting
            const codeInput = document.querySelector('.quiz-code-input');
            if (codeInput) {
                codeInput.addEventListener('input', function() {
                    this.value = this.value.toUpperCase();
                });

                // Add enter key support
                codeInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        const joinBtn = document.querySelector('.btn-join');
                        if (joinBtn) {
                            joinBtn.click();
                        }
                    }
                });
            }

            // Search
            const searchInputs = document.querySelectorAll('.search-box input');
            searchInputs.forEach(input => {
                input.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    const quizCards = document.querySelectorAll('.quiz-card');

                    quizCards.forEach(card => {
                        const title = card.querySelector('.quiz-title').textContent
                            .toLowerCase();
                        const teacher = card.querySelector('.quiz-meta').textContent
                            .toLowerCase();
                        const cardContainer = card.closest('.quiz-card-item') || card;

                        if (title.includes(searchTerm) || teacher.includes(searchTerm)) {
                            cardContainer.style.display = 'block';
                        } else {
                            cardContainer.style.display = 'none';
                        }
                    });
                });
            });

            // Enhanced button animations
            const actionButtons = document.querySelectorAll('.quiz-action-btn');
            actionButtons.forEach(btn => {
                btn.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                });

                btn.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });


        });






        $(document).on('click', '.quiz-result-completed', function(e) {
            e.preventDefault();
             $('#simple-preloader').css('display', 'flex');

            const url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    console.log(response);

                    let html = '';
                    response.quizAttempts.forEach((item, index) => {
                        html += `
                            <a href="/student/quizz/${item.quiz_id}/show-result/${item.id}" class="col-12 col-md-6 mb-4" id="modal-detail-result">
                                <div class="card shadow-lg rounded-3 border-0 h-100 custom-hover">
                                    <div class="card-body p-3 d-flex flex-column justify-content-between h-100">
                                        <div>
                                            <!-- Tiêu đề lượt làm -->
                                            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                                                <h6 class="fw-bold text-dark mb-0">
                                                    <i class="icofont-pencil-alt-2 me-2 text-secondary"></i>Lần ${index + 1}
                                                </h6>
                                                <span class="badge bg-primary px-3 py-2 rounded-pill">
                                                    <i class="icofont-star me-1"></i>${item.score} Điểm
                                                </span>
                                            </div>

                                            <!-- Thời gian nộp -->
                                            <p class="mb-2 text-muted">
                                                <i class="icofont-clock-time me-2 text-primary"></i>
                                                <strong>Thời gian nộp:</strong> ${item.submitted_at}
                                            </p>

                                            <!-- Số câu đúng -->
                                            <p class="mb-0 text-muted">
                                                <i class="icofont-check-circled me-2 text-success"></i>
                                                <strong>Số câu đúng:</strong> ${item.total_correct}/${item.total_questions}
                                            </p>
                                        </div>

                                    </div>
                                </div>
                            </a>
                        `;
                    });
                    $('#body-modal-result').html(html);
                    $('.quiz-action-btn-result').attr('href',
                        `/student/quizz/start/${response.quizAttempts[0].quiz_id}`);

                    if (response.quiz.is_public == 0) {
                        if (response.status == false) {
                            $('.quiz-action-btn-result').hide();
                        }
                    } else {
                        $('.quiz-action-btn-result').show();
                    }

                    $('#resultModal').modal('show');

                    $('#simple-preloader').fadeOut();
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        })


        //hàm định dạng ngày
        function formatDateTime(datetimeStr) {
            if (!datetimeStr) return '';

            // Chuyển dấu cách thành 'T' để Date parse chính xác trong mọi trình duyệt
            const date = new Date(datetimeStr.replace(' ', 'T'));

            const day = date.getDate().toString().padStart(2, '0');
            const month = (date.getMonth() + 1).toString().padStart(2, '0');
            const year = date.getFullYear();

            const hours = date.getHours().toString().padStart(2, '0');
            const minutes = date.getMinutes().toString().padStart(2, '0');

            return `${day}/${month}/${year} ${hours}:${minutes}`;
        }

        $(document).on('click', '#modal-detail-result', function(e) {
            e.preventDefault();
              $('#simple-preloader').css('display', 'flex');
            const url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    $('#resultModalDetail').remove();
                    $('body').append(response);

                    // Hiển thị modal
                    const modal = new bootstrap.Modal(document.getElementById('resultModalDetail'));
                    modal.show();
                    $('#simple-preloader').fadeOut();
                }
            });
        });





        // hàm kiểm tra mã code quizz
        $('.btn-join').on('click', function() {
            const code = $('.quiz-code-input').val().trim().toUpperCase();
            if (!code) {
                Swal.fire({
                    title: 'Lỗi!',
                    text: 'Vui lòng nhập mã quizz!',
                    icon: 'warning',
                    confirmButtonClass: 'btn btn-success w-xs mt-2',
                    buttonsStyling: true
                });
                return;
            }
            $.ajax({
                url: `/student/check-access-code/${code}`, // Route xử lý ở server
                method: 'GET',
                success: function(response) {
                    console.log(response);
                    const quiz = response;
                    const createdAt = new Date(quiz.created_at);
                    const createdDate = createdAt.toLocaleDateString('vi-VN');
                    if(!quiz){
                        Swal.fire({
                            title: 'Lỗi!',
                            text: 'Mã Quiz không hợp lệ. Vui lòng nhập lại mã!',
                            icon: 'error',
                            confirmButtonClass: 'btn btn-success w-xs mt-2',
                            buttonsStyling: true
                        });
                        return;
                    }
                    // Gắn nội dung động
                    $("#quiz-info-body").html(`
                        <div class="row mb-3">
                            <div class="col-12 text-end">
                                <small class="text-muted fst-italic d-block d-sm-inline">
                                    Ngày tạo: <i class="icofont-calendar me-1"></i> ${createdDate}
                                </small>
                            </div>
                        </div>

                       <h4 class="fw-semibold mb-3 text-primary
                                fs-6 fs-sm-5 fs-md-4 fs-lg-3 fs-xl-2">
                            <i class="icofont-paper me-2"></i>
                            Tiêu đề Quiz: <span class="text-dark">${quiz.title}</span>
                        </h4>

                        <p class="mb-4 text-muted fst-italic">
                            <i class="icofont-info-circle me-1"></i> Mô tả: ${quiz.description ?? 'Không có mô tả.'}
                        </p>

                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex align-items-start">
                                <i class="icofont-key fs-6 me-2 text-secondary"></i>
                                <div  class="d-flex align-items-center gap-1">
                                    <strong>Mã truy cập:</strong><br>
                                    <span class="text-uppercase text-dark">${quiz.access_code}</span>
                                </div>
                            </div>

                            <div class="d-flex align-items-start">
                                <i class="icofont-clock-time fs-6 me-2 text-secondary"></i>
                                <div  class="d-flex align-items-center gap-1">
                                    <strong>Thời lượng:</strong><br>
                                    <span>${quiz.duration_minutes} phút</span>
                                </div>
                            </div>

                            <div class="d-flex align-items-start">
                                <i class="icofont-question-circle fs-6 me-2 text-secondary"></i>
                                <div class="d-flex align-items-center gap-1">
                                    <strong>Tổng số câu hỏi:</strong><br>
                                    <span>${quiz.total_questions}</span>
                                </div>
                            </div>

                            <div class="d-flex align-items-start">
                                <i class="icofont-teacher fs-6 me-2 text-secondary"></i>
                                <div class="d-flex align-items-center gap-1">
                                    <strong>Giáo viên:</strong><br>
                                    <span>${quiz.creator.name ?? 'Không rõ'}</span>
                                </div>
                            </div>
                        </div>

                    `);

                    // Gắn link bắt đầu làm bài
                    $('.quiz-action-btn-search').attr('href', `/student/quizz/start/${quiz.id}`);

                    // Hiển thị modal
                    const modal = new bootstrap.Modal(document.getElementById('quizInfoModal'));
                    modal.show();
                },

                error: function(xhr) {
                    Swal.fire({
                        title: 'Lỗi!',
                        text: 'Mã Quiz không hợp lệ. Vui lòng nhập lại mã!',
                        icon: 'error',
                        confirmButtonClass: 'btn btn-success w-xs mt-2',
                        buttonsStyling: true
                    });
                }
            });
        });
    </script>
@endpush
