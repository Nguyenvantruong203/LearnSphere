<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\CourseController;
use App\Http\Controllers\Client\VNPayController;
use App\Http\Controllers\Client\CouponController;

/*
|--------------------------------------------------------------------------
| Client Routes (API for learners)
|--------------------------------------------------------------------------
|
| Đây là nơi khai báo các route cho học viên (client app).
| Các route này chỉ có quyền đọc và làm bài (submit), không có quyền quản trị.
|
*/


Route::post('/create-payment', [VNPayController::class, 'createPayment'])->name('vnpay.create');
Route::get('/vnpay-return', [VNPayController::class, 'vnpayReturn'])->name('vnpay.return');
Route::post('/vnpay-ipn', [VNPayController::class, 'vnpayIpn'])->name('vnpay.ipn');


Route::middleware('auth:sanctum')->prefix('client')->group(function () {

    Route::get('/courses', [CourseController::class, 'index']);
    Route::get('/courses/{id}', [CourseController::class, 'show']);

     Route::post('/apply-coupon', [CouponController::class, 'applyCoupon']);
});
