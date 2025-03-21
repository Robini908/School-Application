<div class="px-6 py-4">
    <!-- Grading System Selection with Material Design Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
        <div class="px-4 py-5 sm:p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="grading_system" class="block text-sm font-medium text-gray-700 mb-1">Select Grading System</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                        <select wire:model.live="selectedGradingSystemId" 
                                class="pl-10 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                {{ $applyToAllSystems ? 'disabled' : '' }}>
                            <option value="">-- Select Grading System --</option>
                            @foreach ($gradingSystems as $system)
                                <option value="{{ $system->id }}">{{ $system->name }}</option>
                            @endforeach
                        </select>
                        <div wire:loading wire:target="selectedGradingSystemId" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <svg class="animate-spin h-5 w-5 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-end">
                    <div class="flex-grow">
                        <div class="flex items-center h-5">
                            <input id="apply_to_all" wire:model.live="applyToAllSystems" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            <label for="apply_to_all" class="ml-2 block text-sm text-gray-700">
                                Apply to all grading systems
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Information Alert -->
    <div x-show="showInfoAlert" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         class="relative bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 mb-6 border border-blue-200">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3 flex-1 md:flex md:justify-between">
                <div>
                    <h3 class="text-sm font-medium text-blue-800">Important Notice</h3>
                    <p class="mt-1 text-sm text-blue-700">
                        These grades will be used in the calculation of the mean grade during exam analysis.
                    </p>
                </div>
                <div class="mt-3 md:mt-0 md:ml-6">
                    <button @click="showInfoAlert = false" class="text-blue-500 hover:text-blue-700 focus:outline-none">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-between items-center mb-6">
        <button wire:click="showAddForm" 
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-150">
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            <span wire:loading.remove wire:target="showAddForm">Add New Mean Grades</span>
            <span wire:loading wire:target="showAddForm">
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Processing...
            </span>
        </button>
        
        <div class="flex space-x-2">
            <button wire:click="$refresh" 
                    class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="-ml-0.5 mr-2 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Refresh
            </button>
        </div>
    </div>

    <!-- Grades Table Card -->
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
        <div class="px-4 py-5 sm:px-6 bg-gradient-to-r from-gray-50 to-white border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Grades for 
                    <span class="font-semibold text-indigo-600">
                        {{ $selectedGradingSystemId ? ($gradingSystems->find($selectedGradingSystemId)->name ?? 'None Selected') : ($applyToAllSystems ? 'All Grading Systems' : 'None Selected') }}
                    </span>
                </h3>
                
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ collect($gradesList)->isEmpty() ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                    {{ collect($gradesList)->isEmpty() ? 'No grades defined' : count($gradesList) . ' grades' }}
                </span>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            @if (collect($gradesList)->isEmpty())
                <div class="p-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No Grades Found</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating new mean grades.</p>
                    <div class="mt-6">
                        <button wire:click="showAddForm" type="button" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Add New Mean Grades
                        </button>
                    </div>
                </div>
            @else
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">MeanGrade</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remark</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">GPA</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Range From</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Range To</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($gradesList as $index => $grade)
                            <tr x-data="{ isEditing: {{ $grade['isEditing'] ? 'true' : 'false' }} }"
                                :class="{ 'bg-blue-50': isEditing }"
                                class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($grade['isEditing'])
                                        <input wire:model="gradesList.{{ $index }}.grade"
                                               type="text" 
                                               class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                               maxlength="2"
                                               oninput="this.value = this.value.toUpperCase()">
                                        @error("gradesList.$index.grade")
                                            <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                                        @enderror
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium bg-indigo-100 text-indigo-800">
                                            {{ $grade['grade'] }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($grade['isEditing'])
                                        <input wire:model="gradesList.{{ $index }}.remark"
                                               type="text" 
                                               class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        @error("gradesList.$index.remark")
                                            <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                                        @enderror
                                    @else
                                        <span class="text-sm text-gray-900">{{ $grade['remark'] }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($grade['isEditing'])
                                        <input wire:model="gradesList.{{ $index }}.gpa"
                                               type="number"
                                               step="0.01"
                                               class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        @error("gradesList.$index.gpa")
                                            <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                                        @enderror
                                    @else
                                        <span class="text-sm text-gray-900">{{ $grade['gpa'] }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($grade['isEditing'])
                                        <input wire:model="gradesList.{{ $index }}.range_from"
                                               type="number" 
                                               class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        @error("gradesList.$index.range_from")
                                            <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                                        @enderror
                                    @else
                                        <span class="text-sm text-gray-500">{{ $grade['range_from'] }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($grade['isEditing'])
                                        <input wire:model="gradesList.{{ $index }}.range_to"
                                               type="number" 
                                               class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        @error("gradesList.$index.range_to")
                                            <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                                        @enderror
                                    @else
                                        <span class="text-sm text-gray-500">{{ $grade['range_to'] }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        @if ($grade['isEditing'])
                                            <!-- Update Icon -->
                                            <button wire:click="updateGrade({{ $index }})"
                                                    class="text-green-600 hover:text-green-900 focus:outline-none transition-colors duration-150"
                                                    title="Update">
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </button>
                                            <!-- Cancel Icon -->
                                            <button wire:click="cancelEdit({{ $index }})"
                                                    class="text-gray-600 hover:text-gray-900 focus:outline-none transition-colors duration-150"
                                                    title="Cancel">
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        @else
                                            <!-- Edit Icon -->
                                            <button wire:click="editGrade({{ $index }})"
                                                    class="text-indigo-600 hover:text-indigo-900 focus:outline-none transition-colors duration-150"
                                                    title="Edit">
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <!-- Delete Icon -->
                                            <button @click="showDeleteConfirm = true; gradeToDelete = {{ $index }}"
                                                    class="text-red-600 hover:text-red-900 focus:outline-none transition-colors duration-150"
                                                    title="Delete">
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div> 