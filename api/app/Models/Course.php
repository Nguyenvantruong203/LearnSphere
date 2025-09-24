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

    /**
     * Cho phép gán hàng loạt.
     * Giữ danh sách này khớp với migration mà mình đã thống nhất.
     */
    protected $fillable = [
        'title',
        'slug',
        'thumbnail_url',
        'short_description',
        'description',

        'status',       // draft|published|archived
        'visibility',   // public|unlisted|private
        'publish_at',

        'price',
        'currency',     // VND
        'level',        // beginner|intermediate|advanced
        'language',     // vi

        'created_by',
        'updated_by',
    ];

    /**
     * Kiểu dữ liệu/cast cho các trường.
     */
    protected $casts = [
        'publish_at' => 'datetime',
        'price'      => 'decimal:2',
    ];

    /**
     * Giá trị mặc định.
     */
    protected $attributes = [
        'status'     => 'draft',
        'visibility' => 'public',
        'price'      => 0,
        'currency'   => 'VND',
        'level'      => 'beginner',
        'language'   => 'vi',
    ];

    /**
     * Append thuộc tính tính toán is_free vào JSON (tuỳ thích).
     */
    protected $appends = ['is_free'];

    /**
     * Route Model Binding sử dụng slug (tiện SEO/URL).
     * Bỏ đi nếu anh vẫn muốn dùng id.
     */
    // public function getRouteKeyName(): string
    // {
    //     return 'slug';
    // }

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
        return $this->belongsToMany(User::class, 'enrollments')
                    ->withTimestamps();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /* =========================
     |  Scopes
     |=========================*/

    public function scopePublished($q)
    {
        return $q->where('status', 'published')
                 ->where(function ($q) {
                     $q->whereNull('publish_at')
                       ->orWhere('publish_at', '<=', now());
                 });
    }

    public function scopeVisible($q, string $visibility = 'public')
    {
        return $q->where('visibility', $visibility);
    }

    public function scopeSearch($q, ?string $term)
    {
        if (!$term) return $q;
        return $q->where(function ($qq) use ($term) {
            $qq->where('title', 'like', "%{$term}%")
               ->orWhere('short_description', 'like', "%{$term}%")
               ->orWhere('description', 'like', "%{$term}%");
        });
    }

    /* =========================
     |  Accessors
     |=========================*/

    // is_free = true nếu price <= 0
    public function getIsFreeAttribute(): bool
    {
        return (float) $this->price <= 0;
    }

    /* =========================
     |  Helpers (tuỳ chọn)
     |=========================*/

    /**
     * Gọi thủ công khi muốn đổi slug theo title mới.
     * Không tự động chạy trong updating() để tránh vỡ URL.
     */
    public function refreshSlug(): void
    {
        $this->slug = static::generateUniqueSlug($this->title);
    }
}
