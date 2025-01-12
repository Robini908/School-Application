<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class SystemNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $message;
    public $title;
    public $timestamp;

    public function __construct($message, $title = 'System Notification')
    {
        $this->message = $message;
        $this->title = $title;
        $this->timestamp = Carbon::now()->format('F j, Y, g:i a'); // Human-readable timestamp
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast']; // Send to database and broadcast
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->title, // Include title
            'message' => $this->message,
            'timestamp' => $this->timestamp, // Include human-readable timestamp
            'url' => '/notifications', // URL to redirect when clicked
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'title' => $this->title, // Include title
            'message' => $this->message,
            'timestamp' => $this->timestamp, // Include human-readable timestamp
            'url' => '/notifications',
        ]);
    }
}