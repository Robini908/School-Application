<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DisapprovalNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $reason;

    public function __construct($student, $reason)
    {
        $this->student = $student;
        $this->reason = $reason;
    }

    public function build()
    {
        return $this->view('emails.disapproval-notification')
                    ->subject('Student Disapproval Notification')
                    ->with([
                        'studentName' => $this->student->first_name . ' ' . $this->student->last_name,
                        'reason' => $this->reason
                    ]);
    }
}
