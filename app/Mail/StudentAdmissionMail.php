<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; // Add this import
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StudentAdmissionMail extends Mailable implements ShouldQueue // Implement ShouldQueue
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @param array $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('🎉 Admission Confirmation')
                    ->view('emails.student-admission')
                    ->with($this->data);
    }
}