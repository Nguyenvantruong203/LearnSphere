<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashcardSet extends Model
{
    use HasFactory;

    protected $table = 'flashcard_sets';

    protected $fillable = [
        'topic_id',
        'title',
        'description',
    ];

    // Một Set thuộc về một Topic
    public function topic()
    {
        return $this->belongsTo(Topic::class, 'topic_id');
    }

    // Một Set có nhiều flashcards
    public function flashcards()
    {
        return $this->hasMany(Flashcard::class, 'flashcard_set_id');
    }
}
