<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ✅ Đăng ký route broadcast auth
        Broadcast::routes(['prefix' => 'api', 'middleware' => ['auth:sanctum']]);

        // ✅ Load file channels
        require base_path('routes/channels.php');
    }
}
