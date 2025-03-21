<!-- Top Navigation Bar -->
<header class="fixed right-0 top-0 md:left-64 left-0 bg-white h-16 z-30 shadow-sm">
    <div class="h-full px-4 flex items-center justify-between">
        <!-- Left side -->
        <div class="flex items-center">
            <!-- Mobile menu button -->
            <button @click="sidebarOpen = true" type="button" class="md:hidden text-gray-600 hover:text-gray-900 focus:outline-none">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <!-- Search -->
            <div class="ml-4">
                @livewire('intelligent-search')
            </div>
        </div>

        <!-- Right side -->
        <div class="flex items-center space-x-4">
            <!-- Notifications -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="p-1 text-gray-600 hover:text-gray-900 focus:outline-none">
                    <span class="sr-only">View notifications</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    @if(Auth::user()->unreadNotifications && Auth::user()->unreadNotifications->count() > 0)
                        <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500"></span>
                    @endif
                </button>
                
                <!-- Notifications dropdown -->
                <div x-show="open" 
                     @click.away="open = false"
                     class="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                     x-cloak>
                    @livewire('notifications')
                </div>
            </div>

            <!-- Messages -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="p-1 text-gray-600 hover:text-gray-900 focus:outline-none">
                    <span class="sr-only">View messages</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    @if(Auth::user()->unreadMessages && Auth::user()->unreadMessages->count() > 0)
                        <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500"></span>
                    @endif
                </button>
                
                <!-- Messages dropdown -->
                <div x-show="open" 
                     @click.away="open = false"
                     class="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                     x-cloak>
                    @livewire('messages')
                </div>
            </div>

            <!-- Profile dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center space-x-2 text-sm focus:outline-none">
                    <img class="h-8 w-8 rounded-full object-cover" 
                         src="{{ Auth::user()->photo ? asset('storage/'.Auth::user()->photo) : asset('global_assets/images/user.png') }}" 
                         alt="{{ Auth::user()->name }}">
                    <span class="hidden md:block text-gray-700">{{ Auth::user()->name }}</span>
                    <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>

                <!-- Profile dropdown panel -->
                <div x-show="open" 
                     @click.away="open = false"
                     class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                     x-cloak>
                    <div class="py-1">
                        <a href="{{ route('my_account') }}" 
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            My Account
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" 
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Sign out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Spacer to prevent content from hiding under fixed header -->
<div class="h-16"></div>
