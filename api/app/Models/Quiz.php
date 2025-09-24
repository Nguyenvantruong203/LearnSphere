<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quiz extends Model
{
    protected $fillable = [
        'topic_id',
        'lesson_id',
        'title',
        'duration_minutes',
        'shuffle_questions',
        'shuffle_options',
        'max_attempts',
    ];

    protected $casts = [
        'shuffle_questions' => 'boolean',
        'shuffle_options'   => 'boolean',
        'duration_minutes'  => 'integer',
        'max_attempts'      => 'integer',
    ];

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class)->withDefault();
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(QuizResult::class);
    }
}
