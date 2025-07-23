@extends('admin.admin')

@section('title', 'Trang admin')
@section('description', '')
@section('content')

    <div class="page-content">

        <form action="{{ route('admin.course-create') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- Start Container Fluid -->

            <div class="container-xxl">
                <div class="row">
                    <div class="col-xl-12 col-lg-10">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <nav aria-label="breadcrumb p-6">
                            <ol class="breadcrumb py-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a
                                        href="{{ route('admin.course-list') }}">Quản lí khóa học</a> </li>
                                <li class="breadcrumb-item active" aria-current="page">Thêm khóa học </li>
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
                                <h4 class="card-title">Thêm thông tin</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- Tên khóa học -->
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Tên khóa học</label>
                                            <input type="text" name="name" id="name" class="form-control"
                                                placeholder="Nhập tên khóa học">
                                        </div>
                                    </div>

                                    <!-- Giá khóa học -->
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="price" class="form-label">Giá khóa học</label>
                                            <input type="number" name="price" id="price" class="form-control"
                                                placeholder="Nhập giá khóa học">
                                        </div>
                                    </div>

                                    <!-- Tổng số buổi học -->
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="total_sessions" class="form-label">Tổng số buổi học</label>
                                            <input type="number" name="total_sessions" id="total_sessions"
                                                class="form-control" placeholder="Nhập tổng số buổi học">
                                        </div>
                                    </div>

                                    <!-- Ngày tạo -->
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="created_at" class="form-label">Ngày tạo khóa học</label>
                                            <input type="datetime-local" name="created_at" id="created_at"
                                                class="form-control">
                                        </div>
                                    </div>

                                    <!-- Ngày cập nhật -->
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="updated_at" class="form-label">Ngày cập nhật khóa học</label>
                                            <input type="datetime-local" name="updated_at" id="updated_at"
                                                class="form-control">
                                        </div>
                                    </div>

                                    <!-- Nội dung khóa học -->
                                    <div class="col-lg-12">
                                        <div class="mb-0">
                                            <label for="description" class="form-label">Nội dung khóa học</label>
                                            <textarea class="form-control bg-light-subtle ckeditor" name="description" id="description" rows="7"
                                                placeholder="Nhập nội dung khóa học"></textarea>
                                        </div>
                                    </div>
                                    {{-- <div class="col-lg-12">
                                        <div class="mb-0">
                                            <label for="teaching_method" class="form-label">Phương pháp dạy học </label>
                                            <textarea class="form-control bg-light-subtle ckeditor" name="teaching_method" id="teaching_method" rows="7"
                                                placeholder="Nhập phương pháp dạy học "></textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-0">
                                            <label for="teaching_goals" class="form-label">Mục tiêu khóa hoc </label>
                                            <textarea class="form-control bg-light-subtle ckeditor" name="teaching_goals" id="teaching_goals" rows="7"
                                                placeholder="Nhập mục tiêu khóa học "></textarea>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                       
                        <!-- Buttons -->
                        <div class="p-3 bg-light mb-3 rounded">
                             
                            <div class="row justify-content-end g-2">

                                <div class="col-lg-2">
                                    {{-- <button type="reset" class="btn btn-primary w-100">Cancel</button> --}}
                                    <button type="button" class="btn btn-primary w-100" onclick="resetForm()">Cancel</button>

                                </div>

                                <div class="col-lg-2">
                                    <button type="submit" class="btn btn-outline-secondary w-100">Save Change</button>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button class="btn btn-soft-primary">
                                <a href="{{ route('admin.course-list') }}"
                                    class="link-primary text-decoration-underline link-offset-2" style="color: black">Trở
                                    về trang khóa học <i class="bx bx-arrow-to-right align-middle fs-16"></i></a>
                            </button>
                        </div>


                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection
 @push('scripts')
   <script>
    function resetForm() {
        if (!confirm("Bạn có chắc muốn hủy và xóa toàn bộ dữ liệu đã nhập?")) return;

        const form = document.querySelector('form');
        if (!form) return;

        // Reset toàn bộ input, select, textarea (trừ CKEditor và file)
        form.reset();

        // Reset CKEditor
        if (typeof CKEDITOR !== 'undefined') {
            for (let instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].setData('');
            }
        }

        // Reset các input type="file"
        const fileInputs = form.querySelectorAll('input[type="file"]');
        fileInputs.forEach(input => {
            input.value = '';
        });

        // Nếu bạn dùng file preview (hiện ảnh khi chọn), thì cũng clear nó nếu có
        const imagePreviews = document.querySelectorAll('.image-preview');
        imagePreviews.forEach(preview => preview.innerHTML = '');
    }
</script>
     
 @endpush