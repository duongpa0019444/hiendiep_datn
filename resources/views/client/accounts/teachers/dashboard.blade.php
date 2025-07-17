@extends('client.accounts.information')

@section('content-information')
<div id="dashboard" class="content-section active mb-4">
    <h2 class="fs-5">
        <i class="icofont-user-alt-4 me-2 text-primary"></i>
        Chào mừng {{ Auth::user()->name }} quay trở lại!
    </h2>

    <!-- Notifications Section -->
    <div class="card mb-2">
        <div class="card-header">
            <i class="icofont-notification me-2 text-white"></i>Thông Báo
        </div>
        <div class="card-body">
            <ul class="list-group">
                <li class="list-group-item notification-item d-flex gap-2">
                    <i class="icofont-warning text-warning fs-5 mt-1"></i>
                    <div class="notification-content">
                        <strong>Thay đổi lịch học</strong> - {{ now()->subDays(2)->format('d/m/Y') }}
                        <p class="mb-0 text-muted">Lớp A1-1 sẽ chuyển sang giờ học mới: Thứ 3, 5, 7 - 18:00-20:00.</p>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <!-- Lịch dạy hôm nay -->
    <div class="card shadow-sm mb-3">
        <div class="card-header bg-light d-flex justify-content-between align-items-center py-2 px-3">
            <h6 class="mb-0 fw-semibold text-white d-flex align-items-center">
                <i class="icofont-calendar me-2"></i> Lịch dạy hôm nay
            </h6>
            <span class="badge badge-completed">{{ now()->format('d/m/Y') }}</span>
        </div>
        <div class="card-body p-3">
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex align-items-start gap-3 py-2">
                    <div class="text-primary fs-5">
                        <i class="icofont-teacher"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-bold d-flex align-items-center">
                            <i class="icofont-book-alt me-2 text-secondary"></i> Lớp A1-1
                        </div>
                        <div class="d-flex align-items-center text-muted small mt-1">
                            <i class="icofont-clock-time me-2"></i> Thời gian: 18:00 - 20:00
                        </div>
                        <div class="d-flex align-items-center text-muted small">
                            <i class="icofont-calendar me-2"></i> Lịch: Thứ 3, 5, 7
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <!-- Current Class Section -->
    <div class="row mb-2">
        <!-- Lịch dạy sắp tới -->
        <div class="col-md-6">
            <div class="card completed-class">
                <div class="card-header">
                    <i class="icofont-ui-calendar me-2 text-white"></i> Lịch dạy sắp tới
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="icofont-book-mark text-secondary me-2"></i>
                                <strong>Lớp IELSE</strong>: 15/10/2024
                            </div>
                            <span class="badge badge-pending">
                                <i class="icofont-clock-time me-1"></i> 08:45 - 10:45
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="icofont-book-mark text-secondary me-2"></i>
                                <strong>Lớp IELSE</strong>: 15/10/2024
                            </div>
                            <span class="badge badge-pending">
                                <i class="icofont-clock-time me-1"></i> 08:45 - 10:45
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="icofont-book-mark text-secondary me-2"></i>
                                <strong>Lớp IELSE</strong>: 15/10/2024
                            </div>
                            <span class="badge badge-pending">
                                <i class="icofont-clock-time me-1"></i> 08:45 - 10:45
                            </span>
                        </li>
                    </ul>
                    <a href="#" class="btn-infomation-myaccount btn-sm mt-3">
                        <i class="icofont-eye me-1"></i> Xem tất cả
                    </a>
                </div>
            </div>
        </div>

        <!-- Tổng quan -->
        <div class="col-md-6">
            <div class="card completed-class">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="icofont-chart-bar-graph me-2 text-white"></i> Tổng quan</span>
                    <div class="btn-group btn-group-sm" role="group" aria-label="Chuyển tháng">
                        <button type="button" class="btn btn-primary" id="prevMonth">
                            <i class="icofont-rounded-left"></i>
                        </button>
                        <span class="btn btn-light" id="currentMonth">{{ \Carbon\Carbon::now()->format('m/Y') }}</span>
                        <button type="button" class="btn btn-primary" id="nextMonth">
                            <i class="icofont-rounded-right"></i>
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-4">
                            <div class="card text-center h-100 border-0 shadow-sm border-top border-primary">
                                <div class="card-body p-3">
                                    <i class="icofont-graduate-alt fs-4 text-primary mb-2"></i>
                                    <strong>Tổng số lớp dạy</strong>
                                    <div class="badge badge-completed fw-bold mt-1">5</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="card text-center h-100 border-0 shadow-sm border-top border-success">
                                <div class="card-body p-3">
                                    <i class="icofont-check-circled fs-4 text-success mb-2"></i>
                                    <strong>Buổi đã dạy</strong>
                                    <div class="badge badge-completed fw-bold mt-1">20</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="card text-center h-100 border-0 shadow-sm border-top border-warning">
                                <div class="card-body p-3">
                                    <i class="icofont-users-social fs-4 text-warning mb-2"></i>
                                    <strong>Tổng học sinh</strong>
                                    <div class="badge badge-completed fw-bold mt-1">45</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
