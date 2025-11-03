<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class InstructorApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $instructor;

    public function __construct(User $instructor)
    {
        $this->instructor = $instructor;
    }

    public function build()
    {
        return $this->subject('🎉 Congratulations! Your Instructor Application Has Been Approved')
            ->markdown('emails.instructors.approved')
            ->with(['instructor' => $this->instructor]);
    }
}
