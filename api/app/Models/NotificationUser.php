<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationUser extends Model
{
    use HasFactory;

    protected $table = 'notification_users';

    protected $fillable = [
        'notification_id',
        'user_id',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    // Quan hệ tới Notification
    public function notification()
    {
        return $this->belongsTo(Notification::class);
    }

    // Quan hệ tới User
    public function users()
    {
        return $this->belongsToMany(User::class, 'notification_users')
            ->withPivot('is_read', 'read_at')
            ->withTimestamps();
    }
}
