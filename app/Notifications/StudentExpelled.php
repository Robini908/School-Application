<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StudentExpelled extends Notification
{
    use Queueable;

    protected $student;

    public function __construct($student)
    {
        $this->student = $student;
    }

    public function via($notifiable)
    {
        return ['mail']; // You can use other channels like 'database', 'nexmo', etc.
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Student Expulsion Notice')
                    ->greeting('Hello!')
                    ->line('This is to inform you that your child, ' . $this->student->first_name . ' ' . $this->student->last_name . ', has been expelled.')
                    ->line('Reason for expulsion: ' . $this->student->expulsion_reason)
                    ->line('Thank you for your understanding.');
    }
}
