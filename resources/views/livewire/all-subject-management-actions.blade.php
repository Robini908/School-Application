<div x-data="{ 
    activeAction: @entangle('activeAction').live,
    searchFocused: false,
    showNotifications: false,
    notifications: @entangle('notifications').live
}" class="min-h-screen">
    
    <!-- Google-style Material Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <!-- Title and Navigation -->
            <div class="flex items-center">
                <div class="bg-white/10 backdrop-blur-sm rounded-full p-2 mr-3">
                    <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
<div>
                    <h2 class="text-xl font-medium text-white">
                        {{ $activeAction ? ($activeAction === 'reuseSame' ? 'Choose Classes' : 
                           ($activeAction === 'suggest' ? 'Subject Selection' : 
                           ($activeAction === 'reuseDifferent' ? 'Manage Selection' : 'Subject Management'))) : 'Subject Management' }}
                    </h2>
                    <p class="mt-1 text-sm text-white/80">
                        {{ $activeAction ? ($activeAction === 'reuseSame' ? 'Assign subjects to classes' : 
                           ($activeAction === 'suggest' ? 'Manage student choices' : 
                           ($activeAction === 'reuseDifferent' ? 'Review selections' : 'Manage subjects and assignments'))) : 'Manage subjects and assignments' }}
                    </p>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex items-center gap-3">
                <!-- Search Bar -->
                <div class="relative" @click.away="searchFocused = false">
                    <input type="text" 
                           wire:model.live="searchTerm"
                           @focus="searchFocused = true"
                           placeholder="Search..." 
                           class="w-full md:w-64 bg-white/10 backdrop-blur-sm focus:bg-white text-sm text-white focus:text-gray-900 placeholder-white/70 focus:placeholder-gray-500 rounded-full py-2 pl-10 pr-4 outline-none transition-all duration-200"
                           :class="{'ring-2 ring-white/30': searchFocused}">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-white/70" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="flex items-center gap-2">
                    <a href="{{ route('dashboard') }}" 
                       class="inline-flex items-center px-3 py-1.5 rounded-full text-white bg-white/10 hover:bg-white/20 transition-colors">
                        <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>
                    
                @if (!$activeAction)
                    <div class="relative" x-data="{ open: false }">
                            <button 
                                @click="open = !open"
                                @click.away="open = false"
                                class="inline-flex items-center px-4 py-1.5 rounded-full text-white bg-white/10 hover:bg-white/20 transition-colors">
                                <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Actions
                        </button>
                        
                            <!-- Dropdown Menu -->
                        <div x-show="open" 
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none z-50">
                            <div class="py-1">
                                    <button wire:click="setAction('reuseSame')" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 w-full text-left">
                                        <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                                        </svg>
                                        Assign to Classes
                                </button>
                                    <button wire:click="setAction('suggest')" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 w-full text-left">
                                        <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                        </svg>
                                        Subject Selection
                                </button>
                                    <button wire:click="setAction('reuseDifferent')" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 w-full text-left">
                                        <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        Manage Selection
                                </button>
                            </div>
                        </div>
                    </div>
                @else
                    <button wire:click="$set('activeAction', null)" 
                                class="inline-flex items-center px-3 py-1.5 rounded-full text-white bg-white/10 hover:bg-white/20 transition-colors">
                            <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back
                    </button>
                @endif
            </div>
        </div>
    </div>
    </div>

    <!-- Stats Summary Bar -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-3 grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Total Subjects -->
                <x-stats-card 
                    title="Total Subjects"
                    :value="$totalSubjects"
                    :change="$subjectChange"
                    icon-class="text-blue-600"
                    bg-class="bg-blue-50" />
                
                <!-- Active Classes -->
                <x-stats-card 
                    title="Active Classes"
                    :value="$activeClasses"
                    icon-class="text-green-600"
                    bg-class="bg-green-50" />
                
                <!-- Students with Selections -->
                <x-stats-card 
                    title="Students with Selections"
                    :value="$studentsWithSelections"
                    :change="$studentChange"
                    icon-class="text-amber-600"
                    bg-class="bg-amber-50" />
            </div>
        </div>
        </div>

    <!-- Main Content Area -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            @if ($activeAction === 'reuseSame')
                <livewire:manage-subject-selection lazy />
            @elseif($activeAction === 'suggest')
                <livewire:subject-selection-component lazy />
            @elseif($activeAction === 'reuseDifferent')
                <livewire:manage-student-subjects lazy />
            @else
                <livewire:manage-subjectss lazy />
            @endif
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>
