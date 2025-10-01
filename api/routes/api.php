<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\TopicController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LessonController;
use App\Http\Controllers\Admin\GoogleAuthController;
use App\Http\Controllers\Admin\QuizController;
use App\Http\Controllers\Admin\LessonQuestionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\TopicQuestionController;

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
Route::get('/google/callback-login', [AuthController::class, 'handleGoogleCallback']);
// Google OAuth for YouTube
Route::get('/google/connect-youtube', [GoogleAuthController::class, 'redirect'])->name('google.youtube.connect');
Route::get('/google/callback-youtube', [GoogleAuthController::class, 'callback'])->name('google.youtube.callback');
// Các định tuyến yêu cầu xác thực
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

    // Courses
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');         // Danh sách courses
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');        // Tạo mới course
    Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');  // Chi tiết course
    Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');  // Update course
    Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');

    // Topics
    Route::get('/courses/{course}/topics', [TopicController::class, 'index'])->name('courses.topics.index');
    Route::post('/courses/{course}/topics', [TopicController::class, 'store'])->name('courses.topics.store');
    Route::get('/topics/{topic}', [TopicController::class, 'show'])->name('topics.show');
    Route::put('/topics/{topic}', [TopicController::class, 'update'])->name('topics.update');
    Route::patch('/topics/{topic}', [TopicController::class, 'update']);
    Route::delete('/topics/{topic}', [TopicController::class, 'destroy'])->name('topics.destroy');

    // Lessons
    Route::get('/topics/{topic}/lessons', [LessonController::class, 'index'])
        ->name('topics.lessons.index');
    Route::post('/topics/{topic}/lessons', [LessonController::class, 'store'])
        ->name('topics.lessons.store');
    Route::get('/lessons/{lesson}', [LessonController::class, 'show'])
        ->name('lessons.show');
    Route::put('/lessons/{lesson}', [LessonController::class, 'update'])
        ->name('lessons.update');
    Route::patch('/lessons/{lesson}', [LessonController::class, 'update']);
    Route::delete('/lessons/{lesson}', [LessonController::class, 'destroy'])
        ->name('lessons.destroy');
    // Extra: upload file cho Lesson
    Route::post('/topics/{topic}/lessons/upload', [LessonController::class, 'upload'])
        ->name('topics.lessons.upload');
    // Extra: reorder lesson trong 1 topic
    Route::patch('/topics/{topic}/lessons/reorder', [LessonController::class, 'reorder'])
        ->name('topics.lessons.reorder');
    Route::get('/me/youtube/status', [GoogleAuthController::class, 'status']);

    // Quiz
    Route::get('lessons/{lesson}/quizzes', [QuizController::class, 'index']);
    Route::post('lessons/{lesson}/quizzes', [QuizController::class, 'store']);
    Route::get('topics/{topic}/quizzes', [QuizController::class, 'indexForTopic']);
    Route::post('topics/{topic}/quizzes', [QuizController::class, 'storeForTopic']);
    Route::get('quizzes/{quiz}', [QuizController::class, 'show']);
    Route::put('quizzes/{quiz}', [QuizController::class, 'update']);
    Route::delete('quizzes/{quiz}', [QuizController::class, 'destroy']);

    // Questions for lesson
    Route::post('quizzes/{quiz}/lesson-questions/ai-generate', [LessonQuestionController::class, 'generate']);
    Route::get('quizzes/{quiz}/lesson-questions', [LessonQuestionController::class, 'indexForLesson']);
    Route::post('quizzes/{quiz}/lesson-questions', [LessonQuestionController::class, 'createForLesson']);
    Route::get('lesson-questions/{question}', [LessonQuestionController::class, 'show']);
    Route::put('quizzes/{quiz}/lesson-questions/{question}', [LessonQuestionController::class, 'updateForLesson']);

    // Questions for topic
    Route::get('quizzes/{quiz}/topic-questions', [TopicQuestionController::class, 'indexForTopic']);
    Route::post('quizzes/{quiz}/topic-questions/ai-suggest', [TopicQuestionController::class, 'suggestForTopic']);
    Route::get('quizzes/{quiz}/topic-questions/pool', [TopicQuestionController::class, 'poolForTopic']);
    Route::post('quizzes/{quiz}/topic-questions/publish', [TopicQuestionController::class, 'publishForTopic']);
    Route::put('quizzes/{quiz}/topic-questions/{question}', [TopicQuestionController::class, 'updateForTopic']);

    Route::delete('quizzes/{quiz}/questions/{question}', [LessonQuestionController::class, 'destroy']);
});

// Đặt lại mật khẩu
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
