<?php

namespace App\Providers;

use Illuminate\Auth\Events\Verified;
use App\Listeners\NotifyAdminWhenInstructorVerified;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Verified::class => [
            NotifyAdminWhenInstructorVerified::class,
        ],
    ];

    public function boot(): void
    {
        //
    }
}
