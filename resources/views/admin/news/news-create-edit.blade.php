@extends('admin.admin')

@section('title', $isEdit ? 'Sửa Tin tức' : 'Thêm Tin tức')
@section('description', '')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.news.index') }}">Quản lý Tin tức</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $isEdit ? 'Sửa Tin tức' : 'Thêm Tin tức' }}</li>
                </ol>
            </nav>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header bg-light-subtle">
                            <h4 class="card-title mb-0">{{ $isEdit ? 'Sửa Tin tức' : 'Thêm Tin tức' }}</h4>
                        </div>
                        <div class="card-body">
                            <form id="news-form" action="{{ $isEdit ? route('admin.news.update', $news->id) : route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @if($isEdit)
                                    @method('PUT')
                                @endif
                                <div class="d-flex justify-content-between flex-wrap">
                                    <div id="error-container" class="col-12 mb-3" style="display: none;"></div>
                                    <!-- Cột bên trái: 8/12 -->
                                    <div class="col-lg-8 border row py-2 rounded">
                                        <div class="mb-3">
                                            <label for="news_title" class="form-label fw-bold">Tiêu đề</label>
                                            <input type="text" class="form-control form-control-sm @error('title') is-invalid @enderror" id="news_title" name="title" value="{{ old('title', $isEdit ? $news->title : '') }}" placeholder="Nhập tiêu đề tin tức">
                                            @error('title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="short_intro" class="form-label fw-bold">Giới thiệu ngắn</label>
                                            <textarea class="form-control form-control-sm @error('short_intro') is-invalid @enderror" id="short_intro" name="short_intro" rows="3" placeholder="Nhập giới thiệu ngắn">{{ old('short_intro', $isEdit ? $news->short_intro : '') }}</textarea>
                                            @error('short_intro')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="full_content" class="form-label fw-bold">Nội dung đầy đủ</label>
                                            <textarea class="form-control form-control-sm ckeditor @error('full_content') is-invalid @enderror" id="full_content" name="full_content" rows="5" placeholder="Nhập nội dung tin tức">{{ old('full_content', $isEdit ? $news->full_content : '') }}</textarea>
                                            @error('full_content')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                         <div class="mb-3 col-lg-6">
                                            <label for="topic_id" class="form-label fw-bold">Chủ đề <span class="text-danger">*</span></label>
                                            <select class="form-select form-select-sm @error('topic_id') is-invalid @enderror" id="topic_id" name="topic_id">
                                                <option value="" {{ old('topic_id', $isEdit ? $news->topic_id : '') == '' ? 'selected' : '' }}>Chọn chủ đề</option>
                                                @foreach (\App\Models\topics::all() as $topic)
                                                    <option value="{{ $topic->id }}" {{ old('topic_id', $isEdit ? $news->topic_id : '') == $topic->id ? 'selected' : '' }}>{{ $topic->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('topic_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-lg-6">
                                            <label for="event_type" class="form-label fw-bold">Loại</label>
                                            <select class="form-select form-select-sm @error('event_type') is-invalid @enderror" id="event_type" name="event_type">
                                                <option value="news" {{ old('event_type', $isEdit ? $news->event_type : '') == 'news' ? 'selected' : '' }}>Tin tức</option>
                                                <option value="event" {{ old('event_type', $isEdit ? $news->event_type : '') == 'event' ? 'selected' : '' }}>Sự kiện</option>
                                            </select>
                                            @error('event_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-lg-2 col-md-4">
                                            <label class="form-label fw-bold">Hiển thị</label>
                                            <div class="form-check">
                                                <input class="form-check-input @error('is_visible') is-invalid @enderror" type="radio" name="is_visible" id="is_visible_1" value="1" {{ old('is_visible', $isEdit ? $news->is_visible : 0) == 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_visible_1">Có</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input @error('is_visible') is-invalid @enderror" type="radio" name="is_visible" id="is_visible_0" value="0" {{ old('is_visible', $isEdit ? $news->is_visible : 0) == 0 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_visible_0">Không</label>
                                            </div>
                                            @error('is_visible')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-lg-2 col-md-4">
                                            <label class="form-label fw-bold">Nổi bật</label>
                                            <div class="form-check">
                                                <input class="form-check-input @error('is_featured') is-invalid @enderror" type="radio" name="is_featured" id="is_featured_1" value="1" {{ old('is_featured', $isEdit ? $news->is_featured : 0) == 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_featured_1">Có</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input @error('is_featured') is-invalid @enderror" type="radio" name="is_featured" id="is_featured_0" value="0" {{ old('is_featured', $isEdit ? $news->is_featured : 0) == 0 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_featured_0">Không</label>
                                            </div>
                                            @error('is_featured')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3 col-lg-3 col-md-6">
                                            <label class="form-label fw-bold">Trạng thái</label>
                                            <div class="form-check">
                                                <input class="form-check-input @error('publish_status') is-invalid @enderror" type="radio" name="publish_status" id="publish_status_published" value="published" {{ old('publish_status', $isEdit ? $news->publish_status : 'draft') == 'published' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="publish_status_published">Đã xuất bản</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input @error('publish_status') is-invalid @enderror" type="radio" name="publish_status" id="publish_status_draft" value="draft" {{ old('publish_status', $isEdit ? $news->publish_status : 'draft') == 'draft' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="publish_status_draft">Lưu nháp</label>
                                            </div>
                                            @error('publish_status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3 col-lg-3 col-md-6">
                                            <label class="form-label fw-bold">Hiển thị trang chủ</label>
                                            <div class="form-check">
                                                <input class="form-check-input @error('show_on_homepage') is-invalid @enderror" type="radio" name="show_on_homepage" id="show_on_homepage_1" value="1" {{ old('show_on_homepage', $isEdit ? $news->show_on_homepage : 0) == 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="show_on_homepage_1">Có</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input @error('show_on_homepage') is-invalid @enderror" type="radio" name="show_on_homepage" id="show_on_homepage_0" value="0" {{ old('show_on_homepage', $isEdit ? $news->show_on_homepage : 0) == 0 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="show_on_homepage_0">Không</label>
                                            </div>
                                            @error('show_on_homepage')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>


                                    <!-- Cột bên phải: 4/12 -->
                                    <div class="col-lg-4 border row rounded py-2">
                                        <div class="mb-3">
                                            <label for="image" class="form-label fw-bold">Hình ảnh</label>
                                            <input type="file" class="form-control form-control-sm @error('image') is-invalid @enderror" id="image" name="image" onchange="previewImage(event)">
                                            <div class="mt-2">
                                                <img id="imagePreview" src="{{ $isEdit && $news->image ? asset($news->image) : '' }}"
                                                    alt="Ảnh xem trước"
                                                    style="max-height: 150px; object-fit: cover;"
                                                    class="{{ $isEdit && $news->image ? '' : 'd-none' }}">
                                            </div>

                                            @error('image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="image_caption" class="form-label fw-bold">Chú thích hình ảnh</label>
                                            <input type="text" class="form-control form-control-sm @error('image_caption') is-invalid @enderror" id="image_caption" name="image_caption" value="{{ old('image_caption', $isEdit ? $news->image_caption : '') }}" placeholder="Nhập chú thích hình ảnh">
                                            @error('image_caption')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="seo_title" class="form-label fw-bold">SEO Title</label>
                                            <input type="text" class="form-control form-control-sm @error('seo_title') is-invalid @enderror" id="seo_title" name="seo_title" value="{{ old('seo_title', $isEdit ? $news->seo_title : '') }}" placeholder="Nhập SEO title">
                                            @error('seo_title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="seo_description" class="form-label fw-bold">SEO Description</label>
                                            <textarea class="form-control form-control-sm @error('seo_description') is-invalid @enderror" id="seo_description" name="seo_description" rows="3" placeholder="Nhập SEO description">{{ old('seo_description', $isEdit ? $news->seo_description : '') }}</textarea>
                                            @error('seo_description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="seo_keywords" class="form-label fw-bold">SEO Keywords</label>
                                            <input type="text" class="form-control form-control-sm @error('seo_keywords') is-invalid @enderror" id="seo_keywords" name="seo_keywords" value="{{ old('seo_keywords', $isEdit ? $news->seo_keywords : '') }}" placeholder="Nhập SEO keywords, cách nhau bởi dấu phẩy">
                                            @error('seo_keywords')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">
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

@push('scripts')
    <script>

            // Hàm preview hình ảnh
            window.previewImage = function(event) {
                const imagePreview = document.getElementById('imagePreview');
                imagePreview.src = URL.createObjectURL(event.target.files[0]);
                imagePreview.classList.remove('d-none');
            };
    </script>
@endpush
