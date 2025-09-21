<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoogleOauthToken extends Model
{
    protected $table = 'google_oauth_tokens';

    protected $fillable = [
        'user_id',
        'provider',
        'access_token',
        'refresh_token',
        'expires_at',
    ];

    protected $casts = [
        // Lưu ý: nếu muốn mã hoá tại rest, dùng 'encrypted' cho 2 cột dưới:
        // 'access_token'  => 'encrypted',
        // 'refresh_token' => 'encrypted',
        'expires_at' => 'datetime',
    ];
}
