<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flashcard extends Model
{
    use HasFactory;

    protected $table = 'flashcards';

    protected $fillable = [
        'flashcard_set_id',
        'front',
        'back',
        'image_url',
        'audio_url',
    ];

    // Thuộc về Flashcard Set
    public function set()
    {
        return $this->belongsTo(FlashcardSet::class, 'flashcard_set_id');
    }

    // Có nhiều logs review của user
    public function logs()
    {
        return $this->hasMany(FlashcardLog::class, 'flashcard_id');
    }

    /* ==========================
     |  Helpers
     ==========================*/

    // Lần review gần nhất của user
    public function lastReviewForUser($userId)
    {
        return $this->logs()
            ->where('user_id', $userId)
            ->orderByDesc('reviewed_at')
            ->first();
    }

    // Kiểm tra user đã từng review flashcard này chưa
    public function hasReviewed($userId): bool
    {
        return $this->logs()
            ->where('user_id', $userId)
            ->exists();
    }
}
