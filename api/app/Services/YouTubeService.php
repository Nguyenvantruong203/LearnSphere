<?php

namespace App\Services;

use App\Models\GoogleOauthToken;
use Google\Client as GoogleClient;
use Google\Service\YouTube as GoogleYouTube;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class YouTubeService
{
    protected GoogleClient $client;

    public function __construct()
    {
        $this->client = new GoogleClient();
        $this->client->setClientId(config('services.youtube.client_id'));
        $this->client->setClientSecret(config('services.youtube.client_secret'));
        $this->client->setRedirectUri(config('services.youtube.redirect'));
        $this->client->setAccessType('offline');
        $this->client->setPrompt('consent');
        $this->client->setScopes([GoogleYouTube::YOUTUBE_UPLOAD, 'https://www.googleapis.com/auth/youtube.readonly']);
    }

    protected function getTokenForUser(int $userId): ?GoogleOauthToken
    {
        return GoogleOauthToken::where('user_id', $userId)
            ->where('provider', 'youtube')
            ->first();
    }

    protected function setAccessTokenForUser(int $userId): bool
    {
        $token = $this->getTokenForUser($userId);

        if (!$token) {
            return false;
        }

        $this->client->setAccessToken([
            'access_token' => $token->access_token,
            'refresh_token' => $token->refresh_token,
            'expires_in' => $token->expires_at->getTimestamp() - time(),
        ]);

        if ($this->client->isAccessTokenExpired()) {
            $newAccessToken = $this->client->fetchAccessTokenWithRefreshToken($token->refresh_token);
            if (isset($newAccessToken['error'])) {
                return false;
            }
            $this->client->setAccessToken($newAccessToken);
            $token->update([
                'access_token' => $newAccessToken['access_token'],
                'expires_at' => now()->addSeconds($newAccessToken['expires_in']),
            ]);
        }

        return true;
    }

    public function uploadVideo(UploadedFile $file, string $title, string $description = ''): ?string
    {
        if (!$this->setAccessTokenForUser(Auth::id())) {
            throw new \Exception('Người dùng chưa kết nối YouTube hoặc token đã hết hạn.', 403);
        }

        $youtube = new GoogleYouTube($this->client);

        $video = new \Google\Service\YouTube\Video();
        $video->setSnippet(new \Google\Service\YouTube\VideoSnippet([
            'title' => $title,
            'description' => $description,
            'tags' => ['elearning', 'education'],
            'categoryId' => '27',
        ]));
        $video->setStatus(new \Google\Service\YouTube\VideoStatus([
            'privacyStatus' => 'unlisted',
            'embeddable' => true,
        ]));

        $chunkSizeBytes = 5 * 1024 * 1024; // tăng chunk size
        $this->client->setDefer(true);

        $insertRequest = $youtube->videos->insert('snippet,status', $video);

        $mimeType = $file->getMimeType() ?? 'video/*';

        $media = new \Google\Http\MediaFileUpload(
            $this->client,
            $insertRequest,
            $mimeType,
            null,
            true,
            $chunkSizeBytes
        );
        $media->setFileSize($file->getSize());

        $status = false;
        $handle = fopen($file->getRealPath(), "rb");
        while (!$status && !feof($handle)) {
            $chunk = fread($handle, $chunkSizeBytes);
            $status = $media->nextChunk($chunk);
        }
        fclose($handle);

        // KHÔNG xoá file ở đây, Laravel tự dọn
        // unlink($file->getRealPath());

        if ($status instanceof \Google\Service\YouTube\Video) {
            return $status->getId();
        }

        return null;
    }

    public static function parseVideoId(string $url): ?string
    {
        $pattern = '~(?:youtu\.be/|youtube\.com/(?:watch\?v=|embed/|shorts/|v/))([A-Za-z0-9_-]{11})~';
        preg_match($pattern, $url, $matches);
        return $matches[1] ?? null;
    }
}
