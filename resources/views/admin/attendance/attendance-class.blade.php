@extends('admin.admin')

@section('title', 'Chưa làm gì')
@section('description', 'Quản lý điểm danh cho các lớp học')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- FullCalendar CSS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />
    <style>
        
    </style>
@endpush

@section('content')
    <div class="page-content">
        <div class="container-xxl">
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="mb-1">Quản lý điểm danh</h3>
                    <p class="text-muted mb-0">Lịch học và điểm danh theo thời gian</p>
                </div>
                <div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEventModal">
                        <i class="fas fa-plus me-2"></i>Thêm sự kiện
                    </button>
                </div>
            </div>

            <!-- Alert Messages -->
            @if (session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công!',
                            text: '{{ session('success') }}',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    });
                </script>
            @endif

            @if (session('error'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi!',
                            text: '{{ session('error') }}',
                            timer: 2500,
                            showConfirmButton: false
                        });
                    });
                </script>
            @endif
        </div>
    </div>
    
@endsection

@push('scripts')
    {{-- <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script> --}}
    <!-- FullCalendar JS -->
    {{-- <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script> --}}
    <script>
       
    </script>
@endpush
