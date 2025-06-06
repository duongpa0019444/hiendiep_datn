<?php

use App\Http\Controllers\admin\AccountController;
use App\Http\Controllers\client\HomeController;
use App\Http\Controllers\admin\dashboardController;
use App\Http\Controllers\client\loginController;
use App\Http\Controllers\client\UserController;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Route;


Route::get('/', [HomeController::class, 'index'])->name('home');


Route::get('logout',[loginController::class, 'logout'])->name('auth.logout');
Route::get('login',[loginController::class, 'login'])->name('auth.login');
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
    Route::get('/dashboard', [DashboardController::class, 'dashBoard'])->name('admin.dashboard');
    Route::get('/account', [AccountController::class, 'account'])->name('admin.account');
    Route::get('/account/{role}', [AccountController::class, 'list'])->name('admin.account.list');
    Route::get('/account-add/{role}', [AccountController::class, 'add'])->name('admin.account.add');
    Route::post('/account-store/{role}', [AccountController::class, 'store'])->name('admin.account.store');
    Route::get('/account-edit/{role}/{id}', [AccountController::class, 'edit'])->name('admin.account.edit');
    Route::post('/account-store/{role}/{id}', [AccountController::class, 'update'])->name('admin.account.update');
    Route::get('/account-delete/{role}/{id}', [AccountController::class, 'delete'])->name('admin.account.delete');


});

// // Routes cho tất cả người dùng (bao gồm học sinh)
// Route::middleware([CheckRole::class . ':admin,staff,student,parent,teacher'])->group(function () {
//     // trang quản trị cho người dùng
//     Route::get('dashboard', [UserController::class, 'dashBoard'])->name('client.dashboard');
// });
