<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Quiz;
use App\Services\GeminiService;
use Illuminate\Http\Request;

class LessonQuestionController extends Controller
{
    // Lấy danh sách câu hỏi của quiz
    public function indexForLesson(Request $request, Quiz $quiz)
    {
        $perPage = $request->input('per_page', 20);

        $questions = $quiz->questions()
            ->where('is_temp', false)
            ->paginate($perPage);

        return response()->json($questions);
    }

    // Tạo câu hỏi thủ công
    public function createForLesson(Request $request, Quiz $quiz)
    {
        $data = $request->validate([
            'type'            => 'required|string',
            'text'            => 'required|string',
            'options'         => 'nullable|array',
            'correct_options' => 'nullable|array',
            'weight'          => 'nullable|numeric'
        ]);

        $data['options'] = $this->normalizeOptions($data['options'] ?? []);
        $data['is_temp'] = false;

        $question = $quiz->questions()->create($data);

        return response()->json($question, 201);
    }


    // Xem chi tiết câu hỏi
    public function show(Quiz $quiz, Question $question)
    {
        $this->authorizeQuestion($quiz, $question);

        return response()->json($question);
    }

    // Update câu hỏi
    public function updateForLesson(Request $request, Quiz $quiz, Question $question)
    {
        $this->authorizeQuestion($quiz, $question);

        $data = $request->validate([
            'type'            => 'sometimes|string',
            'text'            => 'sometimes|string',
            'options'         => 'nullable|array',
            'correct_options' => 'nullable|array',
            'weight'          => 'nullable|numeric',
        ]);

        $data['options'] = $this->normalizeOptions($data['options'] ?? []);
        $data['is_temp'] = false;

        $question->update($data);

        return response()->json($question);
    }


    // Xóa câu hỏi
    public function destroy(Quiz $quiz, Question $question)
    {
        $this->authorizeQuestion($quiz, $question);

        $question->delete();

        return response()->json(['message' => 'Deleted']);
    }

    // AI Generate Questions
    public function generate(Request $request, GeminiService $gemini, Quiz $quiz)
    {
        $request->validate([
            'num' => 'nullable|integer|min:1|max:20',
        ]);

        $num = $request->input('num', 5);
        $content = null;

        // 1. Thử lấy transcript từ video YouTube trước (đa ngôn ngữ)
        if ($quiz->lesson && $quiz->lesson->video_url) {
            $videoId = $gemini->extractVideoId($quiz->lesson->video_url);

            // Thử lấy caption tiếng Việt, nếu không có thì fallback sang tiếng Anh
            $content = $gemini->getCaptionsFromYouTube($videoId, 'vi')
                ?? $gemini->getCaptionsFromYouTube($videoId, 'en');
        }

        // 2. Nếu vẫn không có thì fallback sang content text của bài học
        if (!$content && $quiz->lesson) {
            $content = $quiz->lesson->content ?? null;
        }

        // 3. Nếu không có transcript và content thì báo lỗi
        if (!$content) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy transcript hoặc nội dung bài học để tạo câu hỏi.',
            ], 400);
        }

        // 4. Sinh câu hỏi từ Gemini
        $questions = $gemini->generateQuestions($content, $num);

        $inserted = [];
        foreach ($questions as $q) {
            $inserted[] = $quiz->questions()->create([
                'type'            => $q['type'] ?? 'single',
                'text'            => $q['question'] ?? '',
                'options'         => $this->normalizeOptions($q['options'] ?? []),
                'correct_options' => $q['answers'] ?? [],
                'weight'          => 1,
                'is_temp'         => false,
            ]);
        }

        return response()->json([
            'success'   => true,
            'inserted'  => count($inserted),
            'questions' => $inserted,
        ]);
    }

    private function authorizeQuestion(Quiz $quiz, Question $question)
    {
        if ($quiz->id !== $question->quiz_id) {
            abort(404, 'Question not found in this quiz');
        }
    }

    private function normalizeOptions($options)
    {
        // Nếu là array dạng list: [ ['key'=>'A','label'=>'Dart'], ... ]
        if (is_array($options) && array_is_list($options)) {
            return collect($options)
                ->mapWithKeys(fn($opt) => [$opt['key'] => $opt['label']])
                ->toArray();
        }

        // Nếu null thì trả về rỗng
        if (!$options) {
            return [];
        }

        // Nếu đã là object key-value thì giữ nguyên
        return $options;
    }
}
