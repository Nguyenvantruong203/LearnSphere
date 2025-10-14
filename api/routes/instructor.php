<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Instructor\CourseController;
use App\Http\Controllers\Instructor\TopicController;
use App\Http\Controllers\Instructor\LessonController;
use App\Http\Controllers\Instructor\GoogleAuthController;
use App\Http\Controllers\Instructor\QuizController;
use App\Http\Controllers\Instructor\LessonQuestionController;
use App\Http\Controllers\Instructor\TopicQuestionController;

/*
|--------------------------------------------------------------------------
| Instructor Routes (API for learners)
|--------------------------------------------------------------------------
|
| Đây là nơi khai báo các route cho học viên (Instructor app).
| Các route này chỉ có quyền đọc và làm bài (submit), không có quyền quản trị.
|
*/

Route::middleware('auth:sanctum')->prefix('instructor')->group(function () {
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
