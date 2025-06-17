<?php

use App\Http\Controllers\admin\ClassController;
use App\Http\Controllers\client\HomeController;
use App\Http\Controllers\admin\dashboardController;
use App\Http\Controllers\admin\SchedulesController;
use App\Http\Controllers\client\loginController;
use App\Http\Controllers\client\UserController;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Route;


Route::get('/', [HomeController::class, 'index'])->name('home');


Route::get('logout', [loginController::class, 'logout'])->name('auth.logout');
Route::get('login', [loginController::class, 'login'])->name('auth.login');
Route::post('login', [loginController::class, 'login'])->name('loginAuth');

Route::get('register', [loginController::class, 'register'])->name('auth.register');
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

});

// // Routes cho tất cả người dùng (bao gồm học sinh)
// Route::middleware([CheckRole::class . ':admin,staff,student,parent,teacher'])->group(function () {
//     // trang quản trị cho người dùng
//     Route::get('dashboard', [UserController::class, 'dashBoard'])->name('client.dashboard');
// });
