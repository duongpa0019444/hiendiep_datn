@php
    $roles = [
        'student' => 'Học sinh',
        'teacher' => 'Giáo viên',
        'admin' => 'Quản trị viên',
        'staff' => 'Nhân viên',
    ];
@endphp
@extends('admin.admin')
@section('title', 'Cập nhật ' . $roles[request('role')] ?? request('role'))
@section('description', '')
@section('content')

    <div class="page-content">
        <div class="container-fluid">
            <nav aria-label="breadcrumb p-0">
                <ol class="breadcrumb py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.account') }}">Quản lí người dùng</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.account.list', request('role')) }}">{{ $roles[request('role')] ?? request('role') }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Cập nhật
                        {{ $roles[request('role')] ?? request('role') }}</li>
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
                        <div class="card-header">
                            <h4 class="card-title">Thông tin</h4>
                        </div>
                        <form method="POST"
                            action="{{ route('admin.account.update', ['role' => request('role'), 'id' => request('id')]) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-lg-6">
                                        <div class=" d-flex justify-content-start align-items-start gap-4">
                                            @if (!empty($info->avatar))
                                                <div>
                                                    <label class="form-label fw-semibold d-block mb-2">Ảnh hiện tại</label>
                                                    <div class="d-flex align-items-center mb-3">
                                                        <img src="{{ asset($info->avatar) }}" alt="Ảnh đại diện"
                                                            style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%; border: 2px solid #ccc;">
                                                    </div>
                                                </div>
                                            @endif

                                            <div>
                                                <label for="avatar" class="form-label fw-semibold">Chọn ảnh mới</label>
                                                <input type="file" name="avatar" id="avatar" class="form-control">
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="roles-name" class="form-label fw-semibold">Tên người dùng</label>
                                            <input type="text" value="{{ $info->name ?? '' }}" id="roles-name"
                                                name="name" class="form-control" placeholder="Nhập tên ...">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="user-name" class="form-label fw-semibold">Tên đăng nhập</label>
                                            <input type="text" value="{{ $info->username ?? '' }}" id="user-name"
                                                name="username" class="form-control" placeholder="Nhập tên đăng nhập...">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="user-name" class="form-label fw-semibold">Email</label>
                                            <input type="email" value="{{ $info->email }}" id="user-name" name="email"
                                                class="form-control" placeholder="Nhập Email...">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="" class="form-label fw-semibold">password</label>
                                            <input type="password" name="password" class="form-control"
                                                placeholder="Nhập password mới (nếu muốn thay đổi)">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="" class="form-label fw-semibold">phone</label>
                                            <input type="tel" value="{{ $info->phone }}" name="phone"
                                                class="form-control" placeholder="Nhập phone...">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="" class="form-label fw-semibold">birth_date</label>
                                            <input type="date" value="{{ $info->birth_date }}" name="birth_date"
                                                class="form-control" placeholder="Nhập birth_date...">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="" class="form-label fw-semibold">Giới tính</label>
                                        <div class="d-flex gap-2 align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" value="boy"
                                                    name="gender"
                                                    {{ old('gender', $info->gender ?? '') === 'boy' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="gender_boy">
                                                    Nam
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" value="girl"
                                                    name="gender"
                                                    {{ old('gender', $info->gender ?? '') === 'girl' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="gender_girl">
                                                    Nữ
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <div class="card-footer border-top">
                                <button type="submit" class="btn btn-primary">Cập nhật người dùng</button>
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
        // Dropzone
        var dropzonePreviewNode = document.querySelector("#dropzone-preview-list");
        dropzonePreviewNode.id = "";
        if (dropzonePreviewNode) {
            var previewTemplate = dropzonePreviewNode.parentNode.innerHTML;
            dropzonePreviewNode.parentNode.removeChild(dropzonePreviewNode);
            var dropzone = new Dropzone(".dropzone", {
                url: 'https://httpbin.org/post',
                method: "post",
                previewTemplate: previewTemplate,
                previewsContainer: "#dropzone-preview",
            });
        }
    </script>

@endsection
