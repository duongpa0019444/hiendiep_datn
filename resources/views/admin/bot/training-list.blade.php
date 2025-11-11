@extends('admin.admin')

@section('title', 'Quản lý danh sách thông báo học phí')
@section('description', 'Danh sách thông báo biến động học phí')

@section('content')
    <div class="page-content">

        <div class="container-xxl">

            <nav aria-label="breadcrumb p-0">
                <ol class="breadcrumb py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Huấn luyện chatbot</li>
                </ol>
            </nav>
            <div class="row mb-2 align-items-betwwen">
                <div class="col-md-8">
                    <h1 class="h4 fw-bold">Quản lý Bộ Training Bot</h1>
                    <p class="text-muted mb-0">Quản lý và xem các bộ dữ liệu training cho chatbot</p>
                </div>
                <div class="col-md-4 text-md-end mt-2 mt-md-0">
                    <button type="button" class="btn btn-primary fs-6" id="btnAddTraining" data-bs-toggle="modal"
                        data-bs-target="#addTrainingModal">
                        <i class="fa fa-plus me-1"></i> Thêm mới
                    </button>
                </div>
            </div>

            <!-- Thống kê nhanh -->
            <div class="row">
                <div class="col-md-3">
                    <div class="card stats-card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon bg-soft-primary text-primary me-3 rounded p-2">
                                    <i class="fa fa-database fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-0">Tổng số bộ training</h6>
                                    <h4 class="mb-0">{{ $statistics->tong_bo_training }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stats-card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon bg-soft-success text-success me-3 rounded p-2">
                                    <i class="fa fa-file-alt fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-0">Tổng tài liệu</h6>
                                    <h4 class="mb-0">{{ $statistics->tong_tai_lieu }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stats-card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon bg-soft-info text-info me-3 rounded p-2">
                                    <i class="fa fa-question-circle fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-0">Tổng câu hỏi</h6>
                                    <h4 class="mb-0">{{ $statistics->tong_cau_hoi }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stats-card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon bg-soft-warning text-warning me-3 rounded p-2">
                                    <i class="fa fa-clock fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-0">Cập nhật gần nhất</h6>
                                    <h4 class="mb-0">{{ $statistics->cap_nhat_gan_nhat->diffForHumans() }}</h4>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bộ lọc và tìm kiếm -->
            <div class="filter-section mb-1 card shadow-sm p-2">
                <form action="{{ route('admin.bot.training.filter') }}" method="post" id="searchForm"
                    class="row align-items-center">
                    <input type="hidden" name="filter" id="filterInput" value="all">
                    <input type="hidden" name="limit" id="limit" value="6">
                    <!-- Nút lọc -->
                    <div class="col-md-8 mb-2 mb-md-0" style="overflow-x: auto;">
                        <div class="btn-group" role="group" aria-label="Bộ lọc">
                            <button type="button" class="btn btn-outline-primary active" data-value="all">Tất cả</button>
                            <button type="button" class="btn btn-outline-primary" data-value="newest">Mới nhất</button>
                            <button type="button" class="btn btn-outline-primary" data-value="oldest">Cũ nhất</button>
                            <button type="button" class="btn btn-outline-primary" data-value="most_documents">Nhiều tài
                                liệu nhất</button>
                            <button type="button" class="btn btn-outline-primary" data-value="most_questions">Nhiều câu hỏi
                                nhất</button>
                        </div>
                    </div>

                    <!-- Ô tìm kiếm -->
                    <div class="col-md-4">
                        <div class="input-group">
                            <button type="submit" class="input-group-text bg-white">
                                <i class="fa fa-search text-muted"></i>
                            </button>
                            <input type="text" name="keyword" id="keywordInput" class="form-control"
                                placeholder="Tìm kiếm bộ training...">
                            <button type="button" id="resetBtn" class="btn btn-outline-secondary">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>

                </form>


            </div>


            <!-- Danh sách bộ training -->
            <div class="card shadow-sm p-2">
                <div class="row" id="body-data-trainingbot">
                    @foreach ($trainingSets as $set)
                        <div class="col-md-6 col-lg-4 mb-2" id="training-{{ $set->id }}">
                            <div class="shadow-sm border h-100 rounded-3">
                                <!-- Header -->
                                <div
                                    class="card-header bg-white border-0 d-flex justify-content-between align-items-center rounded-3 px-3 py-2">
                                    <h5 class="card-title mb-0">{{ $set->name }}</h5>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light rounded-circle" type="button"
                                            data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item btn-edit" href="#"
                                                    data-id="{{ $set->id }}" data-name="{{ $set->name }}"
                                                    data-description="{{ $set->description }}">
                                                    <i class="fas fa-edit me-2"></i>Chỉnh sửa
                                                </a>
                                            </li>
                                            <li><a class="dropdown-item" href="{{ route('admin.bot.training.detail', $set->id) }}"><i
                                                        class="fas fa-eye me-2"></i>Xem
                                                    chi
                                                    tiết</a></li>
                                            <li>
                                                <a class="dropdown-item text-danger btnDeleteTraining" href="#"
                                                    data-id="{{ $set->id }}">
                                                    <i class="fas fa-trash-alt me-2"></i> Xóa
                                                </a>
                                            </li>

                                        </ul>
                                    </div>
                                </div>
                                <!-- Body -->
                                <div class="card-body  px-3 py-2">
                                    <p class="text-muted small mb-1">{{ $set->description }}</p>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="badge bg-light text-dark">
                                            <i class="fas fa-file-alt me-1 text-primary"></i> {{ $set->documents_count }}
                                            tài
                                            liệu
                                        </span>
                                        <span class="badge bg-light text-dark">
                                            <i class="fas fa-question-circle me-1 text-success"></i>
                                            {{ $set->questions_count }}
                                            câu hỏi
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted"><i class="far fa-clock me-1"></i> Cập nhật:
                                            {{ $set->updated_at->format('d/m/Y') }}</small>
                                        <a href="{{ route('admin.bot.training.detail', $set->id) }}" class="btn btn-sm btn-outline-primary">Xem chi tiết</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 m-3">
                    <div id="pagination-wrapper" class="flex-grow-1">
                        {{ $trainingSets->links('pagination::bootstrap-5') }}
                    </div>
                    <div class="d-flex align-items-center" style="min-width: 160px;">
                        <label for="limit2" class="form-label mb-0 me-2 small">Hiển thị</label>
                        <select name="limit2" id="limit2" class="form-select form-select-sm" style="width: 100px;">
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

    <!-- Modal Thêm/ Chỉnh sửa -->
    <div class="modal fade" id="postEditTrainingModel" tabindex="-1" aria-labelledby="editTrainingLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="postEditTrainingForm" action="" method="post">
                    @csrf
                    <input type="hidden" name="_method">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editTrainingLabel">Chỉnh sửa bộ training</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Tên</label>
                            <input type="text" class="form-control" id="edit_name" name="name">
                        </div>

                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $(".btn-group .btn").on("click", function() {
                // Xóa active ở tất cả nút
                $(".btn-group .btn").removeClass("active");

                // Gán active cho nút đang click
                $(this).addClass("active");

                // Gán giá trị filter vào input hidden
                $("#filterInput").val($(this).data("value"));

                // Submit form
                $("#searchForm").submit();
            });
        });

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
            $('#body-data-trainingbot').css({
                'opacity': '0.5'
            });
            const url = $("#searchForm").attr('action');
            $.ajax({
                url: url,
                type: 'GET',
                data: $(this).serialize(),
                success: function(response) {
                    console.log('Search response:', response);
                    $('#body-data-trainingbot').html(renderTraining(response.trainingSets.data));
                    $('#pagination-wrapper').html(response.pagination);
                    $('#body-data-trainingbot').css({
                        'opacity': '1'
                    });
                },
                error: function(xhr) {
                    console.error('Lỗi khi tìm kiếm:', xhr.responseText);
                    showDataToast('Có lỗi xảy ra khi tìm kiếm!', 'danger');
                }
            });
        });

        // Pagination
        $(document).on('click', '#pagination-wrapper a', function(e) {
            e.preventDefault();
            $('#body-data-trainingbot').css({
                'opacity': '0.5'
            });
            const url = this.href;
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    $('#body-data-trainingbot').html(renderTraining(response.trainingSets.data));
                    $('#pagination-wrapper').html(response.pagination);
                    $('#body-data-trainingbot').css({
                        'opacity': '1'
                    });
                },
                error: function(xhr) {
                    console.error('Lỗi phân trang:', xhr.responseText);
                    showDataToast('Có lỗi xảy ra khi phân trang!', 'danger');
                }
            });
        });


        // Hàm render
        function renderTraining(datas) {

            let html = '';
            datas.forEach(data => {
                // Nếu updated_at là string (ISO date), cần format bằng JS
                let updatedAt = new Date(data.updated_at);
                let formattedDate = updatedAt.toLocaleDateString('vi-VN'); // ví dụ: 12/09/2025

                html += `
                    <div class="col-md-6 col-lg-4 mb-2">
                        <div class="shadow-sm border h-100 rounded-3">
                            <!-- Header -->
                            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center rounded-3 px-3 py-2">
                                <h5 class="card-title mb-0">${data.name}</h5>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light rounded-circle" type="button"
                                        data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                   <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item btn-edit" href="#"
                                            data-id="${data.id}"
                                            data-name="${data.name}"
                                            data-description="${data.description ?? ''}">
                                            <i class="fas fa-edit me-2"></i>Chỉnh sửa
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="/admin/bot/training/detail/${data.id}">
                                            <i class="fas fa-eye me-2"></i>Xem chi tiết
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-danger btnDeleteTraining" href="#" data-id="${ data.id }">
                                            <i class="fas fa-trash-alt me-2"></i> Xóa
                                        </a>
                                    </li>

                                </ul>
                                </div>
                            </div>
                            <!-- Body -->
                            <div class="card-body px-3 py-2">
                                <p class="text-muted small mb-1">${data.description ?? ''}</p>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="badge bg-light text-dark">
                                        <i class="fas fa-file-alt me-1 text-primary"></i> ${data.documents_count} tài liệu
                                    </span>
                                    <span class="badge bg-light text-dark">
                                        <i class="fas fa-question-circle me-1 text-success"></i> ${data.questions_count} câu hỏi
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted"><i class="far fa-clock me-1"></i> Cập nhật: ${formattedDate}</small>
                                    <a href="/admin/bot/training/detail/${data.id}" class="btn btn-sm btn-outline-primary">Xem chi tiết</a>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            return html;
        }

        //Xử lý khi click nút thêm mới
        $(document).on('click', '#btnAddTraining', function(e) {
            e.preventDefault();
            $('#editTrainingLabel').text('Thêm mới bộ training')
            // Reset form (xóa hết dữ liệu cũ)
            $('#postEditTrainingForm')[0].reset();
            $('input[name="_method"]').val('POST');

            $('#postEditTrainingForm').attr('action', '/admin/bot/training/store-set/');
            $('#postEditTrainingModel').modal('show');
        });

        //hàm xử lý khi nhấn click nút sửa
        $(document).on('click', '.btn-edit', function(e) {
            e.preventDefault();
            $('#editTrainingLabel').text('Chỉnh sửa bộ training')
            let id = $(this).data('id');
            let name = $(this).data('name');
            let description = $(this).data('description');

            // Set method PUT
            $('input[name="_method"]').val('PUT');

            $('#postEditTrainingForm').attr('action', '/admin/bot/training/update-set/' + id);
            $('#edit_id').val(id);
            $('#edit_name').val(name);
            $('#edit_description').val(description);

            $('#postEditTrainingModel').modal('show');
        });

        // Hàm xử lý khi nhấn submit form thêm / sửa
        $(document).on('submit', '#postEditTrainingForm', function(e) {
            e.preventDefault();

            let form = $(this);
            let actionUrl = form.attr('action');
            let formData = form.serialize();

            $.ajax({
                url: actionUrl,
                type: 'POST',
                data: formData,
                success: function(res) {
                    if (res.success) {
                        showDataToast(res.success, 'success');
                        let method = form.find('input[name="_method"]').val();

                        if (method === 'POST') {
                            // thêm mới
                            $("#searchForm").submit()
                        } else if (method === 'PUT') {
                            // cập nhật
                            $('#training-' + res.trainingSet.id).replaceWith(renderTrainingItem(res
                                .trainingSet));
                        }
                        $('#postEditTrainingModel').modal('hide');
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let msg = '';
                        $.each(errors, function(key, value) {
                            msg += value[0] + "\n";
                        });
                        showDataToast(msg);
                    } else {
                        showDataToast("Có lỗi xảy ra, vui lòng thử lại!");
                    }
                }
            });
        });

        // Hàm render 1 bộ training
        function renderTrainingItem(trainingSet) {
            return `
                <div class="col-md-6 col-lg-4 mb-2" id="training-${trainingSet.id}">
                    <div class="shadow-sm border h-100 rounded-3">
                        <!-- Header -->
                        <div
                            class="card-header bg-white border-0 d-flex justify-content-between align-items-center rounded-3 px-3 py-2">
                            <h5 class="card-title mb-0">${trainingSet.name}</h5>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light rounded-circle" type="button"
                                    data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item btn-edit" href="#"
                                            data-id="${trainingSet.id}"
                                            data-name="${trainingSet.name}"
                                            data-description="${trainingSet.description ?? ''}">
                                            <i class="fas fa-edit me-2"></i>Chỉnh sửa
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="/admin/bot/training/detail/${trainingSet.id}">
                                            <i class="fas fa-eye me-2"></i>Xem chi tiết
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-danger btnDeleteTraining" href="#" data-id="${ $trainingSet.id }">
                                            <i class="fas fa-trash-alt me-2"></i> Xóa
                                        </a>
                                    </li>

                                </ul>
                            </div>
                        </div>
                        <!-- Body -->
                        <div class="card-body px-3 py-2">
                            <p class="text-muted small mb-1">${trainingSet.description ?? ''}</p>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-file-alt me-1 text-primary"></i>
                                    ${trainingSet.documents_count ?? 0} tài liệu
                                </span>
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-question-circle me-1 text-success"></i>
                                    ${trainingSet.questions_count ?? 0} câu hỏi
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="far fa-clock me-1"></i>
                                    Cập nhật: ${trainingSet.updated_at_formatted ?? ''}
                                </small>
                                <a href="/admin/bot/training/detail/${trainingSet.id}" class="btn btn-sm btn-outline-primary">Xem chi tiết</a>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }






        // Khi click vào nút xóa
        $(document).on('click', '.btnDeleteTraining', function(e) {
            e.preventDefault();

            let id = $(this).data('id'); // Lấy id từ data-id
            Swal.fire({
                title: 'Bạn có chắc muốn xóa?',
                text: "Hành động này không thể hoàn tác!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EB1327',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Xóa ngay',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/bot/training/delete-set/' + id,
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            _method: "DELETE"
                        },
                        success: function(res) {
                            if (res.success) {
                                showDataToast(res.success, 'success')
                                $('#searchForm').submit();
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi!',
                                text: 'Không thể xóa bộ training.',
                            });
                            console.error(xhr.responseText);
                        }
                    });
                }
            });
        });


        // Show toast log
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
