{{-- Filters Section with Google Material Design 3 --}}
<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    {{-- Header --}}
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4 rounded-t-lg">
        <div class="flex items-center space-x-4">
            <div class="bg-white/10 backdrop-blur-sm rounded-full p-2">
                <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-medium text-white">Select Exam Details</h2>
                <p class="mt-1 text-sm text-white/80">Choose the class, exam, and subject to begin assigning marks</p>
            </div>
        </div>
    </div>

    <div class="p-6">
        {{-- Selection Progress --}}
        <div class="mb-6">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center space-x-2">
                    <span class="text-sm font-medium text-gray-700">Selection Progress</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ ($selectedClass ? 1 : 0) + ($selectedExam ? 1 : 0) + ($selectedSubject ? 1 : 0) }}/3
                    </span>
                </div>
                @if($selectedClassName)
                    <span class="text-sm text-gray-500">{{ $selectedClassName }}</span>
                @endif
            </div>
            <div class="w-full bg-gray-200 rounded-full h-1">
                <div class="bg-blue-600 h-1 rounded-full transition-all duration-300"
                     style="width: {{ (($selectedClass ? 1 : 0) + ($selectedExam ? 1 : 0) + ($selectedSubject ? 1 : 0)) * 33.33 }}%">
                </div>
            </div>
        </div>

        {{-- Selection Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Class Selection --}}
            <div class="relative" wire:key="class-selection">
                <label for="class" class="block text-sm font-medium text-gray-700 mb-2">Class</label>
                <div class="relative">
                    <select wire:model.live="selectedClass" 
                            id="class" 
                            class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-lg">
                        <option value="">Select a class</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                    <div wire:loading wire:target="selectedClass" 
                         class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-500"></div>
                    </div>
                </div>
            </div>

            {{-- Exam Selection --}}
            <div class="relative" wire:key="exam-selection">
                <label for="exam" class="block text-sm font-medium text-gray-700 mb-2">
                    Exam
                    @if(!$selectedClass)
                        <span class="text-gray-400 text-xs">(Select a class first)</span>
                    @endif
                </label>
                <div class="relative">
                    <select wire:model.live="selectedExam" 
                            id="exam" 
                            @if(!$selectedClass) disabled @endif
                            class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-lg disabled:bg-gray-50 disabled:text-gray-500">
                        <option value="">Select an exam</option>
                        @foreach ($exams as $exam)
                            <option value="{{ $exam->id }}">{{ $exam->name }}</option>
                        @endforeach
                    </select>
                    <div wire:loading wire:target="selectedExam" 
                         class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-500"></div>
                    </div>
                </div>
            </div>

            {{-- Subject Selection --}}
            <div class="relative" wire:key="subject-selection">
                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                    Subject
                    @if(!$selectedExam)
                        <span class="text-gray-400 text-xs">(Select an exam first)</span>
                    @endif
                </label>
                <div class="relative">
                    <select wire:model.live="selectedSubject" 
                            id="subject" 
                            @if(!$selectedExam) disabled @endif
                            class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-lg disabled:bg-gray-50 disabled:text-gray-500">
                        <option value="">Select a subject</option>
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                        @endforeach
                    </select>
                    <div wire:loading wire:target="selectedSubject" 
                         class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-500"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Selection Summary --}}
        @if($selectedClass && $selectedExam && $selectedSubject)
            <div class="mt-6 bg-gray-50 rounded-lg p-4 border border-gray-200">
                <div class="flex flex-wrap gap-3">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $selectedClassName }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                        {{ $selectedExamName }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        {{ $selectedSubjectName }}
                    </span>
                </div>
            </div>
        @endif
    </div>
</div> 