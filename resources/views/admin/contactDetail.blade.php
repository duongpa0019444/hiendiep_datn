@extends('admin.admin')

@section('title', 'Trang admin')
@section('description', '')
@section('content')


    <div class="page-content">
        <div class="container-xxl">
            <nav aria-label="breadcrumb p-0">
                <ol class="breadcrumb py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.contact') }}">Quản lí liên hệ</a></li>
                    <li class="breadcrumb-item active">Chi tiết liên hệ</li>
                </ol>
            </nav>

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

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Loại tin nhắn</label>
                                    <input type="text" class="form-control" value="{{ $contact->pl_content }}" readonly>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Nhân viên hỗ trợ</label>
                                    <input type="text" class="form-control"
                                        value="{{ optional($contact->staff)->name ?? 'Chưa có' }}" readonly>
                                </div>
                            </div>

                            <div class="col-lg-6">
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
                                    <a href="{{ route('admin.contact') }}" class="btn btn-primary w-100">
                                        Trở về danh sách liên hệ
                                    </a>
                                </div>
                                <div class="col-lg-2">
                                    <button type="submit" id="btn-approve" data-status="{{ $contact->status }}"
                                        class="btn btn-success w-100">
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

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const approveForm = document.getElementById('approveForm');
            const approveBtn = document.getElementById('btn-approve');
            const rejectBtn = document.getElementById('btn-reject');

            // Kiểm tra trước khi gửi form (Đồng ý hỗ trợ)
            approveForm.addEventListener('submit', function(e) {
                const status = parseInt(approveBtn.getAttribute('data-status'));
                if (status === 1) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Cảnh báo',
                        text: 'Tin nhắn này đã được xử lý bởi người khác.',
                        confirmButtonText: 'Đóng'
                    });
                }
            });

            // Kiểm tra trước khi chuyển trang (Không hỗ trợ)
            rejectBtn.addEventListener('click', function(e) {
                const status = parseInt(rejectBtn.getAttribute('data-status'));
                if (status === 1) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Cảnh báo',
                        text: 'Tin nhắn này đã được xử lý bởi người khác.',
                        confirmButtonText: 'Đóng'
                    });
                }
            });
        });
    </script>

@endsection
