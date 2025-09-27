<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeminiService
{
    protected string $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent";
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
    }

    public function generateQuestions(string $content, int $num = 5): array
    {

        $prompt = "
Bạn là một AI chuyên tạo quiz.
Hãy đọc nội dung sau và tạo chính xác $num câu hỏi trắc nghiệm.

Nội dung:
\"$content\"

Yêu cầu:
1. Mỗi câu hỏi là một JSON object.
2. Trường 'type' có thể là một trong: \"single\" (một đáp án đúng), \"multiple_choice\" (nhiều đáp án đúng), hoặc \"true_false\" (đúng/sai).
3. Với 'single' hoặc 'multiple_choice':
   - Có các trường: type, question, options, answers.
   - options là object gồm 4 đáp án {\"A\": \"...\", \"B\": \"...\", \"C\": \"...\", \"D\": \"...\"}.
   - answers là mảng chứa key của đáp án đúng (ví dụ: [\"A\"] hoặc [\"A\",\"C\"]).
4. Với 'true_false':
   - Có các trường: type, question, options, answers.
   - options luôn là {\"A\": \"Đúng\", \"B\": \"Sai\"}.
   - answers là [\"A\"] nếu đúng, hoặc [\"B\"] nếu sai.
5. Trả về duy nhất một mảng JSON hợp lệ (bắt đầu bằng [ và kết thúc bằng ]).
6. KHÔNG giải thích, KHÔNG thêm text ngoài JSON.
";

        $response = Http::post("{$this->apiUrl}?key={$this->apiKey}", [
            "contents" => [[
                "parts" => [["text" => $prompt]]
            ]]
        ]);

        $data = $response->json();
        \Log::info('Gemini raw response', $data);

        $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '[]';

        // Strip ```json ... ```
        $text = trim($text);
        $text = preg_replace('/^```(json)?/i', '', $text);
        $text = preg_replace('/```$/', '', $text);

        $decoded = json_decode($text, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            \Log::error('Gemini JSON decode error: ' . json_last_error_msg(), ['raw' => $text]);
            return [];
        }

        return $decoded ?? [];
    }

    /**
     * Generate questions directly from a YouTube video URL
     */
    public function generateQuestionsFromYouTube(string $videoUrl, int $num = 5): array
    {
        $videoId = $this->extractVideoId($videoUrl);

        if (!$videoId) {
            \Log::error("Invalid YouTube URL: $videoUrl");
            return [];
        }

        // Thử lấy captions trước
        $transcript = $this->getCaptionsFromYouTube($videoId, 'vi');

        if (!$transcript) {
            \Log::warning("No captions found for video $videoId");
            return [];
        }

        return $this->generateQuestions($transcript, $num);
    }

    /**
     * Extract videoId from YouTube URL
     */
    protected function extractVideoId(string $url): ?string
    {
        if (preg_match('/v=([^&]+)/', $url, $matches)) {
            return $matches[1];
        }
        if (preg_match('/youtu\.be\/([^?]+)/', $url, $matches)) {
            return $matches[1];
        }
        return null;
    }

    /**
     * Get captions from YouTube (nếu có sẵn)
     */
    protected function getCaptionsFromYouTube(string $videoId, array $langs = ['vi', 'en']): ?string
    {
        foreach ($langs as $lang) {
            try {
                $url = "https://video.google.com/timedtext?lang={$lang}&v={$videoId}";
                $response = Http::get($url);

                if ($response->failed() || empty($response->body())) {
                    continue;
                }

                $xml = simplexml_load_string($response->body());
                if (!$xml || !isset($xml->text)) {
                    continue;
                }

                $lines = [];
                foreach ($xml->text as $text) {
                    $lines[] = (string) $text;
                }

                if (!empty($lines)) {
                    \Log::info("Found captions for video {$videoId} lang={$lang}");
                    return implode(" ", $lines);
                }
            } catch (\Exception $e) {
                \Log::error("YouTube caption fetch error [{$lang}]: " . $e->getMessage());
            }
        }

        return null;
    }

    /**
     * Gợi ý câu hỏi cho Topic quiz từ danh sách câu hỏi lesson
     */
    public function suggestForTopic($questions, int $num = 10): array
    {
        if (empty($questions)) {
            return [];
        }

        $prompt = "
Bạn là AI chuyên hỗ trợ giáo viên chọn câu hỏi cuối chương.
Hãy đọc danh sách câu hỏi sau và chọn ra {$num} câu quan trọng nhất để đại diện cho toàn bộ chương học.

Danh sách câu hỏi (dạng JSON):
" . json_encode($questions, JSON_UNESCAPED_UNICODE) . "

Yêu cầu:
1. Chỉ chọn ra các 'id' quan trọng nhất.
2. Trả về duy nhất một mảng JSON các id được chọn. Ví dụ: [1, 3, 5].
3. KHÔNG giải thích, KHÔNG in thêm text nào khác.
";

        $response = Http::post("{$this->apiUrl}?key={$this->apiKey}", [
            "contents" => [[
                "parts" => [["text" => $prompt]]
            ]]
        ]);

        $data = $response->json();
        \Log::info('Gemini raw response (suggestForTopic)', $data);

        $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '[]';

        // Strip ```json ... ```
        $text = trim($text);
        $text = preg_replace('/^```(json)?/i', '', $text);
        $text = preg_replace('/```$/', '', $text);

        $decoded = json_decode($text, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            \Log::error('Gemini JSON decode error in suggestForTopic: ' . json_last_error_msg(), ['raw' => $text]);
            return [];
        }

        return $decoded ?? [];
    }
}
