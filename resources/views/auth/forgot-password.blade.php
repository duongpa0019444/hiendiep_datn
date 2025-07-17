@extends('client.client')
@section('title', 'Quên mật khẩu')
@section('content')

    <section class="container py-5">
        <div class="row justify-content-center py-5">
            <div class="col-md-6">
                <div class="card border-0 shadow-lg p-4" style="border-radius: 15px;">
                    <div class="card-body">
                        <h3 class="fw-bold mb-4 text-center" style="color: var(--primary-gradient);">
                            <i class="fas fa-key me-2"></i> Quên mật khẩu
                        </h3>

                        @if ($errors->has('email'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ $errors->first('email') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="mb-4">
                                <!-- Email -->
                                <span class="d-flex align-items-center gap-2 pb-2">
                                    <i class="icofont-email"></i>
                                    <label for="user-name" class="form-label fw-semibold mb-0">Email</label>
                                </span>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-envelope"></i></span>
                                    <input id="email" type="email" class="form-control form-control-lg" name="email"
                                        value="{{ old('email') }}" required placeholder="Nhập email của bạn">
                                </div>
                            </div>
                            <div class="d-grid">
                                <button style="background: var(--primary-gradient);" type="submit"
                                    class="btn btn-lg fw-bold text-white">Gửi mã đặt lại mật khẩu</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
