@extends('client.client')

@section('title', 'Khóa học')
@section('description', '')
@section('content')

    <main>
        <!--<< Breadcrumb Section Start >>-->
        <div class="section-bg hero-bg">
            <!-- Start Bredcrumbs Area -->
            <section class="ed-breadcrumbs background-image"
                style="background-image: url('https://banghieuviet.org/wp-content/uploads/2023/08/nen-xanh-duong-pastel.jpg');">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="ed-breadcrumbs__content">
                                <h3 class="ed-breadcrumbs__title">Contact</h3>
                                <ul class="ed-breadcrumbs__menu">
                                    <li class="active"><a href="{{ route('home') }}">trang chủ </a></li>
                                    <li>/</li>
                                    <li>Liên Hệ</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- End Bredcrumbs Area -->
        </div>
        <!-- Start Contact Card Area -->
        <div class="ed-contact__card section-gap">
            <div class="container ed-container">
                <div class="row">
                    <!-- Single Card  -->
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="ed-contact__card-item">
                            <div class="ed-contact__card-icon">
                                <img src="https://tinhocnews.com/wp-content/uploads/2024/03/vector-dien-thoai-7.jpg"
                                    alt="" style="width: 120px; height: 110px;">
                            </div>
                            <div class="ed-contact__card-info">
                                <h4>Hãy liên hệ số điện thoại </h4>
                                <a href="tel:+64 939-39-0239">+64 939-39-0239</a>
                                <a href="tel:+54 939-739-02399">+54 939-739-02399</a>
                            </div>
                        </div>
                    </div>

                    <!-- Single Card  -->
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="ed-contact__card-item">
                            <div class="ed-contact__card-icon">
                                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQo6scHPcTIJvT4eUs1tQytHg7SVU0pVVHcQA&s"
                                    alt="" style="width: 100px; height: 100px;">
                            </div>
                            <div class="ed-contact__card-info">
                                <h4>Gửi email cho chúng tôi</h4>
                                <a href="mailto:helloeduna@gmail.com">helloeduna@gmail.com</a>
                                <a href="mailto:eduna@gmail.com">eduna@gmail.com</a>
                            </div>
                        </div>
                    </div>

                    <!-- Single Card  -->
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="ed-contact__card-item">
                            <div class="ed-contact__card-icon">
                                <img src="https://png.pngtree.com/png-clipart/20190924/original/pngtree-location-icon-for-your-project-png-image_4852335.jpg"
                                    alt="" style="width: 100px; height: 100px;" />
                            </div>
                            <div class="ed-contact__card-info">
                                <h4>Địa chỉ của chúng tôi </h4>

                                <a href="https://www.google.com/maps?q=202+Tống+Duy+Tân,+P.+Lam+Sơn,+Thanh+Hóa,+Việt+Nam"
                                    target="_blank" onmouseover="openMap(this.href)">
                                    202 Tống Duy Tân, P. Lam Sơn, Thanh Hóa, Việt Nam
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Contact Card Area -->

        <!-- Start Contact Area -->
        <section class="ed-contact ed-contact--style2 section-gap pt-0 position-relative">
            <div class="container ed-container">
                <div class="row">
                    <div class="col-12">
                        <div class="ed-contact__inner">
                            <!-- Contact Image  -->
                            <div class="ed-contact__img">
                                <img src="https://www.thietkewebthuonghieu.com/wp-content/uploads/2022/01/top-10-trung-tam-day-tieng-anh-cho-tre-em.jpg"
                                    alt="contact-img" />
                            </div>

                            <!-- Contact Form  -->
                            <div class="ed-contact__form">
                                <div class="ed-contact__form-head">
                                    <span class="ed-contact__form-sm-title">LIÊN HỆ VỚI CHÚNG TÔI
                                    </span>
                                    <h3 class="ed-contact__form-big-title ed-split-text right">
                                        Có thắc mắc? Liên hệ <br />
                                        với chúng tôi ngay hôm nay
                                    </h3>
                                </div>
                                {{-- <form id="supportForm">
                                    <input type="text" name="name" placeholder="Full name" required>
                                    <input type="email" name="email" placeholder="Email" required>
                                    <textarea name="message" placeholder="Message" required></textarea>
                             
                                    <div class="ed-contact__form-btn">
                                        <button type="submit" class="ed-btn">Gửi tin nhắn <i
                                                class="fi fi-rr-arrow-small-right"></i></button>
                                    </div>
                                </form> --}}
                                <form action="{{ route('support.store') }}" method="POST">
                                    @csrf
                                    <select name="pl_content" required>
                                        <option value="">Phân loại nội dung</option>
                                        <option value="khiếu nại">Khiếu nại</option>
                                        <option value="góp ý">Góp ý</option>
                                        <option value="hỗ trợ">Hỗ trợ</option>
                                        <option value="đăng ký">Đăng ký</option>
                                    </select>
                                    <input type="text" name="name" placeholder="Tên" required>
                                    <input type="text" name="phone" placeholder="SĐT" required>
                                    <textarea name="message" placeholder="Tin nhắn" required></textarea>
                                    <button type="submit" class="ed-btn">Gửi tin nhắn <i
                                            class="fi fi-rr-arrow-small-right"></i></button>
                                </form>
                                @if (session('success'))
                                    <p style="color: green;">{{ session('success') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Contact Area -->
    </main>
@endsection
<script>
    let mapOpened = false;

    function openMap(url) {
        if (!mapOpened) {
            window.open(url, '_blank');
            mapOpened = true; // Chỉ mở 1 lần khi hover
        }
    }
</script>
