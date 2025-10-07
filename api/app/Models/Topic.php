<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;

    protected $table = 'topics';

    protected $fillable = [
        'course_id',
        'title',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    /* =========================
     |  Relationships
     |=========================*/

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lessons()
    {
        // Giữ thứ tự theo 'order' của lesson
        return $this->hasMany(Lesson::class)->orderBy('order');
    }
    // 🔹 Quiz của topic
    public function quiz()
    {
        return $this->hasOne(Quiz::class, 'topic_id', 'id')
            ->whereNull('lesson_id'); // chỉ quiz của topic, không gắn lesson
    }

    /* =========================
     |  Scopes
     |=========================*/

    public function scopeForCourse($q, int $courseId)
    {
        return $q->where('course_id', $courseId);
    }

    public function scopeOrdered($q)
    {
        return $q->orderBy('order');
    }

    public function scopeSearch($q, ?string $term)
    {
        if (!$term) return $q;
        return $q->where('title', 'like', "%{$term}%");
    }

    /* =========================
     |  Hooks
     |=========================*/

    protected static function booted(): void
    {
        // Tự gán thứ tự tăng dần trong 1 khóa nếu chưa truyền 'order'
        static::creating(function (self $m) {
            if (empty($m->order)) {
                $max = static::where('course_id', $m->course_id)->max('order');
                $m->order = ($max ?? 0) + 1;
            }
        });
    }
    public function quizzes()
    {
        return $this->hasMany(Quiz::class)->orderBy('id');
    }
}
