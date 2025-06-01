@extends('client.client')
@section('title', 'Quên mật khẩu')
@section('content')

<section class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg p-4" style="border-radius: 15px;">
                <div class="card-body">
                    <h1 class="fw-bold text-danger mb-4 text-center">
                        <i class="fas fa-key me-2"></i> Quên mật khẩu
                    </h1>
                   
                    @if($errors->has('email'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ $errors->first('email') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold">Email của bạn</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-envelope"></i></span>
                                <input id="email" type="email" class="form-control form-control-lg" name="email" value="{{ old('email') }}" required placeholder="Nhập email của bạn">
                            </div>
                        </div>
                        <div class="d-grid">    
                            <button type="submit" class="btn btn-danger btn-lg fw-bold">Gửi mã đặt lại mật khẩu</button>
                        </div>
                        <p class="mt-3 text-center">
                            Quay lại <a href="{{ route('auth.login') }}" class="text-primary fw-bold">Đăng nhập</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection