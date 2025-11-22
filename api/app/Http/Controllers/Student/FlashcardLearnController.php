<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Flashcard;
use App\Models\FlashcardSet;
use App\Models\FlashcardLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FlashcardLearnController extends Controller
{
    /**
     * Lấy danh sách flashcards trong set
     */
    public function getFlashcardsBySet($setId)
    {
        $set = FlashcardSet::findOrFail($setId);

        $cards = $set->flashcards()
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $cards
        ]);
    }

    /**
     * Ghi log khi user review flashcard
     */
    public function logReview($flashcardId)
    {
        Flashcard::findOrFail($flashcardId);

        FlashcardLog::create([
            'user_id' => Auth::id(),
            'flashcard_id' => $flashcardId,
            'reviewed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review logged'
        ]);
    }
}
