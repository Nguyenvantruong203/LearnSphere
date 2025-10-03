<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InstructorNewEnrollmentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $course;

    public function __construct(Order $order, Course $course)
    {
        $this->order = $order;
        $this->course = $course;
    }

    public function build()
    {
        return $this->subject('Khóa học mới đã được đăng ký')
            ->view('emails.instructor_new_enrollment');
    }
}
