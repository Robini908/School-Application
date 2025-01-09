<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Notifications extends Component
{
    public $notifications;
    public $unreadCount; // Ensure this is a public property
    public $privateChannelName;

    protected $listeners = [
        'notificationReceived' => 'loadNotifications',
    ];

    public function mount()
    {
        $this->privateChannelName = "echo-private:App.Models.User." . auth()->id() . ",.Illuminate\\Notifications\\Events\\BroadcastNotificationCreated";
        $this->loadNotifications(); // Initialize $notifications and $unreadCount
    }

    public function getListeners()
    {
        return [
            $this->privateChannelName => 'loadNotifications',
        ];
    }

    public function loadNotifications()
    {
        if (!Auth::check()) {
            $this->notifications = collect();
            $this->unreadCount = 0;
            return;
        }

        $user = Auth::user();
        $this->notifications = $user->notifications()
            ->latest()
            ->take(10) // Show only 10 most recent notifications
            ->get();
        $this->unreadCount = $user->unreadNotifications()->count(); // Update $unreadCount

        // Debugging: Log the value of $unreadCount
        \Log::info('Unread Count:', ['unreadCount' => $this->unreadCount]);
    }

    public function markAsRead($notificationId)
    {
        if (!Auth::check()) {
            return;
        }

        $notification = Auth::user()->notifications()->find($notificationId);
        if ($notification) {
            $notification->markAsRead();
            $this->loadNotifications(); // Refresh notifications
        }
    }

    public function render()
    {
        return view('livewire.notifications')
            ->with('pollingInterval', 5000); // Poll every 5 seconds
    }
}