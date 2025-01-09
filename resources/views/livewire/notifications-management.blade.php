<div class="container">
    <!-- Search Bar -->
    <div class="mb-4">
        <input
            type="text"
            wire:model.live="search"
            placeholder="Search notifications..."
            class="form-control rounded-pill shadow-sm w-100"
            style="border: 1px solid #ddd; padding: 10px 20px;"
        />
    </div>

    <!-- Bulk Actions -->
    <div class="mb-4 d-flex flex-column flex-md-row gap-2">
        <button wire:click="markAllAsRead" class="btn btn-sm btn-outline-success rounded-pill shadow-sm flex-fill">
            <i class="fas fa-envelope-open me-2"></i> Mark All as Read
        </button>
        <button wire:click="markAllAsUnread" class="btn btn-sm btn-outline-warning rounded-pill shadow-sm flex-fill">
            <i class="fas fa-envelope me-2"></i> Mark All as Unread
        </button>
        <button wire:click="deleteSelectedNotifications" class="btn btn-sm btn-outline-danger rounded-pill shadow-sm flex-fill">
            <i class="fas fa-trash me-2"></i> Delete Selected
        </button>
    </div>

    <!-- Notifications List -->
    @if (!$showDetails)
        @if ($notifications->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                <p class="text-muted">No notifications found.</p>
            </div>
        @else
            <ul class="list-group list-group-flush">
                @foreach ($notifications as $notification)
                    <li class="list-group-item d-flex flex-column flex-md-row justify-content-between align-items-start p-3 mb-2 shadow-sm"
                        style="border-radius: 10px; background: {{ $notification->read_at ? '#fff' : '#e3f2fd' }}; transition: transform 0.2s ease; cursor: pointer;"
                        wire:click="showDetails('{{ $notification->id }}')">
                        <div class="d-flex align-items-center w-100">
                            <!-- Checkbox for Bulk Selection -->
                            <input
                                type="checkbox"
                                wire:model="selectedNotifications"
                                value="{{ $notification->id }}"
                                class="form-check-input me-3"
                                onclick="event.stopPropagation()"
                            >
                            <i class="fas fa-check-circle text-success me-3"></i>
                            <div class="flex-grow-1">
                                <p class="mb-0 text-dark">{{ $notification->data['message'] }}</p>
                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-3 mt-md-0 ms-md-3">
                            <!-- Mark as Read/Unread -->
                            @if($notification->read_at)
                                <button
                                    wire:click="markAsUnread('{{ $notification->id }}')"
                                    class="btn btn-sm btn-outline-warning rounded-circle shadow-sm"
                                    title="Mark as Unread"
                                    onclick="event.stopPropagation()"
                                >
                                    <i class="fas fa-envelope"></i>
                                </button>
                            @else
                                <button
                                    wire:click="markAsRead('{{ $notification->id }}')"
                                    class="btn btn-sm btn-outline-success rounded-circle shadow-sm"
                                    title="Mark as Read"
                                    onclick="event.stopPropagation()"
                                >
                                    <i class="fas fa-envelope-open"></i>
                                </button>
                            @endif

                            <!-- Delete Notification -->
                            <button
                                wire:click="deleteNotification('{{ $notification->id }}')"
                                class="btn btn-sm btn-outline-danger rounded-circle shadow-sm"
                                title="Delete"
                                onclick="confirm('Are you sure you want to delete this notification?') || event.stopImmediatePropagation()"
                            >
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </li>
                @endforeach
            </ul>
            <div class="mt-4 d-flex justify-content-center">
                {{ $notifications->links() }} <!-- Pagination links -->
            </div>
        @endif
    @else
        <!-- Notification Details Card -->
        <div class="card shadow-sm border-0 rounded w-100">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Notification Details</h5>
                <button wire:click="closeDetails" class="btn btn-sm btn-light rounded-circle shadow-sm">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="card-body">
                <p class="mb-2"><strong>Message:</strong> {{ $selectedNotification->data['message'] }}</p>
                <p class="mb-2"><strong>Date:</strong> {{ $selectedNotification->created_at->diffForHumans() }}</p>
                <p class="mb-0"><strong>Read Status:</strong> {{ $selectedNotification->read_at ? 'Read' : 'Unread' }}</p>
            </div>
        </div>
    @endif

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="alert alert-success mt-4 shadow-sm">
            {{ session('success') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger mt-4 shadow-sm">
            {{ session('error') }}
        </div>
    @endif
</div>