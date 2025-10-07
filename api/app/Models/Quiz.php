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

    // 🔹 Gắn với topic
    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class)->withDefault();
    }

    // 🔹 Gắn với lesson (nếu quiz thuộc lesson cụ thể)
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class, 'lesson_id', 'id')->withDefault();
    }

    // 🔹 Danh sách câu hỏi
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    // 🔹 Các lượt làm bài (thay thế QuizResult)
    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    // 🔹 Lượt làm mới nhất của một user (truy vấn nhanh)
    public function latestAttemptFor($userId)
    {
        return $this->attempts()
            ->where('user_id', $userId)
            ->latest('submitted_at')
            ->first();
    }

    // 🔹 Lượt điểm cao nhất của một user (nếu cần hiển thị "best score")
    public function bestAttemptFor($userId)
    {
        return $this->attempts()
            ->where('user_id', $userId)
            ->orderByDesc('score')
            ->first();
    }
}
