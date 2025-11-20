<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\UserCourse;
use App\Models\LessonCompletion;
use App\Models\InstructorWallet;
use App\Models\Payout;
use App\Models\QuizAttempt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InstructorDashboardController extends Controller
{
    /**
     * ===== 📌 API 01 — Overview tổng quan =====
     */
    public function overview()
    {
        $id = Auth::id();

        $wallet = InstructorWallet::where('instructor_id', $id)->first();

        $totalCourses = Course::where('created_by', $id)->count();

        $courseIds = Course::where('created_by', $id)->pluck('id');

        $totalStudents = UserCourse::whereIn('course_id', $courseIds)->count();

        $activeStudents = LessonCompletion::whereIn('lesson_id', function ($q) use ($courseIds) {
            $q->select('id')
                ->from('lessons')
                ->whereIn('topic_id', function ($sub) use ($courseIds) {
                    $sub->select('id')->from('topics')->whereIn('course_id', $courseIds);
                });
        })
            ->where('completed_at', '>=', now()->subDays(7))
            ->distinct('user_id')
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'total_courses'  => $totalCourses,
                'total_students' => $totalStudents,
                'active_students' => $activeStudents,
                'wallet' => [
                    'balance'       => $wallet->balance ?? 0,
                    'total_earned'  => $wallet->total_earned ?? 0,
                    'total_withdrawn' => $wallet->total_withdrawn ?? 0,
                ]
            ]
        ]);
    }

    /**
     * ===== 📌 API 02 — Revenue Summary =====
     */
    public function revenueSummary()
    {
        $id = Auth::id();

        $wallet = InstructorWallet::where('instructor_id', $id)->first();

        $totalPayouts = Payout::where('instructor_id', $id)->count();

        return response()->json([
            'success' => true,
            'data' => [
                'balance'         => $wallet->balance ?? 0,
                'total_earned'    => $wallet->total_earned ?? 0,
                'total_withdrawn' => $wallet->total_withdrawn ?? 0,
                'total_payout_items' => $totalPayouts
            ]
        ]);
    }


    /**
     * ===== 📌 API 03 — Revenue by Month (chart) =====
     */
    public function revenueByMonth()
    {
        $id = Auth::id();

        $rows = Payout::select(
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
            DB::raw("SUM(instructor_amount) as total")
        )
            ->where('instructor_id', $id)
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $rows
        ]);
    }


    /**
     * ===== 📌 API 04 — Revenue by Course =====
     */
    public function revenueByCourse()
    {
        $id = Auth::id();

        $rows = Course::select(
            'courses.title',
            DB::raw('SUM(payouts.instructor_amount) as total')
        )
            ->leftJoin('order_items', 'order_items.course_id', '=', 'courses.id')
            ->leftJoin('payouts', 'payouts.order_item_id', '=', 'order_items.id')
            ->where('courses.created_by', $id)
            ->groupBy('courses.id', 'courses.title')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $rows
        ]);
    }




    /**
     * ===== 📌 API 05 — Student Summary =====
     */
    public function studentSummary()
    {
        $instructorId = Auth::id();

        $courseIds = Course::where('created_by', $instructorId)->pluck('id');

        $totalStudents = UserCourse::whereIn('course_id', $courseIds)->count();

        $active7Days = LessonCompletion::where('completed_at', '>=', now()->subDays(7))
            ->distinct('user_id')
            ->count();

        $attempts = QuizAttempt::whereIn('quiz_id', function ($q) use ($courseIds) {
            $q->select('id')->from('quizzes')->whereIn('topic_id', function ($sub) use ($courseIds) {
                $sub->select('id')->from('topics')->whereIn('course_id', $courseIds);
            });
        })->count();

        return response()->json([
            'success' => true,
            'data' => [
                'total_students' => $totalStudents,
                'active_students' => $active7Days,
                'quiz_attempts' => $attempts
            ]
        ]);
    }

    /**
     * ===== 📌 API 06 — Student Activity timeline =====
     */
    public function studentActivity()
    {
        $id = Auth::id();

        $rows = LessonCompletion::select(
            DB::raw("DATE(completed_at) as date"),
            DB::raw("COUNT(*) as completions")
        )
            ->join('lessons', 'lessons.id', '=', 'lesson_completions.lesson_id')
            ->join('topics', 'topics.id', '=', 'lessons.topic_id')
            ->join('courses', 'courses.id', '=', 'topics.course_id')
            ->where('courses.created_by', $id)
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $rows
        ]);
    }

    /**
     * ===== 📌 API 07 — Progress by Course =====
     */
    public function courseProgress()
    {
        $id = Auth::id();

        $rows = Course::select(
            'courses.id',
            'courses.title',
            DB::raw('COUNT(user_progress.user_id) as total_students'),
            DB::raw('COALESCE(AVG(user_progress.progress_percent), 0) as avg_progress')
        )
            ->leftJoin('user_progress', 'user_progress.course_id', '=', 'courses.id')
            ->where('courses.created_by', $id)
            ->groupBy('courses.id', 'courses.title')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $rows
        ]);
    }

    /**
     * ===== 📌 API 08 — Chat Statistics =====
     * Thống kê tin nhắn & phản hồi của Instructor
     */
    public function chatStats()
    {
        $id = Auth::id();

        // Tất cả thread Instructor tham gia
        $threadIds = DB::table('chat_participants')
            ->where('user_id', $id)
            ->pluck('thread_id');

        // Tổng tin nhắn Instructor gửi
        $sent = DB::table('chat_messages')
            ->whereIn('thread_id', $threadIds)
            ->where('sender_id', $id)
            ->count();

        // Tổng tin nhắn nhận từ học viên
        $received = DB::table('chat_messages')
            ->whereIn('thread_id', $threadIds)
            ->where('sender_id', '!=', $id)
            ->count();

        // Tin nhắn 7 ngày gần nhất (hội thoại active)
        $activeThreads = DB::table('chat_messages')
            ->whereIn('thread_id', $threadIds)
            ->where('sent_at', '>=', now()->subDays(7))
            ->distinct('thread_id')
            ->count();

        // Response rate (%) — tỉ lệ phản hồi trong 10 phút
        $responses = DB::select("
        SELECT COUNT(*) as responded
        FROM (
            SELECT m1.id, m1.sender_id,
                (
                    SELECT m2.sent_at
                    FROM chat_messages m2
                    WHERE m2.thread_id = m1.thread_id
                      AND m2.sender_id = ?
                      AND m2.sent_at > m1.sent_at
                    ORDER BY m2.sent_at ASC
                    LIMIT 1
                ) AS instructor_reply
            FROM chat_messages m1
            WHERE m1.sender_id != ?
              AND m1.thread_id IN (" . implode(",", $threadIds->toArray()) . ")
        ) as x
        WHERE instructor_reply IS NOT NULL
          AND TIMESTAMPDIFF(MINUTE, x.instructor_reply, x.instructor_reply) <= 10
    ", [$id, $id]);

        $responded = $responses[0]->responded ?? 0;
        $responseRate = $received > 0 ? round(($responded / $received) * 100, 2) : 100;

        // Average response time (in minutes)
        $avgResponse = DB::select("
        SELECT AVG(TIMESTAMPDIFF(SECOND, m1.sent_at, m2.sent_at)) AS diff_seconds
        FROM chat_messages m1
        JOIN chat_messages m2
          ON m2.thread_id = m1.thread_id
         AND m2.sender_id = ?
         AND m2.sent_at > m1.sent_at
        WHERE m1.sender_id != ?
          AND m1.thread_id IN (" . implode(",", $threadIds->toArray()) . ")
    ", [$id, $id]);

        $avgResponseTime = isset($avgResponse[0]->diff_seconds)
            ? round($avgResponse[0]->diff_seconds / 60, 2)
            : 0;

        return response()->json([
            'success' => true,
            'data' => [
                'sent_messages'     => $sent,
                'received_messages' => $received,
                'active_threads'    => $activeThreads,
                'response_rate'     => $responseRate,
                'avg_response_time' => $avgResponseTime,
            ]
        ]);
    }
}
