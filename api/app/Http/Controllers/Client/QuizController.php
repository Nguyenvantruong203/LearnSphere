<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class QuizController extends Controller
{
    /**
     * 📘 Lấy chi tiết 1 quiz:
     *  - Bao gồm danh sách câu hỏi (ẩn đáp án đúng)
     *  - Nếu quiz có bật shuffle -> random thứ tự câu hỏi / lựa chọn
     *  - Nếu user đã làm bài -> trả về thông tin lượt làm gần nhất
     */
    public function getQuizDetail($quizId)
    {
        $user = Auth::user();

        // 🧩 1. Lấy quiz + liên kết cơ bản (cache 5 phút để giảm tải DB)
        $quiz = Cache::remember("quiz_detail_$quizId", 300, function () use ($quizId) {
            return Quiz::with(['lesson:id,title', 'topic:id,title'])
                ->withCount('questions')
                ->find($quizId);
        });

        if (!$quiz) {
            return response()->json([
                'success' => false,
                'message' => 'Quiz không tồn tại.',
            ], 404);
        }

        // 🧩 2. Lấy danh sách câu hỏi (ẩn đáp án đúng)
        $questions = Question::where('quiz_id', $quiz->id)
            ->select('id', 'quiz_id', 'text', 'options', 'type', 'weight') // ❌ loại bỏ correct_options
            ->orderBy('id')
            ->get()
            ->map(function ($q) use ($quiz) {
                $options = is_string($q->options)
                    ? json_decode($q->options, true)
                    : $q->options;

                // 🔀 Nếu quiz bật shuffle lựa chọn
                if ($quiz->shuffle_options && is_array($options)) {
                    shuffle($options);
                }

                return [
                    'id' => $q->id,
                    'quiz_id' => $q->quiz_id,
                    'text' => $q->text,
                    'type' => $q->type,
                    'options' => $options,
                    'weight' => $q->weight,
                ];
            });

        // 🔀 Nếu quiz bật shuffle câu hỏi
        if ($quiz->shuffle_questions) {
            $questions = $questions->shuffle()->values();
        }

        // 🧩 3. Lấy lượt làm gần nhất (nếu có)
        $lastAttempt = null;
        if ($user) {
            $lastAttempt = QuizAttempt::where('user_id', $user->id)
                ->where('quiz_id', $quiz->id)
                ->latest('submitted_at')
                ->first();
        }

        // 🧩 4. Trả về dữ liệu
        return response()->json([
            'success' => true,
            'data' => [
                'quiz' => [
                    'id' => $quiz->id,
                    'title' => $quiz->title,
                    'duration_minutes' => $quiz->duration_minutes,
                    'max_attempts' => $quiz->max_attempts,
                    'total_questions' => $quiz->questions_count,
                    'shuffle_questions' => (bool) $quiz->shuffle_questions,
                    'shuffle_options' => (bool) $quiz->shuffle_options,
                ],

                'lesson' => $quiz->lesson ? [
                    'id' => $quiz->lesson->id,
                    'title' => $quiz->lesson->title,
                ] : null,

                'topic' => $quiz->topic ? [
                    'id' => $quiz->topic->id,
                    'title' => $quiz->topic->title,
                ] : null,

                'questions' => $questions,

                'has_attempted' => (bool) $lastAttempt,
                'last_attempt' => $lastAttempt ? [
                    'attempt_id'   => $lastAttempt->id,
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

    /**
     * 🚀 Bắt đầu làm quiz:
     *  - Tạo 1 bản ghi quiz_attempt mới (nếu chưa đạt max_attempts)
     *  - Random câu hỏi nếu quiz bật shuffle
     *  - Lưu thời gian bắt đầu
     *  - Trả về danh sách câu hỏi (ẩn đáp án đúng)
     */
    public function startQuizAttempt(Request $request, $quizId)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để làm bài quiz.',
            ], 401);
        }

        // 🧩 1. Lấy thông tin quiz
        $quiz = Quiz::withCount('questions')->find($quizId);

        if (!$quiz) {
            return response()->json([
                'success' => false,
                'message' => 'Quiz không tồn tại.',
            ], 404);
        }

        // 🧩 2. Kiểm tra giới hạn số lần làm bài
        $attemptCount = QuizAttempt::where('quiz_id', $quizId)
            ->where('user_id', $user->id)
            ->count();

        if ($quiz->max_attempts && $attemptCount >= $quiz->max_attempts) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn đã đạt số lần làm tối đa cho quiz này.',
            ], 403);
        }

        // 🧩 3. Lấy danh sách câu hỏi
        $questions = Question::where('quiz_id', $quizId)
            ->select('id', 'quiz_id', 'text', 'options', 'type', 'weight')
            ->get()
            ->map(function ($q) {
                return [
                    'id' => $q->id,
                    'text' => $q->text,
                    'type' => $q->type,
                    'options' => is_string($q->options)
                        ? json_decode($q->options, true)
                        : $q->options,
                    'weight' => $q->weight,
                ];
            });

        // 🔀 4. Random câu hỏi & đáp án nếu quiz bật shuffle
        if ($quiz->shuffle_questions) {
            $questions = $questions->shuffle()->values();
        }

        if ($quiz->shuffle_options) {
            $questions = $questions->map(function ($q) {
                if (is_array($q['options'])) {
                    shuffle($q['options']);
                }
                return $q;
            });
        }

        // 🧩 5. Tạo bản ghi quiz_attempt
        $attempt = DB::transaction(function () use ($quiz, $user, $attemptCount, $questions) {
            $newAttempt = new QuizAttempt();
            $newAttempt->quiz_id = $quiz->id;
            $newAttempt->user_id = $user->id;
            $newAttempt->attempt_no = $attemptCount + 1;
            $newAttempt->status = 'in_progress';
            $newAttempt->started_at = Carbon::now();
            $newAttempt->save();

            return $newAttempt;
        });

        // 🧩 6. Trả về thông tin attempt + danh sách câu hỏi
        return response()->json([
            'success' => true,
            'data' => [
                'attempt' => [
                    'id' => $attempt->id,
                    'quiz_id' => $quiz->id,
                    'attempt_no' => $attempt->attempt_no,
                    'status' => $attempt->status,
                    'started_at' => $attempt->started_at,
                    'duration_minutes' => $quiz->duration_minutes,
                ],
                'quiz' => [
                    'id' => $quiz->id,
                    'title' => $quiz->title,
                    'total_questions' => $quiz->questions_count,
                    'duration_minutes' => $quiz->duration_minutes,
                ],
                'questions' => $questions,
            ],
        ]);
    }
    /**
     * 🧠 Nộp bài quiz
     * - Kiểm tra attempt tồn tại & thuộc user
     * - Tự động chấm điểm
     * - Lưu kết quả: điểm, số câu đúng/sai, thời gian nộp
     */
    public function submitQuizAttempt(Request $request, $quizId)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để nộp bài.',
            ], 401);
        }

        // 🧩 1. Validate input
        $data = $request->validate([
            'attempt_id' => 'required|integer',
            'answers'    => 'required|array',
            'answers.*.question_id'     => 'required|integer',
            'answers.*.selected_options' => 'array',
        ]);

        // 🧩 2. Lấy thông tin quiz & attempt
        $quiz = Quiz::find($quizId);
        if (!$quiz) {
            return response()->json([
                'success' => false,
                'message' => 'Quiz không tồn tại.',
            ], 404);
        }

        $attempt = QuizAttempt::where('id', $data['attempt_id'])
            ->where('quiz_id', $quiz->id)
            ->where('user_id', $user->id)
            ->first();

        if (!$attempt) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy lượt làm bài.',
            ], 404);
        }

        if ($attempt->status === 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Bài thi này đã được nộp trước đó.',
            ], 400);
        }

        // 🧩 3. Lấy danh sách câu hỏi & đáp án đúng
        $questions = Question::where('quiz_id', $quiz->id)
            ->select('id', 'correct_options', 'weight')
            ->get()
            ->keyBy('id');

        $totalQuestions = $questions->count();
        $correctCount = 0;
        $wrongCount = 0;
        $totalScore = 0;

        $answerRecords = [];

        // 🧩 4. Chấm điểm từng câu & lưu chi tiết
        foreach ($data['answers'] as $answer) {
            $qId = $answer['question_id'];
            $selected = $answer['selected_options'] ?? [];

            if (!isset($questions[$qId])) continue;

            $question = $questions[$qId];
            $correctOptions = is_string($question->correct_options)
                ? json_decode($question->correct_options, true)
                : $question->correct_options;

            // 🔁 Quy đổi index -> ký tự A/B/C/D (theo vị trí)
            $selectedLetters = array_map(function ($idx) {
                $letters = range('A', 'Z');
                return $letters[(int)$idx] ?? null;
            }, $selected);

            // Loại bỏ giá trị null (phòng lỗi)
            $selectedLetters = array_values(array_filter($selectedLetters));

            sort($selectedLetters);
            sort($correctOptions);

            $isCorrect = $selectedLetters == $correctOptions;

            if ($isCorrect) {
                $correctCount++;
                $totalScore += $question->weight ?? 1;
            } else {
                $wrongCount++;
            }

            $answerRecords[] = [
                'attempt_id'       => $attempt->id,
                'question_id'      => $qId,
                'selected_options' => json_encode($selectedLetters),
                'is_correct'       => $isCorrect,
                'points_awarded'   => $isCorrect ? ($question->weight ?? 1) : 0,
                'created_at'       => now(),
                'updated_at'       => now(),
            ];
        }

        $maxScore = $questions->sum('weight') ?: $totalQuestions;

        // 🧩 5. Lưu câu trả lời vào bảng quiz_attempt_answers
        if (!empty($answerRecords)) {
            DB::table('quiz_attempt_answers')->insert($answerRecords);
        }

        // 🧩 6. Cập nhật attempt tổng
        $attempt->update([
            'status'        => 'completed',
            'submitted_at'  => Carbon::now(),
            'correct_count' => $correctCount,
            'wrong_count'   => $wrongCount,
            'max_score'     => $maxScore,
            'score'         => $totalScore,
        ]);

        // 🧩 7. Trả về kết quả
        return response()->json([
            'success' => true,
            'message' => 'Nộp bài thành công!',
            'data' => [
                'attempt_id'     => $attempt->id,
                'score'          => $totalScore,
                'max_score'      => $maxScore,
                'correct_count'  => $correctCount,
                'wrong_count'    => $wrongCount,
                'submitted_at'   => $attempt->submitted_at,
            ],
        ]);
    }
    public function getQuizReview($quizId, $attemptId)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để xem lại bài làm.',
            ], 401);
        }

        // 🧩 1. Lấy attempt cụ thể
        $attempt = QuizAttempt::where('id', $attemptId)
            ->where('quiz_id', $quizId)
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->first();

        if (!$attempt) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy lượt làm bài hợp lệ.',
            ], 404);
        }

        // 🧩 2. Lấy thông tin quiz
        $quiz = Quiz::select('id', 'title', 'duration_minutes')->find($quizId);
        if (!$quiz) {
            return response()->json([
                'success' => false,
                'message' => 'Quiz không tồn tại.',
            ], 404);
        }

        // 🧩 3. Lấy danh sách câu trả lời của attempt
        $answers = DB::table('quiz_attempt_answers')
            ->where('attempt_id', $attempt->id)
            ->get()
            ->keyBy('question_id');

        // 🧩 4. Lấy danh sách câu hỏi
        $questions = Question::where('quiz_id', $quizId)
            ->select('id', 'text', 'options', 'correct_options', 'type', 'weight')
            ->orderBy('id')
            ->get()
            ->map(function ($q) use ($answers) {
                $options = is_string($q->options) ? json_decode($q->options, true) : $q->options;
                $correctOptions = is_string($q->correct_options)
                    ? json_decode($q->correct_options, true)
                    : $q->correct_options;

                $userAnswer = $answers[$q->id] ?? null;

                return [
                    'id' => $q->id,
                    'text' => $q->text,
                    'type' => $q->type,
                    'options' => $options,
                    'correct_options' => $correctOptions,
                    'selected_options' => $userAnswer
                        ? json_decode($userAnswer->selected_options, true)
                        : [],
                    'is_correct' => $userAnswer ? (bool)$userAnswer->is_correct : false,
                    'points_awarded' => $userAnswer->points_awarded ?? 0,
                    'weight' => $q->weight,
                ];
            });

        // 🧩 5. Trả dữ liệu review
        return response()->json([
            'success' => true,
            'data' => [
                'quiz' => [
                    'id' => $quiz->id,
                    'title' => $quiz->title,
                    'duration_minutes' => $quiz->duration_minutes,
                ],
                'attempt' => [
                    'id' => $attempt->id,
                    'attempt_no' => $attempt->attempt_no,
                    'score' => $attempt->score,
                    'max_score' => $attempt->max_score,
                    'correct_count' => $attempt->correct_count,
                    'wrong_count' => $attempt->wrong_count,
                    'submitted_at' => $attempt->submitted_at,
                ],
                'questions' => $questions,
            ],
        ]);
    }

    public function getQuizAttempts($quizId)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để xem lịch sử làm bài.',
            ], 401);
        }

        // 🧩 1. Kiểm tra quiz tồn tại
        $quiz = Quiz::select('id', 'title', 'duration_minutes')->find($quizId);
        if (!$quiz) {
            return response()->json([
                'success' => false,
                'message' => 'Quiz không tồn tại.',
            ], 404);
        }

        // 🧩 2. Lấy danh sách các lượt làm bài (sắp xếp mới nhất)
        $attempts = QuizAttempt::where('quiz_id', $quizId)
            ->where('user_id', $user->id)
            ->orderByDesc('submitted_at')
            ->get([
                'id',
                'attempt_no',
                'status',
                'score',
                'max_score',
                'correct_count',
                'wrong_count',
                'started_at',
                'submitted_at'
            ]);

        // 🧩 3. Format dữ liệu trả về
        return response()->json([
            'success' => true,
            'data' => [
                'quiz' => [
                    'id' => $quiz->id,
                    'title' => $quiz->title,
                    'duration_minutes' => $quiz->duration_minutes,
                ],
                'attempts' => $attempts->map(function ($a) {
                    return [
                        'id' => $a->id,
                        'attempt_no' => $a->attempt_no,
                        'status' => $a->status,
                        'score' => $a->score,
                        'max_score' => $a->max_score,
                        'correct_count' => $a->correct_count,
                        'wrong_count' => $a->wrong_count,
                        'started_at' => $a->started_at,
                        'submitted_at' => $a->submitted_at,
                    ];
                }),
            ],
        ]);
    }
}
