<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'message',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    // Quan hệ: 1 notification gửi cho nhiều user
    public function recipients()
    {
        return $this->hasMany(NotificationUser::class);
    }

    // Lấy danh sách user nhận thông báo
    public function users()
    {
        return $this->belongsToMany(User::class, 'notification_users')
            ->withPivot(['is_read', 'read_at'])
            ->withTimestamps();
    }
}
