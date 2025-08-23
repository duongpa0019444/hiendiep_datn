@extends('admin.admin')
@section('title', 'Thêm người dùng')
@section('description', '')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <nav aria-label="breadcrumb p-0">
                <ol class="breadcrumb py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.account') }}">Quản lí người dùng</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Thêm người dùng</li>
                </ol>
            </nav>
            <div class="row">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h4 class="card-title fw-bold">Thêm người dùng</h4>
                            <span class="text-danger">lưu ý: (*) là trường bắt buộc nhập</span>
                        </div>
                        <form method="POST" action="{{ route('admin.account-store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Vai trò</label>
                                            <select name="role" id="role" class="form-select">
                                                <option value="">Chọn vai trò</option>
                                                <option value="admin">Admin</option>
                                                <option value="staff">Nhân viên</option>
                                                <option value="student">Học sinh</option>
                                                <option value="teacher">Giáo viên</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-6" id="mission-wrapper" style="display:none;">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Nhiệm vụ nhân viên</label>
                                            <select name="mission" id="mission" class="form-select">
                                                <option value="">Chọn nhiệm vụ</option>
                                                <option value="train">Quản lí đào tạo</option>
                                                <option value="accountant">Kế toán</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="roles-name" class="form-label fw-semibold">Ảnh </label>
                                            <input type="file" name="avatar" value="{{ old('avatar') }}"
                                                class="form-control">

                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="roles-name" class="form-label fw-semibold">Tên người dùng *</label>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ old('name') }}" placeholder="Nhập tên người dùng...">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="user-name" class="form-label fw-semibold">Tên đăng nhập *</label>
                                            <input type="text" name="username" class="form-control"
                                                value="{{ old('username') }}" placeholder="Nhập tên đăng nhập...">
                                            <i>Lưu ý: mật khẩu mặc định là tên đăng nhập</i>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="user-name" class="form-label fw-semibold">Email</label>
                                            <input type="email" name="email" class="form-control"
                                                value="{{ old('email') }}" placeholder="Nhập email..">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="" class="form-label fw-semibold">Số điện thoại</label>
                                            <input type="tel" name="phone" class="form-control"
                                                value="{{ old('phone') }}" placeholder="Nhập số điện thoại...">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="" class="form-label fw-semibold">Địa chỉ</label>
                                            <input type="text" name="address" class="form-control"
                                                value="{{ old('address') }}" placeholder="Nhập địa chỉ...">
                                        </div>
                                    </div>



                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="" class="form-label fw-semibold">Ngày sinh</label>
                                            <input type="date" name="birth_date" class="form-control"
                                                value="{{ old('birth_date') }}" placeholder="Nhập ngày sinh...">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <p class="fw-semibold">Giới tính</p>
                                        <div class="d-flex gap-2 align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" value="boy"
                                                    name="gender">
                                                <label class="form-check-label" for="gender">
                                                    Nam
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" value="girl"
                                                    name="gender">
                                                <label class="form-check-label" for="gender">
                                                    Nữ
                                                </label>
                                            </div>
                                        </div>
                                    </div>



                                </div>

                            </div>
                            <div class="card-footer border-top text-end pe-3">
                                <a href="{{ route('admin.account') }}"
                                    class="btn btn-secondary me-2">Quay Lại</a>
                                <button type="submit" class="btn btn-primary">Lưu</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
        <!-- end row -->
        <!-- End Container Fluid -->
        <!-- ========== Footer Start ========== -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 text-center">
                        <script>
                            document.write(new Date().getFullYear())
                        </script> &copy; DỰ ÁN TỐT NGHIỆP CAO ĐẲNG FPT THANH HÓA<iconify-icon
                            icon="iconamoon:heart-duotone" class="fs-18 align-middle text-danger"></iconify-icon> <a
                            href="#" class="fw-bold footer-text" target="_blank">NHÓM 4</a>
                    </div>
                </div>
            </div>
        </footer>

    </div>
    <script>
        document.getElementById('role').addEventListener('change', function() {
            const missionDiv = document.getElementById('mission-wrapper');
            const missionSelect = document.getElementById('mission');
            if (this.value === 'staff') {
                missionDiv.style.display = 'block';
            } else {
                missionDiv.style.display = 'none';
                missionSelect.value = '';
            }
        });
    </script>

@endsection
