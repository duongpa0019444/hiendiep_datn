@extends('client.accounts.information')

@section('content-information')
    <div id="dashboard" class="content-section active mb-4">
        <h2 class="fs-5">Chào mừng {{ Auth::user()->name }} quay trở lại!</h2>


        <!-- Notifications Section -->
        <div class="card mb-5">
            <div class="card-header">Thông Báo</div>
            <div class="card-body">
                <ul class="list-group">
                    @if (count($unPaymentInfo) > 0)
                        <li class="list-group-item notification-item d-flex justify-content-between align-items-center flex-wrap"
                            id="infomation-payment" style="cursor: pointer">
                            <div class="notification-content">
                                <strong class="text-danger">Thông báo đóng học phí<i class="fas fa-qrcode"></i></strong>
                                <p class="mb-0 text-muted">Vui lòng thanh toán học phí cho khóa hiện tại trước ngày
                                    {{ now()->addDays(7)->format('d/m/Y') }}.</p>
                            </div>
                            <span class="qr-code-button text-primary btn-showQr-payment">Hoàn thành <i
                                    class='fi fi-ss-QR'></i></span>
                        </li>
                    @endif

                   @forelse ($notifications as $noti)
                        <li class="list-group-item notification-item">
                            <div class="notification-content">
                                <h5 class="mb-1 text-primary fw-bold">{{ $noti->title }}</h5>
                                <div class="mb-2">{!! $noti->content !!}</div>
                                <div class="d-flex justify-content-between text-muted small">
                                    <span>Người gửi: {{ $noti->creator->name ?? 'Hệ thống' }}</span>
                                    <span>{{ $noti->created_at->format('H:i d/m/Y') }}</span>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item">Không có thông báo nào.</li>
                    @endforelse
                </ul>
                <!-- Phân trang -->
                {{-- <div class="mt-3">
                    {{ $notifications->links() }}
                </div> --}}
            </div>
        </div>

    </div>
@endsection
