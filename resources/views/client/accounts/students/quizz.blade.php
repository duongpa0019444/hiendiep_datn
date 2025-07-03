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
                {{-- <!-- Recent Quiz Codes -->
                <div class="mt-4">
                    <h5 class="mb-3">📝 Mã quiz gần đây</h5>
                    <div class="row g-2">
                        <div class="col-auto">
                            <span class="badge bg-light text-dark p-2 border">MATH001</span>
                        </div>
                        <div class="col-auto">
                            <span class="badge bg-light text-dark p-2 border">ENG202</span>
                        </div>
                        <div class="col-auto">
                            <span class="badge bg-light text-dark p-2 border">PHY101</span>
                        </div>
                    </div>
                </div> --}}
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
                                                <a href="{{ route('student.quizzes.start') }}"
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
<!-- Modal -->
<div class="modal fade" id="resultModal" tabindex="-1" aria-labelledby="resultModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="resultModalLabel">Kết quả các lần làm bài</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row" id="body-modal-result">

                </div>
            </div>


        </div>
    </div>
</div>
<!-- Modal Thông Tin Quiz -->
<div class="modal fade" id="quizInfoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thông tin Quiz</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="quiz-info-body">
                <!-- Nội dung quiz sẽ được thêm bằng JS -->
            </div>
            <div class="modal-footer">
                <a href="#" id="start-quiz-btn" class="btn btn-primary">
                    <i class="fas fa-play me-1"></i> Bắt đầu làm
                </a>
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

                function updateScrollButtonsAssigned() {
                    const scrollLeft = scrollWrapperAssigned.scrollLeft;
                    const scrollWidth = scrollWrapperAssigned.scrollWidth;
                    const clientWidth = scrollWrapperAssigned.clientWidth;
                    scrollLeftBtnAssigned.disabled = scrollLeft <= 0;
                    scrollRightBtnAssigned.disabled = scrollLeft >= scrollWidth - clientWidth - 10;
                }

                // Lắng nghe các sự kiện cuộn
                scrollWrapperAssigned.addEventListener('scroll', updateScrollButtonsAssigned);

                // Trạng thái nút ban đầu
                updateScrollButtonsAssigned();

                //Xử lý thay đổi kích thước
                window.addEventListener('resize', updateScrollButtonsAssigned);

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
                const scrollAmount = 340; // Width of one card + gap

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

                // Update button states based on scroll position
                function updateScrollButtonsCompleted() {
                    const scrollLeft = scrollWrapperCompleted.scrollLeft;
                    const scrollWidth = scrollWrapperCompleted.scrollWidth;
                    const clientWidth = scrollWrapperCompleted.clientWidth;
                    scrollLeftBtnCompleted.disabled = scrollLeft <= 0;
                    scrollRightBtnCompleted.disabled = scrollLeft >= scrollWidth - clientWidth - 10;
                }

                // Listen for scroll events
                scrollWrapperCompleted.addEventListener('scroll', updateScrollButtonsCompleted);

                // Initial button state
                updateScrollButtonsCompleted();

                // Handle window resize
                window.addEventListener('resize', updateScrollButtonsCompleted);

                // Touch/Mouse drag scrolling
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

            // Search functionality
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

            // Join quiz button functionality
            const joinBtn = document.querySelector('.btn-join');
            if (joinBtn) {
                joinBtn.addEventListener('click', function() {
                    const codeInput = document.querySelector('.quiz-code-input');
                    const code = codeInput ? codeInput.value.trim() : '';

                    if (code) {
                        this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang tham gia...';
                        this.disabled = true;
                        setTimeout(() => {
                            this.innerHTML = '<i class="fas fa-sign-in-alt me-2"></i>Tham gia Quiz';
                            this.disabled = false;
                            alert('Tham gia quiz với mã: ' + code);
                        }, 2000);
                    } else {
                        alert('Vui lòng nhập mã quiz!');
                        codeInput.focus();
                    }
                });
            }
        });






        $(document).on('click', '.quiz-result-completed', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    console.log(response);

                    let html = '';
                    response.forEach((item, index) => {
                        html += `
                            <a href="/student/quizz/${item.quiz_id}/show-result/${item.id}" class="col-12 col-md-6 mb-4" id="modal-detail-result">
                                <div class="card shadow-lg rounded-3 border-0 h-100 custom-hover">
                                    <div class="card-body p-3 d-flex flex-column justify-content-between h-100">
                                        <div>
                                            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                                                <h6 class="fw-bold text-dark mb-0">
                                                    <i class="bi bi-journal-text me-2"></i>Lần ${index + 1}
                                                </h6>
                                                <span class="badge bg-primary px-3 py-2 rounded-pill">
                                                    <i class="bi bi-star-fill me-1"></i>${item.score} Điểm
                                                </span>
                                            </div>

                                            <p class="mb-2 text-muted">
                                                <i class="bi bi-clock me-2 text-primary"></i>
                                                <strong>Thời gian nộp:</strong> ${item.submitted_at}
                                            </p>
                                            <p class="mb-0 text-muted">
                                                <i class="bi bi-check-circle-fill me-2 text-success"></i>
                                                <strong>Số câu đúng:</strong> ${item.total_correct}/${item.total_questions}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        `;
                    });
                    $('#body-modal-result').html(html);
                    $('#resultModal').modal('show');
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
                }
            });
        });





        // hàm kiểm tra mã code quizz
        $('.btn-join').on('click', function () {
            const code = $('.quiz-code-input').val().trim().toUpperCase();

            if (!code) {
                return alert('Vui lòng nhập mã quiz!');
            }

            $.ajax({
                url: `/student/check-access-code/${code}`, // Route xử lý ở server
                method: 'GET',
                success: function (response) {
                    // Gắn link bắt đầu quiz
                    $('#start-quiz-btn').attr('href', `/quiz/start/${response.quiz.id}`);

                    // Hiển thị modal
                    const modal = new bootstrap.Modal(document.getElementById('quizInfoModal'));
                    modal.show();
                },
                error: function (xhr) {
                    alert(xhr.responseJSON?.message ?? 'Mã quiz không hợp lệ!');
                }
            });
        });



    </script>
@endpush
