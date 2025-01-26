<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StudentSuspensionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $pdfPath;

    /**
     * Create a new message instance.
     *
     * @param array $data
     * @param string $pdfPath
     */
    public function __construct($data, $pdfPath)
    {
        $this->data = $data;
        $this->pdfPath = $pdfPath;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('⚠️ Suspension Notification')
                    ->view('emails.student-suspension')
                    ->with($this->data)
                    ->attach($this->pdfPath, [
                        'as' => 'suspension_notice.pdf',
                        'mime' => 'application/pdf',
                    ]);
    }
}