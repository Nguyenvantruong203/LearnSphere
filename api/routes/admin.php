<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CourseController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\AdminDashboardController;

Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);

    // Profile routes
    Route::post('/profile', [UserController::class, 'updateProfile']);
    Route::post('/profile/avatar', [UserController::class, 'updateAvatar']);

    // Users
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::post('/users/{id}/approve', [UserController::class, 'approveUser']);
    Route::post('/users/{id}/reject', [UserController::class, 'rejectUser']);

    // Coupons management
    Route::get('/coupons', [CouponController::class, 'index'])->name('coupons.index');
    Route::post('/coupons', [CouponController::class, 'store'])->name('coupons.store');
    Route::put('/coupons/{id}', [CouponController::class, 'update'])->name('coupons.update');
    Route::delete('/coupons/{id}', [CouponController::class, 'destroy'])->name('coupons.destroy');

    // Course approval management
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/{id}', [CourseController::class, 'show'])->name('courses.show');
    Route::post('/courses/{id}/approve', [CourseController::class, 'approve'])->name('courses.approve');
    Route::post('/courses/{id}/reject', [CourseController::class, 'reject'])->name('courses.reject');

    Route::prefix('dashboard')->group(function () {
        Route::get('/overview', [AdminDashboardController::class, 'overview']);
        Route::get('/revenue/by-month', [AdminDashboardController::class, 'revenueByMonth']);
        Route::get('/courses/top', [AdminDashboardController::class, 'topCourses']);
        Route::get('/users/stats', [AdminDashboardController::class, 'userStats']);
        Route::get('/orders/recent', [AdminDashboardController::class, 'recentOrders']);
        Route::get('/users/new-daily', [AdminDashboardController::class, 'newUsersDaily']);
        Route::get('/chat/stats', [AdminDashboardController::class, 'chatStats']);
        Route::get('/system/health', [AdminDashboardController::class, 'systemHealth']);
    });
});

// Đặt lại mật khẩu
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
