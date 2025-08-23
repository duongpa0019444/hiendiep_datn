<?php

use App\Http\Controllers\admin\AccountController;
use App\Http\Controllers\admin\AttendanceController;
use App\Http\Controllers\admin\ClassController;
use App\Http\Controllers\client\HomeController;
use App\Http\Controllers\admin\dashboardController;
use App\Http\Controllers\admin\ScoreController;
use App\Http\Controllers\client\loginController;
use App\Http\Controllers\client\UserController;
use App\Http\Controllers\admin\coursePaymentController;
use App\Http\Controllers\admin\newsController;
use App\Http\Controllers\admin\questionsController;
use App\Http\Controllers\admin\NotificationsController;
use App\Http\Controllers\admin\quizzesController;
use App\Http\Controllers\admin\SchedulesController;
use App\Http\Controllers\admin\contactController;
use App\Http\Controllers\admin\notiCoursePaymentController;
use App\Http\Controllers\admin\TeacherRulesController;
use App\Http\Controllers\admin\TeacherSalaryController;
use App\Http\Controllers\admin\topicsController;
use App\Http\Controllers\client\quizzesController as ClientQuizzesController;
use App\Http\Controllers\client\CourseController as ClientCourseController;
use App\Http\Controllers\client\AttendanceController as ClientAttendanceController;
use App\Http\Controllers\courseController;
use App\Http\Controllers\admin\ClassroomController;


use App\Http\Controllers\SupportRequestController;


use App\Http\Middleware\CheckRole;
use App\Http\Middleware\CheckRoleClient;
use App\Models\news;
use App\Models\topics;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;



Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/course', [ClientCourseController::class, 'index'])->name('client.course');


// phần liên hệ client

Route::get('contact', [ClientCourseController::class, 'contact'])->name('client.contacts');
Route::post('/contact-support', [SupportRequestController::class, 'store'])->name('support.store');
Route::post('/contact-support/{id}/handle', [SupportRequestController::class, 'handle'])->middleware('auth')->name('support.handle');

// phần giới thiệu client
Route::get('about', [HomeController::class, 'about'])->name('client.about');

//
Route::get('/course/{slug}_{id}', [ClientCourseController::class, 'detail'])->name('client.course.detail');
Route::get('/course-search', [ClientCourseController::class, 'search'])->name('client.course.search');

/* modal */
Route::get('/logout', [loginController::class, 'logout'])->name('auth.logout');
Route::post('/login', [loginController::class, 'login'])->name('loginAuth');
// Route::post('register', [loginController::class, 'register'])->name('registerAuth');


/* trang xử lí đăng nhập và quên mk*/

Route::get('/login-page', [loginController::class, 'showForgotPasswordForm'])->name('auth.login');
Route::get('/forgot-password', [loginController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [loginController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password', [loginController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [loginController::class, 'resetPassword'])->name('password.update');



// Route dành cho admin và nhân viên --
Route::middleware([CheckRole::class . ':admin,staff'])->prefix('admin')->group(function () {
    // trang quản trị cho admin
    Route::get('dashboard', [DashboardController::class, 'dashBoard'])->name('admin.dashboard');
    Route::get('dashboard/chart/{course_id}', [DashboardController::class, 'chart']);
    Route::get('dashboard/schedules/{id}/views', [DashboardController::class, 'getSchedulesViews'])->name('admin.dashboard.schedules.views');
    Route::get('dashboard/schedules/date/{date}', [DashboardController::class, 'getSchedulesByDate'])->name('admin.dashboard.schedules.date');
    Route::get('dashboard/chart/revenue/{year}', [DashboardController::class, 'chartRevenue'])->name('admin.chartRevenue');
    Route::get('dashboard/chart/revenueCourse/{year}', [DashboardController::class, 'chartRevenueCourse'])->name('admin.revenueCourse');


    // Trang quản lý tài khoản
    Route::get('/account', [AccountController::class, 'account'])->name('admin.account');
    Route::get('/account-search', [AccountController::class, 'search'])->name('admin.account.search');
    Route::get('/account/{role}', [AccountController::class, 'list'])->name('admin.account.list');
    Route::get('/account/{role}/{id}', [AccountController::class, 'detail'])->name('admin.account.detail');

    Route::get('/account-add/{role}', [AccountController::class, 'add'])->name('admin.account.add');
    Route::post('/account-store/{role}', [AccountController::class, 'store'])->name('admin.account.store');
    Route::get('/account-edit/{role}/{id}', [AccountController::class, 'edit'])->name('admin.account.edit');
    Route::put('/account-update/{role}/{id}', [AccountController::class, 'update'])->name('admin.account.update');
    // check nguoi dung co đang liên kết với các bảng khác không
    Route::get('/account/check/{id}', [AccountController::class, 'check'])->name('admin.account.check');
    Route::get('/account/delete/{role}/{id}', [AccountController::class, 'delete'])->name('admin.account.delete');
    Route::get('/account-trash', [AccountController::class, 'trash'])->name('admin.account.trash');
    Route::post('/account/restore/{id}', [AccountController::class, 'restore'])->name('admin.account.restore');
    Route::delete('/account/force-delete/{id}', [AccountController::class, 'forceDelete'])->name('admin.account.forceDelete');


    // Quản lí điểm số
    Route::get('/score', [ScoreController::class, 'index'])->name('admin.score');
    Route::get('/score-search', [ScoreController::class, 'scoreSearch'])->name('admin.score.search');
    Route::get('/score/{class_id}/{course_id}', [ScoreController::class, 'detail'])->name('admin.score.detail');
    Route::get('/score-add/{class_id}', [ScoreController::class, 'add'])->name('admin.score.add');
    Route::post('/score-store/{class_id}', [ScoreController::class, 'store'])->name('admin.score.store');
    Route::get('/score-edit/{class_id}/{id}', [ScoreController::class, 'edit'])->name('admin.score.edit');
    Route::put('/score-update/{class_id}/{id}', [ScoreController::class, 'update'])->name('admin.score.update');
    Route::get('/score-delete/{id}', [ScoreController::class, 'delete'])->name('admin.score.delete');
    Route::get('/admin/scores/export/{class_id}/{course_id}', [ScoreController::class, 'export'])->name('admin.scores.export');
    Route::post('/admin/scores/import', [ScoreController::class, 'import'])->name('admin.scores.import');


    // Trang quản lý học phí Thanh toán
    Route::get('course-payments', [coursePaymentController::class, 'index'])->name('admin.course_payments');
    Route::get('course-payments/{id}/detail', [coursePaymentController::class, 'detail'])->name('admin.course_payments.detail');
    Route::put('course-payments/{id}/update', [coursePaymentController::class, 'update'])->name('admin.course_payments.update');
    Route::get('course-payments/filter', [coursePaymentController::class, 'filter'])->name('admin.course_payments.filter');
    Route::delete('course-payments/{id}/delete', [coursePaymentController::class, 'delete'])->name('admin.course_payments.delete');
    Route::get('course-payments/statistics', [coursePaymentController::class, 'statistics'])->name('admin.course_payments.statistics');
    Route::get('course-payments/{id}/show', [coursePaymentController::class, 'show'])->name('admin.course_payments.show');
    Route::get('course-payments/{id}/download', [coursePaymentController::class, 'download'])->name('admin.course_payments.download');
    Route::get('/admin/course-payments/export', [coursePaymentController::class, 'exportExcel'])->name('admin.course_payments.export');

    Route::get('course-payments/trash', [coursePaymentController::class, 'trash'])->name('admin.course_payments.trash');
    Route::get('course-payments/trash/filter', [coursePaymentController::class, 'filterTrash'])->name('admin.course_payments.trash.filter');

    //Trang quản lý quizz
    Route::get('quizzes', [quizzesController::class, 'index'])->name('admin.quizz');
    Route::get('quizzes/{id}/detail', [quizzesController::class, 'detail'])->name('admin.quizzes.detail');
    Route::get('quizzes/filter', [quizzesController::class, 'filter'])->name('admin.quizzes.filter');
    Route::delete('quizzes/{id}/delete', [quizzesController::class, 'delete'])->name('admin.quizzes.delete');
    Route::post('quizzes/store', [quizzesController::class, 'store'])->name('admin.quizzes.store');
    Route::put('quizzes/{id}/update', [quizzesController::class, 'update'])->name('admin.quizzes.update');
    Route::get('quizzes/{id}/update-status/{status}', [quizzesController::class, 'updateStatus'])->name('admin.quizzes.update.status');

    Route::get('/quizzes/trash', [quizzesController::class, 'trash'])->name('admin.quizzes.trash');
    Route::post('/quizzes/{id}/restore', [quizzesController::class, 'restore'])->name('admin.quizzes.restore');
    Route::delete('/quizzes/{id}/force-delete', [quizzesController::class, 'forceDelete'])->name('admin.quizzes.forceDelete');
    Route::get('/quizzes/trash/filter', [quizzesController::class, 'filterTrash'])->name('admin.quizzes.trash.filter');
    Route::get('quizzes/getCourse/class/{id}', [quizzesController::class, 'getCourse']);

    //Quản lý questions
    Route::post('quizzes/{id}/questions/store', [questionsController::class, 'store'])->name('admin.questions.store');
    Route::delete('questions/{id}/delete', [questionsController::class, 'delete'])->name('admin.questions.delete');
    Route::get('questions/{id}/edit', [questionsController::class, 'edit'])->name('admin.questions.edit');
    Route::put('questions/{id}/update', [questionsController::class, 'update'])->name('admin.questions.update');


    //Quản lý questions sentence
    Route::post('quizzes/{id}/questions-sentence/store', [questionsController::class, 'storeSentence'])->name('admin.questions.sentence.store');
    Route::delete('questions-sentence/{id}/delete', [questionsController::class, 'deleteSentence'])->name('admin.questions.sentence.delete');
    Route::get('questions-sentence/{id}/edit', [questionsController::class, 'editSentence'])->name('admin.questions.sentence.edit');
    Route::put('questions-sentence/{id}/update', [questionsController::class, 'updateSentence'])->name('admin.questions.sentence.update');


    //Quản lý kết quả quizz
    Route::get('quizzes/{id}/results', [quizzesController::class, 'results'])->name('admin.quizzes.results');
    Route::get('quizzes/{id}/results/class/{class}', [quizzesController::class, 'resultsClass'])->name('admin.quizzes.results.class');
    Route::get('quizzes/results/filter/class', [quizzesController::class, 'filterResults'])->name('admin.quizzes.filter.reults.class');

    Route::get('quizzes/{id}/results/class/{class}/student/{student}', [quizzesController::class, 'resultsClassStudent'])->name('admin.quizzes.results.class.student');
    Route::get('quizzes/results/class/student/filter', [quizzesController::class, 'filterResultsClassStudent'])->name('admin.quizzes.results.class.student.filter');

    Route::get('quizzes/{id}/results/class/{class}/student/{student}/attempts/{attempt}', [quizzesController::class, 'quizAttemptsStudentAnswer'])->name('admin.quizzes.results.class.student.attempts');



    //Trang quản lý lương giáo viên
    Route::get('teacher-salaries', [TeacherSalaryController::class, 'index'])->name('admin.teacher_salaries');
    Route::get('/api/salary-data', [TeacherSalaryController::class, 'getData'])->name('admin.teacher_salaries.data');
    Route::post('/api/salary-data', [TeacherSalaryController::class, 'save'])->name('admin.teacher_salaries.save');
    Route::post('/admin/teacher-salaries/update-payment', [TeacherSalaryController::class, 'updatePayment'])->name('admin.teacher_salaries.upload');
    Route::get('/admin/teacher-salaries/filter', [TeacherSalaryController::class, 'filter'])->name('admin.teacher_salaries.filter');


    //Chi tiết lương giáo viên
    Route::get('/admin/teacher-salary-rules/{id}/details', [TeacherRulesController::class, 'details'])->name('admin.teacher_salary_rules.details');
    Route::get('/teacher-salary-rules/index', [TeacherRulesController::class, 'indexRules'])->name('admin.teacher_salaries.detail');
    Route::post('/admin/teacher-salary-rules/store', [TeacherRulesController::class, 'store'])->name('admin.teacher_salary.store');
    Route::get('/admin/teacher-salary-rules/by-teacher/{id}', [TeacherRulesController::class, 'getRulesByTeacher'])->name('admin.teacher-salary-rules.byTeacher');
    Route::get('/admin/teacher-salary-rules/search-teacher', [TeacherRulesController::class, 'searchTeacher'])
        ->name('admin.teacher-salary-rules.searchTeacher');


    // Gửi thông báo
    Route::get('admin/notifications', [NotificationsController::class, 'index'])->name('admin.notifications');
    Route::get('/admin/notifications/filter', [NotificationsController::class, 'filter'])->name('admin.notifications.filter');
    Route::post('admin/notifications/seed', [NotificationsController::class, 'seed'])->name('admin.notifications.seed');
    Route::post('/admin/notifications/delete', [NotificationsController::class, 'destroy'])->name('admin.notifications.delete');





    // Quản lý lớp học
    Route::get('/admin/classes', [ClassController::class, 'index'])->name('admin.classes.index');
    Route::get('/admin/classes/create', [ClassController::class, 'create'])->name('admin.classes.create');
    Route::post('/admin/classes', [ClassController::class, 'store'])->name('admin.classes.store');
    Route::get('/admin/classes/{id}', [ClassController::class, 'show'])->name('admin.classes.detail');
    Route::get('/admin/classes/{id}/edit', [ClassController::class, 'edit'])->name('admin.classes.edit');
    Route::put('/admin/classes/{id}', [ClassController::class, 'update'])->name('admin.classes.update');
    Route::patch('/admin/classes/{id}/toggle-status', [ClassController::class, 'toggleStatus'])->name('admin.classes.toggle-status');
    Route::delete('/classes/{id}', [ClassController::class, 'destroy'])->name('admin.classes.destroy');
    Route::post('/admin/classes/{id}/restore', [ClassController::class, 'restore'])->name('admin.classes.restore');
    // Xem danh sách học sinh trong lớp
    Route::get('classes/{id}/students', [ClassController::class, 'students'])->name('admin.classes.students');
    Route::post('classes/{id}/students', [ClassController::class, 'addStudent'])->name('admin.classes.add-student');
    Route::delete('classes/{id}/students/{studentId}', [ClassController::class, 'removeStudent'])->name('admin.classes.remove-student');
    Route::patch('classes/{class}/students/{student}/restore', [ClassController::class, 'restoreStudent'])->name('admin.classes.restore-student');
    Route::delete('classes/{class}/students/{student}/force-delete', [ClassController::class, 'forceDeleteStudent'])->name('admin.classes.force-delete-student');
    // Danh sách lịch hoc của lớp
    Route::get('/classes/{id}/schedules', [ClassController::class, 'schedules'])->name('admin.classes.schedules');




  // phần quản lý phòng học
  Route::get('/classroom', [ClassroomController::class, 'index'])->name('admin.classroom.list-room');
  // thêm phòng học
  Route::get('/classroom/create', [ClassroomController::class, 'create'])->name('admin.classroom.create');
  Route::post('/classroom/store', [ClassroomController::class, 'store'])->name('admin.classroom.store');
  // xóa phòng học
  Route::delete('/admin/classrooms/{id}', [ClassroomController::class, 'delete'])->name('admin.classroom.delete');
  // lấy thời gian
  // Route::get('/classroom/{id}/times', [ClassroomController::class, 'getClassTimes'])->name('admin.classroom.get-class-times');
  // chi tiết phòng học
  Route::get('/classroom/{id}', [ClassroomController::class, 'detailRoom'])->name('admin.classroom.detail-room');

// thêm lớp vào phòng
Route::get('/classroom/{id}/add-class', [ClassroomController::class, 'addClass'])->name('admin.classroom.add-class');
Route::post('/classroom/{id}/store-class', [ClassroomController::class, 'storeClass'])->name('admin.classroom.store-class');

// cập nhật phòng học
Route::get('classroom/{id}/edit', [ClassroomController::class, 'edit'])->name('admin.classroom.edit');
Route::put('classroom/{id}', [ClassroomController::class, 'update'])->name('admin.classroom.update');









    // Quản lý lịch học
    Route::get('/admin/schedules', [SchedulesController::class, 'index'])->name('admin.schedules.index');
    Route::get('/admin/schedules/{id}/create', [SchedulesController::class, 'create'])->name('admin.schedules.create');
    Route::post('/admin/schedules/store', [SchedulesController::class, 'store'])->name('admin.schedules.store');
    Route::get('/schedules/{id}/edit', [SchedulesController::class, 'edit'])->name('admin.schedules.edit');
    Route::put('/schedules/{id}', [SchedulesController::class, 'update'])->name('admin.schedules.update');
    // Route::put('/admin/schedules/{id}', [SchedulesController::class, 'update'])->name('admin.schedules.update');
    Route::delete('/schedules/{id}', [SchedulesController::class, 'destroy'])->name('admin.schedules.destroy');
    Route::post('/admin/schedules/{id}/restore', [SchedulesController::class, 'restore'])->name('admin.schedules.restore');
    Route::get('/admin/schedules/{id}/students', [SchedulesController::class, 'students'])->name('admin.schedules.students');
    Route::post('/admin/schedules/{id}/students', [SchedulesController::class, 'addStudent'])->name('admin.schedules.add-student');
    Route::delete('/admin/schedules/{id}/students/{studentId}', [SchedulesController::class, 'removeStudent'])->name('admin.schedules.remove-student');
    //
    Route::get('classes/{id}/data', [SchedulesController::class, 'getClassData'])->name('admin.classes.show');

    // Quản lý điểm danh
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('admin.attendance.index');
    Route::get('/attendance/getSchedules', [AttendanceController::class, 'getSchedules'])->name('admin.attendance.getSchedules');

    Route::get('/attendance/schedules/{id}', [AttendanceController::class, 'attendanceClass'])->name('admin.attendance.class');

    // Mới thêm
    Route::post('/attendance/summary', [AttendanceController::class, 'updateSummary'])->name('attendance.summary.update');
    Route::post('/attendance/save', [AttendanceController::class, 'saveAttendance'])->name('attendance.save');
    Route::get('/attendance/export', [AttendanceController::class, 'exportAttendance'])->name('attendance.export');
    Route::get('/attendance/schedules/{id}', [AttendanceController::class, 'attendanceClass'])->name('admin.attendance.class');


    // Quản lý bài viết & tin tức
    Route::get('/news', [newsController::class, 'index'])->name('admin.news.index');
    Route::get('/news/filter', [newsController::class, 'filter'])->name('admin.news.filter');
    Route::get('/news/create', [NewsController::class, 'create'])->name('admin.news.create');
    Route::get('/news/{id}/edit', [NewsController::class, 'edit'])->name('admin.news.edit');
    Route::post('/news/store', [newsController::class, 'store'])->name('admin.news.store');
    Route::put('/news/{id}/update', [newsController::class, 'update'])->name('admin.news.update');
    Route::delete('/news/{id}/delete', [newsController::class, 'delete'])->name('admin.news.delete');

    Route::get('/news/upload', [newsController::class, 'upload'])->name('admin.news.temp-upload');
    Route::post('/news/update-toggle', [newsController::class, 'updateToggle']);

    Route::get('/news/trash', [newsController::class, 'trash'])->name('admin.news.trash');
    Route::post('/news/{id}/restore', [NewsController::class, 'restore'])->name('admin.news.restore');
    Route::delete('/news/{id}/force-delete', [NewsController::class, 'forceDelete'])->name('admin.news.forceDelete');
    Route::get('/news/trash/filter', [newsController::class, 'filterTrash'])->name('admin.news.trash.filter');

    //Quản lý topics
    Route::get('/topics', [topicsController::class, 'index'])->name('admin.topics.index');
    Route::get('/topics/filter', [topicsController::class, 'filter'])->name('admin.topics.filter');
    Route::get('/topics/create', [topicsController::class, 'create'])->name('admin.topics.create');
    Route::get('/topics/edit/{id}', [topicsController::class, 'edit'])->name('admin.topics.edit');
    Route::post('/topics/store', [topicsController::class, 'store'])->name('admin.topics.store');
    Route::put('/topics/{id}/update', [topicsController::class, 'update'])->name('admin.topics.update');
    Route::delete('/topics/delete/{id}', [topicsController::class, 'delete'])->name('admin.topics.delete');

    Route::get('/topics/trash', [topicsController::class, 'trash'])->name('admin.topics.trash');
    Route::post('/topics/{id}/restore', [topicsController::class, 'restore'])->name('admin.topics.restore');
    Route::delete('/topics/{id}/force-delete', [topicsController::class, 'forceDelete'])->name('admin.topics.forceDelete');
    Route::get('/topics/trash/filter', [topicsController::class, 'filterTrash'])->name('admin.topics.trash.filter');

    // Trang quản lý khóa học
    Route::get('/course', [CourseController::class, 'index'])->name('admin.course-list');
    // Chi tiết khóa học
    Route::get('/course/detail/{id}', [CourseController::class, 'show'])->name('admin.course-detail');
    // Cập nhật khóa học
    Route::get('/course/edit/{id}', [CourseController::class, 'edit'])->name('admin.course-edit');
    Route::put('/course/edit/{id}', [CourseController::class, 'update'])->name('admin.course-update');
    // Xoá khóa học
    Route::delete('/course/delete/{id}', [CourseController::class, 'delete'])->name('admin.course-delete');
    // Thêm khóa học
    Route::get('/course/add', [CourseController::class, 'add'])->name('admin.course-add');
    Route::post('/course/add', [CourseController::class, 'create'])->name('admin.course-create');

    // Xóa bài giảng
    Route::delete('/course/lessions/delete/{id}', [CourseController::class, 'deleteLession'])->name('admin.lession-delete');
    // Thêm bài giảng
    Route::get('/course/lessions/add/{id}', [CourseController::class, 'addLession'])->name('admin.lession-add');
    Route::post('/course/lessions/add/{id}', [CourseController::class, 'createLession'])->name('admin.lession-create');
    // Cập nhật bài giảng
    Route::get('/course/{course_id}/lessions/edit/{id}', [CourseController::class, 'editLession'])->name('admin.lession-edit');
    Route::put('/course/{course_id}/lessions/edit/{id}', [CourseController::class, 'updateLession'])->name('admin.lession-update');

    // Trang quản lý khóa học
    Route::get('/course', [CourseController::class, 'index'])->name('admin.course-list');
    // Chi tiết khóa học
    Route::get('/course/detail/{id}', [CourseController::class, 'show'])->name('admin.course-detail');
    // Cập nhật khóa học
    Route::get('/course/edit/{id}', [CourseController::class, 'edit'])->name('admin.course-edit');
    Route::put('/course/edit/{id}', [CourseController::class, 'update'])->name('admin.course-update');
    // Xoá khóa học
    Route::delete('/course/delete/{id}', [CourseController::class, 'delete'])->name('admin.course-delete');
    // Thêm khóa học
    Route::get('/course/add', [CourseController::class, 'add'])->name('admin.course-add');
    Route::post('/course/add', [CourseController::class, 'create'])->name('admin.course-create');

    // Xóa Nhiều
    // Route::delete('/admin/courses/bulk-delete', [CourseController::class, 'bulkDelete'])->name('admin.course-bulk-delete');
    // nổi bật khóa học



    // nổi bật khóa học
    Route::post('/courses/{id}/toggle-featured', [CourseController::class, 'toggleFeatured'])->name('admin.course.toggle-featured');
    // Route::post('/admin/courses/{id}/toggle-featured', [CourseController::class, 'toggleFeatured']);


    // quản lý hỗ trợ tin nhắn
    Route::get('/admin/contact', [contactController::class, 'contact'])->name('admin.contact');
    Route::get('/admin/contact/{id}/detail', [contactController::class, 'contactDetail'])->name('admin.contactDetail');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::put('/contact/{id}/approve', [ContactController::class, 'approve'])->name('contact.approve');
        Route::get('/contact/{id}/reject', [ContactController::class, 'reject'])->name('contact.reject');
        Route::delete('/contact/{id}/delete', [ContactController::class, 'delete'])->name('contact.delete');
    });




    // Xóa Nhiều
    // Route::delete('/admin/courses/bulk-delete', [CourseController::class, 'bulkDelete'])->name('admin.course-bulk-delete');
    // nổi bật khóa học

    Route::post('/courses/{id}/toggle-featured', [CourseController::class, 'toggleFeatured'])->name('admin.course.toggle-featured');




    // Quản lý bài viết & tin tức
    Route::get('/news', [newsController::class, 'index'])->name('admin.news.index');
    Route::get('/news/filter', [newsController::class, 'filter'])->name('admin.news.filter');
    Route::get('/news/create', [NewsController::class, 'create'])->name('admin.news.create');
    Route::get('/news/{id}/edit', [NewsController::class, 'edit'])->name('admin.news.edit');
    Route::post('/news/store', [newsController::class, 'store'])->name('admin.news.store');
    Route::put('/news/{id}/update', [newsController::class, 'update'])->name('admin.news.update');
    Route::delete('/news/{id}/delete', [newsController::class, 'delete'])->name('admin.news.delete');

    Route::get('/news/upload', [newsController::class, 'upload'])->name('admin.news.temp-upload');
    Route::post('/news/update-toggle', [newsController::class, 'updateToggle']);

    Route::get('/news/trash', [newsController::class, 'trash'])->name('admin.news.trash');
    Route::post('/news/{id}/restore', [NewsController::class, 'restore'])->name('admin.news.restore');
    Route::delete('/news/{id}/force-delete', [NewsController::class, 'forceDelete'])->name('admin.news.forceDelete');
    Route::get('/news/trash/filter', [newsController::class, 'filterTrash'])->name('admin.news.trash.filter');

    //Quản lý topics
    Route::get('/topics', [topicsController::class, 'index'])->name('admin.topics.index');
    Route::get('/topics/filter', [topicsController::class, 'filter'])->name('admin.topics.filter');
    Route::get('/topics/create', [topicsController::class, 'create'])->name('admin.topics.create');
    Route::get('/topics/edit/{id}', [topicsController::class, 'edit'])->name('admin.topics.edit');
    Route::post('/topics/store', [topicsController::class, 'store'])->name('admin.topics.store');
    Route::put('/topics/{id}/update', [topicsController::class, 'update'])->name('admin.topics.update');
    Route::delete('/topics/delete/{id}', [topicsController::class, 'delete'])->name('admin.topics.delete');



    Route::get('/topics/trash', [topicsController::class, 'trash'])->name('admin.topics.trash');
    Route::post('/topics/{id}/restore', [topicsController::class, 'restore'])->name('admin.topics.restore');
    Route::delete('/topics/{id}/force-delete', [topicsController::class, 'forceDelete'])->name('admin.topics.forceDelete');
    Route::get('/topics/trash/filter', [topicsController::class, 'filterTrash'])->name('admin.topics.trash.filter');

    Route::post('/admin/courses/{id}/toggle-featured', [CourseController::class, 'toggleFeatured'])->name('admin.course.toggle-featured');

    Route::get('/notification/course/payment/updateSeen/{id}', [notiCoursePaymentController::class, 'updateNotiSeen']);
    Route::get('/notification/course/payment/all', [notiCoursePaymentController::class, 'index'])->name('admin.noti.coursepayment');
    Route::get('/notification/course/payment/filter', [notiCoursePaymentController::class, 'filter'])->name('admin.noti.coursepayment.filter');
    Route::get('/notification/course/payment/detail/{id}', [notiCoursePaymentController::class, 'detail'])->name('admin.noti.detail');
    Route::post('/notification/course/payment/updateSeenMultiple', [notiCoursePaymentController::class, 'updateSeenMultiple']);
    Route::post('/notification/course/payment/deleteMultiple', [notiCoursePaymentController::class, 'deleteMultiple']);


});




// Routes dành cho client
Route::middleware([CheckRoleClient::class . ':student,teacher'])->group(function () {

    Route::get('information', [UserController::class, 'information'])->name('client.information');

    // Lịch học và điểm danh
    Route::get('schedule', [UserController::class, 'schedule'])->name('client.schedule');

    // Mới thêm
    Route::get('schedule/{id}/attendance', [ClientAttendanceController::class, 'attendanceClass'])->name('admin.attendance.class');
    Route::post('schedule/attendance/summary', [ClientAttendanceController::class, 'updateSummary'])->name('attendance.summary.update');
    Route::post('schedule/attendance/save', [ClientAttendanceController::class, 'saveAttendance'])->name('client.attendance.save');
    Route::get('schedule/attendance/export', [ClientAttendanceController::class, 'exportAttendance'])->name('attendance.export');



    // Quản lý điểm số
    Route::get('score', [UserController::class, 'score'])->name('client.score');
    Route::get('/score-search', [UserController::class, 'scoreSearch'])->name('client.score.search');
    Route::get('/score/{class_id}/{course_id}', [UserController::class, 'Scoredetail'])->name('client.score.detail');
    Route::get('/score-student/{class_id}/{course_id}', [UserController::class, 'ScoredetailSearch'])->name('client.score.detailSearch');
    Route::get('/score-add/{class_id}', [UserController::class, 'Scoreadd'])->name('client.score.add');
    Route::post('/score-store/{class_id}', [UserController::class, 'Scorestore'])->name('client.score.store');
    Route::get('/score-edit/{class_id}/{id}', [UserController::class, 'Scoreedit'])->name('client.score.edit');
    Route::put('/score-update/{class_id}/{id}', [UserController::class, 'Scoreupdate'])->name('client.score.update');
    Route::get('/score-delete/{id}', [UserController::class, 'Scoredelete'])->name('client.score.delete');
    Route::get('/scores/export/{class_id}/{course_id}', [UserController::class, 'Scoreexport'])->name('client.scores.export');
    Route::post('/scores/import', [UserController::class, 'Scoreimport'])->name('client.scores.import');


    Route::get('quizz', [UserController::class, 'quizz'])->name('client.quizz');

    // Trang quản lý tài khoản
    Route::get('account', [UserController::class, 'account'])->name('client.account');
    Route::get('account/edit', [UserController::class, 'editAccount'])->name('client.account.edit');
    Route::put('account/update', [UserController::class, 'updateAccount'])->name('client.account.update');
    Route::put('account/changePassword', [UserController::class, 'changePassword'])->name('client.account.changePassword');
});

//Dành cho học sinh
Route::middleware([CheckRoleClient::class . ':student'])->prefix('student')->group(function () {
    Route::get('/course-payments/infomation', [coursePaymentController::class, 'showPaymentStudent']); //Lấy thông tin thanh toán của học sinh
    Route::post('/course-payments/updatePayment', [coursePaymentController::class, 'updatePayment']);
    Route::get('/quizz/start/{id}', [ClientQuizzesController::class, 'start'])->name('student.quizzes.start');
    Route::get('/quizz/{quiz}/show-result', [ClientQuizzesController::class, 'showResult'])->name('student.quizzes.showResult');
    Route::get('/quizz/{quiz}/show-result/{attempt}', [ClientQuizzesController::class, 'resultsQuizzStudent']); //hiển thị kết quả làm quiz của học sinh
    Route::get('/check-access-code/{code}', [ClientQuizzesController::class, 'checkAccessCode']);
    Route::post('/submit-quiz/{quizId}/class/{classId}', [ClientQuizzesController::class, 'submitQuiz'])->name('student.quizzes.submit');
    Route::get('/quizz/resulte-student/{quizzAttempts}', [ClientQuizzesController::class, 'resultsQuizzComplete'])->name('student.quizzes.resultsQuizzComplete');

    Route::get('/dashboard/hoctaps/{class}', [UserController::class, 'getHoctaps']);
});


//Dành cho giáo viên
Route::middleware([CheckRoleClient::class . ':teacher'])->prefix('teacher')->group(function () {


    Route::get('dashboard/overview/{month}/{year}', [UserController::class, 'OverviewTeacher']);
    //Quản lý quizz
    Route::get('quizzes/{id}/detail', [quizzesController::class, 'detail'])->name('teacher.quizzes.detail');
    Route::get('quizzes/filter', [quizzesController::class, 'filter'])->name('teacher.quizzes.filter');
    Route::delete('quizzes/{id}/delete', [quizzesController::class, 'delete'])->name('teacher.quizzes.delete');
    Route::post('quizzes/store', [quizzesController::class, 'store'])->name('teacher.quizzes.store');
    Route::put('quizzes/{id}/update', [quizzesController::class, 'update'])->name('teacher.quizzes.update');
    Route::get('quizzes/{id}/update-status/{status}', [quizzesController::class, 'updateStatus'])->name('teacher.quizzes.update.status');

    Route::get('/quizzes/trash', [quizzesController::class, 'trash'])->name('teacher.quizzes.trash');
    Route::post('/quizzes/{id}/restore', [quizzesController::class, 'restore'])->name('teacher.quizzes.restore');
    Route::delete('/quizzes/{id}/force-delete', [quizzesController::class, 'forceDelete'])->name('teacher.quizzes.forceDelete');
    Route::get('/quizzes/trash/filter', [quizzesController::class, 'filterTrash'])->name('teacher.quizzes.trash.filter');

    //Quản lý questions
    Route::post('quizzes/{id}/questions/store', [questionsController::class, 'store'])->name('teacher.questions.store');
    Route::delete('questions/{id}/delete', [questionsController::class, 'delete'])->name('teacher.questions.delete');
    Route::get('questions/{id}/edit', [questionsController::class, 'edit'])->name('teacher.questions.edit');
    Route::put('questions/{id}/update', [questionsController::class, 'update'])->name('teacher.questions.update');

    //Quản lý questions sentence
    Route::post('quizzes/{id}/questions-sentence/store', [questionsController::class, 'storeSentence'])->name('teacher.questions.sentence.store');
    Route::delete('questions-sentence/{id}/delete', [questionsController::class, 'deleteSentence'])->name('teacher.questions.sentence.delete');
    Route::get('questions-sentence/{id}/edit', [questionsController::class, 'editSentence'])->name('teacher.questions.sentence.edit');
    Route::put('questions-sentence/{id}/update', [questionsController::class, 'updateSentence'])->name('teacher.questions.sentence.update');


    //Quản lý kết quả quizz
    Route::get('quizzes/{id}/results', [ClientQuizzesController::class, 'results'])->name('teacher.quizzes.results');
    Route::get('quizzes/{id}/results/class/{class}', [ClientQuizzesController::class, 'resultsClass'])->name('teacher.quizzes.results.class');
    // Route::get('quizzes/results/filter/class', [quizzesController::class, 'filterResults'])->name('admin.quizzes.filter.reults.class');


    Route::get('quizzes/{id}/results/class/{class}/student/{student}', [ClientQuizzesController::class, 'resultsClassStudent'])->name('teacher.quizzes.results.class.student');
    Route::get('/quizz/{quiz}/show-result/{attempt}/student/{student}', [ClientQuizzesController::class, 'resultsQuizzStudent']); //hiển thị kết quả làm quiz của học sinh


});
