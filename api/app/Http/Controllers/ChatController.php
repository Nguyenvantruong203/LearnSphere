<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\ChatThread;
use App\Models\ChatMessage;
use App\Models\ChatParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

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
            'student'    => ['course_group', 'private'],
            'instructor' => ['course_group', 'private', 'support'],
            'admin'      => ['support'],
            default      => ['course_group', 'private'],
        };

        $threads = ChatThread::whereHas('participants', fn($q) => $q->where('user_id', $userId))
            ->when($courseId, fn($q) => $q->where('course_id', $courseId))
            ->when($type, fn($q) => $q->where('thread_type', $type))
            ->whereIn('thread_type', $allowedTypes)
            ->with([
                'participants.user:id,name,avatar_url,role',
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

    public function startPrivateInstructor($courseId)
    {
        $user = Auth::user();
        $course = \App\Models\Course::findOrFail($courseId);

        // Instructor của khóa học
        $instructorId = $course->created_by;

        // Tìm hoặc tạo thread riêng
        $thread = ChatThread::firstOrCreate(
            [
                'course_id'   => $courseId,
                'thread_type' => 'private_instructor',
                'is_group'    => false,
            ],
            [
                'title'       => 'Trao đổi với giảng viên',
                'created_by'  => $user->id,
            ]
        );

        $thread->participants()->syncWithoutDetaching([$user->id, $instructorId]);

        return response()->json([
            'success' => true,
            'thread'  => $thread->load('participants.user:id,name,avatar_url')
        ]);
    }

    /**
     * 🔹 Lấy tin nhắn trong 1 thread
     */
    public function getMessages($threadId)
    {
        $userId = Auth::id();

        $thread = ChatThread::with('participants.user:id,name,avatar_url')->findOrFail($threadId);

        // ✅ Kiểm tra quyền
        if (! $thread->participants()->where('user_id', $userId)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền truy cập cuộc trò chuyện này.',
            ], 403);
        }

        // ✅ Lấy 50 tin nhắn mới nhất (có thể dùng phân trang sau)
        $messages = $thread->messages()
            ->with('sender:id,name,avatar_url')
            ->orderBy('sent_at', 'asc')
            ->take(50)
            ->get();

        // ✅ Đánh dấu đã đọc
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
     * 🔹 Gửi tin nhắn (Realtime Broadcast qua Soketi)
     */
    public function sendMessage(Request $request, $threadId)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'message'       => 'required|string|max:5000',
            'message_type'  => 'nullable|in:text,image,file,system',
        ]);

        $thread = ChatThread::findOrFail($threadId);

        // ✅ Kiểm tra user có trong nhóm không
        if (! $thread->participants()->where('user_id', $user->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không thuộc nhóm chat này.',
            ], 403);
        }

        // ✅ Tạo tin nhắn
        $message = ChatMessage::create([
            'thread_id'    => $thread->id,
            'sender_id'    => $user->id,
            'message'      => $validated['message'],
            'message_type' => $validated['message_type'] ?? 'text',
            'sent_at'      => now(),
        ]);

        // ✅ Cập nhật thời gian hoạt động thread
        $thread->touch();

        // ✅ Broadcast realtime tới channel (qua Soketi)
        broadcast(new MessageSent(
            $message->load('sender:id,name,avatar_url')
        ))->toOthers();

        return response()->json([
            'success' => true,
            'message' => $message->load('sender:id,name,avatar_url'),
        ]);
    }

    /**
     * 🔹 Đánh dấu thread đã đọc
     */
    public function markAsRead($threadId)
    {
        $participant = ChatParticipant::where('thread_id', $threadId)
            ->where('user_id', Auth::id())
            ->first();

        if (! $participant) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy dữ liệu tham gia cuộc trò chuyện.',
            ], 404);
        }

        $participant->update(['last_read_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Đã đánh dấu đã đọc.',
        ]);
    }

    public function startSupport()
    {
        $user = Auth::user();
        $adminId = User::where('role', 'admin')->value('id');

        if (!$adminId) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy admin'], 404);
        }

        $thread = ChatThread::firstOrCreate(
            [
                'thread_type' => 'support',
                'is_group'    => false,
                'created_by'  => $user->id,
            ],
            ['title' => 'Hỗ trợ khách hàng']
        );

        $thread->participants()->syncWithoutDetaching([$user->id, $adminId]);

        return response()->json([
            'success' => true,
            'thread'  => $thread->load('participants.user:id,name,avatar_url'),
        ]);
    }
}
