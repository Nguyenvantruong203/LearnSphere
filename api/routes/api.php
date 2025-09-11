<?php

use App\Http\Controllers\Authcontroller;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Xác thực email
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->middleware('signed')
    ->name('verification.verify');

// Gửi lại email xác thực
Route::post('/email/verification-notification', [AuthController::class, 'resendVerificationEmail'])
    ->middleware(['auth:sanctum', 'throttle:6,1'])
    ->name('verification.send');

// Google Auth Routes
Route::get('/auth/google/redirect', [AuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);


// Các định tuyến yêu cầu xác thực
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/users/{id}/approve', [AuthController::class, 'approveUser']);

    // Profile routes
    Route::post('/profile', [UserController::class, 'updateProfile']);
    Route::post('/profile/avatar', [UserController::class, 'updateAvatar']);

    // User CRUD
    Route::apiResource('users', UserController::class);

    // Course CRUD
    Route::apiResource('courses', CourseController::class)->except(['update']);
    Route::post('courses/{course}', [CourseController::class, 'update'])->name('courses.update');

    // Topic CRUD
    Route::apiResource('topics', TopicController::class);
});

// Đặt lại mật khẩu
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
