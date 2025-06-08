@extends('client.client')

@section('title', 'Trang chủ')
@section('description', '')
@section('content')
    <style>


        .my-account {
            display: flex;
            min-height: 100vh;
        }

        .sidebar-myAccount {
            width: 250px;
            background: white;
            /* Dark teal */
            color: rgb(0, 0, 0);
            padding: 20px;
            height: 80vh;
            position: fixed;
            border-radius: 8px;

        }

        .sidebar-myAccount h2 {
            margin-bottom: 30px;
            font-size: 24px;
            text-align: center;
            color: #000000;
            /* Light teal accent */
        }

        .sidebar-myAccount ul {
            list-style: none;
        }

        .sidebar-myAccount ul li {
            margin: 20px 0;
        }

        .sidebar-myAccount ul li a {
            color: rgb(0, 0, 0);
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 10px;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .sidebar-myAccount ul li a:hover,
        .sidebar-myAccount ul li a.active {
            background: #f0f0f0;
            /* Slightly lighter teal */
        }

        .sidebar-myAccount ul li a i {
            margin-right: 10px;
        }

        .content {
            margin-left: 250px;
            padding-left: 20px;
            width: calc(100% - 250px);
        }

        .content h1 {
            margin-bottom: 20px;
            color: #004d66;
        }

        .content-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: none;
        }

        .content-section.active {
            display: block;
        }

        .content-section table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .content-section table th,
        .content-section table td {
            padding: 12px;
            border: 1px solid #b3e0f2;
            text-align: left;
        }

        .content-section table th {
            background: #004d66;
            color: white;
        }

        .quiz-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .quiz-item {
            background: #e6ecef;
            padding: 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .quiz-item:hover {
            background: #b3e0f2;
        }

        .quiz-item.active {
            background: #006d8f;
            color: white;
        }

        @media (max-width: 768px) {
            .sidebar-myAccount {
                width: 200px;
            }

            .content {
                margin-left: 200px;
                width: calc(100% - 200px);
            }
        }

        @media (max-width: 576px) {
            .sidebar-myAccount {
                display: none;
            }

            .content {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>

    <main>
        <div class="section-bg hero-bg background-image"
            style="background-image: url('{{ asset('client/images/hero/home-1/hero-bg.png') }}');">

            <div class="my-account container">
                <div class="sidebar-myAccount">
                    <h2>My Account</h2>
                    <ul>
                        <li><a href="#dashboard" class="active"><i>📊</i> Dashboard</a></li>
                        <li><a href="#schedule"><i>📅</i> Thời Khóa Biểu</a></li>
                        <li><a href="#grades"><i>📚</i> Điểm Số</a></li>
                        <li><a href="#quizzes"><i>📝</i> Bài Quiz</a></li>
                        <li><a href="#account"><i>👤</i> Thông Tin Tài Khoản</a></li>
                    </ul>
                </div>
                <div class="content">
                    <div id="dashboard" class="content-section active">
                        <h1>Dashboard</h1>
                        <p>Chào mừng bạn đến với trang quản lý tài khoản học sinh!</p>
                        <p>Ở đây bạn có thể xem thông tin thời khóa biểu, điểm số, làm bài quiz và quản lý thông tin cá
                            nhân.</p>
                    </div>
                    <div id="schedule" class="content-section">
                        <h1>Thời Khóa Biểu</h1>
                        <table>
                            <tr>
                                <th>Thời Gian</th>
                                <th>Môn Học</th>
                                <th>Giáo Viên</th>
                                <th>Phòng</th>
                            </tr>
                            <tr>
                                <td>8:00 - 9:30</td>
                                <td>Toán</td>
                                <td>Nguyễn Văn A</td>
                                <td>A101</td>
                            </tr>
                            <tr>
                                <td>9:45 - 11:15</td>
                                <td>Văn</td>
                                <td>Trần Thị B</td>
                                <td>A102</td>
                            </tr>
                        </table>
                    </div>
                    <div id="grades" class="content-section">
                        <h1>Điểm Số</h1>
                        <table>
                            <tr>
                                <th>Môn Học</th>
                                <th>Điểm KT</th>
                                <th>Điểm Thi</th>
                                <th>Điểm TB</th>
                            </tr>
                            <tr>
                                <td>Toán</td>
                                <td>8.5</td>
                                <td>9.0</td>
                                <td>8.8</td>
                            </tr>
                            <tr>
                                <td>Văn</td>
                                <td>7.5</td>
                                <td>8.0</td>
                                <td>7.8</td>
                            </tr>
                        </table>
                    </div>
                    <div id="quizzes" class="content-section">
                        <h1>Bài Quiz</h1>
                        <div class="quiz-list">
                            <div class="quiz-item" data-quiz-id="1">Quiz Toán: Đại số cơ bản</div>
                            <div class="quiz-item" data-quiz-id="2">Quiz Văn: Phân tích tác phẩm</div>
                            <div class="quiz-item" data-quiz-id="3">Quiz Anh: Ngữ pháp nâng cao</div>
                        </div>
                        <div id="quiz-detail" style="margin-top: 20px;">
                            <p>Vui lòng chọn một bài quiz để xem chi tiết.</p>
                        </div>
                    </div>
                    <div id="account" class="content-section">
                        <h1>Thông Tin Tài Khoản</h1>
                        <p><strong>Họ Tên:</strong> Nguyễn Văn An</p>
                        <p><strong>Mã HS:</strong> HS001</p>
                        <p><strong>Lớp:</strong> 10A1</p>
                        <p><strong>Email:</strong> an.nguyen@example.com</p>
                        <p><strong>Số điện thoại:</strong> 0123 456 789</p>
                    </div>
                </div>
            </div>
        </div>


    </main>

@endsection
@push('script')
    <script>
        // Handle sidebar-myAccount navigation
        document.querySelectorAll('.sidebar-myAccount a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();

                // Remove active class from all links
                document.querySelectorAll('.sidebar-myAccount a').forEach(l => l.classList.remove(
                'active'));
                // Add active class to clicked link
                this.classList.add('active');

                // Hide all content sections
                document.querySelectorAll('.content-section').forEach(section => {
                    section.classList.remove('active');
                });

                // Show selected content section
                const sectionId = this.getAttribute('href').substring(1);
                document.getElementById(sectionId).classList.add('active');

                // Reset quiz selection when switching to quiz section
                if (sectionId === 'quizzes') {
                    document.querySelectorAll('.quiz-item').forEach(item => item.classList.remove(
                    'active'));
                    document.getElementById('quiz-detail').innerHTML =
                        '<p>Vui lòng chọn một bài quiz để xem chi tiết.</p>';
                }
            });
        });

        // Handle quiz selection
        document.querySelectorAll('.quiz-item').forEach(item => {
            item.addEventListener('click', function() {
                // Remove active class from all quiz items
                document.querySelectorAll('.quiz-item').forEach(i => i.classList.remove('active'));
                // Add active class to clicked item
                this.classList.add('active');

                // Display quiz details
                const quizId = this.getAttribute('data-quiz-id');
                const quizDetails = {
                    1: {
                        title: 'Quiz Toán: Đại số cơ bản',
                        description: 'Kiểm tra kiến thức về phương trình và bất phương trình.',
                        duration: '30 phút',
                        questions: '20 câu'
                    },
                    2: {
                        title: 'Quiz Văn: Phân tích tác phẩm',
                        description: 'Phân tích các tác phẩm văn học Việt Nam.',
                        duration: '45 phút',
                        questions: '15 câu'
                    },
                    3: {
                        title: 'Quiz Anh: Ngữ pháp nâng cao',
                        description: 'Luyện tập ngữ pháp tiếng Anh trình độ trung cấp.',
                        duration: '25 phút',
                        questions: '25 câu'
                    }
                };

                const detail = quizDetails[quizId];
                document.getElementById('quiz-detail').innerHTML = `
                    <h3>${detail.title}</h3>
                    <p><strong>Mô tả:</strong> ${detail.description}</p>
                    <p><strong>Thời gian:</strong> ${detail.duration}</p>
                    <p><strong>Số câu hỏi:</strong> ${detail.questions}</p>
                    <button onclick="alert('Chức năng làm bài quiz đang được phát triển!')">Làm bài</button>
                `;
            });
        });
    </script>
@endpush
