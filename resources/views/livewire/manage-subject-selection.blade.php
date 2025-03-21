<div x-data="{ 
    activeAction: @entangle('activeAction').live,
    showHelpPanel: false,
    showInfoBanner: true
}" class="bg-white rounded-lg shadow-sm overflow-hidden">

    <!-- Info Banner -->
    <div x-show="showInfoBanner" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-2"
         class="bg-gradient-to-r from-blue-50 via-blue-50 to-indigo-50 px-6 py-4 border-b border-blue-200">
        <div class="flex items-start">
            <div class="flex-shrink-0 pt-0.5">
                <svg class="h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3 flex-1 md:flex md:justify-between">
                <p class="text-sm text-blue-700">
                    Select which classes will participate in subject selection. Students in selected classes will be able to choose their subjects.
                </p>
                <p class="mt-3 text-sm md:mt-0 md:ml-6">
                    <button @click="showHelpPanel = !showHelpPanel" class="whitespace-nowrap font-medium text-blue-700 hover:text-blue-600">
                        Learn more <span aria-hidden="true">&rarr;</span>
                    </button>
                </p>
            </div>
            <div class="ml-4 flex-shrink-0">
                <button @click="showInfoBanner = false" class="inline-flex text-blue-500 hover:text-blue-600">
                    <span class="sr-only">Dismiss</span>
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Help Panel -->
    <div x-show="showHelpPanel" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="bg-gray-50 px-6 py-5 border-b border-gray-200">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-base font-medium text-gray-800">Subject Selection Process</h3>
                <div class="mt-2 text-sm text-gray-600 space-y-1">
                    <p>The subject selection process involves three main steps:</p>
                    <ol class="list-decimal pl-5 space-y-1 mt-2">
                        <li>Select which classes will participate in subject selection</li>
                        <li>Set a deadline for students to submit their choices</li>
                        <li>Students select their preferred subjects before the deadline</li>
                    </ol>
                    <p class="mt-2">After the deadline passes, administrators can review and approve student selections.</p>
                </div>
                <div class="mt-4">
                    <button @click="showHelpPanel = false" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                        Close help
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Header Section -->
    <div class="px-6 py-5 border-b border-gray-200 bg-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="flex items-center mb-4 md:mb-0">
                <h2 class="text-xl font-bold text-gray-900">Class Selection</h2>
                <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    Management
                </span>
            </div>
            
            <div class="flex flex-col sm:flex-row sm:space-x-3 space-y-3 sm:space-y-0">
                <!-- Set Deadline Button -->
                <button wire:click="$set('activeAction', 'selectDeadline')" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Set Deadline
                </button>
                
                <!-- Select All Button -->
                <button wire:click="selectAll" 
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Select All
                </button>
            </div>
        </div>
    </div>

    <!-- Deadline Component (if active) -->
    @if ($activeAction === 'selectDeadline')
        <div class="bg-white border-l-4 border-blue-500 p-5 m-6 rounded shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Set Subject Selection Deadline</h3>
                    <div class="mt-2">
            @livewire('subject-selection-deadline')
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Search and Content Section -->
    <div class="px-6 py-5">
        <!-- Search and Stats -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div class="max-w-lg mb-4 md:mb-0">
                <div class="relative flex items-center">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" 
                           wire:model.live="search" 
                           id="search" 
                           class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md"
                           placeholder="Search classes...">
                </div>
            </div>
            
            <div class="flex space-x-4">
                <div class="bg-blue-50 rounded-lg px-4 py-2 flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <div class="text-xs text-blue-500 uppercase font-semibold">Total Classes</div>
                        <div class="text-lg font-bold text-blue-700">{{ count($filteredClasses) }}</div>
                    </div>
                </div>
                
                <div class="bg-green-50 rounded-lg px-4 py-2 flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <div class="text-xs text-green-500 uppercase font-semibold">Selected</div>
                        <div class="text-lg font-bold text-green-700">{{ collect($settings)->filter(function($value) { return $value; })->count() }}</div>
                    </div>
                </div>
        </div>
    </div>

    <!-- Classes Table -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
                <tbody class="divide-y divide-gray-200">
                @forelse ($filteredClasses as $class)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full bg-blue-100 text-blue-700 font-bold text-sm">
                                        {{ strtoupper(substr($class->name, 0, 2)) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $class->name }}</div>
                                        <div class="text-xs text-gray-500">ID: {{ $class->id }}</div>
                                    </div>
                                </div>
                        </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                            @if ($settings[$class->id])
                                    <div class="flex items-center">
                                        <div class="h-2.5 w-2.5 rounded-full bg-green-500 mr-2"></div>
                                        <span class="text-sm text-gray-900">Selected for subject choice</span>
                                    </div>
                            @else
                                    <div class="flex items-center">
                                        <div class="h-2.5 w-2.5 rounded-full bg-gray-300 mr-2"></div>
                                        <span class="text-sm text-gray-500">Not selected</span>
                                    </div>
                            @endif
                        </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="flex items-center">
                                    <button wire:click="toggleSelection({{ $class->id }})" 
                                            class="{{ $settings[$class->id] 
                                                ? 'bg-red-50 text-red-700 hover:bg-red-100 focus:ring-red-500' 
                                                : 'bg-green-50 text-green-700 hover:bg-green-100 focus:ring-green-500' }} 
                                                inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2">
                            @if ($settings[$class->id])
                                            <svg class="-ml-0.5 mr-1.5 h-4 w-4 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                            </svg>
                                            Remove
                                        @else
                                            <svg class="-ml-0.5 mr-1.5 h-4 w-4 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            Select
                                        @endif
                                </button>
                                    
                                    <button class="ml-2 text-gray-400 hover:text-gray-500">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                        </svg>
                                </button>
                                </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                            <td colspan="3" class="px-6 py-10 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="h-12 w-12 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No Classes Found</h3>
                                    <p class="mt-1 text-sm text-gray-500">Try adjusting your search criteria or check back later.</p>
                                </div>
                            </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>
