<div>
    <!-- Important Information Alert -->
    <div x-data="{ moreDetails: false }" class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 mb-6">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-3 flex-1">
                <div class="flex justify-between items-center">
                    <h3 class="text-sm font-medium text-blue-800">
                        Assign Grading Ranges for <span class="font-semibold">{{ $subjects->find($subjectId)->subject_name }}</span>
                        in <span class="font-semibold">"{{ $gradingSystems->find($selectedGradingSystem)->name }}"</span>
                    </h3>
                    <button @click="moreDetails = !moreDetails" class="text-blue-600 hover:text-blue-800 text-sm font-medium focus:outline-none">
                        <span x-show="!moreDetails">Show Guidelines</span>
                        <span x-show="moreDetails">Hide Guidelines</span>
                    </button>
                </div>
                
                <!-- Collapsible Guidelines -->
                <div x-show="moreDetails" x-transition class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc pl-5 space-y-1">
                        <li>Review existing ranges to avoid conflicts</li>
                        <li>Consider how ranges impact overall grading policies</li>
                        <li>Document changes for future reference</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Selection -->
    <div class="mb-6">
        <div class="flex flex-wrap gap-2">
            <button type="button" 
                    @click="$wire.set('activeAction', 'reuseSame')" 
                    class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    :class="{'ring-2 ring-blue-500 ring-offset-2': activeAction === 'reuseSame'}">
                <svg class="-ml-0.5 mr-2 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                </svg>
                Reuse (Same System)
            </button>
            
            <button type="button" 
                    @click="$wire.set('activeAction', 'reuseDifferent')" 
                    class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    :class="{'ring-2 ring-blue-500 ring-offset-2': activeAction === 'reuseDifferent'}">
                <svg class="-ml-0.5 mr-2 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
                Reuse (Different System)
            </button>
            
            <button type="button" 
                    @click="$wire.set('activeAction', 'suggest')" 
                    class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    :class="{'ring-2 ring-blue-500 ring-offset-2': activeAction === 'suggest'}">
                <svg class="-ml-0.5 mr-2 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
                Suggest Ranges
            </button>
        </div>
    </div>

    <!-- Action Content -->
    <div class="mb-6">
        @if ($activeAction === 'reuseSame')
            <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                <h3 class="text-lg font-medium text-gray-900 mb-3">Reuse Ranges (Same Grading System)</h3>
                <p class="text-sm text-gray-600 mb-4">Select a subject to reuse its existing grading ranges for the current subject.</p>
                
                <div class="mb-4">
                    <label for="reuseSubject" class="block text-sm font-medium text-gray-700 mb-1">Subject:</label>
                    <select wire:model.live="reuseSubjectId" id="reuseSubject" 
                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        <option value="">Select a Subject</option>
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <button type="button" 
                        wire:click="reuseGradingRanges" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Apply Ranges
                </button>
            </div>
        @elseif($activeAction === 'suggest')
            <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                <h3 class="text-lg font-medium text-gray-900 mb-3">Suggest Grading Ranges</h3>
                <p class="text-sm text-gray-600 mb-4">Generate suggested grading ranges based on performance data and best practices.</p>
                
                <button type="button" 
                        wire:click="suggestGradingRanges" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Generate Suggestions
                </button>
            </div>
        @elseif($activeAction === 'reuseDifferent')
            <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                <h3 class="text-lg font-medium text-gray-900 mb-3">Reuse Ranges (Different Grading System)</h3>
                <p class="text-sm text-gray-600 mb-4">Import grading ranges from another grading system and subject.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="reuseGradingSystem" class="block text-sm font-medium text-gray-700 mb-1">Grading System:</label>
                        <select wire:model.live="reuseGradingSystemId" id="reuseGradingSystem" 
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <option value="">Select a Grading System</option>
                            @foreach ($gradingSystems as $gradingSystem)
                                <option value="{{ $gradingSystem->id }}">{{ $gradingSystem->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="reuseSubjectFromOtherSystem" class="block text-sm font-medium text-gray-700 mb-1">Subject:</label>
                        <select wire:model.live="reuseSubjectFromOtherSystemId" id="reuseSubjectFromOtherSystem" 
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <option value="">Select a Subject</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <button type="button" 
                        wire:click="reuseGradingRangesFromOtherSystem" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Import Ranges
                </button>
            </div>
        @endif
    </div>

    <!-- Grading Ranges Table -->
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden mb-6">
        <div class="px-4 py-5 sm:px-6 bg-gray-50 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Grading Ranges</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Define the grading ranges for this subject.</p>
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
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($ranges as $index => $range)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="number" step="0.01" min="0" max="100" 
                                       wire:model.blur="ranges.{{ $index }}.range_from"
                                       class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" 
                                       placeholder="Min">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="number" step="0.01" min="0" max="100" 
                                       wire:model.blur="ranges.{{ $index }}.range_to"
                                       class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" 
                                       placeholder="Max">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="text" 
                                       wire:model.blur="ranges.{{ $index }}.grade"
                                       class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" 
                                       placeholder="Grade">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="text" 
                                       wire:model.blur="ranges.{{ $index }}.remark"
                                       class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" 
                                       placeholder="Remark">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="number" step="0.01" min="0" max="5" 
                                       wire:model.blur="ranges.{{ $index }}.gpa"
                                       class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" 
                                       placeholder="GPA">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                @if ($index > 0)
                                    <button type="button" 
                                            wire:click="removeRange({{ $index }})"
                                            class="text-red-600 hover:text-red-900">
                                        Remove
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
            @if (!$isEditing && count($ranges) < 12)
                <button type="button" 
                        wire:click="addRange" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add Range
                </button>
            @elseif (count($ranges) >= 12)
                <span class="text-sm text-amber-600">Maximum of 12 ranges reached</span>
            @else
                <div></div> <!-- Empty div to maintain flex spacing -->
            @endif
            
            <div class="flex space-x-3">
                <button type="button" 
                        wire:click="saveRanges" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    {{ $isEditing ? 'Update' : 'Save' }}
                </button>
                
                <button type="button" 
                        wire:click="saveAllRanges" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    {{ $isEditing ? 'Update All' : 'Save All' }}
                </button>
            </div>
        </div>
    </div>
</div> 