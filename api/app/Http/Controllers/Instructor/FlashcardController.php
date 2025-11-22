<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Flashcard;
use App\Models\FlashcardSet;
use Illuminate\Http\Request;

class FlashcardController extends Controller
{
    /** Lấy danh sách flashcard thuộc set */
    public function index($setId)
    {
        $flashcards = Flashcard::where('flashcard_set_id', $setId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $flashcards
        ]);
    }

    /** Tạo flashcard */
    public function store(Request $request, $setId)
    {
        FlashcardSet::findOrFail($setId);

        $request->validate([
            'front' => 'required|string',
            'back' => 'required|string',
            'image_url' => 'nullable|string',
            'audio_url' => 'nullable|string',
        ]);

        $flashcard = Flashcard::create([
            'flashcard_set_id' => $setId,
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

    /** Cập nhật flashcard */
    public function update(Request $request, $id)
    {
        $flashcard = Flashcard::findOrFail($id);

        $request->validate([
            'front' => 'required|string',
            'back' => 'required|string',
            'image_url' => 'nullable|string',
            'audio_url' => 'nullable|string',
        ]);

        $flashcard->update($request->only([
            'front',
            'back',
            'image_url',
            'audio_url',
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Flashcard updated successfully',
            'data' => $flashcard
        ]);
    }

    /** Xoá flashcard */
    public function destroy($id)
    {
        Flashcard::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Flashcard deleted successfully'
        ]);
    }
}
