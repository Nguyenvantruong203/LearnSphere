<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\ChatThread;
use App\Models\ChatParticipant;
use App\Models\NotificationUser;
use App\Events\NotificationCreated;

class CourseController extends Controller
{
    use AuthorizesRequests;

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
            'level'             => ['nullable', 'in:beginner,intermediate,advanced'],
            'language'          => ['nullable', 'string'],
            'currency'          => ['nullable', 'string'],
            'subject'           => ['nullable', 'string', 'max:100'],
            'thumbnail'         => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $data['status'] = 'pending';

        // Upload thumbnail
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('thumbnails', 'public');
            $data['thumbnail_url'] = $path;
        }
        unset($data['thumbnail']);

        // Tạo khóa học
        $course = Course::create([
            'created_by' => $request->user()->id,
            ...$data
        ]);

        // Tạo group chat
        $thread = ChatThread::create([
            'course_id'   => $course->id,
            'is_group'    => true,
            'thread_type' => 'course_group',
            'title'       => 'Thảo luận khóa học: ' . $course->title,
            'created_by'  => $request->user()->id,
        ]);

        ChatParticipant::create([
            'thread_id' => $thread->id,
            'user_id'   => $request->user()->id,
            'role'      => 'instructor',
        ]);

        /**
         * 🔔 GỬI NOTIFICATION CHO ADMIN
         */
        $adminIds = User::where('role', 'admin')->pluck('id')->toArray();

        if (!empty($adminIds)) {

            // 1. Tạo notification
            $noti = Notification::create([
                'type'    => 'course',
                'title'   => 'Khóa học mới cần duyệt',
                'message' => "Giảng viên {$request->user()->name} đã tạo khóa học mới: {$course->title}.",
                'data'    => [
                    'course_id' => $course->id,
                ],
            ]);

            // 2. Attach đến tất cả admin
            $noti->users()->attach($adminIds);

            // 3. Load tất cả bản ghi pivot vừa tạo
            $pivotRecords = NotificationUser::with('notification')
                ->where('notification_id', $noti->id)
                ->whereIn('user_id', $adminIds)
                ->get();

            // 4. Bắn realtime cho TỪNG admin
            foreach ($pivotRecords as $pivot) {
                broadcast(new NotificationCreated($pivot))->toOthers();
            }
        }

        return response()->json([
            'course' => $course,
            'chat_thread' => [
                'id' => $thread->id,
                'title' => $thread->title,
            ],
            'message' => 'Khóa học và nhóm chat đã được tạo thành công.',
        ], 201);
    }

    public function show(Course $course)
    {
        $this->authorize('view', $course);

        $course->loadCount('topics');

        return response()->json($course);
    }

    public function update(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $data = $request->validate([
            'title'             => ['required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string'],
            'description'       => ['nullable', 'string'],
            'price'             => ['nullable', 'numeric', 'min:0'],
            'level'             => ['nullable', 'in:beginner,intermediate,advanced'],
            'language'          => ['nullable', 'string'],
            'currency'          => ['nullable', 'string'],
            'subject'           => ['nullable', 'string', 'max:100'],
            'thumbnail'         => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($course->thumbnail_url) {
                Storage::disk('public')->delete($course->thumbnail_url);
            }

            $path = $request->file('thumbnail')->store('thumbnails', 'public');
            $data['thumbnail_url'] = $path;
        }

        unset($data['thumbnail']);
        $course->update($data);

        return response()->json($course);
    }

    /**
     * 🔁 GIẢNG VIÊN GỬI LẠI KHÓA HỌC
     */
    public function resubmit(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        if ($course->status !== 'rejected') {
            return response()->json([
                'message' => 'Only rejected courses can be resubmitted.',
            ], 400);
        }

        $course->update([
            'status' => 'pending',
            'rejection_reason' => null,
            'rejected_at' => null
        ]);

        // Gửi thông báo cho admin
        $adminIds = User::where('role', 'admin')->pluck('id')->toArray();

        if (!empty($adminIds)) {
            $noti = Notification::create([
                'type'    => 'course',
                'title'   => 'Khóa học gửi lại cần duyệt',
                'message' => "Giảng viên {$request->user()->name} đã gửi lại khóa học: {$course->title}.",
                'data'    => json_encode(['course_id' => $course->id]),
            ]);

            $noti->users()->attach($adminIds);

            $pivotRecords = NotificationUser::with('notification')
                ->where('notification_id', $noti->id)
                ->whereIn('user_id', $adminIds)
                ->get();

            foreach ($pivotRecords as $pivot) {
                broadcast(new NotificationCreated($pivot))->toOthers();
            }
        }

        return response()->json([
            'message' => 'Course has been resubmitted for review.',
            'course'  => $course->fresh()
        ]);
    }

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
