<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Start quizz</title>
    <link rel="stylesheet" href="{{ asset('client/plugins/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('client/plugins/css/icofont.css') }}" />
    <script src="{{ asset('client/plugins/js/jquery.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('client/start-quiz.css') }}" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>
    <!-- Header với thông tin học sinh và quiz -->
    <div class="quiz-info-header">
        <div class="info-container d-flex align-items-center">
            <div class="student-info">
                <div class="student-avatar">
                    <i class="icofont-user"></i>
                </div>
                <div class="student-details">
                    <h6>{{ Auth::user()->name }}</h6>
                    <small>{{ $classStudent->class_name }}</small>
                </div>
            </div>

            <div class="quiz-stats">
                <div class="stat-item">
                    <span class="stat-number"
                        id="totalQuestions">{{ $quiz->questions_count + $quiz->sentence_questions_count }}</span>
                    <div class="stat-label">Câu hỏi</div>
                </div>
                <div class="stat-item">
                    <span class="stat-number" id="answered">0</span>
                    <div class="stat-label">Đã làm</div>
                </div>
                <button class="stat-item btn btn-outline-secondary" id="btn-back-quiz">
                    <i class="icofont-reply"></i>
                    <span class="d-none d-sm-inline ms-1">Quay lại</span>
                </button>

            </div>

            <div class="timer-container" id="timerContainer">
                <i class="icofont-clock-time"></i>
                <span class="timer-display" id="timerDisplay">{{ $quiz->duration_minutes }}:00</span>
            </div>


        </div>
    </div>

    <!-- Overlay đếm ngược -->
    <div class="countdown-overlay" id="countdownOverlay">
        <div class="countdown-content">
            <h3>Chuẩn bị làm bài</h3>
            <div class="countdown-number" id="countdownNumber">3</div>
            <p>Quiz sẽ bắt đầu sau...</p>
        </div>
    </div>

    <div class="container main-content">
        <div class="quiz-header fade-in">
            <h1 class="quiz-title">
                <i class="icofont-graduate-alt"></i> {{ $quiz->title }}
            </h1>
            <p class="quiz-subtitle">Thời gian làm bài: {{ $quiz->duration_minutes }} phút | Tổng số câu:
                {{ $quiz->questions_count + $quiz->sentence_questions_count }} câu</p>

            <div class="progress-container">
                <div class="progress-bar" id="progressBar"></div>
            </div>
            <small class="text-muted">Tiến độ hoàn thành: <span
                    id="progressText">0/{{ $quiz->questions_count + $quiz->sentence_questions_count }}</span></small>
        </div>

        <form
            action="{{ route('student.quizzes.submit', ['quizId' => $quiz->id, 'classId' => $classStudent->class_id]) }}"
            method="POST" id="quizForm" style="display: none;">
            @csrf
            @method('POST')
            <input type="hidden" name="started_at" id="started_at">
            <input type="hidden" name="submitted_at" id="submitted_at">



            {{-- Kiểm tra xem loại câu hỏi là gì để biết được nên dùng form câu hỏi gì --}}
            @foreach ($allQuestions as $key => $question)
                @if ($question->question_type === 'multiple_choice')
                    {{-- Trắc nghiệm --}}
                    <div class="question-card fade-in">
                        <div class="question-header">
                            <div class="question-number">{{ $key + 1 }}</div>
                            <div>{{ $question->content }}</div>
                        </div>
                        <div class="options-grid">
                            @foreach ($answers->where('question_id', $question->id) as $answer)
                                @php
                                    $inputType = $question->type === 'single' ? 'radio' : 'checkbox';
                                    $inputName =
                                        $question->type === 'single' ? 'q' . ($key + 1) : 'q' . ($key + 1) . '[]';
                                @endphp

                                <div class="option-item" onclick="handleSelectOption(this)">
                                    <input type="{{ $inputType }}" name="{{ $inputName }}"
                                        value="{{ $answer->id }}"> {{ $answer->content }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                @elseif ($question->question_type === 'fill_blank')
                    @if ($question->type == 'fill')
                        {{-- Câu hỏi điền từ --}}
                        <div class="question-card fade-in">
                            <div class="question-header">
                                <div class="question-number">{{ $key + 1 }}</div>
                                <div>{{ $question->prompt }}</div>
                            </div>
                            <input type="text" name="{{ 'q' . ($key + 1) }}" class="form-control w-100"
                                placeholder="Nhập đáp án" oninput="updateProgress()">
                        </div>
                    @elseif($question->type == 'reorder')
                        {{-- Câu hỏi sắp xếp câu --}}
                        <div class="question-card fade-in">
                            <div class="question-header">
                                <div class="question-number">{{ $key + 1 }}</div>
                                <div>{{ $question->prompt }}</div>
                            </div>
                            <div class="word-sorting">
                                <div class="instruction-text">Click các từ để thêm vào câu, click lại để xóa:</div>

                                <div class="word-bank" id="wordBank{{ $question->id }}">
                                    @php
                                        $words = explode(' ', $question->correct_answer);
                                        shuffle($words);
                                    @endphp

                                    @foreach ($words as $word)
                                        <div class="word-item" data-word="{{ $word }}"
                                            onclick="moveWord(this, 'sentence{{ $question->id }}')">
                                            {{ $word }}
                                        </div>
                                    @endforeach
                                </div>

                                <div class="words-container" id="sentence{{ $question->id }}">
                                    <span class="instruction-text" style="color: #aaa;">Click các từ để thêm vào
                                        đây...</span>
                                </div>

                                <input type="hidden" name="{{ 'q' . ($key + 1) }}" id="q{{ $question->id }}_answer">
                            </div>
                        </div>
                    @endif
                @endif
            @endforeach



            <div class="submit-container">
                <div class="mb-3">
                    <small class="text-muted">
                        <i class="icofont-info-circle"></i>
                        Hãy kiểm tra lại đáp án trước khi nộp bài
                    </small>
                </div>
                <button type="button" class="btn btn-submit" id="submitBtn">
                    <i class="fas fa-check-circle"></i> Hoàn Thành - Nộp Bài
                </button>
            </div>
        </form>
    </div>
    <script>
        let countdown = 3;
        let totalQuestions = {{ $quiz->questions_count + $quiz->sentence_questions_count }};
        let timeLeft = {{ $quiz->duration_minutes }} * 60; // 15 phút
        let quizTimer;

        function startCountdown() {
            const countdownOverlay = document.getElementById('countdownOverlay');
            const countdownNumber = document.getElementById('countdownNumber');
            const quizForm = document.getElementById('quizForm');

            const countdownInterval = setInterval(() => {
                countdown--;
                countdownNumber.textContent = countdown;
                countdownNumber.style.animation = 'countdownBounce 1s ease-in-out';

                if (countdown <= 0) {
                    clearInterval(countdownInterval);
                    countdownOverlay.style.display = 'none';
                    quizForm.style.display = 'block';
                    startQuizTimer();
                    updateProgress();
                }
            }, 1000);
        }

        function startQuizTimer() {
            const timerDisplay = document.getElementById('timerDisplay');
            $('#started_at').val(getFormattedTime());

            quizTimer = setInterval(() => {
                timeLeft--;

                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                timerDisplay.textContent =
                    `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

                if (timeLeft === 300) {
                    alert('Còn lại 5 phút! Hãy kiểm tra và hoàn thành bài làm.');
                }

                if (timeLeft === 60) {
                    document.getElementById('timerContainer').style.background =
                        'linear-gradient(135deg, #dc3545, #c82333)';
                    alert('Chỉ còn 1 phút! Nộp bài ngay!');
                }

                if (timeLeft <= 0) {
                    clearInterval(quizTimer);
                    $('#submitted_at').val(getFormattedTime());
                    alert('Hết thời gian! Bài làm sẽ được nộp tự động.');
                    document.getElementById('quizForm').submit();
                }
            }, 1000);
        }

        function handleSelectOption(element) {
            const input = element.querySelector('input');

            if (!input) return;

            if (input.type === 'radio') {
                const name = input.name;
                const allOptions = document.querySelectorAll(`input[name="${name}"]`);
                allOptions.forEach(opt => {
                    const wrapper = opt.closest('.option-item');
                    if (wrapper) {
                        wrapper.classList.remove('selected');
                    }
                });

                input.checked = true;
                element.classList.add('selected');
            } else if (input.type === 'checkbox') {
                input.checked = !input.checked;

                if (input.checked) {
                    element.classList.add('selected');
                } else {
                    element.classList.remove('selected');
                }
            }

            updateProgress();
        }


        // Hàm chuyển từ từ ngân hàng sang câu trả lời
        function moveWord(element, containerId) {
            const container = document.getElementById(containerId); // Vùng câu trả lời
            const wordBank = document.getElementById(`wordBank${containerId.replace('sentence', '')}`); // Ngân hàng từ

            // Nếu từ đã tồn tại trong câu thì không thêm lại
            const existingWord = container.querySelector(`.word-item[data-word="${element.dataset.word}"]`);
            if (existingWord) return;

            // Xóa hướng dẫn nếu có
            const instructionText = container.querySelector('.instruction-text');
            if (instructionText) instructionText.remove();

            // Tạo phần tử từ mới với sự kiện click để xóa
            const wordElement = document.createElement('div');
            wordElement.className = 'word-item';
            wordElement.dataset.word = element.dataset.word;
            wordElement.textContent = element.dataset.word;
            wordElement.onclick = () => deleteWord(wordElement, containerId); // Click để xóa

            // Thêm từ vào câu
            container.appendChild(wordElement);

            // Xóa từ khỏi ngân hàng
            element.remove();

            // Cập nhật đáp án và tiến độ
            updateSentence(containerId);
            updateProgress();
        }

        // Hàm xóa từ khỏi câu và đưa lại về ngân hàng từ
        function deleteWord(wordItem, containerId) {
            const wordBank = document.getElementById(
                `wordBank${containerId.replace('sentence', '')}`); // Ngân hàng từ tương ứng

            // Tạo lại từ để đưa về ngân hàng
            const newWord = document.createElement('div');
            newWord.className = 'word-item';
            newWord.dataset.word = wordItem.dataset.word;
            newWord.textContent = wordItem.dataset.word;
            newWord.onclick = () => moveWord(newWord, containerId);

            // Thêm từ trở lại vào ngân hàng
            wordBank.appendChild(newWord);

            // Xóa từ khỏi vùng câu trả lời
            wordItem.remove();

            // Nếu câu trống thì thêm lại dòng hướng dẫn
            const container = document.getElementById(containerId);
            if (!container.querySelector('.word-item')) {
                container.innerHTML =
                    '<span class="instruction-text" style="color: #aaa;">Click các từ để thêm vào đây...</span>';
            }

            // Cập nhật lại nội dung câu và tiến độ
            updateSentence(containerId);
            updateProgress();
        }


        // Hàm cập nhật nội dung câu trả lời từ các từ đã chọn
        function updateSentence(containerId) {
            const questionNum = containerId.replace('sentence', ''); // Lấy số câu hỏi từ ID (vd: sentence4 → 4)
            const container = document.getElementById(containerId); // Vùng chứa các từ đã chọn để tạo câu

            // Lấy tất cả các từ trong vùng câu trả lời
            const words = Array.from(container.querySelectorAll('.word-item')).map(item => item.dataset.word);

            // Nối các từ lại thành một câu
            const sentence = words.join(' ');

            // Ghi câu đó vào input ẩn tương ứng để gửi về server
            document.getElementById(`q${questionNum}_answer`).value = sentence;

            // Cập nhật tiến độ làm bài
            updateProgress();
        }

        // Hàm kiểm tra tiến độ làm bài và cập nhật thanh tiến độ
        function updateProgress() {
            let answered = 0;

            // Lấy tất cả input trong form (radio, checkbox, text, hidden)
            const form = document.getElementById('quizForm');
            const inputs = form.querySelectorAll('input');

            // Tạo một Set để lưu các group name của radio/checkbox (tránh đếm trùng)
            const countedGroups = new Set();

            inputs.forEach(input => {
                const name = input.name;

                // Bỏ qua _token hoặc các name không phải question ID
                if (!name || name === '_token') return;

                // Xử lý radio và checkbox
                if ((input.type === 'radio' || input.type === 'checkbox') && name) {
                    if (!countedGroups.has(name)) {
                        const checkedInputs = form.querySelectorAll(`input[name="${name}"]:checked`);
                        if (checkedInputs.length > 0) {
                            answered++;
                        }
                        countedGroups.add(name);
                    }
                }

                // Xử lý text input
                if (input.type === 'text' && input.value.trim() !== '') {
                    answered++;
                }

                // Xử lý hidden input (kéo thả, reorder...)
                if (input.type === 'hidden' && name && input.id.endsWith('_answer')) {
                    if (input.value.trim() !== '') {
                        answered++;
                    }
                }
            });

            // Tính phần trăm hoàn thành
            const progress = (answered / totalQuestions) * 100;

            // Cập nhật thanh tiến độ và văn bản hiển thị
            document.getElementById('progressBar').style.width = progress + '%';
            document.getElementById('progressText').textContent = `${answered}/${totalQuestions}`;
            document.getElementById('answered').textContent = answered;


        }



        window.addEventListener('load', () => {
            setTimeout(startCountdown, 10);
        });


        $('#submitBtn').on('click', function(e) {
            e.preventDefault();

            const widthStr = $('#progressBar').css('width');
            console.log(widthStr);
            const parentWidthStr = $('#progressBar').parent().css('width');

            const width = parseFloat(widthStr);
            const parentWidth = parseFloat(parentWidthStr);

            const percent = (width / parentWidth) * 100;

            if (percent < 100) {
                Swal.fire({
                    title: 'Lưu ý!',
                    text: 'Bạn vẫn còn câu hỏi chưa hoàn thành! Bạn vẫn có thể nộp bài ngay.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '<i class="icofont-check"></i> Nộp luôn',
                    cancelButtonText: '<i class="icofont-reply"></i> Quay lại',
                    customClass: {
                        confirmButton: 'btn btn-primary w-xs mx-1',
                        cancelButton: 'btn btn-success w-xs mx-1'
                    },
                    buttonsStyling: false,
                    backdrop: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Hiện loading spinner trước khi submit
                        Swal.fire({
                            title: 'Đang nộp bài...',
                            html: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            backdrop: true,
                        });

                        // Gán thời gian và gửi form
                        $('#submitted_at').val(getFormattedTime());

                        // Cho delay nhỏ để hiển thị spinner trước khi submit
                        setTimeout(() => {
                            $('#quizForm').submit();
                        }, 200); // 200ms đảm bảo spinner kịp hiển thị
                    }
                });

            } else {
                Swal.fire({
                    title: 'Xác nhận nộp bài',
                    text: 'Bạn sắp nộp bài. Hệ thống sẽ lưu lại tất cả câu trả lời hiện tại. Bạn có chắc chắn muốn tiếp tục không?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: '<i class="icofont-check-circled"></i> Nộp bài',
                    cancelButtonText: '<i class="icofont-reply"></i> Kiểm tra lại',
                    customClass: {
                        confirmButton: 'btn btn-primary w-xs mx-1',
                        cancelButton: 'btn btn-outline-secondary w-xs mx-1'
                    },
                    buttonsStyling: false,
                    backdrop: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Hiện loading spinner trước khi submit
                        Swal.fire({
                            title: 'Đang nộp bài...',
                            html: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            backdrop: true,
                        });

                        // Gán thời gian và gửi form
                        $('#submitted_at').val(getFormattedTime());

                        // Cho delay nhỏ để hiển thị spinner trước khi submit
                        setTimeout(() => {
                            $('#quizForm').submit();
                        }, 200); // 200ms đảm bảo spinner kịp hiển thị
                    }
                });


            }

        });



        $("#btn-back-quiz").on("click", function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Lưu ý!',
                text: 'Dữ liệu sẽ không được lưu. Bạn có chắc chắn muốn quay lại không?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="icofont-sign-out"></i> Thoát',
                cancelButtonText: '<i class="icofont-reply"></i> Ở lại',
                customClass: {
                    confirmButton: 'btn btn-danger w-xs mx-1',
                    cancelButton: 'btn btn-secondary w-xs mx-1'
                },
                buttonsStyling: false,
                backdrop: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.history.back();
                }
            });
        });




        //hàm lấy thời gian hiện tại
        function getFormattedTime() {
            const now = new Date();

            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0'); // Tháng bắt đầu từ 0
            const day = String(now.getDate()).padStart(2, '0');

            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');

            return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
        }
    </script>
</body>

</html>
