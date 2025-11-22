<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseReview extends Model
{
    use HasFactory;

    protected $table = 'course_reviews';

    protected $fillable = [
        'course_id',
        'user_id',
        'rating',
        'comment',
        'created_at',
    ];

    public $timestamps = false;

    /**
     * Relations
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')
            ->select(['id', 'name', 'avatar_url']);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
