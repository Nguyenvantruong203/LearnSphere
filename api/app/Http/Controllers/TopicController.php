<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'sometimes|required|exists:courses,id',
            'search' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $query = Topic::query();

        if ($request->has('course_id')) {
            $query->forCourse($request->course_id);
        }

        $topics = $query->search($request->search)
            ->ordered()
            ->with('course:id,title')
            ->paginate(15);

        return response()->json($topics);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'order' => 'nullable|integer|min:1',
        ]);

        $topic = Topic::create($validated);

        return response()->json($topic, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Topic  $topic
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Topic $topic)
    {
        return response()->json($topic->load('course'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Topic  $topic
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Topic $topic)
    {
        $validated = $request->validate([
            'course_id' => ['sometimes', 'required', Rule::exists('courses', 'id')],
            'title' => 'sometimes|required|string|max:255',
            'order' => 'sometimes|nullable|integer|min:1',
        ]);

        $topic->update($validated);

        return response()->json($topic);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Topic  $topic
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Topic $topic)
    {
        // TODO: Add logic to check if topic has lessons/quizzes before deleting
        $topic->delete();

        return response()->json(null, 204);
    }
}
