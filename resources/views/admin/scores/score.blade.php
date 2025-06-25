@extends('admin.admin')
@section('title', 'Quản lí điểm số')
@section('description', '')
@section('content')

    <div class="page-content">
        <div class="container-fluid ">
             <nav aria-label="breadcrumb p-0">
                <ol class="breadcrumb py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Quản lí điểm số</li>
                </ol>
            </nav>

            <div class="row">
                <div class="col-lg-3">
                    <form method="GET" action="{{route('admin.score.search')}}" class="app-search d-none d-md-block ms-2">
                        <div class="position-relative">
                            <input type="search" name="query" class="form-control" placeholder="Tìm lớp học và khóa học..." autocomplete="off" value="{{ request()->query('query') ?? '' }}">
                            <iconify-icon icon="solar:magnifer-linear" class="search-widget-icon"></iconify-icon>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="row mt-3">
                @foreach ($data as $class)
                    <div class="col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <a href="{{ route('admin.score.detail', ['class_id' => $class->id, 'course_id' => $class->courses_id ])}}">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <h4 class="card-title mb-2 d-flex align-items-center ">Lớp:</h4>
                                            <h4 class="card-title mb-2 d-flex align-items-center ">khóa học:</h4>
                                        </div>
                                        <div>
                                            <h4 class="card-title mb-2 d-flex align-items-center ">{{ $class->name }}</h4>
                                            <h4 class="card-title mb-2 d-flex align-items-center ">{{ $class->course->name }}
                                            </h4>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
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
    </div>
    </footer>

@endsection
