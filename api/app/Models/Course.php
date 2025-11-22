<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'courses';

    protected $fillable = [
        'title',
        'slug',
        'thumbnail_url',
        'short_description',
        'description',

        'status',
        'publish_at',
        'price',
        'currency',      // USD
        'level',
        'language',      // en
        'is_featured',
        'subject',

        'instructor_share',
        'platform_fee',

        'rejection_reason',
        'rejected_at',

        'created_by',
        'updated_by',
    ];


    /**
     * Kiểu dữ liệu/cast cho các trường.
     */
    protected $casts = [
        'publish_at'       => 'datetime',
        'price'            => 'decimal:2',
        'instructor_share' => 'decimal:2',
        'platform_fee'     => 'decimal:2',
    ];


    /**
     * Giá trị mặc định.
     */
    protected $attributes = [
        'status'          => 'draft',
        'price'           => 0,
        'currency'        => 'VND',    // ✅ đổi sang USD
        'level'           => 'beginner',
        'language'        => 'en',     // ✅ đổi sang en
        'is_featured'     => false,
        'instructor_share' => 70.00,    // ✅ thêm mặc định
        'platform_fee'    => 30.00,    // ✅ thêm mặc định
    ];


    /**
     * Append thuộc tính tính toán is_free vào JSON (tuỳ thích).
     */
    protected $appends = ['is_free'];

    /**
     * Tạo slug khi tạo mới (nếu chưa set).
     * Không tự đổi slug khi update để tránh vỡ URL.
     */
    protected static function booted(): void
    {
        static::creating(function (self $course) {
            if (empty($course->slug)) {
                $course->slug = static::generateUniqueSlug($course->title);
            }
        });
    }

    protected static function generateUniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;

        // include trashed để tránh trùng slug với bản đã xoá mềm
        while (static::withTrashed()->where('slug', $slug)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    /* =========================
     |  Relationships
     |=========================*/

    public function topics()
    {
        return $this->hasMany(Topic::class)->orderBy('order');
    }

    // Tiện truy vấn tất cả lessons qua topics
    public function lessons()
    {
        return $this->hasManyThrough(
            Lesson::class,      // model đích
            Topic::class,       // model trung gian
            'course_id',        // FK trên topics trỏ về courses
            'topic_id',         // FK trên lessons trỏ về topics
            'id',               // Local key trên courses
            'id'                // Local key trên topics
        );
    }
    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }
    // Danh sách học viên đã ghi danh
    public function students()
    {
        return $this->belongsToMany(User::class, 'user_courses', 'course_id', 'user_id')
            ->withPivot(['enrolled_at', 'is_paid', 'access_expires_at']);
    }


    public function instructor()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopePublished($q)
    {
        return $q->where('status', 'approved')
            ->where(function ($q) {
                $q->whereNull('publish_at')
                    ->orWhere('publish_at', '<=', now());
            });
    }

    public function getIsFreeAttribute(): bool
    {
        return (float) $this->price <= 0;
    }

    public function refreshSlug(): void
    {
        $this->slug = static::generateUniqueSlug($this->title);
    }

    public function getThumbnailUrlAttribute($value)
    {
        if ($value && !str_starts_with($value, 'http')) {
            return asset('storage/' . $value);
        }
        return $value;
    }

    //xem dánh sách doanh thu từ khoá học
    public function payouts()
    {
        return $this->hasManyThrough(
            Payout::class,
            OrderItem::class,
            'course_id',      // FK trên order_items
            'order_item_id',  // FK trên payouts
            'id',             // local key on courses
            'id'              // local key on order_items
        );
    }

    // Review của khóa học
    public function reviews()
    {
        return $this->hasMany(CourseReview::class, 'course_id');
    }
}
