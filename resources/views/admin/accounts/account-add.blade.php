@extends('admin.admin')
@section('title', 'Thêm ' . request('role'))
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
                            <h4 class="card-title">Thông tin {{ request('role') }}</h4>
                        </div>
                        <form method="POST" action="{{ route('admin.account.store', ['role' => request('role')]) }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">

                                    {{-- <div class="dropzone" id="myDropzone">
                                        <div class="fallback">
                                            <input name="avatar" type="file" multiple="multiple">
                                        </div>
                                        <div class="dz-message needsclick">
                                            <i class="h1 bx bx-cloud-upload"></i>
                                            <h3>Drop files here or click to upload.</h3>
                                            <span class="text-muted fs-13">
                                                (This is just a demo dropzone. Selected files are <strong>not</strong>
                                                actually uploaded.)
                                            </span>
                                        </div>
                                    </div>

                                    <ul class="list-unstyled mb-0" id="dropzone-preview">
                                        <li class="mt-2" id="dropzone-preview-list">
                                            <!-- This is used as the file preview template -->
                                            <div class="border rounded">
                                                <div class="d-flex p-2">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar-sm bg-light rounded">
                                                            <img data-dz-thumbnail class="img-fluid rounded d-block"
                                                                src="{{ old('avatar') }}" alt="" />
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="pt-1">
                                                            <h5 class="fs-14 mb-1" data-dz-name>&nbsp;</h5>
                                                            <p class="fs-13 text-muted mb-0" data-dz-size></p>
                                                            <strong class="error text-danger" data-dz-errormessage></strong>
                                                        </div>
                                                    </div>
                                                    <div class="flex-shrink-0 ms-3">
                                                        <button data-dz-remove class="btn btn-sm btn-danger">Delete</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul> --}}
                                    <!-- end dropzon-preview -->


                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="roles-name" class="form-label">Ảnh </label>
                                            <input type="file" name="avatar" class="form-control">

                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="roles-name" class="form-label">Tên </label>
                                            <input type="text" id="roles-name" name="name" class="form-control"
                                                placeholder="Nhập tên ...">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="user-name" class="form-label">Email</label>
                                            <input type="text" id="user-name" name="email" class="form-control"
                                                placeholder="Nhập email...">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="" class="form-label">password</label>
                                            <input type="text" name="password" class="form-control"
                                                placeholder="Nhập password...">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="" class="form-label">phone</label>
                                            <input type="tel" name="phone" class="form-control"
                                                placeholder="Nhập phone...">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="" class="form-label">birth_date</label>
                                            <input type="date" name="birth_date" class="form-control"
                                                placeholder="Nhập birth_date...">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <p>Giới tính</p>
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
