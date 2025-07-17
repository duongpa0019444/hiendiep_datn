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

            <nav aria-label="Page navigation">
                {!! $data->links('pagination::bootstrap-5') !!}
            </nav>
        </div>


        
    </div>
@endsection
