@extends('client.client')

@section('title', 'Chi tiết khóa học')
@section('description', '')
@section('content')

    <main>
        <!--<< Breadcrumb Section Start >>-->
        <div style="margin-top: 90px;">
            <!-- Start Bredcrumbs Area -->
            <section class="ed-breadcrumbs"
                style="background-image: url('{{ asset('client/images/breadcrumbs-bg.png') }}');">

                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="ed-breadcrumbs__content">
                                <h3 class="ed-breadcrumbs__title"> {{ $course->name }}</h3>
                                <ul class="ed-breadcrumbs__menu">
                                    <li class="active"><a href="{{ route('home') }}">Home</a></li>
                                    <li>/</li>
                                    <li class="active"><a href="{{ route('client.course') }}">Khóa học</a></li>
                                    <li>/</li>
                                    <li> {{ $course->name }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- End Bredcrumbs Area -->
        </div>
        <!-- Start Course Details Area -->
        <section class="ed-course__details">
            <div class="container ed-container">
                <div class="row">
                    <div class="col-lg-8 col-12">
                        <!-- Course Details Content -->
                        <div class="ed-course__details-content">
                            {{-- nội dung nhập từ ckeditor --}}
                            {!! $course->description ?? '' !!}


                            <!-- Course Details Image -->
                            {{-- <div class="ed-course__details-img">
                                        <img src="{{ asset('client/images/course/course-details/details-img-1.png')}}"
                                            alt="{{ $course->name }}" />
                                    </div>
                                    <h3>
                                        Bắt đầu SEO như một khóa học kinh doanh tại nhà trực tuyến
                                    </h3>
                                    <p>
                                        Lorem ipsum dolor sit amet consectur adipisicing elit, sed do eiusmod tempor inc
                                        idid unt ut labore et dolore magna aliqua enim ad minim veniam, quis nostrud
                                        exerec tation ullamco laboris nis
                                        aliquip commodo consequat duis aute irure dolor in reprehenderit in voluptate
                                        velit esse cillum dolore eu fugiat nulla pariatur enim ipsam.
                                    </p>
                                    <br />
                                    <p>
                                        Lorem ipsum dolor sit amet consectur adipisicing elit, sed do eiusmod tempor inc
                                        idid unt ut labore et dolore magna aliqua enim ad minim veniam, quis nostrud
                                        exerec tation ullamco laboris nis
                                        aliquip commodo consequat duis aute irure dolor in reprehenderit in voluptate
                                        velit esse cillum dolore eu fugiat nulla pariatur enim ipsam.
                                    </p>

                                    <!-- Course Details Content List -->
                                    <div class="ed-course__details-list">
                                        <h5>Bạn sẽ học được gì?</h5>
                                        <ul>
                                            <li><img src="{{ asset('client/images/icons/icon-check-blue.svg') }}"
                                                    alt="icon-check-blue" />Tempus imperdiet nulla malesuada
                                                pellentesque elit eget gravida cum sociis</li>
                                            <li><img src="{{ asset('client/images/icons/icon-check-blue.svg') }}"
                                                    alt="icon-check-blue" />Neque sodales ut etiam sit amet nisl purus
                                                non tellus orci ac auctor</li>
                                            <li><img src="{{ asset('client/images/icons/icon-check-blue.svg') }}"
                                                    alt="icon-check-blue" />Tristique nulla aliquet enim tortor at
                                                auctor urna. Sit amet aliquam id diam maer</li>
                                            <li><img src="{{ asset('client/images/icons/icon-check-blue.svg') }}"
                                                    alt="icon-check-blue" />Tempus imperdiet nulla malesuada
                                                pellentesque elit eget gravida cum sociis</li>
                                        </ul>
                                    </div>

                                    <!-- Course Details Image Two -->
                                    <div class="ed-course__details-img image-2">
                                        <img src="{{ asset('client/images/course/course-details/details-img-2.png') }}"
                                            alt="course-details-img-2" />
                                    </div>

                                    <!-- Course Details Content List -->
                                    <div class="ed-course__details-list">
                                        <h5>Tại sao bạn nên chọn khóa học này?</h5>
                                        <ul>
                                            <li><img src="{{ asset('client/images/icons/icon-check-blue.svg') }}"
                                                    alt="icon-check-blue" />Tempus imperdiet nulla malesuada
                                                pellentesque elit eget gravida cum sociis</li>
                                            <li><img src="{{ asset('client/images/icons/icon-check-blue.svg') }}"
                                                    alt="icon-check-blue" />Neque sodales ut etiam sit amet nisl purus
                                                non tellus orci ac auctor</li>
                                            <li><img src="{{ asset('client/images/icons/icon-check-blue.svg') }}"
                                                    alt="icon-check-blue" />Tristique nulla aliquet enim tortor at
                                                auctor urna. Sit amet aliquam id diam maer</li>
                                            <li><img src="{{ asset('client/images/icons/icon-check-blue.svg') }}"
                                                    alt="icon-check-blue" />Tempus imperdiet nulla malesuada
                                                pellentesque elit eget gravida cum sociis</li>
                                        </ul>
                                    </div> --}}
                        </div>
                    </div>
                    <div class="col-lg-4 col-12">
                        <div class="ed-course__sidebar">
                            <div class="ed-course__sidebar-widget">
                                <h4 class="ed-course__sidebar-title">
                                    Thông tin khóa học:
                                </h4>
                                <ul>
                                    <li>Giá:<span class="price">{{ number_format($course->price, 0, ',', '.') }}
                                            VNĐ</span></li>
                                    {{-- <li>Giảng viên:<span>Laura Martinez</span></li> --}}
                                    <li>Chứng nhận:<span>Có</span></li>
                                    <li>Bài giảng:<span>{{ $course->lessons->count() }}</span></li>
                                    <li>Số buổi:<span>{{ $course->total_sessions }}</span></li>
                                    {{-- <li>Language:<span>English</span></li> --}}
                                    <li>Học sinh:<span>{{ $course->student_count }}</span></li>
                                </ul>
                            </div>

                            <div class="ed-course__sidebar-widget">
                                <h4 class="ed-course__sidebar-title">Liên hệ với chúng tôi</h4>
                                <!-- Sigle Info  -->
                                <div class="ed-contact__info-item">
                                    <div class="ed-contact__info-icon">
                                        <img src="{{ asset('client/images/icons/icon-phone-blue.svg') }}"
                                            alt="icon-phone-blue" />
                                    </div>
                                    <div class="ed-contact__info-content">
                                        <span>Hỗ trợ 24/7</span>
                                        <a href="tel:+0922311234">+0922311234</a>
                                    </div>
                                </div>
                                <!-- Sigle Info  -->
                                <div class="ed-contact__info-item">
                                    <div class="ed-contact__info-icon">
                                        <img src="{{ asset('client/images/icons/icon-envelope-blue.svg') }}"
                                            alt="icon-envelope-blue" />
                                    </div>
                                    <div class="ed-contact__info-content text-break">
                                        <span>Gửi tin nhắn</span>
                                        <a href="mailto:trungtamhiendiep@gmail.com">hiendiep@gmail.com</a>
                                    </div>
                                </div>

                                <!-- Sigle Info  -->
                                <div class="ed-contact__info-item">
                                    <div class="ed-contact__info-icon">
                                        <img src="{{ asset('client/images/icons/icon-location-blue.svg') }}"
                                            alt="icon-location-blue" />
                                    </div>
                                    <div class="ed-contact__info-content">
                                        <span>Vị trí của chúng tôi</span>
                                        <a href="https://www.google.com/maps/place/Tr%C6%B0%E1%BB%9Dng+THPT+Tri%E1%BB%87u+S%C6%A1n+3/@19.8181875,105.5346406,13z/data=!4m15!1m8!3m7!1s0x3136e59040000001:0x654e145bb3a5b678!2zVHLGsOG7nW5nIFRIUFQgVHJp4buHdSBTxqFuIDM!8m2!3d19.8181875!4d105.5346406!10e5!16s%2Fg%2F1tdh5y53!3m5!1s0x3136e59040000001:0x654e145bb3a5b678!8m2!3d19.8181875!4d105.5346406!16s%2Fg%2F1tdh5y53?entry=ttu&g_ep=EgoyMDI1MDYzMC4wIKXMDSoASAFQAw%3D%3D">Văn Sơn, Triệu Sơn, Thanh Hóa</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Course Details Area -->
    </main>

@endsection
