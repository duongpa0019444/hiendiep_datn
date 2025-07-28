<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from techzaa.in/larkon/admin/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 28 Oct 2024 06:27:43 GMT -->

<head>

    <!-- Title Meta -->
    <meta charset="utf-8" />
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A fully responsive premium admin dashboard template" />
    <meta name="author" content="Techzaa" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('client/images/logo-icon.png') }}">

    <!-- Vendor css (Require in all Page) -->
    <link href="{{ asset('admin/css/vendor.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Icons css (Require in all Page) -->
    <link href="{{ asset('admin/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- App css (Require in all Page) -->
    <link href="{{ asset('admin/css/app.min.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('admin/css/loading.css') }}" rel="stylesheet" type="text/css" />
    <!-- Theme Config js (Require in all Page) -->
    <script src="{{ asset('admin/js/config.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script> --}}

    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <!-- Font Awesome 6 CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    {{-- LINK Jquery --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script src="{{ asset('ckeditor4/ckeditor/ckeditor.js') }}"></script>
    @stack('styles')
    @vite('resources/js/app.js')



</head>

<body>

    <!-- Preloader -->
    <div id="preloader">
        <div class="spinner-wrapper">
            <div class="spinner-ring"></div>
            <img src="{{ asset('client/images/logo-icon.png') }}" alt="Logo" class="spinner-logo">
        </div>
        <p class="text-muted mt-3">Đang tải...</p>

        <!-- Progress bar -->
        <div class="progress-container mt-3">
            <div class="progress-bar-loading"></div>
        </div>
    </div>




    <!-- Modal chi tiết -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">Chi tiết lớp học</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-1">
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <p><strong>Lớp học:</strong> <span id="modalClass"></span></p>
                            </div>
                            <div class="col-md-4 mb-2">
                                <p><strong>Môn học:</strong> <span id="modalSubject"></span></p>
                            </div>
                            <div class="col-md-4 mb-2">
                                <p><strong>Giáo viên:</strong> <span id="modalTeacher"></span></p>
                            </div>
                            <div class="col-md-6 mb-2">
                                <p><strong>Thời gian:</strong> <span id="modalTime"></span></p>
                            </div>
                            <div class="col-md-6 mb-2">
                                <p class="d-flex align-items-center"><strong>Trạng thái buổi học:</strong>
                                    <span id="modalStatus" class="ms-2"></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h4>Danh sách điểm danh</h4>
                    <table class="table table-bordered attendance-table">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên học viên</th>
                                <th>Trạng thái</th>
                                <th>Ghi chú</th>
                            </tr>
                        </thead>
                        <tbody id="attendanceBody"></tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
                    <a href="{{ route('admin.attendance.index') }}"><button type="button" class="btn btn-primary">Chi
                            tiết</button></a>
                </div>
            </div>
        </div>
    </div>
    <!-- START Wrapper -->
    <div class="wrapper">
        <!-- ========== Topbar Start ========== -->
        <header class="topbar">
            <div class="container-fluid">
                <div class="navbar-header">
                    <div class="d-flex align-items-center">
                        <!-- Menu Toggle Button -->
                        <div class="topbar-item">
                            <button type="button" class="button-toggle-menu me-2">
                                <iconify-icon icon="solar:hamburger-menu-broken"
                                    class="fs-24 align-middle"></iconify-icon>
                            </button>
                        </div>

                        <!-- Menu Toggle Button -->
                        <div class="topbar-item">
                            <h4 class="fw-bold topbar-button pe-none text-uppercase mb-0">Chào mừng
                                {{ Auth::user()->name }}!</h4>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-1">

                        <!-- Theme Color (Light/Dark) -->
                        <div class="topbar-item">
                            <button type="button" class="topbar-button" id="light-dark-mode">
                                <iconify-icon icon="solar:moon-bold-duotone" class="fs-24 align-middle"></iconify-icon>
                            </button>
                        </div>

                        <div class="dropdown topbar-item" data-bs-auto-close="outside">
                            @php

                                $notifications = \App\Models\notificationCoursePayments::where('status', '!=', 'seen')
                                    ->orderBy('created_at', 'desc')
                                    ->get();
                            @endphp
                            <button type="button" class="topbar-button position-relative"
                                id="page-header-notifications-dropdown" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <iconify-icon icon="solar:bell-bing-bold-duotone"
                                    class="fs-24 align-middle"></iconify-icon>
                                <span
                                    class="position-absolute topbar-badge fs-10 translate-middle badge bg-danger rounded-pill">{{ count($notifications) }}<span
                                        class="visually-hidden">unread messages</span></span>
                            </button>
                            <div class="dropdown-menu py-0 dropdown-xl dropdown-menu-end"
                                aria-labelledby="page-header-notifications-dropdown">
                                <div class="p-3 border-top-0 border-start-0 border-end-0 border-dashed border">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h6 class="m-0 fs-16 fw-semibold">Thông báo thu học phí</h6>
                                        </div>
                                    </div>
                                </div>

                                <div data-simplebar style="max-height: 300px;">
                                    <!-- Item 1 -->

                                    @foreach ($notifications as $notification)
                                        <div class="dropdown-item py-3 border-bottom text-wrap position-relative">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <div class="avatar-sm me-2">
                                                        <span
                                                            class="avatar-title {{ $notification->coursePayment->method == 'Cash' ? 'bg-soft-info text-info' : 'bg-soft-success text-success' }} fs-20 rounded-circle">
                                                            <i class="fas fa-money-bill-wave"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 pe-4">
                                                    <p class="mb-1 fw-semibold">
                                                        {{ $notification->coursePayment->user->name }} đã
                                                        {{ $notification->coursePayment->method == 'Cash' ? 'nộp tiền mặt' : 'chuyển khoản' }}
                                                        học phí
                                                    </p>
                                                    <p class="mb-0 text-muted text-wrap">

                                                        Thời gian:
                                                        {{ \Carbon\Carbon::parse($notification->coursePayment->payment_date)->format('H:i - d/m/Y') }}<br>
                                                    </p>
                                                </div>

                                                <!-- Dấu ba chấm và dropdown -->
                                                <div class="dropdown position-absolute top-0 end-0 mt-2 me-2">
                                                    <button class="btn btn-sm border-0" type="button"
                                                        data-bs-toggle="dropdown" aria-expanded="false"
                                                        onclick="event.stopPropagation();">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <a class="dropdown-item mark-as-read"
                                                                href="javascript:void(0);"
                                                                data-id="{{ $notification->id }}">
                                                                <i class="fas fa-check me-1 text-success"></i> Đánh dấu
                                                                là đã đọc
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach



                                </div>

                                <div class="text-center py-3">
                                    <a href="/admin/notifications/tuition" class="btn btn-primary btn-sm">
                                        Xem tất cả thông báo <i class="bx bx-right-arrow-alt ms-1"></i>
                                    </a>
                                </div>
                            </div>



                            <!-- Theme Setting -->
                            <div class="topbar-item d-none d-md-flex">
                                <button type="button" class="topbar-button" id="theme-settings-btn"
                                    data-bs-toggle="offcanvas" data-bs-target="#theme-settings-offcanvas"
                                    aria-controls="theme-settings-offcanvas">
                                    <iconify-icon icon="solar:settings-bold-duotone"
                                        class="fs-24 align-middle"></iconify-icon>
                                </button>
                            </div>


                            <!-- User -->
                            <div class="dropdown topbar-item">
                                <a type="button" class="topbar-button d-flex align-items-center gap-2"
                                    id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">

                                    <div class="d-flex flex-column justify-content-center text-end">
                                        <p class="mb-0 fw-semibold">{{ Auth::user()->name }}</p>
                                    </div>

                                    <span class="d-flex align-items-center  border rounded-circle overflow-hidden">
                                        <img class="rounded-circle" width="32" height="32"
                                            src="{{ Auth::user()->avatar ? asset(Auth::user()->avatar) : asset('icons/user-solid.svg') }}"
                                            alt="avatar">
                                    </span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <!-- item-->
                                    <h6 class="dropdown-header">Welcome {{ Auth::user()->name }}!</h6>
                                    {{-- <a class="dropdown-item" href="pages-profile.html">
                                        <i class="bx bx-user-circle text-muted fs-18 align-middle me-1"></i><span
                                            class="align-middle">Profile</span>
                                    </a>
                                    <a class="dropdown-item" href="apps-chat.html">
                                        <i class="bx bx-message-dots text-muted fs-18 align-middle me-1"></i><span
                                            class="align-middle">Messages</span>
                                    </a>

                                    <a class="dropdown-item" href="pages-pricing.html">
                                        <i class="bx bx-wallet text-muted fs-18 align-middle me-1"></i><span
                                            class="align-middle">Pricing</span>
                                    </a>
                                    <a class="dropdown-item" href="pages-faqs.html">
                                        <i class="bx bx-help-circle text-muted fs-18 align-middle me-1"></i><span
                                            class="align-middle">Help</span>
                                    </a>
                                    <a class="dropdown-item" href="auth-lock-screen.html">
                                        <i class="bx bx-lock text-muted fs-18 align-middle me-1"></i><span
                                            class="align-middle">Lock screen</span>
                                    </a> --}}

                                    <div class="dropdown-divider my-1"></div>

                                    <a class="dropdown-item text-danger" href="{{ route('auth.logout') }}">
                                        <i class="bx bx-log-out fs-18 align-middle me-1"></i><span
                                            class="align-middle">Đăng xuất</span>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
        </header>



        <!-- Right Sidebar (Theme Settings) -->
        <div>
            <div class="offcanvas offcanvas-end border-0" tabindex="-1" id="theme-settings-offcanvas">
                <div class="d-flex align-items-center bg-primary p-3 offcanvas-header">
                    <h5 class="text-white m-0">Theme Settings</h5>
                    <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>

                <div class="offcanvas-body p-0">
                    <div data-simplebar class="h-100">
                        <div class="p-3 settings-bar">

                            <div>
                                <h5 class="mb-3 font-16 fw-semibold">Color Scheme</h5>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="data-bs-theme"
                                        id="layout-color-light" value="light">
                                    <label class="form-check-label" for="layout-color-light">Light</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="data-bs-theme"
                                        id="layout-color-dark" value="dark">
                                    <label class="form-check-label" for="layout-color-dark">Dark</label>
                                </div>
                            </div>

                            <div>
                                <h5 class="my-3 font-16 fw-semibold">Topbar Color</h5>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="data-topbar-color"
                                        id="topbar-color-light" value="light">
                                    <label class="form-check-label" for="topbar-color-light">Light</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="data-topbar-color"
                                        id="topbar-color-dark" value="dark">
                                    <label class="form-check-label" for="topbar-color-dark">Dark</label>
                                </div>
                            </div>


                            <div>
                                <h5 class="my-3 font-16 fw-semibold">Menu Color</h5>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="data-menu-color"
                                        id="leftbar-color-light" value="light">
                                    <label class="form-check-label" for="leftbar-color-light">
                                        Light
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="data-menu-color"
                                        id="leftbar-color-dark" value="dark">
                                    <label class="form-check-label" for="leftbar-color-dark">
                                        Dark
                                    </label>
                                </div>
                            </div>

                            <div>
                                <h5 class="my-3 font-16 fw-semibold">Sidebar Size</h5>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="data-menu-size"
                                        id="leftbar-size-default" value="default">
                                    <label class="form-check-label" for="leftbar-size-default">
                                        Default
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="data-menu-size"
                                        id="leftbar-size-small" value="condensed">
                                    <label class="form-check-label" for="leftbar-size-small">
                                        Condensed
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="data-menu-size"
                                        id="leftbar-hidden" value="hidden">
                                    <label class="form-check-label" for="leftbar-hidden">
                                        Hidden
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="data-menu-size"
                                        id="leftbar-size-small-hover-active" value="sm-hover-active">
                                    <label class="form-check-label" for="leftbar-size-small-hover-active">
                                        Small Hover Active
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="data-menu-size"
                                        id="leftbar-size-small-hover" value="sm-hover">
                                    <label class="form-check-label" for="leftbar-size-small-hover">
                                        Small Hover
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="offcanvas-footer border-top p-3 text-center">
                    <div class="row">
                        <div class="col">
                            <button type="button" class="btn btn-danger w-100" id="reset-layout">Reset</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ========== Topbar End ========== -->




        <!-- ========== App Menu Start ========== -->
        <div class="main-nav">
            <!-- Sidebar Logo -->
            <div class="logo-box">
                <a href="{{ route('admin.dashboard') }}" class="logo-dark">
                    <img src="{{ asset('client/images/logo-icon.png') }}" class="logo-sm" alt="logo sm">
                    <img src="{{ asset('client/images/logo.png') }}" class="logo-lg" alt="logo dark">
                </a>

                <a href="{{ route('admin.dashboard') }}" class="logo-light">

                    <img src="{{ asset('client/images/logo-icon-white.png') }}" class="logo-sm" alt="logo sm">
                    <img src="{{ asset('client/images/logo-white.png') }}" class="logo-lg" alt="logo light">
                </a>
            </div>

            <!-- Menu Toggle Button (sm-hover) -->
            <button type="button" class="button-sm-hover" aria-label="Show Full Sidebar">
                <iconify-icon icon="solar:double-alt-arrow-right-bold-duotone"
                    class="button-sm-hover-icon"></iconify-icon>
            </button>
            <div class="scrollbar" data-simplebar>
                <ul class="navbar-nav" id="navbar-nav">

                    @if (auth()->user()->isAdmin())
                        <li class="menu-title">Tổng quan</li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                <span class="nav-icon">
                                    <iconify-icon icon="solar:widget-5-bold-duotone"></iconify-icon>
                                </span>
                                <span class="nav-text"> Dashboard </span>
                            </a>
                        </li>

                        <li class="menu-title">Quản lý học tập</li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.account') }}">
                                <span class="nav-icon">
                                    <iconify-icon icon="mdi:account-group-outline"></iconify-icon>
                                </span>
                                <span class="nav-text"> Quản lý người dùng </span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.classes.index') }}">
                                <span class="nav-icon">
                                    <iconify-icon icon="mdi:google-classroom"></iconify-icon>
                                </span>
                                <span class="nav-text"> Quản lý lớp học </span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.schedules.index') }}">
                                <span class="nav-icon">
                                    <iconify-icon icon="mdi:calendar-clock-outline"></iconify-icon>
                                </span>
                                <span class="nav-text"> Quản lý lịch học </span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.course-list') }}">
                                <span class="nav-icon">
                                    <iconify-icon icon="mdi:book-education-outline"></iconify-icon>
                                </span>
                                <span class="nav-text"> Quản lý khóa học </span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.attendance.index') }}">
                                <span class="nav-icon">
                                    <iconify-icon icon="mdi:clipboard-check-outline"></iconify-icon>
                                </span>
                                <span class="nav-text"> Quản lý điểm danh </span>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.quizz') }}">
                                <span class="nav-icon">
                                    <iconify-icon icon="ph:exam-bold"></iconify-icon>
                                </span>
                                <span class="nav-text"> Quản lý quiz </span>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.score') }}">
                                <span class="nav-icon">
                                    <iconify-icon icon="mdi:chart-bar"></iconify-icon>
                                </span>
                                <span class="nav-text"> Quản lý điểm số </span>
                            </a>
                        </li>



                        <li class="menu-title">Tài chính</li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.course_payments') }}">
                                <span class="nav-icon">
                                    <iconify-icon icon="mdi:cash-multiple"></iconify-icon>
                                </span>
                                <span class="nav-text"> Học phí & Thanh toán </span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.teacher_salaries') }}">
                                <span class="nav-icon">
                                    <iconify-icon icon="mdi:cash-register"></iconify-icon>
                                </span>
                                <span class="nav-text"> Quản lý lương giáo viên </span>
                            </a>
                            <div class="collapse" id="sidebarTeacherSalaries">
                                <ul class="nav sub-navbar-nav">
                                    <li class="sub-nav-item">
                                        <a class="sub-nav-link" href="{{ route('admin.teacher_salaries') }}">Bảng
                                            lương</a>
                                    </li>
                                    <li class="sub-nav-item">
                                        <a class="sub-nav-link"
                                            href="{{ route('admin.teacher_salaries.detail') }}">Chi tiết lương GV</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="menu-title">Truyền thông & Liên hệ</li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.notifications') }}">
                                <span class="nav-icon">
                                    <iconify-icon icon="mdi:bell-outline"></iconify-icon>
                                </span>
                                <span class="nav-text"> Quản lý thông báo </span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.contact') }}">
                                <span class="nav-icon">
                                    <iconify-icon icon="mdi:email-outline"></iconify-icon>
                                </span>
                                <span class="nav-text"> Quản lý liên hệ </span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-arrow" href="#news" data-bs-toggle="collapse" role="button"
                                aria-expanded="false" aria-controls="news">
                                <span class="nav-icon">
                                    <iconify-icon icon="mdi:post-outline"></iconify-icon>
                                </span>
                                <span class="nav-text"> Quản lý bài viết </span>
                            </a>
                            <div class="collapse" id="news">
                                <ul class="nav sub-navbar-nav">
                                    <li class="sub-nav-item">
                                        <a class="sub-nav-link" href="{{ route('admin.news.index') }}">Danh sách bài
                                            viết</a>
                                    </li>
                                    <li class="sub-nav-item">
                                        <a class="sub-nav-link" href="{{ route('admin.topics.index') }}">Chủ đề</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="nav-item mt-3">
                            <a class="nav-link" href="{{ route('home') }}">
                                <span class="nav-icon">
                                    <iconify-icon icon="mdi:logout"></iconify-icon>
                                </span>
                                <span class="nav-text"> Thoát </span>
                            </a>
                        </li>
                    @elseif (auth()->user()->isUser())
                        <li class="menu-title">Tổng quan</li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                <span class="nav-icon">
                                    <iconify-icon icon="solar:widget-5-bold-duotone"></iconify-icon>
                                </span>
                                <span class="nav-text"> Dashboard </span>
                            </a>
                        </li>

                        <li class="menu-title">Quản lý học tập</li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.account') }}">
                                <span class="nav-icon">
                                    <iconify-icon icon="mdi:account-group-outline"></iconify-icon>
                                </span>
                                <span class="nav-text"> Quản lý người dùng </span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.classes.index') }}">
                                <span class="nav-icon">
                                    <iconify-icon icon="mdi:google-classroom"></iconify-icon>
                                </span>
                                <span class="nav-text"> Quản lý lớp học </span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.schedules.index') }}">
                                <span class="nav-icon">
                                    <iconify-icon icon="mdi:calendar-clock-outline"></iconify-icon>
                                </span>
                                <span class="nav-text"> Quản lý lịch học </span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.course-list') }}">
                                <span class="nav-icon">
                                    <iconify-icon icon="mdi:book-education-outline"></iconify-icon>
                                </span>
                                <span class="nav-text"> Quản lý khóa học </span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.attendance.index') }}">
                                <span class="nav-icon">
                                    <iconify-icon icon="mdi:clipboard-check-outline"></iconify-icon>
                                </span>
                                <span class="nav-text"> Quản lý điểm danh </span>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.quizz') }}">
                                <span class="nav-icon">
                                    <iconify-icon icon="ph:exam-bold"></iconify-icon>
                                </span>
                                <span class="nav-text"> Quản lý quiz </span>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.score') }}">
                                <span class="nav-icon">
                                    <iconify-icon icon="mdi:chart-bar"></iconify-icon>
                                </span>
                                <span class="nav-text"> Quản lý điểm số </span>
                            </a>
                        </li>



                        <li class="menu-title">Tài chính</li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.course_payments') }}">
                                <span class="nav-icon">
                                    <iconify-icon icon="mdi:cash-multiple"></iconify-icon>
                                </span>
                                <span class="nav-text"> Học phí & Thanh toán </span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.teacher_salaries') }}">
                                <span class="nav-icon">
                                    <iconify-icon icon="mdi:cash-register"></iconify-icon>
                                </span>
                                <span class="nav-text"> Quản lý lương giáo viên </span>
                            </a>
                            <div class="collapse" id="sidebarTeacherSalaries">
                                <ul class="nav sub-navbar-nav">
                                    <li class="sub-nav-item">
                                        <a class="sub-nav-link" href="{{ route('admin.teacher_salaries') }}">Bảng
                                            lương</a>
                                    </li>
                                    <li class="sub-nav-item">
                                        <a class="sub-nav-link"
                                            href="{{ route('admin.teacher_salaries.detail') }}">Chi tiết lương GV</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="menu-title">Truyền thông & Liên hệ</li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.notifications') }}">
                                <span class="nav-icon">
                                    <iconify-icon icon="mdi:bell-outline"></iconify-icon>
                                </span>
                                <span class="nav-text"> Quản lý thông báo </span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.contact') }}">
                                <span class="nav-icon">
                                    <iconify-icon icon="mdi:email-outline"></iconify-icon>
                                </span>
                                <span class="nav-text"> Quản lý liên hệ </span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-arrow" href="#news" data-bs-toggle="collapse" role="button"
                                aria-expanded="false" aria-controls="news">
                                <span class="nav-icon">
                                    <iconify-icon icon="mdi:post-outline"></iconify-icon>
                                </span>
                                <span class="nav-text"> Quản lý bài viết </span>
                            </a>
                            <div class="collapse" id="news">
                                <ul class="nav sub-navbar-nav">
                                    <li class="sub-nav-item">
                                        <a class="sub-nav-link" href="{{ route('admin.news.index') }}">Danh sách bài
                                            viết</a>
                                    </li>
                                    <li class="sub-nav-item">
                                        <a class="sub-nav-link" href="{{ route('admin.topics.index') }}">Chủ đề</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="nav-item mt-3">
                            <a class="nav-link" href="{{ route('home') }}">
                                <span class="nav-icon">
                                    <iconify-icon icon="mdi:logout"></iconify-icon>
                                </span>
                                <span class="nav-text"> Thoát </span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
        <!-- ========== App Menu End ========== -->


        @yield('content')



    </div>


    <!-- Spinner container (ẩn mặc định) -->
    <div id="loading-spinner"
        style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;">
        <div class="spinner-border text-info" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <!-- ========== Footer End ========== -->
    <!-- Vendor Javascript (Require in all Page) -->
    <script src="{{ asset('admin/js/vendor.js') }}"></script>

    <!-- App Javascript (Require in all Page) -->
    <script src="{{ asset('admin/js/app.js') }}"></script>

    <!-- Vector Map Js -->
    <script src="{{ asset('admin/vendor/jsvectormap/js/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('admin/vendor/jsvectormap/maps/world-merc.js') }}"></script>
    <script src="{{ asset('admin/vendor/jsvectormap/maps/world.js') }}"></script>


    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>


    <script>
        $(window).on('load', function() {
            console.log('Page loaded');
            $('#preloader').fadeOut('slow');
        });
    </script>

    @stack('scripts')



</body>

</html>
