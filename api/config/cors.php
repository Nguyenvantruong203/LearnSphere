<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Cho phép truy cập từ frontend (Vite/localhost:5173)
    | cho tất cả các route API, client, admin.
    |
    */

    'paths' => [
        'api/*',
        'client/*',
        'admin/*',
        'sanctum/csrf-cookie',
        'broadcasting/auth', // Thêm dòng này để cho phép xác thực kênh bảo vệ
    ],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        env('FRONTEND_URL', 'http://localhost:5173'),
        'http://127.0.0.1:5173',
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,// cho phép gửi cookie/token
];
