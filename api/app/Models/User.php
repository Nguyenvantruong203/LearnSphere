<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * Các trường có thể gán hàng loạt.
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'password',
        'address',
        'avatar_url',
        'birth_date',
        'gender',
        'role',
        'status',
        'google_id',
        'google_token',
        'google_refresh_token',
        'email_verified_at',
    ];

    /**
     * Các trường bị ẩn khi convert sang array/json.
     */
    protected $hidden = [
        'password',
        'remember_token',
        'google_token',
        'google_refresh_token',
    ];

    /**
     * Kiểu dữ liệu cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'birth_date' => 'date',
    ];

    /**
     * Mutator: luôn hash password khi set.
     */
    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = bcrypt($value);
        }
    }

    /**
     * Check nhanh quyền user.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isInstructor(): bool
    {
        return $this->role === 'instructor';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    /**
     * Các quan hệ (ví dụ nếu sau này có bảng courses, posts...).
     */
    // public function courses() {
    //     return $this->hasMany(Course::class);
    // }
}
