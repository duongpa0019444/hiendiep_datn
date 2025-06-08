<?php

use App\Http\Controllers\admin\AccountController;
use App\Http\Controllers\client\HomeController;
use App\Http\Controllers\admin\dashboardController;
use App\Http\Controllers\admin\ScoreController;
use App\Http\Controllers\client\loginController;
use App\Http\Controllers\client\UserController;
use App\Http\Controllers\admin\coursePaymentController;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\CheckRoleClient;
use Illuminate\Support\Facades\Route;


Route::get('/', [HomeController::class, 'index'])->name('home');


Route::get('logout',[loginController::class, 'logout'])->name('auth.logout');
Route::post('login', [loginController::class, 'login'])->name('loginAuth');
Route::post('register', [loginController::class, 'register'])->name('registerAuth');

Route::get('/forgot-password', [loginController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [loginController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password', [loginController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [loginController::class, 'resetPassword'])->name('password.update');

// Route dành cho admin và nhân viên --
Route::middleware([CheckRole::class . ':admin,staff'])->prefix('admin')->group(function () {
    // trang quản trị cho admin
    Route::get('dashboard', [DashboardController::class, 'dashBoard'])->name('admin.dashboard');
    Route::get('dashboard/chart/{course_id}', [DashboardController::class, 'chart']);

    // Trang quản lý tài khoản
    Route::get('/account', [AccountController::class, 'account'])->name('admin.account');
    Route::get('/account/{role}', [AccountController::class, 'list'])->name('admin.account.list');
    Route::get('/account-add/{role}', [AccountController::class, 'add'])->name('admin.account.add');
    Route::post('/account-store/{role}', [AccountController::class, 'store'])->name('admin.account.store');
    Route::get('/account-edit/{role}/{id}', [AccountController::class, 'edit'])->name('admin.account.edit');
    Route::post('/account-store/{role}/{id}', [AccountController::class, 'update'])->name('admin.account.update');
    Route::get('/account-delete/{role}/{id}', [AccountController::class, 'delete'])->name('admin.account.delete');


    // Quản lí điểm số
    Route::get('/score', [ScoreController::class, 'score'])->name('admin.score');

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
});

// Routes dành cho client
Route::middleware([CheckRoleClient::class . ':student,teacher'])->group(function () {
    // trang quản trị cho người dùng
    Route::get('information', [UserController::class, 'information'])->name('client.information');
    Route::get('schedule', [UserController::class, 'schedule'])->name('client.schedule');
    Route::get('score', [UserController::class, 'score'])->name('client.score');
    Route::get('quizz', [UserController::class, 'quizz'])->name('client.quizz');
    Route::get('account', [UserController::class, 'account'])->name('client.account');
});
