<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\FlashcardSet;
use App\Models\Topic;
use Illuminate\Http\Request;

class FlashcardSetController extends Controller
{
    /** Lấy danh sách flashcard set theo topic */
    public function index($topicId)
    {
        $sets = FlashcardSet::where('topic_id', $topicId)
            ->withCount('flashcards')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $sets
        ]);
    }

    /** Tạo flashcard set */
    public function store(Request $request, $topicId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Topic::findOrFail($topicId);

        $set = FlashcardSet::create([
            'topic_id' => $topicId,
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Flashcard set created successfully',
            'data' => $set
        ]);
    }

    /** Cập nhật flashcard set */
    public function update(Request $request, $setId)
    {
        $set = FlashcardSet::findOrFail($setId);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $set->update($request->only(['title', 'description']));

        return response()->json([
            'success' => true,
            'message' => 'Flashcard set updated successfully',
            'data' => $set
        ]);
    }

    /** Xoá flashcard set (xoá cả flashcards bên trong) */
    public function destroy($setId)
    {
        $set = FlashcardSet::findOrFail($setId);
        $set->delete();

        return response()->json([
            'success' => true,
            'message' => 'Flashcard set deleted successfully',
        ]);
    }
}
