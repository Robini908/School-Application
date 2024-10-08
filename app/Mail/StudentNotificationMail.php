<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StudentNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $studentName;
    public $parentName;
    public $notificationContent;
    public $filePath;

    /**
     * Create a new message instance.
     *
     * @param  string  $studentName
     * @param  string  $parentName
     * @param  string  $notificationContent
     * @param  string|null  $filePath
     * @return void
     */
    public function __construct($studentName, $parentName, $notificationContent, $filePath = null)
    {
        $this->studentName = $studentName;
        $this->parentName = $parentName;
        $this->notificationContent = $notificationContent;
        $this->filePath = $filePath;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->view('emails.student-notification')
                      ->subject('Important Notification for Student');

        // Attach file if it exists
        if ($this->filePath) {
            $email->attach(storage_path("app/public/{$this->filePath}"));
        }

        return $email;
    }
}
