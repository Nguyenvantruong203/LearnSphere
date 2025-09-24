<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizResult extends Model
{
    protected $table = 'quiz_results';

    protected $fillable = [
        'user_id',
        'quiz_id',
        'attempt_number',
        'score',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'score'        => 'float',
        'attempt_number' => 'integer',
    ];

    /* =========================
     |  Relationships
     |=========================*/

    // Kết quả thuộc về 1 user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Kết quả thuộc về 1 quiz
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    /* =========================
     |  Scopes
     |=========================*/

    // Lấy kết quả theo user
    public function scopeForUser($q, int $userId)
    {
        return $q->where('user_id', $userId);
    }

    // Lấy kết quả theo quiz
    public function scopeForQuiz($q, int $quizId)
    {
        return $q->where('quiz_id', $quizId);
    }

    // Lấy lần attempt mới nhất
    public function scopeLatestAttempt($q)
    {
        return $q->orderByDesc('attempt_number');
    }
}
