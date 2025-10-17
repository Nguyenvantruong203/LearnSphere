<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public ChatMessage $message;

    public function __construct(ChatMessage $message)
    {
        $this->message = $message;
    }

    /**
     * Kênh broadcast — mỗi thread 1 channel riêng
     */
    public function broadcastOn()
    {
        return new PresenceChannel('chat.thread.' . $this->message->thread_id);
    }

    /**
     * Dữ liệu gửi đến client
     */
    public function broadcastWith(): array
    {
        return [
            'id'           => $this->message->id,
            'thread_id'    => $this->message->thread_id,
            'sender'       => $this->message->sender->only(['id', 'name', 'avatar_url']),
            'message'      => $this->message->message,
            'message_type' => $this->message->message_type,
            'sent_at'      => $this->message->sent_at->toDateTimeString(),
        ];
    }

    /**
     * Tên event khi frontend lắng nghe
     */
    public function broadcastAs(): string
    {
        return 'message.sent';
    }
}
