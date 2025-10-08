<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\QuizAttempt;

class QuizController extends Controller
{
    /**
     * Lấy thông tin chi tiết của 1 quiz (bao gồm danh sách câu hỏi)
     * - Ẩn đáp án đúng nếu user chưa làm hoặc chưa nộp bài
     * - Trả về thông tin lượt làm gần nhất (nếu có)
     */
    public function getQuizDetail($quizId)
    {
        $user = Auth::user();

        // 🧩 1. Lấy quiz + lesson + topic + số câu hỏi
        $quiz = Quiz::with(['lesson:id,title', 'topic:id,title'])
            ->withCount('questions')
            ->find($quizId);

        if (!$quiz) {
            return response()->json([
                'success' => false,
                'message' => 'Quiz không tồn tại.',
            ], 404);
        }

        // 🧩 2. Lấy danh sách câu hỏi (ẩn đáp án đúng)
        $questions = Question::where('quiz_id', $quiz->id)
            ->select('id', 'quiz_id', 'text', 'options', 'type', 'correct_options', 'weight')
            ->get()
            ->map(function ($q) {
                return [
                    'id' => $q->id,
                    'quiz_id' => $q->quiz_id,
                    'text' => $q->text,
                    'type' => $q->type,
                    'options' => is_string($q->options) ? json_decode($q->options, true) : $q->options,
                    'weight' => $q->weight,
                    // Không trả về correct_options trong lần đầu
                ];
            });

        // 🧩 3. Kiểm tra user đã có lượt làm bài chưa
        $lastAttempt = null;
        if ($user) {
            $lastAttempt = QuizAttempt::where('user_id', $user->id)
                ->where('quiz_id', $quiz->id)
                ->latest('submitted_at')
                ->first();
        }

        // 🧩 4. Trả về dữ liệu chi tiết
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $quiz->id,
                'title' => $quiz->title,
                'duration_minutes' => $quiz->duration_minutes,
                'max_attempts' => $quiz->max_attempts,
                'total_questions' => $quiz->questions_count,
                'shuffle_questions' => $quiz->shuffle_questions,
                'shuffle_options' => $quiz->shuffle_options,

                'lesson' => $quiz->lesson ? [
                    'id' => $quiz->lesson->id,
                    'title' => $quiz->lesson->title,
                ] : null,

                'topic' => $quiz->topic ? [
                    'id' => $quiz->topic->id,
                    'title' => $quiz->topic->title,
                ] : null,

                'questions' => $questions,

                'last_attempt' => $lastAttempt ? [
                    'id' => $lastAttempt->id,
                    'attempt_no' => $lastAttempt->attempt_no,
                    'status' => $lastAttempt->status,
                    'score' => $lastAttempt->score,
                    'max_score' => $lastAttempt->max_score,
                    'correct_count' => $lastAttempt->correct_count,
                    'wrong_count' => $lastAttempt->wrong_count,
                    'started_at' => $lastAttempt->started_at,
                    'submitted_at' => $lastAttempt->submitted_at,
                ] : null,
            ],
        ]);
    }
}
