@extends('client.client')

@section('title', 'Trang chủ')
@section('description', '')

@section('content')

    <main>
        <div class="section-bg hero-bg background-image"
            style="background-image: url('{{ asset('client/images/hero/home-1/hero-bg.png') }}');">
            <div class="my-account container">
                <div class="sidebar-myAccount">
                    <h2>My Account</h2>
                    <ul>
                        @if (Auth::user()->role == 'student')
                            <li><a href="{{ route('client.information') }}" data-section="dashboard" class="active">
                                <i class="icofont-chart-bar-graph"></i>Dashboard</a>
                            </li>
                            <li><a href="{{ route('client.schedule') }}" data-section="schedule">
                                <i class="icofont-calendar"></i> Lịch học</a>
                            </li>
                            <li><a href="{{ route('client.score') }}" data-section="grades">
                                <i class="icofont-book-alt"></i>Điểm Số</a>
                            </li>
                            <li><a href="{{ route('client.quizz') }}" data-section="quizzes">
                                <i class="icofont-pencil-alt-2"></i> Bài Quiz</a>
                            </li>
                            <li><a href="{{ route('client.account') }}" data-section="account">
                                <i class="icofont-user"></i>Thông Tin Tài Khoản</a>
                            </li>
                        @elseif (Auth::user()->role == 'teacher')
                           <li><a href="{{ route('client.information') }}" data-section="dashboard" class="active">
                                <i class="icofont-chart-bar-graph"></i>Dashboard</a>
                            </li>
                            <li><a href="{{ route('client.schedule') }}" data-section="schedule">
                                <i class="icofont-calendar"></i> Lịch dạy</a>
                            </li>
                            <li><a href="{{ route('client.score') }}" data-section="grades">
                                <i class="icofont-book-alt"></i>Quản lý điểm</a>
                            </li>
                            <li><a href="{{ route('client.quizz') }}" data-section="quizzes">
                                <i class="icofont-pencil-alt-2"></i> Bài Quiz</a>
                            </li>
                            <li><a href="{{ route('client.account') }}" data-section="account">
                                <i class="icofont-user"></i>Thông Tin Tài Khoản</a>
                            </li>
                        @endif
                    </ul>

                </div>
                <div class="content">
                    @yield('content-information')
                </div>
            </div>
        </div>


    </main>


@endsection

@push('script')
    <script>
        // Function to set active menu and content
        function setActiveSection(sectionId) {
            // Remove active class from all links and sections
            document.querySelectorAll('.sidebar-myAccount a').forEach(link => {
                link.classList.remove('active');
            });
            document.querySelectorAll('.content-section').forEach(section => {
                section.classList.remove('active');
            });

            // Add active class to the selected link and section
            const activeLink = document.querySelector(`.sidebar-myAccount a[data-section="${sectionId}"]`);
            const activeSection = document.getElementById(sectionId);
            if (activeLink && activeSection) {
                activeLink.classList.add('active');
                activeSection.classList.add('active');
            }


            // Save active section to localStorage
            localStorage.setItem('activeSection', sectionId);
        }


        const savedSection = localStorage.getItem('activeSection') || 'dashboard';
        setActiveSection(savedSection);



        // Handle sidebar navigation
        document.querySelectorAll('.sidebar-myAccount a').forEach(link => {
            link.addEventListener('click', function(e) {
                const sectionId = this.getAttribute('data-section');
                setActiveSection(sectionId);
            });
        });



        // Hàm hiển thị popup thông báo
        function showAlert(title, text, callback) {
            const alertBox = document.getElementById('customAlert');
            const alertTitle = document.getElementById('alertTitle');
            const alertText = document.getElementById('alertText');
            const alertBtn = document.getElementById('alertBtn');

            alertTitle.textContent = title || 'Thông báo';
            alertText.textContent = text || '';
            alertBox.classList.add('show');
            alertBox.setAttribute('aria-hidden', 'false');

            function closeAlert() {
                alertBox.classList.remove('show');
                alertBox.setAttribute('aria-hidden', 'true');
                alertBtn.removeEventListener('click', closeAlert);
                if (callback) callback();
            }

            alertBtn.addEventListener('click', closeAlert);

            // Tự động đóng khi nhấn ESC
            function onKeyDown(e) {
                if (e.key === 'Escape') {
                    closeAlert();
                    document.removeEventListener('keydown', onKeyDown);
                }
            }
            document.addEventListener('keydown', onKeyDown);

            // Đóng khi click bên ngoài
            function onBackdropClick(e) {
                if (e.target === alertBox) {
                    closeAlert();
                    alertBox.removeEventListener('click', onBackdropClick);
                }
            }
            alertBox.addEventListener('click', onBackdropClick);
        }
    </script>
@endpush
