<?php

use App\Http\Controllers\admin\AccountController;
use App\Http\Controllers\client\HomeController;
use App\Http\Controllers\admin\dashboardController;
use App\Http\Controllers\admin\ScoreController;
use App\Http\Controllers\client\loginController;
use App\Http\Controllers\client\UserController;
use App\Http\Controllers\admin\coursePaymentController;
use App\Http\Controllers\admin\NotificationsController;
use App\Http\Controllers\admin\quizzesController;
use App\Http\Controllers\admin\TeacherRulesController;
use App\Http\Controllers\admin\TeacherSalaryController;
use App\Http\Controllers\client\CourseController;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\CheckRoleClient;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/course', [CourseController::class, 'index'])->name('client.course');
Route::get('/course/{slug}_{id}', [CourseController::class, 'detail'])->name('client.course.detail');
Route::get('/course-search', [CourseController::class, 'search'])->name('client.course.search');


Route::get('/logout', [loginController::class, 'logout'])->name('auth.logout');
Route::post('/login', [loginController::class, 'login'])->name('loginAuth');
// Route::post('register', [loginController::class, 'register'])->name('registerAuth');

Route::get('/forgot-password', [loginController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [loginController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password', [loginController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [loginController::class, 'resetPassword'])->name('password.update');



// Route dành cho admin và nhân viên --
Route::middleware([CheckRole::class . ':admin,staff'])->prefix('admin')->group(function () {
    // trang quản trị cho admin
    Route::get('dashboard', [DashboardController::class, 'dashBoard'])->name('admin.dashboard');
    Route::get('dashboard/chart/{course_id}', [DashboardController::class, 'chart']);
    // Quản lí điểm số
    Route::get('/score', [ScoreController::class, 'score'])->name('admin.score');

    // Trang quản lý tài khoản
    Route::get('/account', [AccountController::class, 'account'])->name('admin.account');
    Route::get('/account/{role}', [AccountController::class, 'list'])->name('admin.account.list');
    Route::get('/account-add/{role}', [AccountController::class, 'add'])->name('admin.account.add');
    Route::post('/account-store/{role}', [AccountController::class, 'store'])->name('admin.account.store');
    Route::get('/account-edit/{role}/{id}', [AccountController::class, 'edit'])->name('admin.account.edit');
    Route::put('/account-update/{role}/{id}', [AccountController::class, 'update'])->name('admin.account.update');
    Route::get('/account-delete/{role}/{id}', [AccountController::class, 'delete'])->name('admin.account.delete');

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


    //Trang quản lý quizz
    Route::get('quizzes', [quizzesController::class, 'index'])->name('admin.quizz');
    Route::get('quizzes/{id}/detail', [quizzesController::class, 'detail'])->name('admin.quizzes.detail');
    Route::get('quizzes/filter', [quizzesController::class, 'filter'])->name('admin.quizzes.filter');
    Route::delete('quizzes/{id}/delete', [quizzesController::class, 'delete'])->name('admin.quizzes.delete');
    Route::post('quizzes/store', [quizzesController::class, 'store'])->name('admin.quizzes.store');
    Route::put('quizzes/{id}/update', [quizzesController::class, 'update'])->name('admin.quizzes.update');


    //Trang quản lý lương giáo viên
    Route::get('teacher-salaries', [TeacherSalaryController::class, 'index'])->name('admin.teacher_salaries');
    Route::get('/api/salary-data', [TeacherSalaryController::class, 'getData'])->name('admin.teacher_salaries.data');
    Route::post('/api/salary-data', [TeacherSalaryController::class, 'save'])->name('admin.teacher_salaries.save');
    Route::post('/admin/teacher-salaries/update-payment', [TeacherSalaryController::class, 'updatePayment'])->name('admin.teacher_salaries.upload');
    Route::get('/admin/teacher-salaries/filter', [TeacherSalaryController::class, 'filter'])->name('admin.teacher_salaries.filter');


    //Chi tiết lương giáo viên
    Route::get('/admin/teacher-salary-rules/{id}/details', [TeacherRulesController::class, 'details'])->name('admin.teacher_salary_rules.details');
    Route::get('/teacher-salary-rules/index', [TeacherRulesController::class, 'indexRules'])->name('admin.teacher-salary-rules.indexRules');
    Route::post('/admin/teacher-salary-rules/store', [TeacherRulesController::class, 'store'])->name('admin.teacher-salary-rules.store');

    // Gửi thông báo
    Route::get('admin/notifications', [NotificationsController::class, 'index'])->name('admin.notifications');
    Route::get('/admin/notifications/filter', [NotificationsController::class, 'filter'])->name('admin.notifications.filter');
    Route::post('admin/notifications/seed', [NotificationsController::class, 'seed'])->name('admin.notifications.seed');
    Route::post('/admin/notifications/delete', [NotificationsController::class, 'destroy'])->name('admin.notifications.delete');
});




// Routes dành cho client
Route::middleware([CheckRoleClient::class . ':student,teacher'])->group(function () {
    // trang quản trị cho người dùng
    Route::get('information', [UserController::class, 'information'])->name('client.information');
    Route::get('schedule', [UserController::class, 'schedule'])->name('client.schedule');
    Route::get('score', [UserController::class, 'score'])->name('client.score');
    Route::get('quizz', [UserController::class, 'quizz'])->name('client.quizz');
    Route::get('account', [UserController::class, 'account'])->name('client.account');
    Route::get('course-payments/infomation', [coursePaymentController::class, 'showPaymentStudent']); //Lấy thông tin thanh toán của học sinh
    Route::post('course-payments/updatePayment', [coursePaymentController::class, 'updatePayment'])->name('admin.course_payments.updatePayment');
});
