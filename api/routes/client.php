<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\CourseController;


/*
|--------------------------------------------------------------------------
| Client Routes (API for learners)
|--------------------------------------------------------------------------
|
| Đây là nơi khai báo các route cho học viên (client app).
| Các route này chỉ có quyền đọc và làm bài (submit), không có quyền quản trị.
|
*/

// Auth
// Profile
Route::middleware('auth:sanctum')->prefix('client')->group(function () {
    
    Route::get('/courses', [CourseController::class, 'index']);
    Route::get('/courses/{id}', [CourseController::class, 'show']);
});


