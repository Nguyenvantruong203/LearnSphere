<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\LessonCompletion;
use App\Models\UserCourse;
use App\Models\UserProgress;
use App\Services\CertificateService;

class LessonCompletionController extends Controller
{
    public function complete($lesson_id)
    {
        $user = auth()->user();
        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Lấy bài học kèm topic + course
        $lesson = Lesson::with('topic.course')->findOrFail($lesson_id);
        $courseId = $lesson->topic->course_id;

        // Kiểm tra quyền truy cập (Eloquent)
        $hasAccess = UserCourse::where([
            'user_id' => $user->id,
            'course_id' => $courseId,
            'is_paid' => true,
        ])->exists();

        if (! $hasAccess) {
            return response()->json(['message' => 'You do not have access to this course.'], 403);
        }

        // Lưu completion (Eloquent)
        LessonCompletion::updateOrCreate(
            [
                'user_id'   => $user->id,
                'lesson_id' => $lesson_id,
            ],
            [
                'completed_at' => now(),
            ]
        );

        // Cập nhật tiến độ
        $progress = $this->updateCourseProgress($user->id, $courseId);

        return response()->json([
            'message'  => 'Lesson marked as completed.',
            'progress' => $progress,
            'is_completed' => true,
        ]);
    }

    public function getCourseProgress($course_id)
    {
        $user = auth()->user();
        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $progress = UserProgress::where('user_id', $user->id)
            ->where('course_id', $course_id)
            ->value('progress_percent') ?? 0;

        return response()->json([
            'course_id' => $course_id,
            'progress'  => (float) $progress,
        ]);
    }


    private function updateCourseProgress($userId, $courseId)
    {
        // Lấy tất cả lesson thuộc khóa (Eloquent)
        $lessonIds = Lesson::whereHas('topic', function ($q) use ($courseId) {
                $q->where('course_id', $courseId);
            })
            ->pluck('id');

        $totalLessons = $lessonIds->count();
        if ($totalLessons === 0) {
            return 0;
        }

        // Đếm bài đã hoàn thành (Eloquent)
        $completedLessons = LessonCompletion::where('user_id', $userId)
            ->whereIn('lesson_id', $lessonIds)
            ->count();

        $progress = round(($completedLessons / $totalLessons) * 100, 2);

        // Cấp chứng chỉ nếu hoàn thành
        if ($progress >= 100) {
            CertificateService::issueCertificate($userId, $courseId);
        }

        // Lưu progress (Eloquent)
        UserProgress::updateOrCreate(
            [
                'user_id'   => $userId,
                'course_id' => $courseId,
            ],
            [
                'progress_percent' => $progress,
                'last_updated'     => now(),
            ]
        );

        return $progress;
    }
}
