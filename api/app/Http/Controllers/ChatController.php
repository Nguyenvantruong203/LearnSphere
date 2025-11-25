<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\ChatThread;
use App\Models\ChatMessage;
use App\Models\ChatParticipant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;

class ChatController extends Controller
{
    /**
     * 🔹 Lấy danh sách thread mà user đang tham gia
     */
    public function getThreads(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;
        $type = $request->query('thread_type');
        $courseId = $request->query('course_id');

        // 🔹 Phân quyền theo vai trò
        $allowedTypes = match ($user->role) {
            'student'    => ['course_group', 'private', 'user_support', 'consult'],
            'instructor' => ['course_group', 'private', 'support', 'consult'],
            'admin'      => ['support', 'user_support'],
            default      => ['course_group', 'private'],
        };

        $threads = ChatThread::whereHas('participants', fn($q) => $q->where('user_id', $userId))
            ->when($courseId, fn($q) => $q->where('course_id', $courseId))
            ->when($type, fn($q) => $q->where('thread_type', $type))
            ->whereIn('thread_type', $allowedTypes)
            ->with([
                'participants:id,name,avatar_url,role',
                'messages' => fn($q) => $q->latest('sent_at')->take(1),
                'course:id,title',
            ])
            ->orderByDesc('updated_at')
            ->get();

        return response()->json([
            'success' => true,
            'threads' => $threads,
        ]);
    }

    /**
     * 🔹 Lấy tin nhắn trong 1 thread
     */
    public function getMessages($threadId)
    {
        $userId = Auth::id();

        $thread = ChatThread::with('participants:id,name,avatar_url')->findOrFail($threadId);

        if (! $thread->participants()->where('user_id', $userId)->exists()) {
            return response()->json(['success' => false, 'message' => 'Bạn không có quyền truy cập cuộc trò chuyện này.'], 403);
        }

        $messages = $thread->messages()
            ->with('sender:id,name,avatar_url')
            ->orderBy('sent_at', 'asc')
            ->take(50)
            ->get();

        ChatParticipant::where('thread_id', $threadId)
            ->where('user_id', $userId)
            ->update(['last_read_at' => now()]);

        return response()->json([
            'success' => true,
            'thread'  => $thread,
            'messages' => $messages,
        ]);
    }

    /**
     * 🔹 Gửi tin nhắn (Broadcast realtime)
     */
    public function sendMessage(Request $request, $threadId)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'message'       => 'required|string|max:5000',
            'message_type'  => 'nullable|in:text,image,file,system',
        ]);

        $thread = ChatThread::findOrFail($threadId);

        if (! $thread->participants()->where('user_id', $user->id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Bạn không thuộc nhóm chat này.'], 403);
        }

        $message = ChatMessage::create([
            'thread_id'    => $thread->id,
            'sender_id'    => $user->id,
            'message'      => $validated['message'],
            'message_type' => $validated['message_type'] ?? 'text',
            'sent_at'      => now(),
        ]);

        $thread->touch();

        broadcast(new MessageSent($message->load('sender:id,name,avatar_url')))->toOthers();

        return response()->json([
            'success' => true,
            'message' => $message->load('sender:id,name,avatar_url'),
        ]);
    }

    /**
     * 🔹 Student ↔ Admin (hỗ trợ người dùng)
     */
    public function startUserSupport()
    {
        $user = Auth::user();

        // 🧩 Chỉ cho phép student tạo chat hỗ trợ
        if ($user->role !== 'student') {
            return response()->json([
                'success' => false,
                'message' => 'Chỉ sinh viên mới được mở chat hỗ trợ với admin.',
            ], 403);
        }

        // 🧩 Lấy admin thật sự
        $admin = User::where('role', 'admin')->first();

        if (! $admin) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy admin hỗ trợ. Vui lòng liên hệ quản trị viên.',
            ], 404);
        }

        // 🧩 Kiểm tra xem user này đã có thread với admin chưa
        $thread = ChatThread::where('thread_type', 'user_support')
            ->whereHas('participants', fn($q) => $q->where('user_id', $user->id))
            ->whereHas('participants', fn($q) => $q->where('user_id', $admin->id))
            ->first();

        // 🧩 Nếu chưa có thì tạo mới
        if (! $thread) {
            $thread = ChatThread::create([
                'thread_type' => 'user_support',
                'is_group'    => false,
                'title'       => 'User support',
                'created_by'  => $user->id,
            ]);

            // 🧩 Attach đúng 2 user
            $thread->participants()->attach([
                $user->id => ['role' => $user->role, 'joined_at' => now()],
                $admin->id => ['role' => $admin->role, 'joined_at' => now()],
            ]);
        }

        return response()->json([
            'success' => true,
            'thread'  => $thread->load('participants:id,name,avatar_url,role'),
        ]);
    }

    public function startConsult(Request $request)
    {
        $user = Auth::user();
        $courseId = $request->input('course_id');

        // ✅ Kiểm tra khóa học tồn tại
        $course = Course::find($courseId);
        if (! $course) {
            return response()->json([
                'success' => false,
                'message' => 'Khóa học không tồn tại.',
            ], 404);
        }

        // ✅ Lấy instructor của khóa học
        $instructorId = $course->created_by;
        if (! $instructorId) {
            return response()->json([
                'success' => false,
                'message' => 'Khóa học chưa có giảng viên.',
            ], 400);
        }

        // ✅ Tìm hoặc tạo thread tư vấn (consult)
        $thread = ChatThread::firstOrCreate(
            [
                'thread_type' => 'consult',
                'course_id' => $courseId,
                'is_group' => false,
                'created_by' => $user->id,
            ],
            [
                'title' => "Course Consulting: {$course->title}",
            ]
        );

        // ✅ Gắn người dùng và giảng viên làm participants
        $thread->participants()->syncWithoutDetaching([
            $user->id => ['role' => 'student'],
            $instructorId => ['role' => 'instructor'],
        ]);

        return response()->json([
            'success' => true,
            'thread' => $thread->load('participants:id,name,avatar_url,role'),
        ]);
    }
}
