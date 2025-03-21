<!-- Exam Selection Table -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <!-- Progress Bar -->
    @php
        $totalPercentage = array_sum(array_map('intval', $examPercentages));
        $progressColor = match(true) {
            $totalPercentage > 100 => 'bg-red-500',
            $totalPercentage == 100 => 'bg-[#217346]',
            $totalPercentage >= 90 => 'bg-yellow-500',
            default => 'bg-gray-500'
        };
    @endphp
    
    <div class="p-4 border-b border-gray-200">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-medium text-gray-700">Total Percentage: {{ $totalPercentage }}%</span>
            <span class="text-sm font-medium {{ $totalPercentage > 100 ? 'text-red-600' : 'text-gray-500' }}">
                {{ $totalPercentage > 100 ? 'Exceeds 100%' : ($totalPercentage < 100 ? 'Remaining: ' . (100 - $totalPercentage) . '%' : 'Perfect!') }}
            </span>
        </div>
        <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
            <div class="{{ $progressColor }} h-2 transition-all duration-300 ease-in-out"
                style="width: {{ min($totalPercentage, 100) }}%">
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Select
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Percentage
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Exam Name
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Year
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Term
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($this->exams as $exam)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="checkbox" 
                                wire:model.live="selectedExams" 
                                value="{{ $exam->id }}"
                                class="rounded border-gray-300 text-[#217346] focus:ring-[#217346] h-4 w-4">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="relative rounded-md shadow-sm max-w-[150px]">
                                <input type="number"
                                    wire:model.live="examPercentages.{{ $exam->id }}"
                                    {{ !in_array($exam->id, $this->selectedExams ?? []) ? 'disabled' : '' }}
                                    class="block w-full rounded-md border-gray-300 focus:border-[#217346] focus:ring focus:ring-[#217346] focus:ring-opacity-50 pr-12 disabled:bg-gray-100 disabled:text-gray-500"
                                    min="0" max="100"
                                    placeholder="0">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">%</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $exam->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $exam->year }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">Term {{ $exam->term }}</div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                            No exams found matching your criteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Selected Exams Summary -->
    @if(!empty($selectedExams))
        <div class="p-4 bg-gray-50 border-t border-gray-200">
            <h4 class="text-sm font-medium text-gray-700 mb-2">Selected Exams:</h4>
            <div class="flex flex-wrap gap-2">
                @foreach($selectedExams as $examId)
                    @php $exam = $this->exams->firstWhere('id', $examId); @endphp
                    @if($exam)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#E2EFDA] text-[#217346]">
                            {{ $exam->name }}
                            <button wire:click="removeSelectedExam({{ $examId }})" class="ml-1 focus:outline-none">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </span>
                    @endif
                @endforeach
            </div>
        </div>
    @endif

    <!-- Action Buttons -->
    @if(count($selectedExams) > 1)
        <div class="p-4 bg-gray-50 border-t border-gray-200">
            <div class="flex space-x-3">
                <button wire:click="analyzeCombinedResults"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#217346] hover:bg-[#1a5c38] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#217346] transition-colors duration-200"
                    wire:loading.attr="disabled">
                    <svg wire:loading.remove wire:target="analyzeCombinedResults" class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <svg wire:loading wire:target="analyzeCombinedResults" class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="analyzeCombinedResults">Analyze and Save</span>
                    <span wire:loading wire:target="analyzeCombinedResults">Analyzing...</span>
                </button>

                <button wire:click="quickAnalyzeCombinedResults"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#217346] transition-colors duration-200"
                    wire:loading.attr="disabled">
                    <svg wire:loading.remove wire:target="quickAnalyzeCombinedResults" class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    <svg wire:loading wire:target="quickAnalyzeCombinedResults" class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="quickAnalyzeCombinedResults">Quick Analysis</span>
                    <span wire:loading wire:target="quickAnalyzeCombinedResults">Analyzing...</span>
                </button>
            </div>
        </div>
    @else
        <div class="p-4 bg-gray-50 border-t border-gray-200">
            <p class="text-sm text-gray-500">Please select at least two exams to perform analysis.</p>
        </div>
    @endif
</div> 