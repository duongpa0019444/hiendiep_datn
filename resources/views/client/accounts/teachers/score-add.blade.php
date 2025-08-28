@extends('client.accounts.information')

@section('content-information')

<!-- Tom Select CSS -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">

<!-- Tom Select JS -->
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>



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

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title fw-semibold">Thông tin điểm nhập</h5>
                            
                        </div>
                        <form method="POST" action="{{ route('client.score.store', [request('class_id')]) }}">
                            @csrf
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="" class="form-label fw-semibold">Học sinh</label>
                                            <select name="student_id" id="student_filter" class=" w-100"> 
                                                <option value="">Chọn học sinh</option>
                                                @foreach ($data as $stdClass)
                                                    <option value="{{ $stdClass->student_id }}"
                                                        {{ request('student_id') == $stdClass->student_id }}>
                                                        {{ $stdClass->student->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="roles-name" class="form-label fw-semibold">Loại điểm</label>
                                             <input type="text"  name="score_type" class="form-control"
                                                placeholder="Nhập loại điểm...">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="user-name" class="form-label fw-semibold">Số điểm</label>
                                            <input type="number" step="any" min="0" name="score" class="form-control"
                                                placeholder="Nhập số điểm...">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="user-name" class="form-label fw-semibold">Ngày</label>
                                            <input type="date" name="exam_date" class="form-control">
                                               
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


<script>
document.addEventListener('DOMContentLoaded', function () {
    new TomSelect("#student_filter", {
        placeholder: "Chọn học sinh",
        allowEmptyOption: true,
        persist: false,
        create: false,
        maxOptions: 500,
        closeAfterSelect: true,
        sortField: {
            field: "text",
            direction: "asc"
        }
    });
});
</script>


@endsection
