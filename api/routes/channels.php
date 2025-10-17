<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\ChatThread;

Broadcast::routes(['middleware' => ['auth:sanctum']]);
Broadcast::channel('chat.thread.{threadId}', function ($user, $threadId) {
    return ChatThread::where('id', $threadId)
        ->whereHas('participants', fn($q) => $q->where('user_id', $user->id))
        ->exists();
});

