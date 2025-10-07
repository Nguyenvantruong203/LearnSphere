<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttemptAnswer extends Model
{
    use HasFactory;

    protected $table = 'quiz_attempt_answers';

    protected $fillable = [
        'attempt_id',
        'question_id',
        'selected_options',
        'is_correct',
        'points_awarded',
    ];

    protected $casts = [
        'selected_options' => 'array',
        'is_correct'       => 'boolean',
        'points_awarded'   => 'float',
    ];

    /**
     * 🔹 Liên kết tới lượt làm bài (attempt)
     */
    public function attempt()
    {
        return $this->belongsTo(QuizAttempt::class, 'attempt_id');
    }

    /**
     * 🔹 Liên kết tới câu hỏi tương ứng
     */
    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    /**
     * 🔹 Helper: kiểm tra câu trả lời có đúng không
     */
    public function isCorrect(): bool
    {
        return (bool) $this->is_correct;
    }
}
