@extends('admin.admin')

@section('title', 'Thùng rác Chủ đề')
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
                    <li class="breadcrumb-item"><a href="{{ route('admin.topics.index') }}">Quản lý Chủ đề</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Thùng rác</li>
                </ol>
            </nav>

            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 mt-2 gap-2">
                <!-- Tiêu đề -->
                <h4 class="card-title mb-0">Thùng rác Chủ đề</h4>

                <!-- Nhóm nút chức năng -->
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <!-- Form tìm kiếm + Reset -->
                    <form action="{{ route('admin.topics.trash.filter') }}" method="POST" class="d-flex align-items-center gap-2"
                        id="searchForm">
                        @csrf
                        <input type="hidden" name="limit" id="limit" value="10">
                        <div class="position-relative">
                            <input type="search" name="keyword" class="form-control form-control-sm pe-5"
                                placeholder="Tìm kiếm tên chủ đề..." autocomplete="off" value="{{ request('keyword') }}">
                            <button type="submit"
                                class="btn position-absolute top-50 end-0 translate-middle-y me-2 p-0 border-0 bg-transparent text-muted"
                                style="z-index: 5;">
                                <iconify-icon icon="solar:magnifer-linear" class="fs-18"></iconify-icon>
                            </button>
                        </div>
                        <a href="{{ route('admin.topics.trash') }}"
                            class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
                            <iconify-icon icon="material-symbols:refresh-rounded" class="fs-20"></iconify-icon>
                            <span>Xóa lọc</span>
                        </a>
                    </form>

                    <a href="{{ route('admin.topics.index') }}"
                        class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1">
                        <iconify-icon icon="solar:arrow-left-broken" class="fs-20"></iconify-icon>
                        <span>Quay lại Quản lý Chủ đề</span>
                    </a>
                </div>
            </div>

            <!-- Trash Topics List -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="table-responsive table-gridjs">
                                <table class="table table-custom align-middle mb-0 table-hover table-centered"
                                    style="border-radius: 0;">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Tên Chủ đề</th>
                                            <th>Mô tả</th>
                                            <th>Người tạo</th>
                                            <th>Ngày xóa</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody id="body-topics">
                                        @foreach ($topics as $topic)
                                            <tr id="topic-list-{{ $topic->id }}">
                                                <td>
                                                    <div class="fw-bold"
                                                        style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; line-height: 1.4em; max-height: 2.8em;">
                                                        {{ $topic->name }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div
                                                        style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; line-height: 1.4em; max-height: 2.8em;">
                                                        {{ $topic->description ?? 'Không có mô tả' }}
                                                    </div>
                                                </td>
                                                <td>{{ $topic->creator->name ?? 'Không rõ' }}</td>
                                                <td>{{ \Carbon\Carbon::parse($topic->deleted_at)->format('d/m/Y') }}</td>
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
                                                                <form action="{{ route('admin.topics.restore', $topic->id) }}"
                                                                    method="POST" style="display:inline;">
                                                                    @csrf
                                                                    <button type="submit"
                                                                        class="dropdown-item text-success bg-transparent border-0 w-100 text-start btn-restore-topic">
                                                                        <iconify-icon
                                                                            icon="solar:undo-left-broken"
                                                                            class="me-1"></iconify-icon>
                                                                        Khôi phục
                                                                    </button>
                                                                </form>
                                                            </li>
                                                            <li>
                                                                <form
                                                                    action="{{ route('admin.topics.forceDelete', $topic->id) }}"
                                                                    method="POST" style="display:inline;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="button"
                                                                        class="dropdown-item text-danger bg-transparent border-0 w-100 text-start btn-force-delete-topic">
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
                                {{ $topics->links('pagination::bootstrap-5') }}
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
                    $('#body-topics').html(renderTopics(response.topics.data));
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
                    $('#body-topics').html(renderTopics(response.topics.data));
                    $('#pagination-wrapper').html(response.pagination);
                },
                error: function(xhr) {
                    console.error('Lỗi phân trang:', xhr.responseText);
                }
            });
        });

        // Restore topic
        $(document).on('click', '.btn-restore-topic', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            const actionUrl = form.attr('action');
            Swal.fire({
                title: 'Bạn có chắc chắn?',
                text: "Bạn muốn khôi phục chủ đề này?",
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
                            Swal.fire('Đã khôi phục!', 'Chủ đề đã được khôi phục thành công.', 'success');
                            form.closest('tr').remove();
                        },
                        error: function() {
                            Swal.fire('Lỗi!', 'Không thể khôi phục chủ đề.', 'error');
                        }
                    });
                }
            });
        });

        // Force delete topic
        $(document).on('click', '.btn-force-delete-topic', function(e) {
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
                            Swal.fire('Đã xóa!', 'Chủ đề đã được xóa vĩnh viễn.', 'success');
                            form.closest('tr').remove();
                        },
                        error: function() {
                            Swal.fire('Lỗi!', 'Không thể xóa vĩnh viễn chủ đề.', 'error');
                        }
                    });
                }
            });
        });

        // Render topics
        function renderTopics(data) {
            if (data.length === 0) {
                return '<tr><td colspan="5" class="text-center"><div class="alert alert-warning">Không tìm thấy kết quả</div></td></tr>';
            }
            let html = '';
            data.forEach(topic => {
                html += `
                    <tr>
                        <!-- Tên Chủ đề -->
                        <td>
                            <div class="fw-bold">${topic.name}</div>
                        </td>

                        <!-- Mô tả -->
                        <td>
                            <div style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; line-height: 1.4em; max-height: 2.8em;">
                                ${topic.description ?? 'Không có mô tả'}
                            </div>
                        </td>

                        <!-- Người tạo -->
                        <td>${topic.creator?.name ?? 'Không rõ'}</td>

                        <!-- Ngày xóa -->
                        <td>${moment(topic.deleted_at).format('DD/MM/YYYY')}</td>

                        <!-- Hành động -->
                        <td>
                            <div class="btn-group dropstart">
                                <button class="btn btn-light btn-sm dropdown-toggle p-1 d-flex justify-content-center align-items-center" type="button" data-bs-toggle="dropdown">
                                    <iconify-icon icon="solar:settings-bold" class="fs-5"></iconify-icon>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <form action="/admin/topics/restore/${topic.id}" method="POST">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <button type="submit" class="dropdown-item text-success bg-transparent border-0 w-100 text-start btn-restore-topic">
                                                <iconify-icon icon="solar:undo-left-broken" class="me-1"></iconify-icon> Khôi phục
                                            </button>
                                        </form>
                                    </li>
                                    <li>
                                        <form action="/admin/topics/forceDelete/${topic.id}" method="POST">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="button" class="dropdown-item text-danger bg-transparent border-0 w-100 text-start btn-force-delete-topic">
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
