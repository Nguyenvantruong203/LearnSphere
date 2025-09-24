<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Lesson;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class QuizController extends Controller
{
    use AuthorizesRequests;

    // GET /admin/lessons/{lesson}/quizzes
    public function index(Lesson $lesson, Request $request)
    {
        $this->authorize('view', $lesson->topic->course);

        $q = $lesson->quizzes()->orderByDesc('id')->withCount('questions');

        if ($request->filled('search')) {
            $kw = (string) $request->string('search');
            $q->where('title', 'like', "%{$kw}%");
        }

        $quizzes = $q->paginate($request->integer('per_page', 20));

        // Ép kiểu boolean để đảm bảo frontend nhận đúng định dạng
        $quizzes->getCollection()->transform(function ($quiz) {
            $quiz->shuffle_questions = (bool) $quiz->shuffle_questions;
            $quiz->shuffle_options   = (bool) $quiz->shuffle_options;
            return $quiz;
        });

        return response()->json($quizzes);
    }

    // GET /admin/topics/{topic}/quizzes
    public function indexForTopic(Topic $topic, Request $request)
    {
        $this->authorize('view', $topic->course);

        $q = $topic->quizzes()->orderByDesc('id')->withCount('questions');

        if ($request->filled('search')) {
            $kw = (string) $request->string('search');
            $q->where('title', 'like', "%{$kw}%");
        }

        $quizzes = $q->paginate($request->integer('per_page', 20));

        // Ép kiểu boolean để đảm bảo frontend nhận đúng định dạng
        $quizzes->getCollection()->transform(function ($quiz) {
            $quiz->shuffle_questions = (bool) $quiz->shuffle_questions;
            $quiz->shuffle_options   = (bool) $quiz->shuffle_options;
            return $quiz;
        });

        return response()->json($quizzes);
    }

    // POST /admin/lessons/{lesson}/quizzes
    public function store(Request $request, Lesson $lesson)
    {
        $this->authorize('update', $lesson->topic->course);

        $data = $request->validate([
            'title'             => ['required', 'string', 'max:255'],
            'duration_minutes'  => ['nullable', 'integer', 'min:0'],
            'shuffle_questions' => ['boolean'],
            'shuffle_options'   => ['boolean'],
            'max_attempts'      => ['integer', 'min:0'],
        ]);

        $data['topic_id'] = $lesson->topic_id;
        $data['shuffle_questions'] = $request->boolean('shuffle_questions');
        $data['shuffle_options']   = $request->boolean('shuffle_options');

        $quiz = $lesson->quizzes()->create($data);

        return response()->json(['message' => 'created', 'data' => $quiz], 201);
    }

    // POST /admin/topics/{topic}/quizzes
    public function storeForTopic(Request $request, Topic $topic)
    {
        $this->authorize('update', $topic->course);

        $data = $request->validate([
            'title'             => ['required', 'string', 'max:255'],
            'duration_minutes'  => ['nullable', 'integer', 'min:0'],
            'shuffle_questions' => ['boolean'],
            'shuffle_options'   => ['boolean'],
            'max_attempts'      => ['integer', 'min:0'],
        ]);

        $data['shuffle_questions'] = $request->boolean('shuffle_questions');
        $data['shuffle_options']   = $request->boolean('shuffle_options');

        $quiz = $topic->quizzes()->create($data);

        return response()->json(['message' => 'created', 'data' => $quiz], 201);
    }

    // GET /admin/quizzes/{quiz}
    public function show(Quiz $quiz)
    {
        $this->authorize('view', $quiz->topic->course);

        $quiz->load('questions');

        return response()->json($quiz);
    }

    // PUT /admin/quizzes/{quiz}
    public function update(Request $request, Quiz $quiz)
    {
        $this->authorize('update', $quiz->topic->course);

        $data = $request->validate([
            'title'             => ['required', 'string', 'max:255'],
            'duration_minutes'  => ['nullable', 'integer', 'min:0'],
            'shuffle_questions' => ['boolean'],
            'shuffle_options'   => ['boolean'],
            'max_attempts'      => ['integer', 'min:0'],
        ]);

        $data['shuffle_questions'] = $request->boolean('shuffle_questions');
        $data['shuffle_options']   = $request->boolean('shuffle_options');

        $quiz->update($data);

        return response()->json(['message' => 'updated', 'data' => $quiz]);
    }

    // DELETE /admin/quizzes/{quiz}
    public function destroy(Quiz $quiz)
    {
        $this->authorize('delete', $quiz->topic->course);

        DB::transaction(function () use ($quiz) {
            $quiz->questions()->delete();
            $quiz->delete();
        });

        return response()->json(['message' => 'deleted']);
    }
}
