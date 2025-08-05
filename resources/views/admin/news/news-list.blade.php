@extends('admin.admin')

@section('title', 'Quản lý Tin tức')
@section('description', '')
@section('content')
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: '{{ session('success') }}',
                confirmButtonText: 'OK'

            });
        </script>
        {{ session()->forget('success') }}
    @endif
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: '{{ session('error') }}',
                confirmButtonText: 'OK'
            });
        </script>
        {{ session()->forget('error') }}
    @endif

    <div class="page-content">
        <div class="container-fluid">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Quản lý Tin tức</li>
                </ol>
            </nav>

            <div class="row gx-2 gy-2">
                {{-- Tổng bài viết --}}
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card mb-0">
                        <div class="card-body py-2 px-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="mb-1 fs-5">Bài viết</h6>
                                    <p class="text-muted fw-semibold fs-5 mb-0">{{ $statistics[0]->total_news }}</p>
                                </div>
                                <div class="avatar-sm bg-primary bg-opacity-10 rounded">
                                    <iconify-icon icon="solar:document-broken"
                                        class="fs-24 avatar-title text-primary"></iconify-icon>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Bài công khai --}}
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card mb-0">
                        <div class="card-body py-2 px-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="mb-1 fs-5">Hiển thị</h6>
                                    <p class="text-muted fw-semibold fs-5 mb-0">{{ $statistics[0]->total_public_news }}</p>
                                </div>
                                <div class="avatar-sm bg-success bg-opacity-10 rounded">
                                    <iconify-icon icon="solar:global-broken"
                                        class="fs-24 avatar-title text-success"></iconify-icon>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Lượt xem --}}
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card mb-0">
                        <div class="card-body py-2 px-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="mb-1 fs-5">Lượt xem</h6>
                                    <p class="text-muted fw-semibold fs-5 mb-0">{{ $statistics[0]->total_views }}</p>
                                </div>
                                <div class="avatar-sm bg-info bg-opacity-10 rounded">
                                    <iconify-icon icon="solar:eye-broken"
                                        class="fs-24 avatar-title text-info"></iconify-icon>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Chủ đề --}}
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card mb-0">
                        <div class="card-body py-2 px-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="mb-1 fs-5">Chủ đề</h6>
                                    <p class="text-muted fw-semibold fs-5 mb-0">{{ $statistics[0]->total_topics }}</p>
                                </div>
                                <div class="avatar-sm bg-warning bg-opacity-10 rounded">
                                    <iconify-icon icon="solar:folder-broken"
                                        class="fs-24 avatar-title text-warning"></iconify-icon>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Nổi bật --}}
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card mb-0">
                        <div class="card-body py-2 px-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="mb-1 fs-5">Bài nổi bật</h6>
                                    <p class="text-muted fw-semibold fs-5 mb-0">{{ $statistics[0]->total_featured }}</p>
                                </div>
                                <div class="avatar-sm bg-danger bg-opacity-10 rounded">
                                    <iconify-icon icon="solar:star-broken"
                                        class="fs-24 avatar-title text-danger"></iconify-icon>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Trang chủ --}}
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card mb-0">
                        <div class="card-body py-2 px-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="mb-1 fs-5">Trang chủ</h6>
                                    <p class="text-muted fw-semibold fs-5 mb-0">{{ $statistics[0]->total_homepage }}</p>
                                </div>
                                <div class="avatar-sm bg-secondary bg-opacity-10 rounded">
                                    <iconify-icon icon="solar:home-broken"
                                        class="fs-24 avatar-title text-secondary"></iconify-icon>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Title and Actions -->
            <div class="d-flex justify-content-between align-items-center mb-1 mt-2">
                <h4 class="card-title mb-1">Danh sách tin tức</h4>
                <div class="row">
                    <div class="col-auto">
                        <a href="{{ route('admin.news.trash') }}"
                            class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
                            <iconify-icon icon="mdi:trash-can-outline" class="fs-20"></iconify-icon>
                            <span>Thùng rác</span>
                        </a>
                    </div>

                    <div class="col-auto">
                        <a href="{{ route('admin.news.create') }}"
                            class="btn btn-primary btn-sm d-flex align-items-center gap-1">
                            <iconify-icon icon="solar:plus-circle-broken" class="fs-20"></iconify-icon>
                            <span>Thêm tin tức</span>
                        </a>
                    </div>
                </div>

            </div>

            <!-- News List -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card p-2">
                        <form action="{{ route('admin.news.filter') }}"
                            class="d-flex flex-wrap flex-xl-nowrap gap-2 align-items-end w-100" id="searchForm">
                            <input type="hidden" name="limit" id="limit" value="10">

                            {{-- Từ khóa - dài nhất --}}
                            <div class="flex-grow-1"style="min-width: 160px; flex: 0 0 12%;">
                                <label for="keyword" class="form-label mb-1">Từ khóa</label>
                                <input type="text" name="keyword" id="keyword" class="form-control form-control-sm"
                                    placeholder="Tìm tiêu đề">
                            </div>

                            {{-- Chủ đề - hơi dài --}}
                            <div style="min-width: 160px; flex: 0 0 15%;">
                                <label for="topic_filter" class="form-label mb-1">Chủ đề</label>
                                <select name="topic_id" id="topic_filter" class="form-select form-select-sm" data-choices>
                                    <option value="">Tất cả</option>
                                    @foreach (\App\Models\topics::all() as $topic)
                                        <option value="{{ $topic->id }}"
                                            {{ request('topic_id') == $topic->id ? 'selected' : '' }}>
                                            {{ $topic->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Loại --}}
                            <div style="flex: 1;">
                                <label for="event_type_filter" class="form-label mb-1">Loại</label>
                                <select name="event_type" id="event_type_filter" class="form-select form-select-sm">
                                    <option value="">Tất cả</option>
                                    <option value="news">Tin tức</option>
                                    <option value="event">Sự kiện</option>
                                </select>
                            </div>

                            {{-- Hiển thị --}}
                            <div style="flex: 1;">
                                <label for="is_visible_filter" class="form-label mb-1">Hiển thị</label>
                                <select name="is_visible" id="is_visible_filter" class="form-select form-select-sm">
                                    <option value="">Tất cả</option>
                                    <option value="1">Có</option>
                                    <option value="0">Không</option>
                                </select>
                            </div>

                            {{-- Trạng thái --}}
                            <div style="flex: 1;">
                                <label for="publish_status_filter" class="form-label mb-1">Trạng thái</label>
                                <select name="publish_status" id="publish_status_filter"
                                    class="form-select form-select-sm">
                                    <option value="">Tất cả</option>
                                    <option value="published">Đã xuất bản</option>
                                    <option value="draft">Lưu nháp</option>
                                </select>
                            </div>

                            {{-- Trang chủ --}}
                            <div style="flex: 1;">
                                <label for="show_home_filter" class="form-label mb-1">Trang chủ</label>
                                <select name="show_on_homepage" id="show_home_filter" class="form-select form-select-sm">
                                    <option value="">Tất cả</option>
                                    <option value="1">Có</option>
                                    <option value="0">Không</option>
                                </select>
                            </div>

                            {{-- Nổi bật --}}
                            <div style="flex: 1;">
                                <label for="is_featured_filter" class="form-label mb-1">Nổi bật</label>
                                <select name="is_featured" id="is_featured_filter" class="form-select form-select-sm">
                                    <option value="">Tất cả</option>
                                    <option value="1">Có</option>
                                    <option value="0">Không</option>
                                </select>
                            </div>

                            {{-- Nút --}}
                            <div class="d-flex gap-2 align-items-end" style="flex: 1;">
                                <button type="submit" class="btn btn-success btn-sm w-50">Lọc</button>
                                <button type="reset" class="btn btn-danger btn-sm w-50">Xóa</button>
                            </div>
                        </form>


                    </div>
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="table-responsive table-gridjs">
                                <table class="table table-custom align-middle mb-0 table-hover table-centered"
                                    style="border-radius: 0;">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Tiêu đề & Chủ đề</th>
                                            <th>Người tạo</th>
                                            <th>Ngày đăng</th>
                                            <th>Loại</th>
                                            <th>Trạng thái</th>
                                            <th>Lượt xem</th>
                                            <th>Hiển thị</th>
                                            <th>Trang chủ</th>
                                            <th>Nổi bật</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody id="body-news">
                                        @foreach ($news as $article)
                                            <tr id="news-list-{{ $article->id }}">
                                                <!-- Tiêu đề + Chủ đề -->
                                                <td>
                                                    <div class="d-flex align-items-start gap-2">
                                                        {{-- Hình ảnh đại diện --}}
                                                        <img src="{{ asset($article->image) ?? asset('images/no-image.png') }}"
                                                            alt="Ảnh bài viết" class="rounded"
                                                            style="width: 70px; height: 50px; object-fit: cover;">

                                                        {{-- Thông tin tiêu đề + chủ đề --}}
                                                        <div>
                                                            <div class="fw-bold"
                                                                style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; line-height: 1.4em; max-height: 2.8em;">
                                                                {{ $article->title }}
                                                            </div>

                                                            <div class="text-danger small">
                                                                <strong>Chủ đề:</strong>
                                                                {{ $article->topic->name ?? 'Không có chủ đề' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>


                                                <!-- Người tạo -->
                                                <td>{{ $article->creator->name ?? 'Không rõ' }}</td>

                                                <!-- Ngày đăng -->
                                                <td>{{ \Carbon\Carbon::parse($article->created_at)->format('d/m/Y') }}</td>

                                                <!-- Loại -->
                                                <td>
                                                    <span
                                                        class="badge {{ $article->event_type == 'news' ? 'bg-info-subtle text-info' : 'bg-warning-subtle text-warning' }}">
                                                        {{ $article->event_type == 'news' ? 'Tin tức' : 'Sự kiện' }}
                                                    </span>
                                                </td>

                                                <!-- Trạng thái xuất bản -->
                                                <td>
                                                    <span
                                                        class="badge {{ $article->publish_status == 'published' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                                                        {{ $article->publish_status == 'published' ? 'Đã xuất bản' : 'Lưu nháp' }}
                                                    </span>
                                                </td>

                                                <!-- Views -->
                                                <td>{{ $article->views ?? 0 }}</td>

                                                <td>
                                                    <div class="form-check form-checkbox-info">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="isVisible{{ $article->id }}"
                                                            onchange="updateToggle({{ $article->id }}, 'is_visible', this.checked ? 1 : 0)"
                                                            {{ $article->is_visible ? 'checked' : '' }}>
                                                    </div>
                                                </td>

                                                <td>
                                                    <div class="form-check form-checkbox-warning">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="showHome{{ $article->id }}"
                                                            onchange="updateToggle({{ $article->id }}, 'show_on_homepage', this.checked ? 1 : 0)"
                                                            {{ $article->show_on_homepage ? 'checked' : '' }}>
                                                    </div>
                                                </td>

                                                <td>
                                                    <div class="form-check form-checkbox-success">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="isFeatured{{ $article->id }}"
                                                            onchange="updateToggle({{ $article->id }}, 'is_featured', this.checked ? 1 : 0)"
                                                            {{ $article->is_featured ? 'checked' : '' }}>
                                                    </div>
                                                </td>


                                                <!-- Hành động -->
                                                <td>
                                                    <div class="btn-group dropstart">
                                                        <button
                                                            class="btn btn-light btn-sm dropdown-toggle p-1  d-flex justify-content-center align-items-center"
                                                            type="button" data-bs-toggle="dropdown">
                                                            <iconify-icon icon="solar:settings-bold"
                                                                class="fs-5"></iconify-icon>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a href=""
                                                                    class="dropdown-item text-info"><iconify-icon
                                                                        icon="solar:eye-broken"
                                                                        class="me-1"></iconify-icon> Chi tiết</a></li>

                                                            <li><a href="{{ route('admin.news.edit', $article->id) }}"
                                                                    class="dropdown-item text-warning"><iconify-icon
                                                                        icon="solar:pen-2-broken"
                                                                        class="me-1"></iconify-icon> Sửa</a></li>
                                                            <li>
                                                                <form
                                                                    action="{{ route('admin.news.delete', $article->id) }}"
                                                                    method="POST" style="display:inline;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="button"
                                                                        class="dropdown-item text-danger bg-transparent border-0 w-100 text-start btn-delete-news">
                                                                        <iconify-icon
                                                                            icon="solar:trash-bin-minimalistic-2-broken"
                                                                            class="me-1"></iconify-icon>
                                                                        Xóa
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                </table>

                            </div>
                        </div>
                        <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 m-3">
                            <div id="pagination-wrapper" class="flex-grow-1">
                                {{ $news->links('pagination::bootstrap-5') }}
                            </div>
                            <div class="d-flex align-items-center" style="min-width: 160px;">
                                <label for="limit2" class="form-label mb-0 me-2 small">Hiển thị</label>
                                <select name="limit2" id="limit2" class="form-select form-select-sm"
                                    style="width: 100px;">
                                    <option value="10" selected>10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
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
                        </script> © DỰ ÁN TỐT NGHIỆP CAO ĐẲNG FPT THANH HÓA
                        <iconify-icon icon="iconamoon:heart-duotone"
                            class="fs-18 align-middle text-danger"></iconify-icon>
                        <a href="#" class="fw-bold footer-text" target="_blank">NHÓM 4</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
@endsection

@push('scripts')
    <script>
        // Reset search
        $('#searchForm').on('reset', function() {
            setTimeout(() => window.location.reload(), 10);
        });

        // Handle limit change
        $('#limit2').change(function() {
            $('#searchForm #limit').val(this.value);
            $('#searchForm').submit();
        });

        // Hàm Filter
        $('#searchForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: this.action,
                type: 'GET',
                data: $(this).serialize(),
                success: function(response) {
                    $('#body-news').html(renderNews(response.news.data));
                    $('#pagination-wrapper').html(response.pagination);
                },
                error: function(xhr) {
                    console.error('Lỗi khi tìm kiếm:', xhr.responseText);
                }
            });
        });

        // Pagination
        $(document).on('click', '#pagination-wrapper a', function(e) {
            e.preventDefault();
            const url = this.href;
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    $('#body-news').html(renderNews(response.news.data));
                    $('#pagination-wrapper').html(response.pagination);
                },
                error: function(xhr) {
                    console.error('Lỗi phân trang:', xhr.responseText);
                }
            });
        });

        // Delete news
        $(document).on('click', '.btn-delete-news', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            const actionUrl = form.attr('action');
            console.log(form.closest('tr'));
            Swal.fire({
                title: 'Bạn có chắc chắn?',
                text: "Bạn sẽ không thể hoàn tác hành động này!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Vâng, xóa nó!',
                cancelButtonText: 'Không, hủy!',
                confirmButtonClass: 'btn btn-primary w-xs me-2 mt-2',
                cancelButtonClass: 'btn btn-danger w-xs mt-2',
                buttonsStyling: false,
                showCloseButton: true
            }).then(function(result) {
                if (result.isConfirmed) {
                    $.ajax({
                        url: actionUrl,
                        type: 'POST',
                        data: form.serialize(),
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function() {
                            Swal.fire('Đã xóa!', 'Tin tức đã được xóa thành công.', 'success');
                            form.closest('tr').remove();
                        },
                        error: function() {
                            Swal.fire('Lỗi!', 'Không thể xóa tin tức.', 'error');
                        }
                    });
                }
            });
        });

        // Render news
        function renderNews(data) {
            if (data.length === 0) {
                return '<tr><td colspan="10" class="text-center"><div class="alert alert-warning">Không tìm thấy kết quả</div></td></tr>';
            }
            let html = '';
            data.forEach(article => {
                html += `
                    <tr>
                        <!-- Tiêu đề + Chủ đề -->
                        <td>
                            <div class="d-flex align-items-start gap-2">
                                <img src="/${article.image ?? '/images/no-image.png'}"
                                    alt="Ảnh bài viết"
                                    class="rounded"
                                    style="width: 70px; height: 50px; object-fit: cover;">
                                <div>
                                    <div class="fw-bold">${article.title}</div>
                                    <div class="text-danger small">
                                        <strong>Chủ đề:</strong>
                                        ${article.topic?.name ?? 'Không có chủ đề'}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Người tạo -->
                        <td>${article.creator?.name ?? 'Không rõ'}</td>

                        <!-- Ngày đăng -->
                        <td>${moment(article.created_at).format('DD/MM/YYYY')}</td>

                        <!-- Loại -->
                        <td>
                            <span class="badge ${article.event_type === 'news' ? 'bg-info-subtle text-info' : 'bg-warning-subtle text-warning'}">
                                ${article.event_type === 'news' ? 'Tin tức' : 'Sự kiện'}
                            </span>
                        </td>

                        <!-- Trạng thái xuất bản -->
                        <td>
                            <span class="badge ${article.publish_status === 'published' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary'}">
                                ${article.publish_status === 'published' ? 'Đã xuất bản' : 'Lưu nháp'}
                            </span>
                        </td>

                        <!-- Lượt xem -->
                        <td>${article.views ?? 0}</td>

                        <!-- Hiển thị -->
                        <td>
                            <div class="form-check form-checkbox-info">
                                <input type="checkbox" class="form-check-input"
                                    id="isVisible${article.id}"
                                    onchange="updateToggle(${article.id}, 'is_visible', this.checked ? 1 : 0)"
                                    ${article.is_visible ? 'checked' : ''}>
                            </div>
                        </td>

                        <!-- Trang chủ -->
                        <td>
                            <div class="form-check form-checkbox-warning">
                                <input type="checkbox" class="form-check-input"
                                    id="showHome${article.id}"
                                    onchange="updateToggle(${article.id}, 'show_on_homepage', this.checked ? 1 : 0)"
                                    ${article.show_on_homepage ? 'checked' : ''}>
                            </div>
                        </td>

                        <!-- Nổi bật -->
                        <td>
                            <div class="form-check form-checkbox-success">
                                <input type="checkbox" class="form-check-input"
                                    id="isFeatured${article.id}"
                                    onchange="updateToggle(${article.id}, 'is_featured', this.checked ? 1 : 0)"
                                    ${article.is_featured ? 'checked' : ''}>
                            </div>
                        </td>

                        <!-- Hành động -->
                        <td>
                            <div class="btn-group dropstart">
                                <button class="btn btn-light btn-sm dropdown-toggle p-1 d-flex justify-content-center align-items-center" type="button" data-bs-toggle="dropdown">
                                    <iconify-icon icon="solar:settings-bold" class="fs-5"></iconify-icon>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="" class="dropdown-item text-info">
                                            <iconify-icon icon="solar:eye-broken" class="me-1"></iconify-icon> Chi tiết
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/admin/news/edit/${article.id}" class="dropdown-item text-warning">
                                            <iconify-icon icon="solar:pen-2-broken" class="me-1"></iconify-icon> Sửa
                                        </a>
                                    </li>
                                    <li>
                                        <button type="button"
                                                data-id="${article.id}"
                                                class="dropdown-item text-danger bg-transparent border-0 w-100 text-start btn-delete-news">
                                            <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="me-1"></iconify-icon>
                                            Xóa
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                `;
            });

            return html;
        }



        function updateToggle(id, field, value) {
            console.log(id, field, value);

            $.ajax({
                url: '/admin/news/update-toggle',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    field: field,
                    value: value
                },
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        showDataToast('Thay đổi thành công!', 'success');
                    } else {
                        showDataToast('Thay đổi thất bại!', 'danger');
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);

                }
            })
        }


        // Tạo phần tử button ảo có data-toast-* và click nó
        function showDataToast(text, type = 'danger') {
            const btn = document.createElement('button');
            btn.setAttribute('data-toast', '');
            btn.setAttribute('data-toast-text', text);
            btn.setAttribute('data-toast-gravity', 'top');
            btn.setAttribute('data-toast-position', 'right');
            btn.setAttribute('data-toast-duration', '5000');
            btn.setAttribute('data-toast-close', 'close');
            btn.setAttribute('data-toast-className', type);
            btn.style.display = 'none';
            document.body.appendChild(btn);

            // Gắn lại sự kiện bằng tay (copy logic từ ToastNotification)
            btn.addEventListener('click', function() {
                Toastify({
                    newWindow: true,
                    text: text,
                    gravity: "top",
                    position: "right",
                    className: "bg-" + type,
                    stopOnFocus: true,
                    offset: {
                        x: 50,
                        y: 10
                    },
                    duration: 3000,
                    close: true
                }).showToast();
            });

            btn.click();
            setTimeout(() => btn.remove(), 100);
        }
    </script>
@endpush
