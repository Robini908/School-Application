<div x-data="{ filtersOpen: false }" class="bg-white rounded-lg shadow-sm mb-4">
    <div class="p-4 border-b flex justify-between items-center">
        <h3 class="text-lg font-medium text-gray-800">
            <i class="fas fa-filter text-blue-500 mr-2"></i> Student Filters
        </h3>
        <button @click="filtersOpen = !filtersOpen" class="text-blue-500 hover:text-blue-700 focus:outline-none transition-colors">
            <span x-show="!filtersOpen"><i class="fas fa-chevron-down"></i> Show Filters</span>
            <span x-show="filtersOpen"><i class="fas fa-chevron-up"></i> Hide Filters</span>
        </button>
    </div>
    
    <div x-show="filtersOpen" x-transition:enter="transition ease-out duration-200" 
         x-transition:enter-start="opacity-0 transform scale-95" 
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-100" 
         x-transition:leave-start="opacity-100 transform scale-100" 
         x-transition:leave-end="opacity-0 transform scale-95" 
         class="p-4">
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Form Filter -->
            <div>
                <label for="formFilter" class="block text-sm font-medium text-gray-700 mb-1">Form:</label>
                <div class="relative">
                    <select id="formFilter" wire:model.live="formFilter" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">All Forms</option>
                        @foreach ($forms as $form)
                            <option value="{{ $form }}">{{ $form }}</option>
                        @endforeach
                    </select>
                    <div wire:loading wire:target="formFilter" class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="animate-spin h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Stream Filter -->
            <div>
                <label for="sectionFilter" class="block text-sm font-medium text-gray-700 mb-1">Stream:</label>
                <div class="relative">
                    <select id="sectionFilter" wire:model.live="sectionFilter" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">All Streams</option>
                        @if ($formFilter)
                            @foreach ($sections as $section)
                                <option value="{{ $section }}">{{ $section }}</option>
                            @endforeach
                        @endif
                    </select>
                    <div wire:loading wire:target="sectionFilter" class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="animate-spin h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Status Filter -->
            <div>
                <label for="statusFilter" class="block text-sm font-medium text-gray-700 mb-1">Status:</label>
                <div class="relative">
                    <select id="statusFilter" wire:model.live="statusFilter" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">All Statuses</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                    <div wire:loading wire:target="statusFilter" class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="animate-spin h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Year Filter -->
            <div>
                <label for="transitionYearFilter" class="block text-sm font-medium text-gray-700 mb-1">Year:</label>
                <div class="relative">
                    <select id="transitionYearFilter" wire:model.live="transitionYearFilter" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">All Years</option>
                        @foreach ($transitionYears as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                    <div wire:loading wire:target="transitionYearFilter" class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="animate-spin h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Box -->
        <div class="mt-4">
            <div class="relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text" wire:model.live.debounce.300ms="searchTerm" class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="Search students by name, admission number, email...">
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <div wire:loading wire:target="searchTerm" class="animate-spin h-4 w-4 text-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Applied Filters -->
        <div class="mt-4 flex flex-wrap gap-2">
            @if($formFilter || $sectionFilter || $statusFilter || $transitionYearFilter)
                <div class="text-sm text-gray-600 mr-2 self-center">Applied filters:</div>
            @endif
            
            @if($formFilter)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    Form: {{ $formFilter }}
                    <button wire:click="removeFilter('formFilter')" class="ml-1.5 inline-flex text-blue-400 hover:text-blue-600 focus:outline-none">
                        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <div wire:loading wire:target="removeFilter('formFilter')" class="ml-1 animate-spin h-3 w-3 text-blue-500"></div>
                </span>
            @endif
            
            @if($sectionFilter)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    Stream: {{ $sectionFilter }}
                    <button wire:click="removeFilter('sectionFilter')" class="ml-1.5 inline-flex text-green-400 hover:text-green-600 focus:outline-none">
                        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <div wire:loading wire:target="removeFilter('sectionFilter')" class="ml-1 animate-spin h-3 w-3 text-green-500"></div>
                </span>
            @endif
            
            @if($statusFilter)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                    Status: {{ ucfirst($statusFilter) }}
                    <button wire:click="removeFilter('statusFilter')" class="ml-1.5 inline-flex text-purple-400 hover:text-purple-600 focus:outline-none">
                        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <div wire:loading wire:target="removeFilter('statusFilter')" class="ml-1 animate-spin h-3 w-3 text-purple-500"></div>
                </span>
            @endif
            
            @if($formFilter || $sectionFilter || $statusFilter || $transitionYearFilter)
                <button wire:click="resetFilters" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 hover:bg-red-200 transition-colors">
                    Reset All
                    <svg class="ml-1.5 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                    </svg>
                    <div wire:loading wire:target="resetFilters" class="ml-1 animate-spin h-3 w-3 text-red-500"></div>
                </button>
            @endif
        </div>
    </div>
</div> 