@extends('client.client')

@section('title', 'Khóa học')
@section('description', '')
@section('content')

    <main>
        <!--<< Breadcrumb Section Start >>-->
        <div style="margin-top: 90px;">
            <!-- Start Bredcrumbs Area -->
            <section class="ed-breadcrumbs background-image"
                style="background-image: url('client/images/breadcrumbs-bg.png');">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="ed-breadcrumbs__content">
                                <h3 class="ed-breadcrumbs__title">Tất cả khóa học của chúng tôi</h3>
                                <ul class="ed-breadcrumbs__menu">
                                    <li class="active"><a href="{{ route('home') }}">Home</a></li>
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
                                <span class="ed-course__filter-count">{{ $data->count() }}</span> Khóa học
                                hiện có
                            </p>
                            <div class="ed-course__filter-search">
                                <form action="{{ route('client.course.search') }}" method="GET"
                                    class="ed-hero__search-form">
                                    <input type="search" name="client_course_search" placeholder="Nhập tên khóa học..."
                                        value="{{ request('client_course_search') }}" required />
                                    <button type="submit">Tìm kiếm<i class="fi-rr-search"></i></button>
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
                                <a href="{{ route('client.course.detail', ['slug' => Str::slug($course->name), 'id' => $course->id]) }}" class="ed-course__img">
                                    {{-- <input type="hidden" name="course_id" value="{{ $course->id }}"> --}}
                                    <img src="{{ asset($course->image) }}" alt="{{ $course->name }}" />
                                </a>

                                <a href="{{ route('client.course.detail', ['slug' => Str::slug($course->name), 'id' => $course->id]) }}" class="ed-course__tag">
                                    {{-- <input type="hidden" name="course_id" value="{{ $course->id }}"> --}}
                                    {{ $course->name }}</a>

                                <div class="ed-course__body">
                                    <div class="ed-course__lesson">
                                        <div class="ed-course__part">
                                            <i class="fi-rr-book"></i>
                                            <p>{{ $course->lessons->count() }} Bài giảng</p>
                                        </div>
                                        {{-- <div class="ed-course__teacher">
                                            <i class="fi-rr-user"></i>
                                            <p>{{ $course->teachers->name }}</p>
                                        </div> --}}
                                    </div>

                                    <a href="{{ route('client.course.detail', ['slug' => Str::slug($course->name), 'id' => $course->id]) }}" class="ed-course__title">
                                        <h5>
                                            {{-- <input type="hidden" name="course_id" value="{{ $course->id }}"> --}}

                                            {{ $course->name }}
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
                                        <span class="ed-course__price">{{ number_format($course->price, 0, ',', '.') }}
                                            VNĐ</span>
                                        <div class="ed-course__students">
                                            <i class="fi fi-rr-graduation-cap"></i>
                                            {{-- Laravel sẽ tự động gọi getStudentCountAttribute() --}}
                                            <p>{{ $course->student_count  }} học sinh</p>
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
                                {{-- Trang trước --}}
                                @if ($data->hasPages())
                                    <ul class="ed-pagination__list">
                                        {{-- Prev --}}
                                        @if ($data->onFirstPage())
                                            <li class="disabled"><span><i class="fi-rr-arrow-small-left"></i></span></li>
                                        @else
                                            <li><a
                                                    href="{{ $data->previousPageUrl() }}&client_course_search={{ request('client_course_search') }}"><i
                                                        class="fi-rr-arrow-small-left"></i></a></li>
                                        @endif

                                        {{-- Page links --}}
                                        @foreach ($data->getUrlRange(1, $data->lastPage()) as $page => $url)
                                            @php $url .= '&client_course_search=' . urlencode(request('client_course_search')); @endphp
                                            @if ($page == $data->currentPage())
                                                <li class="active"><a href="#">{{ sprintf('%02d', $page) }}</a></li>
                                            @else
                                                <li><a href="{{ $url }}">{{ sprintf('%02d', $page) }}</a></li>
                                            @endif
                                        @endforeach

                                        {{-- Next --}}
                                        @if ($data->hasMorePages())
                                            <li><a
                                                    href="{{ $data->nextPageUrl() }}&client_course_search={{ request('client_course_search') }}"><i
                                                        class="fi-rr-arrow-small-right"></i></a></li>
                                        @else
                                            <li class="disabled"><span><i class="fi-rr-arrow-small-right"></i></span></li>
                                        @endif
                                    </ul>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Course Area -->

    </main>

@endsection
