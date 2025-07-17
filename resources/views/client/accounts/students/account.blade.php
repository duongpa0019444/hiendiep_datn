@extends('client.accounts.information')

@section('content-information')
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <div id="account" class="content-section">
        @if (session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Toastify({
                        text: "{{ session('success') }}",
                        gravity: "top",
                        position: "center",
                        className: "success",
                        duration: 4000
                    }).showToast();
                });
            </script>
        @endif


        <div class="d-flex flex-column pb-2 pb-md-4 flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
            <h5 class="mb-0">Thông Tin Tài Khoản</h5>
            <form action="{{ route('client.account.edit') }}" method="GET">
                <input type="hidden" name="id" value="{{ $user->id }}">
                <button type="submit" style="background: var(--primary-gradient);"
                    class="btn btn-primary active btn-sm btn-md">
                    <i class="icofont-pencil-alt-2"></i> Chỉnh Sửa
                </button>
            </form>
        </div>


        <div class="row gx-3 gy-3 ffs-5 fs-md-6">
            <div class="col-12 col-md-4 text-center pe-4">
                <img src="{{ asset($user->avatar) }}" class="rounded-circle img-fluid border" alt="Ảnh đại diện">
                <div class="p-2 p-md-4 fw-semibold  ">{{ $user->name }}</div>
            </div>
            <div class="col-12 col-md-8">
                <div class="card-body">

                    <ul class="list-group">
                        <li class="list-group-item notification-item">
                            <div class="notification-content">

                                <div class="d-flex justify-content-between text-muted align-items-center">
                                    <span class="d-flex align-items-center">
                                        <i class="icofont-graduate me-2 display-6"></i>Tất cả khóa học đã đăng ký:</span>
                                    <span>{{ $courses }}</span>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item notification-item">
                            <div class="notification-content">

                                <div class="d-flex justify-content-between text-muted align-items-center">
                                    <span class="d-flex align-items-center">
                                       <i class="icofont-ui-play me-2 display-6"></i>Khóa Đang Học:</span>
                                    <span>{{ $inProgressCourseNames }}</span>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item notification-item">
                            <div class="notification-content">

                                <div class="d-flex justify-content-between text-muted align-items-center">
                                    <span class="d-flex align-items-center">
                                        <i class="icofont-check-alt me-2 display-6"></i>Khóa Đã Hoàn Thành:</span>
                                    <span>{{ $completedCourseNames }}</span>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item notification-item">
                            <div class="notification-content">

                                <div class="d-flex justify-content-between text-muted align-items-center">
                                    <span class="d-flex align-items-center"><i class="icofont-birthday-cake me-2 display-6">
                                        </i>Ngày Sinh:</span>
                                    <span>{{ \Carbon\Carbon::parse($user->birth_date)->format('d/m/Y') }}</span>

                                </div>
                            </div>
                        </li>

                        <li class="list-group-item notification-item">
                            <div class="notification-content">

                                <div class="d-flex justify-content-between text-muted align-items-center">
                                    <span class="d-flex align-items-center">
                                        <i class="icofont-boy me-2 display-6"></i>Giới tính:</span>
                                    <span>{{ $user->gender }}</span>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item notification-item">
                            <div class="notification-content">

                                <div class="d-flex justify-content-between text-muted align-items-center">
                                    <span class="d-flex align-items-center">
                                        <i class="icofont-email me-2 display-6"></i>Email:</span>
                                    <span>{{ $user->email }}</span>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item notification-item">
                            <div class="notification-content">

                                <div class="d-flex justify-content-between text-muted align-items-center">
                                    <span class="d-flex align-items-center">
                                        <i class="icofont-phone-circle me-2 display-6"></i>Số điện thoại:</span>
                                    <span>{{ $user->phone }}</span>
                                </div>
                            </div>
                        </li>
                    </ul>

                </div>
            </div>
        </div>

    
       
    </div>
@endsection
