<?php

namespace App\Events;

use App\Models\NotificationUser;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use \Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NotificationCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public NotificationUser $notificationUser;

    public function __construct(NotificationUser $notificationUser)
    {
        $this->notificationUser = $notificationUser;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('notifications.' . $this->notificationUser->user_id);
    }

    public function broadcastAs()
    {
        return 'notification.created';
    }

    public function broadcastWith()
    {
        return [
            'notificationUser' => $this->notificationUser->load('notification')->toArray(),
        ];
    }
}
