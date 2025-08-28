@extends('client.accounts.information')

@section('content-information')
    <div id="classroom" class="content-section">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0">Lớp học</h5>
            <a href="{{ route('client.classroom') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại lớp học
            </a>
        </div>
        <h6 class="mb-3">Nội dung về bài giảng trong khóa học: {{ $course->name }}</h6>

        @if ($lessions->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover table-bordered custom-table">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Tên Bài Giảng</th>
                            <th scope="col">Tài Liệu Học</th>
                            {{-- <th scope="col">Bài Tập</th> --}}
                            <th scope="col">Ngày Tạo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lessions as $index => $lession)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $lession->name }}</td>
                                {{-- <td>
                                    @if ($lession->link_document)
                                        <a target="_blank" rel="noopener noreferrer" href="{{ $lession->link_document }}" class="text-primary">
                                            <i class="fas fa-file-alt me-1"></i>Xem tài liệu
                                        </a>
                                    @else
                                        <span class="text-muted">Chưa có tài liệu</span>
                                    @endif
                                </td> --}}
                                <td>
                                    @if ($lession->quizz)
                                        <a href="{{ route('admin.quizzes.detail', ['id' => $lession->quizz->id]) }}" class="text-primary">
                                            <i class="fas fa-question-circle me-1"></i>{{ $lession->quizz->title }}
                                        </a>
                                    @else
                                        <span class="text-muted">Chưa có bài tập</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($lession->created_at)->format('d/m/Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($lessions->hasPages())
                <div id="pagination-wrapper" class="flex-grow-1 mt-4">
                    {{ $lessions->links('pagination::bootstrap-5') }}
                </div>
            @endif
        @else
            <div class="no-lessons text-center py-5">
                <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                <h3 class="text-muted mb-2">Không có bài giảng nào</h3>
                <p class="text-muted mb-4">Hiện tại khóa học chưa có bài giảng. Vui lòng kiểm tra lại sau.</p>
            </div>
        @endif
    </div>
@endsection
