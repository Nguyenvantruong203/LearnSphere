<?php

namespace App\Events;

use App\Models\NotificationUser;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class NotificationCreated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $notification;
    public $userId;

    public function __construct(NotificationUser $notificationUser)
    {
        $this->notification = $notificationUser->load('notification');
        $this->userId = $notificationUser->user_id;
    }

    public function broadcastOn()
    {
        return new Channel('notifications.' . $this->userId);
    }

    public function broadcastAs()
    {
        return 'notification.created';
    }
}
