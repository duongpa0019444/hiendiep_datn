@extends('admin.admin')

@section('title', 'Trang admin')
@section('description', '')
@section('content')

    <div class="page-content">
        <form action="{{ route('admin.lession-add', ['id' => $id]) }}" method="POST">
            @csrf

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
                                            <label for="name" class="form-label">Tên bài giảng </label>
                                            <input type="text" name="name" id="name" class="form-control"
                                                placeholder="Nhập tên bài giảng ">
                                        </div>
                                    </div>

                                    <!-- Giá khóa học -->
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="link_document" class="form-label">Tài liệu học</label>
                                            <input type="text" name="link_document" id="link_document"
                                                class="form-control" placeholder="TTài liệu học">
                                        </div>
                                    </div>




                                    <!-- Ngày cập nhật -->
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="updated_at" class="form-label">Ngày cập nhật</label>
                                            <input type="datetime-local" name="updated_at" id="updated_at"
                                                class="form-control">
                                        </div>
                                    </div>
                                    {{-- Chọn Bài quizz qua các option --}}
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="quizz_id" class="form-label">Bài Quizz liên kết</label>
                                            <select name="quizz_id" id="quizz_id" class="form-select">
                                                <option value="">-- Chọn bài quizz --</option>
                                                @foreach ($quizz as $quiz)
                                                    <option value="{{ $quiz->id }}">{{ $quiz->title }}</option>
                                                @endforeach
                                            </select>
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
