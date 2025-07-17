@extends('client.accounts.information')

@section('content-information')
    <div id="grades" class="content-section">

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

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title fw-bold">Sửa điểm</h4>
                        </div>
                        <form method="POST"
                            action="{{ route('client.score.update', ['class_id' => request('class_id'), 'id' => $score->id]) }}">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="roles-name" class="form-label fw-semibold">Học sinh</label>
                                            <div>
                                                <select class="form-control" name="student_id" id="">
                                                    <option value="">Chọn học sinh</option>
                                                    @foreach ($data as $stdClass)
                                                        <option value="{{ $stdClass->student_id }}"
                                                            {{ $score->student_id == $stdClass->student_id ? 'selected' : '' }}>
                                                            {{ $stdClass->student->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>


                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="roles-name" class="form-label fw-semibold">Loại điểm</label>
                                            <input type="text" name="score_type"
                                                class="form-control" value="{{ $score->score_type }}"
                                                placeholder="Nhập loại điểm...">


                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="user-name" class="form-label fw-semibold">Số điểm</label>
                                            <input type="number" step="any" min="0" name="score"
                                                class="form-control" value="{{ $score->score }}"
                                                placeholder="Nhập số điểm...">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="user-name" class="form-label fw-semibold">Ngày</label>
                                            <input type="date" name="exam_date" class="form-control"
                                                value="{{ $score->exam_date }}">

                                        </div>
                                    </div>
                                </div>
                            </div>

                             <div class="card-footer border-top text-end">
                                <a href="{{ route('client.score.detail', ['class_id' => $class->id, 'course_id' => $class->courses_id]) }}"  class="btn btn-secondary">Quay lại</a>
                                <button type="submit" style="background: var(--primary-gradient);" class="btn btn-primary">Lưu</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

      

    </div>


@endsection
