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
                            <li><a href="{{ route('client.information') }}" data-section="dashboard" class="active"><i>📊</i>Dashboard</a></li>
                            <li><a href="{{ route('client.schedule') }}" data-section="schedule"><i>📅</i> Lịch học</a></li>
                            <li><a href="{{ route('client.score') }}" data-section="grades"><i>📚</i> Điểm Số</a></li>
                            <li><a href="{{ route('client.quizz') }}" data-section="quizzes"><i>📝</i> Bài Quiz</a></li>
                            <li><a href="{{ route('client.account') }}" data-section="account"><i>👤</i> Thông Tin Tài Khoản</a></li>

                        @elseif (Auth::user()->role == 'teacher')
                            <li><a href="" data-section="dashboard" class="active"><i>📊</i>Dashboard</a></li>
                            <li><a href="" data-section="schedule"><i>📅</i> Lịch dạy</a></li>
                            <li><a href="" data-section="grades"><i>📚</i>Thêm điểm</a></li>
                            <li><a href="" data-section="quizzes"><i>📝</i> Bài Quiz</a></li>
                            <li><a href="" data-section="account"><i>👤</i> Thông Tin Tài Khoản</a></li>
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

        // Load active section from localStorage on page load
        document.addEventListener('DOMContentLoaded', function() {
            const savedSection = localStorage.getItem('activeSection') || 'dashboard';
            setActiveSection(savedSection);
        });

        // Handle sidebar navigation
        document.querySelectorAll('.sidebar-myAccount a').forEach(link => {
            link.addEventListener('click', function(e) {
                const sectionId = this.getAttribute('data-section');
                setActiveSection(sectionId);
            });
        });
    </script>
@endpush
