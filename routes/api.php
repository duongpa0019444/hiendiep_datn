<?php

use App\Http\Controllers\admin\coursePaymentController;
use App\Http\Controllers\client\UserController;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Route;

Route::apiResource('coursePayments', coursePaymentController::class);
Route::apiResource('users', UserController::class);

Route::get('/course-payments/filter', [CoursePaymentController::class, 'filter']);

