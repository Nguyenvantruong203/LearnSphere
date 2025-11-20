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
        'status', // pending | approved | rejected
        'google_id',
        'google_token',
        'google_refresh_token',
        'email_verified_at',

        // 🔹 Dành riêng cho instructor
        'expertise',
        'bio',
        'linkedin_url',
        'portfolio_url',
        'teaching_experience',
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
     * Các accessor thêm vào tự động khi trả JSON.
     */
    protected $appends = ['avatar_url'];

    /**
     * Mutator: tự động hash password khi set.
     */
    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = bcrypt($value);
        }
    }

    /**
     * Gửi email xác minh đăng ký tài khoản.
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail);
    }

    // ==========================
    // 🚦 CHECK ROLE
    // ==========================

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

    // ==========================
    // 🧑‍🏫 SCOPE CHO INSTRUCTOR
    // ==========================

    /**
     * Truy vấn nhanh danh sách giảng viên được duyệt.
     */
    public function scopeApprovedInstructors($query)
    {
        return $query->where('role', 'instructor')
            ->where('status', 'approved');
    }

    /**
     * Truy vấn nhanh danh sách giảng viên đang chờ duyệt.
     */
    public function scopePendingInstructors($query)
    {
        return $query->where('role', 'instructor')
            ->where('status', 'pending');
    }

    // ==========================
    // 🌐 AVATAR
    // ==========================
    public function getAvatarUrlAttribute()
    {
        $path = $this->attributes['avatar_url'] ?? null;

        if ($path) {
            if (str_starts_with($path, 'http')) {
                return $path;
            }
            return asset('storage/' . $path);
        }

        // Default avatar fallback
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name ?? 'User');
    }

    // ==========================
    // 🔗 QUAN HỆ
    // ==========================

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'user_courses')
            ->using(UserCourse::class)
            ->withPivot(['enrolled_at', 'is_paid', 'access_expires_at'])
            ->withTimestamps();
    }

    public function quizAttempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

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

    public function chatThreads()
    {
        return $this->belongsToMany(ChatThread::class, 'chat_participants', 'user_id', 'thread_id')
            ->withPivot('role', 'joined_at', 'last_read_at')
            ->withTimestamps();
    }

    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class, 'sender_id');
    }

    public function groupThreads()
    {
        return $this->chatThreads()->where('is_group', true);
    }

    public function privateThreads()
    {
        return $this->chatThreads()->where('is_group', false);
    }

    public function wallet()
    {
        return $this->hasOne(InstructorWallet::class, 'instructor_id');
    }

    public function payouts()
    {
        return $this->hasMany(Payout::class, 'instructor_id');
    }
    //lấy danh sách admin để gửi thông báo
    public static function admins()
    {
        return self::where('role', 'admin')->pluck('id')->toArray();
    }

    public function completedLessons()
    {
        return $this->hasMany(LessonCompletion::class);
    }
}
