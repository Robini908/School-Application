<!-- Filters Section -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Class Selection -->
        <div>
            <label for="class" class="block text-sm font-medium text-gray-700 mb-1">Class</label>
            <div class="relative">
                <select wire:model.live="selectedClass" id="class" 
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#217346] focus:ring focus:ring-[#217346] focus:ring-opacity-50">
                    <option value="">Select Class</option>
                    @foreach ($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
                <div wire:loading wire:target="selectedClass" 
                    class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>
        </div>

        @if($selectedClass)
            <!-- Search Exams -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search Exams</label>
                <div class="relative rounded-md shadow-sm">
                    <input type="text" wire:model.live="search" id="search"
                        class="block w-full rounded-md border-gray-300 focus:border-[#217346] focus:ring focus:ring-[#217346] focus:ring-opacity-50 pl-10"
                        placeholder="Search by exam name...">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Term Selection -->
            <div>
                <label for="term" class="block text-sm font-medium text-gray-700 mb-1">Term</label>
                <select wire:model.live="selectedTerm" id="term" 
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#217346] focus:ring focus:ring-[#217346] focus:ring-opacity-50">
                    <option value="">All Terms</option>
                    <option value="1">Term 1</option>
                    <option value="2">Term 2</option>
                    <option value="3">Term 3</option>
                </select>
            </div>

            <!-- Year Selection -->
            <div>
                <label for="year" class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                <select wire:model.live="selectedYear" id="year" 
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#217346] focus:ring focus:ring-[#217346] focus:ring-opacity-50">
                    <option value="">All Years</option>
                    @foreach (range(date('Y') - 5, date('Y')) as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>
        @endif
    </div>

    <!-- Applied Filters -->
    @if($selectedClass || !empty($selectedExams))
        <div class="mt-4 flex flex-wrap items-center gap-2">
            @if($selectedClass)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-[#E2EFDA] text-[#217346]">
                    Class: {{ optional($classes->firstWhere('id', $selectedClass))->name }}
                    <button wire:click="resetFilter('selectedClass')" class="ml-2 focus:outline-none">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </span>
            @endif

            @if($selectedTerm)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-[#E2EFDA] text-[#217346]">
                    Term: {{ $selectedTerm }}
                    <button wire:click="resetFilter('selectedTerm')" class="ml-2 focus:outline-none">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </span>
            @endif

            @if($selectedYear)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-[#E2EFDA] text-[#217346]">
                    Year: {{ $selectedYear }}
                    <button wire:click="resetFilter('selectedYear')" class="ml-2 focus:outline-none">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </span>
            @endif

            <button wire:click="resetFields" 
                class="inline-flex items-center px-3 py-1 rounded-md text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Reset All
            </button>
        </div>
    @endif
</div> 