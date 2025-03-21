<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Class Selection -->
        <div class="space-y-2">
            <label for="class" class="block text-sm font-medium text-gray-700">Select Class</label>
            <div class="relative">
                <select wire:model.live="classId" id="class" 
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                    wire:key="class-selection">
                    <option value="">Select Class</option>
                    @foreach ($classes as $class)
                        <option value="{{ $class->id }}" wire:key="class-{{ $class->id }}">
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
                <div wire:loading wire:target="classId" class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-500"></div>
                </div>
            </div>
        </div>

        <!-- Exam Selection -->
        @if ($classId)
            <div class="space-y-2">
                <label for="exam" class="block text-sm font-medium text-gray-700">Select Exam</label>
                <div class="relative">
                    <select wire:model.live="examId" id="exam" 
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        wire:key="exam-selection">
                        <option value="">Select Exam</option>
                        @foreach ($exams as $exam)
                            <option value="{{ $exam->id }}" wire:key="exam-{{ $exam->id }}">
                                {{ $exam->name }}
                            </option>
                        @endforeach
                    </select>
                    <div wire:loading wire:target="examId" class="absolute inset-y-0 right-0 flex items-center pr-3">
                        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-500"></div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Section Selection -->
        @if ($examId)
            <div class="space-y-2">
                <label for="section" class="block text-sm font-medium text-gray-700">Select Section</label>
                <div class="relative">
                    <select wire:model.live="sectionId" id="section" 
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        wire:key="section-selection">
                        <option value="">Select Section</option>
                        @foreach ($sections as $section)
                            <option value="{{ $section->id }}" wire:key="section-{{ $section->id }}">
                                {{ $section->name }}
                            </option>
                        @endforeach
                    </select>
                    <div wire:loading wire:target="sectionId" class="absolute inset-y-0 right-0 flex items-center pr-3">
                        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-500"></div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Action Buttons -->
    @if ($sectionId)
        <div class="flex justify-end space-x-4">
            <button wire:click="fetchMarks" 
                x-on:click="currentStep = 2"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-50 cursor-not-allowed">
                <span wire:loading.remove wire:target="fetchMarks">Fetch Marks</span>
                <span wire:loading wire:target="fetchMarks" class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Loading...
                </span>
            </button>
        </div>
    @endif
</div> 