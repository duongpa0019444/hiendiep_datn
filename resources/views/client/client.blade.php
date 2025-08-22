<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <meta name="description" content="@yield('description')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('client/images/logo-icon.png') }}">
    <meta name="keywords" content="@yield('keywords', 'Học tiếng Anh, Bài viết tiếng Anh, Tin tức tiếng Anh')">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ asset('client/plugins/css/bootstrap.min.css') }}" />
    <!-- Animate CSS -->
    <link rel="stylesheet" href="{{ asset('client/plugins/css/animate.min.css') }}" />
    <!-- Owl Carousel CSS -->
    <link rel="stylesheet" href="{{ asset('client/plugins/css/owl.carousel.min.css') }}" />
    <!-- Swiper Slider CSS -->
    <link rel="stylesheet" href="{{ asset('client/plugins/css/swiper-bundle.min.css') }}" />
    <!-- Maginific Popup CSS -->
    <link rel="stylesheet" href="{{ asset('client/plugins/css/maginific-popup.min.css') }}" />
    <!-- Nice Select CSS -->
    <link rel="stylesheet" href="{{ asset('client/plugins/css/nice-select.min.css') }}" />
    <!-- Icofont -->
    <link rel="stylesheet" href="{{ asset('client/plugins/css/icofont.css') }}" />
    <!-- Uicons -->
    <link rel="stylesheet" href="{{ asset('client/plugins/css/uicons.css') }}" />
    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('client/style.css') }}" />

    <!-- Font Awesome 6 CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <script src="{{ asset('client/plugins/js/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    @stack('styles')

</head>


<body class="element-wrapper">

    <!-- Preloader Start -->
    <!-- Start Preloader  -->
    <div id="preloader">
        <div id="ed-preloader" class="ed-preloader">
            <div class="animation-preloader">
                <div class="spinner"></div>
            </div>
        </div>
    </div>
    <!-- End Preloader -->



    <!-- Sidebar -->
    <div class="sidebar" id="classSidebar">
        <span class="close-btn" id="close-btn-sidebar">&times;</span>
        <div class="d-flex justify-content-between align-items-start mt-3">
            <h3 class="col-7">Danh sách lớp làm quiz</h3>
            <div class="d-flex justify-content-end align-items-center flex-wrap col-5" id="statistics">

            </div>
        </div>

        <ul class="class-list list-group" id="classList">

        </ul>
    </div>

    <div class="sidebar" id="sidebarResultStudent">
        <span class="close-btn" id="close-btn-sidebar-resultStudent">&times;</span>
        <div class="d-flex justify-content-between align-items-start mt-3">
            <h3 class="col-6" id="titleResultStudent"></h3>
            <div class="d-flex justify-content-end align-items-center flex-wrap col-6" id="statisticsResulltStudent">

            </div>
        </div>

        <ul class="class-list list-group">
            <ul class="student-list list-group" id="resultStudentList">

            </ul>

        </ul>
    </div>


    <!-- Start Mobile Menu Offcanvas -->
    <div class="modal mobile-menu-modal offcanvas-modal fade" id="offcanvas-modal">
        <div class="modal-dialog offcanvas-dialog">
            <div class="modal-content">
                <div class="modal-header offcanvas-header">
                    <!-- Mobile Menu Logo -->
                    <div class="offcanvas-logo">
                        <a href="{{ route('home') }}"><img src="{{ asset('client/images/logo.png') }}"
                                alt="#" /></a>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fi fi-ss-cross"></i>
                    </button>
                </div>
                <div class="mobile-menu-modal-main-body">
                    <!-- Mobile Menu Navigation -->
                    <nav class="offcanvas__menu">
                        <ul class="offcanvas__menu_ul">
                            <li class="offcanvas__menu_li">
                                <a class="offcanvas__menu_item" href="{{ route('home') }}">Trang chủ</a>

                            </li>

                            <li class="offcanvas__menu_li">
                                <a class="offcanvas__menu_item" href="{{ route('client.course') }}">Khóa học</a>

                            </li>


                            <li class="offcanvas__menu_li">
                                <a class="offcanvas__menu_item" href="{{ route('client.news') }}">Bài viết</a>
                                <ul class="offcanvas__sub_menu">
                                    @foreach (\DB::table('topics')->get() as $topic)
                                        <li class="offcanvas__sub_menu_li">
                                            <a href="{{ route('client.news.category', ['id' => $topic->id, 'slug' => \Illuminate\Support\Str::slug($topic->name)]) }}"
                                                class="offcanvas__sub_menu_item">
                                                {{ $topic->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>


                            <li class="offcanvas__menu_li">
                                <a class="offcanvas__menu_item active" href="{{ route('client.about') }}">Giới thiệu</a>

                            </li>

                            <li class="offcanvas__menu_li">
                                <a class="offcanvas__menu_item" href="{{ route('client.contacts') }}">Liên hệ</a>
                            </li>

                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- End Mobile Menu Offcanvas -->
    <!-- Start Header Area -->
    <header class="ed-header ed-header--style2">
        <div class="container ed-container-expand">
            <div class="ed-header__inner">
                <div class="row align-items-center">
                    <div class="col-lg-9 col-6">
                        <div class="ed-header__left--style2">
                            <div class="ed-header__left-widget--style2">
                                <!-- Logo  -->
                                <div class="ed-topbar__logo">
                                    <a href="{{ route('home') }}">
                                        <img src="{{ asset('client/images/logo.png') }}" alt="logo" />
                                    </a>
                                </div>


                            </div>

                            <!-- Navigation Menu -->
                            <nav class="ed-header__navigation">
                                <ul class="ed-header__menu">
                                    <li class="active">
                                        <a href="{{ route('home') }}">Trang chủ</a>

                                    </li>
                                    <li>
                                        <a href="{{ route('client.course') }}">Khóa học<i class=""></i></a>
                                    </li>

                                    <li>
                                        <a href="{{ route('client.news') }}">Bài viết<i
                                                class="fi fi-ss-angle-small-down"></i></a>
                                        <ul class="sub-menu">
                                            @foreach (\DB::table('topics')->get() as $topic)
                                                <li class="offcanvas__sub_menu_li">
                                                    <a href="{{ route('client.news.category', ['id' => $topic->id, 'slug' => \Illuminate\Support\Str::slug($topic->name)]) }}"
                                                        class="offcanvas__sub_menu_item">
                                                        {{ $topic->name }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="{{ route('client.about') }}">Giới thiệu</a>

                                    </li>
                                    <li>
                                        <a href="{{ route('client.contacts') }}">Liên hệ</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <!-- Header Right -->
                        <div class="ed-header__right">
                            <div class="ed-header__action">

                                <div class="ed-topbar__info-buttons">

                                    @guest
                                        <button type="button" class="" data-bs-toggle="modal"
                                            data-bs-target="#loginModal"><i class="icofont-user me-2"></i>
                                            Đăng nhập
                                        </button>
                                    @endguest

                                    @auth
                                        <div class="d-flex align-items-center gap-3">

                                            <div class="dropdown">
                                                <div class="d-flex justify-content-center align-items-center gap-2"
                                                    data-bs-toggle="dropdown" aria-expanded="false" role="button">
                                                    <span
                                                        class="fw-semibold d-none d-md-inline">{{ Auth::user()->name }}</span>

                                                    <div
                                                        class="avatar-dropdown-toggle d-flex align-items-center position-relative">
                                                        <img src="{{ Auth::user()->avatar ? asset(Auth::user()->avatar) : asset('icons/user-solid.svg') }}"
                                                            alt="Avatar" class="rounded-circle avatar-img ">
                                                        <span
                                                            class="dropdown-icon d-flex justify-content-center align-items-center">
                                                            <i class="icofont-rounded-down"></i>
                                                        </span>
                                                    </div>
                                                </div>

                                                <ul
                                                    class="dropdown-menu dropdown-menu-myaccount dropdown-menu-end shadow-sm mt-2 p-3 rounded-3 avatar-menu">
                                                    @if (Auth::user()->role == 'admin' || Auth::user()->role == 'staff')
                                                        <li>
                                                            <a href="{{ route('admin.dashboard') }}"
                                                                class="dropdown-item p-2" data-section="dashboard">
                                                                <i class="icofont-dashboard-web me-2"></i> Vào trang quản
                                                                trị
                                                            </a>
                                                        </li>
                                                    @else
                                                        <li>
                                                            <a href="{{ route('client.information') }}"
                                                                class="dropdown-item p-2" data-section="dashboard">
                                                                <i class="icofont-chart-bar-graph me-2"></i> Dashboard
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ route('client.schedule') }}"
                                                                class="dropdown-item p-2" data-section="schedule">
                                                                <i class="icofont-calendar me-2"></i>
                                                                {{ Auth::user()->role == 'teacher' ? 'Lịch dạy' : 'Lịch học' }}
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ route('client.score') }}"
                                                                class="dropdown-item p-2" data-section="grades">
                                                                <i class="icofont-book-alt me-2"></i> Điểm số
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ route('client.quizz') }}"
                                                                class="dropdown-item p-2" data-section="quizzes">
                                                                <i class="icofont-pencil-alt-2 me-2"></i> Bài Quiz
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ route('client.account') }}"
                                                                class="dropdown-item p-2" data-section="account">
                                                                <i class="icofont-user me-2"></i> Thông tin tài khoản
                                                            </a>
                                                        </li>
                                                    @endif
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('auth.logout') }}" method="GET"
                                                            class="px-3 m-0">
                                                            @csrf
                                                            <button type="submit"
                                                                class="btn btn-outline-danger btn-sm w-100">
                                                                <i class="icofont-logout me-1"></i> Đăng xuất
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>



                                            {{-- <form action="{{ route('auth.logout') }}" method="GET" style="margin: 0">
                                                @csrf
                                                <button type="submit" class="text-decoration-none">Logout</button>
                                            </form> --}}
                                        </div>
                                    @endauth

                                </div>

                            </div>


                            <!-- Mobile Menu Button -->
                            <button type="button" class="mobile-menu-offcanvas-toggler" data-bs-toggle="modal"
                                data-bs-target="#offcanvas-modal">
                                <span class="line"></span>
                                <span class="line"></span>
                                <span class="line"></span>
                            </button>
                            <!-- End Mobile Menu Button -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- End Header Area -->






    <div id="smooth-wrapper">
        <div id="smooth-content">

            @yield('content')

            <!-- Footer area start here -->
            <div class="footer-bg position-relative">
                <div class="footer-bg__img">
                    <img src="{{ asset('client/images/footer/footer-2/footer-bg.png') }}" alt="footer-bg-img" />
                </div>

                {{-- <!-- Start Call Action Area -->
                <section class="ed-call-action position-relative">
                    <div class="container ed-container">
                        <div class="ed-call-action__inner position-relative">
                            <div class="ed-call-action__shapes">
                                <img class="ed-call-action__shape-1 rotate-ani"
                                    src="{{ asset('client/images/call-action/call-action-1/shape-1.svg') }}"
                                    alt="shape-1" />
                                <img class="ed-call-action__shape-2"
                                    src="{{ asset('client/images/call-action/call-action-1/shape-2.svg') }}"
                                    alt="shape-2" />
                                <img class="ed-call-action__shape-3 updown-ani"
                                    src="{{ asset('client/images/call-action/call-action-1/shape-3.svg') }}"
                                    alt="shape-3" />
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-12">
                                    <div class="ed-call-action__img">
                                        <img src="{{ asset('client/images/call-action/call-action-1/call-action-img.png') }}"
                                            alt="call-action-img" />
                                    </div>
                                </div>
                                <div class="col-lg-6 col-12 order-class">
                                    <div class="ed-call-action__content">
                                        <div class="ed-section-head">
                                            <span class="ed-section-head__sm-title">GET STARTED NOW</span>
                                            <h3 class="ed-section-head__title">
                                                Affordable Your Online Courses <br />
                                                & Learning Opportunities
                                            </h3>
                                            <p class="ed-section-head__text">
                                                Excepteur sint occaecat cupidatat non proident sunt in culpa qui officia
                                                deserunt mollit. Excepteur sint occaecat.
                                            </p>
                                        </div>
                                        <div class="ed-call-action__content-btn">
                                            <a href="course-1.html" class="ed-btn"> Start Learning Today<i
                                                    class="fi fi-rr-arrow-small-right"></i> </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section> --}}
                <!-- End Call Action Area -->

                <!-- Start Footer Area -->
                <footer class="ed-footer position-relative">
                    <!-- Footer Top -->
                    <div class="ed-footer__top position-relative">
                        <div class="ed-footer__shapes">
                            <img class="ed-footer__shape-1"
                                src="{{ asset('client/images/footer/footer-1/shape-1.svg') }}" alt="shape-1" />
                            <img class="ed-footer__shape-2 rotate-ani"
                                src="{{ asset('client/images/footer/footer-1/shape-2.svg') }}" alt="shape-2" />
                            <img class="ed-footer__shape-3"
                                src="{{ asset('client/images/footer/footer-1/shape-3.svg') }}" alt="shape-3" />
                        </div>
                        <div class="container ed-container">
                            <div class="row g-0">
                                <!-- Giới thiệu -->
                                <div class="col-lg-4 col-md-6 col-12">
                                    <div class="ed-footer__widget ed-footer__about">
                                        <a href="{{ url('/') }}" class="ed-footer__logo">
                                            <img src="{{ asset('client/images/logo.png') }}" alt="footer-logo" />
                                        </a>
                                        <p class="ed-footer__about-text">
                                            Trung tâm Tiếng Anh Hiền Diệp cam kết mang đến môi trường học tập hiện đại,
                                            phương pháp giảng dạy tiên tiến và đội ngũ giáo viên tận tâm.
                                        </p>
                                        <ul class="ed-footer__about-social">
                                            <li><a href="https://www.facebook.com/" target="_blank"><img
                                                        src="{{ asset('client/images/icons/icon-dark-facebook.svg') }}"
                                                        alt="facebook" /></a></li>
                                            <li><a href="https://www.youtube.com/" target="_blank"><img
                                                        src="{{ asset('client/images/icons/icon-dark-twitter.svg') }}"
                                                        alt="youtube" /></a></li>
                                            <li><a href="https://www.instagram.com/" target="_blank"><img
                                                        src="{{ asset('client/images/icons/icon-dark-instagram.svg') }}"
                                                        alt="instagram" /></a></li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Liên kết -->
                                <div class="col-lg-2 col-md-6 col-12">
                                    <div class="ed-footer__widget">
                                        <h4 class="ed-footer__widget-title">Liên kết</h4>
                                        <ul class="ed-footer__widget-links">
                                            <li><a href="{{ url('/about') }}">Giới thiệu</a></li>
                                            <li><a href="{{ url('/courses') }}">Các khóa học</a></li>
                                            <li><a href="{{ url('/pricing') }}">Gói học phí</a></li>
                                            <li><a href="{{ url('/contact') }}">Liên hệ</a></li>
                                            <li><a href="{{ url('/news') }}">Tin tức</a></li>
                                            <li><a href="{{ url('/faq') }}">Câu hỏi thường gặp</a></li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Thông tin liên hệ -->
                                <div class="col-lg-3 col-md-6 col-12">
                                    <div class="ed-footer__widget contact-widget">
                                        <h4 class="ed-footer__widget-title">Thông tin liên hệ</h4>

                                        <div class="ed-footer__contact">
                                            <div class="ed-footer__contact-icon">
                                                <img src="{{ asset('client/images/icons/icon-phone-blue.svg') }}"
                                                    alt="phone" />
                                            </div>
                                            <div class="ed-footer__contact-info">
                                                <span>Hỗ trợ 24/7</span>
                                                <a href="tel:09888661153">0988 866 1153</a>
                                            </div>
                                        </div>

                                        <div class="ed-footer__contact">
                                            <div class="ed-footer__contact-icon">
                                                <img src="{{ asset('client/images/icons/icon-envelope-blue.svg') }}"
                                                    alt="email" />
                                            </div>
                                            <div class="ed-footer__contact-info">
                                                <span>Email</span>
                                                <a
                                                    href="mailto:hiendiepedu.edu@gmail.com">hiendiepedu.edu@gmail.com</a>
                                            </div>
                                        </div>

                                        <div class="ed-footer__contact">
                                            <div class="ed-footer__contact-icon">
                                                <img src="{{ asset('client/images/icons/icon-location-blue.svg') }}"
                                                    alt="location" />
                                            </div>
                                            <div class="ed-footer__contact-info">
                                                <span>Địa chỉ</span>
                                                <a href="#" target="_blank">984 Quang Trung 3, Đông Vệ, TP.
                                                    Thanh Hóa</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Đăng ký nhận tin -->
                                <div class="col-lg-3 col-md-6 col-12">
                                    <div class="ed-footer__widget newsletter-widget">
                                        <h4 class="ed-footer__widget-title">Đăng ký nhận tin</h4>
                                        <div class="ed-footer__newsletter">
                                            <p class="ed-footer__about-text">
                                                Nhập email của bạn để nhận những thông tin và khuyến mãi mới nhất từ
                                                trung tâm.
                                            </p>
                                            <form action="#" method="post" class="ed-footer__newsletter-form">
                                                <input type="email" name="email" placeholder="Nhập email của bạn"
                                                    required />
                                                <button type="submit" class="ed-btn">Đăng ký ngay <i
                                                        class="fi fi-rr-arrow-small-right"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Bottom -->
                    <div class="ed-footer__bottom">
                        <div class="container ed-container">
                            <div class="row">
                                <div class="col-12">
                                    <p class="ed-footer__copyright-text">
                                        © 2024 Trung tâm Tiếng Anh Hiền Diệp. Thiết kế & phát triển bởi <a
                                            href="#" target="_blank">Team tigerCode - FPL</a>.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>





                <!-- End Footer Area  -->
            </div> <!-- Footer area end here -->
        </div>
    </div>




    <!-- Start Login Modal -->
    <div class="modal fade ed-auth__modal" id="loginModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="ed-auth__modal-content modal-content">
                <button type="button" class="ed-auth__modal-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fi-rr-cross"></i>
                </button>

                <!-- Auth Head  -->
                <div class="ed-auth__modal-head">
                    <a href="{{ route('home') }}" class="ed-auth__modal-logo">
                        <img src="{{ asset('client/images/logo.png') }}" alt="logo" />
                    </a>
                    <h3 class="ed-auth__modal-title">Đăng Nhập</h3>

                </div>

                <!-- Auth Body  -->
                <div class="ed-auth__modal-body">
                    <div class="alert alert-danger" id="alert-login" role="alert" style="display: none">
                        Sai tên đăng nhập hoặc mật khẩu
                    </div>
                    <form action="{{ route('loginAuth') }}" method="POST" class="ed-auth__modal-form">
                        @csrf
                        <div class="form-group">
                            <input type="text" id="usernameLogin" name="username" placeholder="Tên đang nhập"
                                required />
                        </div>

                        <div class="form-group">
                            <input type="password" id="passwordLogin" name="password" placeholder="Mật khẩu"
                                required />
                        </div>

                        <div class=" mt-2">
                            <a href="{{ route('password.request') }}"
                                class="text-decoration-none text-primary fw-medium">
                                Quên mật khẩu?
                            </a>
                        </div>

                        <div class="ed-auth__form-btn d-flex justify-content-end">
                            <button id="btn-login" type="submit" class="ed-btn">Sign In<i
                                    class="fi fi-rr-arrow-small-right"></i></button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <!-- End Login Modal -->


    <!-- Start Sidebar  -->
    <div class="offcanvas offcanvas-end ed-sidebar" tabindex="-1" id="edSidebar"
        aria-labelledby="offcanvasRightLabel">
        <div class="ed-sidebar-header">
            <a href="index-1.html" class="ed-sidebar-logo">
                <img src="{{ asset('client/images/logo.png') }}" alt="logo" />
            </a>
            <button type="button" class="text-reset" data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="fi fi-rr-cross"></i>
            </button>
        </div>
        <div class="ed-sidebar-body m-0">
            <!-- Single Widget  -->
            <div class="ed-sidebar-widget">
                <h3 class="ed-sidebar-widget-title">Liên Hệ Với Chúng Tôi</h3>
                <!-- Sigle Info  -->
                <div class="ed-contact__info-item">
                    <div class="ed-contact__info-icon">
                        <img src="{{ asset('client/images/icons/icon-phone-blue.svg') }}" alt="icon-phone-blue" />
                    </div>
                    <div class="ed-contact__info-content">
                        <span>24/7 Support</span>
                        <a href="tel:09888661153">09888661153</a>
                    </div>
                </div>
                <!-- Sigle Info  -->
                <div class="ed-contact__info-item">
                    <div class="ed-contact__info-icon">
                        <img src="{{ asset('client/images/icons/icon-envelope-blue.svg') }}"
                            alt="icon-envelope-blue" />
                    </div>
                    <div class="ed-contact__info-content">
                        <span>Email</span>
                        <a href="mailto:hiendiepedu.edu@gmail.com">hiendiepedu.edu@gmail.com</a>
                    </div>
                </div>

                <!-- Sigle Info  -->
                <div class="ed-contact__info-item">
                    <div class="ed-contact__info-icon">
                        <img src="{{ asset('client/images/icons/icon-location-blue.svg') }}"
                            alt="icon-location-blue" />
                    </div>
                    <div class="ed-contact__info-content">
                        <span>Address</span>
                        <a href="#">984 Quang trung 3 - Đông Vệ - Thành Phố Thanh Hóa</a>
                    </div>
                </div>
            </div>

            <!-- Single Widget  -->
            <div class="ed-sidebar-widget">
                <h3 class="ed-sidebar-widget-title">Follow Us:</h3>
                <ul class="ed-sidebar-social">
                    <li>
                        <a href="https://www.facebook.com/" target="_blank"><img
                                src="{{ asset('client/images/icons/icon-dark-facebook.svg') }}"
                                alt="icon-dark-facebook" /></a>
                    </li>
                    <li>
                        <a href="https://www.twitter.com/" target="_blank"><img
                                src="{{ asset('client/images/icons/icon-dark-twitter.svg') }}"
                                alt="icon-dark-twitter" /></a>
                    </li>
                    <li>
                        <a href="https://www.dribbble.com/" target="_blank"><img
                                src="{{ asset('client/images/icons/icon-dark-dribbble.svg') }}"
                                alt="icon-dark-dribbble" /></a>
                    </li>
                    <li>
                        <a href="https://www.instagram.com/" target="_blank"><img
                                src="{{ asset('client/images/icons/icon-dark-instagram.svg') }}"
                                alt="icon-dark-instagram" /></a>
                    </li>
                </ul>
            </div>

            <!-- Single Widget  -->
            <div class="ed-sidebar-widget">
                <h3 class="ed-sidebar-widget-title">Subscribe Now:</h3>
                <form action="#" method="post" class="ed-sidebar-subscribe">
                    <input type="email" name="email-address" placeholder="Enter email" required />
                    <button type="submit" class="ed-btn">Subscribe<i
                            class="fi fi-rr-arrow-small-right"></i></button>
                </form>
            </div>
        </div>
    </div>
    <!-- End Sidebar  -->


    <!-- Modal -->
    <div id="customModal" class="modal" tabindex="-1" style=" background: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-xl" style="margin-top: 20px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">THÔNG BÁO ĐÓNG HỌC PHÍ!</h5>
                    <button type="button" class="btn-close" onclick="closeModal('customModal')"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">
                        Trung tâm ngoại ngữ Hiền Diệp thông báo về việc <strong class="text-danger">đóng học
                            phí</strong>
                        cho khóa hiện tại. Vui lòng kiểm tra
                        thông tin dưới đây và thực hiện thanh toán đúng hạn!
                    </p>
                    <h6>Thông tin thanh toán:</h6>
                    <ul>
                        <li class="text-black">
                            <strong>Tên học sinh:</strong> <span
                                id="studentName">{{ Auth::user()->name ?? '' }}</span>
                            <strong class="ms-4">Ngày sinh:</strong>
                            <span` id="studentName">
                                {{ \Carbon\Carbon::parse(Auth::user()->birth_date ?? '')->format('d/m/Y') }}</span>

                        </li>

                    </ul>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Khóa học</th>
                                <th>Lớp</th>
                                <th>Số tiền phải đóng (VNĐ)</th>
                            </tr>
                        </thead>
                        <tbody id="body-infomation-payment-student">


                        </tbody>
                    </table>
                    <p class="mb-3">
                        Vui lòng thực hiện thanh toán qua mã QR tại đây:
                        <button class="text-primary fw-bold btn-showQr-payment" target="_blank">Xem mã QR
                            thanh toán </button>
                    </p>
                    <p class="text-end mt-3">
                        Trân trọng,
                        Trung tâm ngoại ngữ Hiền Diệp!<br>
                        <em>Ngày {{ now()->format('d') }} tháng {{ now()->format('m') }} năm
                            {{ now()->format('Y') }}</em>
                    </p>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div id="qrModal" class="modal" tabindex="-1" style=" background: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-md" style="margin-top: 20px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Mã Thanh toán!</h5>
                    <button type="button" class="btn-close btn-close-qr"></button>
                </div>
                <div class="modal-body text-center">
                    <p>Quét mã QR để thanh toán:</p>
                    <img src="" alt="QR Code" class="img-fluid" id="qrCode">
                    <p>Nội dung chuyển khoản: <span id="qrContent"></span></p>
                    <p>Số tiền: <span id="qrAmount"></span></p>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Thêm/Sửa Quiz -->
    <div class="modal fade" id="modal-add-quiz" tabindex="-1" aria-labelledby="addQuizModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header custom-modal-header bg-light-subtle">
                    <h5 class="modal-title d-flex align-items-center gap-2" id="addQuizModalLabel">
                        <iconify-izcon icon="solar:pen-2-broken" class="text-primary"></iconify-izcon>
                        <span id="modal-title-text">Thêm bài quiz</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="quiz-form" action="{{ route('admin.quizzes.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="form-method" value="POST">
                    <input type="hidden" name="quiz_id" id="quiz-id">

                    <div class="modal-body" style="overflow-y: auto; max-height: 60vh;">
                        <div class="row g-3">

                            <div class="col-12">
                                <label for="quiz_title" class="form-label fw-bold">Tiêu đề</label>
                                <input type="text" class="form-control form-control-sm" id="quiz_title"
                                    name="title" placeholder="Nhập tiêu đề bài quiz">
                            </div>

                            <div class="col-md-6">
                                <label for="description" class="form-label fw-bold">Mô tả</label>
                                <input type="text" class="form-control form-control-sm" id="description"
                                    name="description" placeholder="Nhập mô tả bài quiz">
                            </div>

                            <div class="col-md-6">
                                <label for="duration" class="form-label fw-bold">Thời gian (phút)</label>
                                <input type="number" class="form-control form-control-sm" id="duration"
                                    name="duration_minutes" placeholder="Nhập thời gian" min="1">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold">Trạng thái công khai</label>
                                <div class="">
                                    <input class="" type="radio" name="is_public" id="is_public_1"
                                        value="1">
                                    <label class="" for="is_public_1">Công khai</label>
                                </div>
                                <div class="">
                                    <input class="" type="radio" name="is_public" id="is_public_0"
                                        value="0" checked>
                                    <label class="" for="is_public_0">Không công khai</label>
                                </div>
                            </div>

                            <div class="col-md-6 class-course-fields" id="class_field">
                                <label for="class_id" class="form-label fw-bold w-100">Lớp học</label>
                                <select class="form-select-sm w-100" id="class_id" name="class_id">
                                    <option value="">Chọn lớp</option>

                                    @php
                                        $teacherClasses = \DB::table('schedules as cs')
                                            ->join('classes as c', 'cs.class_id', '=', 'c.id')
                                            ->where('cs.teacher_id', Auth::user()->id ?? 0)
                                            ->where('c.status', '!=', 'completed')
                                            ->select('c.id', 'c.name', 'c.courses_id')
                                            ->distinct()
                                            ->get();
                                    @endphp
                                    @foreach ($teacherClasses as $class)
                                        <option value="{{ $class->id }}" data-course="{{ $class->courses_id }}">
                                            {{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 class-course-fields" id="course_field">
                                <label for="course_id" class="form-label fw-bold w-100">Khóa học</label>
                                <select class="form-select-sm w-100" id="course_id" name="course_id">
                                    <option value="">Khóa học</option>
                                    @foreach (DB::table('courses')->get() as $course)
                                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 text-muted small d-flex align-items-center mt-2">
                                <i class="icofont-info-circle me-2 text-warning"></i>
                                Quiz chỉ có thể được tạo cho "một lớp" hoặc "một khóa học" hoặc để "công khai"
                                không
                                đồng thời nhiều đối tượng!
                            </div>
                            <div id="error-container" class="col-12" style="display: none;"></div>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary custom-btn-cancel"
                            data-bs-dismiss="modal">
                            <iconify-icon icon="solar:close-circle-broken" class="me-1"></iconify-icon> Đóng
                        </button>
                        <button type="submit" class="btn btn-primary-quiz custom-btn-submit">
                            <iconify-icon icon="solar:check-circle-broken" class="me-1"></iconify-icon> Tiếp theo
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Modal kết quả các lần làm bài quiz của học sinh -->
    <div class="modal fade" id="resultModal" tabindex="-1" aria-labelledby="resultModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center gap-2 fs-6 fs-md-5 fs-lg-4"
                        id="resultModalLabel">
                        <i class="icofont-chart-bar-graph text-primary"></i> Kết quả các lần làm bài
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row" id="body-modal-result">

                    </div>
                </div>

                <div class="modal-footer">
                    <div class="quiz-footer">
                        <a href="" class="btn btn-outline-primary-quiz quiz-action-btn-result">
                            <i class="icofont-play-alt-1 me-1"></i> Làm Lại
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- Thông báo --}}
    <!-- Popup container -->
    <div id="customAlert" class="custom-alert-backdrop" role="alert" aria-modal="true" aria-hidden="true">
        <div class="custom-alert-box">
            <div class="particles">
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
            </div>
            <div class="custom-alert-icon"></div>
            <div class="custom-alert-title" id="alertTitle"></div>
            <div class="custom-alert-text" id="alertText"></div>
            <button class="custom-alert-btn" id="alertBtn">OK</button>
        </div>
    </div>


    <!-- Back to top area start here -->
    <!-- Start Back To Top  -->
    <div class="progress-wrap">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
        </svg>
    </div>
    <!-- End Back To Top --> <!-- Back to top area end here -->

    <!--<< All JS Plugins >>-->

    <script src="{{ asset('client/plugins/js/jquery.min.js') }}"></script>
    <script src="{{ asset('client/plugins/js/jquery-migrate.js') }}"></script>
    <!-- Bootstrap JS -->
    <script src="{{ asset('client/plugins/js/bootstrap.min.js') }}"></script>

    <!-- Gsap JS -->
    <script src="{{ asset('client/plugins/js/gsap/gsap.js') }}"></script>
    <script src="{{ asset('client/plugins/js/gsap/gsap-scroll-to-plugin.js') }}"></script>
    <script src="{{ asset('client/plugins/js/gsap/gsap-scroll-smoother.js') }}"></script>
    <script src="{{ asset('client/plugins/js/gsap/gsap-scroll-trigger.js') }}"></script>
    <script src="{{ asset('client/plugins/js/gsap/gsap-split-text.js') }}"></script>

    <!-- Wow JS -->
    <script src="{{ asset('client/plugins/js/wow.min.js') }}"></script>
    <!-- Owl Carousel JS -->
    <script src="{{ asset('client/plugins/js/owl.carousel.min.js') }}"></script>
    <!-- Swiper Slider JS -->
    <script src="{{ asset('client/plugins/js/swiper-bundle.min.js') }}"></script>
    <!-- Magnific Popup JS -->
    <script src="{{ asset('client/plugins/js/magnific-popup.min.js') }}"></script>
    <!-- CounterUp  JS -->
    <script src="{{ asset('client/plugins/js/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('client/plugins/js/waypoints.min.js') }}"></script>
    <!-- Nice Select JS -->
    <script src="{{ asset('client/plugins/js/nice-select.min.js') }}"></script>
    <!-- Back To Top JS -->
    <script src="{{ asset('client/plugins/js/backToTop.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('client/plugins/js/active.js') }}"></script>

    @stack('script')
    <script>
        $('#btn-login').on('click', function(e) {
            e.preventDefault();

            const username = $('#usernameLogin').val();
            const password = $('#passwordLogin').val();
            $.ajax({
                url: "{{ route('loginAuth') }}",
                type: 'POST',
                data: {
                    username: username,
                    password: password,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.status && response.redirect) {
                        window.location.href = response.redirect;
                    } else {
                        $('#alert-login').show();
                    }
                },
                error: function(xhr) {
                    // const res = xhr.responseJSON;
                    // alert(res?.message || 'Đăng nhập thất bại');
                }
            });
        });
    </script>


</body>

<!-- Mirrored from bizantheme.com/php/eduna-php/index.php by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 13 May 2025 09:12:16 GMT -->

</html>
