@extends('admin.admin')

@section('title', 'Quản lý bộ dữ liệu huấn luyện chatbot')
@section('description', 'Quản lý chi tiết bộ dữ liệu training cho chatbot')

@section('content')
    <div class="page-content">
        <div class="container-xxl">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Huấn luyện chatbot</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Chi tiết</li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between align-items-center mb-1">
                <div>
                    <h4 class="mb-1">Chi tiết bộ training ( {{ $training->name }} )</h4>
                    <p class="text-muted mb-0">Quản lý chi tiết bộ dữ liệu training cho chatbot</p>
                </div>
                <button class="btn btn-primary">
                    <i class="bx bx-download me-1"></i> Xuất dữ liệu
                </button>
            </div>

            <div class="card">
                <div class="card-body p-0">
                    <ul class="nav nav-tabs nav-justified" role="tablist">
                        <li class="nav-item pt-2">
                            <a class="nav-link active" data-bs-toggle="tab" href="#qa-data" role="tab">
                                <span class="d-inline-block d-md-none"><i class="bx bx-chat"></i></span>
                                <span class="d-none d-md-inline-block">
                                    <i class="bx bx-chat me-1"></i> Dữ liệu huấn luyện (Q&A)
                                </span>
                            </a>
                        </li>
                        <li class="nav-item pt-2">
                            <a class="nav-link" data-bs-toggle="tab" href="#doc-data" role="tab">
                                <span class="d-inline-block d-md-none"><i class="bx bx-file"></i></span>
                                <span class="d-none d-md-inline-block">
                                    <i class="bx bx-file me-1"></i> Tài liệu huấn luyện
                                </span>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content p-3">
                        <!-- Tab Q&A -->
                        <div class="tab-pane active" id="qa-data" role="tabpanel">
                            <div class="row align-items-center mb-1">
                                <div class="col-md-6 col-12 mb-2 mb-md-0">
                                    <h5 class="mb-0">Danh sách câu hỏi và trả lời</h5>
                                </div>

                                <div class="col-md-6 col-12 d-flex justify-content-md-end align-items-center gap-2">
                                    <!-- Ô tìm kiếm -->
                                    <form action="{{ route('admin.bot.training.detail.filter', $training->id) }}"
                                        method="POST" class="input-group" id="searchForm" style="max-width: 400px;">
                                        @csrf
                                        <input type="hidden" name="limit" id="limit" value="10">

                                        <button type="submit" class="input-group-text bg-white">
                                            <i class="fa fa-search text-muted"></i>
                                        </button>
                                        <input type="text" name="keyword" id="keywordInput" class="form-control"
                                            placeholder="Câu hỏi / Từ khóa...">
                                        <button type="button" id="resetBtn" class="btn btn-outline-secondary">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </form>

                                    <!-- Nút thêm mới -->
                                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addQAModal">
                                        <i class="bx bx-plus me-1"></i> Thêm mới
                                    </button>
                                </div>
                            </div>


                            <div class="table-responsive">
                                <table class="table table-hover table-striped align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="50">#</th>
                                            <th>Câu hỏi</th>
                                            <th>Trả lời</th>
                                            <th width="150">Từ khóa</th>
                                            <th width="120">Ngữ cảnh</th>
                                            <th width="120" class="text-center">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody id="body-data">
                                        @forelse($data as $index => $item)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td class="fw-semibold">{{ $item->question }}</td>
                                                <td>{{ $item->answer }}</td>
                                                <td>
                                                    @if ($item->keywords)
                                                        @foreach (explode(',', $item->keywords) as $keyword)
                                                            <span class="badge bg-info me-1">{{ trim($keyword) }}</span>
                                                        @endforeach
                                                    @endif
                                                </td>
                                                <td>{{ $item->context ?? '-' }}</td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn btn-outline-warning" title="Sửa">
                                                            <i class="bx bx-edit"></i>
                                                        </button>
                                                        <button class="btn btn-outline-danger btnDeleteQA"
                                                            data-id="{{ $item->id }}" title="Xóa">
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted">Chưa có dữ liệu Q&A</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                                <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 m-3">
                                    <div id="pagination-wrapper-data" class="flex-grow-1">
                                        {{ $data->links('pagination::bootstrap-5') }}
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

                        <!-- Tab Tài liệu -->
                        <div class="tab-pane" id="doc-data" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Danh sách tài liệu huấn luyện</h5>

                                <div class="col-md-6 col-12 d-flex justify-content-md-end align-items-center gap-2">
                                    <!-- Ô tìm kiếm -->
                                    <form action="{{ route('admin.bot.training.detail.document.filter', $training->id) }}"
                                        method="POST" class="input-group" id="searchForm-document"
                                        style="max-width: 400px;">
                                        @csrf
                                        <input type="hidden" name="limit" id="limit-document" value="10">

                                        <button type="submit" class="input-group-text bg-white">
                                            <i class="fa fa-search text-muted"></i>
                                        </button>
                                        <input type="text" name="keyword" id="keywordInput-document"
                                            class="form-control" placeholder="Tiêu đề ...">
                                        <button type="button" id="resetBtn-document" class="btn btn-outline-secondary">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </form>

                                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addDocModal">
                                        <i class="bx bx-plus me-1"></i> Thêm mới
                                    </button>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover table-striped align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="50">#</th>
                                            <th>Tiêu đề</th>
                                            <th>Đường dẫn</th>
                                            <th width="120">Ngày tạo</th>
                                            <th width="120" class="text-center">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody id="body-data-document">
                                        @forelse($documents as $index => $doc)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td class="fw-semibold">{{ $doc->title }}</td>
                                                <td>
                                                    <a href="{{ asset($doc->file_path) }}"
                                                        class="text-primary text-decoration-none" target="_blank">
                                                        <i class="bx bx-file me-1"></i> {{ basename($doc->file_path) }}
                                                    </a>
                                                </td>
                                                <td>{{ $doc->created_at->format('d/m/Y') }}</td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn btn-outline-warning" title="Sửa">
                                                            <i class="bx bx-edit"></i>
                                                        </button>
                                                        <button class="btn btn-outline-danger btnDeleteDoc"
                                                            data-id="{{ $doc->id }}" title="Xóa">
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">Chưa có tài liệu huấn
                                                    luyện</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>


                                <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 m-3">
                                    <div id="pagination-wrapper-document" class="flex-grow-1">
                                        {{ $documents->links('pagination::bootstrap-5') }}
                                    </div>
                                    <div class="d-flex align-items-center" style="min-width: 160px;">
                                        <label for="limit2-document" class="form-label mb-0 me-2 small">Hiển thị</label>
                                        <select name="limit2-document" id="limit2" class="form-select form-select-sm"
                                            style="width: 100px;">
                                            <option value="6" selected>6</option>
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
            </div>
        </div>
    </div>

    <!-- Modal Thêm Q&A -->
    <div class="modal fade" id="addQAModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm câu hỏi và trả lời mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Câu hỏi</label>
                            <input type="text" class="form-control" placeholder="Nhập câu hỏi">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Câu trả lời</label>
                            <textarea class="form-control" rows="3" placeholder="Nhập câu trả lời"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Từ khóa</label>
                            <input type="text" class="form-control"
                                placeholder="Nhập từ khóa, phân cách bằng dấu phẩy">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ngữ cảnh</label>
                            <select class="form-select">
                                <option selected>Chọn ngữ cảnh</option>
                                <option value="Giới thiệu">Giới thiệu</option>
                                <option value="Hướng dẫn">Hướng dẫn</option>
                                <option value="Hỗ trợ">Hỗ trợ</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Thêm Tài liệu -->
    <div class="modal fade" id="addDocModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('admin.bot.training.storeDocument') }}" method="post" enctype="multipart/form-data"
                id="form-document" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Thêm tài liệu mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div>
                        <input type="hidden" name="training_set_id" value="{{ $training->id }}">
                        <div class="mb-3">
                            <label class="form-label">Tiêu đề tài liệu</label>
                            <input type="text" name="title" class="form-control"
                                placeholder="Nhập tiêu đề tài liệu">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tải lên tài liệu</label>
                            <input type="file" name="document" class="form-control">
                            <div class="form-text">Hỗ trợ định dạng: PDF, DOCX, TXT (tối đa 10MB)</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Tải lên</button>
                </div>
            </form>
        </div>
    </div>

    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-center">
                    <script>
                        document.write(new Date().getFullYear())
                    </script> &copy; DỰ ÁN TỐT NGHIỆP CAO ĐẲNG FPT POLYTECHNIC THANH HÓA
                    <iconify-icon icon="iconamoon:heart-duotone" class="fs-18 align-middle text-danger"></iconify-icon>
                    <a href="#" class="fw-bold footer-text" target="_blank">Tiger Code</a>
                </div>
            </div>
        </div>
    </footer>
@endsection



@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Khởi tạo tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Xử lý chuyển tab
            const triggerTabList = document.querySelectorAll('.nav-tabs a')
            triggerTabList.forEach(triggerEl => {
                triggerEl.addEventListener('click', function(e) {
                    e.preventDefault()
                    const tab = new bootstrap.Tab(this)
                    tab.show()
                })
            });
        });

        //Tìm kiếm QA
        $('#resetBtn').on('click', function() {
            $('#keywordInput').val('');
            $('#searchForm').submit();
        });

        $('#limit2').change(function() {
            $('#searchForm #limit').val(this.value);
            $('#searchForm').submit();
        });
        //Form submit
        $('#searchForm').on('submit', function(e) {
            e.preventDefault();
            $('#body-data').css({
                'opacity': '0.5'
            });
            const url = $("#searchForm").attr('action');
            $.ajax({
                url: url,
                type: 'GET',
                data: $(this).serialize(),
                success: function(response) {
                    //console.log('Search response:', response);
                    $('#body-data').html(renderTraining(response.datas.data, 'data'));
                    $('#pagination-wrapper-data').html(response.pagination);
                    $('#body-data').css({
                        'opacity': '1'
                    });
                },
                error: function(xhr) {
                    console.error('Lỗi khi tìm kiếm:', xhr.responseText);
                    showDataToast('Có lỗi xảy ra khi tìm kiếm!', 'danger');
                }
            });
        });


        //Tìm kiếm tài liệu
        $('#resetBtn-document').on('click', function() {
            $('#keywordInput-document').val('');
            $('#searchForm-document').submit();
        });

        $('#limit2-document').change(function() {
            $('#searchForm-document #limit-document').val(this.value);
            $('#searchForm-document').submit();
        });
        //Form submit document
        $(document).on('submit', '#searchForm-document', function(e) {
            e.preventDefault();
            $('#body-data-document').css({
                'opacity': '0.5'
            });
            const url = $("#searchForm-document").attr('action');
            $.ajax({
                url: url,
                type: 'GET',
                data: $(this).serialize(),
                success: function(response) {
                    //console.log('Search response:', response);
                    $('#body-data-document').html(renderTraining(response.datas.data, 'document'));
                    $('#pagination-wrapper-data-document').html(response.pagination);
                    $('#body-data-document').css({
                        'opacity': '1'
                    });
                },
                error: function(xhr) {
                    console.error('Lỗi khi tìm kiếm:', xhr.responseText);
                    showDataToast('Có lỗi xảy ra khi tìm kiếm!', 'danger');
                }
            });
        });



        // Lưu trạng thái page
        var page_data = 1;
        var page_document = 1;

        // Hàm xử lý phân trang chung
        function handlePagination(wrapperSelector, bodySelector, pageVarName) {
            $(document).on('click', `${wrapperSelector} a`, function(e) {
                e.preventDefault();

                const url = this.href;
                const page = new URL(url).searchParams.get("page");

                // Gán vào biến toàn cục tương ứng
                if (pageVarName === 'data') page_data = page;
                if (pageVarName === 'document') page_document = page;

                $(bodySelector).css({
                    'opacity': '0.5'
                });

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        let datas, pagination;

                        if (pageVarName === 'data') {
                            datas = response.data.data; // lấy mảng QnA
                            pagination = response['pagination-data'];
                        } else if (pageVarName === 'document') {
                            datas = response.document.data; // lấy mảng Document
                            pagination = response['pagination-document'];
                        }

                        $(bodySelector).html(renderTraining(datas, pageVarName));
                        $(wrapperSelector).html(pagination);

                        $(bodySelector).css({
                            'opacity': '1'
                        });
                    },
                    error: function(xhr) {
                        console.error('Lỗi phân trang:', xhr.responseText);
                        showDataToast('Có lỗi xảy ra khi phân trang!', 'danger');
                    }
                });
            });
        }


        // Gọi hàm cho từng phân trang
        handlePagination('#pagination-wrapper-data', '#body-data', 'data');
        handlePagination('#pagination-wrapper-document', '#body-data-document', 'document');

        // Hàm render
        function renderTraining(datas, pageVarName) {

            //console.log(datas);
            let html = '';

            if (pageVarName === 'data') {
                // Render bảng Q&A
                datas.forEach((item, index) => {
                    html += `
                <tr>
                    <td>${index + 1}</td>
                    <td class="fw-semibold">${item.question}</td>
                    <td>${item.answer}</td>
                    <td>
                        ${(
                            (item.keywords && Array.isArray(item.keywords))
                                ? item.keywords
                                : (item.keywords ? item.keywords.split(',') : [])
                        ).map(kw => `<span class="badge bg-info me-1">${kw.trim()}</span>`).join('')}
                    </td>
                    <td>${item.context || ''}</td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-warning" title="Sửa" data-id="${item.id}">
                                <i class="bx bx-edit"></i>
                            </button>
                            <button class="btn btn-outline-danger" title="Xóa" data-id="${item.id}">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
                });
            } else if (pageVarName === 'document') {
                // Render bảng Document
                datas.forEach((item, index) => {
                    html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td class="fw-semibold">${item.title}</td>
                            <td>
                                <a href="${item.file_path}"
                                class="text-primary text-decoration-none" target="_blank">
                                    <i class="bx bx-file me-1"></i> ${item.filename || item.file_path.split('/').pop()}
                                </a>
                            </td>
                            <td>${formatDate(item.created_at)}</td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-warning btnEditDoc"
                                            data-id="${item.id}" title="Sửa">
                                        <i class="bx bx-edit"></i>
                                    </button>
                                    <button class="btn btn-outline-danger btnDeleteDoc"
                                            data-id="${item.id}" title="Xóa">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;

                });
            }

            return html || `<tr><td colspan="6" class="text-center text-muted">Không có dữ liệu</td></tr>`;
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0'); // tháng bắt đầu từ 0
            const year = date.getFullYear();
            return `${day}/${month}/${year}`;
        }


        // Hàm xử lý khi submit form thêm tài liệu
        $('#form-document').on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    //console.log('Thêm tài liệu thành công:', response);
                    $('#addDocModal').modal('hide');
                    showDataToast('Tài liệu đã được thêm thành công!', 'success');
                    // Tải lại danh sách tài liệu
                    $('#searchForm-document').submit();
                    // Reset form
                    $('#form-document')[0].reset();
                },
                error: function(xhr) {
                    console.error('Lỗi khi thêm tài liệu:', xhr.responseText);
                    showDataToast('Có lỗi xảy ra khi thêm tài liệu!', 'danger');
                }
            });
        });

        // Show toast log
        function showDataToast(text, type = 'danger', icon = '') {

            const icons = {
                success: '<i class="fa fa-check-circle"></i>',
                danger: '<i class="fa fa-times-circle"></i>',
                error: '<i class="fa fa-times-circle"></i>' // alias cho danger
            };
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
                    text: `${icons[type] || ''} ${text}`,
                    gravity: "top",
                    position: "right",
                    className: "bg-" + type,
                    stopOnFocus: true,
                    offset: {
                        x: 50,
                        y: 10
                    },
                    duration: 3000,
                    close: true,
                    escapeMarkup: false // rất quan trọng để Toastify không escape HTML
                }).showToast();

            });

            btn.click();
            setTimeout(() => btn.remove(), 100);
        }
    </script>
@endpush
