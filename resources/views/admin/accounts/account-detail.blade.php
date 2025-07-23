@php
    $roles = [
        'student' => 'Học sinh',
        'teacher' => 'Giáo viên',
        'admin' => 'Quản trị viên',
        'staff' => 'Nhân viên',
    ];

@endphp


@extends('admin.admin')
@section('title', 'Chi tiết ' . ($roles[request('role')] ?? (request('role') ?? 'người dùng')))
@section('description', '')
@section('content')

    <div class="page-content">
        <div class="container-fluid">
            <nav aria-label="breadcrumb p-0">
                <ol class="breadcrumb py-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.account') }}">Quản lí người dùng</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('admin.account.list', request('role')) }}">{{ $roles[request('role')] ?? request('role') }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $user->name ?? 'Chi tiết người dùng' }}</li>
                </ol>
            </nav>



            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title p-3">Thông tin chi tiết {{ $roles[request('role')] ?? request('role') }} </h4>


            </div> <!-- end card-header-->

            {{-- <div class="accordion" id="accordionClasses">
                @forelse ($classes as $index => $class)
                    @php
                        $classScores = $scores[$class->id] ?? collect();
                    @endphp

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading{{ $index }}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapse{{ $index }}" aria-expanded="false"
                                aria-controls="collapse{{ $index }}">
                                Lớp: {{ $class->name }} - Khóa học: {{ $class->course_name }}
                            </button>   
                        </h2>

                        <div id="collapse{{ $index }}" class="accordion-collapse collapse"
                            aria-labelledby="heading{{ $index }}" data-bs-parent="#accordionClasses">
                            <div class="accordion-body">
                                @if ($classScores->count())
                                    <table class="table table-bordered table-sm">
                                        <thead>
                                            <tr>
                                                <th>Loại điểm</th>
                                                <th>Điểm</th>
                                                <th>Ghi chú</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($classScores as $score)
                                                <tr>
                                                    <td>{{ $score->score_type }}</td>
                                                    <td>{{ $score->score }}</td>
                                                    <td>{{ $score->note ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p class="text-muted">Chưa có điểm nào</p>
                                @endif

                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-warning text-center mt-3">
                        {{ $roles[request('role')] ?? request('role') }} này không thuộc lớp học nào.
                    </div>
                @endforelse
            </div> --}}


            <div class="accordion" id="accordionRole">
                @if ($user->role === 'student')

                    <div class="row">
                        <div class="col-6">
                            <h5 class="mb-3 d-flex align-items-center"><iconify-icon class="pe-1 text-warning"
                                    icon="mdi:progress-clock" width="30"></iconify-icon> Các lớp đang học</h5>
                            <div class="">
                                @forelse ($currentClasses as $index => $class)
                                    @php
                                        $classScores = $scores[$class->id] ?? collect();
                                    @endphp
                                    <div class="col-12">
                                        <div class="card border-success shadow-sm">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between">
                                                    <div class="div">
                                                        <h5 class="card-title">{{ $class->name }}</h5>
                                                        <p class="card-text">Khóa học: {{ $class->course_name }}</p>
                                                    </div>
                                                    <button class="btn btn-sm btn-outline-primary mt-2" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#scoresCollapse{{ $index }}" aria-expanded="false"
                                                        aria-controls="scoresCollapse{{ $index }}">
                                                        Xem điểm
                                                    </button>
                                                </div>
                                                <div class="collapse mt-3" id="scoresCollapse{{ $index }}">
                                                    @if ($classScores->count())
                                                        <table class="table table-bordered table-sm">
                                                            <thead>
                                                                <tr>
                                                                    <th>Loại điểm</th>
                                                                    <th>Điểm</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($classScores as $score)
                                                                    <tr>
                                                                        <td>{{ $score->score_type }}</td>
                                                                        <td>{{ $score->score }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    @else
                                                        <p class="text-muted">Chưa có điểm</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted">Không có lớp nào đã học</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="col-6">
                            <h5 class="mb-3 d-flex align-items-center"><iconify-icon class="pe-1 text-success"
                                    icon="mdi:check-circle-outline" width="30"></iconify-icon> Các lớp học đã hoàn thành</h5>
                            <div class="">
                                @forelse ($finishedClasses as $index => $class)
                                    @php
                                        $classScores = $scores[$class->id] ?? collect();
                                    @endphp
                                    <div class="col-12">
                                        <div class="card border-success shadow-sm">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between">
                                                    <div class="div">
                                                        <h5 class="card-title">{{ $class->name }}</h5>
                                                        <p class="card-text">Khóa học: {{ $class->course_name }}</p>
                                                    </div>
                                                    <button class="btn btn-sm btn-outline-primary mt-2" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#scoresCollapse{{ $index }}" aria-expanded="false"
                                                        aria-controls="scoresCollapse{{ $index }}">
                                                        Xem điểm
                                                    </button>
                                                </div>
                                                <div class="collapse mt-3" id="scoresCollapse{{ $index }}">
                                                    @if ($classScores->count())
                                                        <table class="table table-bordered table-sm">
                                                            <thead>
                                                                <tr>
                                                                    <th>Loại điểm</th>
                                                                    <th>Điểm</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($classScores as $score)
                                                                    <tr>
                                                                        <td>{{ $score->score_type }}</td>
                                                                        <td>{{ $score->score }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    @else
                                                        <p class="text-muted">Chưa có điểm</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted">Không có lớp nào đã học</p>
                                @endforelse
                            </div>
                        </div>
                    </div>


                @elseif ($user->role === 'teacher')

                    <div class="row">
                        <div class="col-6">
                            <h5 class="d-flex align-items-center"><iconify-icon class="pe-1 text-warning"
                                    icon="mdi:progress-clock" width="30"></iconify-icon> Các lớp đang dạy</h5>
                            <div class="col-12">
                                @forelse ($teachingClasses as $class)
                                    @php
                                        $studentCount = $countStudent[$class->id] ?? 0;
                                        $scheduleCount = $schedules->where('class_id', $class->id)->count();
                                    @endphp
                                    <div class="col">
                                        <div class="card border-success shadow-sm h-100">
                                            <div class="card-body d-flex flex-column justify-content-between">
                                                <div>
                                                    <div class="d-flex justify-content-between">
                                                        <h5 class="card-title d-flex align-items-center gap-2">
                                                            <iconify-icon icon="ph:chalkboard-teacher"
                                                                width="20"></iconify-icon>
                                                            {{ $class->name }}
                                                        </h5>
                                                        <p class="mb-1">
                                                            <iconify-icon icon="mdi:book-open-page-variant" width="18"
                                                                class="me-1 "></iconify-icon>
                                                            Khóa học: <strong>{{ $class->course_name }}</strong>
                                                        </p>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <p class="mb-1">
                                                            <iconify-icon icon="mdi:account-group-outline" width="18"
                                                                class="me-1"></iconify-icon>
                                                            Sĩ số: <strong>{{ $countStudent[$class->id] ?? 0 }}</strong>
                                                        </p>
                                                        @php
                                                            $done = $class->number_of_sessions;
                                                            $total = $class->total_sessions;
                                                            $percent = $total > 0 ? round(($done / $total) * 100) : 0;
                                                        @endphp
                                                        <p class="mb-1">
                                                            <iconify-icon icon="mdi:calendar-clock" width="18"
                                                                class="me-1"></iconify-icon>
                                                            Số buổi: <strong>{{ $done }} / {{ $total }}</strong>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar bg-success" role="progressbar"
                                                            style="width: {{ $percent }}%"
                                                            aria-valuenow="{{ $percent }}" aria-valuemin="0"
                                                            aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                    <small class="text-muted">{{ $percent }}% hoàn thành</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted">Không có lớp nào đang dạy</p>
                                @endforelse
                            </div>
                        </div>
                        <div class="col-6">
                            <h5 class=" d-flex align-items-center"><iconify-icon class="pe-1 text-success"
                                    icon="mdi:check-circle-outline" width="30"></iconify-icon> Các lớp đã dạy</h5>
                            <div class="col-12">
                                @forelse ($taughtClasses as $class)
                                    @php
                                        $studentCount = $countStudent[$class->id] ?? 0;
                                        $scheduleCount = $schedules->where('class_id', $class->id)->count();
                                    @endphp
                                    <div class="col">
                                        <div class="card border-success shadow-sm h-100">
                                            <div class="card-body d-flex flex-column justify-content-between">
                                                <div>
                                                    <div class="d-flex justify-content-between">
                                                        <h5 class="card-title d-flex align-items-center gap-2">
                                                            <iconify-icon icon="ph:chalkboard-teacher"
                                                                width="20"></iconify-icon>
                                                            {{ $class->name }}
                                                        </h5>
                                                        <p class="mb-1">
                                                            <iconify-icon icon="mdi:book-open-page-variant" width="18"
                                                                class="me-1"></iconify-icon>
                                                            Khóa học: <strong>{{ $class->course_name }}</strong>
                                                        </p>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <p class="mb-1">
                                                            <iconify-icon icon="mdi:account-group-outline" width="18"
                                                                class="me-1"></iconify-icon>
                                                            Sĩ số: <strong>{{ $countStudent[$class->id] ?? 0 }}</strong>
                                                        </p>
                                                        @php
                                                            $done = $class->number_of_sessions;
                                                            $total = $class->total_sessions;
                                                            $percent = $total > 0 ? round(($done / $total) * 100) : 0;
                                                        @endphp
                                                        <p class="mb-1">
                                                            <iconify-icon icon="mdi:calendar-clock" width="18"
                                                                class="me-1"></iconify-icon>
                                                            Số buổi: <strong>{{ $done }} / {{ $total }}</strong>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar bg-success" role="progressbar"
                                                            style="width: {{ $percent }}%"
                                                            aria-valuenow="{{ $percent }}" aria-valuemin="0"
                                                            aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                    <small class="text-muted">{{ $percent }}% hoàn thành</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted">Không có lớp nào đã dạy</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info text-center mt-3">
                        Vai trò quản trị không có lớp học để hiển thị.
                    </div>
                @endif
            </div>
        </div>
        <!-- end row -->
        <!-- End Container Fluid -->
        <!-- ========== Footer Start ========== -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 text-center">
                        <script>
                            document.write(new Date().getFullYear())
                        </script> &copy; DỰ ÁN TỐT NGHIỆP CAO ĐẲNG FPT THANH HÓA<iconify-icon
                            icon="iconamoon:heart-duotone" class="fs-18 align-middle text-danger"></iconify-icon> <a
                            href="#" class="fw-bold footer-text" target="_blank">NHÓM 4</a>
                    </div>
                </div>
            </div>
        </footer>

    </div>

    <script></script>



@endsection
