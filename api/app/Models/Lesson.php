<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'topic_id',
        'title',
        'video_provider',
        'video_id',
        'video_url',
        'content',
        'order',
        'duration_seconds',
        'player_params',
    ];

    protected $casts = [
        'order' => 'integer',
        'duration_seconds' => 'integer',
        'player_params' => 'array',
    ];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function scopeOrdered($q)
    {
        return $q->orderBy('order');
    }

    protected static function booted(): void
    {
        static::creating(function (self $m) {
            if (empty($m->order)) {
                $max = static::where('topic_id', $m->topic_id)->max('order');
                $m->order = ($max ?? 0) + 1;
            }
        });
    }
}
