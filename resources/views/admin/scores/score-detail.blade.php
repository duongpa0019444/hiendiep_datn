@extends('admin.admin')
@section('title', 'Bảng điểm')
@section('description', '')
@section('content')

    <div class="page-content">
        <div class="container-fluid">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="row">
                
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Bảng điểm </h4>
                        <div>
                            <a href="{{ route('admin.score.add', [request('class_id')]) }}" class="btn btn-sm btn-primary">
                                Nhập điểm mới
                            </a>
                            <a href="{{ route('admin.scores.export', [request('class_id')  , request('course_id')]) }}" class="btn btn-sm btn-success">
                                Xuất Excel  
                            </a>
                        </div>

                    </div> <!-- end card-header-->
                    <div class="card-body p-0">
                        <div class="px-3">
                            <table class="table table-hover mb-0 table-centered">
                                <thead>
                                    <tr>
                                        <th>Họ Tên</th>
                                        <th>Lớp</th>
                                        <th>Khóa học</th>
                                        <th>Loại Điểm</th>
                                        <th>Điểm</th>
                                        <th>Ngày</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $score)
                                    
                                        <tr>
                                            {{-- sử lí bảng score và thêm modol score vào --}}
                                            <td>{{ $score->student->name }}</td>
                                            <td>{{ $score->class->name }}</td>
                                            <td>{{ $score->class->course->name }}</td>
                                            <td>{{ $score->score_type }}</td> {{-- làm hàm trong model score --}}
                                            <td>{{ $score->score }}</td>
                                            <td>{{ \Carbon\Carbon::parse($score->exam_date)->format('d/m/Y') }}</td>
                                            <td>
                                                <div class="d-flex gap-2">

                                                    <a href="{{ route('admin.score.edit', ['class_id' => request('class_id'), 'id' => $score->id]) }}"
                                                        class="btn btn-soft-primary btn-sm"><iconify-icon
                                                            icon="solar:pen-2-broken"
                                                            class="align-middle fs-18"></iconify-icon></a>
                                                    <a href="{{ route('admin.score.delete', ['id' => $score->id ]) }}"
                                                        class="btn btn-soft-danger btn-sm"
                                                        onclick="return confirm('Bạn có muốn xóa {{ $score->scoreTypeVN() }} của học sinh {{ $score->student->name }} ?')">
                                                        <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"
                                                            class="align-middle fs-18"></iconify-icon></a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div> <!-- end card body -->
                    <div class="card-footer border-top">
                        <nav aria-label="Page navigation">
                            {!! $data->links('pagination::bootstrap-5') !!}
                        </nav>
                    </div>
                </div>

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

@endsection
