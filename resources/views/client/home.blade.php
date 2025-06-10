@extends('client.client')

@section('title', 'Trang chủ')
@section('description', '')
@section('content')


            <main>
                <div class="section-bg hero-bg background-image"
                    style="background-image: url('{{ asset('client/images/hero/home-1/hero-bg.png')}}');">
                    <!-- Start Hero Area -->
                    <section class="ed-hero">
                        <div class="container ed-container-expand">
                            <!-- Hero Element Shape -->
                            <div class="ed-hero__elements">
                                <img class="element-move ed-hero__shape-1" src="{{ asset('client/images/hero/home-1/shape-1.svg')}}"
                                    alt="shape-1" />
                                <img class="element-move ed-hero__shape-2" src="{{ asset('client/images/hero/home-1/shape-2.svg')}}"
                                    alt="shape-1" />
                                <img class="element-move ed-hero__shape-3" src="{{ asset('client/images/hero/home-1/shape-3.svg')}}"
                                    alt="shape-1" />
                                <img class="element-move ed-hero__shape-4" src="{{ asset('client/images/hero/home-1/shape-4.svg')}}"
                                    alt="shape-1" />
                                <img class="element-move ed-hero__shape-5" src="{{ asset('client/images/hero/home-1/shape-5.png')}}"
                                    alt="shape-5" />
                            </div>
                            <div class="row align-items-center">
                                <div class="col-lg-6 col-12">
                                    <!-- Hero Content -->
                                    <div class="ed-hero__content">
                                        <h1 class="ed-hero__content-title ed-split-text left">Best <span>Online</span>
                                            Platform to Learn Everything</h1>
                                        <p class="ed-hero__content-text">
                                            Excedteur sint occaecat cupidatat non proident sunt in culpa qui officia
                                            deserunt mollit.
                                        </p>
                                        <div class="ed-hero__btn">
                                            <a href="course-1.html" class="ed-btn">Find Courses<i
                                                    class="fi fi-rr-arrow-small-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-12">
                                    <!-- Hero Image -->
                                    <div class="ed-hero__image">
                                        <img src="{{ asset('client/images/hero/home-1/hero-img.png')}}" alt="hero-img" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- End Hero Area -->
                </div>

                <div class="section-bg background-image"
                    style="background-image: url('{{ asset('client/images/section-bg-1.png')}}');">
                    <!-- Start About Area -->
                    <section class="ed-about section-gap position-relative">
                        <div class="container ed-container">
                            <div class="row align-items-center">
                                <div class="col-lg-6 col-12">
                                    <!-- About Images  -->
                                    <div class="ed-about__images">
                                        <div class="ed-about__main-img">
                                            <img src="{{ asset('client/images/about/about-1/about-img.png')}}" alt="about-img" />
                                        </div>
                                        <div class="counter-card updown-ani">
                                            <div class="counter-card__icon">
                                                <i class="fi fi-rr-graduation-cap"></i>
                                            </div>
                                            <div class="counter-card__info">
                                                <h4><span class="counter">9394</span>+</h4>
                                                <p>Enrolled Learners</p>
                                            </div>
                                        </div>

                                        <div class="ed-about__shapes">
                                            <img class="ed-about__shape-1"
                                                src="{{ asset('client/images/about/about-1/shape-1.svg')}}" alt="shape-1" />
                                            <img class="ed-about__shape-2"
                                                src="{{ asset('client/images/about/about-1/shape-2.svg')}}" alt="shape-2" />
                                            <img class="ed-about__shape-3 rotate-ani"
                                                src="{{ asset('client/images/about/about-1/shape-3.svg')}}" alt="shape-3" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-12 order-class">
                                    <!-- About Content  -->
                                    <div class="ed-about__content">
                                        <div class="ed-section-head">
                                            <span class="ed-section-head__sm-title">WELCOME TO EDUNA</span>
                                            <h3 class="ed-section-head__title ed-split-text left">
                                                Digital Online Academy: Your <br />
                                                Path to Creative Excellence
                                            </h3>
                                            <p class="ed-section-head__text">
                                                Excedteur sint occaecat cupidatat non proident sunt in culpa qui officia
                                                deserunt mollit.
                                            </p>
                                        </div>
                                        <div class="ed-about__feature">
                                            <ul class="ed-about__features-list">
                                                <li><img src="{{ asset('client/images/icons/icon-check-blue.svg')}}"
                                                        alt="icon-check-blue" />Our Expert Trainers</li>
                                                <li><img src="{{ asset('client/images/icons/icon-check-blue.svg')}}"
                                                        alt="icon-check-blue" />Online Remote Learning</li>
                                                <li><img src="{{ asset('client/images/icons/icon-check-blue.svg')}}"
                                                        alt="icon-check-blue" />Easy to follow curriculum</li>
                                                <li><img src="{{ asset('client/images/icons/icon-check-blue.svg')}}"
                                                        alt="icon-check-blue" />Lifetime Access</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <img class="ed-about__shape-4" src="{{ asset('client/images/abstracts/abstract-element-regular.svg')}}"
                            alt="shape-4" />
                    </section>
                    <!-- End About Area -->

                    <!-- Start Category Area -->
                    <section class="ed-category section-gap pt-0">
                        <div class="container ed-container">
                            <div class="row">
                                <div class="col-12">
                                    <div class="ed-section-head d-flex-between">
                                        <div class="ed-section-head__info">
                                            <span class="ed-section-head__sm-title">COURSE CATEGORIES</span>
                                            <h3 class="ed-section-head__title m-0 ed-split-text left">
                                                Top Categories You Want to Learn
                                            </h3>
                                        </div>
                                        <div class="ed-section-head__btn">
                                            <a href="course-1.html" class="ed-btn">Find Courses<i
                                                    class="fi fi-rr-arrow-small-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="ed-category__wrapper">
                                        <!-- Single Coure Card  -->
                                        <a href="course-1.html" class="ed-category__card wow fadeInUp"
                                            data-wow-delay=".3s" data-wow-duration="1s">
                                            <div class="ed-category__icon bg-1">
                                                <img src="{{ asset('client/images/category/category-1/1.svg')}}" alt="icon" />
                                            </div>
                                            <div class="ed-category__info">
                                                <h4>Business</h4>
                                                <p>04 Courses</p>
                                            </div>
                                        </a>
                                        <!-- Single Coure Card  -->
                                        <a href="course-1.html" class="ed-category__card wow fadeInUp"
                                            data-wow-delay=".5s" data-wow-duration="1s">
                                            <div class="ed-category__icon bg-2">
                                                <img src="{{ asset('client/images/category/category-1/2.svg')}}" alt="icon" />
                                            </div>
                                            <div class="ed-category__info">
                                                <h4>Marketing</h4>
                                                <p>88 Courses</p>
                                            </div>
                                        </a>
                                        <!-- Single Coure Card  -->
                                        <a href="course-1.html" class="ed-category__card wow fadeInUp"
                                            data-wow-delay=".7s" data-wow-duration="1s">
                                            <div class="ed-category__icon bg-3">
                                                <img src="{{ asset('client/images/category/category-1/3.svg')}}" alt="icon" />
                                            </div>
                                            <div class="ed-category__info">
                                                <h4>Design</h4>
                                                <p>23 Courses</p>
                                            </div>
                                        </a>
                                        <!-- Single Coure Card  -->
                                        <a href="course-1.html" class="ed-category__card wow fadeInUp"
                                            data-wow-delay=".9s" data-wow-duration="1s">
                                            <div class="ed-category__icon bg-4">
                                                <img src="{{ asset('client/images/category/category-1/4.svg')}}" alt="icon" />
                                            </div>
                                            <div class="ed-category__info">
                                                <h4>Finance</h4>
                                                <p>02 Courses</p>
                                            </div>
                                        </a>
                                        <!-- Single Coure Card  -->
                                        <a href="course-1.html" class="ed-category__card wow fadeInUp"
                                            data-wow-delay=".3s" data-wow-duration="1s">
                                            <div class="ed-category__icon bg-5">
                                                <img src="{{ asset('client/images/category/category-1/5.svg')}}" alt="icon" />
                                            </div>
                                            <div class="ed-category__info">
                                                <h4>Lifestyle</h4>
                                                <p>29 Courses</p>
                                            </div>
                                        </a>
                                        <!-- Single Coure Card  -->
                                        <a href="course-1.html" class="ed-category__card wow fadeInUp"
                                            data-wow-delay=".5s" data-wow-duration="1s">
                                            <div class="ed-category__icon bg-6">
                                                <img src="{{ asset('client/images/category/category-1/6.svg')}}" alt="icon" />
                                            </div>
                                            <div class="ed-category__info">
                                                <h4>Cyber</h4>
                                                <p>45 Courses</p>
                                            </div>
                                        </a>
                                        <!-- Single Coure Card  -->
                                        <a href="course-1.html" class="ed-category__card wow fadeInUp"
                                            data-wow-delay=".7s" data-wow-duration="1s">
                                            <div class="ed-category__icon bg-7">
                                                <img src="{{ asset('client/images/category/category-1/7.svg')}}" alt="icon" />
                                            </div>
                                            <div class="ed-category__info">
                                                <h4>Development</h4>
                                                <p>28 Courses</p>
                                            </div>
                                        </a>
                                        <!-- Single Coure Card  -->
                                        <a href="course-1.html" class="ed-category__card wow fadeInUp"
                                            data-wow-delay=".9s" data-wow-duration="1s">
                                            <div class="ed-category__icon bg-8">
                                                <img src="{{ asset('client/images/category/category-1/8.svg')}}" alt="icon" />
                                            </div>
                                            <div class="ed-category__info">
                                                <h4>Photography</h4>
                                                <p>03 Courses</p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- End Category Area -->
                </div>
                <!-- Start Features Area -->
                <section class="ed-features position-relative">
                    <div class="ed-category__shapes">
                        <img class="ed-category__shape-1 updown-ani"
                            src="{{ asset('client/images/features/features-1/shape-1.svg')}}" alt="shape-1" />
                        <img class="ed-category__shape-2 rotate-ani"
                            src="{{ asset('client/images/features/features-1/shape-2.svg')}}" alt="shape-2" />
                    </div>
                    <div class="container ed-container">
                        <div class="row">
                            <!-- Single Features Card  -->
                            <div class="col-lg-4 col-md-6 col-12">
                                <div class="ed-features__card wow fadeInUp" data-wow-delay=".3s"
                                    data-wow-duration="1s">
                                    <div class="ed-features__icon icon-bg bg-1">
                                        <img src="{{ asset('client/images/features/features-1/1.svg')}}" alt="icon" />
                                    </div>
                                    <div class="ed-features__info">
                                        <h4>Educator Support</h4>
                                        <p>
                                            Excedteur sint occaecat cupidatat non the proident sunt in culpa
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- Single Features Card  -->
                            <div class="col-lg-4 col-md-6 col-12">
                                <div class="ed-features__card wow fadeInUp" data-wow-delay=".5s"
                                    data-wow-duration="1s">
                                    <div class="ed-features__icon icon-bg bg-2">
                                        <img src="{{ asset('client/images/features/features-1/2.svg')}}" alt="icon" />
                                    </div>
                                    <div class="ed-features__info">
                                        <h4>Top Instructor</h4>
                                        <p>
                                            Excedteur sint occaecat cupidatat non the proident sunt in culpa
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- Single Features Card  -->
                            <div class="col-lg-4 col-md-6 col-12">
                                <div class="ed-features__card wow fadeInUp" data-wow-delay=".7s"
                                    data-wow-duration="1s">
                                    <div class="ed-features__icon icon-bg bg-3">
                                        <img src="{{ asset('client/images/features/features-1/3.svg')}}" alt="icon" />
                                    </div>
                                    <div class="ed-features__info">
                                        <h4>Award Wining</h4>
                                        <p>
                                            Excedteur sint occaecat cupidatat non the proident sunt in culpa
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- End Features Area -->

                <!-- Start Course Area -->
                <section class="ed-course section-gap section-bg-1 position-relative">
                    <div class="ed-course__shapes">
                        <img class="ed-course__shape-1 rotate-ani" src="{{ asset('client/images/course/course-1/shape-1.svg')}}"
                            alt="shape-1" />
                        <img class="ed-course__shape-2 updown-ani"
                            src="{{ asset('client/images/abstracts/abstract-element-regular.svg')}}" alt="shape-2" />
                        <img class="ed-course__shape-3 updown-ani" src="{{ asset('client/images/course/course-1/shape-3.svg')}}"
                            alt="shape-3" />
                    </div>
                    <div class="container ed-container">
                        <div class="row justify-content-center">
                            <div class="col-lg-6 col-md-8 col-12">
                                <div class="ed-section-head text-center">
                                    <span class="ed-section-head__sm-title">ONLINE COURSES</span>
                                    <h3 class="ed-section-head__title ed-split-text left">
                                        Get Your Course With Us
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- Single Course Card -->
                            <div class="col-lg-6 col-xl-4 col-md-6 col-12">
                                <div class="ed-course__card wow fadeInUp" data-wow-delay=".3s"
                                    data-wow-duration="1s">
                                    <a href="course-details.html" class="ed-course__img">
                                        <img src="{{ asset('client/images/course/course-1/1.png')}}" alt="course-img" />
                                    </a>

                                    <a href="course-1.html" class="ed-course__tag">Data Science</a>

                                    <div class="ed-course__body">
                                        <div class="ed-course__lesson">
                                            <div class="ed-course__part">
                                                <i class="fi-rr-book"></i>
                                                <p>23 Lessons</p>
                                            </div>
                                            <div class="ed-course__teacher">
                                                <i class="fi-rr-user"></i>
                                                <p>Harrison Stone</p>
                                            </div>
                                        </div>

                                        <a href="course-details.html" class="ed-course__title">
                                            <h5>
                                                Data Competitive Strategy law and Organization Course
                                            </h5>
                                        </a>

                                        <div class="ed-course__rattings">
                                            <ul>
                                                <li><i class="icofont-star"></i></li>
                                                <li><i class="icofont-star"></i></li>
                                                <li><i class="icofont-star"></i></li>
                                                <li><i class="icofont-star"></i></li>
                                                <li><i class="icofont-star"></i></li>


                                                <li><span>(09 Reviews)</span></li>
                                            </ul>
                                        </div>

                                        <div class="ed-course__bottom">
                                            <span class="ed-course__price">$674.00</span>
                                            <div class="ed-course__students">
                                                <i class="fi fi-rr-graduation-cap"></i>
                                                <p>673 Students</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Single Course Card -->
                            <div class="col-lg-6 col-xl-4 col-md-6 col-12">
                                <div class="ed-course__card wow fadeInUp" data-wow-delay=".5s"
                                    data-wow-duration="1s">
                                    <a href="course-details.html" class="ed-course__img">
                                        <img src="{{ asset('client/images/course/course-1/2.png')}}" alt="course-img" />
                                    </a>

                                    <a href="course-1.html" class="ed-course__tag">Business</a>

                                    <div class="ed-course__body">
                                        <div class="ed-course__lesson">
                                            <div class="ed-course__part">
                                                <i class="fi-rr-book"></i>
                                                <p>04 Lessons</p>
                                            </div>
                                            <div class="ed-course__teacher">
                                                <i class="fi-rr-user"></i>
                                                <p>Alexander Wells</p>
                                            </div>
                                        </div>

                                        <a href="course-details.html" class="ed-course__title">
                                            <h5>
                                                Grow Personal Financial Security Thinking & Principles
                                            </h5>
                                        </a>

                                        <div class="ed-course__rattings">
                                            <ul>
                                                <li><i class="icofont-star"></i></li>
                                                <li><i class="icofont-star"></i></li>
                                                <li><i class="icofont-star"></i></li>
                                                <li><i class="icofont-star"></i></li>
                                                <li><i class="icofont-star"></i></li>
                                                <li><span>(46 Reviews)</span></li>
                                            </ul>
                                        </div>

                                        <div class="ed-course__bottom">
                                            <span class="ed-course__price">$633.00</span>
                                            <div class="ed-course__students">
                                                <i class="fi fi-rr-graduation-cap"></i>
                                                <p>964 Students</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Single Course Card -->
                            <div class="col-lg-6 col-xl-4 col-md-6 col-12">
                                <div class="ed-course__card wow fadeInUp" data-wow-delay=".7s"
                                    data-wow-duration="1s">
                                    <a href="course-details.html" class="ed-course__img">
                                        <img src="{{ asset('client/images/course/course-1/3.png')}}" alt="course-img" />
                                    </a>

                                    <a href="course-1.html" class="ed-course__tag">Design</a>

                                    <div class="ed-course__body">
                                        <div class="ed-course__lesson">
                                            <div class="ed-course__part">
                                                <i class="fi-rr-book"></i>
                                                <p>87 Lessons</p>
                                            </div>
                                            <div class="ed-course__teacher">
                                                <i class="fi-rr-user"></i>
                                                <p>John Smith</p>
                                            </div>
                                        </div>

                                        <a href="course-details.html" class="ed-course__title">
                                            <h5>
                                                The Complete Guide to Build RESTful API Application
                                            </h5>
                                        </a>

                                        <div class="ed-course__rattings">
                                            <ul>
                                                <li><i class="icofont-star"></i></li>
                                                <li><i class="icofont-star"></i></li>
                                                <li><i class="icofont-star"></i></li>
                                                <li><i class="icofont-star"></i></li>
                                                <li><i class="icofont-star"></i></li>
                                                <li><span>(65 Reviews)</span></li>
                                            </ul>
                                        </div>

                                        <div class="ed-course__bottom">
                                            <span class="ed-course__price">$383.00</span>
                                            <div class="ed-course__students">
                                                <i class="fi fi-rr-graduation-cap"></i>
                                                <p>316 Students</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Single Course Card -->
                            <div class="col-lg-6 col-xl-4 col-md-6 col-12">
                                <div class="ed-course__card wow fadeInUp" data-wow-delay=".3s"
                                    data-wow-duration="1s">
                                    <a href="course-details.html" class="ed-course__img">
                                        <img src="{{ asset('client/images/course/course-1/4.png')}}" alt="course-img" />
                                    </a>

                                    <a href="course-1.html" class="ed-course__tag">Development</a>

                                    <div class="ed-course__body">
                                        <div class="ed-course__lesson">
                                            <div class="ed-course__part">
                                                <i class="fi-rr-book"></i>
                                                <p>04 Lessons</p>
                                            </div>
                                            <div class="ed-course__teacher">
                                                <i class="fi-rr-user"></i>
                                                <p>Gabriel Cross</p>
                                            </div>
                                        </div>

                                        <a href="course-details.html" class="ed-course__title">
                                            <h5>
                                                Exploring Learning Landscapes in Academic Business
                                            </h5>
                                        </a>

                                        <div class="ed-course__rattings">
                                            <ul>
                                                <li><i class="icofont-star"></i></li>
                                                <li><i class="icofont-star"></i></li>
                                                <li><i class="icofont-star"></i></li>
                                                <li><i class="icofont-star"></i></li>
                                                <li><i class="icofont-star"></i></li>
                                                <li><span>(94 Reviews)</span></li>
                                            </ul>
                                        </div>

                                        <div class="ed-course__bottom">
                                            <span class="ed-course__price">$356.00</span>
                                            <div class="ed-course__students">
                                                <i class="fi fi-rr-graduation-cap"></i>
                                                <p>352 Students</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Single Course Card -->
                            <div class="col-lg-6 col-xl-4 col-md-6 col-12">
                                <div class="ed-course__card wow fadeInUp" data-wow-delay=".5s"
                                    data-wow-duration="1s">
                                    <a href="course-details.html" class="ed-course__img">
                                        <img src="{{ asset('client/images/course/course-1/5.png')}}" alt="course-img" />
                                    </a>

                                    <a href="course-1.html" class="ed-course__tag">Marketing</a>

                                    <div class="ed-course__body">
                                        <div class="ed-course__lesson">
                                            <div class="ed-course__part">
                                                <i class="fi-rr-book"></i>
                                                <p>04 Lessons</p>
                                            </div>
                                            <div class="ed-course__teacher">
                                                <i class="fi-rr-user"></i>
                                                <p>Maxwell Ford</p>
                                            </div>
                                        </div>

                                        <a href="course-details.html" class="ed-course__title">
                                            <h5>Voices from the Learning Manage Education Hub</h5>
                                        </a>

                                        <div class="ed-course__rattings">
                                            <ul>
                                                <li><i class="icofont-star"></i></li>
                                                <li><i class="icofont-star"></i></li>
                                                <li><i class="icofont-star"></i></li>
                                                <li><i class="icofont-star"></i></li>
                                                <li><i class="icofont-star"></i></li>
                                                <li><span>(09 Reviews)</span></li>
                                            </ul>
                                        </div>

                                        <div class="ed-course__bottom">
                                            <span class="ed-course__price">$643.00</span>
                                            <div class="ed-course__students">
                                                <i class="fi fi-rr-graduation-cap"></i>
                                                <p>553 Students</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Single Course Card -->
                            <div class="col-lg-6 col-xl-4 col-md-6 col-12">
                                <div class="ed-course__card wow fadeInUp" data-wow-delay=".7s"
                                    data-wow-duration="1s">
                                    <a href="course-details.html" class="ed-course__img">
                                        <img src="{{ asset('client/images/course/course-1/6.png')}}" alt="course-img" />
                                    </a>

                                    <a href="course-1.html" class="ed-course__tag">Cyber Security</a>

                                    <div class="ed-course__body">
                                        <div class="ed-course__lesson">
                                            <div class="ed-course__part">
                                                <i class="fi-rr-book"></i>
                                                <p>04 Lessons</p>
                                            </div>
                                            <div class="ed-course__teacher">
                                                <i class="fi-rr-user"></i>
                                                <p>Dominic Chase</p>
                                            </div>
                                        </div>

                                        <a href="course-details.html" class="ed-course__title">
                                            <h5>
                                                Starting SEO as your Home Based Business Courses
                                            </h5>
                                        </a>

                                        <div class="ed-course__rattings">
                                            <ul>
                                                <li><i class="icofont-star"></i></li>
                                                <li><i class="icofont-star"></i></li>
                                                <li><i class="icofont-star"></i></li>
                                                <li><i class="icofont-star"></i></li>
                                                <li><i class="icofont-star"></i></li>
                                                <li><span>(09 Reviews)</span></li>
                                            </ul>
                                        </div>

                                        <div class="ed-course__bottom">
                                            <span class="ed-course__price">$275.00</span>
                                            <div class="ed-course__students">
                                                <i class="fi fi-rr-graduation-cap"></i>
                                                <p>254 Students</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- End Course Area -->

                <!-- Start Why Choose Area -->
                <section class="ed-why-choose section-gap background-image position-relative"
                    style="background-image: url('{{ asset('client/images/section-bg-2.png')}}');">
                    <img class="ed-w-choose__pattern-1" src="{{ asset('client/images/why-choose/why-choose-1/pattern-1.svg')}}"
                        alt="pattern-1" />
                    <div class="container ed-container">
                        <div class="row align-items-center">
                            <div class="col-lg-6 col-12">
                                <div class="ed-w-choose__content">
                                    <div class="ed-section-head">
                                        <span class="ed-section-head__sm-title">WHY CHOOSE US</span>
                                        <h3 class="ed-section-head__title ed-split-text left">
                                            Transform Your Best Practice <br />
                                            with Our Online Course
                                        </h3>
                                        <p class="ed-section-head__text">
                                            Excepteur sint occaecat cupidatat non proident sunt in culpa qui officia
                                            deserunt mollit. Excepteur sint occaecat.
                                        </p>
                                    </div>

                                    <div class="ed-w-choose__info">
                                        <!-- Single Info  -->
                                        <div class="ed-w-choose__info-single">
                                            <div class="ed-w-choose__info-head">
                                                <div class="ed-w-choose__info-icon bg-1">
                                                    <img src="{{ asset('client/images/why-choose/why-choose-1/icon-1.svg')}}"
                                                        alt="icon" />
                                                </div>
                                                <h5>Face-to-face Teaching</h5>
                                            </div>
                                            <div class="ed-w-choose__info-bottom">
                                                <p>
                                                    Excepteur sint occaecat cupidatat non proident sunt in culpa qui
                                                    officia for this is a for that an deserunt mollit.
                                                </p>
                                            </div>
                                        </div>
                                        <!-- Single Info  -->
                                        <div class="ed-w-choose__info-single">
                                            <div class="ed-w-choose__info-head">
                                                <div class="ed-w-choose__info-icon bg-2">
                                                    <img src="{{ asset('client/images/why-choose/why-choose-1/icon-2.svg')}}"
                                                        alt="icon" />
                                                </div>
                                                <h5>24/7 Support Available</h5>
                                            </div>
                                            <div class="ed-w-choose__info-bottom">
                                                <p>
                                                    Excepteur sint occaecat cupidatat non proident sunt in culpa qui
                                                    officia for this is a for that an deserunt mollit.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-12">
                                <div class="ed-w-choose__images position-relative">
                                    <!-- Why Choose Image  -->
                                    <div class="ed-w-choose__main-img">
                                        <img src="{{ asset('client/images/why-choose/why-choose-1/why-choose-img.png')}}"
                                            alt="why-choose-img" />
                                    </div>
                                    <!-- Counter Card -->
                                    <div class="counter-card updown-ani">
                                        <div class="counter-card__icon">
                                            <i class="fi fi-rr-graduation-cap"></i>
                                        </div>
                                        <div class="counter-card__info">
                                            <h4><span class="counter">69</span>K+</h4>
                                            <p>Satisfied Students</p>
                                        </div>
                                    </div>
                                    <!-- Shapes Elements -->
                                    <div class="ed-w-choose__shapes">
                                        <img class="ed-w-choose__shape-1 rotate-ani"
                                            src="{{ asset('client/images/why-choose/why-choose-1/shape-1.svg')}}" alt="shape-1" />
                                        <img class="ed-w-choose__shape-2"
                                            src="{{ asset('client/images/why-choose/why-choose-1/pattern-2.svg')}}"
                                            alt="pattern-2" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- End Why Choose Area -->

                <!-- Start Funfact Area -->
                <section class="ed-funfact">
                    <div class="container ed-container position-relative overflow-hidden">
                        <div class="ed-funfact__shapes">
                            <img class="ed-funfact__shape-1 updown-ani"
                                src="{{ asset('client/images/funfact/funfact-1/shape-1.svg')}}" alt="shape-1" />
                            <img class="ed-funfact__shape-2 rotate-ani"
                                src="{{ asset('client/images/funfact/funfact-1/shape-2.svg')}}" alt="shape-1" />
                        </div>
                        <div class="ed-funfact__inner">
                            <div class="ed-funfact__img">
                                <img src="{{ asset('client/images/funfact/funfact-1/funfact-img.png')}}" alt="funfact-img" />
                            </div>
                            <div class="ed-funfact__content">
                                <div class="row">
                                    <!-- Single Counter  -->
                                    <div class="col-lg-6 col-md-6 col-6">
                                        <div class="ed-funfact__counter mg-btm-80">
                                            <h4><span class="counter">5923</span>+</h4>
                                            <p>Student enrolled</p>
                                        </div>
                                    </div>

                                    <!-- Single Counter  -->
                                    <div class="col-lg-6 col-md-6 col-6">
                                        <div class="ed-funfact__counter mg-btm-80">
                                            <h4><span class="counter">8497</span>+</h4>
                                            <p>Classes completed</p>
                                        </div>
                                    </div>

                                    <!-- Single Counter  -->
                                    <div class="col-lg-6 col-md-6 col-6">
                                        <div class="ed-funfact__counter">
                                            <h4><span class="counter">7554</span>+</h4>
                                            <p>Learners report</p>
                                        </div>
                                    </div>

                                    <!-- Single Counter  -->
                                    <div class="col-lg-6 col-md-6 col-6">
                                        <div class="ed-funfact__counter">
                                            <h4><span class="counter">2755</span>+</h4>
                                            <p>Top instructors</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- End Funfact Area -->

                <!-- Start Partner Area -->
                <section class="ed-partner section-gap">
                    <div class="container ed-container">
                        <div class="row">
                            <div class="col-12">
                                <div class="ed-partner__section-head">
                                    <h3 class="ed-partner__section-head-title">Get in touch with the <span>250+</span>
                                        companies who Collaboration us</h3>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="owl-carousel ed-partner__slider">
                                    <!-- Single Slider  -->
                                    <a href="#" target="_blank" class="ed-parnet__brand-logo">
                                        <img src="{{ asset('client/images/partner/partner-1/1.svg')}}" alt="brand-logo" />
                                    </a>
                                    <!-- Single Slider  -->
                                    <a href="#" target="_blank" class="ed-parnet__brand-logo">
                                        <img src="{{ asset('client/images/partner/partner-1/2.svg')}}" alt="brand-logo" />
                                    </a>
                                    <!-- Single Slider  -->
                                    <a href="#" target="_blank" class="ed-parnet__brand-logo">
                                        <img src="{{ asset('client/images/partner/partner-1/3.svg')}}" alt="brand-logo" />
                                    </a>
                                    <!-- Single Slider  -->
                                    <a href="#" target="_blank" class="ed-parnet__brand-logo">
                                        <img src="{{ asset('client/images/partner/partner-1/4.svg')}}" alt="brand-logo" />
                                    </a>
                                    <!-- Single Slider  -->
                                    <a href="#" target="_blank" class="ed-parnet__brand-logo">
                                        <img src="{{ asset('client/images/partner/partner-1/5.svg')}}" alt="brand-logo" />
                                    </a>
                                    <!-- Single Slider  -->
                                    <a href="#" target="_blank" class="ed-parnet__brand-logo">
                                        <img src="{{ asset('client/images/partner/partner-1/6.svg')}}" alt="brand-logo" />
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- End Partner Area -->

                <!-- Start Testimonial Area -->
                <section class="ed-testimonial section-bg-color-1 section-gap">
                    <div class="container ed-container">
                        <div class="row align-items-center">
                            <div class="col-lg-6 col-12">
                                <!-- Testimonial Content  -->
                                <div class="ed-testimonial__content">
                                    <div class="ed-section-head">
                                        <span class="ed-section-head__sm-title">OUR TESTIMONIAL</span>
                                        <h3 class="ed-section-head__title ed-split-text left">
                                            What Student Say About Our Online Education Course
                                        </h3>
                                    </div>

                                    <div class="owl-carousel ed-testimonial__slider">
                                        <!-- Single Testimonial  -->
                                        <div class="ed-testimonial__slider-item">
                                            <ul class="ed-testimonial__rattings">
                                                <li><i class="icofont-star"></i></li>
                                                <li><i class="icofont-star"></i></li>
                                                <li><i class="icofont-star"></i></li>
                                                <li><i class="icofont-star"></i></li>
                                                <li><i class="icofont-star"></i></li>
                                            </ul>
                                            <p class="ed-testimonial__text">
                                                “ Attending EduVibe School of Business was one of the best decisions
                                                I've ever made. The curriculum was practical and industry-focused, and I
                                                was able to apply what I learned in the
                                                classroom.”
                                            </p>
                                            <div class="ed-testimonial__author">
                                                <div class="ed-testimonial__author-img">
                                                    <img src="{{ asset('client/images/testimonial/testimonial-1/author-1.png')}}"
                                                        alt="author-img" />
                                                </div>
                                                <div class="ed-testimonial__author-info">
                                                    <h5>John Smith</h5>
                                                    <p>Science Student</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Single Testimonial  -->
                                        <div class="ed-testimonial__slider-item">
                                            <ul class="ed-testimonial__rattings">
                                                <li><i class="icofont-star"></i></li>
                                                <li><i class="icofont-star"></i></li>
                                                <li><i class="icofont-star"></i></li>
                                                <li><i class="icofont-star"></i></li>
                                                <li><i class="icofont-star"></i></li>
                                            </ul>
                                            <p class="ed-testimonial__text">
                                                “ Attending EduVibe School of Business was one of the best decisions
                                                I've ever made. The curriculum was practical and industry-focused, and I
                                                was able to apply what I learned in the
                                                classroom.”
                                            </p>
                                            <div class="ed-testimonial__author">
                                                <div class="ed-testimonial__author-img">
                                                    <img src="{{ asset('client/images/testimonial/testimonial-1/author-1.png')}}"
                                                        alt="author-img" />
                                                </div>
                                                <div class="ed-testimonial__author-info">
                                                    <h5>John Smith</h5>
                                                    <p>Science Student</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-12">
                                <!-- Testimonial Images -->
                                <div class="ed-testimonial__images position-relative">
                                    <div class="ed-testimonial__main-img">
                                        <img src="{{ asset('client/images/testimonial/testimonial-1/testimonial-img.png')}}"
                                            alt="testimonial-img" />
                                    </div>

                                    <!-- Counter Card -->
                                    <div class="counter-card updown-ani">
                                        <div class="counter-card__icon">
                                            <i class="fi fi-rr-graduation-cap"></i>
                                        </div>
                                        <div class="counter-card__info">
                                            <h4><span class="counter">667</span>K+</h4>
                                            <p>Satisfied Students</p>
                                        </div>
                                    </div>

                                    <!-- Testimonial Shapes -->
                                    <div class="ed-testimonial__shapes">
                                        <img class="ed-testimonial__shape-1"
                                            src="{{ asset('client/images/testimonial/testimonial-1/shape-1.svg')}}"
                                            alt="shape-1" />
                                        <img class="ed-testimonial__shape-2"
                                            src="{{ asset('client/images/testimonial/testimonial-1/shape-2.svg')}}"
                                            alt="shape-2" />
                                        <img class="ed-testimonial__shape-3 rotate-ani"
                                            src="{{ asset('client/images/testimonial/testimonial-1/shape-3.svg')}}"
                                            alt="shape-3" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- End Testimonial Area -->

                <div class="section-bg background-image"
                    style="background-image: url('{{ asset('client/images/section-bg-3.png')}}');">
                    <!-- Start Blog Area -->
                    <section class="ed-blog section-gap">
                        <div class="container ed-container">
                            <div class="row justify-content-center">
                                <div class="col-lg-6 col-md-8 col-12">
                                    <div class="ed-section-head text-center">
                                        <span class="ed-section-head__sm-title">OUR NEWS</span>
                                        <h3 class="ed-section-head__title ed-split-text left">
                                            Our New Articles
                                        </h3>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Single Blog -->
                                <div class="col-lg-4 col-md-6 col-12">
                                    <div class="ed-blog__card wow fadeInUp" data-wow-delay=".3s"
                                        data-wow-duration="1s">
                                        <div class="ed-blog__head">
                                            <div class="ed-blog__img">
                                                <img src="{{ asset('client/images/blog/blog-1/1.png')}}" alt="blog-img" />
                                            </div>
                                            <a href="blog.html" class="ed-blog__category">Education</a>
                                        </div>
                                        <div class="ed-blog__content">
                                            <ul class="ed-blog__meta">
                                                <li><i class="fi fi-rr-calendar"></i>09 May, 2025</li>
                                                <li><i class="fi fi-rr-comment-alt-dots"></i>32 Comments</li>
                                            </ul>
                                            <a href="blog-details.html" class="ed-blog__title">
                                                <h4>
                                                    Solutions Your All Problem With Online Courses For Your Thinking
                                                </h4>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Single Blog -->
                                <div class="col-lg-4 col-md-6 col-12">
                                    <div class="ed-blog__card wow fadeInUp" data-wow-delay=".5s"
                                        data-wow-duration="1s">
                                        <div class="ed-blog__head">
                                            <div class="ed-blog__img">
                                                <img src="{{ asset('client/images/blog/blog-1/2.png')}}" alt="blog-img" />
                                            </div>
                                            <a href="blog.html" class="ed-blog__category">Business</a>
                                        </div>
                                        <div class="ed-blog__content">
                                            <ul class="ed-blog__meta">
                                                <li><i class="fi fi-rr-calendar"></i>09 January, 2025</li>
                                                <li><i class="fi fi-rr-comment-alt-dots"></i>98 Comments</li>
                                            </ul>
                                            <a href="blog-details.html" class="ed-blog__title">
                                                <h4>
                                                    Exploring Learning Landscapes in All Academic Calendar For Season
                                                </h4>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Single Blog -->
                                <div class="col-lg-4 col-md-6 col-12">
                                    <div class="ed-blog__card wow fadeInUp" data-wow-delay=".7s"
                                        data-wow-duration="1s">
                                        <div class="ed-blog__head">
                                            <div class="ed-blog__img">
                                                <img src="{{ asset('client/images/blog/blog-1/3.png')}}" alt="blog-img" />
                                            </div>
                                            <a href="blog.html" class="ed-blog__category">Marketing</a>
                                        </div>
                                        <div class="ed-blog__content">
                                            <ul class="ed-blog__meta">
                                                <li><i class="fi fi-rr-calendar"></i>03 June, 2025</li>
                                                <li><i class="fi fi-rr-comment-alt-dots"></i>04 Comments</li>
                                            </ul>
                                            <a href="blog-details.html" class="ed-blog__title">
                                                <h4>
                                                    Voices from the Learning Education Hub For Your Children
                                                </h4>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- End Blog Area -->

                </div>
            </main>




@endsection
