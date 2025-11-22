<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Flashcard;
use App\Models\FlashcardLog;
use Illuminate\Support\Facades\Auth;

class FlashcardLearnController extends Controller
{
    /**
     * Lấy danh sách flashcard để học
     */
    public function getFlashcardsForLearning($topicId)
    {
        $flashcards = Flashcard::where('topic_id', $topicId)
            ->orderBy('id')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $flashcards
        ]);
    }

    /**
     * Lưu lại 1 lượt ôn flashcard
     */
    public function saveFlashcardReview($flashcardId)
    {
        FlashcardLog::create([
            'user_id' => Auth::id(),
            'flashcard_id' => $flashcardId,
            'reviewed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Flashcard review logged'
        ]);
    }
}
