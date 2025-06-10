@extends('admin.admin')

@section('title', 'Chi tiết Quiz')
@section('description', 'Quản lý câu hỏi và câu trả lời cho bài quiz')
@section('content')
    <div class="page-content">
        <!-- Start Container Fluid -->
        <div class="container-fluid">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.quizz') }}">Quản lý Quiz</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Chi tiết Quiz</li>
                </ol>
            </nav>

            <!-- Quiz Information -->
            <div class="card mb-2 border-success border">
                <h4 class="card-title  p-2 bg-success-subtle rounded text-dark">Thông tin bài quiz</h4>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <p><strong>Tiêu đề:</strong> {{ $quiz->title }}</p>
                        </div>

                        <div class="col-md-12 d-flex justify-content-between">
                            <p><strong>Thời gian:</strong> {{ $quiz->duration_minutes }} phút</p>
                            <p><strong>Mã truy cập:</strong> {{ $quiz->access_code }}</p>
                            <p><strong>Trạng thái:</strong> <span
                                    class="badge  {{ $quiz->is_public == 1 ? 'bg-success-subtle text-success' : 'bg-info-subtle text-info' }} py-1 px-2">{{ $quiz->is_public == 1 ? 'public' : 'private' }}</span>
                            </p>

                            @if ($quiz->class)
                                <p><strong>Lớp học:</strong> {{ $quiz->class->name }}</p>
                            @endif

                            @if ($quiz->course)
                                <p><strong>Lớp học:</strong> {{ $quiz->course->name }}</p>
                            @endif


                        </div>
                    </div>
                </div>
            </div>

            <!-- Title and Actions -->
            <div class="d-flex justify-content-between align-items-center mb-1">
                <h4 class="card-title">Danh sách câu hỏi</h4>
                <div>
                    <a href="{{ route('admin.quizz') }}" class="btn btn-outline-secondary btn-sm me-2">
                        <iconify-icon icon="solar:arrow-left-broken" class="fs-20"></iconify-icon> Quay lại
                    </a>
                    <a href="#" class="btn btn-outline-primary btn-sm me-2" data-bs-toggle="modal"
                        data-bs-target="#modal-select-question-type">
                        <iconify-icon icon="solar:plus-circle-broken" class="fs-20"></iconify-icon> Lưu Nháp
                    </a>
                    <a href="#" class="btn btn-success btn-sm btn-add" data-bs-toggle="modal"
                        data-bs-target="#modal-select-question-type">
                        <iconify-icon icon="solar:plus-circle-broken" class="fs-20"></iconify-icon> Xuất Bản
                    </a>
                </div>
            </div>

            <!-- Question List -->
            <div class="row g-3" id="question-list">
                <!-- Câu hỏi Trắc nghiệm -->
                <div class="col-12">
                    <div class="card border-purple shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-start flex-wrap">
                            <div>
                                <div class="d-flex flex-wrap align-items-center">
                                    <h5 class="card-title mb-0 me-3">
                                        Câu hỏi 1 <span class="badge bg-info-subtle text-info">1 điểm</span>
                                    </h5>
                                    <p class="card-text mb-0 "><strong>Nội dung:</strong> Từ "happy" có nghĩa là
                                        gì?</p>
                                </div>
                                <p class="card-text mt-2 mb-0 fs-6"><strong>Loại:</strong> Một đáp án</p>
                            </div>
                            <div class="mt-2 mt-md-0">
                                <a href="#!" class="btn btn-soft-primary btn-sm me-1" title="Sửa"
                                    data-bs-toggle="modal" data-bs-target="#modal-add-question">
                                    <iconify-icon icon="solar:pen-2-broken" class="fs-18"></iconify-icon>
                                </a>
                                <a href="#!" class="btn btn-soft-danger btn-sm sweetalert-params-question"
                                    title="Xoá" data-question-id="1">
                                    <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"
                                        class="fs-18"></iconify-icon>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled d-flex flex-wrap gap-3">
                                <li class="border px-3 py-1 rounded bg-light bg-outline-success">A: vui vẻ <span
                                        class="text-success">✔</span></li>
                                <li class="border px-3 py-1 rounded bg-light">B: buồn bã <span class="text-danger">✖</span>
                                </li>
                                <li class="border px-3 py-1 rounded bg-light">C: giận dữ <span class="text-danger">✖</span>
                                </li>
                                <li class="border px-3 py-1 rounded bg-light">D: sợ hãi <span class="text-danger">✖</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Câu hỏi Điền từ (Mẫu 1) -->
                <div class="col-12">
                    <div class="card border-purple shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-start flex-wrap">
                            <div>
                                <div class="d-flex flex-wrap align-items-center">
                                    <h5 class="card-title mb-0 me-3">
                                        Câu hỏi 2 <span class="badge bg-info-subtle text-info">2 điểm</span>
                                    </h5>
                                    <p class="card-text mb-0 "><strong>Nội dung:</strong> I have a ___ cat.</p>
                                </div>
                                <p class="card-text mt-2 mb-0 fs-6"><strong>Loại:</strong> Điền từ</p>
                            </div>
                            <div class="mt-2 mt-md-0">
                                <a href="#!" class="btn btn-soft-primary btn-sm me-1" title="Sửa"
                                    data-bs-toggle="modal" data-bs-target="#modal-add-fill-question">
                                    <iconify-icon icon="solar:pen-2-broken" class="fs-18"></iconify-icon>
                                </a>
                                <a href="#!" class="btn btn-soft-danger btn-sm sweetalert-params-question"
                                    title="Xoá" data-question-id="2">
                                    <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"
                                        class="fs-18"></iconify-icon>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="mb-2"><strong>Đáp án đúng:</strong> black</p>
                            <p class="mb-0"><strong>Giải thích:</strong> Từ "black" mô tả màu sắc của con mèo trong câu.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Câu hỏi Điền từ (Mẫu 2) -->
                <div class="col-12">
                    <div class="card border-purple shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-start flex-wrap">
                            <div>
                                <div class="d-flex flex-wrap align-items-center">
                                    <h5 class="card-title mb-0 me-3">
                                        Câu hỏi 3 <span class="badge bg-info-subtle text-info">1 điểm</span>
                                    </h5>
                                    <p class="card-text mb-0 "><strong>Nội dung:</strong> She ___ to school
                                        every day.</p>
                                </div>
                                <p class="card-text mt-2 mb-0 fs-6"><strong>Loại:</strong> Điền từ</p>
                            </div>
                            <div class="mt-2 mt-md-0">
                                <a href="#!" class="btn btn-soft-primary btn-sm me-1" title="Sửa"
                                    data-bs-toggle="modal" data-bs-target="#modal-add-fill-question">
                                    <iconify-icon icon="solar:pen-2-broken" class="fs-18"></iconify-icon>
                                </a>
                                <a href="#!" class="btn btn-soft-danger btn-sm sweetalert-params-question"
                                    title="Xoá" data-question-id="3">
                                    <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"
                                        class="fs-18"></iconify-icon>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="mb-2"><strong>Đáp án đúng:</strong> walks</p>
                            <p class="mb-0"><strong>Giải thích:</strong> Động từ "walks" phù hợp với chủ ngữ số ít và
                                diễn tả hành động hàng ngày.</p>
                        </div>
                    </div>
                </div>

                <!-- Câu hỏi Sắp xếp câu (Mẫu 1) -->
                <div class="col-12">
                    <div class="card border-purple shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-start flex-wrap">
                            <div>
                                <div class="d-flex flex-wrap align-items-center">
                                    <h5 class="card-title mb-0 me-3">
                                        Câu hỏi 4 <span class="badge bg-info-subtle text-info">3 điểm</span>
                                    </h5>
                                    <p class="card-text mb-0 "><strong>Nội dung:</strong> Sắp xếp các từ sau
                                        thành câu hoàn chỉnh: <em>is, my, teacher, she</em></p>
                                </div>
                                <p class="card-text mt-2 mb-0 fs-6"><strong>Loại:</strong> Sắp xếp câu</p>
                            </div>
                            <div class="mt-2 mt-md-0">
                                <a href="#!" class="btn btn-soft-primary btn-sm me-1" title="Sửa"
                                    data-bs-toggle="modal" data-bs-target="#modal-add-reorder-question">
                                    <iconify-icon icon="solar:pen-2-broken" class="fs-18"></iconify-icon>
                                </a>
                                <a href="#!" class="btn btn-soft-danger btn-sm sweetalert-params-question"
                                    title="Xoá" data-question-id="4">
                                    <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"
                                        class="fs-18"></iconify-icon>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="mb-2"><strong>Đáp án đúng:</strong> She is my teacher.</p>
                            <p class="mb-0"><strong>Giải thích:</strong> Câu được sắp xếp theo cấu trúc chủ ngữ - động từ
                                - tân ngữ.</p>
                        </div>
                    </div>
                </div>

                <!-- Câu hỏi Sắp xếp câu (Mẫu 2) -->
                <div class="col-12">
                    <div class="card border-purple shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-start flex-wrap">
                            <div>
                                <div class="d-flex flex-wrap align-items-center">
                                    <h5 class="card-title mb-0 me-3">
                                        Câu hỏi 5 <span class="badge bg-info-subtle text-info">2 điểm</span>
                                    </h5>
                                    <p class="card-text mb-0 "><strong>Nội dung:</strong> Sắp xếp các từ sau
                                        thành câu hoàn chỉnh: <em>book, reads, he, the</em></p>
                                </div>
                                <p class="card-text mt-2 mb-0 fs-6"><strong>Loại:</strong> Sắp xếp câu</p>
                            </div>
                            <div class="mt-2 mt-md-0">
                                <a href="#!" class="btn btn-soft-primary btn-sm me-1" title="Sửa"
                                    data-bs-toggle="modal" data-bs-target="#modal-add-reorder-question">
                                    <iconify-icon icon="solar:pen-2-broken" class="fs-18"></iconify-icon>
                                </a>
                                <a href="#!" class="btn btn-soft-danger btn-sm sweetalert-params-question"
                                    title="Xoá" data-question-id="5">
                                    <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"
                                        class="fs-18"></iconify-icon>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="mb-2"><strong>Đáp án đúng:</strong> He reads the book.</p>
                            <p class="mb-0"><strong>Giải thích:</strong> Câu được sắp xếp theo cấu trúc chủ ngữ - động từ
                                - tân ngữ, với "the" đứng trước danh từ.</p>
                        </div>
                    </div>
                </div>
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
                                <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal"
                                    data-bs-toggle="modal" data-bs-target="#modal-add-question">
                                    <iconify-icon icon="solar:check-circle-broken" class="fs-20 me-2"></iconify-icon> Trắc
                                    nghiệm
                                </button>
                                <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal"
                                    data-bs-toggle="modal" data-bs-target="#modal-add-fill-question">
                                    <iconify-icon icon="solar:pen-2-broken" class="fs-20 me-2"></iconify-icon> Điền từ
                                </button>
                                <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal"
                                    data-bs-toggle="modal" data-bs-target="#modal-add-reorder-question">
                                    <iconify-icon icon="solar:sort-broken" class="fs-20 me-2"></iconify-icon> Sắp xếp câu
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
                                <iconify-icon icon="solar:pen-2-broken" class="text-primary"></iconify-icon> Thêm câu hỏi
                                trắc nghiệm
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <form id="question-form">
                            <div class="modal-body d-flex justify-content-between flex-wrap"
                                style="overflow-y: scroll; height: 60vh">
                                <div class="mb-3 col-12">
                                    <label for="question_content" class="form-label fw-bold">Nội dung câu hỏi</label>
                                    <textarea class="form-control form-control-sm" id="question_content" rows="4"
                                        placeholder="Nhập nội dung câu hỏi" required></textarea>
                                </div>
                                <div class="mb-3 col-12 col-md-5">
                                    <label for="question_type" class="form-label fw-bold">Loại câu hỏi</label>
                                    <select class="form-select form-select-sm" id="question_type" required>
                                        <option value="mcq">Một đáp án</option>
                                        <option value="multi">Nhiều đáp án</option>
                                    </select>
                                </div>
                                <div class="mb-3 col-12 col-md-5">
                                    <label for="points" class="form-label fw-bold">Số điểm</label>
                                    <input type="number" class="form-control form-control-sm" id="points"
                                        name="points" placeholder="Nhập số điểm" min="1" required>
                                </div>
                                <!-- Đáp án trắc nghiệm -->
                                <div class="mb-3 col-12" id="answers_mcq">
                                    <label class="form-label fw-bold">Đáp án</label>
                                    <div class="mb-2">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">A</span>
                                            <input type="text" class="form-control" placeholder="Nhập đáp án A"
                                                name="answers[]" required>
                                            <div class="input-group-text">
                                                <input class="form-check-input mt-0 correct-answer" type="radio"
                                                    name="correct_answer" value="0">
                                            </div>

                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">B</span>
                                            <input type="text" class="form-control" placeholder="Nhập đáp án B"
                                                name="answers[]" required>
                                            <div class="input-group-text">
                                                <input class="form-check-input mt-0 correct-answer" type="radio"
                                                    name="correct_answer" value="0">
                                            </div>

                                        </div>
                                    </div>

                                </div>
                                <div class="text-end">
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="add-answer-btn">
                                        <iconify-icon icon="solar:plus-circle-broken" class="me-1"></iconify-icon> Thêm
                                        đáp án
                                    </button>
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
                                <iconify-icon icon="solar:pen-2-broken" class="text-primary"></iconify-icon> Thêm câu hỏi
                                điền từ
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <form id="fill-question-form">
                            <div class="modal-body d-flex justify-content-between flex-wrap"
                                style="overflow-y: scroll; height: 60vh">
                                <div class="mb-3 col-12">
                                    <label for="fill_question_prompt" class="form-label fw-bold">Nội dung câu hỏi</label>
                                    <textarea class="form-control form-control-sm" id="fill_question_prompt" rows="4"
                                        placeholder="Nhập câu với chỗ trống, sử dụng [___] để biểu thị chỗ trống" required></textarea>
                                </div>
                                <div class="mb-3 col-12">
                                    <label for="fill_correct_answer" class="form-label fw-bold">Đáp án đúng</label>
                                    <input type="text" class="form-control form-control-sm" id="fill_correct_answer"
                                        placeholder="Nhập đáp án đúng cho chỗ trống" required>
                                </div>
                                <div class="mb-3 col-12">
                                    <label for="fill_explanation" class="form-label fw-bold">Giải thích (tùy chọn)</label>
                                    <textarea class="form-control form-control-sm" id="fill_explanation" rows="3"
                                        placeholder="Nhập giải thích cho đáp án"></textarea>
                                </div>
                                <div class="mb-3 col-12 col-md-5">
                                    <label for="fill_points" class="form-label fw-bold">Số điểm</label>
                                    <input type="number" class="form-control form-control-sm" id="fill_points"
                                        name="points" placeholder="Nhập số điểm" min="1" required>
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
                                <iconify-icon icon="solar:pen-2-broken" class="text-primary"></iconify-icon> Thêm câu hỏi
                                sắp xếp câu
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <form id="reorder-question-form">
                            <div class="modal-body d-flex justify-content-between flex-wrap"
                                style="overflow-y: scroll; height: 60vh">
                                <div class="mb-3 col-12">
                                    <label for="reorder_question_prompt" class="form-label fw-bold">Nội dung câu
                                        hỏi</label>
                                    <textarea class="form-control form-control-sm" id="reorder_question_prompt" rows="4"
                                        placeholder="Nhập câu hoặc đoạn văn cần sắp xếp" required></textarea>
                                </div>
                                <div class="mb-3 col-12">
                                    <label for="reorder_correct_answer" class="form-label fw-bold">Thứ tự đúng</label>
                                    <textarea class="form-control form-control-sm" id="reorder_correct_answer" rows="3"
                                        placeholder="Nhập thứ tự đúng của các từ/câu, phân tách bằng dấu phẩy" required></textarea>
                                </div>
                                <div class="mb-3 col-12">
                                    <label for="reorder_explanation" class="form-label fw-bold">Giải thích (tùy
                                        chọn)</label>
                                    <textarea class="form-control form-control-sm" id="reorder_explanation" rows="3"
                                        placeholder="Nhập giải thích cho đáp án"></textarea>
                                </div>
                                <div class="mb-3 col-12 col-md-5">
                                    <label for="reorder_points" class="form-label fw-bold">Số điểm</label>
                                    <input type="number" class="form-control form-control-sm" id="reorder_points"
                                        name="points" placeholder="Nhập số điểm" min="1" required>
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

            <!-- Footer -->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 text-center">
                            2025 © DỰ ÁN TỐT NGHIỆP CAO ĐẲNG FPT THANH HÓA
                            <iconify-icon icon="iconamoon:heart-duotone"
                                class="fs-18 align-middle text-danger"></iconify-icon>
                            <a href="#" class="fw-bold footer-text" target="_blank">NHÓM 4</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const questionTypeSelect = document.getElementById('question_type');

        function toggleAnswerType() {
            const answerInputs = document.querySelectorAll('.correct-answer');
            const isMcq = questionTypeSelect.value === 'mcq';

            answerInputs.forEach(input => {
                input.type = isMcq ? 'radio' : 'checkbox';
                input.name = isMcq ? 'correct_answer' : 'correct_answer[]';
                if (isMcq) input.required = true;
                else input.required = false;
            });
        }

        questionTypeSelect.addEventListener('change', toggleAnswerType);
        toggleAnswerType();

        // Handle question deletion
        document.querySelectorAll('.sweetalert-params-question').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Bạn có chắc chắn?',
                    text: 'Bạn sẽ không thể hoàn tác hành động này!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Vâng, xóa nó!',
                    cancelButtonText: 'Không, hủy!',
                    confirmButtonClass: 'btn btn-primary w-xs me-2 mt-2',
                    cancelButtonClass: 'btn btn-danger w-xs mt-2',
                    buttonsStyling: false
                }).then(result => {
                    if (result.isConfirmed) {
                        Swal.fire('Đã xóa!', 'Câu hỏi đã được xóa.', 'success');
                    }
                });
            });
        });


        document.addEventListener('DOMContentLoaded', function() {
            let answerIndex = 2; // E = 4
            const letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

            const answersContainer = document.getElementById('answers_mcq');
            const addAnswerBtn = document.getElementById('add-answer-btn');

            addAnswerBtn.addEventListener('click', function() {
                const div = document.createElement('div');
                div.classList.add('mb-2');

                div.innerHTML = `
                    <div class="input-group input-group-sm answer-item">
                        <span class="input-group-text">${letters[answerIndex]}</span>
                        <input type="text" class="form-control" placeholder="Nhập đáp án ${letters[answerIndex]}" name="answers[]" required>
                         <button type="button" class="btn btn-sm btn-danger btn-remove-answer">
                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"></iconify-icon>
                        </button>
                        <div class="input-group-text">
                            <input class="form-check-input mt-0 correct-answer" type="radio" name="correct_answer" value="${answerIndex}">
                        </div>

                    </div>
                `;
                answersContainer.appendChild(div);
                answerIndex++;
            });

            // Xử lý nút xóa đáp án
            answersContainer.addEventListener('click', function(e) {
                if (e.target.closest('.btn-remove-answer')) {
                    const answerItem = e.target.closest('.answer-item');
                    if (answerItem) {
                        answerIndex = 2;
                        answerItem.parentElement.remove(); // xóa toàn bộ dòng
                    }
                }
            });
        });
    </script>
@endpush
