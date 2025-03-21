<div wire:ignore.self>
    <!-- Impersonation Icon (Only for Superadmin) -->
    @if (Auth::check() && Auth::user()->userType->title === 'super_admin')
        <a href="#" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-user-secret"></i> <!-- Font Awesome User Secret Icon -->
        </a>

        <!-- Impersonation Dropdown -->
        <div class="dropdown-menu dropdown-menu-right p-0" style="width: 350px; max-height: 400px; overflow-y: auto;" wire:ignore.self>
            <!-- Dropdown Header -->
            <div class="dropdown-header bg-primary text-white p-3 sticky-top" style="z-index: 1;">
                <span class="font-weight-bold">Impersonate User</span>
            </div>
            <div class="dropdown-divider"></div>

            <!-- Search Bar -->
            <div class="px-3 py-2 sticky-top">
                <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="Search users..." autofocus>
            </div>

            <!-- User List -->
            <div style="margin-top: 60px; margin-bottom: 60px;"> <!-- Add margin to account for sticky header and footer -->
                @if ($search && $users->count())
                    @foreach ($users as $user)
                        <a href="#" class="dropdown-item d-flex align-items-center p-3" wire:click.prevent="impersonate({{ $user->id }})" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-user-circle text-primary me-3"></i> <!-- Font Awesome User Circle Icon -->
                            <div class="flex-grow-1" style="min-width: 0;">
                                <p class="mb-0 text-truncate" style="max-width: 250px;" title="{{ $user->name }} ({{ $user->username }})">
                                    {{ $user->name }} ({{ $user->username }})
                                </p>
                                <small class="text-muted">{{ $user->email }}</small>
                            </div>
                        </a>
                    @endforeach
                @elseif ($search)
                    <div class="dropdown-item text-center py-3">
                        <i class="fas fa-user-slash fa-2x text-muted mb-2"></i> <!-- Font Awesome User Slash Icon -->
                        <p class="text-muted mb-0">No users found</p>
                    </div>
                @endif
            </div>

            <!-- Stop Impersonating Link (Only when impersonating) -->
            @if (session('impersonated_by'))
                <div class="sticky-bottom bg-light" style="position: sticky; bottom: 0; z-index: 1;">
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item text-center py-2 text-danger" wire:click.prevent="stopImpersonating">
                        <i class="fas fa-sign-out-alt me-2"></i> Stop Impersonating
                    </a>
                </div>
            @endif
        </div>
    @endif
</div>