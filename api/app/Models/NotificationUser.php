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

    // Pivot belongs to Notification
    public function notification()
    {
        return $this->belongsTo(Notification::class);
    }

    // Pivot belongs to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
