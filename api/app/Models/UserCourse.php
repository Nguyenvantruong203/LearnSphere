<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserCourse extends Pivot
{
    use HasFactory;

    protected $table = 'user_courses';

    protected $fillable = [
        'user_id',
        'course_id',
        'enrolled_at',
        'is_paid',
        'access_expires_at',
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
        'access_expires_at' => 'datetime',
        'is_paid' => 'boolean',
    ];

    public $incrementing = false;   // vì khóa chính là composite (user_id + course_id)
    protected $primaryKey = null;

    // Quan hệ tới User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Quan hệ tới Course
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
