@extends('client.client')


@section('title', 'Liên hệ')
@section('description', '')

@section('content')
<main>
    @if (session('success'))
<div id="popup-success" style="
    position: fixed;
    top: 80px; /* <-- Đẩy xuống 1 chút */
    left: 50%;
    transform: translateX(-50%);
    background-color: #d4edda;
    color: #155724;
    padding: 15px 30px;
    border: 1px solid #c3e6cb;
    border-radius: 8px;
    z-index: 9999;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    font-weight: bold;
">
    {{ session('success') }}
</div>

<script>
    setTimeout(() => {
        const popup = document.getElementById('popup-success');
        if (popup) popup.style.display = 'none';
    }, 1000); // 1 giây
</script>
@endif

  
    <!-- Breadcrumb Section -->
    <div class="section-bg hero-bg">
        <section class="ed-breadcrumbs background-image"
            style="background-image: url('https://banghieuviet.org/wp-content/uploads/2023/08/nen-xanh-duong-pastel.jpg');">

            <div class="container">
               
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-md-6 col-12">
                        
                        <div class="ed-breadcrumbs__content">
                            
                            <h3 class="ed-breadcrumbs__title">Liên Hệ</h3>
                            <ul class="ed-breadcrumbs__menu">
                                <li class="active"><a href="{{ route('home') }}">Trang Chủ</a></li>
                                <li>/</li>
                                <li>Liên Hệ</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
     
    <!-- Contact Cards -->
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 text-center p-4 shadow">
                    <img src="https://tinhocnews.com/wp-content/uploads/2024/03/vector-dien-thoai-7.jpg" class="card-img-top mx-auto" style="width: 100px; height: 90px;" alt="Phone">
                    <div class="card-body">
                        <h5 class="card-title">Hãy liên hệ số điện thoại</h5>
                        <p><a href="tel:+64 939-39-0239">+64 939-39-0239</a></p>
                        <p><a href="tel:+54 939-739-02399">+54 939-739-02399</a></p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card h-100 text-center p-4 shadow">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQo6scHPcTIJvT4eUs1tQytHg7SVU0pVVHcQA&s" class="card-img-top mx-auto" style="width: 90px; height: 90px;" alt="Email">
                    <div class="card-body">
                        <h5 class="card-title">Gửi email cho chúng tôi</h5>
                        <p><a href="mailto:helloeduna@gmail.com">helloeduna@gmail.com</a></p>
                        <p><a href="mailto:eduna@gmail.com">eduna@gmail.com</a></p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card h-100 text-center p-4 shadow">
                    <img src="https://png.pngtree.com/png-clipart/20190924/original/pngtree-location-icon-for-your-project-png-image_4852335.jpg" class="card-img-top mx-auto" style="width: 90px; height: 90px;" alt="Location">
                    <div class="card-body">
                        <h5 class="card-title">Địa chỉ của chúng tôi</h5>
                        <p>
                            <a href="https://www.google.com/maps?q=202+Tống+Duy+Tân,+P.+Lam+Sơn,+Thanh+Hóa,+Việt+Nam" target="_blank" onmouseover="openMap(this.href)">
                                202 Tống Duy Tân, P. Lam Sơn, Thanh Hóa
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Form Section -->
    <section class="bg-light py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 mb-4 mb-md-0">
                    <img src="https://www.thietkewebthuonghieu.com/wp-content/uploads/2022/01/top-10-trung-tam-day-tieng-anh-cho-tre-em.jpg" alt="contact-img" class="img-fluid rounded shadow">
                </div>

                <div class="col-md-6">
                    <h4 class="mb-3 text-primary">Liên hệ với chúng tôi</h4>
                    <form action="{{ route('support.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <select class=" @error('pl_content') is-invalid @enderror" name="pl_content">
                                <option value="">-- Chọn phân loại --</option>
                                <option value="khiếu nại" {{ old('pl_content') == 'khiếu nại' ? 'selected' : '' }}>Khiếu nại</option>
                                <option value="góp ý" {{ old('pl_content') == 'góp ý' ? 'selected' : '' }}>Góp ý</option>
                                <option value="hỗ trợ" {{ old('pl_content') == 'hỗ trợ' ? 'selected' : '' }}>Hỗ trợ</option>
                                <option value="đăng ký" {{ old('pl_content') == 'đăng ký' ? 'selected' : '' }}>Đăng ký</option>
                            </select>
                            @error('pl_content')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Tên" value="{{ old('name') }}">
                            @error('name')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" placeholder="SĐT" value="{{ old('phone') }}">
                            @error('phone')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <textarea class="form-control @error('message') is-invalid @enderror" name="message" rows="4" placeholder="Tin nhắn">{{ old('message') }}</textarea>
                            @error('message')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Gửi tin nhắn</button>

                       
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@section('scripts')
<script>
    let mapOpened = false;
    function openMap(url) {
        if (!mapOpened) {
            window.open(url, '_blank');
            mapOpened = true;
        }
    }
</script>
@endsection
