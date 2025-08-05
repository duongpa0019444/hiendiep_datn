@extends('client.client')


@section('title', 'Danh mục bài viết')
@section('description', 'Danh mục bài viết ' . $topic->name)
@section('keywords', 'bài viết, sự kiện, học tiếng Anh, tin tức')
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
                                        <h3 class="ed-breadcrumbs__title">Blog</h3>
                                        <ul class="ed-breadcrumbs__menu">
                                            <li class="active"><a href="{{ route('home') }}">Home</a></li>
                                            <li>/</li>
                                            <li>Blog Category</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- End Bredcrumbs Area -->
                </div>

                <!-- End Testimonial Area -->
                <!-- Start Blog Area -->
                <section class="ed-blog section-gap">
                    <div class="container ed-container">
                        <div class="row justify-content-center">
                            <div class="col-lg-6 col-md-8 col-12">
                                <div class="ed-section-head text-center">
                                    <span class="ed-section-head__sm-title">DANH MỤC TIN TỨC</span>
                                    <h3 class="ed-section-head__title ed-split-text left">
                                        {{ $topic->name }}
                                    </h3>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Single Blog -->
                            @foreach ($newsCategorys as $new)
                                <div class="col-lg-4 col-md-6 col-12">
                                    <div class="ed-blog__card wow fadeInUp" data-wow-delay=".3s" data-wow-duration="1s">
                                        <div class="ed-blog__head">
                                            <div class="ed-blog__img">
                                                <img src="{{ asset($new->image) }}" alt="{{ $new->image_caption }}" />
                                            </div>
                                            <a href="" class="ed-blog__category">
                                                {{ $new->topic->name }}</a>
                                        </div>
                                        <div class="ed-blog__content">
                                            <ul class="ed-blog__meta">
                                                <li><i class="fi fi-rr-calendar"></i>{{ $new->updated_at }}</li>
                                                {{-- <li><i class="fi fi-rr-comment-alt-dots"></i>25 Bình Luận</li> --}}
                                            </ul>
                                            <a href="{{ route('client.news.detail', ['id' => $new->id, 'slug' => $new->slug]) }}"
                                                class="ed-blog__title">
                                                <h4>
                                                    {{ $new->title }}
                                                </h4>
                                                <p>
                                                    {{ $new->short_intro }}

                                                </p>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach


                        </div>
                    </div>
                </section>
                <!-- End Blog Area -->

            </main>



        </div>
    </div>

@endsection
