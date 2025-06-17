@extends('admin.admin')
@section('title', 'Nhập điểm')
@section('description', '')
@section('content')

    <div class="page-content">
        <div class="container-fluid">
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
                        <div class="card-header">
                            <h4 class="card-title fw-bold">Thêm {{ request('role') }}</h4>
                        </div>
                        <form method="POST" action="{{ route('admin.account.store', ['role' => request('role')]) }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="roles-name" class="form-label fw-semibold">Ảnh </label>
                                            <input type="file" name="avatar" class="form-control">

                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="roles-name" class="form-label fw-semibold">Tên người dùng</label>
                                            <input type="text"  name="name" class="form-control"
                                                placeholder="Nhập tên người dùng...">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="user-name" class="form-label fw-semibold">Tên đăng nhập</label>
                                            <input type="text" name="username" class="form-control"
                                                placeholder="Nhập tên đăng nhập...">
                                                <i>Lưu ý: mật khẩu mặc định là tên đăng nhập</i>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="user-name" class="form-label fw-semibold">Email</label>
                                            <input type="email" name="email" class="form-control"
                                                placeholder="Nhập email..">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="" class="form-label fw-semibold">phone</label>
                                            <input type="tel" name="phone" class="form-control"
                                                placeholder="Nhập phone...">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="" class="form-label fw-semibold">birth_date</label>
                                            <input type="date" name="birth_date" class="form-control"
                                                placeholder="Nhập birth_date...">
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
                            <div class="card-footer border-top">
                                <button type="submit" class="btn btn-primary">Create Roles</button>
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
        Dropzone.options.myDropzone = {
            autoProcessQueue: false,
            paramName: "avatar",
            maxFiles: 1,
            addRemoveLinks: true,
            init: function() {
                var myDropzone = this;

                document.querySelector("form").addEventListener("submit", function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    if (myDropzone.getQueuedFiles().length > 0) {
                        myDropzone.processQueue(); // submit file
                    } else {
                        this.submit(); // không có file thì submit luôn
                    }

                    myDropzone.on("success", function() {
                        document.querySelector("form")
                    .submit(); // submit form sau khi upload thành công
                    });
                });
            }
        };
    </script>

@endsection
