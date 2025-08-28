@section('title', 'Trang admin')
@section('description', '')
@section('content')
    {{-- Hiển thị thông báo lỗi hoặc thành công --}}
    @extends('admin.admin')
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: '{{ session('success') }}',
                confirmButtonText: 'OK'

            });
        </script>
        {{-- Xoa session thong bao --}}
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

        <!-- Start Container Fluid -->
        <div class="container-xxl">
            <div class="row g-2 mb-2">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-light px-3 py-1 rounded shadow-sm">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Quản lí khóa học</li>
                        </ol>
                    </nav>
                </div>

                <!-- Tổng khóa học -->
                <div class="col-md-6 col-xl-3">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center row">
                            <div class="col-4">
                                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTP-n3YOmyI1QcbUFS8MHj9jDrcQQOlctafZw&s"
                                    alt="Tổng khóa học" class="img-fluid rounded" style="max-height: 80px;">
                            </div>
                            <div class="col-8">
                                <h5 class="fw-bold">Tổng khóa học</h5>
                                <p class="mb-0 fs-5 text-primary">{{ $totalCourses }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tổng buổi học -->
                <div class="col-md-6 col-xl-3">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center row">
                            <div class="col-4">
                                <img src="https://ecorp.edu.vn/wp-content/uploads/ung-dung-hoc-tieng-anh-0.jpg"
                                    alt="Tổng buổi học" class="img-fluid rounded" style="max-height: 80px;">
                            </div>
                            <div class="col-8">
                                <h5 class="fw-bold">Tổng buổi học</h5>
                                <p class="mb-0 fs-5 text-primary">{{ $totalSessions }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tổng giá trị khóa học -->
                <div class="col-md-6 col-xl-3">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center row">
                            <div class="col-4">
                                <img src="https://media.kenhtuyensinh.vn/images/cms/2018/07/top-10-website-hoc-tieng-anh-online-mien-phi-danh-cho-hssv2.png"
                                    alt="Tổng giá trị khóa học" class="img-fluid rounded" style="max-height: 80px;">
                            </div>
                            <div class="col-8">
                                <h5 class="fw-bold">Tổng giá trị khóa học</h5>
                                <p class="mb-0 fs-5 text-success">{{ number_format($totalRevenue) }} VNĐ</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Khóa học tháng này -->
                <div class="col-md-6 col-xl-3">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center row">
                            <div class="col-4">
                                <img src="https://stepup.edu.vn/wp-content/uploads/2020/11/anh-bia-tai-lieu-luyen-nghe-tieng-anh.jpg"
                                    alt="Khóa học tháng này" class="img-fluid rounded" style="max-height: 80px;">
                            </div>
                            <div class="col-8">
                                <h5 class="fw-bold">Trong tháng này</h5>
                                <p class="mb-0 fs-5 text-primary">{{ $coursesThisMonth }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-end flex-wrap w-100">
                                <form action="{{ route('admin.course-list') }}" method="GET"
                                    class="row g-2 align-items-end w-100"> <!-- Thêm w-100 ở đây -->

                                    <!-- Từ khóa -->
                                    <div class="col">
                                        <label for="name" class="form-label mb-1">Từ khóa</label>
                                        <input type="text" name="name" id="name"
                                            class="form-control form-control-sm" placeholder="Tên khóa học"
                                            value="{{ request('name') }}">
                                    </div>

                                    <!-- Tháng tạo -->
                                    <div class="col">
                                        <label for="month" class="form-label mb-1">Tháng tạo</label>
                                        <select name="month" id="month" class="form-select form-select-sm">
                                            <option value="">Tất cả</option>
                                            @for ($i = 1; $i <= 12; $i++)
                                                <option value="{{ $i }}"
                                                    {{ request('month') == $i ? 'selected' : '' }}>
                                                    Tháng {{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>

                                    <!-- Sắp xếp giá -->
                                    <div class="col">
                                        <label for="sort" class="form-label mb-1">Sắp xếp giá</label>
                                        <select name="sort" id="sort" class="form-select form-select-sm">
                                            <option value="">Tất cả</option>
                                            <option value="price_asc"
                                                {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Tăng dần</option>
                                            <option value="price_desc"
                                                {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giảm dần</option>
                                        </select>
                                    </div>

                                    <!-- Nút Lọc & Xóa -->
                                    <div class="col">
                                        <label class="form-label mb-1 d-block">Tác vụ</label>
                                        <div class="d-flex gap-1">

                                            <button type="submit" class="btn btn-success btn-sm w-100">
                                                <iconify-icon icon="ic:baseline-filter-alt" class="me-1"></iconify-icon>
                                                Lọc
                                            </button>
                                            <a href="{{ route('admin.course-list') }}" class="btn btn-danger btn-sm w-100">
                                                <iconify-icon icon="ic:round-clear" class="me-1"></iconify-icon> Xóa
                                            </a>

                                        </div>
                                    </div>

                                    <!-- Nút Thêm khóa học -->
                                    <div class="col">

                                        <a href="{{ route('admin.course-add') }}"
                                            class="btn btn-warning btn-sm w-100">Thêm
                                            khóa học</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    <div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover table-centered">
                                <thead class="bg-light-subtle">

                                    <tr>

                                        <th>STT</th>
                                        <th>Hình ảnh </th>
                                        <th>Tên Khóa Học</th>
                                        <th>Giá Khóa Học </th>
                                        <th>Tổng Số Buổi Học </th>
                                        <th>Ngày Tạo Khóa Học </th>
                                        <th>Nổi Bật </th>
                                        <th>Hành Động </th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($courses as $course)
                                        <tr>
                                            {{-- <td>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="customCheck2">
                                                    <label class="form-check-label" for="customCheck2"></label>
                                                </div>
                                            </td> --}}
                                            {{-- <td> {{ $course->id }}</td> --}}
                                            <td> {{ ($courses->currentPage() - 1) * $courses->perPage() + $loop->iteration }}
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div
                                                        class="rounded bg-light avatar-md d-flex align-items-center justify-content-center">
                                                        @if ($course->image)
                                                            <img src="{{ asset($course->image) }}" width="80">
                                                        @else
                                                            <span>Không có ảnh</span>
                                                        @endif
                                                    </div>

                                                </div>
                                            </td>

                                            <td>
                                                <p class="text-dark fw-medium fs-15 mb-0">{{ $course->name }}</p>
                                            </td>
                                            <td>{{ number_format($course->price) }} VNĐ</td>
                                            <td>{{ $course->total_sessions }}</td>

                                            <td>{{ $course->created_at }}</td>
                                            {{-- <td>{{ $course->description }}</td> --}}
                                            <td style="width: 100px;">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input"
                                                        onchange="toggleFeatured({{ $course->id }})"
                                                        {{ $course->is_featured ? 'checked' : '' }}>
                                                </div>
                                            </td>

                                            <td>

                                                <div class="dropdown">
                                                    <button class="btn btn-light btn-sm dropdown-toggle" type="button"
                                                        data-bs-toggle="dropdown">
                                                        Thao tác <iconify-icon icon="tabler:caret-down-filled"
                                                            class="ms-1"></iconify-icon>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a href="{{ route('admin.course-detail', ['id' => $course->id]) }}"
                                                                class="dropdown-item text-primary"><iconify-icon
                                                                    icon="solar:eye-broken"
                                                                    class="me-1"></iconify-icon>Chi tiết</a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ route('admin.course-edit', ['id' => $course->id]) }}"
                                                                class="dropdown-item text-warning"><iconify-icon
                                                                    icon="solar:pen-2-broken"
                                                                    class="me-1"></iconify-icon>Sửa</a>
                                                        </li>
                                                        <li>
                                                            <form id="delete-course-form-{{ $course->id }}"
                                                                action="{{ route('admin.course-delete', ['id' => $course->id]) }}"
                                                                method="POST" style="display:inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="button" class="dropdown-item text-danger"
                                                                    onclick="confirmDelete({{ $course->id }})">
                                                                    <iconify-icon
                                                                        icon="solar:trash-bin-minimalistic-2-broken"
                                                                        class="align-middle fs-18 me-1"></iconify-icon>
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
                            {{ $courses->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    </div>
@endsection


@push('scripts')
    <script>
        function confirmDelete(courseId) {

            Swal.fire({
                title: 'Bạn có chắc chắn?',
                text: "Thao tác này sẽ xóa bài giảng!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    // console.log(result);

                    document.getElementById('delete-course-form-' + courseId).submit();

                    // console.log(dom);
                }
            });
        }

        function toggleFeatured(courseId) {
            fetch(`/admin/courses/${courseId}/toggle-featured`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Cập nhật thành công',
                            text: data.status ? 'Khóa học đã được đánh dấu nổi bật' :
                                'Khóa học đã được bỏ đánh dấu nổi bật',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                })
                .catch(error => {
                    console.error('Lỗi:', error);
                    Swal.fire('Lỗi', 'Không thể cập nhật trạng thái nổi bật', 'error');
                });
        }
    </script>
@endpush
