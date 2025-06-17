@extends('admin.admin')

@section('title', 'Trang admin')
@section('description', '')
@section('content')

<div class="page-content">
    <form action="{{ route('admin.course-update',$course->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Start Container Fluid -->
        <div class="container-xxl">
            <div class="row">
                <div class="col-xl-12 col-lg-10">
                    <!-- Thêm ảnh -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Thêm ảnh</h4>
                        </div>
                        <div class="card-body">
                            <!-- File Upload -->
                            <div class="fallback">
                                <input name="file" type="file" multiple />
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
                                        <input type="text" name="name" id="name" class="form-control"value="{{ $course->name }}" >
                                    </div>
                                </div>

                                <!-- Giá khóa học -->
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="price" class="form-label">Giá khóa học</label>
                                        <input type="number" name="price" id="price" class="form-control" value="{{ $course->price }}">
                                    </div>
                                </div>

                                <!-- Tổng số buổi học -->
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="total_sessions" class="form-label">Tổng số buổi học</label>
                                        <input type="number" name="total_sessions" id="total_sessions" class="form-control" value="{{ $course->total_sessions }}">
                                    </div>
                                </div>

                                <!-- Ngày tạo -->
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="created_at" class="form-label">Ngày tạo khóa học</label>
                                        <input type="datetime-local" name="created_at" id="created_at" class="form-control" value="{{ $course->created_at }}">
                                    </div>
                                </div>

                                <!-- Ngày cập nhật -->
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="updated_at" class="form-label">Ngày cập nhật khóa học</label>
                                        <input type="datetime-local" name="updated_at" id="updated_at" class="form-control" value="{{ $course->updated_at }}">
                                    </div>
                                </div>

                                <!-- Nội dung khóa học -->
                                <div class="col-lg-12">
                                    <div class="mb-0">
                                        <label for="description" class="form-label">Nội dung khóa học</label>
                                        <input class="form-control bg-light-subtle" name="description" id="description" rows="7" value="{{ $course->description }}"></input>
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

                </div>
            </div>
        </div>
    </form>
</div>

@endsection
