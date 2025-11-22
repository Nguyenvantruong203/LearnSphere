<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashcardLog extends Model
{
    use HasFactory;

    protected $table = 'flashcard_logs';

    protected $fillable = [
        'user_id',
        'flashcard_id',
        'reviewed_at',
    ];

    public function flashcard()
    {
        return $this->belongsTo(Flashcard::class, 'flashcard_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
