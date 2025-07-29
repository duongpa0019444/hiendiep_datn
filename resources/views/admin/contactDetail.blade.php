@extends('admin.admin')

@section('title', 'Trang admin')
@section('description', '')
@section('content')

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
        <div class="container-xxl">
            <div class="d-flex  align-items-center justify-content-between mb-1">
                <nav aria-label="breadcrumb p-0">
                    <ol class="breadcrumb py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.contact') }}">Quản lí liên hệ</a></li>
                        <li class="breadcrumb-item active">Chi tiết liên hệ</li>
                    </ol>
                </nav>
                <a href="{{ route('admin.contact') }}" class="btn btn-secondary">
                    <iconify-icon icon="mdi:arrow-left" class="me-1"></iconify-icon>
                    Quay lại danh sách
                </a>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Chi tiết thông tin và nội dung cần hỗ trợ</h4>
                </div>
                <div class="card-body">
                    <form id="approveForm" action="{{ route('admin.contact.approve', ['id' => $contact->id]) }}"
                        method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Tên người gửi</label>
                                    <input type="text" class="form-control" value="{{ $contact->name }}" readonly>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Số điện thoại</label>
                                    <input type="text" class="form-control" value="{{ $contact->phone }}" readonly>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">Loại tin nhắn</label>
                                    <input type="text" class="form-control" value="{{ $contact->pl_content }}" readonly>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">Nhân viên hỗ trợ</label>
                                    <input type="text" class="form-control"
                                        value="{{ optional($contact->staff)->name ?? 'Chưa có' }}" readonly>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label">Ngày gửi tin nhắn</label>
                                    <input type="text" class="form-control" value="{{ $contact->created_at }}" readonly>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Nội dung tin nhắn</label>
                                    <textarea class="form-control bg-light-subtle" rows="6" readonly>{{ $contact->message }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="p-3 bg-light mb-3 rounded mt-4">
                            <div class="row justify-content-end g-2">
                                <div class="col-lg-2">
                                    <a href="{{ route('admin.contact') }}" class="btn btn-secondary w-100">
                                        <iconify-icon icon="mdi:arrow-left" class="me-1"></iconify-icon>
                                        Quay lại danh sách
                                    </a>
                                </div>

                                <div class="col-lg-2">
                                    <button type="submit" id="btn-approve" class="btn btn-success w-100">
                                        <iconify-icon icon="mdi:check-circle-outline" class="me-1"></iconify-icon>
                                        Đồng ý hỗ trợ
                                    </button>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



@endsection
