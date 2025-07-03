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
</head>

<body>
    <!-- Header với thông tin học sinh và quiz -->
    <div class="quiz-info-header">
        <div class="info-container d-flex">
            <div class="student-info">
                <div class="student-avatar">
                    <i class="icofont-user"></i>
                </div>
                <div class="student-details">
                    <h6>Nguyễn Văn A</h6>
                    <small>Lớp 12A1 - MSSV: SV001</small>
                </div>
            </div>

            <div class="quiz-stats">
                <div class="stat-item">
                    <span class="stat-number" id="totalQuestions">7</span>
                    <div class="stat-label">Câu hỏi</div>
                </div>
                <div class="stat-item">
                    <span class="stat-number" id="answered">0</span>
                    <div class="stat-label">Đã làm</div>
                </div>
            </div>

            <div class="timer-container" id="timerContainer">
                <i class="icofont-clock-time"></i>
                <span class="timer-display" id="timerDisplay">15:00</span>
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
                <i class="icofont-graduate-alt"></i> Bài Kiểm Tra Toán - Tiếng Anh
            </h1>
            <p class="quiz-subtitle">Thời gian làm bài: 15 phút | Tổng số câu: 7 câu</p>

            <div class="progress-container">
                <div class="progress-bar" id="progressBar"></div>
            </div>
            <small class="text-muted">Tiến độ hoàn thành: <span id="progressText">0/7</span></small>
        </div>

        <form action="/submit-quiz" method="POST" id="quizForm" style="display: none;">
            <!-- Câu 1 -->
            <div class="question-card fade-in">
                <div class="question-header">
                    <div class="question-number">1</div>
                    <div>Tính giá trị của biểu thức: 2x + 3y khi x = 2 và y = 4?</div>
                </div>
                <div class="options-grid">
                    <div class="option-item" onclick="selectOption(this)">
                        <input type="radio" name="q1" value="16"> 16
                    </div>
                    <div class="option-item" onclick="selectOption(this)">
                        <input type="radio" name="q1" value="18"> 18
                    </div>
                    <div class="option-item" onclick="selectOption(this)">
                        <input type="radio" name="q1" value="20"> 20
                    </div>
                    <div class="option-item" onclick="selectOption(this)">
                        <input type="radio" name="q1" value="22"> 22
                    </div>
                </div>
            </div>

            <!-- Câu 2 -->
            <div class="question-card fade-in">
                <div class="question-header">
                    <div class="question-number">2</div>
                    <div>Chọn tất cả các số nguyên tố trong các số sau:</div>
                </div>
                <div class="options-grid">
                    <div class="option-item" onclick="toggleCheckbox(this)">
                        <input type="checkbox" name="q2[]" value="2"> 2
                    </div>
                    <div class="option-item" onclick="toggleCheckbox(this)">
                        <input type="checkbox" name="q2[]" value="4"> 4
                    </div>
                    <div class="option-item" onclick="toggleCheckbox(this)">
                        <input type="checkbox" name="q2[]" value="7"> 7
                    </div>
                    <div class="option-item" onclick="toggleCheckbox(this)">
                        <input type="checkbox" name="q2[]" value="9"> 9
                    </div>
                </div>
            </div>

            <!-- Câu 3 -->
            <div class="question-card fade-in">
                <div class="question-header">
                    <div class="question-number">3</div>
                    <div>Điền giá trị của x trong phương trình: x + 5 = 10</div>
                </div>
                <input type="text" name="q3" class="form-control w-100" placeholder="Nhập đáp án"
                    oninput="updateProgress()">
            </div>

            <!-- Câu 4 - Click to move -->
            <div class="question-card fade-in">
                <div class="question-header">
                    <div class="question-number">4</div>
                    <div>Sắp xếp các từ sau thành câu hoàn chỉnh:</div>
                </div>
                <div class="word-sorting">
                    <div class="instruction-text">Click các từ để thêm vào câu, click lại để xóa:</div>

                    <div class="word-bank" id="wordBank4">
                        <div class="word-item" data-word="tôi" onclick="moveWord(this, 'sentence4')">tôi</div>
                        <div class="word-item" data-word="thích" onclick="moveWord(this, 'sentence4')">thích</div>
                        <div class="word-item" data-word="học" onclick="moveWord(this, 'sentence4')">học</div>
                        <div class="word-item" data-word="toán" onclick="moveWord(this, 'sentence4')">toán</div>
                    </div>

                    <div class="words-container" id="sentence4">
                        <span class="instruction-text" style="color: #aaa;">Click các từ để thêm vào đây...</span>
                    </div>

                    <input type="hidden" name="q4" id="q4_answer">
                </div>
            </div>

            <!-- Câu 5 -->
            <div class="question-card fade-in">
                <div class="question-header">
                    <div class="question-number">5</div>
                    <div>Phương trình x² - 5x + 6 = 0 có nghiệm là:</div>
                </div>
                <div class="options-grid">
                    <div class="option-item" onclick="selectOption(this)">
                        <input type="radio" name="q5" value="1_6"> x = 1, x = 6
                    </div>
                    <div class="option-item" onclick="selectOption(this)">
                        <input type="radio" name="q5" value="2_3"> x = 2, x = 3
                    </div>
                    <div class="option-item" onclick="selectOption(this)">
                        <input type="radio" name="q5" value="-2_-3"> x = -2, x = -3
                    </div>
                    <div class="option-item" onclick="selectOption(this)">
                        <input type="radio" name="q5" value="1_-6"> x = 1, x = -6
                    </div>
                </div>
            </div>

            <!-- Câu 6 -->
            <div class="question-card fade-in">
                <div class="question-header">
                    <div class="question-number">6</div>
                    <div>He ___ (play) football every weekend.</div>
                </div>
                <input type="text" name="q6" class="form-control w-100" placeholder="Nhập từ đúng"
                    oninput="updateProgress()">
            </div>

            <!-- Câu 7 - Click to move -->
            <div class="question-card fade-in">
                <div class="question-header">
                    <div class="question-number">7</div>
                    <div>Sắp xếp các từ sau thành câu hoàn chỉnh:</div>
                </div>
                <div class="word-sorting">
                    <div class="instruction-text">Click các từ để thêm vào câu, click nút X để xóa:</div>

                    <div class="word-bank" id="wordBank7">
                        <div class="word-item" data-word="she" onclick="moveWord(this, 'sentence7')">she</div>
                        <div class="word-item" data-word="is" onclick="moveWord(this, 'sentence7')">is</div>
                        <div class="word-item" data-word="reading" onclick="moveWord(this, 'sentence7')">reading
                        </div>
                        <div class="word-item" data-word="a" onclick="moveWord(this, 'sentence7')">a</div>
                        <div class="word-item" data-word="book" onclick="moveWord(this, 'sentence7')">book</div>
                    </div>

                    <div class="words-container" id="sentence7">
                        <span class="instruction-text" style="color: #aaa;">Click các từ để thêm vào đây...</span>
                    </div>

                    <input type="hidden" name="q7" id="q7_answer">
                </div>
            </div>

            <div class="submit-container">
                <div class="mb-3">
                    <small class="text-muted">
                        <i class="icofont-info-circle"></i>
                        Hãy kiểm tra lại đáp án trước khi nộp bài
                    </small>
                </div>
                <button type="submit" class="btn btn-submit" id="submitBtn" disabled>
                    <i class="icofont-paper-plane"></i> Nộp Bài Quiz
                </button>
            </div>
        </form>
    </div>
    <script>
        let countdown = 3;
        let totalQuestions = 7;
        let timeLeft = 15 * 60; // 15 phút
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

            quizTimer = setInterval(() => {
                timeLeft--;

                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                timerDisplay.textContent =
                    `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

                if (timeLeft === 300) {
                    alert('⚠️ Còn lại 5 phút! Hãy kiểm tra và hoàn thành bài làm.');
                }

                if (timeLeft === 60) {
                    document.getElementById('timerContainer').style.background =
                        'linear-gradient(135deg, #dc3545, #c82333)';
                    alert('⚠️ Chỉ còn 1 phút! Nộp bài ngay!');
                }

                if (timeLeft <= 0) {
                    clearInterval(quizTimer);
                    alert('⏰ Hết thời gian! Bài làm sẽ được nộp tự động.');
                    document.getElementById('quizForm').submit();
                }
            }, 1000);
        }

        function selectOption(element) {
            const input = element.querySelector('input');
            if (input.type === 'radio') {
                const name = input.name;
                const allOptions = document.querySelectorAll(`input[name="${name}"]`);
                allOptions.forEach(opt => {
                    opt.closest('.option-item').classList.remove('selected');
                });

                input.checked = true;
                element.classList.add('selected');
            }
            updateProgress();
        }

        function toggleCheckbox(element) {
            const input = element.querySelector('input');
            input.checked = !input.checked;

            if (input.checked) {
                element.classList.add('selected');
            } else {
                element.classList.remove('selected');
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
            let answered = 0; // Biến đếm số câu đã làm

            // Kiểm tra các câu hỏi radio (chọn 1 đáp án)
            const radioGroups = ['q1', 'q5'];
            radioGroups.forEach(group => {
                if (document.querySelector(`input[name="${group}"]:checked`)) {
                    answered++;
                }
            });

            // Kiểm tra câu checkbox (chọn nhiều đáp án)
            if (document.querySelectorAll('input[name="q2[]"]:checked').length > 0) {
                answered++;
            }

            // Kiểm tra các input type="text" (câu điền từ)
            document.querySelectorAll('input[type="text"]').forEach(input => {
                if (input.value.trim() !== '') {
                    answered++;
                }
            });

            // Kiểm tra các input ẩn chứa câu trả lời kéo thả
            document.querySelectorAll('input[type="hidden"][id$="_answer"]').forEach(input => {
                if (input.value.trim() !== '') {
                    answered++;
                }
            });

            // Tính phần trăm hoàn thành
            const progress = (answered / totalQuestions) * 100;

            // Cập nhật thanh tiến độ và văn bản hiển thị
            document.getElementById('progressBar').style.width = progress + '%';
            document.getElementById('progressText').textContent = `${answered}/${totalQuestions}`;
            document.getElementById('answered').textContent = answered;

            // Cập nhật nút nộp bài
            const submitBtn = document.getElementById('submitBtn');
            if (progress === 100) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-check-circle"></i> Hoàn Thành - Nộp Bài';
            } else {
                submitBtn.disabled = false; // Cho phép nộp cả khi chưa hoàn thành hết
                submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Nộp Bài Quiz';
            }
        }


        window.addEventListener('load', () => {
            setTimeout(startCountdown, 1000);
        });

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('quizForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const form = document.getElementById('quizForm');
                const formData = new FormData(form);
                const data = Object.fromEntries(formData.entries());
                console.log(data);

                if (confirm('Bạn có chắc chắn muốn nộp bài? Hành động này không thể hoàn tác.')) {
                    clearInterval(quizTimer);

                    const submitBtn = document.getElementById('submitBtn');
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang Xử Lý...';
                    submitBtn.disabled = true;
                    setTimeout(() => {
                        const answered = document.getElementById('answered').textContent;
                        alert(
                            `🎉 Bài quiz đã được nộp thành công!\n\nKết quả:\n- Số câu đã làm: ${answered}/${totalQuestions}\n\nCảm ơn bạn đã tham gia!`);
                    }, 2000);
                }
            });
        });

        // Add keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'Enter') {
                document.getElementById('submitBtn').click();
            }

            if (e.key === 'Escape') {
                document.querySelectorAll('.option-item.selected').forEach(item => {
                    item.classList.remove('selected');
                    const input = item.querySelector('input');
                    if (input) input.checked = false;
                });
                updateProgress();
            }
        });
    </script>
</body>

</html>
