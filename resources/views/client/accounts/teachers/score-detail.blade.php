@extends('client.accounts.information')

@section('content-information')
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <div id="grades" class="content-section">


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

        @if (session('import_errors'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const allErrors = `{!! addslashes(implode("\n", session('import_errors'))) !!}`;
                    Toastify({
                        text: allErrors,
                        gravity: "top",
                        position: "center",
                        duration: 6000,
                        style: {
                            background: "linear-gradient(to right, #ff0000, #cc0000)",
                            color: "#fff",
                            whiteSpace: "pre-line" // cho xuống dòng
                        }
                    }).showToast();
                });
            </script>
        @endif



        <div class="d-flex flex-wrap gap-2 justify-content-between">

            <div class="">
                <h5 class="">Bảng Điểm </h5>
            </div>
            <div class="">
                <a href="{{ route('client.score.download', ['class_id' => request('class_id'), 'course_id' => request('course_id')]) }}"
                    class="btn  btn-secondary">
                    Tải mẫu nhập điểm
                </a>

                <a href="{{ route('client.score.add', [request('class_id')]) }}" class="btn  btn-primary">
                    Nhập điểm mới
                </a>
                <a href="{{ route('client.scores.export', [request('class_id'), request('course_id')]) }}"
                    class="btn  btn-success">
                    Xuất điểm Excel
                </a>



            </div>
        </div>

        <div class="d-flex flex-column flex-md-row justify-content-between gap-5 mt-3">

            {{-- Form Import --}}
            <form action="{{ route('client.scores.import') }}" method="POST" enctype="multipart/form-data"
                class="flex-grow-1">
                @csrf
                <div class="d-flex flex-column flex-sm-row gap-2 align-items-sm-center">
                    <input type="file" name="file" accept=".xlsx,.xls" class="" required>
                    <button type="submit" class="btn btn-warning">Nhập Excel</button>

                </div>
            </form>

            {{-- Form Search --}}
            <form method="GET"
                action="{{ route('client.score.detailSearch', [request('class_id'), request('course_id')]) }}"
                class="flex-grow-1">
                <div class="position-relative">
                    <input type="search" name="searchScoreStudent" class="form-control form-control-sm ps-5"
                        placeholder="Tìm mã hoặc tên học sinh..." autocomplete="off"
                        value="{{ request()->query('searchScoreStudent') ?? '' }}">
                    <i class="icofont-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                </div>
            </form>

        </div>


        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 table-centered">

                    <thead>
                        <tr>
                            <th>Mã học sinh</th>
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
                        @forelse ($data as $score)
                            <tr>
                                {{-- sử lí bảng score và thêm modol score vào --}}
                                <td>{{ $score->student->snake_case }}</td>
                                <td>{{ $score->student->name }}</td>
                                <td>{{ $score->class->name }}</td>
                                <td>{{ $score->class->course->name }}</td>
                                <td>{{ $score->score_type }}</td> {{-- làm hàm trong model score --}}
                                <td>{{ $score->score }}</td>
                                <td>{{ \Carbon\Carbon::parse($score->exam_date)->format('d/m/Y') }}</td>
                                <td>
                                    <div class="d-flex">

                                        <a href="{{ route('client.score.edit', ['class_id' => request('class_id'), 'id' => $score->id]) }}"
                                            class="btn btn-soft-primary "><i class="icofont-pencil-alt-2"></i></a>
                                        <a href="#" class="btn btn-soft-danger "
                                            onclick="showDeleteConfirm({{ $score->id }}, '{{ $score->student->name }}', '{{ $score->score_type }}')">
                                            <i class="icofont-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Không có điểm nào được tìm thấy.</td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div> <!-- end card body -->


        {{-- phân trang --}}
        <div class="row">
            <div class="col-12">
                <div class="ed-pagination">
                    <ul class="ed-pagination__list">
                        {{-- Trang trước --}}
                        @if ($data->hasPages())
                            <ul class="ed-pagination__list">
                                {{-- Prev --}}
                                @if ($data->onFirstPage())
                                    <li class="disabled"><span><i class="fi-rr-arrow-small-left"></i></span></li>
                                @else
                                    <li><a
                                            href="{{ $data->previousPageUrl() }}&client_course_search={{ request('client_course_search') }}"><i
                                                class="fi-rr-arrow-small-left"></i></a></li>
                                @endif

                                {{-- Page links --}}
                                @foreach ($data->getUrlRange(1, $data->lastPage()) as $page => $url)
                                    @php $url .= '&client_course_search=' . urlencode(request('client_course_search')); @endphp
                                    @if ($page == $data->currentPage())
                                        <li class="active"><a href="#">{{ sprintf('%02d', $page) }}</a></li>
                                    @else
                                        <li><a href="{{ $url }}">{{ sprintf('%02d', $page) }}</a></li>
                                    @endif
                                @endforeach

                                {{-- Next --}}
                                @if ($data->hasMorePages())
                                    <li><a
                                            href="{{ $data->nextPageUrl() }}&client_course_search={{ request('client_course_search') }}"><i
                                                class="fi-rr-arrow-small-right"></i></a></li>
                                @else
                                    <li class="disabled"><span><i class="fi-rr-arrow-small-right"></i></span></li>
                                @endif
                            </ul>
                        @endif
                    </ul>
                </div>
            </div>
        </div>


    </div>



    <script>
        function showDeleteConfirm(id, name, score_type) {
            Swal.fire({
                title: 'Xác nhận xóa',
                text: `Bạn có chắc chắn muốn xóa điểm ${score_type} của học sinh "${name}" không?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `/score-delete/${id}`;
                }
            });
        }
    </script>


@endsection
