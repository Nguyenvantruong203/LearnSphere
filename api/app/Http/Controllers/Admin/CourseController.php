<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CourseController extends Controller
{
    use AuthorizesRequests;
    /**
     * @author Truong
     */
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    // GET /admin/courses
    public function index(Request $request)
    {
        $this->authorize('viewAny', Course::class);

        $q = Course::query()->orderBy('title');

        if ($request->filled('search')) {
            $kw = (string) $request->string('search');
            $q->where('title', 'like', "%{$kw}%");
        }

        // Phân trang đồng bộ per_page
        $courses = $q->paginate($request->integer('per_page', 10));

        return response()->json($courses);
    }

    // POST /admin/courses
    public function store(Request $request)
    {
        $this->authorize('create', Course::class);
        $data = $request->validate([
            'title'              => ['required', 'string', 'max:255'],
            'short_description'  => ['nullable', 'string'],
            'description'        => ['nullable', 'string'],
            'price'              => ['nullable', 'numeric', 'min:0'],
            'status'             => ['nullable', 'in:draft,published,archived'],
            'visibility'         => ['nullable', 'in:public,private'],
            'level'              => ['nullable', 'in:beginner,intermediate,advanced'],
            'language'           => ['nullable', 'string', 'max:10'],
            'currency'           => ['nullable', 'string', 'max:10'],
        ]);

        $course = Course::create([
            'created_by' => $request->user()->id,
            ...$data
        ]);

        return response()->json(['message' => 'created', 'data' => $course], 201);
    }

    // GET /admin/courses/{course}
    public function show(Course $course)
    {
        $this->authorize('view', $course);

        // Có thể trả kèm counts
        $course->loadCount('topics');

        return response()->json(['data' => $course]);
    }

    // PATCH /admin/courses/{course}
    public function update(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $data = $request->validate([
            'title'              => ['required', 'string', 'max:255'],
            'short_description'  => ['nullable', 'string'],
            'description'        => ['nullable', 'string'],
            'price'              => ['nullable', 'numeric', 'min:0'],
            'status'             => ['nullable', 'in:draft,published,archived'],
            'visibility'         => ['nullable', 'in:public,private'],
            'level'              => ['nullable', 'in:beginner,intermediate,advanced'],
            'language'           => ['nullable', 'string', 'max:10'],
            'currency'           => ['nullable', 'string', 'max:10'],
        ]);

        // Nếu có file thumbnail
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('thumbnails', 'public');
            $data['thumbnail_url'] = '/storage/' . $path;
        }

        $course->update($data);

        return response()->json([
            'message' => 'updated',
            'data' => $course
        ]);
    }

    // DELETE /admin/courses/{course}
    public function destroy(Course $course)
    {
        $this->authorize('delete', $course);

        DB::transaction(function () use ($course) {
            $course->load(['topics.lessons']);
            foreach ($course->topics as $topic) {
                foreach ($topic->lessons as $lesson) {
                    if (!empty($lesson->video_path)) {
                        Storage::disk('public')->delete($lesson->video_path);
                    }
                }
            }
            $course->delete();
        });

        return response()->json(['message' => 'deleted']);
    }
}
