<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DisapprovalNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $disapprovalReason;

    public function __construct($student, $disapprovalReason)
    {
        $this->student = $student;
        $this->disapprovalReason = $disapprovalReason;
    }

    public function build()
    {
        return $this->view('emails.disapproval-notification')
                    ->subject('Disapproval Notification')
                    ->with([
                        'studentName' => "{$this->student->first_name} {$this->student->last_name}",
                        'disapprovalReason' => $this->disapprovalReason,
                    ]);
    }
}
