<div wire:ignore.self>
    <!-- Notification Bell Icon -->
    <a href="#" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-bell"></i>
        @if($unreadCount > 0)
            <span class="badge badge-pill bg-danger">{{ $unreadCount }}</span>
        @endif
    </a>

    <!-- Notification Dropdown -->
    <div class="dropdown-menu dropdown-menu-right p-0" style="width: 350px; max-height: 400px; overflow-y: auto;" wire:ignore.self wire:poll.5000ms="loadNotifications">
        <!-- Dropdown Header -->
        <div class="dropdown-header bg-primary text-white p-3 sticky-top" style="z-index: 1;">
            <span class="font-weight-bold">Notifications</span>
        </div>
        <div class="dropdown-divider"></div>

        <!-- Notification Items -->
        <div style="margin-top: 60px; margin-bottom: 60px;"> <!-- Add margin to account for sticky header and footer -->
            @forelse($notifications as $notification)
                <a href="{{ route('notifications.index') }}" class="dropdown-item d-flex align-items-center p-3" style="background: {{ $notification->read_at ? '#fff' : '#e3f2fd' }}; text-decoration: none; color: inherit;">
                    <i class="fas fa-check-circle text-success me-3"></i>
                    <div class="flex-grow-1" style="min-width: 0;">
                        <!-- Truncated Message with Ellipsis -->
                        <p class="mb-0 text-truncate" style="max-width: 250px;" title="{{ $notification->data['message'] }}">
                            {{ $notification->data['message'] }}
                        </p>
                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                    </div>
                    @if(!$notification->read_at)
                        <button class="btn btn-sm btn-outline-primary ms-auto" wire:click.prevent="markAsRead('{{ $notification->id }}')" onclick="event.stopPropagation()">
                            Mark as Read
                        </button>
                    @endif
                </a>
            @empty
                <div class="dropdown-item text-center py-3">
                    <i class="fas fa-bell-slash fa-2x text-muted mb-2"></i>
                    <p class="text-muted mb-0">No new notifications</p>
                </div>
            @endforelse
        </div>

        <!-- View All Notifications Link -->
        <div class="sticky-bottom bg-light" style="position: sticky; bottom: 0; z-index: 1;">
            <div class="dropdown-divider"></div>
            <a href="{{ route('notifications.index') }}" class="dropdown-item text-center py-2">
                <i class="fas fa-list me-2"></i> View all notifications
            </a>
        </div>
    </div>
</div>