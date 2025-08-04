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


        <div
            class="d-flex flex-column pb-2 pb-md-4 flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
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
                        @php
                            // Xử lý tên các lớp học nếu có nhiều hơn 2 lớp
                            function limitClassNames($collection, $limit = 2)
                            {
                                $names =
                                    $collection instanceof \Illuminate\Support\Collection
                                        ? $collection
                                        : collect($collection);
                                $total = $names->count();
                                $sliced = $names->take($limit);
                                $short = $sliced->implode(', ');
                                if ($total > $limit) {
                                    $short .= ' ...(+' . ($total - $limit) . ' lớp nữa)';
                                }
                                return [
                                    'short' => $short,
                                    'full' => $names->implode(', '),
                                ];
                            }

                            $all = limitClassNames($classNames);
                            $inProgress = limitClassNames($inProgressClasses);
                            $completed = limitClassNames($completedClasses);
                        @endphp

                        <li class="list-group-item notification-item">
                            <div class="notification-content">
                                <div class="d-flex justify-content-between text-muted align-items-center">
                                    <span class="d-flex align-items-center">
                                        <i class="icofont-graduate me-2 "></i>Tất cả các lớp học:
                                    </span>
                                    <span title="{{ $all['full'] }}">{{ $all['short'] }}</span>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item notification-item">
                            <div class="notification-content">
                                <div class="d-flex justify-content-between text-muted align-items-center">
                                    <span class="d-flex align-items-center">
                                        <i class="icofont-ui-play me-2 "></i>Các lớp học đang dạy:
                                    </span>
                                    <span title="{{ $inProgress['full'] }}">{{ $inProgress['short'] }}</span>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item notification-item">
                            <div class="notification-content">
                                <div class="d-flex justify-content-between text-muted align-items-center">
                                    <span class="d-flex align-items-center">
                                        <i class="icofont-check-alt me-2 "></i>Các lớp học đã hoàn thành:
                                    </span>
                                    <span title="{{ $completed['full'] }}">{{ $completed['short'] }}</span>
                                </div>
                            </div>
                        </li>


                        <li class="list-group-item notification-item">
                            <div class="notification-content">

                                <div class="d-flex justify-content-between text-muted align-items-center">
                                    <span class="d-flex align-items-center"><i class="icofont-birthday-cake me-2 ">
                                        </i>Ngày Sinh:</span>
                                    <span>{{ \Carbon\Carbon::parse($user->birth_date)->format('d/m/Y') }}</span>

                                </div>
                            </div>
                        </li>

                        <li class="list-group-item notification-item">
                            <div class="notification-content">

                                <div class="d-flex justify-content-between text-muted align-items-center">
                                    <span class="d-flex align-items-center">
                                        <i class="icofont-boy me-2 "></i>Giới tính:</span>
                                    <span>
                                        {{ $user->gender === 'girl' ? 'Nữ' : ($user->gender === 'boy' ? 'Nam' : 'Khác') }}
                                    </span>

                                </div>
                            </div>
                        </li>

                        <li class="list-group-item notification-item">
                            <div class="notification-content">

                                <div class="d-flex justify-content-between text-muted align-items-center">
                                    <span class="d-flex align-items-center">
                                        <i class="icofont-email me-2 "></i>Email:</span>
                                    <span>{{ $user->email }}</span>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item notification-item">
                            <div class="notification-content">

                                <div class="d-flex justify-content-between text-muted align-items-center">
                                    <span class="d-flex align-items-center">
                                        <i class="icofont-phone-circle me-2 "></i>Số điện thoại:</span>
                                    <span>{{ $user->phone }}</span>
                                </div>
                            </div>
                        </li>

                        <li class="list-group-item notification-item">
                            <div class="notification-content">

                                <div class="d-flex justify-content-between text-muted align-items-center">
                                    <span class="d-flex align-items-center">
                                        <i class="icofont-address-circle me-2 "></i>Địa chỉ:</span>
                                    <span>{{ $user->address }}</span>
                                </div>
                            </div>
                        </li>
                    </ul>

                </div>
            </div>
        </div>



    </div>
@endsection
