@extends('admin.admin')
@section('title', 'Nhập điểm')
@section('description', '')
@section('content')

    <div class="page-content">
        <div class="container-fluid">
            <nav aria-label="breadcrumb p-0">
                <ol class="breadcrumb py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.score') }}">Quản lí điểm số</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.score.detail', [request('class_id'), $class->courses_id])}}">{{ $class->name }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Sửa điểm</li>
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

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title fw-bold">Sửa điểm</h4>
                        </div>
                        <form method="POST"
                            action="{{ route('admin.score.update', ['class_id' => request('class_id'), 'id' => $score->id]) }}">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="roles-name" class="form-label fw-semibold">Học sinh</label>
                                            <select class="form-select" name="student_id" id="">
                                                <option value="">Chọn học sinh</option>
                                                @foreach ($data as $stdClass)
                                                    <option value="{{ $stdClass->student_id }}"
                                                        {{ $score->student_id == $stdClass->student_id ? 'selected' : '' }}>
                                                        {{ $stdClass->student->name }}
                                                    </option>
                                                @endforeach
                                            </select>


                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="roles-name" class="form-label fw-semibold">Loại điểm</label>
                                            <input type="text" name="score_type"
                                                class="form-control" value="{{ $score->score_type }}"
                                                placeholder="Nhập loại điểm...">


                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="user-name" class="form-label fw-semibold">Số điểm</label>
                                            <input type="number" step="any" min="0" name="score"
                                                class="form-control" value="{{ $score->score }}"
                                                placeholder="Nhập số điểm...">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="user-name" class="form-label fw-semibold">Ngày</label>
                                            <input type="date" name="exam_date" class="form-control"
                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                value="{{ $score->exam_date }}">

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer border-top">
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
