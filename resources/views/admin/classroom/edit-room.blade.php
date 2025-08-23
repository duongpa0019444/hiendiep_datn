@extends('admin.admin')

@section('title', 'Sửa phòng học')
@section('description', '')

@section('content')
<div class="page-content">
    <form action="{{ route('admin.classroom.update', $classroom->id) }}" method="POST">
        @csrf
        @method('PUT')
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
                            <li class="breadcrumb-item"><a href="{{ route('admin.classroom.list-room') }}">Quản Lý Phòng Học</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Sửa Phòng Học</li>
                        </ol>
                    </nav>
                </div>

                <!-- Form sửa phòng -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Thông tin phòng học</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <!-- Tên phòng học (readonly) -->
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="room_name" class="form-label">Tên phòng học</label>
                                    <input type="text" id="room_name" class="form-control" 
                                           value="{{ $classroom->room_name }}" readonly>
                                </div>
                            </div>

                            <!-- Trạng thái -->
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Trạng thái</label>
                                    <select name="status" id="status" class="form-select">
                                        <option value="0" {{ $classroom->status == 0 ? 'selected' : '' }}>Đang được sử dụng</option>
                                        <option value="1" {{ $classroom->status == 1 ? 'selected' : '' }}>Chưa được sử dụng</option>
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
                                           value="{{ old('Capacity', $classroom->Capacity) }}" placeholder="Nhập sức chứa học sinh">
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
                            <button type="submit" class="btn btn-outline-secondary w-100">Lưu</button>
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
@endsection
