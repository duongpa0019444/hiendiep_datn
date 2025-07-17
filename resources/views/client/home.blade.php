@extends('client.client')

@section('title', 'Trang chủ')
@section('description', '')
@section('content')

    @if (session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Toastify({
                        text: "{{ session('success') }}",
                        gravity: "top",
                        position: "center",
                        className: "success",
                        duration: 4000
                    }).showToast();
                });
            </script>
        @endif

    <main>
        <div class="section-bg hero-bg background-image"
            style="background-image: url('{{ asset('client/images/hero/home-1/hero-bg.png') }}');">


            <!-- Start Hero Area -->
            <!-- Start Hero Area -->
            <section class="ed-hero">
                <div class="container ed-container-expand">
                    <!-- Hero Element Shape -->
                    <div class="ed-hero__elements">
                        <img class="element-move ed-hero__shape-1" src="{{ asset('client/images/hero/home-1/shape-1.svg') }}"
                            alt="shape-1" />
                        <img class="element-move ed-hero__shape-2" src="{{ asset('client/images/hero/home-1/shape-2.svg') }}"
                            alt="shape-1" />
                        <img class="element-move ed-hero__shape-3"
                            src="{{ asset('client/images/hero/home-1/shape-3.svg') }}" alt="shape-1" />
                        <img class="element-move ed-hero__shape-4"
                            src="{{ asset('client/images/hero/home-1/shape-4.svg') }}" alt="shape-1" />
                        <img class="element-move ed-hero__shape-5"
                            src="{{ asset('client/images/hero/home-1/shape-5.png') }}" alt="shape-5" />
                    </div>
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-12">
                            <!-- Hero Content -->
                            <div class="ed-hero__content">
                                <h1 class="ed-hero__content-title ed-split-text left">Nền tảng <span>học tiếng Anh</span>
                                    tốt nhất tại Hiền Diệp</h1>
                                <p class="ed-hero__content-text">
                                    Cải thiện kỹ năng tiếng Anh với các khóa học đa dạng, giáo viên tận tâm và phương pháp
                                    giảng dạy hiện đại.
                                </p>
                                <div class="ed-hero__btn">
                                    <a href="courses.html" class="ed-btn">Khám phá khóa học<i
                                            class="fi fi-rr-arrow-small-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-12">
                            <!-- Hero Image -->
                            <div class="ed-hero__image">
                                <img src="{{ asset('client/images/hero/home-1/hero-img.png') }}" alt="hero-img" />
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- End Hero Area -->
        </div>

        <div class="section-bg background-image"
            style="background-image: url('{{ asset('client/images/section-bg-1.png') }}');">
            <!-- Start About Area -->
            <section class="ed-about section-gap position-relative">
                <div class="container ed-container">
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-12">
                            <!-- About Images  -->
                            <div class="ed-about__images">
                                <div class="ed-about__main-img">
                                    <img src="{{ asset('client/images/about/about-1/about-img.png') }}" alt="about-img" />
                                </div>
                                <div class="counter-card updown-ani">
                                    <div class="counter-card__icon">
                                        <i class="fi fi-rr-graduation-cap"></i>
                                    </div>
                                    <div class="counter-card__info">
                                        <h4><span class="counter">5000</span>+</h4>
                                        <p>Học viên tham gia</p>
                                    </div>
                                </div>

                                <div class="ed-about__shapes">
                                    <img class="ed-about__shape-1"
                                        src="{{ asset('client/images/about/about-1/shape-1.svg') }}" alt="shape-1" />
                                    <img class="ed-about__shape-2"
                                        src="{{ asset('client/images/about/about-1/shape-2.svg') }}" alt="shape-2" />
                                    <img class="ed-about__shape-3 rotate-ani"
                                        src="{{ asset('client/images/about/about-1/shape-3.svg') }}" alt="shape-3" />
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-12 order-class">
                            <!-- About Content  -->
                            <div class="ed-about__content">
                                <div class="ed-section-head">
                                    <span class="ed-section-head__sm-title">CHÀO MỪNG ĐẾN VỚI HIỀN DIỆP</span>
                                    <h3 class="ed-section-head__title ed-split-text left">
                                        Trung tâm Anh ngữ Hiền Diệp: <br />
                                        Hành trình chinh phục tiếng Anh
                                    </h3>
                                    <p class="ed-section-head__text">
                                        Với sứ mệnh mang đến môi trường học tập chất lượng, chúng tôi giúp học viên tự tin
                                        giao tiếp và đạt mục tiêu tiếng Anh.
                                    </p>
                                </div>
                                <div class="ed-about__feature">
                                    <ul class="ed-about__features-list">
                                        <li><img src="{{ asset('client/images/icons/icon-check-blue.svg') }}"
                                                alt="icon-check-blue" />Giáo viên giàu kinh nghiệm</li>
                                        <li><img src="{{ asset('client/images/icons/icon-check-blue.svg') }}"
                                                alt="icon-check-blue" />Học trực tuyến và trực tiếp</li>
                                        <li><img src="{{ asset('client/images/icons/icon-check-blue.svg') }}"
                                                alt="icon-check-blue" />Chương trình học dễ hiểu</li>
                                        <li><img src="{{ asset('client/images/icons/icon-check-blue.svg') }}"
                                                alt="icon-check-blue" />Hỗ trợ học viên mọi lúc</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <img class="ed-about__shape-4" src="{{ asset('client/images/abstracts/abstract-element-regular.svg') }}"
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
                                    <span class="ed-section-head__sm-title">DANH MỤC KHÓA HỌC</span>
                                    <h3 class="ed-section-head__title m-0 ed-split-text left">
                                        Các khóa học tiếng Anh nổi bật
                                    </h3>
                                </div>
                                <div class="ed-section-head__btn">
                                    <a href="{{ route('admin.course-list') }}" class="ed-btn">Khám phá khóa học<i
                                            class="fi fi-rr-arrow-small-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        @forelse ($featuredCourses as $course)
                            <!-- Hiển thị khóa học -->
                            <div class="col-lg-6 col-xl-4 col-md-6 col-12">
                                <div class="ed-course__card wow fadeInUp" data-wow-delay=".3s" data-wow-duration="1s">
                                    <a href="{{ route('admin.course-detail', $course->id) }}" class="ed-course__img">
                                        <img src="{{ asset($course->image) }}" alt="course-img" style="height:150px" />
                                    </a>

                                    <a href="#" class="ed-course__tag">Tiếng Anh</a>

                                    <div class="ed-course__body">
                                        <div class="ed-course__lesson">
                                            <div class="ed-course__part">
                                                <i class="fi-rr-book"></i>
                                                <p>{{ $course->total_sessions }} Buổi học</p>
                                            </div>
                                            <div class="ed-course__teacher">
                                                <i class="fi-rr-user"></i>
                                                <p>{{ $course->teacher_name ?? 'Giảng viên' }}</p>
                                            </div>
                                        </div>

                                        <a href="{{ route('admin.course-detail', $course->id) }}"
                                            class="ed-course__title">
                                            <h5>{{ $course->name }}</h5>
                                        </a>

                                        <div class="ed-course__bottom">
                                            <span class="ed-course__price">{{ number_format($course->price) }} VNĐ</span>
                                            <div class="ed-course__students">
                                                <i class="fi fi-rr-graduation-cap"></i>
                                                <p>{{ $course->students_count ?? 0 }} Học viên</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-center">Hiện chưa có khóa học nổi bật nào.</p>
                        @endforelse
                    </div>
                </div>
            </section>
            <!-- End Category Area -->
        </div>
        <!-- Start Features Area -->
        <section class="ed-features position-relative">
            <div class="ed-category__shapes">
                <img class="ed-category__shape-1 updown-ani"
                    src="{{ asset('client/images/features/features-1/shape-1.svg') }}" alt="shape-1" />
                <img class="ed-category__shape-2 rotate-ani"
                    src="{{ asset('client/images/features/features-1/shape-2.svg') }}" alt="shape-2" />
            </div>
            <div class="container ed-container">
                <div class="row">
                    <!-- Single Features Card  -->
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="ed-features__card wow fadeInUp" data-wow-delay=".3s" data-wow-duration="1s">
                            <div class="ed-features__icon icon-bg bg-1">
                                <img src="{{ asset('client/images/features/features-1/1.svg') }}" alt="icon" />
                            </div>
                            <div class="ed-features__info">
                                <h4>Hỗ trợ học viên</h4>
                                <p>
                                    Đội ngũ tư vấn luôn sẵn sàng giải đáp mọi thắc mắc và hỗ trợ học viên trong suốt quá
                                    trình học.
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Single Features Card  -->
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="ed-features__card wow fadeInUp" data-wow-delay=".5s" data-wow-duration="1s">
                            <div class="ed-features__icon icon-bg bg-2">
                                <img src="{{ asset('client/images/features/features-1/2.svg') }}" alt="icon" />
                            </div>
                            <div class="ed-features__info">
                                <h4>Giáo viên hàng đầu</h4>
                                <p>
                                    Đội ngũ giáo viên bản ngữ và Việt Nam giàu kinh nghiệm, tận tâm với từng học viên.
                                </p>
                            </div>
                        </div>

                    </div>
                    <!-- Single Features Card  -->
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="ed-features__card wow fadeInUp" data-wow-delay=".7s" data-wow-duration="1s">
                            <div class="ed-features__icon icon-bg bg-3">
                                <img src="{{ asset('client/images/features/features-1/3.svg') }}" alt="icon" />
                            </div>
                            <div class="ed-features__info">
                                <h4>Chất lượng được công nhận</h4>
                                <p>
                                    Hiền Diệp được đánh giá cao bởi hàng nghìn học viên và các tổ chức giáo dục uy tín.
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
                <img class="ed-course__shape-1 rotate-ani" src="{{ asset('client/images/course/course-1/shape-1.svg') }}"
                    alt="shape-1" />
                <img class="ed-course__shape-2 updown-ani"
                    src="{{ asset('client/images/abstracts/abstract-element-regular.svg') }}" alt="shape-2" />
                <img class="ed-course__shape-3 updown-ani" src="{{ asset('client/images/course/course-1/shape-3.svg') }}"
                    alt="shape-3" />
            </div>
            <div class="container ed-container">
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-md-8 col-12">
                        <div class="ed-section-head text-center">
                            <span class="ed-section-head__sm-title">KHÓA HỌC TIẾNG ANH</span>
                            <h3 class="ed-section-head__title ed-split-text left">
                                Tìm khóa học phù hợp với bạn
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- Single Course Card -->
                    <div class="col-lg-6 col-xl-4 col-md-6 col-12">
                        <div class="ed-course__card wow fadeInUp" data-wow-delay=".3s" data-wow-duration="1s">
                            <a href="course-details.html" class="ed-course__img">
                                <img src="{{ asset('client/images/course/course-1/1.png') }}" alt="course-img" />
                            </a>

                            <a href="courses.html" class="ed-course__tag">Tiếng Anh giao tiếp</a>

                            <div class="ed-course__body">
                                <div class="ed-course__lesson">
                                    <div class="ed-course__part">
                                        <i class="fi-rr-book"></i>
                                        <p>15 Bài học</p>
                                    </div>
                                    <div class="ed-course__teacher">
                                        <i class="fi-rr-user"></i>
                                        <p>Nguyễn Minh Anh</p>

                                    </div>
                                </div>

                                <a href="course-details.html" class="ed-course__title">
                                    <h5>
                                        Khóa học giao tiếp tiếng Anh cơ bản
                                    </h5>
                                </a>

                                <div class="ed-course__rattings">
                                    <ul>
                                        <li><i class="icofont-star"></i></li>
                                        <li><i class="icofont-star"></i></li>
                                        <li><i class="icofont-star"></i></li>
                                        <li><i class="icofont-star"></i></li>
                                        <li><i class="icofont-star"></i></li>
                                        <li><span>(120 Đánh giá)</span></li>
                                    </ul>
                                </div>

                                <div class="ed-course__bottom">
                                    <span class="ed-course__price">3.500.000 VNĐ</span>
                                    <div class="ed-course__students">
                                        <i class="fi fi-rr-graduation-cap"></i>
                                        <p>450 Học viên</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Single Course Card -->
                    <div class="col-lg-6 col-xl-4 col-md-6 col-12">
                        <div class="ed-course__card wow fadeInUp" data-wow-delay=".5s" data-wow-duration="1s">
                            <a href="course-details.html" class="ed-course__img">
                                <img src="{{ asset('client/images/course/course-1/2.png') }}" alt="course-img" />
                            </a>

                            <a href="courses.html" class="ed-course__tag">Luyện thi IELTS</a>

                            <div class="ed-course__body">
                                <div class="ed-course__lesson">
                                    <div class="ed-course__part">
                                        <i class="fi-rr-book"></i>
                                        <p>20 Bài học</p>
                                    </div>
                                    <div class="ed-course__teacher">
                                        <i class="fi-rr-user"></i>
                                        <p>John Taylor</p>
                                    </div>
                                </div>

                                <a href="course-details.html" class="ed-course__title">
                                    <h5>
                                        Luyện thi IELTS 6.5+ chuyên sâu
                                    </h5>
                                </a>

                                <div class="ed-course__rattings">
                                    <ul>
                                        <li><i class="icofont-star"></i></li>
                                        <li><i class="icofont-star"></i></li>
                                        <li><i class="icofont-star"></i></li>
                                        <li><i class="icofont-star"></i></li>
                                        <li><i class="icofont-star"></i></li>
                                        <li><span>(85 Đánh giá)</span></li>
                                    </ul>
                                </div>

                                <div class="ed-course__bottom">
                                    <span class="ed-course__price">5.000.000 VNĐ</span>
                                    <div class="ed-course__students">
                                        <i class="fi fi-rr-graduation-cap"></i>
                                        <p>300 Học viên</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Single Course Card -->
                    <div class="col-lg-6 col-xl-4 col-md-6 col-12">
                        <div class="ed-course__card wow fadeInUp" data-wow-delay=".7s" data-wow-duration="1s">
                            <a href="course-details.html" class="ed-course__img">
                                <img src="{{ asset('client/images/course/course-1/3.png') }}" alt="course-img" />
                            </a>

                            <a href="courses.html" class="ed-course__tag">Tiếng Anh trẻ em</a>

                            <div class="ed-course__body">
                                <div class="ed-course__lesson">
                                    <div class="ed-course__part">
                                        <i class="fi-rr-book"></i>
                                        <p>12 Bài học</p>
                                    </div>
                                    <div class="ed-course__teacher">
                                        <i class="fi-rr-user"></i>
                                        <p>Trần Thị Hương</p>
                                    </div>
                                </div>

                                <a href="course-details.html" class="ed-course__title">
                                    <h5>
                                        Tiếng Anh cho trẻ em từ 6-12 tuổi
                                    </h5>
                                </a>

                                <div class="ed-course__rattings">
                                    <ul>
                                        <li><i class="icofont-star"></i></li>
                                        <li><i class="icofont-star"></i></li>
                                        <li><i class="icofont-star"></i></li>
                                        <li><i class="icofont-star"></i></li>
                                        <li><i class="icofont-star"></i></li>
                                        <li><span>(150 Đánh giá)</span></li>
                                    </ul>
                                </div>

                                <div class="ed-course__bottom">
                                    <span class="ed-course__price">2.800.000 VNĐ</span>
                                    <div class="ed-course__students">
                                        <i class="fi fi-rr-graduation-cap"></i>
                                        <p>600 Học viên</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-12">
                        <div class="ed-section-bottom-btn">
                            <a href="course-1.html" class="ed-btn">View All Courses<i
                                    class="fi fi-rr-arrow-small-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Course Area -->

        <!-- Start Why Choose Area -->
        <section class="ed-why-choose section-gap background-image position-relative"
            style="background-image: url('{{ asset('client/images/section-bg-2.png') }}');">
            <img class="ed-w-choose__pattern-1" src="{{ asset('client/images/why-choose/why-choose-1/pattern-1.svg') }}"
                alt="pattern-1" />
            <div class="container ed-container">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-12">
                        <div class="ed-w-choose__content">
                            <div class="ed-section-head">
                                <span class="ed-section-head__sm-title">TẠI SAO CHỌN CHÚNG TÔI</span>
                                <h3 class="ed-section-head__title ed-split-text left">
                                    Biến ước mơ tiếng Anh <br />
                                    thành hiện thực với Hiền Diệp
                                </h3>
                                <p class="ed-section-head__text">
                                    Chúng tôi mang đến môi trường học tập thân thiện, chuyên nghiệp và các khóa học được
                                    thiết kế phù hợp với từng học viên.
                                </p>
                            </div>

                            <div class="ed-w-choose__info">
                                <!-- Single Info  -->
                                <div class="ed-w-choose__info-single">
                                    <div class="ed-w-choose__info-head">
                                        <div class="ed-w-choose__info-icon bg-1">
                                            <img src="{{ asset('client/images/why-choose/why-choose-1/icon-1.svg') }}"
                                                alt="icon" />
                                        </div>
                                        <h5>Giảng dạy trực tiếp</h5>
                                    </div>
                                    <div class="ed-w-choose__info-bottom">
                                        <p>
                                            Học viên được tương tác trực tiếp với giáo viên, giúp nâng cao hiệu quả học tập.
                                        </p>
                                    </div>
                                </div>
                                <!-- Single Info  -->
                                <div class="ed-w-choose__info-single">
                                    <div class="ed-w-choose__info-head">
                                        <div class="ed-w-choose__info-icon bg-2">
                                            <img src="{{ asset('client/images/why-choose/why-choose-1/icon-2.svg') }}"
                                                alt="icon" />
                                        </div>
                                        <h5>Hỗ trợ 24/7</h5>
                                    </div>
                                    <div class="ed-w-choose__info-bottom">
                                        <p>
                                            Đội ngũ hỗ trợ luôn sẵn sàng giải đáp mọi thắc mắc của học viên bất cứ lúc nào.
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
                                <img src="{{ asset('client/images/why-choose/why-choose-1/why-choose-img.png') }}"
                                    alt="why-choose-img" />
                            </div>
                            <!-- Counter Card -->
                            <div class="counter-card updown-ani">
                                <div class="counter-card__icon">
                                    <i class="fi fi-rr-graduation-cap"></i>
                                </div>
                                <div class="counter-card__info">
                                    <h4><span class="counter">10</span>K+</h4>
                                    <p>Học viên hài lòng</p>
                                </div>
                            </div>
                            <!-- Shapes Elements -->
                            <div class="ed-w-choose__shapes">
                                <img class="ed-w-choose__shape-1 rotate-ani"
                                    src="{{ asset('client/images/why-choose/why-choose-1/shape-1.svg') }}"
                                    alt="shape-1" />
                                <img class="ed-w-choose__shape-2"
                                    src="{{ asset('client/images/why-choose/why-choose-1/pattern-2.svg') }}"
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
                        src="{{ asset('client/images/funfact/funfact-1/shape-1.svg') }}" alt="shape-1" />
                    <img class="ed-funfact__shape-2 rotate-ani"
                        src="{{ asset('client/images/funfact/funfact-1/shape-2.svg') }}" alt="shape-1" />
                </div>
                <div class="ed-funfact__inner">
                    <div class="ed-funfact__img">
                        <img src="{{ asset('client/images/funfact/funfact-1/funfact-img.png') }}" alt="funfact-img" />
                    </div>
                    <div class="ed-funfact__content">
                        <div class="row">
                            <!-- Single Counter  -->
                            <div class="col-lg-6 col-md-6 col-6">
                                <div class="ed-funfact__counter mg-btm-80">
                                    <h4><span class="counter">5000</span>+</h4>
                                    <p>Học viên tham gia</p>
                                </div>
                            </div>

                            <!-- Single Counter  -->
                            <div class="col-lg-6 col-md-6 col-6">
                                <div class="ed-funfact__counter mg-btm-80">
                                    <h4><span class="counter">1000</span>+</h4>
                                    <p>Lớp học hoàn thành</p>
                                </div>
                            </div>

                            <!-- Single Counter  -->
                            <div class="col-lg-6 col-md-6 col-6">
                                <div class="ed-funfact__counter">
                                    <h4><span class="counter">3000</span>+</h4>
                                    <p>Báo cáo học tập</p>
                                </div>
                            </div>

                            <!-- Single Counter  -->
                            <div class="col-lg-6 col-md-6 col-6">
                                <div class="ed-funfact__counter">
                                    <h4><span class="counter">50</span>+</h4>
                                    <p>Giáo viên xuất sắc</p>
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
                            <h3 class="ed-partner__section-head-title">Hợp tác với hơn <span>50+</span>
                                tổ chức giáo dục uy tín</h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="owl-carousel ed-partner__slider">
                            <!-- Single Slider  -->
                            <a href="#" target="_blank" class="ed-parnet__brand-logo">
                                <img src="{{ asset('client/images/partner/partner-1/1.svg') }}" alt="brand-logo" />
                            </a>
                            <!-- Single Slider  -->
                            <a href="#" target="_blank" class="ed-parnet__brand-logo">
                                <img src="{{ asset('client/images/partner/partner-1/2.svg') }}" alt="brand-logo" />
                            </a>
                            <!-- Single Slider  -->
                            <a href="#" target="_blank" class="ed-parnet__brand-logo">
                                <img src="{{ asset('client/images/partner/partner-1/3.svg') }}" alt="brand-logo" />
                            </a>
                            <!-- Single Slider  -->
                            <a href="#" target="_blank" class="ed-parnet__brand-logo">
                                <img src="{{ asset('client/images/partner/partner-1/4.svg') }}" alt="brand-logo" />
                            </a>
                            <!-- Single Slider  -->
                            <a href="#" target="_blank" class="ed-parnet__brand-logo">
                                <img src="{{ asset('client/images/partner/partner-1/5.svg') }}" alt="brand-logo" />
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
                                <span class="ed-section-head__sm-title">CẢM NHẬN HỌC VIÊN</span>
                                <h3 class="ed-section-head__title ed-split-text left">
                                    Học viên nói gì về Hiền Diệp
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
                                        “Học tại Hiền Diệp là quyết định đúng đắn nhất của tôi. Giáo viên nhiệt tình, phương
                                        pháp dạy dễ hiểu, giúp tôi tự tin giao tiếp chỉ sau 3 tháng.”
                                    </p>
                                    <div class="ed-testimonial__author">
                                        <div class="ed-testimonial__author-img">
                                            <img src="{{ asset('client/images/testimonial/testimonial-1/author-1.png') }}"
                                                alt="author-img" />
                                        </div>
                                        <div class="ed-testimonial__author-info">
                                            <h5>Nguyễn Thị Mai</h5>
                                            <p>Học viên giao tiếp</p>
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
                                        “Khóa học IELTS tại Hiền Diệp giúp tôi đạt band 7.0. Giáo viên tận tâm, tài liệu cập
                                        nhật và môi trường học rất thân thiện.”
                                    </p>
                                    <div class="ed-testimonial__author">
                                        <div class="ed-testimonial__author-img">
                                            <img src="{{ asset('client/images/testimonial/testimonial-1/author-1.png') }}"
                                                alt="author-img" />
                                        </div>
                                        <div class="ed-testimonial__author-info">
                                            <h5>Trần Văn Hùng</h5>
                                            <p>Học viên IELTS</p>
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
                                <img src="{{ asset('client/images/testimonial/testimonial-1/testimonial-img.png') }}"
                                    alt="testimonial-img" />
                            </div>

                            <!-- Counter Card -->
                            <div class="counter-card updown-ani">
                                <div class="counter-card__icon">
                                    <i class="fi fi-rr-graduation-cap"></i>
                                </div>
                                <div class="counter-card__info">
                                    <h4><span class="counter">10</span>K+</h4>
                                    <p>Học viên hài lòng</p>
                                </div>
                            </div>

                            <!-- Testimonial Shapes -->
                            <div class="ed-testimonial__shapes">
                                <img class="ed-testimonial__shape-1"
                                    src="{{ asset('client/images/testimonial/testimonial-1/shape-1.svg') }}"
                                    alt="shape-1" />
                                <img class="ed-testimonial__shape-2"
                                    src="{{ asset('client/images/testimonial/testimonial-1/shape-2.svg') }}"
                                    alt="shape-2" />
                                <img class="ed-testimonial__shape-3 rotate-ani"
                                    src="{{ asset('client/images/testimonial/testimonial-1/shape-3.svg') }}"
                                    alt="shape-3" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Testimonial Area -->
        <div class="section-bg background-image"
            style="background-image: url('{{ asset('client/images/section-bg-english.png') }}');">
            <!-- Start Blog Area -->
            <section class="ed-blog section-gap">
                <div class="container ed-container">
                    <div class="row justify-content-center">
                        <div class="col-lg-6 col-md-8 col-12">
                            <div class="ed-section-head text-center">
                                <span class="ed-section-head__sm-title">TIN TỨC & MẸO HỌC</span>
                                <h3 class="ed-section-head__title ed-split-text left">
                                    Bài Viết Mới Về Học Tiếng Anh
                                </h3>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Single Blog -->
                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="ed-blog__card wow fadeInUp" data-wow-delay=".3s" data-wow-duration="1s">
                                <div class="ed-blog__head">
                                    <div class="ed-blog__img">
                                        <img src="{{ asset('client/images/blog/blog-1/1.png') }}"
                                            alt="english-blog-img" />
                                    </div>
                                    <a href="blog.html" class="ed-blog__category">Kỹ Năng Nói</a>
                                </div>
                                <div class="ed-blog__content">
                                    <ul class="ed-blog__meta">
                                        <li><i class="fi fi-rr-calendar"></i>15 Tháng 4, 2025</li>
                                        <li><i class="fi fi-rr-comment-alt-dots"></i>25 Bình Luận</li>
                                    </ul>
                                    <a href="blog-details.html" class="ed-blog__title">
                                        <h4>
                                            5 Mẹo Hàng Đầu Để Cải Thiện Kỹ Năng Nói Tiếng Anh
                                        </h4>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Single Blog -->
                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="ed-blog__card wow fadeInUp" data-wow-delay=".5s" data-wow-duration="1s">
                                <div class="ed-blog__head">
                                    <div class="ed-blog__img">
                                        <img src="{{ asset('client/images/blog/blog-1/1.png') }}"
                                            alt="english-blog-img" />
                                    </div>
                                    <a href="blog.html" class="ed-blog__category">Từ Vựng</a>
                                </div>
                                <div class="ed-blog__content">
                                    <ul class="ed-blog__meta">
                                        <li><i class="fi fi-rr-calendar"></i>20 Tháng 1, 2025</li>
                                        <li><i class="fi fi-rr-comment-alt-dots"></i>18 Bình Luận</li>
                                    </ul>
                                    <a href="blog-details.html" class="ed-blog__title">
                                        <h4>
                                            Cách Học 100 Từ Vựng Tiếng Anh Hiệu Quả Trong 1 Tuần
                                        </h4>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Single Blog -->
                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="ed-blog__card wow fadeInUp" data-wow-delay=".7s" data-wow-duration="1s">
                                <div class="ed-blog__head">
                                    <div class="ed-blog__img">
                                        <img src="{{ asset('client/images/blog/blog-1/1.png') }}"
                                            alt="english-blog-img" />
                                    </div>
                                    <a href="blog.html" class="ed-blog__category">Luyện Thi</a>
                                </div>
                                <div class="ed-blog__content">
                                    <ul class="ed-blog__meta">
                                        <li><i class="fi fi-rr-calendar"></i>10 Tháng 6, 2025</li>
                                        <li><i class="fi fi-rr-comment-alt-dots"></i>12 Bình Luận</li>
                                    </ul>
                                    <a href="blog-details.html" class="ed-blog__title">
                                        <h4>
                                            Bí Quyết Đạt Điểm Cao Trong Kỳ Thi IELTS Writing
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
