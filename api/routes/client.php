<?php

use Illuminate\Support\Facades\Route;


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
Route::middleware('auth:sanctum')->group(function () {

});


