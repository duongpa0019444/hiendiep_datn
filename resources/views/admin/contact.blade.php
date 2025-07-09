@extends('admin.admin')

@section('title', 'Hỗ trợ đã nhận')
@section('description', 'Danh sách tin nhắn hỗ trợ đã có nhân viên nhận xử lý.')

@section('content')
    <div class="page-content">
        <div class="container-xxl">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="mb-1">Tin nhắn hỗ trợ đã nhận</h3>
                    <p class="text-muted mb-0">Tổng số: {{ $contacts->total() }} tin nhắn</p>
                </div>
            </div>

            @if ($contacts->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-envelope-open-text fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Chưa có tin nhắn nào được nhận</h5>
                </div>
            @else
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Tên người gửi</th>
                                        <th>Số điện thoại</th>
                                        <th>Phân loại</th>
                                        <th>Nhân viên xử lý</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày gửi</th>
                                        <th>Hành Động</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($contacts as $index => $contact)
                                        <tr>
                                            <td>{{ ($contacts->currentPage() - 1) * $contacts->perPage() + $index + 1 }}
                                            </td>
                                            <td>{{ $contact->name }}</td>
                                            <td>{{ $contact->phone }}</td>
                                            <td>{{ ucfirst($contact->pl_content) }}</td>
                                            {{-- <td>{{ Str::limit($contact->message, 50) }}</td> --}}
                                            <td>{{ $contact->staff->name ?? 'N/A' }}</td>
                                            <td>
                                                {{-- @if ($contact->status === 'đợi xử lý')
                                                    <span class="badge bg-warning">Đợi xử lý</span>
                                                @else
                                                    <span class="badge bg-success">Đã xử lý</span>
                                                @endif --}}
                                                @if ($contact->status == 0)
                                                    <span class="badge bg-warning">Đợi xử lý</span>
                                                @elseif ($contact->status == 1)
                                                    <span class="badge bg-success">Đã xử lý</span>
                                                @elseif ($contact->status == 2)
                                                    <span class="badge bg-secondary">Không hỗ trợ</span>
                                                @endif


                                            </td>

                                            <td>{{ $contact->created_at->format('d/m/Y H:i') }}</td>

                                            <td>

                                                <div class="dropdown">
                                                    <button class="btn btn-light btn-sm dropdown-toggle" type="button"
                                                        data-bs-toggle="dropdown">
                                                        Thao tác <iconify-icon icon="tabler:caret-down-filled"
                                                            class="ms-1"></iconify-icon>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a href=" {{ route('admin.contactDetail', ['id' => $contact->id]) }}"
                                                                class="dropdown-item text-primary"><iconify-icon
                                                                    icon="solar:eye-broken"
                                                                    class="me-1"></iconify-icon>Chi tiết</a>
                                                        </li>

                                                        <li>
                                                            <form id="delete-course-form-" action="" method="POST"
                                                                style="display:inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="button" class="dropdown-item text-danger"
                                                                    onclick="">
                                                                    <iconify-icon
                                                                        icon="solar:trash-bin-minimalistic-2-broken"
                                                                        class="align-middle fs-18 me-1"></iconify-icon>
                                                                    Xóa
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>


                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="p-3">
                        {{ $contacts->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
