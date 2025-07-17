@extends('client.client')
@section('title', 'Đặt lại mật khẩu')
@section('content')

    <section class="container my-5">
        <div class="row justify-content-center py-5">
            <div class="col-md-6">
                <div class="card border-0 shadow-lg p-4" style="border-radius: 15px;">
                    <div class="card-body">
                        <h3 class="fw-bold mb-4 text-center">
                            <i class="fas fa-lock me-2"></i> Đặt lại mật khẩu
                        </h3>
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <div class="mb-4">
                                <!-- Email -->
                                <span class="d-flex align-items-center gap-2 pb-2">
                                    <i class="icofont-verification-check"></i>
                                    <label for="user-name" class="form-label fw-semibold mb-0">Mã đặt lại mật khẩu</label>
                                </span>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-envelope"></i></span>
                                    <input id="token" type="text" class="form-control form-control-lg" name="token"
                                        required placeholder="Nhập mã đặt lại mật khẩu">
                                </div>
                            </div>

                            <div class="mb-4">
                                <!-- Mật khẩu mới -->
                                <span class="d-flex align-items-center gap-2 pb-2 mt-3">
                                    <i class="icofont-lock"></i>
                                    <label for="password" class="form-label fw-semibold mb-0">Mật khẩu mới</label>
                                </span>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-lock"></i></span>
                                    <input id="password" type="password" class="form-control form-control-lg"
                                        name="password" required placeholder="Nhập mật khẩu mới">
                                </div>
                            </div>
                            <div class="mb-4">
                                <span class="d-flex align-items-center gap-2 pb-2 mt-3">
                                    <i class="icofont-lock"></i>
                                    <label for="password_confirmation" class="form-label fw-semibold mb-0">Xác nhận mật
                                        khẩu</label>
                                </span>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-lock"></i></span>
                                    <input id="password_confirmation" type="password" class="form-control form-control-lg"
                                        name="password_confirmation" required placeholder="Xác nhận mật khẩu">
                                </div>
                            </div>
                            <div class="d-grid">
                                <button type="submit" style="background: var(--primary-gradient);" class="btn text-white btn-lg fw-bold">Đặt lại mật khẩu</button>
                            </div>
                           
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
