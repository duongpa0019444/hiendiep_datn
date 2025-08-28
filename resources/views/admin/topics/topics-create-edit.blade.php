@extends('admin.admin')

@section('title', $isEdit ? 'Sửa Chủ đề' : 'Thêm Chủ đề')
@section('description', '')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.topics.index') }}">Quản lý Chủ đề</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $isEdit ? 'Sửa Chủ đề' : 'Thêm Chủ đề' }}</li>
                </ol>
            </nav>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header bg-light-subtle">
                            <h4 class="card-title mb-0">{{ $isEdit ? 'Sửa Chủ đề' : 'Thêm Chủ đề' }}</h4>
                        </div>
                        <div class="card-body">
                            <form id="topic-form" action="{{ $isEdit ? route('admin.topics.update', $topic->id) : route('admin.topics.store') }}" method="POST">
                                @csrf
                                @if($isEdit)
                                    @method('PUT')
                                @endif
                                <div class="d-flex justify-content-between flex-wrap">
                                    <div id="error-container" class="col-12 mb-3" style="display: none;"></div>
                                    <!-- Cột chính -->
                                    <div class="col-lg-12 border row py-2 rounded">
                                        <div class="mb-3">
                                            <label for="name" class="form-label fw-bold">Tên Chủ đề <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-sm @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $isEdit ? $topic->name : '') }}" placeholder="Nhập tên chủ đề">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="description" class="form-label fw-bold">Mô tả</label>
                                            <textarea class="form-control form-control-sm @error('description') is-invalid @enderror" id="description" name="description" rows="5" placeholder="Nhập mô tả chủ đề">{{ old('description', $isEdit ? $topic->description : '') }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.topics.index') }}" class="btn btn-secondary">
                                        <iconify-icon icon="solar:close-circle-broken" class="me-1"></iconify-icon> Hủy
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <iconify-icon icon="solar:check-circle-broken" class="me-1"></iconify-icon> Lưu
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 text-center">
                        <script>
                            document.write(new Date().getFullYear())
                        </script> © DỰ ÁN TỐT NGHIỆP CAO ĐẲNG FPT POLYTECHNIC  THANH HÓA
                        <iconify-icon icon="iconamoon:heart-duotone" class="fs-18 align-middle text-danger"></iconify-icon>
                        <a href="#" class="fw-bold footer-text" target="_blank">Tiger Code</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
@endsection
