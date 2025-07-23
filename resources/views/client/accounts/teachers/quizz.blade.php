@extends('client.accounts.information')

@section('content-information')
    <div id="quizzes" class="content-section">
        <!-- Header Section -->
        <div class="quiz-container">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <h4 class="mb-2 text-white">Quản lý Quiz của bạn</h4>
                    <p class="mb-0 opacity-75 text-light">Thêm, chỉnh sửa quiz ôn tập cho học sinh!</p>
                </div>
                <div class="col-md-5 text-end">
                    <div class="d-flex justify-content-end align-items-center">
                        <div class="me-3">
                            <small class="opacity-75">Tất cả</small>
                            <div class="h4 mb-0">{{ $quizzesAll->count() ?? 0 }}</div>
                        </div>
                        <div class="me-3">
                            <small class="opacity-75">Xuất bản</small>
                            <div class="h4 mb-0">{{ $quizzesPublished->count() ?? 0 }}</div>
                        </div>
                        <div class="me-3">
                            <small class="opacity-75">Nháp</small>
                            <div class="h4 mb-0">{{ $quizzesDraft->count() ?? 0 }}</div>
                        </div>
                        <div>
                            <small class="opacity-75">Đã xóa</small>
                            <div class="h4 mb-0">{{ $quizzesTrashed->count() ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <ul class="nav quiz-tabs" id="quizTabs">
            <li class="nav-item">
                <a class="nav-link active" href="#quiz-all" data-bs-toggle="tab">
                    <i class="icofont-keyboard me-2"></i>Tất cả quiz
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#published" data-bs-toggle="tab">
                    <i class="icofont-tasks-alt me-2"></i>Đã xuất bản
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#draft" data-bs-toggle="tab">
                    <i class="icofont-edit me-2"></i>Nháp
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#trashed" data-bs-toggle="tab">
                    <i class="icofont-ui-delete me-2"></i>Thùng rác
                </a>
            </li>


        </ul>


        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Tab 1: All Quizzes -->
            <div class="tab-pane fade show active" id="quiz-all">
                <div class="">
                    <div class="d-flex justify-content-between align-items-center mb-1 gap-1">
                        <div class="col-md-8">
                            <div class="search-box position-relative">
                                <input type="text" class="form-control" placeholder="Tìm kiếm quiz...">
                                <i class="icofont-search position-absolute top-50 "></i>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <button type="button" class="btn btn-add-quiz" id="btn-add-quizz"
                                data-bs-target="#modal-add-quiz">
                                <i class="icofont-plus me-1"></i><span class="btn-label">Thêm</span>
                            </button>
                        </div>
                    </div>

                    <div class="quiz-scroll-container">
                        <button class="scroll-nav-btn left" id="scrollLeftAll">
                            <i class="icofont-rounded-left"></i>
                        </button>
                        <button class="scroll-nav-btn right" id="scrollRightAll">
                            <i class="icofont-rounded-right"></i>
                        </button>
                        <div class="quiz-cards-wrapper" id="quizCardsWrapperAll">
                            <div class="quiz-cards-row px-1" id="quiz-card-all">
                                @foreach ($quizzesAll as $quiz)
                                    <div class="quiz-card-item quiz-item-{{ $quiz->id }}">
                                        <div class="card quiz-card h-100">
                                            <div class="card-body">
                                                <span
                                                    class="status-badge
                                                    {{ $quiz->deleted_at ? 'bg-danger' : ($quiz->status == 'published' ? 'bg-success' : 'bg-warning') }}
                                                    text-white">
                                                    {{ $quiz->deleted_at ? 'Đã xóa' : ($quiz->status == 'published' ? 'Đã xuất bản' : 'Nháp') }}
                                                </span>
                                                <div class="dropdown text-end action-quizz-teacher  col-1">
                                                    <button class="btn btn-sx btn-light border-0" type="button"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="icofont-navigation-menu"></i>
                                                    </button>

                                                    <div class="dropdown-menu quiz-footer row">
                                                        <div class="col-12 dropdown-item">
                                                            <a href="{{ route('teacher.quizzes.detail', $quiz->id) }}"
                                                                class="btn btn-outline-info w-100 quiz-action-btn">
                                                                <i class="icofont-eye me-1"></i> Xem chi tiết
                                                            </a>
                                                        </div>
                                                        <div class="col-12 dropdown-item">
                                                            <button data-quiz-id="{{ $quiz->id }}"
                                                                class="btn btn-outline-primary-quiz quiz-action-btn btn-edit-quiz">
                                                                <i class="icofont-edit me-1"></i> Sửa
                                                            </button>
                                                        </div>
                                                        <div class="col-12 dropdown-item">
                                                            <form action="{{ route('teacher.quizzes.delete', $quiz->id) }}"
                                                                method="POST" class="w-100 btn-delete-quiz-form">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" data-quiz-id="{{ $quiz->id }}"
                                                                    class="btn btn-outline-danger border w-100 quiz-action-btn btn-delete-quiz">
                                                                    <i class="icofont-ui-delete me-1"></i> Xóa
                                                                </button>
                                                            </form>

                                                        </div>

                                                        <div class="col-12 dropdown-item">
                                                            <a href="{{ route('teacher.quizzes.results', $quiz->id) }}"
                                                                class="btn btn-success quiz-action-btn w-100 view-classes">
                                                                <i class="icofont-chart-line-alt me-1"></i> Xem kết quả
                                                            </a>
                                                        </div>

                                                    </div>
                                                </div>


                                                <h5 class="quiz-title">{{ $quiz->title }}</h5>
                                                <div>
                                                    <div class="question-count">
                                                        <i class="icofont-question-circle me-1"></i>
                                                        {{ $quiz->total_questions }} câu hỏi
                                                    </div>
                                                    <div class="question-count">
                                                        <i class="icofont-clock-time me-1"></i>
                                                        {{ $quiz->duration_minutes }} Phút
                                                    </div>
                                                </div>
                                                <div class="quiz-meta">
                                                    <i class="icofont-teacher"></i> Giáo viên: {{ $quiz->creator_name }}
                                                </div>
                                                <div class="quiz-meta mb-2">
                                                    <i class="icofont-clock-time"></i> Cập nhật:
                                                    {{ \Carbon\Carbon::parse($quiz->updated_at)->format('d/m/Y') }}
                                                </div>



                                            </div>
                                        </div>
                                    </div>
                                @endforeach


                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 2: Published Quizzes -->
            <div class="tab-pane fade" id="published">
                <div class="">
                    <div class="search-box mb-3">
                        <input type="text" class="form-control" placeholder="Tìm kiếm quiz đã xuất bản...">
                        <i class="icofont-search position-absolute top-50 "></i>
                    </div>
                    <div class="quiz-scroll-container">
                        <button class="scroll-nav-btn left" id="scrollLeftPublished">
                            <i class="icofont-rounded-left"></i>
                        </button>
                        <button class="scroll-nav-btn right" id="scrollRightPublished">
                            <i class="icofont-rounded-right"></i>
                        </button>
                        <div class="quiz-cards-wrapper">
                            <div class="quiz-cards-row px-1" id="quizCardsWrapperPublished">
                                @foreach ($quizzesPublished as $quiz)
                                    <div class="quiz-card-item quiz-item-{{ $quiz->id }}">
                                        <div class="card quiz-card h-100">
                                            <div class="card-body">
                                                <span class="status-badge bg-success text-white">Đã xuất bản</span>

                                                <div class="dropdown text-end action-quizz-teacher  col-1">
                                                    <button class="btn btn-sx btn-light border-0" type="button"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="icofont-navigation-menu"></i>
                                                    </button>

                                                    <div class="dropdown-menu quiz-footer row">

                                                        <div class="col-12 dropdown-item">
                                                            <a href="{{ route('teacher.quizzes.detail', $quiz->id) }}"
                                                                class="btn btn-outline-info w-100 quiz-action-btn">
                                                                <i class="icofont-eye me-1"></i> Xem chi tiết
                                                            </a>
                                                        </div>
                                                        <div class="col-12 dropdown-item">
                                                            <a href="#"
                                                                class="btn btn-outline-primary-quiz quiz-action-btn">
                                                                <i class="icofont-edit me-1"></i> Sửa
                                                            </a>
                                                        </div>
                                                        <div class="col-12 dropdown-item">
                                                            <form
                                                                action="{{ route('teacher.quizzes.delete', $quiz->id) }}"
                                                                method="POST" class="w-100 btn-delete--quizform">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" data-quiz-id="{{ $quiz->id }}"
                                                                    class="btn btn-outline-danger border w-100 quiz-action-btn btn-delete-quiz">
                                                                    <i class="icofont-ui-delete me-1"></i> Xóa
                                                                </button>
                                                            </form>
                                                        </div>
                                                        <div class="col-12 dropdown-item">
                                                            <a href="#"
                                                                class="btn btn-success quiz-action-btn w-100">
                                                                <i class="icofont-chart-line-alt me-1"></i> Xem kết quả
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <h5 class="quiz-title">{{ $quiz->title }}</h5>
                                                <div>
                                                    <div class="question-count">
                                                        <i class="icofont-question-circle me-1"></i>
                                                        {{ $quiz->total_questions }} câu hỏi
                                                    </div>
                                                    <div class="question-count">
                                                        <i class="icofont-clock-time me-1"></i>
                                                        {{ $quiz->duration_minutes }} Phút
                                                    </div>
                                                </div>
                                                <div class="quiz-meta">
                                                    <i class="icofont-teacher"></i> Giáo viên: {{ $quiz->creator_name }}
                                                </div>
                                                <div class="quiz-meta mb-3">
                                                    <i class="icofont-clock-time"></i> Cập nhật:
                                                    {{ \Carbon\Carbon::parse($quiz->updated_at)->format('d/m/Y') }}
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 3: Draft Quizzes -->
            <div class="tab-pane fade" id="draft">
                <div class="">
                    <div class="search-box mb-3">
                        <input type="text" class="form-control" placeholder="Tìm kiếm quiz nháp...">
                        <i class="icofont-search position-absolute top-50 "></i>
                    </div>
                    <div class="quiz-scroll-container">
                        <button class="scroll-nav-btn left" id="scrollLeftDraft">
                            <i class="icofont-rounded-left"></i>
                        </button>
                        <button class="scroll-nav-btn right" id="scrollRightDraft">
                            <i class="icofont-rounded-right"></i>
                        </button>
                        <div class="quiz-cards-wrapper" >
                            <div class="quiz-cards-row px-1" id="quizCardsWrapperDraft">
                                @foreach ($quizzesDraft as $quiz)
                                    <div class="quiz-card-item quiz-item-{{ $quiz->id }}">
                                        <div class="card quiz-card h-100">
                                            <div class="card-body">
                                                <span class="status-badge bg-warning text-white">Nháp</span>

                                                <div class="dropdown text-end action-quizz-teacher  col-1">
                                                    <button class="btn btn-sx btn-light border-0" type="button"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="icofont-navigation-menu"></i>
                                                    </button>

                                                    <div class="dropdown-menu quiz-footer row">

                                                        <div class="col-12 dropdown-item">
                                                            <a href="{{ route('teacher.quizzes.detail', $quiz->id) }}"
                                                                class="btn btn-outline-info w-100 quiz-action-btn">
                                                                <i class="icofont-eye me-1"></i> Xem chi tiết
                                                            </a>
                                                        </div>
                                                        <div class="col-12 dropdown-item">
                                                            <a href="#"
                                                                class="btn btn-outline-primary-quiz quiz-action-btn">
                                                                <i class="icofont-edit me-1"></i> Sửa
                                                            </a>
                                                        </div>
                                                        <div class="col-12 dropdown-item">
                                                            <form
                                                                action="{{ route('teacher.quizzes.delete', $quiz->id) }}"
                                                                method="POST" class="w-100 btn-delete-quiz-form">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" data-quiz-id="{{ $quiz->id }}"
                                                                    class="btn btn-outline-danger border w-100 quiz-action-btn">
                                                                    <i class="icofont-ui-delete me-1"></i> Xóa
                                                                </button>
                                                            </form>
                                                        </div>

                                                    </div>
                                                </div>
                                                <h5 class="quiz-title">{{ $quiz->title }}</h5>
                                                <div>
                                                    <div class="question-count">
                                                        <i class="icofont-question-circle me-1"></i>
                                                        {{ $quiz->total_questions }} câu hỏi
                                                    </div>
                                                    <div class="question-count">
                                                        <i class="icofont-clock-time me-1"></i>
                                                        {{ $quiz->duration_minutes }} Phút
                                                    </div>
                                                </div>
                                                <div class="quiz-meta">
                                                    <i class="icofont-teacher"></i> Giáo viên: {{ $quiz->creator_name }}
                                                </div>
                                                <div class="quiz-meta mb-3">
                                                    <i class="icofont-clock-time"></i> Cập nhật:
                                                    {{ \Carbon\Carbon::parse($quiz->updated_at)->format('d/m/Y') }}
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 4: Trashed Quizzes -->
            <div class="tab-pane fade" id="trashed">
                <div class="">
                    <div class="search-box mb-3">
                        <input type="text" class="form-control" placeholder="Tìm kiếm quiz đã xóa...">
                        <i class="icofont-search position-absolute top-50 "></i>
                    </div>
                    <div class="quiz-scroll-container">
                        <button class="scroll-nav-btn left" id="scrollLeftTrashed">
                            <i class="icofont-rounded-left"></i>
                        </button>
                        <button class="scroll-nav-btn right" id="scrollRightTrashed">
                            <i class="icofont-rounded-right"></i>
                        </button>
                        <div class="quiz-cards-wrapper" id="quizCardsWrapperTrashed">
                            <div class="quiz-cards-row px-1" id="quiz-card-trashed">
                                @foreach ($quizzesTrashed as $quiz)
                                    <div class="quiz-card-item quiz-item-{{ $quiz->id }}">
                                        <div class="card quiz-card h-100">
                                            <div class="card-body">
                                                <span class="status-badge bg-danger text-white">Đã xóa</span>

                                                <div class="dropdown text-end action-quizz-teacher  col-1">
                                                    <button class="btn btn-sx btn-light border-0" type="button"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="icofont-navigation-menu"></i>
                                                    </button>

                                                    <div class="dropdown-menu quiz-footer row">

                                                        <div class="col-12 dropdown-item">
                                                            <a href="{{ route('teacher.quizzes.detail', $quiz->id) }}"
                                                                class="btn btn-outline-info w-100 quiz-action-btn">
                                                                <i class="icofont-eye me-1"></i> Xem chi tiết
                                                            </a>
                                                        </div>
                                                        <div class="col-12 dropdown-item">
                                                            <button
                                                                class="btn btn-outline-success w-100 quiz-action-btn restore-quiz"
                                                                data-id="{{ $quiz->id }}">
                                                                <i class="icofont-undo me-1"></i> Khôi phục
                                                            </button>
                                                        </div>
                                                        <div class="col-12 dropdown-item">
                                                            <form
                                                                action="{{ route('teacher.quizzes.forceDelete', $quiz->id) }}"
                                                                method="POST" class="w-100 btn-delete-quiz-form">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="button"
                                                                    class="btn btn-outline-danger w-100 quiz-action-btn delete-permanent"
                                                                    data-id="{{ $quiz->id }}">
                                                                    <i class="icofont-trash me-1"></i> Xóa vĩnh viễn
                                                                </button>
                                                            </form>
                                                        </div>

                                                    </div>
                                                </div>
                                                <h5 class="quiz-title">{{ $quiz->title }}</h5>
                                                <div>
                                                    <div class="question-count">
                                                        <i class="icofont-question-circle me-1"></i>
                                                        {{ $quiz->total_questions }} câu hỏi
                                                    </div>
                                                    <div class="question-count">
                                                        <i class="icofont-clock-time me-1"></i>
                                                        {{ $quiz->duration_minutes }} Phút
                                                    </div>
                                                </div>
                                                <div class="quiz-meta">
                                                    <i class="icofont-teacher"></i> Giáo viên: {{ $quiz->creator_name }}
                                                </div>
                                                <div class="quiz-meta mb-3">
                                                    <i class="icofont-clock-time"></i> Xóa:
                                                    {{ \Carbon\Carbon::parse($quiz->deleted_at)->format('d/m/Y') }}
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        const csrfToken = $('meta[name="csrf-token"]').attr('content');


        document.addEventListener('DOMContentLoaded', function() {
            // Scroll functionality for all tabs
            const tabs = ['All', 'Published', 'Draft', 'Trashed'];
            tabs.forEach(tab => {
                const scrollWrapper = document.getElementById(`quizCardsWrapper${tab}`);
                const scrollLeftBtn = document.getElementById(`scrollLeft${tab}`);
                const scrollRightBtn = document.getElementById(`scrollRight${tab}`);

                if (scrollWrapper && scrollLeftBtn && scrollRightBtn) {
                    const scrollAmount = 340;

                    scrollLeftBtn.addEventListener('click', function() {
                        scrollWrapper.scrollBy({
                            left: -scrollAmount,
                            behavior: 'smooth'
                        });
                    });

                    scrollRightBtn.addEventListener('click', function() {
                        scrollWrapper.scrollBy({
                            left: scrollAmount,
                            behavior: 'smooth'
                        });
                    });

                    let isDown = false;
                    let startX;
                    let scrollLeftStart;

                    scrollWrapper.addEventListener('mousedown', (e) => {
                        isDown = true;
                        scrollWrapper.style.cursor = 'grabbing';
                        startX = e.pageX - scrollWrapper.offsetLeft;
                        scrollLeftStart = scrollWrapper.scrollLeft;
                    });

                    scrollWrapper.addEventListener('mouseleave', () => {
                        isDown = false;
                        scrollWrapper.style.cursor = 'grab';
                    });

                    scrollWrapper.addEventListener('mouseup', () => {
                        isDown = false;
                        scrollWrapper.style.cursor = 'grab';
                    });

                    scrollWrapper.addEventListener('mousemove', (e) => {
                        if (!isDown) return;
                        e.preventDefault();
                        const x = e.pageX - scrollWrapper.offsetLeft;
                        const walk = (x - startX) * 2;
                        scrollWrapper.scrollLeft = scrollLeftStart - walk;
                    });

                    scrollWrapper.addEventListener('touchstart', (e) => {
                        startX = e.touches[0].pageX - scrollWrapper.offsetLeft;
                        scrollLeftStart = scrollWrapper.scrollLeft;
                    });

                    scrollWrapper.addEventListener('touchmove', (e) => {
                        if (!startX) return;
                        const x = e.touches[0].pageX - scrollWrapper.offsetLeft;
                        const walk = (x - startX) * 2;
                        scrollWrapper.scrollLeft = scrollLeftStart - walk;
                    });
                }
            });


            //Tìm kiếm quizz all
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


            // Button animations
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

            form.attr('action', '{{ route('teacher.quizzes.store') }}');
            formMethod.val('POST');
            modalTitle.text('Thêm bài quiz');
            quizIdInput.val('');
            form[0].reset();
            $('#is_public_0').prop('checked', true); // chọn mặc định là private
            $('#course_id').val('').prop('disabled', false);
            toggleFields();
            errorContainer.hide().empty();
        });

        //sử lý chọn lớp và khóa khi thêm quizz
        $(document).ready(function() {
            $('#class_id').on('change', function() {
                const courseId = $(this).find('option:selected').data('course');
                if (courseId) {
                    console.log(courseId);
                    $('#course_field').hide();

                } else {
                    $('#course_field').show();
                }
            });

            $('#course_id').on('change', function() {
                const courseId = $(this).val();
                if (courseId) {
                    console.log(courseId);
                    $('#class_field').hide();

                } else {
                    $('#class_field').show();
                }
            });
        });

        // Mở modal Sửa quiz
        $(document).on('click', '.btn-edit-quiz', function() {
            $('#ed-preloader').css('display', 'flex');
            $("#modal-add-quiz").modal("show");
            const quizId = $(this).data('quiz-id');
            quizIdInput.val(quizId);
            form.attr('action', `/teacher/quizzes/${quizId}/update`);
            formMethod.val('PUT');
            modalTitle.text('Sửa bài quiz');
            errorContainer.hide().empty();

            $.ajax({
                url: `/teacher/quizzes/${quizId}/detail`,
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
                    $('#ed-preloader').fadeOut();
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
            // console.log(form.serialize());
            errorContainer.hide().empty();

            console.log(method)
            $.ajax({
                url: actionUrl,
                method: method,
                data: form.serialize(),
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        window.location.href = `/teacher/quizzes/${response.quiz.id}/detail`;
                    }
                    if (response.action === 'edit') {
                        Swal.fire({
                                title: 'Sửa thành công!',
                                text: 'Bài quiz đã được sửa thành công.',
                                icon: 'success',
                                confirmButtonText: 'OK',
                                customClass: {
                                    confirmButton: 'btn btn-success w-xs mt-2'
                                },
                                buttonsStyling: false
                            })
                            .then(() => {
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
                                errorHtml +=
                                    `<li><i class="icofont-warning-alt text-danger me-1"></i> ${msg}</li>`;

                            });
                        }
                        errorHtml += '</ul></div>';
                        errorContainer.html(errorHtml).show(); // đúng cú pháp jQuery
                    } else {
                        Swal.fire({
                            title: 'Lỗi!',
                            text: 'Đã có lỗi xảy ra. Vui lòng thử lại.',
                            icon: 'error',
                            customClass: {
                                confirmButton: 'btn btn-danger w-xs mt-2'
                            },
                            buttonsStyling: false
                        });
                    }
                }
            });
        });




        // Delete quiz
        $(document).on('click', '.btn-delete-quiz', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            const actionUrl = form.attr('action');
            const quizId = $(this).data('quiz-id');
            console.log(quizId);
            console.log($(`.quiz-item-${ quizId }`));

            Swal.fire({
                title: 'Bạn có chắc chắn?',
                text: "Bài quiz sẽ được đưa vào thùng rác!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Vâng, xóa nó!',
                cancelButtonText: 'Không, hủy!',
                customClass: {
                    confirmButton: 'btn btn-danger w-xs me-2 mt-2',
                    cancelButton: 'btn btn-success w-xs mt-2'
                },
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
                        success: function(response) {
                            Swal.fire('Đã xóa!', 'Quiz đã được xóa thành công.',
                                'success');
                            form.closest('.quiz-card-item').remove();
                            $(`.quiz-item-${ quizId }`).remove();

                            console.log(response);
                            $('#quiz-card-trashed').prepend(renderDeletedQuizItem(response))

                        },
                        error: function() {
                            Swal.fire('Lỗi!', 'Không thể xóa quiz.', 'error');
                        }
                    });
                }
            });
        });

        // Restore quiz
        $(document).on('click', '.restore-quiz', function() {
            const quizId = $(this).data('id');
            Swal.fire({
                title: 'Xác nhận khôi phục',
                text: 'Bạn có chắc muốn khôi phục quiz này?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonClass: 'btn btn-success w-xs mt-2',
                cancelButtonClass: 'btn btn-secondary w-xs mt-2',
                buttonsStyling: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/teacher/quizzes/${quizId}/restore`,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Thành công!',
                                text: 'Quiz đã được khôi phục.',
                                icon: 'success',
                                confirmButtonClass: 'btn btn-success w-xs mt-2'
                            })
                            $(`.quiz-item-${ quizId }`).remove();
                            $('#quiz-card-all').prepend(renderQuizCardItem(response))
                            if(response.status === 'published') {
                                $('#quizCardsWrapperPublished').prepend(renderQuizCardItem(response));
                            } else {
                                $('#quizCardsWrapperDraft').prepend(renderQuizCardItem(response));
                            }
                        },
                        error: function() {
                            Swal.fire({
                                title: 'Lỗi!',
                                text: 'Không thể khôi phục quiz.',
                                icon: 'error',
                                confirmButtonClass: 'btn btn-success w-xs mt-2'
                            });
                        }
                    });
                }
            });
        });

        // Delete vĩnh viễn
        $(document).on('click', '.delete-permanent', function(e) {
            const quizId = $(this).data('id');
            const form = $(this).closest('form');
            const actionUrl = form.attr('action');
            Swal.fire({
                title: 'Xác nhận xóa vĩnh viễn',
                text: 'Quiz này sẽ bị xóa vĩnh viễn và không thể khôi phục. Bạn có chắc?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonClass: 'btn btn-danger w-xs mt-2',
                cancelButtonClass: 'btn btn-secondary w-xs mt-2',
                buttonsStyling: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: actionUrl,
                        method: 'POST',
                        data: form.serialize(),
                        success: function(response) {
                            Swal.fire({
                                title: 'Thành công!',
                                text: 'Quiz đã được xóa vĩnh viễn.',
                                icon: 'success',
                                confirmButtonClass: 'btn btn-success w-xs mt-2'
                            });
                            $(`.quiz-item-${ quizId }`).remove();
                        },
                        error: function() {
                            Swal.fire({
                                title: 'Lỗi!',
                                text: 'Không thể xóa vĩnh viễn quiz.',
                                icon: 'error',
                                confirmButtonClass: 'btn btn-success w-xs mt-2'
                            });
                        }
                    });
                }
            });
        });



        //Hàm render bản ghi xóa mềm
        function renderDeletedQuizItem(quiz) {
            return `
                <div class="quiz-card-item quiz-item-${quiz.id}">
                    <div class="card quiz-card h-100">
                        <div class="card-body">
                            <span class="status-badge bg-danger text-white">Đã xóa</span>

                            <div class="dropdown text-end action-quizz-teacher col-1">
                                <button class="btn btn-sx btn-light border-0" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="icofont-navigation-menu"></i>
                                </button>

                                <div class="dropdown-menu quiz-footer row">
                                    <div class="col-12 dropdown-item">
                                        <a href="/teacher/quizzes/${quiz.id}/detail" class="btn btn-outline-info w-100 quiz-action-btn">
                                            <i class="icofont-eye me-1"></i> Xem chi tiết
                                        </a>
                                    </div>
                                    <div class="col-12 dropdown-item">
                                        <button class="btn btn-outline-success w-100 quiz-action-btn restore-quiz"
                                            data-id="${quiz.id}">
                                            <i class="icofont-undo me-1"></i> Khôi phục
                                        </button>
                                    </div>
                                    <div class="col-12 dropdown-item">
                                        <form
                                            action="/teacher/quizzes/${quiz.id}/force-delete"
                                            method="POST" class="w-100 btn-delete-quiz-form">
                                            <input type="hidden" name="_token" value="${csrfToken}">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="button"  data-quiz-id="${quiz.id}"
                                                class="btn btn-outline-danger w-100 quiz-action-btn delete-permanent"
                                                data-id="${quiz.id}">
                                                <i class="icofont-trash me-1"></i> Xóa vĩnh viễn
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <h5 class="quiz-title">${quiz.title}</h5>
                           <div>
                                <div class="question-count">
                                    <i class="icofont-question-circle me-1"></i>
                                    ${quiz.total_questions??0 } câu hỏi
                                </div>
                                    <div class="question-count">
                                    <i class="icofont-clock-time me-1"></i>
                                    ${quiz.duration_minutes } Phút
                                </div>
                            </div>
                            <div class="quiz-meta">
                                <i class="icofont-teacher"></i> Giáo viên: ${quiz.creator.name}
                            </div>
                            <div class="quiz-meta mb-3">
                                <i class="icofont-clock-time"></i> Xóa: ${formatDate(quiz.deleted_at)}
                            </div>
                        </div>
                    </div>
                </div>`;
        }

        //Hàm render quizz all
        function renderQuizCardItem(quiz) {
            const statusText = quiz.deleted_at ?
                'Đã xóa' :
                (quiz.status === 'published' ? 'Đã xuất bản' : 'Nháp');

            const statusClass = quiz.deleted_at ?
                'bg-danger' :
                (quiz.status === 'published' ? 'bg-success' : 'bg-warning');

            const updatedAt = formatDate(quiz.updated_at); // Định nghĩa hàm formatDate nếu chưa có

            return `
                <div class="quiz-card-item quiz-item-${quiz.id}">
                    <div class="card quiz-card h-100">
                        <div class="card-body">
                            <span class="status-badge ${statusClass} text-white">${statusText}</span>

                            <div class="dropdown text-end action-quizz-teacher col-1">
                                <button class="btn btn-sx btn-light border-0" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="icofont-navigation-menu"></i>
                                </button>

                                <div class="dropdown-menu quiz-footer row">
                                    <div class="col-12 dropdown-item">
                                        <a href="/teacher/quizzes/detail/${quiz.id}" class="btn btn-outline-info w-100 quiz-action-btn">
                                            <i class="icofont-eye me-1"></i> Xem chi tiết
                                        </a>
                                    </div>
                                    <div class="col-12 dropdown-item">
                                        <a href="#" class="btn btn-outline-primary-quiz quiz-action-btn">
                                            <i class="icofont-edit me-1"></i> Sửa
                                        </a>
                                    </div>
                                    <div class="col-12 dropdown-item">
                                        <form action="/teacher/quizzes/${quiz.id}/delete" method="POST" class="w-100 btn-delete-quiz-form">
                                            <input type="hidden" name="_token" value="${csrfToken}">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="btn btn-outline-danger border w-100 quiz-action-btn btn-delete-quiz" data-quiz-id="${quiz.id}">
                                                <i class="icofont-ui-delete me-1"></i> Xóa
                                            </button>
                                        </form>
                                    </div>
                                    <div class="col-12 dropdown-item">
                                        <a href="#" class="btn btn-success quiz-action-btn w-100">
                                            <i class="icofont-chart-line-alt me-1"></i> Xem kết quả
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <h5 class="quiz-title">${quiz.title}</h5>
                            <div>
                                <div class="question-count">
                                    <i class="icofont-question-circle me-1"></i>
                                    ${quiz.total_questions??0 } câu hỏi
                                </div>
                                    <div class="question-count">
                                    <i class="icofont-clock-time me-1"></i>
                                    ${quiz.duration_minutes } Phút
                                </div>
                            </div>
                            <div class="quiz-meta">
                                <i class="icofont-teacher"></i> Giáo viên: ${quiz.creator_name}
                            </div>
                            <div class="quiz-meta mb-2">
                                <i class="icofont-clock-time"></i> Cập nhật: ${updatedAt}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }


        function formatDate(dateTimeStr) {
            const date = new Date(dateTimeStr);
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();
            return `${day}/${month}/${year}`;
        }



        //Hàm xử lý khi click vào xem kết quả
        $('.view-classes').on('click', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            $.ajax({
                method: 'GET',
                url: url,
                success: function(response) {

                    const classes = response.classes;
                    const quiz = response.quiz;

                    let classListHtml = '';
                    if (classes.length > 0) {
                        classes.forEach(cls => {

                            classListHtml += `
                                    <li class="list-group-item class-item d-flex justify-content-between align-items-start border mt-1">
                                        <div class="ms-2 me-auto">
                                            <div class="fw-bold">
                                                <i class="icofont-school me-1 text-primary"></i> ${cls.class_name}
                                            </div>
                                            <small><i class="icofont-book-alt me-1 text-secondary"></i> <strong>Khóa học:</strong> ${cls.course_name}</small><br>
                                            <small><i class="icofont-student-alt me-1 text-primary"></i> <strong>Số học sinh đã làm bài:</strong> ${cls.students_attempted}</small><br>
                                            <small><i class="icofont-tasks me-1 text-info"></i> <strong>Tổng lượt làm bài:</strong> ${cls.total_attempts}</small>
                                        </div>
                                        <a href="/teacher/quizzes/${quiz.id}/results/class/${cls.class_id}" class="btn btn-sm btn-outline-primary mt-2 btn-resultStudent">
                                            <i class="icofont-eye-alt me-1"></i> Chi tiết
                                        </a>
                                    </li>
                                `;
                        });
                    } else {
                        classListHtml =
                            '<div class="alert alert-info">Không có lớp nào làm quiz!</div>';
                    }
                    $("#statistics").html(`
                        <div class="small question-count"><i class="icofont-users-alt-3 text-info"></i> Tổng lớp: ${response.statistics}</div>
                    `)

                    // Cập nhật danh sách lớp
                    $('#classList').html(classListHtml);
                    $('#classSidebar').addClass('open');

                },
                error: function() {
                    alert('Có lỗi xảy ra khi lấy danh sách lớp học.');
                }
            });

        });

        // Xử lý sự kiện đóng sidebar
        $('#close-btn-sidebar').on('click', function() {
            $('#classSidebar').removeClass('open');
            $('#classList').html('');
        });



        //Hàm xử lý khi click vào xem chi tiết kết quả của lớp
        $(document).on('click', '.btn-resultStudent', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            $.ajax({
                method: 'GET',
                url: url,
                success: function(response) {
                    const students = response.students;
                    const quiz = response.quiz;
                    const statistics = response.statistics;
                    console.log(response);
                    let html = '';
                    if (students.length > 0) {
                        students.forEach(student => {

                            html += `
                                <li class="list-group-item student-item border rounded shadow-sm p-3 mb-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="w-100">
                                            <!-- Tên và nút xem -->
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div class="fw-bold text-primary">
                                                    <i class="icofont-user me-2"></i> ${student.student_name}
                                                </div>
                                                <a href="/teacher/quizzes/${quiz.id}/results/class/${statistics.class_id}/student/${student.student_id}" class="btn btn-sm btn-outline-primary quiz-result-completed">
                                                    <i class="icofont-eye-alt me-1"></i> Xem chi tiết
                                                </a>
                                            </div>

                                            <!-- Thông tin cá nhân -->
                                            <div class="row">
                                                <div class="col-6">
                                                    <small class="d-block mb-1">
                                                        <i class="icofont-birthday-cake me-1 text-muted"></i>
                                                        <strong>Ngày sinh:</strong> ${student.birthday}
                                                    </small>
                                                    <small class="d-block">
                                                        <i class="icofont-user me-1 text-muted"></i>
                                                        <strong>Giới tính:</strong> ${student.gender}
                                                    </small>
                                                </div>
                                                <div class="col-6">
                                                    <small class="d-block mb-1">
                                                        <i class="icofont-ui-edit me-1 text-info"></i>
                                                        <strong>Số lần làm bài:</strong> ${student.total_attempts}
                                                    </small>
                                                    <small class="d-block">
                                                        <i class="icofont-chart-line me-1 text-success"></i>
                                                        <strong>Điểm trung bình:</strong> ${student.average_score}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            `;
                        });

                    } else {
                        html = '<div class="alert alert-info">Không có học sinh nào làm quiz!</div>';
                    }

                    $("#statisticsResulltStudent").html(`
                        <div class="small question-count"><i class="icofont-users text-info"></i> Tổng học sinh: ${statistics.students_attempted}/${statistics.total_students}</div>
                    `)
                    $("#titleResultStudent").text(`Học sinh lớp: ${statistics.class_name}`)
                    // Cập nhật danh sách học sinh
                    $('#resultStudentList').html(html);
                    $('#sidebarResultStudent').addClass('open');
                },
                error: function() {
                    alert('Có lỗi xảy ra khi lấy danh sách học sinh làm quiz');
                }
            });


        });


        // Xử lý sự kiện đóng sidebar
        $('#close-btn-sidebar-resultStudent').on('click', function() {
            $('#sidebarResultStudent').removeClass('open');
            $('#resultStudentList').html('');
        });



        //Hàm sử  lý khi click vào xem chi tiết các làm bài quizz của học sinh
        $(document).on('click', '.quiz-result-completed', function(e) {
            e.preventDefault();
            $('#ed-preloader').css('display', 'flex');

            const url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    console.log(response);
                    let html = '';
                    response.quizAttempts.forEach((item, index) => {
                        html += `
                            <a href="/teacher/quizz/${item.quiz_id}/show-result/${item.attempt_id}/student/${item.user_id}" class="col-12 col-md-6 mb-4" id="modal-detail-result">
                                <div class="card shadow-lg rounded-3 border-0 h-100 custom-hover">
                                    <div class="card-body p-3 d-flex flex-column justify-content-between h-100">
                                        <div>
                                            <!-- Tiêu đề lượt làm -->
                                            <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                                                <h6 class="fw-bold text-dark mb-0">
                                                    <i class="icofont-pencil-alt-2 me-2 text-secondary"></i>Lần ${index + 1}
                                                </h6>
                                                <span class="badge bg-primary px-3 py-2 rounded-pill">
                                                    <i class="icofont-star me-1"></i>${item.score} Điểm
                                                </span>
                                            </div>

                                            <!-- Thời gian nộp -->
                                            <p class="mb-1 text-muted">
                                                <i class="icofont-clock-time me-2 text-primary"></i>
                                                <strong>Thời gian:</strong> ${item.duration_minutes}
                                            </p>
                                            <!-- Thời gian nộp -->
                                            <p class="mb-1 text-muted">
                                                <i class="icofont-clock-time me-2 text-secondary"></i>
                                                <strong>Ngày làm:</strong> ${item.completed_date}
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
                    $("#resultModalLabel").html(
                        `<i class="icofont-chart-bar-graph text-primary"></i>  Kết quả: ${response.quiz.title} - ${response.student.name}`
                        )
                    $('.quiz-action-btn-result').hide();
                    $('#ed-preloader').fadeOut();

                    $('#resultModal').modal('show');

                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        })


        //Hàm hiển thị chi tiết kết quả
        $(document).on('click', '#modal-detail-result', function(e) {
            e.preventDefault();
            $('#ed-preloader').css('display', 'flex');
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
                    $('#ed-preloader').fadeOut();
                }
            });
        });
    </script>
@endpush
