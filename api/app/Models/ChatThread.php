<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatThread extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'course_id',
        'is_group',
        'thread_type',
        'title',
        'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ✅ Quan hệ many-to-many với User qua bảng chat_participants
    public function participants()
    {
        return $this->belongsToMany(User::class, 'chat_participants', 'thread_id', 'user_id')
            ->withPivot(['role', 'joined_at', 'last_read_at'])
            ->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(ChatMessage::class, 'thread_id')->orderBy('sent_at', 'asc');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
