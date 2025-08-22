@extends('client.client')


@section('title', $news->title)
@section('description', $news->seo_description)
@section('keywords', $news->seo_keywords)
@section('content')
    <div id="smooth-wrapper">
        <div id="smooth-content">
            <main>
                <!--<< Breadcrumb Section Start >>-->
                <div class="section-bg hero-bg">
                    <!-- Start Bredcrumbs Area -->
                    <section class="ed-breadcrumbs background-image"
                        style="background-image: url('{{ asset('client/images/breadcrumbs-bg.png') }}');">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-lg-6 col-md-6 col-12">
                                    <div class="ed-breadcrumbs__content">
                                        <h3 class="ed-breadcrumbs__title">Blog Details</h3>
                                        <ul class="ed-breadcrumbs__menu">
                                            <li class="active"><a href="{{ route('home') }}">Home</a></li>
                                            <li>/</li>
                                            <li>Blog Details</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- End Bredcrumbs Area -->
                </div>


                <!-- Start Blog Details Area -->
                <section class="ed-blog__details section-gap position-relative">
                    <div class="container ed-container">
                        <div class="row">
                            <div class="col-lg-12 col-xl-8 col-12">
                                <!-- Blog Details Main -->
                                <div class="ed-blog__details-main">
                                    <div class="ed-blog__details-top">
                                        <!-- Blog Details Cover -->
                                        <div class="ed-blog__details-cover">
                                            <div class="ed-blog__details-cover-img">
                                                <img src="{{ asset($news->image) }}" alt="{{ $news->image_caption }}" />
                                            </div>

                                            <ul class="ed-blog__details-meta">

                                                <li>
                                                    <a href="blog.html">{{ $news->topic->name }}</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <h2 class="ed-blog__details-title">
                                            {{ $news->title }}
                                        </h2>
                                        <p class="ed-blog__details-text">
                                            {{ $news->short_intro }}
                                        </p>
                                        <br />

                                    </div>
                                    <div class="ed-blog__details-content">
                                        {!! $news->full_content !!}
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-6 col-xl-4 col-md-8 col-12">
                                <div class="ed-blog__sidebar">
                                    <!-- Single Sidebar Widget -->
                                    <div class="ed-blog__sidebar-widget">
                                        <h4 class="ed-blog__sidebar-title">TOPICS</h4>
                                        <div class="ed-blog__sidebar-category">
                                            <ul>
                                                @foreach ($topics as $topic)
                                                    <li>
                                                        <a href="{{ route('client.news.category', ['id' => $topic->id, 'slug' => \Illuminate\Support\Str::slug($topic->name)]) }}">{{ $topic->name }}
                                                            <span>{{ $topic->news_count }}</span> </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>

                                    <!-- Single Sidebar Widget -->
                                    <div class="ed-blog__sidebar-widget">
                                        <h4 class="ed-blog__sidebar-title">Bài Viết Phổ Biến</h4>
                                        <div class="ed-blog__latest">

                                            @foreach ($popularNews as $popularNew)
                                                <!-- Single Latest -->
                                                <div class="ed-blog__latest-item">
                                                    <div class="ed-blog__latest-img">
                                                        <img src="{{ asset($popularNew->image) }}" alt="{{ $popularNew->image_caption }}"/>
                                                    </div>
                                                    <div class="ed-blog__latest-info">
                                                        <a href="{{ route('client.news.detail', ['id' => $popularNew->id, 'slug' => $popularNew->slug]) }}">{{ $popularNew->title }}</a>
                                                        <span>
                                                            {{ \Carbon\Carbon::parse($popularNew->updated_at)->format('d, M, Y') }}

                                                        </span>
                                                    </div>
                                                </div>
                                            @endforeach

                                        </div>
                                    </div>

                                    <!-- Single Sidebar Widget -->
                                    <div class="ed-blog__sidebar-widget">
                                        <h4 class="ed-blog__sidebar-title">Liên Hệ Với Chúng Tôi</h4>
                                        <!-- Sigle Info  -->
                                        <div class="ed-contact__info-item">
                                            <div class="ed-contact__info-icon">
                                                <img src="{{ asset('client/images/icons/icon-phone-blue.svg') }}"
                                                    alt="icon-phone-blue" />
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
                                                <span>Adress</span>
                                                <a href="#" target="_blank">984 Quang Trung 3, Đông Vệ, TP.
                                                    Thanh Hóa</a>
                                            </div>
                                        </div>
                                    </div>
                                    @php
                                        $tags = array_filter(array_map('trim', explode(',', $news->seo_keyword)));
                                    @endphp

                                    <!-- Single Sidebar Widget -->
                                    <!-- Single Sidebar Widget -->
                                    <div class="ed-blog__sidebar-widget">
                                        <h4 class="ed-blog__sidebar-title">Từ khóa phổ biến</h4>
                                        <div class="ed-blog__tags">
                                            <ul>
                                                @foreach ($tags as $tag)
                                                    <li><a
                                                            href="{{ url('/search?tag=' . urlencode($tag)) }}">{{ $tag }}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- End Blog Details Area -->
            </main>


        </div>
    </div>
@endsection
