@extends('admin.admin')
@section('title', 'Nhập điểm')
@section('description', '')
@section('content')

    <!-- Tom Select CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">

    <!-- Tom Select JS -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <div class="page-content">
        <div class="container-fluid">
            <nav aria-label="breadcrumb p-0">
                <ol class="breadcrumb py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.score') }}">Quản lí điểm số</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.score.detail', ['class_id' => request('class_id'), 'course_id' => $class->courses_id]) }}">{{ $class->name }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Nhập điểm </li>
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
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title fw-bold">Thông tin</h4>

                        </div>
                        <form method="POST" action="{{ route('admin.score.store', [request('class_id')]) }}">
                            @csrf
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="student_filter" class="form-label fw-semibold">Học sinh</label>
                                            <select name="student_id" id="student_filter"
                                                class="form-select">
                                                <option value="">Chọn học sinh</option>
                                                @foreach ($data as $stdClass)
                                                    <option value="{{ $stdClass->student_id }}"
                                                        {{ request('student_id') == $stdClass->student_id ? 'selected' : '' }}>
                                                        {{ $stdClass->student->name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>


                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="roles-name" class="form-label fw-semibold">Loại điểm</label>
                                            <input type="text" name="score_type" class="form-control"
                                                placeholder="Nhập loại điểm...">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="user-name" class="form-label fw-semibold">Số điểm</label>
                                            <input type="number" step="any" min="0" name="score"
                                                class="form-control" placeholder="Nhập số điểm...">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="user-name" class="form-label fw-semibold">Ngày</label>
                                            <input type="date" 
                                                name="exam_date" class="form-control">

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
        document.addEventListener('DOMContentLoaded', function() {
            new TomSelect("#student_filter", {
                placeholder: "Chọn học sinh",
                allowEmptyOption: true,
                persist: false,
                create: false,
                maxOptions: 500,
                closeAfterSelect: true,
                sortField: {
                    field: "text",
                    direction: "asc"
                }
            });
        });
    </script>


@endsection
