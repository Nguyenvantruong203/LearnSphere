<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\Events\Verified;
use App\Listeners\NotifyAdminWhenInstructorVerified;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: [
            __DIR__ . '/../routes/admin.php',
            __DIR__ . '/../routes/student.php',
            __DIR__ . '/../routes/instructor.php',
            __DIR__ . '/../routes/auth.php',
            __DIR__ . '/../routes/channels.php',
        ],
        apiPrefix: 'api',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )

    // ✅ Đăng ký Event → Listener
    // ->withEvents([
    //     Verified::class => [
    //         NotifyAdminWhenInstructorVerified::class,
    //     ],
    // ])

    // ✅ Middleware setup
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(prepend: [
            \Illuminate\Http\Middleware\HandleCors::class,
        ]);
    })

    // ✅ Exception handler (để trống cũng được)
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
