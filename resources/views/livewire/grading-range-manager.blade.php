<div x-data="{ 
    instructionsVisible: false, 
    isEditing: @entangle('isEditing').live, 
    currentIndex: null, 
    showForm: @entangle('showForm').live, 
    isLoading: @entangle('isLoading').live,
    activeAction: @entangle('activeAction').live
}" class="bg-white rounded-lg shadow-sm overflow-hidden">

    <!-- Header Section -->
    <div class="border-b border-gray-200 px-6 py-4">
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-medium text-gray-900 flex items-center">
                <svg class="w-6 h-6 mr-2 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Manage Grading Ranges
            </h1>
            
            <!-- Refresh Button -->
            <button wire:click="refreshGradingSystems" 
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Refresh
                <span wire:loading wire:target="refreshGradingSystems" class="ml-2">
                    <svg class="animate-spin h-4 w-4 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
            </span>
        </button>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="px-6 py-4">
        <!-- Selection Controls -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <!-- Grading System Selection -->
            <div>
                <label for="grading-system" class="block text-sm font-medium text-gray-700 mb-1">Select Grading System</label>
                <select wire:model.live="selectedGradingSystem" 
                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" 
                        id="grading-system">
                <option value="">Select a Grading System</option>
                @foreach ($gradingSystems as $gradingSystem)
                    <option value="{{ $gradingSystem->id }}" wire:key="grading-system-{{ $gradingSystem->id }}">
                        {{ $gradingSystem->name }}
                    </option>
                @endforeach
            </select>
        </div>

            <!-- Subject Selection (only shown when grading system is selected) -->
        @if ($selectedGradingSystem)
                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Select Subject</label>
                    <select wire:model.live="subjectId" 
                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" 
                            id="subject">
                    <option value="">Select a Subject</option>
                    @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                    @endforeach
                </select>
            </div>
        @endif
    </div>

    @if ($subjectId && $selectedGradingSystem)
        @if (!$hasAssignedRanges)
                <!-- New Ranges Assignment Section -->
                @include('livewire.partials.grading-range.new-ranges-assignment')
                @endif

            @if ($hasAssignedRanges)
                <!-- Existing Ranges Management Section -->
                @include('livewire.partials.grading-range.existing-ranges-management')
                @endif
        @endif
            </div>
</div>
