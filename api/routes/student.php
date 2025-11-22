<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Student\CourseController;
use App\Http\Controllers\Student\VNPayController;
use App\Http\Controllers\Student\CouponController;
use App\Http\Controllers\Student\LessonController;
use App\Http\Controllers\Student\QuizController;
use App\Http\Controllers\Student\ProfileController;
use App\Http\Controllers\Student\InstructorController;
use App\Http\Controllers\Student\LessonCompletionController;
use App\Http\Controllers\Student\CertificateController;
use App\Http\Controllers\Student\ReviewController;
use App\Http\Controllers\Student\FlashcardLearnController;

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

    Route::post('/lessons/{lesson_id}/complete', [LessonCompletionController::class, 'complete']);
    Route::get('/courses/{course_id}/progress', [LessonCompletionController::class, 'getCourseProgress']);

    // Lấy toàn bộ chứng chỉ
    Route::get('/certificates', [CertificateController::class, 'listByUser']);

    Route::get('/certificates/{id}', [CertificateController::class, 'getCertificationDetail']);

    // Lấy chứng chỉ theo khóa học (GET /student/certificates?course_id=1)
    Route::get('/certificates/by-course/{courseId}', [CertificateController::class, 'getByCourse']);

    // Tải PDF chứng chỉ
    Route::get('/certificates/{id}/download', [CertificateController::class, 'download']);

    Route::prefix('reviews')->middleware('auth:sanctum')->group(function () {
        Route::get('/course/{courseId}', [ReviewController::class, 'index']);
        Route::post('/course/{courseId}', [ReviewController::class, 'store']);
        Route::put('/{id}', [ReviewController::class, 'update']);
        Route::delete('/{id}', [ReviewController::class, 'destroy']);
        Route::get('/summary/{courseId}', [ReviewController::class, 'summary']);
        Route::get('/course/{courseId}/can-review', [ReviewController::class, 'canReview']);
    });

    Route::get('/topics/{topicId}/flashcards/learn', [FlashcardLearnController::class, 'getFlashcardsForLearning']);
    Route::post('/flashcards/{id}/review', [FlashcardLearnController::class, 'saveFlashcardReview']);
});
