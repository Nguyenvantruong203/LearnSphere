<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Flashcard;
use App\Models\Topic;
use Illuminate\Http\Request;

class FlashcardController extends Controller
{
    /**
     * Lấy danh sách flashcard thuộc một topic
     */
    public function getFlashcardsByTopic($topicId)
    {
        $flashcards = Flashcard::where('topic_id', $topicId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $flashcards
        ]);
    }

    /**
     * Tạo mới flashcard
     */
    public function createFlashcard(Request $request, $topicId)
    {
        $request->validate([
            'front' => 'required|string',
            'back' => 'required|string',
            'image_url' => 'nullable|string',
            'audio_url' => 'nullable|string',
        ]);

        Topic::findOrFail($topicId);

        $flashcard = Flashcard::create([
            'topic_id' => $topicId,
            'front' => $request->front,
            'back' => $request->back,
            'image_url' => $request->image_url,
            'audio_url' => $request->audio_url,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Flashcard created successfully',
            'data' => $flashcard
        ]);
    }

    /**
     * Cập nhật flashcard
     */
    public function updateFlashcard(Request $request, $id)
    {
        $flashcard = Flashcard::findOrFail($id);

        $request->validate([
            'front' => 'required|string',
            'back' => 'required|string',
            'image_url' => 'nullable|string',
            'audio_url' => 'nullable|string',
        ]);

        $flashcard->update($request->only([
            'front', 'back', 'image_url', 'audio_url'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Flashcard updated successfully',
            'data' => $flashcard
        ]);
    }

    /**
     * Xóa flashcard
     */
    public function deleteFlashcard($id)
    {
        $flashcard = Flashcard::findOrFail($id);
        $flashcard->delete();

        return response()->json([
            'success' => true,
            'message' => 'Flashcard deleted successfully'
        ]);
    }
}
