@extends('client.client')

@section('title', 'Liên hệ')
@section('description', '')
@section('content')

    <main>
        <!--<< Breadcrumb Section Start >>-->
        <div style="margin-top: -40px;">
            <div class="section-bg hero-bg">
                <!-- Start Bredcrumbs Area -->
                <section class="ed-breadcrumbs background-image"
                    style="background-image: url('{{ asset('client/images/breadcrumbs-bg.png') }}');">

                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="ed-breadcrumbs__content">
                                    <ul class="ed-breadcrumbs__menu">
                                        <li class="active"><a href="index.html">Trang chủ</a></li>
                                        <li>/</li>
                                        <li>Giới thiệu</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- End Bredcrumbs Area -->
            </div>
        </div>
        <!-- Start Why Choose Area -->
        <section class="ed-why-choose ed-why-choose--style3 section-gap position-relative">
            <div class="container ed-container">
                <div class="row">
                    <div class="col-lg-6 col-12">
                        <div class="ed-w-choose__content">
                            <div class="ed-section-head">
                                <span class="ed-section-head__sm-title">CHÚNG TÔI LÀ AI</span>
                                <h3 class="ed-section-head__title ed-split-text left">
                                    Nâng Tầm Phương Pháp Tốt Nhất <br />
                                    với Khóa Học Của Chúng Tôi
                                </h3>
                                <p class="ed-section-head__text">
                                    Khám phá hành trình học tập hiện đại với các khóa học trực tuyến của chúng tôi – nơi bạn có thể nâng cao kỹ năng, cải thiện phương pháp học và tiếp cận kiến thức thực tiễn từ các chuyên gia hàng đầu. Học mọi lúc, mọi nơi, dễ dàng và hiệu quả hơn bao giờ hết.

                                </p>
                            </div>

                            <div class="ed-w-choose__info">
                                <!-- Thông Tin Đơn Lẻ -->
                                <div class="ed-w-choose__info-single">
                                    <div class="ed-w-choose__info-head">
                                        <div class="ed-w-choose__info-icon bg-1">
                                            <img src="{{ asset('client/images/why-choose/why-choose-1/icon-1.svg') }}"
                                                alt="icon" />
                                        </div>
                                        <h5>Giảng Dạy Trực Tiếp</h5>
                                    </div>
                                    <div class="ed-w-choose__info-bottom">
                                        <p>
                                            Trải nghiệm học tập tương tác với giảng viên thông qua các buổi giảng dạy trực tiếp, giúp bạn hiểu sâu kiến thức, được hỗ trợ ngay tức thì và kết nối hiệu quả với người hướng dẫn.
                                        </p>
                                    </div>
                                </div>
                                <!-- Thông Tin Đơn Lẻ -->
                                <div class="ed-w-choose__info-single">
                                    <div class="ed-w-choose__info-head">
                                        <div class="ed-w-choose__info-icon bg-2">
                                            <img src="{{ asset('client/images/why-choose/why-choose-1/icon-2.svg') }}"
                                                alt="icon" />
                                        </div>
                                        <h5>Hỗ Trợ 24/7</h5>
                                    </div>
                                    <div class="ed-w-choose__info-bottom">
                                        <p>
                                          Đội ngũ hỗ trợ luôn sẵn sàng 24/7 để giải đáp mọi thắc mắc, hướng dẫn kỹ thuật và đồng hành cùng bạn trong suốt quá trình học tập – bất kể ngày hay đêm.

                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-12">
                        <div class="ed-w-choose__images ed-w-choose__images--style3 position-relative">
                            <!-- Hình Ảnh Vì Sao Chọn -->
                            <div class="ed-w-choose__main-img--style2 position-relative">
                                <img class="why-choose-img-1"
                                    src="{{ asset('client/images/why-choose/why-choose-3/img-1.png') }}" alt="" />
                                <img class="why-choose-img-2"
                                    src="{{ asset('client/images/why-choose/why-choose-3/img-2.png') }}" alt="" />
                            </div>
                            <!-- Thẻ Bộ Đếm -->
                            <div class="counter-card updown-ani">
                                <div class="counter-card__icon">
                                    <i class="fi fi-rr-graduation-cap"></i>
                                </div>
                                <div class="counter-card__info">
                                    <h4><span class="counter">2000</span>+</h4>
                                    <p>Học Viên Đã Ghi Danh</p>
                                </div>
                            </div>
                            <!-- Hình Trang Trí -->
                            <div class="ed-w-choose__shapes">
                                <img class="ed-w-choose__shape-1 rotate-ani"
                                    src="{{ asset('client/images/why-choose/why-choose-3/shape-1.svg') }}" alt="" />
                                <img class="ed-w-choose__shape-2"
                                    src="{{ asset('client/images/why-choose/why-choose-3/shape-2.svg') }}" alt="" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- End Why Choose Area -->




        <!-- Start Call Action Area -->
        <section class="ed-call-action ed-call-action--style2 position-relative overflow-hidden">
            <div class="container ed-container">
                <div class="ed-call-action__inner ed-call-action__inner--style2">
                    <div class="ed-call-action__shapes">
                        <img class="ed-call-action__shape-1"
                            src="{{ asset('client/images/abstracts/abstract-element-regular.svg') }}"
                            alt="hình-trừu-tượng-1" />
                        <img class="ed-call-action__shape-2" src="{{ asset('client/images/abstracts/abstract-dot-4.svg') }}"
                            alt="hình-chấm-tròn-4" />
                        <img class="ed-call-action__shape-3"
                            src="{{ asset('client/images/abstracts/abstract-element-regular.svg') }}"
                            alt="hình-trừu-tượng-2" />
                        <img class="ed-call-action__shape-4"
                            src="{{ asset('client/images/abstracts/abstract-orange-plus-1.svg') }}"
                            alt="hình-dấu-cộng-cam-1" />
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-12">
                            <div class="ed-call-action__img">
                                <img src="{{ asset('client/images/call-action/call-action-2/call-action-img.png') }}"
                                    alt="hình-gọi-hành-động" />
                            </div>
                        </div>
                        <div class="col-lg-6 col-12 order-class">
                            <div class="ed-call-action__content">
                                <div class="ed-section-head">
                                    <span class="ed-section-head__sm-title text-white">KHÓA HỌC</span>
                                    <h3 class="ed-section-head__title ed-split-text right">
                                        Tìm Lộ Trình Học Tập Phù Hợp <br />
                                        Cho Tương Lai Của Bạn
                                    </h3>
                                    <p class="ed-section-head__text">
                                        Chúng tôi giúp bạn xác định lộ trình học tập tối ưu dựa trên mục tiêu, sở thích và trình độ hiện tại – từ đó xây dựng nền tảng vững chắc để phát triển sự nghiệp và chinh phục tương lai bạn mong muốn.
                                    </p>
                                </div>
                                <div class="ed-call-action__content-btn">
                                    <a href="course-1.html" class="ed-btn "> Bắt Đầu Học Ngay Hôm Nay<i
                                            class="fi fi-rr-arrow-small-right"></i> </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- End Call Action Area -->

        <!-- Bắt đầu Khu Vực Giới Thiệu -->
        <section class="ed-about section-gap position-relative">
            <div class="container ed-container">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-12">
                        <!-- Nội dung Giới thiệu -->
                        <div class="ed-about__content p-0">
                            <div class="ed-section-head">
                                <span class="ed-section-head__sm-title">VÌ SAO CHỌN EDUNA</span>
                                <h3 class="ed-section-head__title ed-split-text left">
                                    Học Viện Số: <br />
                                    Lộ Trình Đến Sự Sáng Tạo Vượt Trội
                                </h3>
                                <p class="ed-section-head__text">
                                    Học viện số của chúng tôi mang đến môi trường học tập sáng tạo, linh hoạt và hiện đại – nơi bạn được truyền cảm hứng, phát triển tư duy đổi mới và khai phá tiềm năng sáng tạo thông qua chương trình đào tạo tiên tiến và thực tiễn.
                                </p>
                            </div>
                            <div class="ed-about__feature">
                                <ul class="ed-about__features-list">
                                    <li><img src="{{ asset('client/images/icons/icon-check-blue.svg') }}"
                                            alt="check" />Đội ngũ giảng viên chuyên gia</li>
                                    <li><img src="{{ asset('client/images/icons/icon-check-blue.svg') }}"
                                            alt="check" />Học từ xa</li>
                                    <li><img src="{{ asset('client/images/icons/icon-check-blue.svg') }}"
                                            alt="check" />Giáo trình dễ theo dõi</li>
                                    <li><img src="{{ asset('client/images/icons/icon-check-blue.svg') }}"
                                            alt="check" />Truy cập trọn đời</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-12">
                        <!-- Hình ảnh Giới thiệu -->
                        <div class="ed-about__images">
                            <div class="ed-about__main-img">
                                <img src="{{ asset('client/images/about/about-3/about-img.png') }}"
                                    alt="ảnh-giới-thiệu" />
                            </div>
                            <div class="counter-card updown-ani">
                                <div class="counter-card__icon">
                                    <i class="fi fi-rr-graduation-cap"></i>
                                </div>
                                <div class="counter-card__info">
                                    <h4><span class="counter">3458</span>+</h4>
                                    <p>Học viên hài lòng</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Kết thúc Khu Vực Giới Thiệu -->




        <!-- Bắt đầu Khu Vực FAQ -->
        <section class="ed-faq position-relative">
            <div class="container ed-container">
                <div class="ed-faq__inner position-relative">
                    <div class="row align-items-center">
                        <div class="col-lg-12 col-xl-6 col-12">
                            <!-- Hình ảnh FAQ -->
                            <div class="ed-faq__images position-relative">
                                <div class="ed-faq__images-group">
                                    <div class="ed-faq__image-group-1">
                                        <img class="faq-img-1" src="{{ asset('client/images/faq/faq-1/faq-img-1.png') }}"
                                            alt="ảnh-faq-1" />
                                    </div>
                                    <div class="ed-faq__image-group-2">
                                        <img class="faq-img-2" src="{{ asset('client/images/faq/faq-1/faq-img-2.png') }}"
                                            alt="ảnh-faq-2" />
                                        <img class="faq-img-3" src="{{ asset('client/images/faq/faq-1/faq-img-3.png') }}"
                                            alt="ảnh-faq-3" />
                                    </div>
                                </div>
                                <!-- Hình trang trí -->
                                <div class="ed-faq__shapes">
                                    <img class="ed-faq__shape-1" src="{{ asset('client/images/faq/faq-1/shape-1.svg') }}"
                                        alt="hình-1" />
                                    <img class="ed-faq__shape-2" src="{{ asset('client/images/faq/faq-1/shape-2.svg') }}"
                                        alt="hình-2" />
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-xl-6 col-12">
                            <!-- Nội dung FAQ -->
                            <div class="ed-faq__content">
                                <div class="ed-section-head m-0">
                                    <span class="ed-section-head__sm-title">CÂU HỎI THƯỜNG GẶP</span>
                                    <h3 class="ed-section-head__title ed-split-text right">
                                        Những Câu Hỏi Phổ Biến Về Khóa Học Của Chúng Tôi
                                    </h3>
                                </div>
                                <div class="ed-faq__accordion faq-inner accordion" id="accordionExample">
                                    <!-- Câu hỏi 1 -->
                                    <div class="ed-faq__accordion-item">
                                        <h2 class="accordion-header" id="headingOne">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapseOne" aria-expanded="true"
                                                aria-controls="collapseOne">
                                                Làm sao tôi bắt đầu học lớp?
                                            </button>
                                        </h2>
                                        <div id="collapseOne" class="accordion-collapse collapse show"
                                            aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                            <div class="ed-faq__accordion-body">
                                                <p class="ed-faq__accordion-text">
                                                    
                                                    Để bắt đầu học, bạn chỉ cần đăng ký tài khoản trên trang web của chúng tôi, chọn khóa học bạn muốn tham gia và thanh toán. Sau khi hoàn tất, bạn sẽ nhận được hướng dẫn chi tiết để truy cập vào lớp học trực tuyến.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Câu hỏi 2 -->
                                    <div class="ed-faq__accordion-item">
                                        <h2 class="accordion-header" id="headingTwo">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                                aria-expanded="false" aria-controls="collapseTwo">
                                                Làm sao tôi đăng ký tài khoản để học?
                                            </button>
                                        </h2>
                                        <div id="collapseTwo" class="accordion-collapse collapse"
                                            aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                            <div class="ed-faq__accordion-body">
                                                <p class="ed-faq__accordion-text">
                                                    Để đăng ký tài khoản, bạn chỉ cần truy cập trang liên hệ của chúng tôi, điền thông tin cá nhân và xác nhận email. Sau đó, bạn có thể đăng nhập và bắt đầu khám phá các khóa học.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Câu hỏi 3 -->
                                    <div class="ed-faq__accordion-item">
                                        <h2 class="accordion-header" id="headingThree">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapseThree"
                                                aria-expanded="false" aria-controls="collapseThree">
                                                Tôi có được quyền truy cập trọn đời không?
                                            </button>
                                        </h2>
                                        <div id="collapseThree" class="accordion-collapse collapse"
                                            aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                            <div class="ed-faq__accordion-body">
                                                <p class="ed-faq__accordion-text">
                                                    Có, khi bạn đăng ký khóa học, bạn sẽ được quyền truy cập trọn đời vào tất cả tài liệu và bài giảng của khóa học đó. Bạn có thể học lại bất cứ lúc nào mà không bị giới hạn thời gian.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Câu hỏi 4 -->
                                    <div class="ed-faq__accordion-item">
                                        <h2 class="accordion-header" id="headingFour">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapseFour"
                                                aria-expanded="false" aria-controls="collapseFour">
                                                Làm sao tôi có thể liên hệ trực tiếp với trường?
                                            </button>
                                        </h2>
                                        <div id="collapseFour" class="accordion-collapse collapse"
                                            aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                                            <div class="ed-faq__accordion-body">
                                                <p class="ed-faq__accordion-text">
                                                    Bạn có thể liên hệ trực tiếp với trường qua email hoặc số điện thoại được cung cấp trên trang liên hệ của chúng tôi. Chúng tôi luôn sẵn sàng hỗ trợ bạn trong quá trình học tập.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Kết thúc Khu Vực FAQ -->



    </main>


@endsection
