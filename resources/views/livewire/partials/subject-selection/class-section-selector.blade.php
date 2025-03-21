<!-- Class and Section Selection Card -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
    <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
        <h3 class="text-base font-medium text-gray-900 flex items-center">
            <svg class="w-5 h-5 text-blue-600 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            Class and Section Selection
            @if($selectedClass)
            <button 
                type="button" 
                class="ml-auto text-xs bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-1 px-2 rounded"
                onclick="console.log('Debug - Selected Class:', @js($selectedClass), 'Sections:', @js($sections))">
                Debug
            </button>
            @endif
        </h3>
    </div>
    
    <div class="p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Class Selection -->
            <div>
                <label for="class" class="block text-sm font-medium text-gray-700 mb-1">Select Class</label>
                <div class="relative">
                    <select id="class" 
                            wire:model.live="selectedClass" 
                            class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">-- Select Class --</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                @if($selectedClass)
                <div class="mt-2">
                    <button 
                        type="button" 
                        wire:click="loadSections" 
                        class="text-xs bg-blue-50 hover:bg-blue-100 text-blue-600 font-medium py-1 px-2 rounded inline-flex items-center">
                        <svg class="w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Refresh Sections
                    </button>
                </div>
                @endif
            </div>

            <!-- Section Selection (only shown when class is selected) -->
            <div wire:key="section-selector-{{ $selectedClass ?? 'none' }}">
                @if($selectedClass)
                    <label for="section" class="block text-sm font-medium text-gray-700 mb-1">Select Section</label>
                    <div class="relative">
                        <select id="section" 
                                wire:model.live="selectedSection" 
                                class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="">-- Select Section --</option>
                            @forelse($sections as $section)
                                <option value="{{ $section->id ?? $section['id'] ?? '' }}">
                                    {{ $section->name ?? $section['name'] ?? 'Section '.($loop->index+1) }}
                                </option>
                            @empty
                                <option value="" disabled>No sections available</option>
                            @endforelse
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2 text-xs text-gray-500">
                        @if(is_countable($sections) && count($sections) == 0)
                            No sections available for this class
                        @else
                            {{ is_countable($sections) ? count($sections) : 'Unknown number of' }} section(s) available
                        @endif
                    </div>
                @else
                    <div class="flex items-center h-full justify-center text-sm text-gray-500">
                        <svg class="mr-2 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Please select a class first
                    </div>
                @endif
            </div>
        </div>
    </div>
</div> 