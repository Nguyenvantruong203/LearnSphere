<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class InstructorRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $instructor;
    public $reason;

    public function __construct(User $instructor, ?string $reason = null)
    {
        $this->instructor = $instructor;
        $this->reason = $reason;
    }

    public function build()
    {
        return $this->subject('⚠️ Instructor Application Update')
            ->markdown('emails.instructors.rejected')
            ->with([
                'instructor' => $this->instructor,
                'reason' => $this->reason,
            ]);
    }
}
