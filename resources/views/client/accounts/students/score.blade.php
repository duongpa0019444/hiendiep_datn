@extends('client.accounts.information')

@section('content-information')
    <div id="grades" class="content-section">
        <h3>Điểm Số</h3>
        <table>
            <thead>
                <tr>
                    <th>Lớp</th>
                    <th>Môn Học</th>
                    <th>Loại điểm</th>
                    <th>Điểm</th>
                    <th>Ngày kiểm tra</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $score)
                    <tr>
                        <td>{{ $score->class->name ?? '' }}</td>
                        <td>{{ $score->class->course->name ?? '' }}</td>
                        <td>{{ $score->score_type }}</td> {{-- làm hàm trong model score --}}
                        <td>{{ $score->score }}</td>
                        <td>{{ \Carbon\Carbon::parse($score->exam_date)->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
       
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
@endsection
