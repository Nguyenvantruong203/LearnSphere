<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Instructor\GoogleAuthController;
use App\Http\Controllers\Admin\AuthController;

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
