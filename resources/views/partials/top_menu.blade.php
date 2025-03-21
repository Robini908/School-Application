<!-- Top Navigation -->
<nav class="bg-gray-800" x-data="{ 
    isUserMenuOpen: false,
    isNotificationsOpen: false,
    isMessagesOpen: false 
}">
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Left side -->
            <div class="flex items-center">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <h4 class="text-xl font-bold text-white">MBUKU ERP-1.0</h4>
        </a>
    </div>

                <!-- Sidebar toggle button (mobile) -->
                <button @click="isSidebarOpen = !isSidebarOpen" 
                        class="ml-4 px-2 py-1 text-gray-300 hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white md:hidden">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
        </button>
    </div>

            <!-- Center - Search -->
            <div class="flex-1 max-w-xl px-4">
                <div class="w-full">
            @livewire('intelligent-search')
        </div>
            </div>

            <!-- Right side -->
            <div class="flex items-center space-x-4">
                <!-- Notifications -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" 
                            class="p-1 text-gray-300 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white">
                        <span class="sr-only">View notifications</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </button>
                    
                    <div x-show="open" 
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
                        @livewire('notifications')
                    </div>
                </div>

                <!-- Messages -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                            class="p-1 text-gray-300 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white">
                        <span class="sr-only">View messages</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </button>
                    
                    <div x-show="open"
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
                        @livewire('messages')
                    </div>
                </div>

                <!-- Impersonate User -->
                <div class="relative">
                    @livewire('impersonate-user')
                </div>

                <!-- Profile dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                            class="flex items-center max-w-xs text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white"
                            id="user-menu-button">
                        <span class="sr-only">Open user menu</span>
                        <span class="text-white text-sm px-4 py-2 rounded-md hover:bg-gray-700">
                    @if(Auth::check())
                                Welcome - {{ Auth::user()->name }}
                    @else
                                Welcome - Guest
                    @endif
                        </span>
                    </button>

                    <div x-show="open"
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
                        
                    @if(Auth::check())
                        @php
                            $studentRecordId = Qs::userIsStudent() ? Qs::findStudentRecord(Auth::user()->id)->id : null;
                        @endphp
                            
                            <a href="{{ Qs::userIsStudent() ? route('students.show', Qs::hash($studentRecordId)) : route('users.show', Qs::hash(Auth::user()->id)) }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user-plus mr-2"></i> My profile
                            </a>
                            
                            <a href="{{ route('my_account') }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-cog mr-2"></i> Account settings
                            </a>
                            
                            <form method="POST" action="{{ route('logout') }}" class="block">
                            @csrf
                                <button type="submit"
                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </button>
                        </form>
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
