@extends('admin.admin')

@section('title', 'Thùng rác Tin tức')
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
                    <li class="breadcrumb-item"><a href="{{ route('admin.news.index') }}">Quản lý Tin tức</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Thùng rác</li>
                </ol>
            </nav>

            <!-- Title and Actions -->
            <div class="d-flex justify-content-between align-items-center mb-1 mt-2">
                <h4 class="card-title mb-1">Danh sách tin tức bị xóa <span class="text-danger">Tổng: ({{ $totalDeleted }} tin tức)</span></h4>
                <div>
                    <a href="{{ route('admin.news.index') }}" class="btn btn-outline-primary btn-sm">
                        <iconify-icon icon="solar:arrow-left-broken" class="fs-20"></iconify-icon> Quay lại Quản lý Tin tức
                    </a>
                </div>
            </div>

            <!-- Trash News List -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card p-2">
                        <form action="{{ route('admin.news.trash.filter') }}"
                            class="d-flex flex-wrap flex-xl-nowrap gap-2 align-items-end w-100" id="searchForm">
                            <input type="hidden" name="limit" id="limit" value="10">

                            {{-- Từ khóa - dài nhất --}}
                            <div class="flex-grow-1" style="min-width: 160px; flex: 0 0 12%;">
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

                            {{-- Ngày xóa từ --}}
                            <div style="min-width: 160px; flex: 0 0 14%;">
                                <label for="deleted_from" class="form-label mb-1">Xóa từ ngày</label>
                                <input type="date" name="deleted_from" id="deleted_from"
                                    class="form-control form-control-sm" value="{{ request('deleted_from') }}">
                            </div>

                            {{-- Ngày xóa đến --}}
                            <div style="min-width: 160px; flex: 0 0 14%;">
                                <label for="deleted_to" class="form-label mb-1">Đến ngày</label>
                                <input type="date" name="deleted_to" id="deleted_to" class="form-control form-control-sm"
                                    value="{{ request('deleted_to') }}">
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
                                            <th>Ngày xóa</th>
                                            <th>Loại</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody id="body-news">
                                        @foreach ($news as $article)
                                            <tr id="news-list-{{ $article->id }}">
                                                <!-- Tiêu đề + Chủ đề -->
                                                <td>
                                                    <div class="d-flex align-items-start gap-2">
                                                        <img src="{{ asset($article->image) ?? asset('images/no-image.png') }}"
                                                            alt="Ảnh bài viết" class="rounded"
                                                            style="width: 70px; height: 50px; object-fit: cover;">
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

                                                <!-- Ngày xóa -->
                                                <td>{{ \Carbon\Carbon::parse($article->deleted_at)->format('d/m/Y') }}</td>

                                                <!-- Loại -->
                                                <td>
                                                    <span
                                                        class="badge {{ $article->event_type == 'news' ? 'bg-info-subtle text-info' : 'bg-warning-subtle text-warning' }}">
                                                        {{ $article->event_type == 'news' ? 'Tin tức' : 'Sự kiện' }}
                                                    </span>
                                                </td>

                                                <!-- Hành động -->
                                                <td>
                                                    <div class="btn-group dropstart">
                                                        <button
                                                            class="btn btn-light btn-sm dropdown-toggle p-1 d-flex justify-content-center align-items-center"
                                                            type="button" data-bs-toggle="dropdown">
                                                            <iconify-icon icon="solar:settings-bold"
                                                                class="fs-5"></iconify-icon>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <form
                                                                    action="{{ route('admin.news.restore', $article->id) }}"
                                                                    method="POST" style="display:inline;">
                                                                    @csrf
                                                                    <button type="submit"
                                                                        class="dropdown-item text-success bg-transparent border-0 w-100 text-start btn-restore-news">
                                                                        <iconify-icon icon="solar:undo-left-broken"
                                                                            class="me-1"></iconify-icon>
                                                                        Khôi phục
                                                                    </button>
                                                                </form>
                                                            </li>
                                                            <li>
                                                                <form
                                                                    action="{{ route('admin.news.forceDelete', $article->id) }}"
                                                                    method="POST" style="display:inline;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="button"
                                                                        class="dropdown-item text-danger bg-transparent border-0 w-100 text-start btn-force-delete-news">
                                                                        <iconify-icon
                                                                            icon="solar:trash-bin-minimalistic-2-broken"
                                                                            class="me-1"></iconify-icon>
                                                                        Xóa vĩnh viễn
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
                        </script> © DỰ ÁN TỐT NGHIỆP CAO ĐẲNG FPT POLYTECHNIC  THANH HÓA
                        <iconify-icon icon="iconamoon:heart-duotone"
                            class="fs-18 align-middle text-danger"></iconify-icon>
                        <a href="#" class="fw-bold footer-text" target="_blank">Tiger Code</a>
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

        // Restore news
        $(document).on('click', '.btn-restore-news', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            const actionUrl = form.attr('action');
            Swal.fire({
                title: 'Bạn có chắc chắn?',
                text: "Bạn muốn khôi phục bài viết này?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Vâng, khôi phục!',
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
                            Swal.fire('Đã khôi phục!', 'Tin tức đã được khôi phục thành công.',
                                'success');
                            form.closest('tr').remove();
                        },
                        error: function() {
                            Swal.fire('Lỗi!', 'Không thể khôi phục tin tức.', 'error');
                        }
                    });
                }
            });
        });

        // Force delete news
        $(document).on('click', '.btn-force-delete-news', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            const actionUrl = form.attr('action');
            Swal.fire({
                title: 'Bạn có chắc chắn?',
                text: "Bạn sẽ không thể hoàn tác hành động này!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Vâng, xóa vĩnh viễn!',
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
                            Swal.fire('Đã xóa!', 'Tin tức đã được xóa vĩnh viễn.', 'success');
                            form.closest('tr').remove();
                        },
                        error: function() {
                            Swal.fire('Lỗi!', 'Không thể xóa vĩnh viễn tin tức.', 'error');
                        }
                    });
                }
            });
        });

        // Render news
        function renderNews(data) {
            if (data.length === 0) {
                return '<tr><td colspan="5" class="text-center"><div class="alert alert-warning">Không tìm thấy kết quả</div></td></tr>';
            }
            let html = '';
            data.forEach(article => {
                html += `
                    <tr>
                        <!-- Tiêu đề + Chủ đề -->
                        <td>
                            <div class="d-flex align-items-start gap-2">
                                <img src="${article.image ?? '/images/no-image.png'}"
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

                        <!-- Ngày xóa -->
                        <td>${moment(article.deleted_at).format('DD/MM/YYYY')}</td>

                        <!-- Loại -->
                        <td>
                            <span class="badge ${article.event_type === 'news' ? 'bg-info-subtle text-info' : 'bg-warning-subtle text-warning'}">
                                ${article.event_type === 'news' ? 'Tin tức' : 'Sự kiện'}
                            </span>
                        </td>

                        <!-- Hành động -->
                        <td>
                            <div class="btn-group dropstart">
                                <button class="btn btn-light btn-sm dropdown-toggle p-1 d-flex justify-content-center align-items-center" type="button" data-bs-toggle="dropdown">
                                    <iconify-icon icon="solar:settings-bold" class="fs-5"></iconify-icon>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <form action="/admin/news/restore/${article.id}" method="POST">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <button type="submit" class="dropdown-item text-success bg-transparent border-0 w-100 text-start btn-restore-news">
                                                <iconify-icon icon="solar:undo-left-broken" class="me-1"></iconify-icon> Khôi phục
                                            </button>
                                        </form>
                                    </li>
                                    <li>
                                        <form action="/admin/news/force-delete/${article.id}" method="POST">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="button" class="dropdown-item text-danger bg-transparent border-0 w-100 text-start btn-force-delete-news">
                                                <iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="me-1"></iconify-icon> Xóa vĩnh viễn
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                `;
            });

            return html;
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
