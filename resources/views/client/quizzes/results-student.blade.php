<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Result Modal</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('client/results-student.css') }}" />
    <link rel="stylesheet" href="{{ asset('client/plugins/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('client/plugins/css/icofont.css') }}" />
</head>

<body>
    <div class="content">
        <div class="header">
            <div class="floating-particles">
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
            </div>
            <h5 class="title text-center d-flex align-items-center justify-content-center gap-2">
                <i class="icofont-trophy"></i> Kết Quả: {{ $quiz->title }}
            </h5>
        </div>

        <div class="score-container text-center">
            <div class="score-display">
                @php
                    $correctPercent =
                        $quizAttempts->total_questions > 0
                            ? round(($quizAttempts->total_correct / $quizAttempts->total_questions) * 100, 2)
                            : 0;
                @endphp
                <div class="progress-container">
                    <div class="progress-bar" style="width:{{ $correctPercent }}%;"></div>
                </div>
                <div class="score-percentage">
                    {{ $correctPercent }}%;
                </div>
            </div>
            @php
                $score = $quizAttempts->score;
                if ($score >= 9) {
                    $class = 'excellent';
                    $text = 'Thành Tích Vượt Trội';
                } elseif ($score >= 7) {
                    $class = 'good';
                    $text = 'Thành Tích Tốt';
                } elseif ($score >= 5) {
                    $class = 'average';
                    $text = 'Cần Cải Thiện';
                } else {
                    $class = 'poor';
                    $text = 'Cần Cố Gắng Hơn';
                }
            @endphp

            <div class="performance-tag {{ $class }}">
                <i class="icofont-star"></i> {{ $text }}
            </div>

        </div>

        <div class="stats-grid">
            <div class="row row-cols-2 g-3">
                <div class="col">
                    <div class="stat-card">
                        <div class="stat-icon text-success"><i class="icofont-check"></i></div>
                        <div>
                            <div class="stat-value">
                                {{ $quizAttempts->total_correct }}/{{ $quizAttempts->total_questions }}</div>
                            <div class="stat-label">Câu Đúng</div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="stat-card">
                        <div class="stat-icon text-danger"><i class="icofont-close"></i></div>
                        <div>
                            <div class="stat-value">
                                {{ $quizAttempts->total_questions - $quizAttempts->total_correct }}/{{ $quizAttempts->total_questions }}
                            </div>
                            <div class="stat-label">Câu Sai</div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="icofont-clock-time"></i></div>
                        <div>
                            @php
                                $start = strtotime($quizAttempts->start_time);
                                $end = strtotime($quizAttempts->submit_time);
                                $duration = $end - $start;
                            @endphp

                            <div class="stat-value">{{ gmdate('i:s', $duration) }}</div>
                            <div class="stat-label">Thời Gian</div>

                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="stat-card">
                        <div class="stat-icon text-warning"><i class="icofont-star"></i></div>
                        <div>
                            <div class="stat-value">{{ $quizAttempts->score }}</div>
                            <div class="stat-label">Điểm Số</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer d-flex justify-content-center gap-3 flex-column flex-sm-row">
            <a href="{{ route('student.quizzes.start', ['id'=> $quiz->id]) }}"
                class="btn btn-outline-secondary d-flex align-items-center justify-content-center gap-2">
                <i class="icofont-redo"></i> Làm Lại
            </a>
            <a href="{{ route('client.quizz') }}"
                class="btn btn-primary-custom d-flex align-items-center justify-content-center gap-2">
                <span><i class="icofont-share"></i> Tiếp Tục</span>
            </a>
        </div>
    </div>

    <script src="{{ asset('client/plugins/js/bootstrap.min.js') }}"></script>

</body>

</html>
