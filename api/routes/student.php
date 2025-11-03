<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Student\CourseController;
use App\Http\Controllers\Student\VNPayController;
use App\Http\Controllers\Student\CouponController;
use App\Http\Controllers\Student\LessonController;
use App\Http\Controllers\Student\QuizController;
use App\Http\Controllers\Student\ProfileController;
use App\Http\Controllers\Student\InstructorController;

/*
|--------------------------------------------------------------------------
| Student Routes (API for learners)
|--------------------------------------------------------------------------
|
| Đây là nơi khai báo các route cho học viên (Student app).
| Các route này chỉ có quyền đọc và làm bài (submit), không có quyền quản trị.
|
*/

Route::post('/create-payment', [VNPayController::class, 'createPayment'])->name('vnpay.create');
Route::get('/vnpay-return', [VNPayController::class, 'vnpayReturn'])->name('vnpay.return');
Route::get('/vnpay-ipn', [VNPayController::class, 'vnpayIpn'])->name('vnpay.ipn');

Route::get('/student/courses', [CourseController::class, 'index']);
Route::get('/student/courses/{id}', [CourseController::class, 'show']);

Route::get('/instructors', [InstructorController::class, 'index']);
Route::post('/instructors/apply', [InstructorController::class, 'apply']);

Route::middleware('auth:sanctum')->prefix('student')->group(function () {
    Route::post('/profile/update', [ProfileController::class, 'updateProfile']);
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar']);

    Route::get('/courses/recommended', [CourseController::class, 'recommended']);
    Route::post('/courses/{id}/enroll', [CourseController::class, 'enroll']);
    Route::get('/courses/{id}/check-access', [CourseController::class, 'checkAccess']);
    Route::get('/my-courses', [CourseController::class, 'myCourses']);

    Route::post('/apply-coupon', [CouponController::class, 'applyCoupon']);

    Route::get('/courses/{courseId}/lessons', [LessonController::class, 'getLessonListByCourseId']);
    Route::get('/lessons/{lessonId}/lesson-detail', [LessonController::class, 'getLessonDetail']);


    Route::get('/quizzes/{quizId}/quiz-detail', [QuizController::class, 'getQuizDetail']);
    Route::post('/quizzes/{quizId}/start', [QuizController::class, 'startQuizAttempt']);
    Route::post('/quizzes/{quizId}/submit', [QuizController::class, 'submitQuizAttempt']);
    Route::get('/quizzes/{quizId}/review/{attemptId}', [QuizController::class, 'getQuizReview']);
    //history
    Route::get('/quizzes/{quizId}/attempts', [QuizController::class, 'getQuizAttempts']);


});
