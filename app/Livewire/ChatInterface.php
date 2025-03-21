<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ChatMessage;
use App\User;
use Illuminate\Support\Facades\Auth;

class ChatInterface extends Component
{
    public $selectedTeacher;
    public $message;
    public $messages = [];
    public $selectedStudent;
    public $classTeacher;
    public $isOtherUserTyping = false; // Tracks if the other user is typing
    public $otherUserName = ''; // The name of the typing user
    public $editingMessageId = null; // Track the message being edited
    public $editedMessage = ''; // Track the edited message content
    public $selectedTheme = 'light'; // Default theme

    public function mount($selectedStudent, $classTeacher)
    {
        $this->selectedStudent = $selectedStudent;
        $this->classTeacher = $classTeacher;
        $this->selectedTeacher = $this->classTeacher;
        $this->loadMessages();
    }

    public function loadMessages()
    {
        if ($this->selectedTeacher) {
            $this->messages = ChatMessage::where(function ($query) {
                $query->where('sender_id', auth()->id())
                    ->where('receiver_id', $this->selectedTeacher->id);
            })->orWhere(function ($query) {
                $query->where('sender_id', $this->selectedTeacher->id)
                    ->where('receiver_id', auth()->id());
            })->orderBy('created_at', 'asc')->get()->toArray();
        } else {
            $this->messages = [];
        }
    }

    public function cancelEdit()
    {
        $this->editingMessageId = null;
        $this->editedMessage = '';
    }

    public function updatedMessage($value)
    {
        // Notify the other user you're typing
        $this->dispatch('userTyping', [
            'sender_id' => Auth::id(),
            'receiver_id' => $this->selectedTeacher->id,
        ]);
    }

    public function sendMessage()
    {
        $this->validate([
            'message' => 'required|string|max:1000',
        ]);

        ChatMessage::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $this->selectedTeacher->id,
            'message' => $this->message,
        ]);

        $this->message = '';
        $this->dispatch('stopTyping', [
            'sender_id' => Auth::id(),
            'receiver_id' => $this->selectedTeacher->id,
        ]);
        $this->loadMessages();
    }

    public function userTyping($data)
    {
        if ($data['receiver_id'] === Auth::id()) {
            $this->isOtherUserTyping = true;
            $this->otherUserName = User::find($data['sender_id'])->name;
        }
    }

    public function stopTyping($data)
    {
        if ($data['receiver_id'] === Auth::id()) {
            $this->isOtherUserTyping = false;
        }
    }

    public function editMessage($messageId)
    {
        $message = ChatMessage::find($messageId);
        if ($message && $message->sender_id === auth()->id()) {
            $this->editingMessageId = $messageId;
            $this->editedMessage = $message->message;
        }
    }

    public function updateMessage()
    {
        $this->validate([
            'editedMessage' => 'required|string|max:1000',
        ]);

        $message = ChatMessage::find($this->editingMessageId);
        if ($message && $message->sender_id === auth()->id()) {
            $message->update(['message' => $this->editedMessage]);
            $this->editingMessageId = null;
            $this->editedMessage = '';
            $this->loadMessages();
        }
    }

    public function deleteMessage($messageId)
    {
        $message = ChatMessage::find($messageId);
        if ($message && $message->sender_id === auth()->id()) {
            $message->delete();
            $this->loadMessages();
        }
    }

    public function archiveMessage($messageId)
    {
        $message = ChatMessage::find($messageId);
        if ($message && $message->sender_id === auth()->id()) {
            $message->update(['archived' => true]);
            $this->loadMessages();
        }
    }

    public function changeTheme($theme)
    {
        $this->selectedTheme = $theme;
    }

    public function render()
    {
        return view('livewire.chat-interface', [
            'messages' => $this->messages,
            'selectedTheme' => $this->selectedTheme,
        ]);
    }
}
