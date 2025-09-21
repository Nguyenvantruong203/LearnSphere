<?php

namespace App\Http\Controllers;

use App\Models\GoogleOauthToken;
use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class GoogleAuthController extends Controller
{

    public function redirect()
    {
        $client = new GoogleClient();
        $client->setClientId(config('services.youtube.client_id'));
        $client->setClientSecret(config('services.youtube.client_secret'));
        $client->setRedirectUri(config('services.youtube.redirect'));
        $client->setAccessType('offline');
        $client->setPrompt('consent');
        $client->setScopes([
            \Google\Service\YouTube::YOUTUBE_UPLOAD,
            'https://www.googleapis.com/auth/youtube.readonly',
        ]);

        $state = request('state') ?? Auth::id();
        $client->setState($state);

        return redirect()->away($client->createAuthUrl());
    }

    public function callback()
    {
        $client = new GoogleClient();
        $client->setClientId(config('services.youtube.client_id'));
        $client->setClientSecret(config('services.youtube.client_secret'));
        $client->setRedirectUri(config('services.youtube.redirect'));

        if (!request()->has('code')) {
            return response()->json(['message' => 'Authorization code missing'], 400);
        }

        $token = $client->fetchAccessTokenWithAuthCode(request('code'));

        if (isset($token['error'])) {
            return response()->json(['message' => $token['error']], 400);
        }

        $userId = Auth::id() ?? request('state');
        if (!$userId) {
            return response()->json(['message' => 'Missing user context'], 400);
        }

        GoogleOauthToken::updateOrCreate(
            [
                'user_id'  => $userId,
                'provider' => 'youtube',
            ],
            [
                'access_token'  => $token['access_token'],
                'refresh_token' => $token['refresh_token'] ?? null,
                'expires_at'    => now()->addSeconds($token['expires_in']),
            ]
        );

        return redirect(config('app.frontend_url') . '/admin/courses?youtube=connected');
    }

    public function status(Request $request)
    {
        $userId = Auth::id() ?? $request->get('user_id');
        $record = GoogleOauthToken::where('user_id', $userId)
            ->where('provider', 'youtube')
            ->first();

        return response()->json([
            'connected' => (bool) $record,
            'expires_at' => $record?->expires_at,
        ]);
    }
}
