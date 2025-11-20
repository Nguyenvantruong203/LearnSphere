<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Instructor\GoogleAuthController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Broadcast;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout']);
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

Route::middleware('auth:sanctum')->prefix('chat')->group(function () {
    Route::get('/threads', [ChatController::class, 'getThreads']);
    Route::get('/{threadId}/messages', [ChatController::class, 'getMessages']);
    Route::post('/{threadId}/messages', [ChatController::class, 'sendMessage']);

    // 🔹 Student ↔ Admin (hỗ trợ người dùng)
    Route::post('/support/user', [ChatController::class, 'startUserSupport']);

    Route::post('/consult/start', [ChatController::class, 'startConsult']);
});

Route::middleware('auth:sanctum')->prefix('notifications')->group(function () {
    Route::get('/', [NotificationController::class, 'index']);
    Route::post('/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/read-all', [NotificationController::class, 'markAllAsRead']);
});
