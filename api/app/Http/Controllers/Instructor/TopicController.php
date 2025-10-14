<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TopicController extends Controller
{
    use AuthorizesRequests;
    /**
     * @author Truong
     */

    // GET /admin/courses/{course}/topics
    public function index(Course $course, Request $request)
    {
        $this->authorize('view', $course);

        $q = $course->topics()->orderBy('order')->orderBy('id');

        if ($request->filled('search')) {
            $kw = (string) $request->string('search');
            $q->where('title', 'like', "%{$kw}%");
        }

        // Có thể không phân trang nếu ít
        $topics = $q->paginate($request->integer('per_page', 20), ['id', 'title', 'order', 'course_id']);

        return response()->json($topics);
    }

    // POST /admin/courses/{course}/topics

    public function store(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'order' => ['nullable', 'integer', 'min:1'],
        ]);

        // ✅ Kiểm tra trùng title trong cùng khóa học
        $existsTitle = $course->topics()
            ->where('title', trim($data['title']))
            ->exists();

        if ($existsTitle) {
            return response()->json([
                'message' => 'Tiêu đề này đã tồn tại trong khóa học.',
                'errors' => [
                    'title' => ['Tiêu đề đã tồn tại trong khóa học này.']
                ]
            ], 422);
        }

        // Nếu không truyền order thì auto max+1
        if (!isset($data['order'])) {
            $data['order'] = ($course->topics()->max('order') ?? 0) + 1;
        } else {
            $newOrder = $data['order'];

            // Kiểm tra trùng
            $exists = $course->topics()->where('order', $newOrder)->exists();
            if ($exists) {
                return response()->json([
                    'message' => 'Thứ tự đã tồn tại trong khóa học này.',
                    'errors' => ['order' => ['Thứ tự đã tồn tại trong khóa học này.']]
                ], 422);
            }

            // Nếu không trùng thì dịch các topic sau xuống
            $course->topics()
                ->where('order', '>=', $newOrder)
                ->increment('order');
        }

        $topic = $course->topics()->create($data);

        return response()->json(['message' => 'created', 'data' => $topic], 201);
    }

    // GET /admin/topics/{topic}  (shallow show)
    public function show(Topic $topic)
    {
        $this->authorize('view', $topic->course);

        $topic->loadCount('lessons');

        return response()->json(['data' => $topic]);
    }

    // PUT /admin/courses/{course}/topics/{topic}
    public function update(Request $request, Topic $topic)
    {
        $this->authorize('update', $topic->course);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'order' => ['required', 'integer', 'min:1'],
        ]);

        // ✅ Check trùng title (trừ chính nó)
        $existsTitle = $topic->course
            ->topics()
            ->where('id', '<>', $topic->id)
            ->where('title', trim($data['title']))
            ->exists();

        if ($existsTitle) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'title' => ['Tiêu đề đã tồn tại trong khóa học này.']
            ]);
        }

        $oldOrder = $topic->order;
        $newOrder = $data['order'];

        DB::transaction(function () use ($topic, $oldOrder, $newOrder, $data) {
            if ($newOrder != $oldOrder) {
                $courseTopics = $topic->course->topics();

                // ✅ Check trùng order (ngoài chính nó)
                $exists = $courseTopics
                    ->where('id', '<>', $topic->id)
                    ->where('order', $newOrder)
                    ->exists();

                if ($exists) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'order' => ['Thứ tự đã tồn tại trong khóa học này.']
                    ]);
                }

                // Tạm set order = 0 để tránh unique conflict
                $topic->update(['order' => 0]);

                if ($newOrder < $oldOrder) {
                    // Chuyển lên trước → dịch xuống
                    $courseTopics
                        ->whereBetween('order', [$newOrder, $oldOrder - 1])
                        ->increment('order');
                } else {
                    // Chuyển xuống sau → dịch lên
                    $courseTopics
                        ->whereBetween('order', [$oldOrder + 1, $newOrder])
                        ->decrement('order');
                }

                // Cập nhật title + order mới
                $topic->update([
                    'title' => $data['title'],
                    'order' => $newOrder,
                ]);
            } else {
                // Chỉ update title
                $topic->update([
                    'title' => $data['title'],
                    'order' => $oldOrder,
                ]);
            }
        });

        return response()->json(['message' => 'updated', 'data' => $topic]);
    }

    // DELETE /admin/topics/{topic}
    public function destroy(Topic $topic)
    {
        $this->authorize('delete', $topic->course);

        DB::transaction(function () use ($topic) {
            $topic->load('lessons');
            foreach ($topic->lessons as $lesson) {
                if (!empty($lesson->video_path)) {
                    Storage::disk('public')->delete($lesson->video_path);
                }
            }
            $topic->delete();
        });

        return response()->json(['message' => 'deleted']);
    }
}
