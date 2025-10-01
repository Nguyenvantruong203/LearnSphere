<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Question;
use App\Services\GeminiService;

class TopicQuestionController extends Controller
{
    /**
     * Hiển thị danh sách câu hỏi đã chọn (public) của quiz topic
     */
    public function indexForTopic(Quiz $quiz)
    {
        if (!$quiz->topic) {
            return response()->json([
                'success' => false,
                'message' => 'Quiz này không thuộc topic nào.',
            ], 400);
        }

        $questions = $quiz->questions()->where('is_temp', false)->get();

        return response()->json($questions);
    }

    /**
     * Gom câu hỏi từ lesson → gửi AI lọc ra danh sách gợi ý cho quiz topic
     */
    public function suggestForTopic(Request $request, GeminiService $gemini, Quiz $quiz)
    {
        $request->validate([
            'num' => 'nullable|integer|min:1|max:50',
        ]);
        $num = $request->input('num', 10);

        $topic = $quiz->topic;
        if (!$topic) {
            return response()->json([
                'success' => false,
                'message' => 'Quiz này không thuộc topic nào.',
            ], 400);
        }

        // Lấy toàn bộ quiz thuộc các lesson của topic
        $lessonQuizIds = $topic->lessons()
            ->with('quiz')
            ->get()
            ->pluck('quiz.id')
            ->filter();

        $questions = Question::whereIn('quiz_id', $lessonQuizIds)
            ->where('is_temp', false)
            ->get();

        if ($questions->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Không có câu hỏi nào trong topic để gợi ý.',
            ], 400);
        }

        // Gọi AI gợi ý
        $suggestedIds = $gemini->suggestForTopic($questions, $num);

        // Clone các câu hỏi gợi ý vào quiz topic
        $suggestedQuestions = $questions->whereIn('id', $suggestedIds);

        foreach ($suggestedQuestions as $origin) {
            Question::create([
                'quiz_id'          => $quiz->id,
                'type'             => $origin->type,
                'text'             => $origin->text,
                'options'          => $origin->options,
                'correct_options'  => $origin->correct_options,
                'weight'           => $origin->weight ?? 1,
                'is_temp'          => false,
            ]);
        }
        $result = $quiz->questions()->where('is_temp', false)->get();
        return response()->json($result);
    }

    public function poolForTopic(Quiz $quiz)
    {
        $topic = $quiz->topic;
        if (!$topic) {
            return response()->json([
                'success' => false,
                'message' => 'Quiz này không thuộc topic nào.',
            ], 400);
        }

        $lessonQuizIds = $topic->lessons()
            ->with('quiz')
            ->get()
            ->pluck('quiz.id')
            ->filter();

        $questions = Question::whereIn('quiz_id', $lessonQuizIds)
            ->where('is_temp', false)
            ->get(['id', 'text', 'type', 'options', 'correct_options', 'weight']);

        // Lấy danh sách text đã tồn tại trong quiz topic
        $existingTexts = $quiz->questions()->pluck('text')->toArray();

        $result = $questions->map(function ($q) use ($existingTexts) {
            return [
                'id'             => $q->id,
                'text'           => $q->text,
                'type'           => $q->type,
                'options'        => $q->options,
                'correct_options' => $q->correct_options,
                'weight'         => $q->weight,
                'selected'       => in_array($q->text, $existingTexts), // ⚡ append
            ];
        });

        return response()->json($result);
    }

    /**
     * Admin tick chọn → copy sang quiz của topic
     */
    public function publishForTopic(Request $request, Quiz $quiz)
    {
        $ids = $request->input('question_ids', []);

        // Lấy danh sách câu gốc từ lesson
        $originQuestions = Question::whereIn('id', $ids)->get();

        // Xóa những câu hỏi trong quiz topic mà không còn trong selection
        $quiz->questions()
            ->whereNotIn('text', $originQuestions->pluck('text'))
            ->delete();

        // Thêm mới những câu chưa có
        $existingTexts = $quiz->questions()->pluck('text')->toArray();
        foreach ($originQuestions as $origin) {
            if (in_array($origin->text, $existingTexts)) continue;

            Question::create([
                'quiz_id'         => $quiz->id,
                'type'            => $origin->type,
                'text'            => $origin->text,
                'options'         => $origin->options,
                'correct_options' => $origin->correct_options,
                'weight'          => $origin->weight ?? 1,
                'is_temp'         => false,
            ]);
        }

        return response()->json(
            $quiz->questions()->where('is_temp', false)->get()
        );
    }
}
