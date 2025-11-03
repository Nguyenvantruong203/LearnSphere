<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class NewInstructorAppliedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $instructor;
    public $reviewUrl;

    public function __construct(User $instructor)
    {
        $this->instructor = $instructor;
        $this->reviewUrl = config('app.url') . '/admin/instructors/' . $instructor->id . '/review';
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '📩 New Instructor Application Received',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.instructors.applied',
            with: [
                'instructor' => $this->instructor,
                'reviewUrl' => $this->reviewUrl,
            ],
        );
    }
}
