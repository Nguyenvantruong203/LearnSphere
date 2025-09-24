<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProgress extends Model
{
    protected $table = 'user_progress';

    protected $fillable = [
        'user_id',
        'course_id',
        'progress_percent',
        'last_updated',
    ];

    protected $casts = [
        'last_updated' => 'datetime',
    ];

    public $timestamps = false; // anh có last_updated riêng rồi

    // Tiến độ thuộc về user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Tiến độ thuộc về course
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
