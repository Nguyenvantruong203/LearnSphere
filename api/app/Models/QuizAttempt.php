<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quiz_id',
        'attempt_no',
        'status',
        'score',
        'max_score',
        'correct_count',
        'wrong_count',
        'duration_seconds',
        'started_at',
        'submitted_at',
    ];

    protected $casts = [
        'score'            => 'float',
        'max_score'        => 'float',
        'correct_count'    => 'integer',
        'wrong_count'      => 'integer',
        'duration_seconds' => 'integer',
        'started_at'       => 'datetime',
        'submitted_at'     => 'datetime',
    ];

    /**
     * 🔹 Liên kết với Quiz
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * 🔹 Liên kết với User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 🔹 Danh sách câu trả lời trong lượt làm này
     */
    public function answers()
    {
        return $this->hasMany(QuizAttemptAnswer::class, 'attempt_id');
    }

    /**
     * 🔹 Helper: Kiểm tra đã hoàn thành chưa
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * 🔹 Helper: Tính phần trăm điểm
     */
    public function scorePercent(): ?float
    {
        if (!$this->max_score || $this->max_score == 0) {
            return null;
        }

        return round(($this->score / $this->max_score) * 100, 2);
    }
}
