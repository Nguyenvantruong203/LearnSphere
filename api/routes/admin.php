<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CouponController;


Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/users/{id}/approve', [AuthController::class, 'approveUser']);

    // Profile routes
    Route::post('/profile', [UserController::class, 'updateProfile']);
    Route::post('/profile/avatar', [UserController::class, 'updateAvatar']);

    // Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');        // Danh sách users
    Route::post('/users', [UserController::class, 'store'])->name('users.store');       // Tạo mới user
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');   // Chi tiết 1 user
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');   // Update user
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Lấy danh sách tất cả coupon
    Route::get('/coupons', [CouponController::class, 'index'])->name('coupons.index');
    Route::post('/coupons', [CouponController::class, 'store'])->name('coupons.store');
    Route::put('/coupons/{id}', [CouponController::class, 'update'])->name('coupons.update');
    Route::delete('/coupons/{id}', [CouponController::class, 'destroy'])->name('coupons.destroy');
});

// Đặt lại mật khẩu
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
