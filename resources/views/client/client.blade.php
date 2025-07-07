<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <meta name="description" content="@yield('description')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('client/images/favicon.svg') }}" />

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

    <script src="{{ asset('client/plugins/js/jquery.min.js') }}"></script>

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
    <!-- Cursor Area Start -->


    <!-- Start Mobile Menu Offcanvas -->
    <div class="modal mobile-menu-modal offcanvas-modal fade" id="offcanvas-modal">
        <div class="modal-dialog offcanvas-dialog">
            <div class="modal-content">
                <div class="modal-header offcanvas-header">
                    <!-- Mobile Menu Logo -->
                    <div class="offcanvas-logo">
                        <a href="index.html"><img src="{{ asset('client/images/logo.svg') }}" alt="#" /></a>
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
                                <a class="offcanvas__menu_item" href="blog.html">Bài viết</a>
                                <ul class="offcanvas__sub_menu">
                                    <li class="offcanvas__sub_menu_li">
                                        <a href="blog.html" class="offcanvas__sub_menu_item">Tin tức giáo dục</a>
                                    </li>
                                    <li class="offcanvas__sub_menu_li">
                                        <a href="blog-details.html" class="offcanvas__sub_menu_item">Tin tức công
                                            nghệ</a>
                                    </li>
                                </ul>
                            </li>

                            <li class="offcanvas__menu_li">
                                <a class="offcanvas__menu_item active" href="about-1.html">Giới thiệu</a>

                            </li>

                            <li class="offcanvas__menu_li">
                                <a class="offcanvas__menu_item" href="contact.html">Liên hệ</a>
                            </li>


                            <li class="offcanvas__menu_li mt-4">
                                <a class="offcanvas__menu_item" href=""><i class="icofont-user me-2"></i>Tài
                                    khoản của bạn</a>
                                <ul class="offcanvas__sub_menu">

                                    <li class="offcanvas__sub_menu_li"><a href="{{ route('client.information') }}" class="offcanvas__sub_menu_item" data-section="dashboard">
                                            <i class="icofont-chart-bar-graph"></i>Dashboard</a>
                                    </li>
                                    <li class="offcanvas__sub_menu_li"><a href="{{ route('client.schedule') }}" class="offcanvas__sub_menu_item" data-section="schedule">
                                            <i class="icofont-calendar"></i> Lịch học</a>
                                    </li>
                                    <li class="offcanvas__sub_menu_li"><a href="{{ route('client.score') }}" class="offcanvas__sub_menu_item" data-section="grades">
                                            <i class="icofont-book-alt"></i>Điểm Số</a>
                                    </li>
                                    <li class="offcanvas__sub_menu_li"><a href="{{ route('client.quizz') }}" class="offcanvas__sub_menu_item" data-section="quizzes">
                                            <i class="icofont-pencil-alt-2"></i> Bài Quiz</a>
                                    </li>
                                    <li class="offcanvas__sub_menu_li"><a href="{{ route('client.account') }}" class="offcanvas__sub_menu_item" data-section="account">
                                            <i class="icofont-user"></i>Thông Tin Tài Khoản</a>
                                    </li>
                                </ul>
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
                                        <img src="{{ asset('client/images/logo.svg') }}" alt="logo" />
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

                                        <a href="{{ route('client.course') }}">Khóa học<i
                                                class=""></i></a>

                                    </li>

                                    <li>
                                        <a href="javascript:void(0)">Bài viết<i
                                                class="fi fi-ss-angle-small-down"></i></a>
                                        <ul class="sub-menu">
                                            <li><a href="#">Tin giáo dục </a></li>
                                            <li><a href="#">Tin công nghệ </a></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)">Giới thiệu</a>

                                    </li>
                                    <li>
                                        <a href="#">Liên hệ</a>
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

                                            @if (Auth::user()->role == 'teacher')
                                                <a href="{{ route('client.information') }}">
                                                    <span class="fw-medium name-user">{{ Auth::user()->name }} </span>

                                                    <img src="{{ asset('client/images/icons/teacher.svg') }}"
                                                        alt="{{ Auth::user()->name }}" style="width: 30px; height: 30px">
                                                </a>
                                            @elseif (Auth::user()->role == 'student')
                                                <a href="{{ route('client.information') }}">
                                                    <span class="fw-medium name-user">{{ Auth::user()->name }} </span>

                                                    <img src="{{ asset('client/images/icons/studentBoy.svg') }}"
                                                        alt="{{ Auth::user()->name }}" style="width: 30px; height: 30px">
                                                </a>
                                            @elseif (Auth::user()->role == 'admin')
                                                <a href="{{ route('admin.dashboard') }}">
                                                    <span class="fw-medium name-user">{{ Auth::user()->name }} </span>
                                                    {{--
                                                    <img src="{{ asset('client/images/icons/studentBoy.svg') }}"
                                                        alt="{{ Auth::user()->name }}" style="width: 30px; height: 30px"> --}}
                                                </a>
                                            @endif

                                            <form action="{{ route('auth.logout') }}" method="GET" style="margin: 0">
                                                @csrf
                                                <button type="submit" class="text-decoration-none">Logout</button>
                                            </form>
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

                <!-- Start Call Action Area -->
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
                </section>
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
                                <div class="col-lg-4 col-md-6 col-12">
                                    <div class="ed-footer__widget ed-footer__about">
                                        <a href="index.html" class="ed-footer__logo">
                                            <img src="{{ asset('client/images/logo.svg') }}" alt="footer-logo" />
                                        </a>
                                        <p class="ed-footer__about-text">
                                            Excepteur sint occaecat cupidatat non proident sunt in culpa qui officia
                                            deserunt mollit.
                                        </p>
                                        <ul class="ed-footer__about-social">
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
                                </div>
                                <div class="col-lg-2 col-md-6 col-12">
                                    <div class="ed-footer__widget">
                                        <h4 class="ed-footer__widget-title">Links</h4>
                                        <ul class="ed-footer__widget-links">
                                            <li><a href="about-1.html">About Us</a></li>
                                            <li><a href="course-1.html">Our Courses</a></li>
                                            <li><a href="#">Pricing Plan</a></li>
                                            <li><a href="contact.html">Contact Us</a></li>
                                            <li><a href="blog.html">Our News</a></li>
                                            <li><a href="faq.html">FAQ’s</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-12">
                                    <div class="ed-footer__widget contact-widget">
                                        <h4 class="ed-footer__widget-title">Contact</h4>
                                        <!-- Single Info  -->
                                        <div class="ed-footer__contact">
                                            <div class="ed-footer__contact-icon">
                                                <img src="{{ asset('client/images/icons/icon-phone-blue.svg') }}"
                                                    alt="icon-phone-blue" />
                                            </div>
                                            <div class="ed-footer__contact-info">
                                                <span>24/7 Support</span>
                                                <a href="tel:+532 321 33 33">+532 321 33 33</a>
                                            </div>
                                        </div>
                                        <!-- Single Info  -->
                                        <div class="ed-footer__contact">
                                            <div class="ed-footer__contact-icon">
                                                <img src="{{ asset('client/images/icons/icon-envelope-blue.svg') }}"
                                                    alt="icon-envelope-blue" />
                                            </div>
                                            <div class="ed-footer__contact-info">
                                                <span>Send Message</span>
                                                <a href="mailto:eduna@gmail.com">eduna@gmail.com</a>
                                            </div>
                                        </div>

                                        <!-- Single Info  -->
                                        <div class="ed-footer__contact">
                                            <div class="ed-footer__contact-icon">
                                                <img src="{{ asset('client/images/icons/icon-location-blue.svg') }}"
                                                    alt="icon-location-blue" />
                                            </div>
                                            <div class="ed-footer__contact-info">
                                                <span>Our Locati0n</span>
                                                <a href="#" target="_blank">32/Jenin, London</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6 col-12">
                                    <div class="ed-footer__widget newsletter-widget">
                                        <h4 class="ed-footer__widget-title">Subscribe</h4>

                                        <div class="ed-footer__newsletter">
                                            <p class="ed-footer__about-text">
                                                Enter your email address to register to our newsletter subscription
                                            </p>
                                            <form action="#" method="post" class="ed-footer__newsletter-form">
                                                <input type="email" name="email" placeholder="Enter email"
                                                    required />
                                                <button type="submit" class="ed-btn">Subscribe Now<i
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
                                        Copyright 2024 Eduna | Developed By
                                        <a href="https://themeforest.net/user/bizantheme"
                                            target="_blank">BizanTheme</a>. All Rights Reserved
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
                    <a href="index.html" class="ed-auth__modal-logo">
                        <img src="{{ asset('client/images/logo.svg') }}" alt="logo" />
                    </a>
                    <h3 class="ed-auth__modal-title">Sign In Now</h3>

                </div>

                <!-- Auth Body  -->
                <div class="ed-auth__modal-body">
                    <div class="alert alert-danger" id="alert-login" role="alert" style="display: none">
                        Sai tên đăng nhập hoặc mật khẩu
                    </div>
                    <form action="{{ route('loginAuth') }}" method="POST" class="ed-auth__modal-form">
                        @csrf
                        <div class="form-group">
                            <input type="text" id="usernameLogin" name="username" placeholder="Enter password"
                                required />
                        </div>

                        <div class="form-group">
                            <input type="password" id="passwordLogin" name="password" placeholder="Enter password"
                                required />
                        </div>

                        <div class="form-check">
                            <label class="form-check-label" for="flexCheckDefault">
                                <input class="form-check-input" type="checkbox" value=""
                                    id="flexCheckDefault" />
                                Remember me
                            </label>
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

    <!-- Start Register Modal -->
    {{-- <div class="modal fade ed-auth__modal" id="registerModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="ed-auth__modal-content modal-content">
                <button type="button" class="ed-auth__modal-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fi-rr-cross"></i>
                </button>

                <!-- Auth Head  -->
                <div class="ed-auth__modal-head">
                    <a href="index.html" class="ed-auth__modal-logo">
                        <img src="{{ asset('client/images/logo.svg') }}" alt="logo" />
                    </a>
                    <h3 class="ed-auth__modal-title">Sign Up Now</h3>
                    <p class="ed-auth__modal-text">
                        already have an account?
                        <button type="button" data-bs-toggle="modal" data-bs-target="#loginModal">
                            Sign In
                        </button>
                    </p>
                </div>

                <!-- Auth Body  -->
                <div class="ed-auth__modal-body">

                    <form action="{{ route('registerAuth') }}" method="post" class="ed-auth__modal-form">
                        @csrf
                        <div class="form-group">
                            <input type="text" name="name" placeholder="Enter name" required />
                            @error('name')
                                <span class="error" style="color: red; font-size: 0.9em;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <input type="email" name="email" placeholder="Enter email" required />
                            @error('email')
                                <span class="error" style="color: red; font-size: 0.9em;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <input type="tel" name="phone" inputmode="numeric" pattern="[0-9]{10,15}"
                                value="{{ old('phone') }}" placeholder="Enter phone" required
                                onkeypress="return (event.charCode !=8 && event.charCode == 0) ? true : (event.charCode >= 48 && event.charCode <= 57)" />
                            @error('phone')
                                <span class="error" style="color: red; font-size: 0.9em;">{{ $message }}</span>
                            @enderror

                        </div>

                        <div class="form-group">
                            <input type="password" name="password" placeholder="Enter password" required />

                            @error('password')
                                <span class="error" style="color: red; font-size: 0.9em;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <input type="password" name="password_confirmation"
                                placeholder="Enter password_confirmation" required />
                            @error('password_confirmation')
                                <span class="error" style="color: red; font-size: 0.9em;">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="ed-auth__form-btn">
                            <button type="submit" class="ed-btn">Register Now<i
                                    class="fi fi-rr-arrow-small-right"></i></button>
                        </div>
                    </form>
                </div>



                <!-- Auth Footer  -->
                <div class="ed-auth__modal-footer">
                    <div class="ed-auth__modal-third-party">
                        <p>Or Sign Up with</p>
                        <ul class="ed-auth__modal-third-party-list">
                            <li>
                                <a class="google-login" href="https://www.google.com/"><img
                                        src="{{ asset('client/images/icons/icon-color-google.svg') }}"
                                        alt="icon-color-google" /></a>
                            </li>

                            <li>
                                <a class="facebook-login" href="https://facebook.com/"><img
                                        src="{{ asset('client/images/icons/icon-color-facebook.svg') }}"
                                        alt="icon-color-facebook" /></a>
                            </li>
                            <li>
                                <a class="linkedin-login" href="https://www.linkedin.com/"><img
                                        src="{{ asset('client/images/icons/icon-color-linkedin.svg') }}"
                                        alt="icon-color-linkedin" /></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <!-- End Register Modal -->



    <!-- Start Sidebar  -->
    <div class="offcanvas offcanvas-end ed-sidebar" tabindex="-1" id="edSidebar"
        aria-labelledby="offcanvasRightLabel">
        <div class="ed-sidebar-header">
            <a href="index-1.html" class="ed-sidebar-logo">
                <img src="{{ asset('client/images/logo.svg') }}" alt="logo" />
            </a>
            <button type="button" class="text-reset" data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="fi fi-rr-cross"></i>
            </button>
        </div>
        <div class="ed-sidebar-body m-0">
            <!-- Single Widget  -->
            <div class="ed-sidebar-widget">
                <h3 class="ed-sidebar-widget-title">Contacts Us:</h3>
                <!-- Sigle Info  -->
                <div class="ed-contact__info-item">
                    <div class="ed-contact__info-icon">
                        <img src="{{ asset('client/images/icons/icon-phone-blue.svg') }}" alt="icon-phone-blue" />
                    </div>
                    <div class="ed-contact__info-content">
                        <span>24/7 Support</span>
                        <a href="tel:+532 321 33 33">+532 321 33 33</a>
                    </div>
                </div>
                <!-- Sigle Info  -->
                <div class="ed-contact__info-item">
                    <div class="ed-contact__info-icon">
                        <img src="{{ asset('client/images/icons/icon-envelope-blue.svg') }}"
                            alt="icon-envelope-blue" />
                    </div>
                    <div class="ed-contact__info-content">
                        <span>Send Message</span>
                        <a href="mailto:eduna@gmail.com">eduna@gmail.com3</a>
                    </div>
                </div>

                <!-- Sigle Info  -->
                <div class="ed-contact__info-item">
                    <div class="ed-contact__info-icon">
                        <img src="{{ asset('client/images/icons/icon-location-blue.svg') }}"
                            alt="icon-location-blue" />
                    </div>
                    <div class="ed-contact__info-content">
                        <span>Our Locati0n</span>
                        <a href="#">32/Jenin, London</a>
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
                    <img src="https://via.placeholder.com/200" alt="QR Code" class="img-fluid" id="qrCode">
                    <p>Nội dung chuyển khoản: <span id="qrContent"></span></p>
                    <p>Số tiền: <span id="qrAmount"></span></p>
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
