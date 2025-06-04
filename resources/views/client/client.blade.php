<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <meta name="description" content="@yield('description')">

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
                                <a class="offcanvas__menu_item" href="index.html">Home</a>
                                <ul class="offcanvas__sub_menu">
                                    <li class="offcanvas__sub_menu_li">
                                        <a href="index.html" class="offcanvas__sub_menu_item">Home One</a>
                                    </li>
                                    <li class="offcanvas__sub_menu_li">
                                        <a href="index-2.html" class="offcanvas__sub_menu_item">Home Two</a>
                                    </li>

                                    <li class="offcanvas__sub_menu_li">
                                        <a href="index-3.html" class="offcanvas__sub_menu_item">Home Three</a>
                                    </li>

                                    <li class="offcanvas__sub_menu_li">
                                        <a href="index-4.html" class="offcanvas__sub_menu_item">Home Four</a>
                                    </li>

                                    <li class="offcanvas__sub_menu_li">
                                        <a href="index-5.html" class="offcanvas__sub_menu_item">Home Five</a>
                                    </li>
                                </ul>
                            </li>

                            <li class="offcanvas__menu_li">
                                <a class="offcanvas__menu_item" href="course-1.html">Courses</a>
                                <ul class="offcanvas__sub_menu">
                                    <li class="offcanvas__sub_menu_li">
                                        <a href="course-1.html" class="offcanvas__sub_menu_item">Course One</a>
                                    </li>
                                    <li class="offcanvas__sub_menu_li">
                                        <a href="course-2.html" class="offcanvas__sub_menu_item">Course Two</a>
                                    </li>
                                    <li class="offcanvas__sub_menu_li">
                                        <a href="course-3.html" class="offcanvas__sub_menu_item">Course Three</a>
                                    </li>
                                    <li class="offcanvas__sub_menu_li">
                                        <a href="course-details.html" class="offcanvas__sub_menu_item">Course
                                            Details</a>
                                    </li>
                                </ul>
                            </li>

                            <li class="offcanvas__menu_li">
                                <a class="offcanvas__menu_item" href="javascript:void(0)">Pages</a>
                                <ul class="offcanvas__sub_menu">
                                    <li class="offcanvas__sub_menu_li">
                                        <a href="product.html" class="offcanvas__sub_menu_item">Products</a>
                                        <ul class="offcanvas__sub_menu">
                                            <li class="offcanvas__sub_menu_li">
                                                <a class="offcanvas__sub_menu_item" href="product.html">Product</a>
                                            </li>
                                            <li class="offcanvas__sub_menu_li">
                                                <a class="offcanvas__sub_menu_item" href="product-details.html">Product
                                                    Details</a>
                                            </li>
                                            <li class="offcanvas__sub_menu_li">
                                                <a class="offcanvas__sub_menu_item" href="cart.html">Product Cart</a>
                                            </li>
                                            <li class="offcanvas__sub_menu_li">
                                                <a class="offcanvas__sub_menu_item" href="checkout.html">Product
                                                    Checkout</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="offcanvas__sub_menu_li">
                                        <a href="teacher.html" class="offcanvas__sub_menu_item">Teacher </a>
                                        <ul class="offcanvas__sub_menu">
                                            <li class="offcanvas__sub_menu_li">
                                                <a class="offcanvas__sub_menu_item" href="teacher.html">Teacher</a>
                                            </li>
                                            <li class="offcanvas__sub_menu_li">
                                                <a class="offcanvas__sub_menu_item"
                                                    href="teacher-details.html">Teacher
                                                    Details</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="offcanvas__sub_menu_li">
                                        <a href="faq.html" class="offcanvas__sub_menu_item">Faq</a>
                                    </li>

                                    <li class="offcanvas__sub_menu_li">
                                        <a href="404.html" class="offcanvas__sub_menu_item">404 Error</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="offcanvas__menu_li">
                                <a class="offcanvas__menu_item" href="blog.html">News</a>
                                <ul class="offcanvas__sub_menu">
                                    <li class="offcanvas__sub_menu_li">
                                        <a href="blog.html" class="offcanvas__sub_menu_item">Blog</a>
                                    </li>
                                    <li class="offcanvas__sub_menu_li">
                                        <a href="blog-details.html" class="offcanvas__sub_menu_item">Blog Details</a>
                                    </li>
                                </ul>
                            </li>

                            <li class="offcanvas__menu_li">
                                <a class="offcanvas__menu_item active" href="about-1.html">About</a>
                                <ul class="offcanvas__sub_menu">
                                    <li class="offcanvas__sub_menu_li">
                                        <a href="about-1.html" class="offcanvas__sub_menu_item active">About One</a>
                                    </li>
                                    <li class="offcanvas__sub_menu_li">
                                        <a href="about-2.html" class="offcanvas__sub_menu_item">About Two</a>
                                    </li>
                                </ul>
                            </li>

                            <li class="offcanvas__menu_li">
                                <a class="offcanvas__menu_item" href="contact.html">Contact</a>
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
                                    <a href="index.html">
                                        <img src="{{ asset('client/images/logo.svg') }}" alt="logo" />
                                    </a>
                                </div>

                                <!-- Category Dropdown -->
                                <div class="ed-topbar__search-widget">
                                    <div class="ed-topbar__category">
                                        <select>
                                            <option data-display="All Categories">
                                                All Categories
                                            </option>
                                            <option value="1">Business</option>
                                            <option value="2">Marketing</option>
                                            <option value="3">Design</option>
                                            <option value="4">Finance</option>
                                            <option value="5">Lifestyle</option>
                                            <option value="6">Development</option>
                                            <option value="7">Photography</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Navigation Menu -->
                            <nav class="ed-header__navigation">
                                <ul class="ed-header__menu">
                                    <li class="active">
                                        <a href="javascript:void(0)">Home<i class="fi fi-ss-angle-small-down"></i></a>
                                        <ul class="sub-menu">
                                            <li>
                                                <a href="index.html">Home One</a>
                                            </li>
                                            <li class="active">
                                                <a href="index-2.html">Home Two</a>
                                            </li>
                                            <li>
                                                <a href="index-3.html">Home Three</a>
                                            </li>
                                            <li>
                                                <a href="index-4.html">Home Four</a>
                                            </li>
                                            <li>
                                                <a href="index-5.html">Home Five</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)">Courses<i
                                                class="fi fi-ss-angle-small-down"></i></a>
                                        <ul class="sub-menu">
                                            <li><a href="course-1.html">Course One</a></li>
                                            <li><a href="course-2.html">Course Two</a></li>
                                            <li><a href="course-3.html">Course Three</a></li>
                                            <li>
                                                <a href="course-details.html">Course Details</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)">Pages<i
                                                class="fi fi-ss-angle-small-down"></i></a>
                                        <ul class="sub-menu">
                                            <li>
                                                <a href="javascript:void(0)">Products<i
                                                        class="fi fi-ss-angle-small-right"></i></a>
                                                <ul class="sub-menu third-menu">
                                                    <li><a href="product.html">Product</a></li>
                                                    <li>
                                                        <a href="product-details.html">Product Details</a>
                                                    </li>
                                                    <li>
                                                        <a href="cart.html">Product Cart</a>
                                                    </li>
                                                    <li>
                                                        <a href="checkout.html">Product Checkout</a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0)">Teacher <i
                                                        class="fi fi-ss-angle-small-right"></i></a>
                                                <ul class="sub-menu third-menu">
                                                    <li><a href="teacher.html">Teacher</a></li>
                                                    <li>
                                                        <a href="teacher-details.html">Teacher Details</a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li><a href="faq.html">Faq</a></li>
                                            <li>
                                                <a href="404.html">404 Error</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)">News<i class="fi fi-ss-angle-small-down"></i></a>
                                        <ul class="sub-menu">
                                            <li><a href="blog.html">Blog </a></li>
                                            <li><a href="blog-details.html">Blog Details</a></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)">About<i
                                                class="fi fi-ss-angle-small-down"></i></a>
                                        <ul class="sub-menu">
                                            <li><a href="about-1.html">About One </a></li>
                                            <li><a href="about-2.html">About Two</a></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="contact.html">Contact</a>
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
                                    <button type="button" class="register-btn" data-bs-toggle="modal"
                                        data-bs-target="#registerModal">
                                        Register
                                    </button>
                                    <button type="button" class="login-btn" data-bs-toggle="modal"
                                        data-bs-target="#loginModal">
                                        Log In
                                    </button>
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
                    <p class="ed-auth__modal-text">
                        Didn’t Create an account?
                        <button type="button" data-bs-toggle="modal" data-bs-target="#registerModal">
                            Sign Up
                        </button>
                    </p>
                </div>

                <!-- Auth Body  -->
                <div class="ed-auth__modal-body">
                    <form action="{{ route('loginAuth') }}" method="POST" class="ed-auth__modal-form">
                        <div class="form-group">
                            <input type="email" name="email" placeholder="Enter user email" required />
                        </div>

                        <div class="form-group">
                            <input type="password" name="password" placeholder="Enter password" required />
                        </div>

                        <div class="form-check">
                            <label class="form-check-label" for="flexCheckDefault">
                                <input class="form-check-input" type="checkbox" value=""
                                    id="flexCheckDefault" />
                                Remember me
                            </label>
                        </div>
                        <div class="ed-auth__form-btn">
                            <button type="submit" class="ed-btn">Sign In<i
                                    class="fi fi-rr-arrow-small-right"></i></button>
                        </div>
                    </form>
                </div>
                <!-- Auth Footer  -->
                <div class="ed-auth__modal-footer">
                    <div class="ed-auth__modal-third-party">
                        <p>Or Sign In with</p>
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
    </div>
    <!-- End Login Modal -->

    <!-- Start Register Modal -->
    <div class="modal fade ed-auth__modal" id="registerModal" tabindex="-1" aria-labelledby="exampleModalLabel"
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
                    <form action="#" method="post" class="ed-auth__modal-form">
                        <div class="form-group">
                            <input type="text" name="name" placeholder="Enter name" required />
                        </div>

                        <div class="form-group">
                            <input type="text" name="user name" placeholder="Enter user name" required />
                        </div>

                        <div class="form-group">
                            <input type="email" name="email" placeholder="Enter email" required />
                        </div>

                        <div class="form-group">
                            <input type="password" name="password" placeholder="Enter password" required />
                        </div>

                        <div class="form-check">
                            <label class="form-check-label" for="flexCheckDefault2">
                                <input class="form-check-input" type="checkbox" value=""
                                    id="flexCheckDefault2" />
                                I agree with your <strong>Privacy Policy</strong>
                            </label>
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
    </div>
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



</body>

<!-- Mirrored from bizantheme.com/php/eduna-php/index.php by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 13 May 2025 09:12:16 GMT -->

</html>
