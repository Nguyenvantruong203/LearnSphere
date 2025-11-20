<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\ChatThread;

Broadcast::routes(['middleware' => ['auth:sanctum']]);
Broadcast::channel('chat.thread.{threadId}', function ($user, $threadId) {
    \Log::info('🔐 Broadcast auth check', [
        'user_id' => $user->id,
        'thread_id' => $threadId,
        'exists' => ChatThread::where('id', $threadId)
            ->whereHas('participants', fn($q) => $q->where('user_id', $user->id))
            ->exists(),
    ]);

    return ChatThread::where('id', $threadId)
        ->whereHas('participants', fn($q) => $q->where('user_id', $user->id))
        ->exists();
});

Broadcast::channel('notifications.{userId}', function ($user, $userId) {
    return (int)$user->id === (int)$userId;
});
