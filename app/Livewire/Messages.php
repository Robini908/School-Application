<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Auth;

class Messages extends Component
{
    public $unreadMessagesCount = 0;
    public $recentMessages = [];
    public $isDropdownOpen = false;

    protected $listeners = ['messageRead' => 'updateUnreadCount'];

    public function mount()
    {
        $this->updateUnreadCount();
        $this->loadRecentMessages();
    }

    public function updateUnreadCount()
    {
        $this->unreadMessagesCount = ChatMessage::where('receiver_id', Auth::id())
            ->whereNull('read_at')
            ->count();
    }

    public function loadRecentMessages()
    {
        $this->recentMessages = ChatMessage::with('sender') // Load the sender relationship
            ->where('receiver_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();
    }

    public function markAsRead($messageId)
    {
        $message = ChatMessage::find($messageId);
        if ($message && $message->receiver_id === Auth::id()) {
            $message->update(['read_at' => now()]);
            $this->updateUnreadCount();
            $this->loadRecentMessages();
        }
        // Keep the dropdown menu open after marking as read
        $this->isDropdownOpen = true;
    }

    public function toggleDropdown()
    {
        $this->isDropdownOpen = !$this->isDropdownOpen;
        if ($this->isDropdownOpen) {
            $this->loadRecentMessages();
        }
    }

    public function getInitials($name)
    {
        if (auth()->check() && auth()->user()->name === $name) {
            return 'You';
        }

        $names = explode(' ', $name);
        $initials = '';
        foreach ($names as $n) {
            $initials .= strtoupper(substr($n, 0, 1));
        }
        return $initials;
    }
    public function getRandomColor($userId)
    {
        if (auth()->check() && auth()->id() === $userId) {
            // Return a specific color for the authenticated user
            return '#FFFFFF'; // Example: White color
        }

        $colors = ['#FF6B6B', '#4ECDC4', '#45B7D7', '#A4D555', '#D4A5A5', '#FFD166', '#06D6A0', '#118AB2'];
        $colorIndex = $userId % count($colors);
        return $colors[$colorIndex];
    }


    public function render()
    {
        return view('livewire.messages');
    }
}
