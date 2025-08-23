<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Vendor css (Require in all Page) -->
    <link href="{{ asset('admin/css/vendor.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Icons css (Require in all Page) -->
    <link href="{{ asset('admin/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- App css (Require in all Page) -->
    <link href="{{ asset('admin/css/app.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Theme Config js (Require in all Page) -->
    <script src="{{ asset('admin/js/config.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- LINK Jquery --}}
    <script src="{{ asset('client/plugins/js/jquery.min.js') }}"></script>
    <style>
        body {
            background: linear-gradient(180deg, #f3f3f3 0%, #f3f3f3 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
        }

        .border-radius-bottom {
            border-bottom-left-radius: 12px;
            border-bottom-right-radius: 12px;
        }
    </style>

</head>

<body>
    <header class="bg-white py-2">
        <div class="container">
            <div class="d-flex align-items-center flex-wrap justify-content-between">
                <!-- Nút Quay lại -->
                <div class="col-2 col-md-2 mb-md-0 text-center text-md-start">
                    <a href="{{ route('client.quizz') }}"
                        class="btn btn-outline-secondary btn-sm d-flex align-items-center justify-content-center">
                        <iconify-icon icon="solar:arrow-left-broken" class="fs-20"></iconify-icon>
                        <span class="d-none d-sm-inline ms-1">Quay lại</span>
                    </a>
                </div>
                <div class="card-header text-dark text-center py-2 border-bottom d-none d-sm-block">
                    <h5 class="mb-0">{{ $quiz->title }}</h5>
                </div>
                <!-- Nút Lưu Nháp + Xuất Bản -->
                <div class="d-flex flex-wrap justify-content-center justify-content-md-end gap-2">
                    <a href="{{ route('teacher.quizzes.update.status', ['id' => $quiz->id, 'status' => 'draft']) }}"
                        class="btn btn-outline-primary btn-sm d-flex align-items-center btn-update-status-quiz">
                        <iconify-icon icon="mdi:file-document-edit-outline" class="fs-20 me-1"></iconify-icon> Lưu Nháp
                    </a>
                    <a href="{{ route('teacher.quizzes.update.status', ['id' => $quiz->id, 'status' => 'published']) }}"
                        class="btn btn-success btn-sm d-flex align-items-center btn-add btn-update-status-quiz">
                        <iconify-icon icon="mdi:cloud-upload-outline" class="fs-20 me-1"></iconify-icon> Xuất Bản
                    </a>
                </div>
            </div>
        </div>
    </header>


    <div class="container">
        <!-- Quiz Information -->
        <div class="mb-2 shadow-sm bg-white border-radius-bottom">
            <div class="card-header d-block d-md-none text-dark text-center py-2 border-bottom">
                <h5 class="mb-0">{{ $quiz->title }}</h5>
            </div>
            <div class="p-2 custom-box">
                <div class="d-flex justify-content-around flex-wrap">

                    <!-- Thời gian -->
                    <div class="col-12 col-md-2">
                        <div class="d-flex align-items-center">
                            <iconify-icon icon="mdi:clock-outline" class="text-success me-2 fs-5"></iconify-icon>
                            <div><strong>Thời gian:</strong> {{ $quiz->duration_minutes }} phút</div>
                        </div>
                    </div>

                    <!-- Mã truy cập -->
                    <div class="col-12 col-md-2">
                        <div class="d-flex align-items-center">
                            <iconify-icon icon="mdi:key-outline" class="text-primary me-2 fs-5"></iconify-icon>
                            <div><strong>Mã truy cập:</strong> {{ $quiz->access_code }}</div>
                        </div>
                    </div>

                    <!-- Trạng thái -->
                    <div class="col-12 col-md-2">
                        <div class="d-flex align-items-center">
                            <iconify-icon icon="mdi:lock-open-variant-outline"
                                class="text-info me-2 fs-5"></iconify-icon>
                            <div>
                                <strong>Trạng thái:</strong>
                                <span
                                    class="badge {{ $quiz->is_public == 1 ? 'bg-success-subtle text-success' : 'bg-info-subtle text-info' }} py-1 px-2">
                                    {{ $quiz->is_public == 1 ? 'public' : 'private' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Lớp học -->
                    @if ($quiz->class)
                        <div class="col-12 col-md-2">
                            <div class="d-flex align-items-center">
                                <iconify-icon icon="mdi:school-outline" class="text-warning me-2 fs-5"></iconify-icon>
                                <div><strong>Lớp học:</strong> {{ $quiz->class->name }}</div>
                            </div>
                        </div>
                    @endif

                    <!-- Khóa học -->
                    @if ($quiz->course)
                        <div class="col-12 col-md-2">
                            <div class="d-flex align-items-center">
                                <iconify-icon icon="mdi:book-open-variant-outline"
                                    class="text-danger me-2 fs-5"></iconify-icon>
                                <div><strong>Khóa học:</strong> {{ $quiz->course->name }}</div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>


        <!-- Title and Actions -->
        <div class="d-flex justify-content-between align-items-center mb-1">
            <h4 class="card-title">Danh sách câu hỏi</h4>

        </div>

        <!-- Question List -->
        <div class="row" id="question-list">

            @if (empty($allQuestions) || $allQuestions->filter()->isEmpty())
                {{-- Nếu không có câu hỏi nào --}}
                {{-- Hiển thị thông báo không có câu hỏi --}}
                <div class="col-12">
                    <div class="alert alert-info text-center d-flex justify-content-center align-items-center "
                        role="alert">
                        <iconify-icon icon="solar:info-circle-broken" class="fs-24 me-3"></iconify-icon>
                        Hiện tại chưa có câu hỏi nào trong quiz này.
                    </div>
                </div>
            @else
                @foreach ($allQuestions as $key => $question)
                    @if ($question->question_type === 'multiple_choice')
                        {{-- Hiển thị câu hỏi trắc nghiệm --}}
                        <div class="col-12 question-item" id="question-{{ $question->id }}">
                            <div class="card border-purple shadow-sm">
                                <div class="card-header d-flex justify-content-between align-items-start flex-wrap">
                                    <div>
                                        <div class="d-flex flex-wrap align-items-center">
                                            <h5 class="card-title mb-0 me-3">
                                                Câu hỏi {{ (int) $key + 1 }} <span
                                                    class="badge bg-info-subtle text-info">{{ $question->points }}
                                                    điểm</span>
                                            </h5>
                                            <p class="card-text mb-0 fw-bold"><strong></strong>
                                                {{ $question->content }}
                                            </p>
                                        </div>
                                        <p class="card-text mt-2 mb-0 fs-6"><strong>Loại:</strong> Trắc nghiệm
                                            {{ $question->type == 'single' ? 'một đáp án' : 'nhiều đáp án' }}</p>
                                    </div>
                                    <div class="mt-2 mt-md-0">
                                        <a href="{{ route('teacher.questions.edit', ['id' => $question->id]) }}"
                                            class="btn btn-soft-primary btn-sm me-1 btn-edit-question" title="Sửa"
                                            data-questiontype = "{{ $question->question_type ?? '' }}">
                                            <iconify-icon icon="solar:pen-2-broken" class="fs-18"></iconify-icon>
                                        </a>
                                        <form action="{{ route('teacher.questions.delete', ['id' => $question->id]) }}"
                                            method="POST" class="d-inline-block delete-question-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                class="btn btn-soft-danger btn-sm delete-question-btn" title="Xoá"
                                                data-question-id="{{ $question->id }}">
                                                <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"
                                                    class="fs-18"></iconify-icon>
                                            </button>
                                        </form>

                                    </div>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled d-flex flex-wrap gap-3">
                                        @foreach ($answers->where('question_id', $question->id)->values() as $loopIndex => $answer)
                                            <li class="border px-3 py-1 rounded bg-light bg-outline-success">
                                                {{ chr(65 + $loopIndex) }}: {{ $answer->content }}
                                                <span
                                                    class="{{ $answer->is_correct == 1 ? 'text-success' : 'text-danger' }}">
                                                    {{ $answer->is_correct == 1 ? '✔' : '✖' }}
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <p class="mb-0"><strong>Giải thích:</strong>
                                        {{ $question->explanation ?? 'No explanation' }}</p>

                                </div>
                            </div>
                        </div>
                    @elseif ($question->question_type === 'fill_blank')
                        {{-- Hiển thị câu hỏi điền từ --}}
                        <div class="col-12 question-item" id="question-{{ $question->id }}">
                            <div class="card border-purple shadow-sm">
                                <div class="card-header d-flex justify-content-between align-items-start flex-wrap">
                                    <div>
                                        <div class="d-flex flex-wrap align-items-center">
                                            <h5 class="card-title mb-0 me-3">
                                                Câu hỏi {{ (int) $key + 1 }} <span
                                                    class="badge bg-info-subtle text-info">{{ $question->points }}
                                                    điểm</span>
                                            </h5>
                                            <p class="card-text mb-0 fw-bold"><strong></strong>
                                                {{ $question->prompt }}
                                            </p>
                                        </div>
                                        <p class="card-text mt-2 mb-0 fs-6"><strong>Loại:</strong>
                                            {{ $question->type == 'fill' ? 'Điền từ' : 'Sắp xếp câu' }}</p>
                                    </div>
                                    <div class="mt-2 mt-md-0">
                                        <a href="{{ route('teacher.questions.sentence.edit', ['id' => $question->id]) }}"
                                            class="btn btn-soft-primary btn-sm me-1 btn-edit-question" title="Sửa"
                                            data-questiontype="{{ $question->type }}">
                                            <iconify-icon icon="solar:pen-2-broken" class="fs-18"></iconify-icon>
                                        </a>
                                        <form
                                            action="{{ route('teacher.questions.sentence.delete', ['id' => $question->id]) }}"
                                            method="POST" class="d-inline-block delete-question-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                class="btn btn-soft-danger btn-sm delete-question-btn" title="Xoá"
                                                data-question-id="{{ $question->id }}">
                                                <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"
                                                    class="fs-18"></iconify-icon>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p class="mb-2"><strong>Đáp án đúng:</strong>
                                        {{ $question->correct_answer }}</p>
                                    <p class="mb-0"><strong>Giải thích:</strong>
                                        {{ $question->explanation ?? 'No explanation' }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach

            @endif

        </div>


        <!-- Modal Chọn Loại Câu hỏi -->
        <div class="modal fade custom-modal" id="modal-select-question-type" tabindex="-1"
            aria-labelledby="selectQuestionTypeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header custom-modal-header bg-light-subtle">
                        <h5 class="modal-title d-flex align-items-center gap-2" id="selectQuestionTypeModalLabel">
                            <iconify-icon icon="solar:question-circle-broken" class="text-primary"></iconify-icon>
                            Chọn loại câu hỏi
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex flex-column gap-3">
                            <button type="button" class="btn btn-outline-primary btn-open-modal"
                                data-target="#modal-add-question">
                                <iconify-icon icon="solar:check-circle-broken" class="fs-20 me-2"></iconify-icon>
                                Trắc
                                nghiệm
                            </button>

                            <button type="button" class="btn btn-outline-primary btn-open-modal"
                                data-target="#modal-add-fill-question">
                                <iconify-icon icon="solar:pen-2-broken" class="fs-20 me-2"></iconify-icon> Điền từ
                            </button>

                            <button type="button" class="btn btn-outline-primary btn-open-modal"
                                data-target="#modal-add-reorder-question">
                                <iconify-icon icon="solar:sort-broken" class="fs-20 me-2"></iconify-icon> Sắp xếp
                                câu
                            </button>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary custom-btn-cancel" data-bs-dismiss="modal">
                            <iconify-icon icon="solar:close-circle-broken" class="me-1"></iconify-icon> Đóng
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Thêm/Sửa Câu hỏi Trắc nghiệm -->
        <div class="modal fade custom-modal" id="modal-add-question" tabindex="-1"
            aria-labelledby="addQuestionModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <div class="modal-header custom-modal-header bg-light-subtle">
                        <h5 class="modal-title d-flex align-items-center gap-2" id="addQuestionModalLabel">
                            <iconify-icon icon="solar:pen-2-broken" class="text-primary"></iconify-icon>
                            <span class="title-modal-question">
                                Thêm câu hỏi
                                trắc nghiệm
                            </span>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <form id="question-form" action="{{ route('teacher.questions.store', ['id' => $quiz->id]) }}"
                        method="POST">
                        <div class="modal-body d-flex justify-content-between flex-wrap"
                            style="overflow-y: scroll; height: 60vh">
                            <input type="hidden" name="_method" value="POST">
                            <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">

                            <div class="mb-3 col-12">
                                <label for="question_content" class="form-label fw-bold">Nội dung câu hỏi</label>
                                <textarea class="form-control form-control-sm" id="question_content" rows="4" name="content"
                                    placeholder="Nhập nội dung câu hỏi"></textarea>
                            </div>

                            <div class="mb-3 col-12 col-md-5">
                                <label for="question_type" class="form-label fw-bold">Loại câu hỏi</label>
                                <select class="form-select form-select-sm" id="question_type" name="type">
                                    <option value="single">Một đáp án</option>
                                    <option value="multiple">Nhiều đáp án</option>
                                </select>
                            </div>

                            <div class="mb-3 col-12 col-md-5">
                                <label for="points" class="form-label fw-bold">Số điểm</label>
                                <input type="number" class="form-control form-control-sm" id="points"
                                    name="points" placeholder="Nhập số điểm" min="0.1" step="any">
                            </div>

                            <!-- Đáp án trắc nghiệm -->
                            <div class="mb-1 col-12" id="answers_mcq">
                                <label class="form-label fw-bold">Đáp án</label>

                                <!-- Answer A -->
                                <div class="mb-2 answer-item" data-index="0">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">A</span>
                                        <input type="text" class="form-control" name="answers[0][content]"
                                            placeholder="Nhập đáp án A">
                                        <input type="hidden" name="answers[0][is_correct]" value="false">
                                        <div class="input-group-text">
                                            <input class="form-check-input mt-0 correct-answer" type="radio"
                                                name="correct_selector" value="0">
                                            <!-- dùng value để JS cập nhật -->
                                        </div>
                                    </div>
                                </div>

                                <!-- Answer B -->
                                <div class="mb-2 answer-item" data-index="1">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">B</span>
                                        <input type="text" class="form-control" name="answers[1][content]"
                                            placeholder="Nhập đáp án B">
                                        <input type="hidden" name="answers[1][is_correct]" value="false">
                                        <div class="input-group-text">
                                            <input class="form-check-input mt-0 correct-answer" type="radio"
                                                name="correct_selector" value="0">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-primary" id="add-answer-btn">
                                    <iconify-icon icon="solar:plus-circle-broken" class="me-1"></iconify-icon>
                                    Thêm
                                    đáp án
                                </button>
                            </div>


                            <div class="mb-3 col-12 mt-3">
                                <label for="explanation" class="form-label fw-bold">Giải thích đáp án:</label>
                                <textarea class="form-control form-control-sm" id="explanation" rows="4" name="explanation"
                                    placeholder="Nhập câu giải thích nếu có"></textarea>
                            </div>
                            <div id="error-questions-container" class="col-12 mb-3 mt-1" style="display: none;">
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


        <!-- Modal Thêm/Sửa Câu hỏi Điền từ -->
        <div class="modal fade custom-modal" id="modal-add-fill-question" tabindex="-1"
            aria-labelledby="addFillQuestionModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <div class="modal-header custom-modal-header bg-light-subtle">
                        <h5 class="modal-title d-flex align-items-center gap-2" id="addFillQuestionModalLabel">
                            <iconify-icon icon="solar:pen-2-broken" class="text-primary"></iconify-icon>
                            <span class="title-modal-question-fill">
                                Thêm câu hỏi
                                điền từ
                            </span>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <form id="fill-question-form"
                        action="{{ route('teacher.questions.sentence.store', ['id' => $quiz->id]) }}" method="POST">
                        <input type="hidden" name="_method" value="POST">
                        <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">
                        <input type="hidden" name="type" value="fill">
                        <div class="modal-body d-flex justify-content-between flex-wrap"
                            style="overflow-y: scroll; height: 60vh">
                            <div class="mb-3 col-12">
                                <label for="fill_question_prompt" class="form-label fw-bold">Nội dung câu
                                    hỏi</label>
                                <textarea class="form-control form-control-sm" id="fill_question_prompt" rows="4"
                                    placeholder="Nhập câu với chỗ trống, sử dụng ___ để biểu thị chỗ trống" name="prompt"></textarea>
                            </div>
                            <div class="mb-3 col-12">
                                <label for="fill_correct_answer" class="form-label fw-bold">Đáp án đúng</label>
                                <input type="text" class="form-control form-control-sm" id="fill_correct_answer"
                                    placeholder="Nhập đáp án đúng cho chỗ trống" name="correct_answer">
                            </div>
                            <div class="mb-3 col-12">
                                <label for="fill_explanation" class="form-label fw-bold">Giải thích (tùy
                                    chọn)</label>
                                <textarea class="form-control form-control-sm" id="fill_explanation" rows="3"
                                    placeholder="Nhập giải thích cho đáp án" name="explanation"></textarea>
                            </div>
                            <div class="mb-3 col-12 col-md-5">
                                <label for="fill_points" class="form-label fw-bold">Số điểm</label>
                                <input type="number" class="form-control form-control-sm" id="fill_points"
                                    name="points" placeholder="Nhập số điểm" min="0.1" step="any">
                            </div>
                            <div id="error-questions-fill-container" class="col-12 mb-3 mt-1" style="display: none;">
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

        <!-- Modal Thêm/Sửa Câu hỏi Sắp xếp câu -->
        <div class="modal fade custom-modal" id="modal-add-reorder-question" tabindex="-1"
            aria-labelledby="addReorderQuestionModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <div class="modal-header custom-modal-header bg-light-subtle">
                        <h5 class="modal-title d-flex align-items-center gap-2" id="addReorderQuestionModalLabel">
                            <iconify-icon icon="solar:pen-2-broken" class="text-primary"></iconify-icon>
                            <span class="title-modal-question-reorder">
                                Thêm câu hỏi
                                sắp xếp câu
                            </span>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <form id="reorder-question-form"
                        action="{{ route('teacher.questions.sentence.store', ['id' => $quiz->id]) }}" method="POST">
                        <input type="hidden" name="_method" value="POST">
                        <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">
                        <input type="hidden" name="type" value="reorder">
                        <div class="modal-body d-flex justify-content-between flex-wrap"
                            style="overflow-y: scroll; height: 60vh">
                            <div class="mb-3 col-12">
                                <label for="reorder_question_prompt" class="form-label fw-bold">Nội dung câu
                                    hỏi</label>
                                <textarea class="form-control form-control-sm" id="reorder_question_prompt" rows="4"
                                    placeholder="Nhập câu hoặc đoạn văn cần sắp xếp" name="prompt"></textarea>
                            </div>
                            <div class="mb-3 col-12">
                                <label for="reorder_correct_answer" class="form-label fw-bold">Thứ tự đúng</label>
                                <textarea class="form-control form-control-sm" id="reorder_correct_answer" rows="3"
                                    placeholder="Nhập thứ tự đúng của các từ/câu, phân tách bằng dấu phẩy" name="correct_answer"></textarea>
                            </div>
                            <div class="mb-3 col-12">
                                <label for="reorder_explanation" class="form-label fw-bold">Giải thích (tùy
                                    chọn)</label>
                                <textarea class="form-control form-control-sm" id="reorder_explanation" rows="3"
                                    placeholder="Nhập giải thích cho đáp án" name="explanation"></textarea>
                            </div>
                            <div class="mb-3 col-12 col-md-5">
                                <label for="reorder_points" class="form-label fw-bold">Số điểm</label>
                                <input type="number" class="form-control form-control-sm" id="reorder_points"
                                    name="points" placeholder="Nhập số điểm" min="0.1" step="any">
                            </div>
                            <div id="error-questions-reorder-container" class="col-12 mb-3 mt-1"
                                style="display: none;">
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

        <div class="d-flex justify-content-center mb-5">
            <a href="#" class="btn btn-primary btn-sm btn-add w-auto" data-bs-toggle="modal"
                data-bs-target="#modal-select-question-type">
                <iconify-icon icon="solar:plus-circle-broken" class="fs-20"></iconify-icon>+ Thêm câu hỏi
            </a>
        </div>


    </div>


    <!-- Vendor Javascript (Require in all Page) -->
    <script src="{{ asset('admin/js/vendor.js') }}"></script>

    <!-- App Javascript (Require in all Page) -->
    <script src="{{ asset('admin/js/app.js') }}"></script>
    <script>
        let answerIndex = 2; // Đã có sẵn 2 đáp án A và B

        document.addEventListener('DOMContentLoaded', function() {
            const letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

            const questionTypeSelect = document.getElementById('question_type');
            const answersContainer = document.getElementById('answers_mcq');
            const addAnswerBtn = document.getElementById('add-answer-btn');

            function toggleAnswerType() {
                const isSingle = questionTypeSelect.value === 'single';
                const answerItems = answersContainer.querySelectorAll('.answer-item');

                answerItems.forEach((item) => {
                    const input = item.querySelector('.correct-answer');
                    input.type = isSingle ? 'radio' : 'checkbox';

                    //Gán name khi là radio
                    if (isSingle) {
                        input.name = 'correct_selector';
                    } else {
                        input.removeAttribute('name');
                    }
                });
            }

            questionTypeSelect.addEventListener('change', toggleAnswerType);
            toggleAnswerType();

            // Xử lý chọn đúng/sai
            answersContainer.addEventListener('change', function(e) {
                if (e.target.classList.contains('correct-answer')) {
                    const isSingle = questionTypeSelect.value === 'single';
                    const index = e.target.closest('.answer-item').dataset.index;
                    const hiddenInput = e.target.closest('.answer-item').querySelector(
                        `input[name="answers[${index}][is_correct]"]`);

                    if (isSingle) {
                        // Reset tất cả về 0
                        const allHidden = answersContainer.querySelectorAll(
                            'input[type="hidden"][name*="[is_correct]"]');
                        allHidden.forEach(input => input.value = '0');
                        hiddenInput.value = '1'; // Đáp án được chọn
                    } else {
                        // Toggle theo checkbox
                        hiddenInput.value = e.target.checked ? '1' : '0';
                    }
                }
            });

            // Thêm đáp án mới
            addAnswerBtn.addEventListener('click', function() {
                const letter = letters[answerIndex] || '?';
                const index = answerIndex;
                const isSingle = questionTypeSelect.value === 'single';

                const div = document.createElement('div');
                div.classList.add('mb-2', 'answer-item');
                div.dataset.index = index;

                div.innerHTML = `
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">${letter}</span>
                        <input type="text" class="form-control" name="answers[${index}][content]" placeholder="Nhập đáp án ${letter}">
                        <input type="hidden" name="answers[${index}][is_correct]" value="0">
                        <button type="button" class="btn btn-sm btn-danger btn-remove-answer">
                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"></iconify-icon>
                        </button>
                        <div class="input-group-text">
                            <input class="form-check-input mt-0 correct-answer"
                                type="${isSingle ? 'radio' : 'checkbox'}"
                                ${isSingle ? 'name="correct_selector"' : ''}
                                value="${index}">
                        </div>
                    </div>
                    `;
                answersContainer.appendChild(div);
                answerIndex++;
            });

            // Xóa đáp án
            answersContainer.addEventListener('click', function(e) {
                const removeBtn = e.target.closest('.btn-remove-answer');
                if (removeBtn) {
                    const answerItem = removeBtn.closest('.answer-item');
                    if (answerItem) {
                        answerItem.remove();
                        reindexAnswers();
                    }
                }
            });

            function reindexAnswers() {
                answerIndex = 0;
                const answerItems = answersContainer.querySelectorAll('.answer-item');
                const isSingle = questionTypeSelect.value === 'single';

                answerItems.forEach((item, i) => {
                    const letter = letters[i] || '?';
                    item.dataset.index = i;

                    const textInput = item.querySelector('input[type="text"]');
                    textInput.name = `answers[${i}][content]`;
                    textInput.placeholder = `Nhập đáp án ${letter}`;

                    const hiddenInput = item.querySelector('input[type="hidden"]');
                    hiddenInput.name = `answers[${i}][is_correct]`;

                    const correctInput = item.querySelector('.correct-answer');
                    correctInput.setAttribute('value', i);
                    correctInput.setAttribute('type', isSingle ? 'radio' : 'checkbox');

                    if (isSingle) {
                        correctInput.setAttribute('name', 'correct_selector');
                    } else {
                        correctInput.removeAttribute('name');
                    }

                    item.querySelector('.input-group-text').textContent = letter;
                });

                answerIndex = answerItems.length;
            }
        });






        $(document).ready(function() {

            //Xử lý sự kiện mở modal chọn loại câu hỏi
            $(document).on('click', '.btn-open-modal', function() {
                answerIndex = 2; // Reset lại chỉ số đáp án sau khi lưu
                const targetModalId = $(this).data('target');
                const currentModal = $('.modal.show');

                // Ẩn modal hiện tại nếu có
                if (currentModal.length) {
                    currentModal.modal('hide');
                }

                const targetModal = $(targetModalId);
                const modalForm = targetModal.find('form')[0];
                if (modalForm) {
                    modalForm.reset();
                }
                // Nếu mở modal trắc nghiệm thì xử lý vùng đáp án
                const answerContainer = targetModal.find('#answers_mcq');
                if (answerContainer.length) {
                    answerContainer.find('.answer-item').slice(2).remove();
                    answerContainer.find('.answer-item').each(function() {
                        $(this).find('input[type="text"]').val(''); // Xoá nội dung đáp án
                        $(this).find('input[type="hidden"]').val('0'); // Reset is_correct về 0
                        $(this).find('input.correct-answer').prop('checked', false); // Bỏ chọn đúng
                        $(this).find('input.correct-answer').attr('type',
                            'radio'); // Reset lại type

                    });
                }
                targetModal.modal('show');
            });


            // Xử lý form thêm / sửa câu hỏi trắc nghiệm
            $('#question-form').on('submit', function(e) {
                e.preventDefault();

                const form = $(this);
                const formData = form.serialize();
                const actionUrl = form.attr('action');
                const method = form.attr('method');
                const errorContainer = $('#error-questions-container');
                const formModal = $('#modal-add-question');
                errorContainer.hide().empty();
                console.log(formData);

                postEdit(formData, actionUrl, method, errorContainer, formModal);
                answerIndex = 2; // Reset lại chỉ số đáp án sau khi lưu
            });

            // Xử lý form thêm / sửa câu hỏi điền từ
            $('#fill-question-form').on('submit', function(e) {
                e.preventDefault();

                const form = $(this);
                const formData = form.serialize();
                const actionUrl = form.attr('action');
                const method = form.attr('method');
                const errorContainer = $('#error-questions-fill-container');
                const formModal = $('#modal-add-fill-question');
                errorContainer.hide().empty();
                console.log(formData);

                postEdit(formData, actionUrl, method, errorContainer, formModal);

            });


            // Xử lý form thêm / sửa câu hỏi sắp xếp câu
            $('#reorder-question-form').on('submit', function(e) {
                e.preventDefault();

                const form = $(this);
                const formData = form.serialize();
                const actionUrl = form.attr('action');
                const method = form.attr('method');
                const errorContainer = $('#error-questions-reorder-container');
                const formModal = $('#modal-add-reorder-question');
                errorContainer.hide().empty();
                console.log(formData);

                postEdit(formData, actionUrl, method, errorContainer, formModal);

            });



            //Xử lý khi ấn nút sửa
            $(document).on('click', '.btn-edit-question', function(e) {
                e.preventDefault();
                const questionType = $(this).data('questiontype');
                console.log('Loại câu hỏi:', questionType);

                if (questionType === 'multiple_choice') {
                    const mcModal = $('#modal-add-question');
                    const title = mcModal.find('.title-modal-question');
                    title.text('Sửa câu hỏi trắc nghiệm');
                    const url = $(this).attr('href');
                    if (!url) {
                        alert('Không tìm thấy URL!');
                        return;
                    }

                    $.ajax({
                        url: url,
                        method: 'GET',
                        success: function(response) {
                            console.log(response);
                            $('#question-form').attr('action',
                                `/teacher/questions/${response.question.id}/update`);
                            $('#question-form').find('input[name="_method"]').val('PUT');
                            const question = response.question;
                            const answers = response.answers || [];
                            const questionType = question.type; // 'single' hoặc 'multiple'

                            mcModal.find('#question_content').val(question.content || '');
                            mcModal.find('#question_type').val(questionType || 'single');
                            mcModal.find('#points').val(question.points || 1);
                            mcModal.find('#explanation').val(question.explanation || '');

                            // Xoá đáp án cũ
                            mcModal.find('#answers_mcq').empty();

                            answers.forEach(function(answer, index) {
                                const letter = String.fromCharCode(65 + index);
                                const isCorrect = answer.is_correct ? 'checked' : '';

                                // Với radio thì cần đặt name giống nhau để chọn 1 đáp án
                                const inputType = questionType === 'single' ? 'radio' :
                                    'checkbox';


                                const answerHtml = `
                                    <div class="mb-2 answer-item" data-index="${index}">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">${letter}</span>
                                            <input type="text" class="form-control" name="answers[${index}][content]"
                                                value="${answer.content || ''}" placeholder="Nhập đáp án ${letter}">
                                            <input type="hidden" name="answers[${index}][is_correct]" value="${answer.is_correct}">
                                            <div class="input-group-text">
                                                <input class="form-check-input mt-0 correct-answer" type="${inputType}"
                                                    name="correct_selector" value="1" ${isCorrect}>
                                            </div>
                                        </div>
                                    </div>
                                `;

                                mcModal.find('#answers_mcq').append(answerHtml);
                            });
                        }
                    });


                    mcModal.modal('show');


                } else if (questionType === 'fill') {
                    const fillModal = $('#modal-add-fill-question');
                    const title = fillModal.find('.title-modal-question-fill');
                    title.text('Sửa câu hỏi điền từ');
                    const url = $(this).attr('href');
                    console.log('URL:' + url);
                    if (!url) {
                        alert('Không tìm thấy URL!');
                        return;
                    }
                    $.ajax({
                        url: url,
                        method: 'GET',
                        success: function(response) {
                            console.log(response);

                            const question = response.question;

                            // Gán action và method cho form
                            $('#fill-question-form').attr('action',
                                `/teacher/questions-sentence/${response.question.id}/update`
                            );
                            $('#fill-question-form').find('input[name="_method"]').val('PUT');

                            // Đổ dữ liệu vào form
                            $('#fill_question_prompt').val(question.prompt || '');
                            $('#fill_correct_answer').val(question.correct_answer || '');
                            $('#fill_explanation').val(question.explanation || '');
                            $('#fill_points').val(question.points || 1);
                        },
                        error: function() {
                            alert('Không thể lấy dữ liệu câu hỏi. Vui lòng thử lại.');
                        }
                    });

                    fillModal.modal('show');

                } else if (questionType === 'reorder') {
                    const reorderModal = $('#modal-add-reorder-question');
                    const title = reorderModal.find('.title-modal-question-reorder');
                    title.text('Sửa câu hỏi sắp xếp câu');

                    const url = $(this).attr('href');
                    if (!url) {
                        alert('Không tìm thấy URL!');
                        return;
                    }
                    $.ajax({
                        url: url,
                        method: 'GET',
                        success: function(response) {
                            console.log(response);

                            const question = response.question;

                            // Gán action và method cho form
                            $('#reorder-question-form').attr('action',
                                `/teacher/questions-sentence/${response.question.id}/update`
                            );
                            $('#reorder-question-form').find('input[name="_method"]').val(
                                'PUT');

                            // Đổ dữ liệu vào form
                            $('#reorder_question_prompt').val(question.prompt || '');
                            $('#reorder_correct_answer').val(question.correct_answer || '');
                            $('#reorder_explanation').val(question.explanation || '');
                            $('#reorder_points').val(question.points || 1);
                        },
                        error: function() {
                            alert('Không thể lấy dữ liệu câu hỏi. Vui lòng thử lại.');
                        }
                    });

                    reorderModal.modal('show');

                } else {
                    alert('Loại câu hỏi không hợp lệ: ' + questionType);
                }
            });



            // function xử lý thêm sửa question all
            function postEdit(formData, actionUrl, method, errorContainer, formModal) {
                const form = formModal.find('form').first();
                console.log(actionUrl);
                $.ajax({
                    url: actionUrl,
                    method: method,
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // Hiển thị thông báo thành công
                        console.log(response);

                        const html = response.question_type === "multiple_choice" ?
                            renderSingleQuestion(response.question, response.answers) :
                            renderSentenceQuestion(response.question);

                        console.log(html);
                        if ((form.find('input[name="_method"]').val() || '').toUpperCase() == 'PUT') {
                            // Nếu sửa
                            const questionId = response.question.id;
                            const target = $(`#question-${questionId}`);
                            console.log(target);
                            target.replaceWith(html);
                        } else {
                            // Nếu thêm
                            $('#question-list').append(html);
                            if ($('.alert-info').length) {
                                $('.alert-info').remove();
                            }

                        }

                        Swal.fire({
                            title: 'Thành công!',
                            text: response.message || 'Câu hỏi đã được lưu thành công.',
                            icon: 'success',
                            confirmButtonClass: 'btn btn-primary w-xs mt-2',
                            buttonsStyling: false
                        });

                        form[0].reset(); // reset input
                        $('#answers_mcq .answer-item').slice(2).remove(); // chỉ giữ lại 2 đáp án đầu
                        $('#answers_mcq input[type="hidden"]').val('0'); // reset is_correct về 0
                        $('#answers_mcq input.correct-answer').prop('checked',
                            false); // bỏ chọn radio/checkbox
                        formModal.modal('hide');
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            let errorHtml = '<div class="alert alert-danger"><ul>';
                            for (let field in errors) {
                                errors[field].forEach(msg => {
                                    errorHtml += `<li>${msg}</li>`;
                                    showDataToast(msg, 'danger');
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
            }
        });






        //Hàm xử lý sự kiện xóa
        $(document).on('click', '.delete-question-btn', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            const actionUrl = form.attr('action');
            Swal.fire({
                title: 'Bạn có chắc chắn?',
                text: "Bạn sẽ không thể hoàn tác hành động này!",
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
                            Swal.fire('Đã xóa!', 'Câu hỏi đã được xóa thành công.',
                                'success');
                            form.closest('.question-item').remove();

                        },
                        error: function() {
                            Swal.fire('Lỗi!', 'Không thể xóa câu hỏi này.', 'error');
                        }
                    });
                }
            });
        });







        // Hàm render câu hỏi trắc nghiệm
        function renderSingleQuestion(question, answers) {
            const letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            const container = document.getElementById('question-list');

            const questionId = question.id;
            const questionItems = document.querySelectorAll('#question-list .question-item');
            let index = Array.from(questionItems).findIndex(item => item.id === `question-${questionId}`);
            // Nếu không tìm thấy (thêm mới), lấy độ dài danh sách
            if (index === -1) {
                index = questionItems.length;
            }

            let answerListHtml = '<ul class="list-unstyled d-flex flex-wrap gap-3">';
            answers.forEach((ans, i) => {
                const letter = letters[i] || '?';
                answerListHtml += `
                    <li class="border px-3 py-1 rounded bg-light bg-outline-success">
                        ${letter}: ${ans.content}
                        <span class="${ans.is_correct == 1 ? 'text-success' : 'text-danger'}">
                            ${ans.is_correct == 1 ? '✔' : '✖'}
                        </span>
                    </li>
                `;
            });
            answerListHtml += '</ul>';

            const html = `
                <div class="col-12 question-item"  id="question-${ question.id }">
                    <div class="card border-purple shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-start flex-wrap">
                            <div>
                                <div class="d-flex flex-wrap align-items-center">
                                    <h5 class="card-title mb-0 me-3">
                                        Câu hỏi ${index + 1} <span class="badge bg-info-subtle text-info">${question.points} điểm</span>
                                    </h5>
                                    <p class="card-text mb-0 fw-bold">${question.content}</p>
                                </div>
                                <p class="card-text mt-2 mb-0 fs-6">
                                    <strong>Loại:</strong> ${question.type === 'single' ? 'Trắc nghiệm một đáp án' : 'Trắc nghiệm nhiều đáp án'}
                                </p>
                            </div>
                            <div class="mt-2 mt-md-0">
                                <a href="/teacher/questions/${question.id}/edit" class="btn btn-soft-primary btn-sm me-1 btn-edit-question" title="Sửa"
                                    data-questiontype = "multiple_choice">
                                    <iconify-icon icon="solar:pen-2-broken" class="fs-18"></iconify-icon>
                                </a>
                                <form action="/teacher/questions/${question.id}/delete" method="POST" class="d-inline-block delete-question-form">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="${document.querySelector('meta[name=csrf-token]').getAttribute('content')}">
                                    <button type="submit" class="btn btn-soft-danger btn-sm delete-question-btn" title="Xóa">
                                        <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="fs-18"></iconify-icon>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            ${answerListHtml}
                            <p class="mb-0"><strong>Giải thích:</strong> ${question.explanation || 'No explanation'}</p>
                        </div>
                    </div>
                </div>
            `;
            return html;
        }




        //Hàm render câu hỏi điền từ và sắp xếp câu
        function renderSentenceQuestion(question) {
            const container = document.getElementById('question-list');

            const questionId = question.id;
            const questionItems = document.querySelectorAll('#question-list .question-item');
            let index = Array.from(questionItems).findIndex(item => item.id === `question-${questionId}`);
            // Nếu không tìm thấy (thêm mới), lấy độ dài danh sách
            if (index === -1) {
                index = questionItems.length;
            }

            const html = `
                <div class="col-12 question-item" id="question-${question.id}">
                    <div class="card border-purple shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-start flex-wrap">
                            <div>
                                <div class="d-flex flex-wrap align-items-center">
                                    <h5 class="card-title mb-0 me-3">
                                        Câu hỏi ${index + 1}
                                        <span class="badge bg-info-subtle text-info">
                                            ${question.points} điểm
                                        </span>
                                    </h5>
                                    <p class="card-text mb-0 fw-bold">
                                        <strong></strong> ${question.prompt}
                                    </p>
                                </div>
                                <p class="card-text mt-2 mb-0 fs-6">
                                    <strong>Loại:</strong>
                                    ${question.type === 'fill' ? 'Điền từ' : 'Sắp xếp câu'}
                                </p>
                            </div>
                            <div class="mt-2 mt-md-0">
                                <a href="/teacher/questions-sentence/${question.id}/edit"
                                class="btn btn-soft-primary btn-sm me-1 btn-edit-question"
                                title="Sửa"
                                data-questiontype="${question.type}">
                                    <iconify-icon icon="solar:pen-2-broken" class="fs-18"></iconify-icon>
                                </a>
                                <form action="/teacher/questions-sentence/${question.id}/delete" method="POST" class="d-inline-block delete-question-form">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="${document.querySelector('meta[name=csrf-token]').getAttribute('content')}">
                                    <button type="submit" class="btn btn-soft-danger btn-sm delete-question-btn" title="Xóa">
                                        <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="fs-18"></iconify-icon>
                                    </button>
                                </form>

                            </div>
                        </div>
                        <div class="card-body">
                            <p class="mb-2"><strong>Đáp án đúng:</strong> ${question.correct_answer}</p>
                            <p class="mb-0"><strong>Giải thích:</strong> ${question.explanation || 'No explanation'}</p>
                        </div>
                    </div>
                </div>
            `;

            return html;

        }







        // Tạo phần tử button ảo có data-toast-* và click nó
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

            // Gắn lại sự kiện bằng tay (copy logic từ ToastNotification)
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



        $(document).on('click', '.btn-update-status-quiz', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            console.log('Cập nhật trạng thái quiz:', url);
            $.ajax({
                url: url,
                method: 'GET',
                success: function(response) {
                    if (response.message) {
                        Swal.fire({
                            title: 'Thành công!',
                            text: response.message || 'Lưu thành công quiz.',
                            icon: 'success',
                            confirmButtonClass: 'btn btn-primary w-xs mt-2',
                            buttonsStyling: false
                        });
                        // Cập nhật trạng thái trên giao diện
                        const statusBadge = $('#quiz-status-badge');
                        statusBadge.text(response.new_status_text);
                        statusBadge.removeClass('bg-success bg-danger').addClass(response
                            .new_status_class);
                    } else {
                        Swal.fire({
                            title: 'Lỗi!',
                            text: response.message || 'Không thể cập nhật trạng thái quiz.',
                            icon: 'error',
                            confirmButtonClass: 'btn btn-primary w-xs mt-2',
                            buttonsStyling: false
                        });
                    }
                },
                error: function() {
                    alert('Không thể lấy dữ liệu câu hỏi. Vui lòng thử lại.');
                }
            })

        })
    </script>

</body>

</html>
