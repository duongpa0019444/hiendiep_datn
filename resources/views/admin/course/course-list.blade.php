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
    @endif
    <style>
        .form-label {
            font-weight: 500;
            font-size: 1rem;
        }

        .stat-card img {
            width: 100%;
            max-width: 120px;
            height: auto;
            object-fit: cover;
            border-radius: 12px;
            transition: transform 0.3s ease;
        }

        .stat-card img:hover {
            transform: scale(1.05);
        }

        .stat-card h4 {
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
            margin-top: 15px;
        }

        .stat-card p {
            font-size: 1.1rem;
            font-weight: bold;
            color: #555;
        }

        .stat-card .card-body {
            padding: 1.5rem;
        }

        /* .stat-card .text-center {
            background-color: #e7c58f;
        } */

        .stat-card .rounded {
            padding: 10px;
            background-color: #ecae84;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .custom-alert {
            padding: 15px 25px;
            border-radius: 8px;
            font-size: 16px;
            text-align: center;
            max-width: 600px;
            margin: 20px auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            font-weight: 500;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>



    <div class="page-content">

        <!-- Start Container Fluid -->
        <div class="container-xxl">
            <div class="row">
                <!-- Tổng khóa học -->
                <div class="col-md-6 col-xl-3">
                    <div class="card stat-card">
                        <div class="card-body text-center">
                            <div class="rounded mx-auto d-flex justify-content-center align-items-center">
                                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTP-n3YOmyI1QcbUFS8MHj9jDrcQQOlctafZw&s"
                                    alt="Tổng khóa học">
                            </div>
                            <h4>Tổng khóa học</h4>
                            <p>{{ $totalCourses }}</p>
                        </div>
                    </div>
                </div>

                <!-- Tổng buổi học -->
                <div class="col-md-6 col-xl-3">
                    <div class="card stat-card">
                        <div class="card-body text-center">
                            <div class="rounded mx-auto d-flex justify-content-center align-items-center">
                                <img src="https://ecorp.edu.vn/wp-content/uploads/ung-dung-hoc-tieng-anh-0.jpg"
                                    alt="Tổng buổi học">
                            </div>
                            <h4>Tổng buổi học</h4>
                            <p>{{ $totalSessions }}</p>
                        </div>
                    </div>
                </div>

                <!-- Tổng giá trị khóa học -->
                <div class="col-md-6 col-xl-3">
                    <div class="card stat-card">
                        <div class="card-body text-center">
                            <div class="rounded mx-auto d-flex justify-content-center align-items-center">
                                <img src="https://media.kenhtuyensinh.vn/images/cms/2018/07/top-10-website-hoc-tieng-anh-online-mien-phi-danh-cho-hssv2.png"
                                    alt="Tổng giá trị khóa học">
                            </div>
                            <h4>Tổng giá trị khóa học</h4>
                            <p>{{ number_format($totalRevenue) }} VNĐ</p>
                        </div>
                    </div>
                </div>

                <!-- Khóa học tháng này -->
                <div class="col-md-6 col-xl-3">
                    <div class="card stat-card">
                        <div class="card-body text-center">
                            <div class="rounded mx-auto d-flex justify-content-center align-items-center">
                                <img src="https://stepup.edu.vn/wp-content/uploads/2020/11/anh-bia-tai-lieu-luyen-nghe-tieng-anh.jpg"
                                    alt="Khóa học tháng này">
                            </div>
                            <h4>Trong tháng này</h4>
                            <p>{{ $coursesThisMonth }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            {{-- <div class="d-flex flex-wrap justify-content-between align-items-center w-100 gap-2"> --}}

                            {{-- Tiêu đề bên trái --}}
                            <p class="card-title mb-0"
                                style="font-family: 'Poppins', sans-serif; color: #ff7f0e; font-size: 20px; font-weight: 600;">
                                Quản lý danh sách khóa học
                            </p>
                            {{-- Form lọc bên phải --}}
                            <form action="{{ route('admin.course-list') }}" method="GET"
                                class="d-flex flex-wrap align-items-end gap-2 w-100 justify-content-end">

                                {{-- Từ khóa --}}
                                <div>
                                    <label for="name" class="form-label mb-1">Từ khóa</label>
                                    <input type="text" name="name" id="name" class="form-control form-control-sm"
                                        placeholder="Tên khóa học" value="{{ request('name') }}">
                                </div>

                                {{-- Tháng tạo --}}
                                <div>
                                    <label for="month" class="form-label mb-1">Tháng tạo</label>
                                    <select name="month" id="month" class="form-select form-select-sm">
                                        <option value="">Tất cả</option>
                                        @for ($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}"
                                                {{ request('month') == $i ? 'selected' : '' }}>Tháng
                                                {{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>

                                {{-- Sắp xếp giá --}}
                                <div>
                                    <label for="sort" class="form-label mb-1">Sắp xếp giá</label>
                                    <select name="sort" id="sort" class="form-select form-select-sm">
                                        <option value="">Tất cả</option>
                                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>
                                            Tăng dần</option>
                                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>
                                            Giảm dần</option>
                                    </select>
                                </div>

                                {{-- Nút lọc và xóa --}}
                                <div class="d-flex gap-2 align-items-end">
                                    <button type="submit" class="btn btn-success btn-sm">Lọc</button>
                                    <a href="{{ route('admin.course-list') }}" class="btn btn-danger btn-sm">Xóa</a>
                                </div>

                                {{-- Nút thêm khóa học --}}
                                <div class="d-flex align-items-end">
                                    <a href="{{ route('admin.course-add') }}" class="btn btn-warning btn-sm">Thêm khóa
                                        học</a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover table-centered">
                                <thead class="bg-light-subtle">

                                    <tr>
                                        <th style="width: 20px;">
                                            {{-- phần này để bỏ ô checkbox --}}
                                            {{-- <div class="form-check">
                                                                      <input type="checkbox" class="form-check-input" id="customCheck1">
                                                                      <label class="form-check-label" for="customCheck1"></label>
                                                                 </div> --}}
                                        </th>
                                        <th>ID</th>
                                        <th>Hình ảnh </th>
                                        <th>Tên Khóa Học</th>
                                        <th>Giá Khóa Học </th>
                                        <th>Tổng Số Buổi Học </th>
                                        <th>Ngày Tạo Khóa Học </th>
                                        <th>Mô Tả Khóa Học </th>
                                        <th>Hành Động </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($courses as $course)
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="customCheck2">
                                                    <label class="form-check-label" for="customCheck2"></label>
                                                </div>
                                            </td>
                                            <td> {{ $course->id }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div
                                                        class="rounded bg-light avatar-md d-flex align-items-center justify-content-center">
                                                        @if ($course->image)
                                                            <img src="{{ asset($course->image) }}"
                                                                width="80" height="auto">
                                                        @else
                                                            <span>Không có ảnh</span>
                                                        @endif
                                                    </div>

                                                </div>
                                            </td>

                                            <td>
                                                <p class="text-dark fw-medium fs-15 mb-0">{{ $course->name }}</p>
                                            </td>
                                            <td>{{ $course->price }}</td>
                                            <td>{{ $course->total_sessions }}</td>

                                            <td>{{ $course->created_at }}</td>
                                            <td>{{ $course->description }}</td>

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
                                                                    class="me-1"></iconify-icon>Xem</a>
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
                                    @endforeach;


                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 m-3">
                        <div id="pagination-wrapper" class="flex-grow-1">
                            {{ $courses->links('pagination::bootstrap-5') }}
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

    </div>
@endsection


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
</script>
