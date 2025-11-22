<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Topic;
use Illuminate\Support\Facades\Auth;


class LessonController extends Controller
{
    /**
     * Lấy toàn bộ topic + lesson + quiz của một khóa học
     * Dùng để hiển thị sidebar học tập
     */
    public function getLessonListByCourseId($courseId)
    {
        $user = Auth::user();

        // 🧩 Kiểm tra khóa học tồn tại
        $course = Course::find($courseId);
        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Khóa học không tồn tại.'
            ], 404);
        }

        // 🧩 Load toàn bộ Topic + Lesson + Quiz
        $topics = Topic::with([
            'lessons' => function ($q) {
                $q->select('id', 'topic_id', 'title', 'order', 'duration_seconds')
                    ->orderBy('order');
            },
            'lessons.quiz:id,lesson_id,topic_id,title',
            'quiz:id,topic_id,title',
            'flashcardSets:id,topic_id,title'
        ])
            ->where('course_id', $courseId)
            ->orderBy('order')
            ->get(['id', 'title', 'course_id', 'order'])
            ->map(function ($topic) use ($user) {

                // Thêm flashcard_set_id vào payload
                $topic->flashcard_set_id = $topic->flashcardSet->id ?? null;

                // Xử lý quiz topic
                $topic->quiz = $topic->quiz ? [
                    'id' => $topic->quiz->id,
                    'topic_id' => $topic->quiz->topic_id,
                    'title' => $topic->quiz->title,
                ] : null;

                // xử lý bài học
                $topic->lessons = $topic->lessons->map(function ($lesson) use ($user) {
                    $lesson->duration_minutes = $lesson->duration_seconds
                        ? round($lesson->duration_seconds / 60)
                        : null;

                    $lesson->quiz = $lesson->quiz ? [
                        'id' => $lesson->quiz->id,
                        'topic_id' => $lesson->quiz->topic_id,
                        'title' => $lesson->quiz->title,
                    ] : null;

                    unset($lesson->topic_id, $lesson->duration_seconds);
                    return $lesson;
                });

                return $topic;
            });

        // 🧩 Trả về kết quả chuẩn JSON
        return response()->json([
            'success' => true,
            'data' => [
                'course' => [
                    'id' => $course->id,
                    'title' => $course->title,
                ],
                'topics' => $topics,
            ],
        ]);
    }

    /**
     * Lấy chi tiết 1 bài học cụ thể
     * Dùng cho khu vực video player (LessonPlayer)
     */
    public function getLessonDetail($lessonId)
    {
        $lesson = \App\Models\Lesson::with([
            'topic.course:id,title,slug',
            'quiz:id,lesson_id,title,duration_minutes'
        ])->find($lessonId);

        if (!$lesson) {
            return response()->json([
                'success' => false,
                'message' => 'Bài học không tồn tại.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'lesson' => [
                    'id' => $lesson->id,
                    'title' => $lesson->title,
                    'video_provider' => $lesson->video_provider,
                    'video_id' => $lesson->video_id,
                    'video_url' => $lesson->video_url,
                    'content' => $lesson->content,
                    'duration_seconds' => $lesson->duration_seconds,
                    'quiz' => $lesson->quiz,
                ],
                'course' => [
                    'id' => $lesson->topic->course->id,
                    'title' => $lesson->topic->course->title,
                ],
            ],
        ]);
    }
}
