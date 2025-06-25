@extends('admin.admin')
@section('title', 'Trang admin')
@section('description', '')
@section('content')

<style>
    .btn-group .btn.active {
    background-color: #0d6efd;
    color: white;
    font-weight: bold;
}
 #roleButtons .role-btn {
        min-width: 120px;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    #roleButtons .role-btn.active {
        background-color: #0d6efd;
        color: white;
        border-color: #0d6efd;
    }

    #roleButtons .role-btn:hover {
        opacity: 0.9;
    }
</style>

     <div class="page-content">
        <div class="container-fluid">
            <div class="container-xxl">
                <nav aria-label="breadcrumb p-0">
                    <ol class="breadcrumb py-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Quản lí thông báo</li>
                    </ol>
                </nav>
                <div class="row">
                    <div class="col-lg-7 col-12">
                        <h4>📢 Gửi thông báo</h4>
                          @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('admin.notifications.seed') }}">
                            @csrf
                           <input type="hidden" name="created_by" value="{{ Auth::user()->id }}">
                            <div class="mb-3">
                                <label class="form-label">Tiêu đề</label>
                                <input type="text" name="title" class="form-control" value="{{ old('title') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nội dung</label>
                                <textarea class="form-control bg-light-subtle mb-3 ckeditor" id="content" name="content" rows="7" placeholder="Nội dung thông báo"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Gửi đến</label>
                                <select name="target_role" class="form-select" required>
                                    <option value="all">Toàn hệ thống</option>
                                    <option value="student">Học sinh</option>
                                    <option value="teacher">Giáo viên</option>
                                    <option value="staff">Nhân viên</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary" onclick="return confirm('Bạn có chắc muốn gửi thông báo?')">Gửi thông báo</button>
                        </form>
                    </div>
                    <div class="col-lg-5 col-12">
                        <h4>Danh sách thông báo</h4>
                     <div class="mb-3 d-flex gap-2 flex-wrap" id="roleButtons">
                        <button type="button" class="btn btn-outline-secondary role-btn active" data-role="">Tất cả</button>
                        <button type="button" class="btn btn-outline-success role-btn" data-role="teacher">🎓 Giáo viên</button>
                        <button type="button" class="btn btn-outline-info role-btn" data-role="student">👩‍🎓 Học sinh</button>
                        <button type="button" class="btn btn-outline-warning role-btn" data-role="staff">🧑‍💼 Nhân viên</button>
                    </div>

                        <div id="notificationsContainer">
                            @foreach ( $notis as $noti )
                                <div class="card mb-3 shadow-sm">
                                    <div class="row g-0 align-items-center">
                                        <div class="col-md-2 text-center">
                                            <img src="{{ $noti->avatar }}" alt="avatar" class="img-fluid rounded-circle p-2" style="width: 80px; height: 80px; object-fit: cover;">
                                        </div>
                                        <div class="col-md-8">
                                            <div class="card-body py-2">
                                                <span class="fw-bold">{{$noti->title}}</span>
                                                <span class="text-muted fs-6 fst-italic">— {{ $noti->name }}, lúc  {{ \Carbon\Carbon::parse($noti->created_at)->diffForHumans() }}</span>
                                                 </h5>
                                                <p class="card-text text-muted mb-0">{{ $noti->content }}</p>
                                            </div>
                                        </div>
                                         <div class="col-md-1 text-end pe-3 mx-2">
                                            <button class="btn btn-sm btn-outline-danger delete-noti-btn" data-id="{{ $noti->id }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>

                                </div>
                             @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
     </div>

<script>

    function renderNotifications(data) {
    let html = '';

    if (data.length === 0) {
        html = '<p class="text-muted">Không có thông báo nào.</p>';
    } else {
        data.forEach(noti => {

            console.log(noti.id)
            const formattedTime = new Date(noti.created_at).toLocaleString('vi-VN', {
                hour: '2-digit',
                minute: '2-digit',
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
                });

            html += `
                <div class="card mb-3 shadow-sm">
                    <div class="row g-0 align-items-center">
                        <div class="col-md-2 text-center">
                            <img src="${noti.avatar}" alt="avatar" class="img-fluid rounded-circle p-2" style="width: 80px; height: 80px; object-fit: cover;">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body py-2">
                               <span class="fw-bold">${noti.title}</span>
                                <span class="text-muted fs-6 fst-italic">— ${noti.name}, lúc ${formattedTime}</span>
                                <p class="card-text text-muted mb-0">${noti.content}</p>
                            </div>
                        </div>
                        <div class="col-md-1 text-end pe-3 mx-2">
                            <button class="btn btn-sm btn-outline-danger delete-noti-btn" data-id="${noti.id}">
                                <i class="bi bi-trash"></i>
                            </button>
                         </div>
                    </div>
                </div>
            `;
        });
    }

    $('#notificationsContainer').html(html);
}



    $(document).on('click', '.role-btn', function () {
    // Xóa lớp active khỏi tất cả nút
    $('.role-btn').removeClass('active');

    // Gán lớp active cho nút được click
    $(this).addClass('active');

    const role = $(this).data('role');

    $.ajax({
        url: "{{ route('admin.notifications.filter') }}",
        type: "GET",
        data: { role: role },
        success: function (res) {
            if (res.success) {
                renderNotifications(res.data); // Hàm hiển thị lại dữ liệu
            }
        },
        error: function () {
            alert("Lỗi khi lọc dữ liệu.");
        }
    });
});

 $(document).on('click', '.delete-noti-btn', function () {
        const button = $(this);
        const notiId = button.data('id');

        if (!confirm('Bạn có chắc muốn xóa thông báo này?')) return;

        $.ajax({
            url: "{{ route('admin.notifications.delete') }}",
            method: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                id: notiId
            },
            success: function (res) {
                if (res.success) {
                    button.closest('.row').remove(); // Xóa khỏi giao diện
                    alert(res.messege);
                } else {
                    alert(res.message || 'Xóa không thành công');
                }
            },
            error: function () {
                alert('Lỗi kết nối khi xóa');
            }
        });
    });



</script>

@endsection
