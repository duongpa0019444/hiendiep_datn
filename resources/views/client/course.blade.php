@extends('client.client')

@section('title', 'Khóa học')
@section('description', '')
@section('content')

    <main>
        <!--<< Breadcrumb Section Start >>-->
        <div class="section-bg hero-bg">
            <!-- Start Bredcrumbs Area -->
            <section class="ed-breadcrumbs background-image"
                style="background-image: url('assets/images/breadcrumbs-bg.png');">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="ed-breadcrumbs__content">
                                <h3 class="ed-breadcrumbs__title">Tất cả khóa học của chúng tôi</h3>
                                <ul class="ed-breadcrumbs__menu">
                                    <li class="active"><a href="index.html">Home</a></li>
                                    <li>/</li>
                                    <li>khóa học</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- End Bredcrumbs Area -->
        </div>
        <!-- Start Course Area -->
        <section class="ed-course section-gap position-relative">
            <div class="container ed-container">
                <div class="row">
                    <div class="col-12">
                        <div class="ed-course__filter">
                            <p class="ed-course__filter-text">
                                Showing 1-6 Of 15 Results
                            </p>
                            <div class="ed-course__filter-search">
                                <form action="#" method="post" class="ed-hero__search-form">
                                    <input type="search" name="search" placeholder="Search your courses..." required />
                                    <button type="submit">Search<i class="fi-rr-search"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- Single Course Card -->
                    @foreach ($data as $course)
                        <div class="col-lg-6 col-xl-4 col-md-6 col-12">
                            <div class="ed-course__card wow fadeInUp" data-wow-delay=".3s" data-wow-duration="1s">
                                <a href="{{-- {{ asset('assets/images/course/course-1/1.png') }} --}}" class="ed-course__img">
                                    <img src="{{ asset('uploads/course/' . $course->image ) }}" alt="course-img" />
                                </a>

                                <a href="course-1.html" class="ed-course__tag">{{ $course->name }}</a>

                                <div class="ed-course__body">
                                    <div class="ed-course__lesson">
                                        <div class="ed-course__part">
                                            <i class="fi-rr-book"></i>
                                            <p>{{ $course->lessons->count() }} Bài giảng</p>
                                        </div>
                                        <div class="ed-course__teacher">
                                            <i class="fi-rr-user"></i>
                                            {{-- <p>{{ $course->teachers->name }}</p> --}}
                                        </div>
                                    </div>

                                    <a href="#" class="ed-course__title">
                                        <h5>
                                            {{ $course->description }}
                                        </h5>
                                    </a>

                                    <div class="ed-course__rattings">
                                        <ul>
                                            <li><i class="icofont-star"></i></li>
                                            <li><i class="icofont-star"></i></li>
                                            <li><i class="icofont-star"></i></li>
                                            <li><i class="icofont-star"></i></li>
                                            <li><i class="icofont-star"></i></li>
                                            {{-- <li><span>(09 Reviews)</span></li> --}}
                                        </ul>
                                    </div>

                                    <div class="ed-course__bottom">
                                        <span class="ed-course__price">{{ number_format($course->price, 0, ',', '.') }} VNĐ</span>
                                        <div class="ed-course__students">
                                            <i class="fi fi-rr-graduation-cap"></i>
                                            <p>{{ $course->students->count() }} học sinh</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Single Course Card -->

                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="ed-pagination">
                            <ul class="ed-pagination__list">
                                <li class="active">
                                    <a href="#">01</a>
                                </li>
                                <li>
                                    <a href="#">02</a>
                                </li>
                                <li>
                                    <a href="#"><i class="fi-rr-arrow-small-right"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Course Area -->

    </main>

@endsection
