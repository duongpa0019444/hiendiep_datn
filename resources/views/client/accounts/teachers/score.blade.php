@extends('client.accounts.information')

@section('content-information')
    <div id="grades" class="content-section">

        <div class="row">
            <div class="col-7">
                <h5 class="mb-0">Điểm số</h5>

            </div>
            <div class="col-5">
                <form method="GET" action="{{ route('client.score.search') }}" class="app-search d-none d-md-block ms-2">
                    <div class="position-relative">
                        <input type="search" name="queryScoreClient" class="form-control ps-5" {{-- padding-left để icon không đè chữ --}}
                            placeholder="Tìm lớp học và khóa học..." autocomplete="off"
                            value="{{ request()->query('queryScoreClient') ?? '' }}">

                        <i class="icofont-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    </div>
                </form>

            </div>
        </div>

        <div class="row mt-3">
            @forelse ($data as $class)
                <div class="col-lg-4 mb-4">
                    <div class="card shadow-sm h-100 border-0">
                        <div class="card-body">
                            <a href="{{ route('client.score.detail', ['class_id' => $class->id, 'course_id' => $class->courses_id]) }}"
                                class="text-decoration-none text-dark">

                                <div class="d-flex align-items-center mb-2">
                                    <i class="icofont-ui-home text-muted me-2"></i>
                                    <span class="fw-semibold me-2">Lớp:</span>
                                    <span>{{ $class->name }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="icofont-graduate-alt text-muted me-2"></i>
                                    <span class="fw-semibold me-2">Khóa học:</span>
                                    <span>{{ $class->course->name }}</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning text-center" role="alert">
                        Không tìm thấy lớp hoặc khóa học nào.
                    </div>
                </div>
            @endforelse

             {{-- Pagination --}}
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



    </div>
@endsection
