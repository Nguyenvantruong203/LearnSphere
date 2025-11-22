<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Instructor\CourseController;
use App\Http\Controllers\Instructor\TopicController;
use App\Http\Controllers\Instructor\LessonController;
use App\Http\Controllers\Instructor\GoogleAuthController;
use App\Http\Controllers\Instructor\QuizController;
use App\Http\Controllers\Instructor\LessonQuestionController;
use App\Http\Controllers\Instructor\TopicQuestionController;
use App\Http\Controllers\Instructor\InstructorDashboardController;
use App\Http\Controllers\Instructor\FlashcardController;
use App\Http\Controllers\Instructor\FlashcardSetController;

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
    Route::post('/courses/{course}/resubmit', [CourseController::class, 'resubmit']);

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

    Route::prefix('dashboard')->group(function () {
        // ===== OVERVIEW =====
        Route::get('/overview', [InstructorDashboardController::class, 'overview']);

        // ===== REVENUE =====
        Route::get('/revenue/summary', [InstructorDashboardController::class, 'revenueSummary']);
        Route::get('/revenue/by-month', [InstructorDashboardController::class, 'revenueByMonth']);
        Route::get('/revenue/by-course', [InstructorDashboardController::class, 'revenueByCourse']);

        // ===== STUDENTS & LEARNING ACTIVITY =====
        Route::get('/students/summary', [InstructorDashboardController::class, 'studentSummary']);
        Route::get('/students/activity', [InstructorDashboardController::class, 'studentActivity']);
        Route::get('/courses/progress', [InstructorDashboardController::class, 'courseProgress']);

        Route::get('/chat/stats', [InstructorDashboardController::class, 'chatStats']);
    });

    Route::get('/topics/{topicId}/flashcard-sets', [FlashcardSetController::class, 'index']);
    Route::post('/topics/{topicId}/flashcard-sets', [FlashcardSetController::class, 'store']);

    Route::put('/flashcard-sets/{setId}', [FlashcardSetController::class, 'update']);
    Route::delete('/flashcard-sets/{setId}', [FlashcardSetController::class, 'destroy']);

    Route::get('/flashcard-sets/{setId}/flashcards', [FlashcardController::class, 'index']);
    Route::post('/flashcard-sets/{setId}/flashcards', [FlashcardController::class, 'store']);

    Route::put('/flashcards/{id}', [FlashcardController::class, 'update']);
    Route::delete('/flashcards/{id}', [FlashcardController::class, 'destroy']);
});
