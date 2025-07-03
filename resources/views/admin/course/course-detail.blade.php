  @extends('admin.admin')


  @section('title', 'Trang admin')
  @section('description', '')
  @section('content')


      <div class="page-content">

          <!-- Start Container Fluid -->
          <div class="container-xxl">
              <nav aria-label="breadcrumb p-0">
                  <ol class="breadcrumb py-0">
                      <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                      <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('admin.course-list') }}">Quản lí
                              khóa học</a> </li>
                      <li class="breadcrumb-item active" aria-current="page">Chi tiết khóa học </li>
                  </ol>
              </nav>

              <div class="row">
                  <div class="col-lg-4">
                      <div class="card" style="width:70%;">
                          <div class="card-body">
                              <!-- Crossfade -->
                              <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
                                  <div class="carousel-inner" role="listbox">
                                      <div class="carousel-item active">
                                          @if ($course->image)
                                              <img src="{{ asset($course->image) }}"
                                                  style="width: 100%; height: 300px; object-fit: cover;"
                                                  class="d-block w-100" alt="...">
                                          @else
                                              <span>Không có ảnh</span>
                                          @endif
                                      </div>


                                  </div>
                              </div>
                          </div>

                      </div>
                  </div>
                  <div class="col-lg-8">
                      <div class="card">
                          <div class="card-body">
                              <p class="mb-1">
                                  <a href="#!" class="fs-24 text-dark fw-medium">{{ $course->name }}</a>
                              </p>

                              <h2 class="fw-medium my-3">{{ $course->price }} VND </h2>

                              <div class="row align-items-center g-2 mt-3">


                              </div>


                              <h4 class="text-dark fw-medium">🧑‍🏫 Phương pháp giảng dạy:</h4>
                              <p class="text-muted">{{ $course->teaching_method }}
                              </p>
                              <h4 class="text-dark fw-medium mt-3">🌈 Mục tiêu khóa học:</h4>
                              <div class="d-flex align-items-center mt-2">
                                  <i class="bx bxs-bookmarks text-success me-3 fs-20 mt-1"></i>
                                  <p class="mb-0"><span class="fw-medium text-dark">
                                          {{ $course->teaching_goals }}
                              </div>
                              <h4 class="text-dark fw-medium mt-3">🌈 Nội dung về khóa học :</h4>
                              <div class="d-flex align-items-center mt-2">
                                  <i class="bx bxs-bookmarks text-success me-3 fs-20 mt-1"></i>
                                  <p class="mb-0"><span class="fw-medium text-dark">{{ $course->description }}
                              </div>

                          </div>
                      </div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-lg-0">
                      <div class="card">
                          <div class="card-header">
                              <h4 class="card-title"> Nội dung về bài giảng trong khóa học : </h4>

                          </div>
                          <div class="card-body">
                              <div class="">


                                  <table class="table">
                                      <thead>
                                          <tr>
                                              <th scope="col">Tên Bài Giảng </th>
                                              <th scope="col">tài Liệu Học </th>
                                              <th scope="col">Bài Tập</th>
                                              <th scope="col">Ngày Cập Nhật </th>
                                              <th scope="col">hành Động </th>
                                              <th scope="col"><a
                                                      href="{{ route('admin.lession-add', ['id' => $course->id]) }}"
                                                      class="btn btn-sm btn-primary">Thêm bài giảng</a></th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                          @foreach ($lessions as $lession)
                                              <tr>
                                                  <td>{{ $lession->name }}</td>
                                                  <td><a href="{{ $lession->link_document }}"><iconify-icon
                                                              icon="mdi:file-pdf-box" class="text-danger"
                                                              style="font-size: 40px;"></iconify-icon>
                                                      </a></td>
                                                  <td>
                                                      @if ($lession->quizz)
                                                          <a
                                                              href="{{ route('admin.quizzes.detail', ['id' => $lession->quizz->id]) }}">
                                                              {{ $lession->quizz->title }}
                                                          </a>
                                                      @else
                                                          <span class="text-muted">Chưa có bài tập</span>
                                                      @endif
                                                  </td>

                                                  <td>{{ $lession->updated_at }}</td>
                                                  <td>
                                                      <a href="{{ route('admin.lession-edit', ['course_id' => $course->id, 'id' => $lession->id]) }} "
                                                          class="btn btn-soft-primary btn-sm"><iconify-icon
                                                              icon="solar:pen-2-broken"
                                                              class="align-middle fs-18"></iconify-icon></a>

                                                      <form id="delete-course-form-{{ $lession->id }}"
                                                          action="{{ route('admin.lession-delete', ['id' => $lession->id]) }}"
                                                          method="POST" style="display:inline;">
                                                          @csrf
                                                          @method('DELETE')
                                                          <button type="button" class="btn btn-soft-danger btn-sm"
                                                              onclick="confirmDelete({{ $lession->id }})">
                                                              <iconify-icon icon="solar:trash-bin-minimalistic-2-broken"
                                                                  class="align-middle fs-18"></iconify-icon>
                                                          </button>
                                                      </form>

                                                  </td>

                                              </tr>
                                          @endforeach
                                      </tbody>
                                  </table>


                              </div>
                              <div class="mt-3">
                                  <button class="btn btn-soft-primary">
                                      <a href="{{ route('admin.course-list') }}"
                                          class="link-primary text-decoration-underline link-offset-2"
                                          style="color: black">Trở về trang khóa học <i
                                              class="bx bx-arrow-to-right align-middle fs-16"></i></a>
                                  </button>
                              </div>
                          </div>
                      </div>
                  </div>

              </div>

          </div>
          <!-- End Container Fluid -->

          <!-- ========== Footer Start ========== -->
          <footer class="footer">
              <div class="container-fluid">
                  <div class="row">
                      <div class="col-12 text-center">
                          <script>
                              document.write(new Date().getFullYear())
                          </script> &copy; DỰ ÁN TỐT NGHIỆP CAO ĐẲNG FPT THANH HÓA<iconify-icon
                              icon="iconamoon:heart-duotone" class="fs-18 align-middle text-danger"></iconify-icon> <a
                              href="#" class="fw-bold footer-text" target="_blank">NHÓM 4</a>
                      </div>
                  </div>
              </div>
          </footer>
          <!-- ========== Footer End ========== -->

      </div>
      @if (session('success'))
          <script>
              Swal.fire({
                  icon: 'success',
                  title: 'Thành công!',
                  text: '{{ session('success') }}',
                  confirmButtonText: 'Đóng',
              });
          </script>
      @endif


  @endsection
  <script>
      function confirmDelete(lessionId) {
          Swal.fire({
              title: 'Bạn có chắc chắn?',
              text: "Thao tác này sẽ xóa bài giảng!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#d33',
              cancelButtonColor: '#6c757d',
              confirmButtonText: 'Xóa',
              cancelButtonText: 'Hủy'
          }).then((result) => {
              if (result.isConfirmed) {

                  document.getElementById('delete-course-form-' + lessionId).submit();

                  // console.log(dom);
              }
          });
      }
  </script>
