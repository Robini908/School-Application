@if ($selectedSection)
    {{-- Students List with Google Material Design 3 --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200"
         x-data="{ 
            showGradeMenu: false,
            refreshing: false,
            lastUpdate: null,
            init() {
                Livewire.on('marks-updated', () => {
                    this.refreshing = true;
                    setTimeout(() => this.refreshing = false, 500);
                    this.lastUpdate = new Date().toLocaleTimeString();
                });
            }
         }"
         x-init="init()"
         :class="{ 'opacity-50': refreshing }"
         wire:poll.10s>

        {{-- Header --}}
        <div class="bg-gradient-to-r from-green-600 to-teal-700 px-6 py-4 rounded-t-lg">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="bg-white/10 backdrop-blur-sm rounded-full p-2">
                        <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-medium text-white">Assign Marks</h2>
                        <p class="mt-1 text-sm text-white/80">
                            {{ $sections->where('id', $selectedSection)->first()->name }} • {{ $selectedClassName }}
                        </p>
                    </div>
                </div>
                
                {{-- Last Update Indicator --}}
                <div x-show="lastUpdate" x-cloak class="flex items-center space-x-2">
                    <span class="text-xs text-white/80">Last updated: <span x-text="lastUpdate"></span></span>
                    <button @click="$wire.refreshMarks()" 
                            class="inline-flex items-center p-1 rounded-full bg-white/10 hover:bg-white/20 transition-colors duration-200 focus:outline-none"
                            :class="{ 'animate-spin': refreshing }">
                        <svg class="w-4 h-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="p-6">
            {{-- Students List --}}
            <div class="space-y-4">
                @forelse($students as $student)
                    <div wire:key="student-{{ $student->id }}" 
                         x-data="{ 
                            showGradeMenu: false,
                            highlight: false,
                            init() {
                                Livewire.on('marks-updated', () => {
                                    if (this.$el.querySelector('[data-mark-updated]')) {
                                        this.highlight = true;
                                        setTimeout(() => this.highlight = false, 2000);
                                    }
                                });
                            }
                         }"
                         x-init="init()"
                         :class="{ 'ring-2 ring-green-500 ring-offset-2': highlight }"
                         class="bg-white rounded-lg border {{ $student->is_enrolled ? 'border-green-200' : 'border-red-200' }} p-4 relative hover:shadow-md transition-all duration-200">
                        <div class="flex items-center justify-between">
                            {{-- Student Info --}}
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full {{ $student->is_enrolled ? 'bg-gray-100' : 'bg-red-50' }} flex items-center justify-center">
                                        <span class="text-sm font-medium {{ $student->is_enrolled ? 'text-gray-600' : 'text-red-600' }}">
                                            {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900">
                                        {{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}
                                    </h3>
                                    <div class="flex items-center mt-1 space-x-2">
                                        <span class="text-xs text-gray-500">ADM: {{ $student->adm_no }}</span>
                                        <span class="text-xs px-1.5 py-0.5 rounded-full {{ $student->is_enrolled ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $student->is_enrolled ? 'Enrolled' : 'Not Enrolled' }}
                                        </span>
                                        @if(!$student->is_enrolled)
                                            <span class="text-xs text-red-600">Cannot assign marks</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Marks/Grades Input with data-mark-updated attribute --}}
                            <div class="flex items-center space-x-3">
                                @if($student->is_enrolled)
                                    @php
                                        $existingMark = $marks[$student->id] ?? null;
                                        $specialGrade = $specialGrades[$student->id] ?? null;
                                    @endphp

                                    {{-- Special Grade Display/Input --}}
                                    @if($specialGrade)
                                        <div class="flex items-center space-x-2" data-mark-updated>
                                            <span @click="showGradeMenu = true" 
                                                  class="cursor-pointer px-3 py-1.5 rounded-full text-sm font-medium transition-colors duration-200
                                                         {{ $specialGrade == 'AB' ? 'bg-red-100 text-red-800' : 
                                                            ($specialGrade == 'EX' ? 'bg-blue-100 text-blue-800' : 
                                                            ($specialGrade == 'P' ? 'bg-green-100 text-green-800' : 
                                                             'bg-yellow-100 text-yellow-800')) }}">
                                                {{ $specialGrade }}
                                            </span>
                                            <button wire:click="clearMark({{ $student->id }})"
                                                    class="text-gray-400 hover:text-gray-600 focus:outline-none transition-colors duration-200">
                                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    @else
                                        {{-- Numeric Mark Input --}}
                                        <div class="relative" x-data="{ editing: false }" data-mark-updated>
                                            <div x-show="!editing" @click="editing = true" class="cursor-pointer">
                                                @if(isset($marks[$student->id]))
                                                    <span class="px-3 py-1.5 bg-gray-100 rounded-lg text-sm font-medium text-gray-900">
                                                        {{ $marks[$student->id] }}
                                                    </span>
                                                @else
                                                    <span class="px-3 py-1.5 border border-dashed border-gray-300 rounded-lg text-sm text-gray-500">
                                                        Add Mark
                                                    </span>
                                                @endif
                                            </div>

                                            <div x-show="editing" 
                                                 x-cloak
                                                 @click.away="editing = false"
                                                 class="absolute right-0 top-0 z-10 bg-white rounded-lg shadow-lg border border-gray-200 p-4 w-48">
                                                <div class="space-y-4">
                                                    <div>
                                                        <label for="mark-{{ $student->id }}" class="block text-xs font-medium text-gray-700 mb-1">
                                                            Enter Mark (0-100)
                                                        </label>
                                                        <input type="number"
                                                               id="mark-{{ $student->id }}"
                                                               wire:model.defer="marks.{{ $student->id }}"
                                                               min="0"
                                                               max="100"
                                                               class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm"
                                                               placeholder="Enter mark">
                                                    </div>
                                                    <div class="flex justify-end space-x-2">
                                                        <button @click="editing = false"
                                                                class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                            Cancel
                                                        </button>
                                                        <button wire:click="saveMark({{ $student->id }})"
                                                                @click="editing = false"
                                                                class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                            Save
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Special Grade Button --}}
                                        <button @click="showGradeMenu = true"
                                                class="inline-flex items-center px-2 py-1 border border-gray-300 rounded-md text-xs font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                            <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                            </svg>
                                            Special Grade
                                        </button>
                                    @endif

                                    {{-- Special Grades Menu with improved transitions --}}
                                    <div x-show="showGradeMenu" 
                                         x-cloak
                                         @click.away="showGradeMenu = false"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 transform scale-95"
                                         x-transition:enter-end="opacity-100 transform scale-100"
                                         x-transition:leave="transition ease-in duration-150"
                                         x-transition:leave-start="opacity-100 transform scale-100"
                                         x-transition:leave-end="opacity-0 transform scale-95"
                                         class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
                                         style="top: 100%;">
                                        <div class="py-1">
                                            @foreach(['AB' => ['Absent', 'bg-red-50 text-red-900'], 
                                                     'EX' => ['Exempted', 'bg-blue-50 text-blue-900'],
                                                     'P' => ['Pass', 'bg-green-50 text-green-900'],
                                                     'F' => ['Fail', 'bg-yellow-50 text-yellow-900']] as $code => $details)
                                                <button wire:click="assignSpecialGrade('{{ $code }}', {{ $student->id }})"
                                                        @click="showGradeMenu = false"
                                                        class="w-full text-left px-4 py-2 text-sm hover:{{ explode(' ', $details[1])[0] }} {{ $specialGrade === $code ? $details[1] : 'text-gray-700' }} focus:outline-none">
                                                    <span class="font-medium">{{ $code }}</span>
                                                    <span class="ml-2 text-gray-500">{{ $details[0] }}</span>
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    {{-- Disabled State for Non-enrolled Students --}}
                                    <div class="flex items-center space-x-3">
                                        <span class="px-3 py-1.5 bg-gray-100 rounded-lg text-sm text-gray-400 cursor-not-allowed">
                                            Not Enrolled
                                        </span>
                                        <button disabled
                                                class="inline-flex items-center px-2 py-1 border border-gray-200 rounded-md text-xs font-medium text-gray-400 bg-gray-50 cursor-not-allowed">
                                            <svg class="w-4 h-4 mr-1 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                            </svg>
                                            Special Grade
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 mb-4">
                            <svg class="w-6 h-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-medium text-gray-900">No Students Found</h3>
                        <p class="mt-1 text-sm text-gray-500">There are no students in this section.</p>
                    </div>
                @endforelse
            </div>

            {{-- Save All Button --}}
            @if(count($students) > 0)
                <div class="mt-6 flex justify-end">
                    <button wire:click="assignMarks"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Save All Marks
                    </button>
                </div>
            @endif
        </div>
    </div>

    {{-- Success Message Toast with improved animations --}}
    <div x-data="{ show: false, message: '' }"
         @mark-saved.window="show = true; message = 'Mark saved successfully!'; setTimeout(() => show = false, 3000)"
         x-show="show"
         x-transition:enter="transform ease-out duration-300 transition"
         x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
         x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed bottom-0 right-0 mb-4 mr-4 max-w-sm w-full bg-green-50 border-l-4 border-green-400 p-4 shadow-lg">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-green-800" x-text="message"></p>
            </div>
        </div>
    </div>
@endif 