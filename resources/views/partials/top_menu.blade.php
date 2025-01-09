<div class="navbar navbar-expand-md navbar-dark bg-dark">
    <div class="mt-2 mr-5">
        <a href="{{ route('dashboard') }}">
            <h4 class="text-bold text-white">MBUKU ERP-1.0</h4>
        </a>
    </div>

    <div class="d-md-none">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
            <i class="fas fa-bars"></i> <!-- Font Awesome Bars Icon -->
        </button>
        <button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
            <i class="fas fa-align-justify"></i> <!-- Font Awesome Align Justify Icon -->
        </button>
    </div>

    <div class="collapse navbar-collapse" id="navbar-mobile">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
                    <i class="fas fa-align-justify"></i> <!-- Font Awesome Align Justify Icon -->
                </a>
            </li>
        </ul>

        <!-- Include the Livewire search next to menu icons -->
        <div class="navbar-text ml-md-3 mr-md-auto d-flex align-items-center" style="max-width: 400px; width: 100%;">
            @livewire('intelligent-search')
        </div>

        <ul class="navbar-nav">
            <!-- Livewire Notification Component -->
            <li class="nav-item dropdown">
                @livewire('notifications') <!-- Include the Livewire Notifications component -->
            </li>

            <!-- User Dropdown -->
            <li class="nav-item dropdown dropdown-user">
                <a href="#" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown">
                    @if(Auth::check())
                        <span>Welcome - {{ Auth::user()->username }}</span>
                    @else
                        <span>Welcome - Guest</span>
                    @endif
                </a>

                <div class="dropdown-menu dropdown-menu-right">
                    @if(Auth::check())
                        @php
                            $studentRecordId = Qs::userIsStudent() ? Qs::findStudentRecord(Auth::user()->id)->id : null;
                        @endphp
                        <a href="{{ Qs::userIsStudent() ? route('students.show', Qs::hash($studentRecordId)) : route('users.show', Qs::hash(Auth::user()->id)) }}" class="dropdown-item">
                            <i class="fas fa-user-plus me-2"></i> My profile
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('my_account') }}" class="dropdown-item">
                            <i class="fas fa-cog me-2"></i> Account settings
                        </a>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-item">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @endif
                </div>
            </li>
        </ul>
    </div>
</div>