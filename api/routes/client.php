<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\CourseController;
use App\Http\Controllers\Client\VNPayController;
use App\Http\Controllers\Client\CouponController;
use App\Http\Controllers\Client\LessonController;
use App\Http\Controllers\Client\QuizController;

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
Route::get('/vnpay-ipn', [VNPayController::class, 'vnpayIpn'])->name('vnpay.ipn');

Route::get('/client/courses', [CourseController::class, 'index']);
Route::get('/client/courses/{id}', [CourseController::class, 'show']);

Route::middleware('auth:sanctum')->prefix('client')->group(function () {


    Route::get('/courses/{id}/check-access', [CourseController::class, 'checkAccess']);
    Route::get('/my-courses', [CourseController::class, 'myCourses']);

    Route::post('/apply-coupon', [CouponController::class, 'applyCoupon']);

    Route::get('/courses/{courseId}/lessons', [LessonController::class, 'getLessonListByCourseId']);
    Route::get('/lessons/{lessonId}/lesson-detail', [LessonController::class, 'getLessonDetail']);

    // 🧩 Lấy chi tiết quiz + câu hỏi
    Route::get('/quizzes/{quizId}/quiz-detail', [QuizController::class, 'getQuizDetail']);

    // 🧩 Bắt đầu một lượt làm quiz (tạo record quiz_attempts)
    Route::post('/quizzes/{quizId}/start', [QuizController::class, 'startQuizAttempt'])
        ->middleware('auth:sanctum');

    // 🧩 Nộp bài quiz (lưu câu trả lời & chấm điểm)
    Route::post('/quizzes/{quizId}/submit', [QuizController::class, 'submitQuizAttempt'])
        ->middleware('auth:sanctum');

    // 🧩 Xem lại kết quả bài làm (review)
    Route::get('/quizzes/{quizId}/review', [QuizController::class, 'getQuizReview'])
        ->middleware('auth:sanctum');
});
