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
                            <form method="GET" class="bg-white rounded p-3 shadow-sm mb-4">
                                <div class="row g-3 align-items-end">
                                    {{-- Phân loại --}}
                                    <div class="col-md">
                                        <label class="form-label mb-1">Phân loại</label>
                                        <select name="pl_content" class="form-select">
                                            <option value="">Tất cả</option>
                                            <option value="Góp ý" {{ request('pl_content') == 'Góp ý' ? 'selected' : '' }}>
                                                Góp ý
                                            </option>
                                            <option value="Khiếu nại"
                                                {{ request('pl_content') == 'Khiếu nại' ? 'selected' : '' }}>
                                                Khiếu nại</option>
                                            <option value="Hỗ Trợ"
                                                {{ request('pl_content') == 'Hỗ Trợ' ? 'selected' : '' }}>Hỗ Trợ
                                            </option>
                                            <option value="Đăng kí"
                                                {{ request('pl_content') == 'Đăng kí' ? 'selected' : '' }}>Đăng
                                                kí</option>
                                        </select>
                                    </div>

                                    {{-- Nhân viên xử lý --}}
                                    <div class="col-md">
                                        <label class="form-label mb-1">Nhân viên xử lý</label>
                                        <select name="assigned_to" class="form-select">
                                            <option value="">Tất cả</option>
                                            @foreach ($staffs as $staff)
                                                <option value="{{ $staff->id }}"
                                                    {{ request('assigned_to') == $staff->id ? 'selected' : '' }}>
                                                    {{ $staff->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Trạng thái --}}
                                    <div class="col-md">
                                        <label class="form-label mb-1">Trạng thái</label>
                                        <select name="status" class="form-select">
                                            <option value="">Tất cả</option>
                                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Đợi xử
                                                lý
                                            </option>
                                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Đã xử lý
                                            </option>
                                            <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Không
                                                hỗ trợ
                                            </option>
                                        </select>
                                    </div>

                                    {{-- Từ ngày --}}
                                    <div class="col-md">
                                        <label class="form-label mb-1">Từ ngày</label>
                                        <input type="date" name="from_date" class="form-control"
                                            value="{{ request('from_date') }}">
                                    </div>

                                    {{-- Đến ngày --}}
                                    <div class="col-md">
                                        <label class="form-label mb-1">Đến ngày</label>
                                        <input type="date" name="to_date" class="form-control"
                                            value="{{ request('to_date') }}">
                                    </div>

                                    {{-- Nút hành động --}}
                                    <div class="col-md-auto d-flex gap-2">
                                        <div>
                                            <button type="submit" class="btn btn-success">Lọc</button>
                                        </div>
                                        <div>
                                            <a href="{{ url()->current() }}" class="btn btn-danger">Xóa</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
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

                                                {{-- Trạng thái --}}
                                                @if ($contact->status == 0)
                                                    <p class="badge bg-warning">Đợi xử lý</p>
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
                                                            <form
                                                                action="{{ route('admin.contact.delete', ['id' => $contact->id]) }}"
                                                                method="POST" style="display:inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger"
                                                                    onclick="return confirm('Bạn có chắc chắn muốn xóa tin nhắn này không?')">
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
{{-- popup xóa thành công --}}
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'OK'
                });
            @endif
        });
    </script>