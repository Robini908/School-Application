<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class NotificationsManagement extends Component
{
    use WithPagination;

    public $perPage = 10; // Notifications per page
    public $search = ''; // Search term for filtering notifications
    public $showDetails = false; // Boolean to toggle details view
    public $selectedNotification = null; // Selected notification for details view
    public $selectedNotifications = []; // Array to store selected notification IDs for bulk actions
    public $isShowingNotification = false; // Boolean to control the visibility of the notification details card
    public $pollingInterval = 5000; // Set the polling interval in milliseconds


    // Mark a notification as read
    public function markAsRead($notificationId)
    {
        $notification = Auth::user()->notifications()->findOrFail($notificationId);
        $notification->markAsRead();

        session()->flash('success', 'Notification marked as read.');
    }

    // Mark a notification as unread
    public function markAsUnread($notificationId)
    {
        $notification = Auth::user()->notifications()->findOrFail($notificationId);
        $notification->update(['read_at' => null]);

        session()->flash('success', 'Notification marked as unread.');
    }

    // Delete a notification
    public function deleteNotification($notificationId)
    {
        $notification = Auth::user()->notifications()->findOrFail($notificationId);
        $notification->delete();

        session()->flash('success', 'Notification deleted successfully.');
    }

    // Mark all notifications as read
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        session()->flash('success', 'All notifications marked as read.');
    }

    // Mark all notifications as unread
    public function markAllAsUnread()
    {
        Auth::user()->notifications()->update(['read_at' => null]);
        session()->flash('success', 'All notifications marked as unread.');
    }

    // Delete selected notifications
    public function deleteSelectedNotifications()
    {
        if (empty($this->selectedNotifications)) {
            session()->flash('error', 'No notifications selected.');
            return;
        }

        Auth::user()->notifications()->whereIn('id', $this->selectedNotifications)->delete();
        $this->selectedNotifications = []; // Clear the selection
        session()->flash('success', 'Selected notifications deleted successfully.');
    }

    // Show notification details
    public function showNotificationDetails($notificationId)
    {
        $this->selectedNotification = Auth::user()->notifications()->findOrFail($notificationId);
        $this->isShowingNotification = true; // Show the details card
    }

    // Close notification details
    public function closeDetails()
    {
        $this->isShowingNotification = false; // Hide the details card
        $this->selectedNotification = null; // Clear the selected notification
    }

    // Render the component

    public function render()
    {
        $notifications = Auth::user()->notifications()
            ->when($this->search, function ($query) {
                $query->where('data', 'like', '%' . $this->search . '%');
            })
            ->paginate($this->perPage);

        return view('livewire.notifications-management', [
            'notifications' => $notifications,
            'pollingInterval' => $this->pollingInterval,
        ]);
    }
}
