@extends('admin.admin')

@section('title', 'Sửa bài giảng')
@section('description', '')
@section('content')



    <div class="page-content">
        <form action="{{ route('admin.lession-update', ['course_id' => $course_id, 'id' => $id]) }}" method="POST">
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
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Sửa thông tin</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- Tên khóa học -->
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Tên bài giảng </label>
                                            <input type="text" name="name" id="name"
                                                class="form-control"value="{{ $lession->name }}">
                                        </div>
                                    </div>

                                    <!-- Giá khóa học -->
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="link_document" class="form-label">Tài liệu học </label>
                                            <input type="text" name="link_document" id="link_document"
                                                class="form-control" value="{{ $lession->link_document }}">
                                        </div>
                                    </div>

                                    <!-- Chọn Bài quizz qua các option -->

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <select name="quizz_id" id="quizz_id" class="form-select">
                                                {{-- <option value=" {{ $quizz->name }}"></option> --}}
                                                <option value="">-- Chọn bài Quiz --</option>
                                                @foreach ($quizz as $quiz)
                                                    {{-- <option value="{{ $quiz->id }}">{{ $quiz->name }}</option> --}}
                                                    <option value="{{ $quiz->id }}">{{ $quiz->title }}</option>
                                                @endforeach

                                            </select>
                                        </div>



                                
                                        <!-- Buttons -->




                                    </div>
                                    <div class="p-3 bg-light mb-3 rounded">
                                        <div class="row justify-content-end g-2">

                                            <div class="col-lg-2">
                                                <button type="reset" class="btn btn-primary w-100">Cancel</button>
                                            </div>

                                            <div class="col-lg-2">
                                                <button type="submit" class="btn btn-outline-secondary w-100">Save
                                                    Change</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <button class="btn btn-soft-primary">
                                            <a href="{{ route('admin.course-detail', ['id' => $course_id]) }}"
                                                class="link-primary text-decoration-underline link-offset-2"
                                                style="color: black">Trở về trang chi tiết khóa học <i
                                                    class="bx bx-arrow-to-right align-middle fs-16"></i></a>
                                        </button>
                                    </div>

                                </div>

                            </div>
        </form>
    </div>

@endsection
