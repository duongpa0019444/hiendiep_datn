@extends('admin.admin')


@section('title', 'Trang admin')
@section('description', '')
@section('content')
    <div class="page-content">

        <!-- Start Container Fluid -->
        <div class="container-fluid">

            <!-- Start here.... -->
            <div class="row mt-2">
                <div class="col-xxl-5">
                    <div class="row">
                        <h4 class="card-title mb-3">Tổng quan (Overview)</h4>


                        <div class="col-md-12">
                            <div class="card overflow-hidden">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="avatar-md bg-soft-primary rounded">
                                                <iconify-icon icon="ic:baseline-people-alt"
                                                    class="avatar-title fs-32 text-primary"></iconify-icon>
                                            </div>
                                        </div> <!-- end col -->
                                        <div class="col-8 text-end">
                                            <p class="text-muted mb-0 text-truncate">Tổng Số Người Dùng</p>
                                            <h3 class="text-dark mt-1 mb-0">200</h3>
                                        </div> <!-- end col -->
                                    </div> <!-- end row-->
                                    <div class="d-flex align-items-center justify-content-between mt-2 flex-wrap">
                                        <div class="">
                                            <span class="text-success"> <i class="bx bxs-up-arrow fs-12"></i> 100</span>
                                            <span class="text-muted ms-1 fs-12">Học sinh</span>
                                        </div>
                                        <div class="">
                                            <span class="text-success"> <i class="bx bxs-up-arrow fs-12"></i> 100</span>
                                            <span class="text-muted ms-1 fs-12">Giáo viên</span>
                                        </div>
                                        <div class="">
                                            <span class="text-success"> <i class="bx bxs-up-arrow fs-12"></i> 100</span>
                                            <span class="text-muted ms-1 fs-12">Nhân viên</span>
                                        </div>

                                    </div>
                                </div> <!-- end card body -->
                                <div class="card-footer py-1 bg-light bg-opacity-50">
                                    <div class="d-flex align-items-center justify-content-between">

                                        <div>
                                            <span class="text-primary"> <i class="bx bxs-up-arrow fs-12"></i> 1</span>
                                            <span class="text-muted ms-1 fs-12">Người quản trị</span>
                                        </div>

                                        <a href="#!" class="text-reset fw-semibold fs-12">Xem Thêm</a>
                                    </div>
                                </div> <!-- end card body -->
                            </div> <!-- end card -->
                        </div> <!-- end col -->




                        <div class="col-md-12">
                            <div class="card overflow-hidden">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="avatar-md bg-soft-primary rounded">
                                                <iconify-icon icon="mdi:school"
                                                    class="avatar-title fs-32 text-primary"></iconify-icon>
                                            </div>
                                        </div> <!-- end col -->
                                        <div class="col-8 text-end">
                                            <p class="text-muted mb-0 text-truncate">Tổng Số lớp học</p>
                                            <h3 class="text-dark mt-1 mb-0">200</h3>
                                        </div> <!-- end col -->
                                    </div> <!-- end row-->

                                </div> <!-- end card body -->
                                <div class="card-footer py-1 bg-light bg-opacity-50">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap">

                                        <div>
                                            <span class="text-success"> <i class="bx bxs-up-arrow fs-12"></i> 100</span>
                                            <span class="text-muted ms-1 fs-12">Đang học</span>
                                        </div>

                                        <div>
                                            <span class="text-primary"> <i class="bx bxs-up-arrow fs-12"></i> 100</span>
                                            <span class="text-muted ms-1 fs-12">Đã kết thúc</span>
                                        </div>

                                        <a href="#!" class="text-reset fw-semibold fs-12">Xem Thêm</a>
                                    </div>
                                </div> <!-- end card body -->


                            </div> <!-- end card -->
                        </div> <!-- end col -->

                        <div class="col-md-12">
                            <div class="card overflow-hidden">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="avatar-md bg-soft-primary rounded">
                                                <iconify-icon icon="mdi:currency-usd"
                                                    class="avatar-title fs-32 text-primary"></iconify-icon>
                                            </div>
                                        </div> <!-- end col -->
                                        <div class="col-8 text-end">
                                            <p class="text-muted mb-0 text-truncate">Tổng doanh thu học phí</p>
                                            <h3 class="text-dark mt-1 mb-0">200.000.000 VNĐ</h3>
                                        </div> <!-- end col -->
                                    </div> <!-- end row-->

                                </div> <!-- end card body -->
                                <div class="card-footer py-1 bg-light bg-opacity-50">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap">


                                        <div class="">
                                            <span class="text-success"> <i class="bx bxs-up-arrow fs-12"></i> 1.000</span>
                                            <span class="text-muted ms-1 fs-12">Đã đóng tiền</span>
                                        </div>
                                        <div class="">
                                            <span class="text-primary"> <i class="bx bxs-up-arrow fs-12"></i> 100</span>
                                            <span class="text-muted ms-1 fs-12">Chưa đóng tiền</span>
                                        </div>


                                        <a href="#!" class="text-reset fw-semibold fs-12">Xem Thêm</a>
                                    </div>
                                </div> <!-- end card body -->


                            </div> <!-- end card -->
                        </div> <!-- end col -->

                    </div> <!-- end row -->
                </div> <!-- end col -->

                <div class="col-xxl-7">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="card-title col-xl-5">Thống kê nộp tiền học</h4>
                                <form class="col-xl-5">
                                    <select class="form-control w-100" id="product-categories" data-choices
                                        data-choices-groups data-placeholder="Select Categories" name="where">
                                        <option value="">Tất cả các khóa học</option>
                                        <option value="#">Tiếng Anh A1
                                        </option>
                                        <option value="#">Tiếng Anh A2
                                        </option>
                                        <option value="#">IELTS
                                        </option>
                                        <option value="#">TOEIC
                                        </option>
                                        <option value="#">Giao tiếp
                                        </option>
                                        <option value="#">IELTS 1
                                        </option>
                                        <option value="#">IELTS 2
                                        </option>
                                        <option value="#">IELTS 3
                                        </option>
                                    </select>
                                </form>
                            </div> <!-- end card-title-->

                            <div id="grouped-bar" class="apex-charts text-white"></div>
                            <div class="card-footer border-top">
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination justify-content-end mb-0">
                                        <li class="page-item"><a class="page-link"
                                                href="javascript:void(0);">Previous</a>
                                        </li>
                                        <li class="page-item active"><a class="page-link"
                                                href="javascript:void(0);">1</a>
                                        </li>
                                        <li class="page-item"><a class="page-link" href="javascript:void(0);">2</a></li>
                                        <li class="page-item"><a class="page-link" href="javascript:void(0);">3</a></li>
                                        <li class="page-item"><a class="page-link" href="javascript:void(0);">Next</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div> <!-- end card body -->
                    </div> <!-- end card -->
                </div> <!-- end col -->
            </div> <!-- end row -->

            <div class="row">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title">Danh sách lớp học</h4>

                            </div> <!-- end card-header-->
                            <div class="card-body p-0">
                                <div class="px-3" data-simplebar style="max-height: 398px;">
                                    <table class="table table-hover mb-0 table-centered">
                                        <thead>
                                            <tr>
                                                <th>Tên lớp</th>
                                                <th>Khóa học</th>
                                                <th>Giáo viên</th>
                                                <th>Số học viên</th>
                                                <th>Thời gian</th>
                                                <th>Lịch học</th>
                                                {{-- <th>Hành động</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Lớp A1-1</td>
                                                <td>Tiếng Anh A1</td>
                                                <td>Nguyễn Thị Lan</td>
                                                <td>15</td>
                                                <td>01/06/2025 - 30/08/2025</td>
                                                <td>
                                                    <a href="#!" class="text-primary" data-bs-toggle="collapse"
                                                        data-bs-target="#schedule-1">Xem</a>
                                                    <div id="schedule-1" class="collapse">
                                                        <ul class="list-unstyled mb-0">
                                                            <li>Thứ 2: 18:00-20:00, Phòng 101</li>
                                                            <li>Thứ 4: 18:00-20:00, Phòng 101</li>
                                                        </ul>
                                                    </div>
                                                </td>

                                            </tr>
                                            <tr>
                                                <td>Lớp A1-2</td>
                                                <td>Tiếng Anh A1</td>
                                                <td>Trần Văn Hùng</td>
                                                <td>12</td>
                                                <td>01/06/2025 - 30/08/2025</td>
                                                <td>
                                                    <a href="#!" class="text-primary" data-bs-toggle="collapse"
                                                        data-bs-target="#schedule-2">Xem</a>
                                                    <div id="schedule-2" class="collapse">
                                                        <ul class="list-unstyled mb-0">
                                                            <li>Thứ 3: 19:00-21:00, Phòng 102</li>
                                                            <li>Thứ 5: 19:00-21:00, Phòng 102</li>
                                                        </ul>
                                                    </div>
                                                </td>

                                            </tr>
                                            <tr>
                                                <td>Lớp IELTS-1</td>
                                                <td>IELTS</td>
                                                <td>Phạm Minh Tuấn</td>
                                                <td>10</td>
                                                <td>15/06/2025 - 15/09/2025</td>
                                                <td>
                                                    <a href="#!" class="text-primary" data-bs-toggle="collapse"
                                                        data-bs-target="#schedule-3">Xem</a>
                                                    <div id="schedule-3" class="collapse">
                                                        <ul class="list-unstyled mb-0">
                                                            <li>Thứ 2: 17:00-19:00, Phòng 201</li>
                                                            <li>Thứ 6: 17:00-19:00, Phòng 201</li>
                                                        </ul>
                                                    </div>
                                                </td>

                                            </tr>
                                            <tr>
                                                <td>Lớp TOEIC-1</td>
                                                <td>TOEIC</td>
                                                <td>Lê Thị Mai</td>
                                                <td>18</td>
                                                <td>01/07/2025 - 30/09/2025</td>
                                                <td>
                                                    <a href="#!" class="text-primary" data-bs-toggle="collapse"
                                                        data-bs-target="#schedule-4">Xem</a>
                                                    <div id="schedule-4" class="collapse">
                                                        <ul class="list-unstyled mb-0">
                                                            <li>Thứ 4: 18:30-20:30, Phòng 202</li>
                                                            <li>Thứ 7: 18:30-20:30, Phòng 202</li>
                                                        </ul>
                                                    </div>
                                                </td>

                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div> <!-- end card body -->
                        </div> <!-- end card-->


            </div> <!-- end row -->

        </div>
        <!-- End Container Fluid -->

        <!-- ========== Footer Start ========== -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 text-center">
                        <script>
                            document.write(new Date().getFullYear())
                        </script> &copy; DỰ ÁN TỐT NGHIỆP CAO ĐẲNG FPT THANH HÓA<iconify-icon
                            icon="iconamoon:heart-duotone" class="fs-18 align-middle text-danger"></iconify-icon> <a
                            href="#" class="fw-bold footer-text" target="_blank">NHÓM 4</a>
                    </div>
                </div>
            </div>
        </footer>
        <!-- ========== Footer End ========== -->

    </div>


@endsection
