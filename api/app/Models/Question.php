<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question extends Model
{
    protected $table = 'questions';

    protected $fillable = [
        'quiz_id',
        'type',            // multiple_choice | true_false | short_answer
        'text',
        'options',
        'correct_options',
        'weight',
    ];

    protected $casts = [
        'options'         => 'array',
        'correct_options' => 'array',
        'weight'          => 'decimal:2',
    ];

    /* =========================
     |  Relationships
     |=========================*/

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    /* =========================
     |  Scopes
     |=========================*/

    public function scopeForQuiz($q, int $quizId)
    {
        return $q->where('quiz_id', $quizId);
    }
}
