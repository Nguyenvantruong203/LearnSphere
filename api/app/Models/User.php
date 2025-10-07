<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;
use App\Mail\CustomVerifyEmail;

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
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['avatar_url'];

    /**
     * Mutator: luôn hash password khi set.
     */
    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = bcrypt($value);
        }
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail);
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
     * Get the full URL to the user's avatar.
     *
     * @return string|null
     */
    public function getAvatarUrlAttribute()
    {
        // Lấy đường dẫn thô từ cột 'avatar_url' để tránh đệ quy vô hạn
        $path = $this->attributes['avatar_url'] ?? null;

        if ($path) {
            // Nếu đã là một URL đầy đủ (ví dụ: từ Google), trả về luôn
            if (str_starts_with($path, 'http')) {
                return $path;
            }
            // Nếu là đường dẫn tương đối, tạo URL đầy đủ từ storage
            // Sử dụng asset() helper để tránh lỗi linter "Undefined method 'url'"
            return asset('storage/' . $path);
        }

        // Nếu không có avatar, trả về null
        return null;
    }

    /**
     * Các quan hệ (ví dụ nếu sau này có bảng courses, posts...).
     */
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'user_courses')
            ->using(UserCourse::class)
            ->withPivot(['enrolled_at', 'is_paid', 'access_expires_at'])
            ->withTimestamps();
    }

    // ====== QUIZ ATTEMPTS (mỗi lượt làm bài) ======
    public function quizAttempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    // ====== QUIZZES (liên kết thông qua attempts) ======
    public function quizzes()
    {
        return $this->belongsToMany(Quiz::class, 'quiz_attempts')
            ->withPivot(['attempt_no', 'score', 'status', 'submitted_at'])
            ->withTimestamps();
    }
    public function notifications()
    {
        return $this->belongsToMany(Notification::class, 'notification_users')
            ->withPivot('is_read', 'read_at')
            ->withTimestamps();
    }
}
