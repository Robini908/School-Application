<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ChatMessage;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class MessageManager extends Component
{
    public $view = 'inbox'; // Default view
    public $selectedUser = null;
    public $isTyping = false;
    public $otherUserTyping = false;
    public $searchTerm = '';
    public $searchResults = [];
    public $message = '';
    public $messages = [];
    public $editReply = false;
    public $replyMessageText = '';
    public $messageBeingRepliedTo = null;

    public $messageReply = ''; // Store the reply message
    public $messageId = null;  // Store the ID of the message being replied t

    public $editedMessage = [];
    protected $listeners = [
        'messageSent' => 'loadMessages',
        'userTyping' => 'handleUserTyping',
        'userStoppedTyping' => 'handleUserStoppedTyping',
    ];


    public function editMessage($messageId)
    {
        $message = ChatMessage::find($messageId);
        if ($message) {
            $this->editReply = true;
            $this->editedMessage = $message->message; // Set the message to be edited
            $this->messageBeingRepliedTo = $message; // Keep track of the message being edited
        }
    }

    public function updateMessage($messageId)
    {
        $message = ChatMessage::find($messageId);
        if ($message && $this->editedMessage) {
            $message->message = $this->editedMessage; // Update the message
            $message->save();
            $this->editReply = false; // Reset edit state
        }
        $this->loadMessages();
    }




    public function cancelEdit()
    {
        $this->editReply = false; // Reset the edit state
        $this->editedMessage = ''; // Clear the edited message text
    }

    public function replyMessage($messageId)
    {
        $this->messageBeingRepliedTo = ChatMessage::find($messageId); // Set message to reply to
        $this->editReply = true; // Enable the reply form
    }

    public function sendReply($messageId)
    {
        $message = new ChatMessage();
        $message->sender_id = auth()->id();
        $message->receiver_id = $this->messageBeingRepliedTo->sender_id; // Set the receiver to the original sender
        $message->message = $this->replyMessageText; // Set the reply message
        $message->reply_to = $messageId; // Link to the original message
        $message->save();

        $this->editReply = false; // Reset edit state
        $this->replyMessageText = ''; // Clear reply input
    }

    public function cancelReply()
    {
        $this->editReply = false; // Reset edit state
        $this->replyMessageText = ''; // Clear the reply text
    }




    public function handleUserTyping($data)
    {
        if ($data['receiverId'] == auth()->id() && $data['userId'] == $this->selectedUser->id) {
            $this->otherUserTyping = true;
        }
    }

    public function markMessagesAsRead($userId)
    {
        ChatMessage::where('sender_id', $userId)
            ->where('receiver_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function handleUserStoppedTyping($data)
    {
        if ($data['receiverId'] == auth()->id() && $data['userId'] == $this->selectedUser->id) {
            $this->otherUserTyping = false;
        }
    }

    public function mount()
    {
        $this->loadMessages();

        
    }

    public function updatedSearchTerm()
    {
        $this->searchResults = User::where('name', 'like', '%' . $this->searchTerm . '%')
            ->where('id', '!=', Auth::id())
            ->get();
    }

    public function startTyping()
    {
        $this->isTyping = true;
        $this->dispatch('userTyping', ['userId' => auth()->id(), 'receiverId' => $this->selectedUser->id]);
    }

    public function stopTyping()
    {
        $this->isTyping = false;
        $this->dispatch('userStoppedTyping', ['userId' => auth()->id(), 'receiverId' => $this->selectedUser->id]);
    }

    public function selectUser($userId)
    {
        $this->selectedUser = User::find($userId);
        $this->view = 'chat';
        $this->loadMessages();
        $this->markMessagesAsRead($userId);
    }

    public function getUnreadCount($userId)
    {
        return ChatMessage::where('sender_id', $userId)
            ->where('receiver_id', auth()->id())
            ->whereNull('read_at')
            ->count();
    }

    public function loadMessages()
    {
        if ($this->selectedUser) {
            $this->messages = ChatMessage::where(function ($query) {
                $query->where('sender_id', auth()->id())
                    ->where('receiver_id', $this->selectedUser->id);
            })->orWhere(function ($query) {
                $query->where('sender_id', $this->selectedUser->id)
                    ->where('receiver_id', auth()->id());
            })->with('sender') // Load the sender relationship
                ->orderBy('created_at', 'asc')
                ->get()
                ->toArray(); // Convert collection to array
        } else {
            $this->messages = []; // Ensure it's an array if no user is selected
        }
    }

    public function sendMessage()
    {
        $this->validate([
            'message' => 'required|string|max:1000',
        ]);

        ChatMessage::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $this->selectedUser->id,
            'message' => $this->message,
        ]);

        $this->message = '';
        $this->loadMessages();
        $this->dispatch('messageSent');
    }

    public function backToInbox()
    {
        $this->selectedUser = null;
        $this->view = 'inbox';
    }

    public function render()
    {
        // Get inbox users with unread counts
        $inboxUsers = User::whereHas('sentMessages', function ($query) {
            $query->where('receiver_id', Auth::id());
        })->orWhereHas('receivedMessages', function ($query) {
            $query->where('sender_id', Auth::id());
        })->with(['latestMessage' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])->get();

        // Filter recent and all messages
        $recentMessages = $inboxUsers->filter(function ($user) {
            return $user->latestMessage && $user->latestMessage->created_at && $user->latestMessage->created_at->gt(now()->subMinutes(4));
        });

        $allMessages = $inboxUsers->filter(function ($user) {
            return $user->latestMessage;
        });

        // Add unread count to each user
        $inboxUsers->each(function ($user) {
            $user->color = $this->getRandomColor($user->id);
            $user->unread_count = $this->getUnreadCount($user->id);
        });

        return view('livewire.message-manager', [
            'recentMessages' => $recentMessages,
            'allMessages' => $allMessages,
        ]);
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


    // Helper function to generate a consistent color based on user ID
    public function getRandomColor($userId)
    {
        $colors = ['#FF6B6B', '#4ECDC4', '#45B7D7', '#A4D555', '#D4A5A5', '#FFD166', '#06D6A0', '#118AB2'];
        $colorIndex = $userId % count($colors);
        return $colors[$colorIndex];
    }

    public function deleteMessage($messageId, $deleteForAll = false)
    {
        $message = ChatMessage::find($messageId);

        if ($message) {
            if ($deleteForAll && $message->sender_id == auth()->id()) {
                // Delete for both sender and receiver
                $message->delete();
            } elseif (!$deleteForAll && $message->receiver_id == auth()->id()) {
                // Delete only for the current user (receiver)
                $message->delete();
            } else {
                // If it's the sender deleting for themselves
                $message->delete();
            }
            $this->loadMessages();
        }
    }

    public function archiveMessage($messageId)
    {
        $message = ChatMessage::find($messageId);

        if ($message) {
            // Archive the message by setting archived_at to the current time
            $message->update(['archived_at' => now()]);
            $this->loadMessages();
        }
    }

    public function shareMessage($messageId)
    {
        $message = ChatMessage::find($messageId);

        if ($message) {
            // Implement the sharing logic here, like opening a modal or sending the message
            // For now, we will just return the message content
            session()->flash('shared_message', $message->message);
            $this->loadMessages();
        }
    }



    public function replyToMessage($messageId, $replyText)
    {
        $message = ChatMessage::find($messageId);

        if ($message) {
            // Create a new reply message
            ChatMessage::create([
                'message' => $replyText,
                'sender_id' => auth()->id(),
                'receiver_id' => $message->sender_id,
                'parent_id' => $messageId, // Associate it with the original message
            ]);
            $this->loadMessages();
        }
    }
}
