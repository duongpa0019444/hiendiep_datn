@extends('admin.admin')

@section('title', 'Thêm phòng học')
@section('description', '')


@section('content')
    <div class="page-content">
        <form action="{{ route('admin.classroom.store') }}" method="POST">
            @csrf
            <div class="container-xxl">
                <div class="row">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="col-xl-12 col-lg-10">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb py-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.classroom.list-room') }}">Quản Lý Phòng
                                        Học</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Thêm Phòng Học</li>
                            </ol>
                        </nav>
                    </div>

                    <!-- Form thêm phòng -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Thông tin phòng học</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">

                                <!-- Tên phòng học -->
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="room_name" class="form-label">Tên phòng học</label>
                                        <input type="text" name="room_name" id="room_name" class="form-control"
                                            value="{{ old('room_name') }}" placeholder="Nhập tên phòng học">
                                        @error('room_name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Trạng thái -->
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Trạng thái</label>
                                        <select name="status" id="status" class="form-select">
                                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Đang được sử
                                                dụng</option>
                                            <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Chưa được sử
                                                dụng</option>
                                        </select>

                                        @error('status')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!-- Sức chứa -->
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="Capacity" class="form-label">Sức chứa</label>
                                        <input type="number" name="Capacity" id="Capacity" class="form-control"
                                            value="{{ old('Capacity') }}" placeholder="Nhập sức chứa học sinh ">
                                        @error('Capacity')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>



                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="p-3 bg-light mb-3 rounded">
                          
                        <div class="row justify-content-end g-2">
                            <div class="col-lg-2">
                                <button type="reset" class="btn btn-primary w-100">Xóa dữ liệu đã nhập</button>
                            </div>
                            <div class="col-lg-2">
                                <button type="submit" class="btn btn-outline-secondary w-100">Lưu </button>
                            </div>

                        </div>
                       

                    </div>
                     <div class="mt-3">
                                <a href="{{ route('admin.classroom.list-room') }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bx bx-arrow-back"></i> Trở về trang Quản Lý Phòng
                                </a>
                            </div>

                   

                </div>
            </div>
        </form>
    </div>

    {{-- JS xử lý tự động thay đổi trạng thái --}}
    <script>
        document.getElementById('class_id').addEventListener('change', function() {
            let statusSelect = document.getElementById('status');
            let startTimeInput = document.getElementById('start_time');
            let endTimeInput = document.getElementById('end_time');

            if (this.value) {
                statusSelect.value = "1"; // Đang có lớp học
                // Gọi API lấy giờ học
                fetch(`/admin/classroom/get-class-times/${this.value}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.start_time && data.end_time) {
                            startTimeInput.value = data.start_time.substring(0, 5); // hh:mm
                            endTimeInput.value = data.end_time.substring(0, 5);
                        }
                    })
                    .catch(err => console.error(err));
            } else {
                statusSelect.value = "0";
                startTimeInput.value = '';
                endTimeInput.value = '';
            }
        });
    </script>
@endsection
