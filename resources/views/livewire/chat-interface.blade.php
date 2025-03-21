<div class="container mt-2 p-4" style="max-width: 800px;">
    <!-- Theme Selector -->
    <div>
        <label for="theme-selector">Select Theme:</label>
        <select wire:model.live="selectedTheme" id="theme-selector" class="form-control">
            <option value="light">Light</option>
            <option value="dark">Dark</option>
            <option value="blue">Blue</option>
        </select>
    </div>

    <!-- Chat Messages (Integrated into Parent Container) -->
    <div class="mt-4" style="background-color: {{ $selectedTheme === 'light' ? '#f8f9fa' : ($selectedTheme === 'dark' ? '#343a40' : '#e3f2fd') }}; border-radius: 8px; padding: 16px; height: 500px; overflow-y: auto; box-shadow: 0px 4px 10px rgba(0,0,0,0.1);">
        @foreach ($messages as $message)
            <div class="mb-4 d-flex {{ $message['sender_id'] === auth()->id() ? 'justify-content-end' : 'justify-content-start' }}">
                <div class="p-3" style="background-color: {{ $message['sender_id'] === auth()->id() ? '#d1e7dd' : '#e9ecef' }}; color: {{ $message['sender_id'] === auth()->id() ? '#0f5132' : '#495057' }}; border-radius: 8px;">
                    <div class="font-weight-bold small">
                        {{ $message['sender_id'] === auth()->id() ? 'You' : \App\User::find($message['sender_id'])->name }}
                    </div>
                    @if ($editingMessageId === $message['id'])
                        <textarea wire:model="editedMessage" rows="2" class="form-control mb-2"></textarea>
                        <button wire:click="updateMessage" class="btn btn-sm btn-success">Save</button>
                        <button wire:click="cancelEdit" class="btn btn-sm btn-secondary">Cancel</button>
                    @else
                        <div class="mt-1">{{ $message['message'] }}</div>
                        <div class="mt-2 text-right small text-muted">
                            {{ \Carbon\Carbon::parse($message['created_at'])->diffForHumans() }}
                        </div>
                        @if ($message['sender_id'] === auth()->id())
                            <div class="mt-2 d-flex gap-2">
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-cog"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" wire:click="editMessage({{ $message['id'] }})">Edit</a>
                                        <a class="dropdown-item" wire:click="deleteMessage({{ $message['id'] }})">Delete</a>
                                        <a class="dropdown-item" wire:click="archiveMessage({{ $message['id'] }})">Archive</a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        @endforeach

        <!-- Typing Indicator -->
        @if ($isOtherUserTyping)
            <div class="d-flex align-items-center mt-3 justify-content-start">
                <div class="text-muted small">
                    <span>{{ $otherUserName }} is typing...</span>
                    <div class="spinner-grow spinner-grow-sm text-secondary mx-1" role="status"></div>
                    <div class="spinner-grow spinner-grow-sm text-secondary mx-1" role="status"></div>
                    <div class="spinner-grow spinner-grow-sm text-secondary mx-1" role="status"></div>
                </div>
            </div>
        @endif
    </div>

    <!-- Message Input (Integrated into Parent Container) -->
    <div class="mt-4 d-flex align-items-start gap-2">
        <textarea wire:model.defer="message" rows="2" class="form-control" placeholder="Type your message here..."></textarea>
        <button wire:click="sendMessage" class="btn btn-primary">
            Send
        </button>
    </div>

    <!-- Reaction Suggestions (Integrated into Parent Container) -->
    <div class="mt-3 text-center">
        <button class="btn btn-light mx-1">😊</button>
        <button class="btn btn-light mx-1">👍</button>
        <button class="btn btn-light mx-1">❤️</button>
        <button class="btn btn-light mx-1">🎉</button>
    </div>
</div>

