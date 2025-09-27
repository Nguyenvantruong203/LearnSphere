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

        return response()->json([
            'success'   => true,
            'questions' => $questions,
        ]);
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
            ->get(['id', 'text']);

        if ($questions->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Không có câu hỏi nào trong topic để gợi ý.',
            ], 400);
        }

        $suggestedIds = $gemini->suggestForTopic($questions, $num);

        return response()->json([
            'success'       => true,
            'suggested_ids' => $suggestedIds,
            'all_questions' => $questions,
        ]);
    }

    /**
     * Admin tick chọn → copy sang quiz của topic
     */
    public function publishForTopic(Request $request, Quiz $quiz)
    {
        $data = $request->validate([
            'question_ids' => 'required|array',
            'question_ids.*' => 'integer|exists:questions,id',
        ]);

        if (!$quiz->topic) {
            return response()->json([
                'success' => false,
                'message' => 'Quiz này không thuộc topic nào.',
            ], 400);
        }

        $published = [];
        foreach ($data['question_ids'] as $qid) {
            $origin = Question::find($qid);
            if (!$origin) {
                continue;
            }

            // copy sang quiz topic (nếu chưa tồn tại)
            $copy = $quiz->questions()->firstOrCreate(
                ['origin_question_id' => $origin->id],
                [
                    'type'              => $origin->type,
                    'text'              => $origin->text,
                    'options'           => $origin->options,
                    'correct_options'   => $origin->correct_options,
                    'weight'            => $origin->weight,
                    'is_temp'           => false,
                ]
            );

            $published[] = $copy;
        }

        return response()->json([
            'success'   => true,
            'published' => $published,
        ]);
    }

    /**
     * Sửa lại câu hỏi đã copy vào quiz topic
     */
    public function updateForTopic(Request $request, Quiz $quiz, Question $question)
    {
        if ($quiz->id !== $question->quiz_id) {
            return response()->json([
                'success' => false,
                'message' => 'Câu hỏi này không thuộc quiz topic.',
            ], 400);
        }

        $data = $request->validate([
            'type' => 'sometimes|string',
            'text' => 'sometimes|string',
            'options' => 'nullable|array',
            'correct_options' => 'nullable|array',
            'weight' => 'nullable|numeric',
        ]);

        $question->update($data);

        return response()->json([
            'success'  => true,
            'question' => $question,
        ]);
    }
}
