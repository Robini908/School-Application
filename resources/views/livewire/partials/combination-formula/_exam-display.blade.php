@if($selectedClass)
    <div class="space-y-6">
        <!-- Class Info Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 bg-[#E2EFDA] rounded-lg flex items-center justify-center">
                        <svg class="h-5 w-5 text-[#217346]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">{{ $classes->firstWhere('id', $selectedClass)?->name ?? 'Selected Class' }}</h2>
                        <p class="text-sm text-gray-500">Select an exam to view analysis</p>
                    </div>
                </div>
                <div class="flex items-center">
                    @if($exams->count() > 0)
                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium bg-[#E2EFDA] text-[#217346]">
                            {{ $exams->count() }} Exams Available
                        </span>
                    @endif
                </div>
            </div>
        </div>

        @if($exams->isEmpty())
            <div class="text-center py-12">
                <div class="rounded-lg bg-gray-50 px-6 py-10">
                    <p class="text-sm text-gray-500">No exams found for this class.</p>
                </div>
            </div>
        @else
            <!-- Exam Selection Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($exams as $exam)
                    <div wire:key="exam-{{ $exam->id }}" 
                        class="bg-white rounded-lg border transition-all duration-200 hover:shadow-md"
                        :class="{
                            'border-[#217346] ring-1 ring-[#217346]': $wire.selectedExam == {{ $exam->id }},
                            'border-gray-200': $wire.selectedExam != {{ $exam->id }}
                        }">
                        <div class="p-4">
                            <!-- Exam Header -->
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3 class="text-base font-semibold text-gray-900 mb-1">
                                    {{ $exam->name ?? 'Unnamed Exam' }}
                                </h3>
                                    <div class="flex items-center space-x-3 text-sm text-gray-500">
                                        <div class="flex items-center">
                                            <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span>Term {{ $exam->term ?? '-' }}</span>
                                        </div>
                                        <span class="text-gray-300">|</span>
                                        <span>Year {{ $exam->year ?? '-' }}</span>
                                    </div>
                                </div>
                                @if($selectedExam == $exam->id)
                                    <div class="h-8 w-8 bg-[#E2EFDA] rounded-full flex items-center justify-center">
                                    <svg class="h-5 w-5 text-[#217346]" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Student Count -->
                            <div class="mb-4 flex items-center justify-between bg-gray-50 rounded-lg p-3">
                                <div class="flex items-center space-x-2">
                                    <div class="h-8 w-8 bg-white rounded-full flex items-center justify-center shadow-sm">
                                        <svg class="h-4 w-4 text-[#217346]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $exam->student_count ?? 0 }} / {{ $exam->myClass->total_students }}</div>
                                        <div class="text-xs text-gray-500">Students Sat</div>
                                    </div>
                                </div>
                                <div class="text-xs px-2 py-1 bg-[#E2EFDA] text-[#217346] rounded-full font-medium">
                                    {{ $exam->turnout_percentage ?? 0 }}% Turnout
                                </div>
                            </div>

                            <!-- Action Button -->
                            <button wire:click="selectExamForAnalysis({{ $exam->id }})"
                                class="w-full relative bg-[#217346] text-white rounded-md transition-all duration-200 hover:bg-[#1a5c38] disabled:opacity-50 disabled:cursor-not-allowed"
                                :class="{ 'opacity-75': $wire.loading && $wire.selectedExam == {{ $exam->id }} }"
                                wire:loading.attr="disabled">
                                <div class="px-4 py-2.5 flex items-center justify-center">
                                    <div wire:loading.remove wire:target="selectExamForAnalysis({{ $exam->id }})">
                                        <span x-text="$wire.selectedExam == {{ $exam->id }} ? 'Selected for Analysis' : 'Select for Analysis'"
                                            class="text-sm font-medium"></span>
                                    </div>
                                    <div wire:loading wire:target="selectExamForAnalysis({{ $exam->id }})"
                                        class="flex items-center">
                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span class="text-sm font-medium">Analyzing...</span>
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endif 