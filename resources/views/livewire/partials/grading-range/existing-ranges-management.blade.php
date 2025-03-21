<div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
    <div class="px-4 py-5 sm:px-6 bg-gray-50 border-b border-gray-200">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            Existing Grading Ranges
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">
            Manage grading ranges for 
            <span class="font-medium text-blue-600">{{ $submittedRanges->first()->subject->subject_name }}</span> 
            in 
            <span class="font-medium text-blue-600">{{ $submittedRanges->first()->gradingSystem->name }}</span>
        </p>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Range From</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Range To</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remark</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">GPA</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($submittedRanges as $index => $submittedRange)
                    <tr>
                        <!-- Range From -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($isEditing && $currentIndex == $index)
                                <input type="number" step="0.01" min="0" max="100" 
                                       class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                       wire:model="ranges.{{ $index }}.range_from">
                            @else
                                <span class="text-sm text-gray-900">{{ $submittedRange->range_from }}</span>
                            @endif
                        </td>

                        <!-- Range To -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($isEditing && $currentIndex == $index)
                                <input type="number" step="0.01" min="0" max="100" 
                                       class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                       wire:model="ranges.{{ $index }}.range_to">
                            @else
                                <span class="text-sm text-gray-900">{{ $submittedRange->range_to }}</span>
                            @endif
                        </td>

                        <!-- Grade -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($isEditing && $currentIndex == $index)
                                <input type="text" 
                                       class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                       wire:model="ranges.{{ $index }}.grade">
                            @else
                                <span class="text-sm font-medium text-gray-900">{{ $submittedRange->grade }}</span>
                            @endif
                        </td>

                        <!-- Remark -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($isEditing && $currentIndex == $index)
                                <input type="text" 
                                       class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                       wire:model="ranges.{{ $index }}.remark">
                            @else
                                <span class="text-sm text-gray-500">{{ $submittedRange->remark }}</span>
                            @endif
                        </td>

                        <!-- GPA -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($isEditing && $currentIndex == $index)
                                <input type="number" step="0.01" min="0" max="5" 
                                       class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                       wire:model="ranges.{{ $index }}.gpa">
                            @else
                                <span class="text-sm text-gray-500">{{ $submittedRange->gpa }}</span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                @if (!$isEditing || $currentIndex != $index)
                                    <button wire:click="editRange({{ $index }})"
                                            class="text-blue-600 hover:text-blue-900 focus:outline-none">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                @else
                                    <button wire:click="submitRange({{ $index }})"
                                            class="text-green-600 hover:text-green-900 focus:outline-none">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                    <button wire:click="cancelEdit"
                                            class="text-gray-600 hover:text-gray-900 focus:outline-none">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                @endif

                                <button wire:click="deleteRange({{ $submittedRange->id }})"
                                        class="text-red-600 hover:text-red-900 focus:outline-none">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
        <div class="flex justify-end">
            <button type="button" 
                    wire:click="addRange" 
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Add New Range
            </button>
        </div>
    </div>
</div> 