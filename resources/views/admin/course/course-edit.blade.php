@extends('admin.admin')

@section('title', 'Trang admin')
@section('description', '')
@section('content')

    <div class="page-content">


        <form action="{{ route('admin.course-update', $course->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Start Container Fluid -->

            <div class="container-xxl">
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
                    <div class="col-xl-12 col-lg-10">
                        <nav aria-label="breadcrumb p-6">
                            <ol class="breadcrumb py-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a
                                        href="{{ route('admin.course-list') }}">Quản lí khóa học</a> </li>
                                <li class="breadcrumb-item active" aria-current="page">Sửa khóa học </li>
                            </ol>
                        </nav>
                        <!-- Thêm ảnh -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Thêm ảnh</h4>
                            </div>
                            <div class="card-body">
                                <!-- File Upload -->
                                <div class="fallback">
                                    <input type="file" name="image" accept="image/*" multiple>
                                </div>
                            </div>
                        </div>

                        <!-- Thêm thông tin -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Sửa thông tin</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- Tên khóa học -->
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Tên khóa học</label>
                                            <input type="text" name="name" id="name"
                                                class="form-control"value="{{ $course->name }}">
                                        </div>
                                    </div>

                                    <!-- Giá khóa học -->
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="price" class="form-label">Giá khóa học</label>
                                            <input type="number" name="price" id="price" class="form-control"
                                                value="{{ $course->price }}">
                                        </div>
                                    </div>

                                    <!-- Tổng số buổi học -->
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="total_sessions" class="form-label">Tổng số buổi học</label>
                                            <input type="number" name="total_sessions" id="total_sessions"
                                                class="form-control" value="{{ $course->total_sessions }}">
                                        </div>
                                    </div>

                                    <!-- Nội dung khóa học -->
                                    <div class="col-lg-12">
                                        <div class="mb-0">
                                            <label for="description" class="form-label">Nội dung khóa học</label>
                                            <textarea class="form-control bg-light-subtle ckeditor" name="description" id="description" rows="7"
                                                >{{ $course->description }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Buttons -->
                        <div class="p-3 bg-light mb-3 rounded">

                            <div class="row justify-content-end g-2">

                                <div class="col-lg-2">
                                    <button type="reset" class="btn btn-primary w-100">Cancel</button>
                                </div>

                                <div class="col-lg-2">
                                    <button type="submit" class="btn btn-outline-secondary w-100">Save Change</button>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                                  <button class="btn btn-soft-primary">
                                      <a href="{{ route('admin.course-list') }}"
                                          class="link-primary text-decoration-underline link-offset-2" style="color: black">Trở về trang khóa học <i
                                              class="bx bx-arrow-to-right align-middle fs-16"></i></a>
                                  </button>
                              </div>

                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection
