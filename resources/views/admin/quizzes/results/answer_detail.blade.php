@extends('admin.admin')

@section('title', 'Chi tiết câu trả lời')
@section('description', '')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb py-0 mb-3">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.quizz') }}">Quản lý Quiz</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.quizzes.results', ['id' => $quiz->id]) }}">Kết quả theo Lớp</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.quizzes.results.class', ['id' => $quiz->id, 'class' => $class->id]) }}">Học viên trong Lớp</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.quizzes.results.class.student', ['id' => $quiz->id,'class' => $class->id, 'student' => $student->id]) }}">Kết quả các lần làm Quiz</a></li>
                <li class="breadcrumb-item active" aria-current="page">Chi tiết câu trả lời</li>
            </ol>
        </nav>

        <!-- Summary Card -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-dark d-flex justify-content-between align-items-center py-2">
                <h4 class="card-title mb-0 fs-5 text-light">Chi tiết câu trả lời</h4>
                <a href="{{ route('admin.quizzes.results.class.student', ['id' => $quiz->id,'class' => $class->id, 'student' => $student->id]) }}" class="btn btn-secondary btn-sm d-flex align-items-center gap-1">
                    <iconify-icon icon="solar:arrow-left-broken" class="fs-16"></iconify-icon> Quay lại
                </a>
            </div>
            <div class="card-body py-2">
                <div class="row mb-4">
                    <div class="col-md-8">
                        <p class="mb-1 fs-5"><strong>Học viên:</strong> {{ $student->name }}</p>
                        <p class="mb-1 fs-5"><strong>Quiz:</strong> {{ $quiz->title }}</p>
                        <p class="mb-1 fs-5"><strong>Thời gian làm bài:</strong> {{ $attempt->duration_minutes }} phút</p>
                        <p class="mb-1 fs-5"><strong>Hoàn thành lúc:</strong> {{ $attempt->completed_date }}</p>
                        <p class="mb-1 fs-5"><strong>Tổng điểm:</strong> {{ $attempt->score }}</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        @php
                            $correctPercent = $attempt->total_questions > 0
                                ? round(($attempt->total_correct / $attempt->total_questions) * 100, 2)
                                : 0;
                        @endphp
                        <h5 class="mb-1 fs-5"><strong>Số câu đúng:</strong> {{ $attempt->total_correct }} / {{ $attempt->total_questions }}</h5>
                        <div class="progress mb-1" style="height: 6px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $correctPercent }}%;"
                                aria-valuenow="{{ $correctPercent }}" aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                        <small class="text-muted fs-5">Tỷ lệ đúng: {{ $correctPercent }}%</small>
                    </div>
                </div>

            </div>
        </div>




        @foreach ($allQuestions as $key => $question)
            @if ($question->question_type === 'multiple_choice')
                @php
                    // Lấy danh sách answer_id mà học viên đã chọn cho câu hỏi này
                    $studentAnswerIds = collect($studentMcAnswers)
                        ->where('question_id', $question->id)
                        ->pluck('answer_id')
                        ->toArray();

                    // Kiểm tra học viên trả lời đúng hết chưa
                    $correctAnswerIds = collect($answers)
                        ->where('question_id', $question->id)
                        ->where('is_correct', 1)
                        ->pluck('id')
                        ->toArray();

                    $isCorrect = !array_diff($correctAnswerIds, $studentAnswerIds) && !array_diff($studentAnswerIds, $correctAnswerIds);
                @endphp

                <div class="card mb-3 shadow-sm {{ $isCorrect ? 'border-success border-2' : 'border-danger border-2' }}">
                    <div class="card-header border-bottom py-1 d-flex justify-content-between align-items-center">
                        <div class="fs-5"><strong>Câu {{ $key + 1 }} ({{ $question->points }} điểm):</strong> {{ $question->content }}</div>
                        <span class="badge {{ $isCorrect ? 'bg-success' : 'bg-danger' }} text-white py-1 px-2 d-flex align-items-center gap-1">
                            <iconify-icon icon="solar:{{ $isCorrect ? 'check' : 'close' }}-circle-broken" class="fs-16"></iconify-icon> {{ $isCorrect ? 'Đúng' : 'Sai' }}
                        </span>
                    </div>
                    <div class="card-body py-2">
                        <ul class="list-unstyled mb-2">
                            @foreach ($answers->where('question_id', $question->id) as $answer)
                                @php
                                    $isSelected = in_array($answer->id, $studentAnswerIds);
                                    $isAnswerCorrect = $answer->is_correct == 1;
                                @endphp
                                <li class="mb-1 d-flex align-items-center">
                                    <input type="{{ $question->type === 'single' ? 'radio' : 'checkbox' }}"
                                        class="form-check-input me-2"
                                        disabled
                                        {{ $isSelected ? 'checked' : '' }}>

                                    <span class="fs-5 {{ $isAnswerCorrect ? 'text-success' : ($isSelected ? 'text-danger' : '') }}">
                                        {{ $answer->content }}
                                        @if ($isSelected && $isAnswerCorrect)
                                            <iconify-icon icon="solar:check-circle-broken" class="fs-14 text-success ms-1"></iconify-icon>
                                        @elseif ($isSelected && !$isAnswerCorrect)
                                            <iconify-icon icon="solar:close-circle-broken" class="fs-14 text-danger ms-1"></iconify-icon>
                                        @elseif (!$isSelected && $isAnswerCorrect)
                                            <iconify-icon icon="solar:check-circle-line-duotone" class="fs-14 text-muted ms-1"></iconify-icon>
                                        @endif
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                        <div class="alert alert-info mb-0 py-1 px-2 fs-5">
                            <strong>Giải thích:</strong> {{ $question->explanation ?? 'Không có giải thích' }}
                        </div>
                    </div>
                </div>
            @elseif ($question->question_type === 'fill_blank')
                @php
                    // Lấy câu trả lời tương ứng với câu hỏi hiện tại
                    $studentAnswer = $studentSentenceAnswers
                        ->firstWhere('question_id', $question->id);

                    $isCorrect = $studentAnswer?->is_correct == 1;
                    $studentContent = $studentAnswer?->user_answer ?? 'Chưa trả lời';
                @endphp

                <div class="card mb-3 shadow-sm border-{{ $isCorrect ? 'success' : 'danger' }} border-2">
                    <div class="card-header border-bottom py-1 d-flex justify-content-between align-items-center">
                        <div class="fs-5"><strong>Câu {{ $key + 1 }} ({{ $question->points }} điểm):</strong> {{ $question->prompt }}</div>
                        <span class="badge bg-{{ $isCorrect ? 'success' : 'danger' }} text-white py-1 px-2 d-flex align-items-center gap-1">
                            <iconify-icon icon="solar:{{ $isCorrect ? 'check-circle-broken' : 'close-circle-broken' }}" class="fs-16"></iconify-icon>
                            {{ $isCorrect ? 'Đúng' : 'Sai' }}
                        </span>
                    </div>
                    <div class="card-body py-2">
                        <p class="mb-1 fs-5">
                            <strong>Học viên trả lời:</strong>
                            <span class="text-{{ $isCorrect ? 'success' : 'danger' }}">
                                {{ $studentContent }}
                                <iconify-icon icon="solar:{{ $isCorrect ? 'check-circle-broken' : 'close-circle-broken' }}" class="fs-14 text-{{ $isCorrect ? 'success' : 'danger' }}"></iconify-icon>
                            </span>
                        </p>
                        <p class="mb-2 fs-5">
                            <strong>Đáp án đúng:</strong>
                            <span class="text-success">
                                {{ $question->correct_answer }}
                                <iconify-icon icon="solar:check-circle-broken" class="fs-14 text-success"></iconify-icon>
                            </span>
                        </p>
                        <div class="alert alert-info mb-0 py-1 px-2 fs-5">
                            <strong>Giải thích:</strong> {{ $question->explanation ?? 'Không có giải thích' }}
                        </div>
                    </div>
                </div>
            @endif


        @endforeach

    </div>
</div>
@endsection
