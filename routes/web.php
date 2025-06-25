<?php

use App\Http\Controllers\admin\AccountController;
use App\Http\Controllers\client\HomeController;
use App\Http\Controllers\admin\dashboardController;
use App\Http\Controllers\admin\ScoreController;

use App\Http\Controllers\admin\ClassController;
use App\Http\Controllers\admin\SchedulesController;

use App\Http\Controllers\client\loginController;
use App\Http\Controllers\client\UserController;
use App\Http\Controllers\admin\coursePaymentController;
use App\Http\Controllers\admin\quizzesController;
use App\Http\Controllers\admin\AttendanceController;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\CheckRoleClient;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/', [HomeController::class, 'index'])->name('home');


Route::get('logout', [loginController::class, 'logout'])->name('auth.logout');
Route::get('login', [loginController::class, 'login'])->name('auth.login');
Route::post('login', [loginController::class, 'login'])->name('loginAuth');

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
    Route::get('/admin/classes/{id}/students', [ClassController::class, 'students'])->name('admin.classes.students');
    Route::post('/admin/classes/{id}/students', [ClassController::class, 'addStudent'])->name('admin.classes.add-student');
    Route::delete('/admin/classes/{id}/students/{studentId}', [ClassController::class, 'removeStudent'])->name('admin.classes.remove-student');
    // Danh sách lịch hoc của lớp
    Route::get('/classes/{id}/schedules', [ClassController::class, 'schedules'])->name('admin.classes.schedules');


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
