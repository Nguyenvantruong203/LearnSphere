<?php
namespace App\Mail;
;

use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class CustomVerifyEmail extends BaseVerifyEmail
{
    protected function buildMailMessage($url)
    {
        return (new MailMessage)
            ->subject('Xác minh email của bạn')
            ->view('emails.verify', [
                'actionUrl' => $url,
                'expiration' => config('auth.verification.expire', 60),
                'appName' => config('app.name'),
            ]);
    }
}
