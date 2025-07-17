@extends('client.accounts.information')

@section('content-information')
    <div id="account" class="content-section">
        <h5>Cập nhật Tài Khoản</h5>

        <form method="POST" action="{{ route('client.account.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row">

                    <div class="col-lg-6">
                        <div class=" d-flex justify-content-start align-items-start gap-4">
                            @if (!empty($info->avatar))
                                <div>
                                    <div class="d-flex align-items-center mb-3">
                                        <img class="rounded-circle" width="100" src="{{ asset($info->avatar) }}"
                                            alt="Ảnh đại diện">
                                    </div>
                                </div>
                            @endif

                            <div>
                                <span class="d-flex align-items-center gap-2">
                                    <i class="icofont-upload"></i>
                                    <label for="avatar" class="form-label fw-semibold mb-0">Chọn ảnh mới</label>
                                </span>
                                <input type="file" name="avatar" id="avatar" class="form-control">
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-6">
                        <div class="mb-3">
                            <span class="d-flex align-items-center gap-2">
                                <i class="icofont-user"></i>
                                <label for="roles-name" class="form-label fw-semibold mb-0">Tên người dùng</label>
                            </span>
                            <input type="text" value="{{ $info->name ?? '' }}" id="roles-name" name="name"
                                class="form-control" placeholder="Nhập tên ...">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="mb-3">
                            <span class="d-flex align-items-center gap-2">
                                <i class="icofont-id"></i>
                                <label for="user-name" class="form-label fw-semibold mb-0">Tên đăng nhập</label>
                            </span>
                            <input type="text" value="{{ $info->username ?? '' }}" id="user-name" name="username"
                                class="form-control" placeholder="Nhập tên đăng nhập...">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <span class="d-flex align-items-center gap-2">
                                <i class="icofont-email"></i>
                                <label for="user-name" class="form-label fw-semibold mb-0">Email</label>
                            </span>
                            <input type="email" value="{{ $info->email }}" id="user-name" name="email"
                                class="form-control" placeholder="Nhập Email...">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="mb-3">
                            <span class="d-flex align-items-center gap-2">
                                <i class="icofont-phone"></i>
                                <label class="form-label fw-semibold mb-0">Phone</label>
                            </span>
                            <input type="tel" value="{{ $info->phone }}" name="phone" class="form-control"
                                placeholder="Nhập phone...">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="mb-3">
                            <span class="d-flex align-items-center gap-2">
                                <i class="icofont-calendar"></i>
                                <label class="form-label fw-semibold mb-0">Ngày sinh</label>
                            </span>
                            <input type="date" value="{{ $info->birth_date }}" name="birth_date" class="form-control"
                                placeholder="Nhập birth_date...">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <span class="d-flex align-items-center gap-2">
                            <i class="icofont-user-male"></i>
                            <label class="form-label fw-semibold mb-0">Giới tính</label>
                        </span>
                        <div class="d-flex gap-2 align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" value="boy" name="gender"
                                    {{ old('gender', $info->gender ?? '') === 'boy' ? 'checked' : '' }}>
                                Nam
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" value="girl" name="gender"
                                    {{ old('gender', $info->gender ?? '') === 'girl' ? 'checked' : '' }}>

                                Nữ
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <div class="card-footer text-end">
                <button type="submit" style="background: var(--primary-gradient);" class="btn btn-primary">Cập nhật người
                    dùng</button>
            </div>
        </form>


        <h4 class="mt-5">Đổi mật khẩu</h4>
        <form method="POST" action="{{ route('client.account.changePassword') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">


                <div class="col-lg-12">
                    <div class="mb-3">
                        <span class="d-flex align-items-center gap-2">
                            <i class="icofont-lock"></i>
                            <label for="roles-name" class="form-label fw-semibold mb-0">Mật khẩu cũ</label>
                        </span>
                        <input type="password" id="roles-name" name="current_password" class="form-control"
                            placeholder="Nhập mật khẩu cũ ...">
                        @error('current_password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror

                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="mb-3">
                        <span class="d-flex align-items-center gap-2">
                            <i class="icofont-key"></i>
                            <label for="user-name" class="form-label fw-semibold mb-0">Mật khẩu mới</label>
                        </span>
                        <input type="password" id="user-name" name="new_password" class="form-control"
                            placeholder="Nhập Mật khẩu mới...">
                        @error('new_password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror

                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="mb-3">
                        <span class="d-flex align-items-center gap-2">
                            <i class="icofont-check-alt"></i>
                            <label for="user-name" class="form-label fw-semibold mb-0">Xác nhận mật khẩu mới</label>
                        </span>
                        <input type="password" id="user-name" name="new_password_confirmation" class="form-control"
                            placeholder="Xác nhận mật khẩu mới...">
                        @error('new_password_confirmation')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror

                    </div>
                </div>


            </div>
            <div class="card-footer text-end">
                <button type="submit" style="background: var(--primary-gradient);" class="btn btn-primary">Cập nhật mật
                    khẩu</button>
            </div>
        </form>

    </div>
@endsection
