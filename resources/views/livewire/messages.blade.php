<div wire:ignore.self wire:poll.5000ms="updateUnreadCount">
    <!-- Messages Icon -->
    <a href="#" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false" wire:click.prevent="toggleDropdown">
        <i class="fas fa-envelope"></i>
        @if($unreadMessagesCount > 0)
            <span class="badge badge-pill bg-danger">{{ $unreadMessagesCount }}</span>
        @endif
    </a>

    <!-- Messages Dropdown -->
    <div class="dropdown-menu dropdown-menu-right p-0" style="width: 350px; max-height: 400px; overflow-y: auto;" wire:ignore.self>
        <!-- Dropdown Header -->
        <div class="dropdown-header bg-primary text-white p-3 sticky-top" style="z-index: 1;">
            <span class="font-weight-bold">Messages</span>
        </div>
        <div class="dropdown-divider"></div>

        <!-- Message Items -->
        <div style="margin-top: 60px; margin-bottom: 60px;">
            @forelse($recentMessages as $message)
                <div class="dropdown-item d-flex align-items-center p-3" style="background: {{ $message->read_at ? '#fff' : '#e3f2fd' }}; text-decoration: none; color: inherit;">
                    @if(!$message->sender->photo)
                        <div class="rounded-circle mr-3 d-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px; background-color: {{ $this->getRandomColor($message->sender->id) }}; color: {{ $message->sender->id === auth()->id() ? '#000000' : '#FFFFFF' }};">
                            <span class="text-white font-weight-bold">
                                {{ $this->getInitials($message->sender->name) }}
                            </span>
                        </div>
                    @else
                        <img src="{{ $message->sender->photo }}" alt="{{ $message->sender->name }}" class="rounded-circle mr-3" style="width: 40px; height: 40px;">
                    @endif
                    <div class="flex-grow-1" style="min-width: 0;">
                        <strong>{{ $message->sender->name }}:</strong>
                        <p class="mb-0 text-truncate" style="max-width: 250px;" title="{{ $message->message }}">
                            {{ $message->message }}
                        </p>
                        <small class="text-muted">{{ $message->created_at->diffForHumans() }}</small>
                        @if($message->created_at->diffInHours() < 24 && !$message->read_at)
                            <span class="badge badge-pill bg-success ml-2">New</span>
                        @endif
                    </div>
                    @if(!$message->read_at)
                        <button class="btn btn-sm btn-outline-primary ml-auto" wire:click.prevent="markAsRead('{{ $message->id }}')">
                            Mark as Read
                        </button>
                    @endif
                </div>
            @empty
                <div class="dropdown-item text-center py-3">
                    <i class="fas fa-envelope-open fa-2x text-muted mb-2"></i>
                    <p class="text-muted mb-0">No new messages</p>
                </div>
            @endforelse
        </div>

        <!-- View All Messages Link -->
        <div class="sticky-bottom bg-light" style="position: sticky; bottom: 0; z-index: 1;">
            <div class="dropdown-divider"></div>
            <a href="{{ route('messages.index') }}" class="dropdown-item text-center py-2">
                <i class="fas fa-list me-2"></i> View all messages
            </a>
        </div>
    </div>
</div>
