<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flashcard extends Model
{
    use HasFactory;

    protected $table = 'flashcards';

    protected $fillable = [
        'topic_id',
        'front',
        'back',
        'image_url',
        'audio_url',
    ];

    /* ==========================
     |  Relationships
     ==========================*/

    public function topic()
    {
        return $this->belongsTo(Topic::class, 'topic_id');
    }

    public function logs()
    {
        return $this->hasMany(FlashcardLog::class, 'flashcard_id');
    }

    /* ==========================
     |  Helpers
     ==========================*/

    /**
     * Lấy lần review gần nhất của user
     */
    public function lastReviewForUser($userId)
    {
        return $this->logs()
            ->where('user_id', $userId)
            ->orderByDesc('reviewed_at')
            ->first();
    }

    /**
     * Kiểm tra user đã từng review flashcard này chưa
     */
    public function hasReviewed($userId): bool
    {
        return $this->logs()
            ->where('user_id', $userId)
            ->exists();
    }
}
