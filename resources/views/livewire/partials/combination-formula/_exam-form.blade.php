<!-- Combined Exam Form -->
@if($showCombinedExamForm && count($selectedExams) > 1)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">Save Combined Exam</h2>
            <p class="mt-1 text-sm text-gray-600">
                Create a new exam record based on the combined analysis
            </p>
        </div>

        <div class="p-6 space-y-6">
            <!-- Information Alert -->
            <div class="rounded-md bg-blue-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Combined Exam Details</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>The following exams will be combined:</p>
                            <ul class="mt-2 list-disc list-inside space-y-1">
                                @foreach($selectedExamNames as $index => $examName)
                                    <li>{{ $examName }} ({{ $gradingSystemNames[$index] ?? 'Unknown Grading' }})</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Fields -->
            <div class="grid grid-cols-1 gap-6">
                <!-- Exam Name -->
                <div>
                    <label for="customExamName" class="block text-sm font-medium text-gray-700">
                        Exam Name
                    </label>
                    <div class="mt-1">
                        <input type="text" 
                            wire:model="customExamName" 
                            id="customExamName"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#217346] focus:ring focus:ring-[#217346] focus:ring-opacity-50 sm:text-sm"
                            placeholder="Enter a name for the combined exam">
                        @error('customExamName')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <p class="mt-2 text-sm text-gray-500">
                        Choose a descriptive name that reflects the combined exams
                    </p>
                </div>

                <!-- Term Selection -->
                <div>
                    <label for="customExamTerm" class="block text-sm font-medium text-gray-700">
                        Term
                    </label>
                    <div class="mt-1">
                        <select wire:model="customExamTerm" 
                            id="customExamTerm"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#217346] focus:ring focus:ring-[#217346] focus:ring-opacity-50 sm:text-sm">
                            <option value="">Select Term</option>
                            <option value="1">Term 1</option>
                            <option value="2">Term 2</option>
                            <option value="3">Term 3</option>
                        </select>
                        @error('customExamTerm')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Year Selection -->
                <div>
                    <label for="customExamYear" class="block text-sm font-medium text-gray-700">
                        Year
                    </label>
                    <div class="mt-1">
                        <input type="number" 
                            wire:model="customExamYear" 
                            id="customExamYear"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#217346] focus:ring focus:ring-[#217346] focus:ring-opacity-50 sm:text-sm"
                            placeholder="{{ date('Y') }}">
                        @error('customExamYear')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            <div class="flex justify-end space-x-3">
                <button type="button"
                    wire:click="$set('showCombinedExamForm', false)"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#217346]">
                    Cancel
                </button>
                <button type="button"
                    wire:click="saveCombinedExam"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#217346] hover:bg-[#1a5c38] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#217346]"
                    wire:loading.attr="disabled">
                    <svg wire:loading.remove wire:target="saveCombinedExam" class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    <svg wire:loading wire:target="saveCombinedExam" class="animate-spin h-4 w-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="saveCombinedExam">Save Combined Exam</span>
                    <span wire:loading wire:target="saveCombinedExam">Saving...</span>
                </button>
            </div>
        </div>
    </div>
@endif 