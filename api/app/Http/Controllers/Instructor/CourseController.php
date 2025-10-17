<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\ChatThread;
use App\Models\ChatParticipant;

class CourseController extends Controller
{
    use AuthorizesRequests;
    // GET /admin/courses
    public function index(Request $request)
    {
        $this->authorize('viewAny', Course::class);

        $q = Course::query()->orderBy('title');

        if ($request->filled('search')) {
            $kw = (string) $request->string('search');
            $q->where('title', 'like', "%{$kw}%");
        }

        $courses = $q->paginate($request->integer('per_page', 10));

        return response()->json($courses);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Course::class);

        $data = $request->validate([
            'title'             => ['required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string'],
            'description'       => ['nullable', 'string'],
            'price'             => ['nullable', 'numeric', 'min:0'],
            'status'            => ['nullable', 'in:draft,published,archived'],
            'level'             => ['nullable', 'in:beginner,intermediate,advanced'],
            'language'          => ['nullable', 'string'],
            'currency'          => ['nullable', 'string'],
            'subject'           => ['nullable', 'string', 'max:100'],
            'thumbnail'         => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        // 🔹 Upload thumbnail
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('thumbnails', 'public');
            $data['thumbnail_url'] = $path;
        }
        unset($data['thumbnail']);

        // 🔹 Tạo khóa học
        $course = Course::create([
            'created_by' => $request->user()->id,
            ...$data
        ]);

        // 🔹 Tạo group chat cho khóa học (nếu chưa có)
        $thread = ChatThread::create([
            'course_id'   => $course->id,
            'is_group'    => true,
            'thread_type' => 'course_group',
            'title'       => 'Thảo luận khóa học: ' . $course->title,
            'created_by'  => $request->user()->id,
        ]);

        // 🔹 Thêm instructor (người tạo khóa) vào group chat
        ChatParticipant::create([
            'thread_id' => $thread->id,
            'user_id'   => $request->user()->id,
            'role'      => 'instructor',
        ]);

        // ✅ Trả về kết quả kèm thông tin chat
        return response()->json([
            'course' => $course,
            'chat_thread' => [
                'id' => $thread->id,
                'title' => $thread->title,
            ],
            'message' => 'Khóa học và nhóm chat đã được tạo thành công.',
        ], 201);
    }


    // GET /admin/courses/{course}
    public function show(Course $course)
    {
        $this->authorize('view', $course);

        // Có thể trả kèm counts
        $course->loadCount('topics');

        return response()->json($course);
    }

    // PATCH /admin/courses/{course}
    public function update(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $data = $request->validate([
            'title'             => ['required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string'],
            'description'       => ['nullable', 'string'],
            'price'             => ['nullable', 'numeric', 'min:0'],
            'status'            => ['nullable', 'in:draft,published,archived'],
            'level'             => ['nullable', 'in:beginner,intermediate,advanced'],
            'language'          => ['nullable', 'string'],
            'currency'          => ['nullable', 'string'],
            'subject'           => ['nullable', 'string', 'max:100'],
            'thumbnail'         => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        if ($request->hasFile('thumbnail')) {
            // Xóa ảnh cũ nếu có
            if ($course->thumbnail_url) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $course->thumbnail_url));
            }

            // Lưu ảnh mới
            $path = $request->file('thumbnail')->store('thumbnails', 'public');
            $data['thumbnail_url'] = $path;
        }

        unset($data['thumbnail']); // bỏ key gốc tránh lỗi fillable

        $course->update($data);

        return response()->json($course);
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
