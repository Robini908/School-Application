<div>
    <h2 class="text-lg font-medium text-gray-800 mb-2">Transition Details</h2>
    <p class="text-sm text-gray-600 mb-6">Review and confirm the details for transitioning your selected students.</p>

    @if (count($selectedStudents) > 0)
        <div class="relative" x-data="{ showStudentSidebar: false, animationPlaying: false }">
            <!-- Floating action button to view selected students -->
            <button type="button" 
                @click="showStudentSidebar = !showStudentSidebar" 
                class="fixed bottom-6 right-6 z-40 bg-blue-600 hover:bg-blue-700 text-white rounded-full p-3 shadow-lg transform transition-all duration-300 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                :class="{'rotate-45': showStudentSidebar}">
                <span x-show="!showStudentSidebar"><i class="fas fa-users text-lg"></i></span>
                <span x-show="showStudentSidebar"><i class="fas fa-times text-lg"></i></span>
            </button>

            <!-- Student sidebar overlay - Now closes sidebar when clicked -->
            <div x-show="showStudentSidebar" 
                 @click="showStudentSidebar = false"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm z-30"></div>

            <!-- Student sidebar - Prevent clicks from bubbling up to overlay -->
            <div x-show="showStudentSidebar" 
                 @click.stop
                 @keydown.escape.window="showStudentSidebar = false"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="translate-x-full"
                 class="fixed top-0 right-0 w-80 h-full bg-white shadow-xl z-40 overflow-y-auto">
                 
                <div class="p-5 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50 sticky top-0 z-10">
                    <!-- Close button at the top right -->
                    <button @click="showStudentSidebar = false" 
                            class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full p-1 transition-all duration-200 transform hover:scale-110"
                            aria-label="Close sidebar">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                    
                    <h3 class="text-lg font-medium text-gray-800 flex items-center pr-8">
                        <i class="fas fa-users text-blue-600 mr-2"></i>
                        Selected Students
                    </h3>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ count($selectedStudents) }} student(s) selected for transition
                    </p>
                </div>
                
                <div class="p-4">
                    @php
                        $students = \App\Models\StudentRecord::whereIn('id', $selectedStudents)->get();
                    @endphp
                    
                    <ul class="space-y-3">
                        @foreach($students as $index => $student)
                            <li class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-blue-50 hover:border-blue-300 hover:shadow-md transition-all duration-200 transform hover:-translate-y-0.5"
                                x-transition:enter="transition ease-out duration-300 delay-{{ $index * 100 }}"
                                x-transition:enter-start="opacity-0 transform translate-x-6"
                                x-transition:enter-end="opacity-100 transform translate-x-0">
                                <div class="flex-shrink-0 h-10 w-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center shadow-sm">
                                    <span class="font-medium">{{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}</span>
                                </div>
                                <div class="ml-3 flex-1">
                                    <h4 class="text-sm font-medium text-gray-900">{{ $student->first_name }} {{ $student->last_name }}</h4>
                                    <div class="flex items-center text-xs text-gray-500 mt-0.5">
                                        <i class="fas fa-id-card text-gray-400 mr-1"></i>
                                        <span>{{ $student->adm_no }}</span>
                                    </div>
                                </div>
                                <!-- Quick actions for each student can be added here -->
                                <div class="ml-2">
                                    <button class="text-gray-400 hover:text-blue-600 focus:outline-none focus:text-blue-600 transition-colors duration-200 p-1 rounded-full hover:bg-blue-100" title="View student details">
                                        <i class="fas fa-info-circle"></i>
                                    </button>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    
                    <!-- Close button at the bottom with keyboard focus -->
                    <div class="mt-6 flex justify-center">
                        <button @click="showStudentSidebar = false" 
                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:-translate-y-0.5">
                            <i class="fas fa-times mr-2"></i>
                            Close
                        </button>
                    </div>
                    
                    <!-- Keyboard shortcut hint -->
                    <div class="text-center text-xs text-gray-500 mt-3">
                        Press <kbd class="px-2 py-1 bg-gray-100 border border-gray-300 rounded text-xs">ESC</kbd> to close
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-5 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 bg-blue-100 rounded-full p-2">
                            <i class="fas fa-clipboard-check text-blue-600"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-800">
                                Transition Summary
                            </h3>
                            <p class="mt-1 text-sm text-gray-600">
                                You're about to transition <button type="button" @click="showStudentSidebar = true" class="font-medium text-blue-600 hover:text-blue-800 underline">{{ count($selectedStudents) }} student(s)</button>.
                                Please verify all details before proceeding.
                            </p>
                        </div>
                    </div>
                </div>

                @if (!$targetClass && $transitionType !== 'repetition')
                    <div class="px-6 py-4 bg-yellow-50 border-b border-yellow-200">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 bg-yellow-100 rounded-full p-2">
                                <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-base font-medium text-yellow-800">
                                    Cannot Complete Transition
                                </h3>
                                <p class="mt-1 text-sm text-yellow-700">
                                    @if ($transitionType === 'promotion')
                                        <span class="font-medium">No higher class available</span> for promotion.
                                        The selected class is already the highest available.
                                    @elseif ($transitionType === 'demotion')
                                        <span class="font-medium">No lower class available</span> for demotion.
                                        The selected class is already the lowest available.
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="px-6 py-5">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Target Class Selection -->
                        <div>
                            <label for="targetClass" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                <i class="fas fa-graduation-cap text-blue-500 mr-2"></i>
                                Target Class
                            </label>
                            <div class="relative mt-1">
                                <div class="flex shadow-sm rounded-md">
                                    <div class="relative flex-grow focus-within:z-10">
                                        <select wire:model.live="targetClass" id="targetClass" 
                                                class="block w-full rounded-md border-gray-300 pl-3 pr-10 py-2.5 text-base focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-gray-100 cursor-not-allowed"
                                                disabled>
                                            <option value="">Select Target Class</option>
                                            @if ($targetClass)
                                                @foreach ($classes as $class)
                                                    @if ($class->id == $targetClass)
                                                        <option value="{{ $class->id }}" selected>{{ $class->name }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-400">
                                            <i class="fas fa-lock text-xs"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-1.5 text-xs text-gray-500 flex items-center">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    @if ($transitionType === 'promotion')
                                        Automatically set to the next higher class
                                    @elseif ($transitionType === 'demotion')
                                        Automatically set to the previous lower class
                                    @elseif ($transitionType === 'repetition')
                                        Same as current class
                                    @endif
                                </p>
                                @error('targetClass')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $errors->first('targetClass') }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Target Section Selection -->
                        <div>
                            <label for="targetSection" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                <i class="fas fa-users text-blue-500 mr-2"></i>
                                Target Section
                            </label>
                            <div class="relative mt-1">
                                <select wire:model.live="targetSection" id="targetSection" 
                                        class="block w-full rounded-md border-gray-300 pl-3 pr-10 py-2.5 text-base focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-gray-50 hover:bg-white transition-colors duration-200"
                                        {{ !$targetClass ? 'disabled' : '' }}>
                                    <option value="">-- Select Target Section --</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-600">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                            @error('targetSection')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $errors->first('targetSection') }}
                                </p>
                            @enderror
                            
                            @if (!$targetClass)
                                <p class="mt-1.5 text-xs text-gray-500 flex items-center">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Please wait for a target class to be available
                                </p>
                            @endif
                        </div>
                        
                        <!-- Transition Year -->
                        <div>
                            <label for="transitionYear" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                                <i class="fas fa-calendar-alt text-blue-500 mr-2"></i>
                                Transition Year
                            </label>
                            <div class="relative mt-1">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-calendar-alt text-gray-400"></i>
                                </div>
                                <input wire:model="transitionYear" 
                                       type="text" 
                                       id="transitionYear"
                                       class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 pr-3 py-2.5 sm:text-sm border-gray-300 rounded-md bg-gray-100 cursor-not-allowed" 
                                       value="{{ now()->year }}" 
                                       readonly>
                            </div>
                            <p class="mt-1.5 text-xs text-gray-500 flex items-center">
                                <i class="fas fa-info-circle mr-1"></i>
                                Current academic year
                            </p>
                            @error('transitionYear')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $errors->first('transitionYear') }}
                                </p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Transition Summary Card -->
                    @if ($targetClass)
                        <div class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded-lg shadow-sm">
                            <h3 class="text-sm font-medium text-blue-800 mb-3 flex items-center">
                                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                                Transition Path
                            </h3>
                            <div class="flex flex-col items-center justify-center p-3 bg-white rounded-md border border-blue-100">
                                @php
                                    $currentClass = $classes->firstWhere('id', $selectedClass);
                                    $targetClassObj = $classes->firstWhere('id', $targetClass);
                                @endphp
                                
                                @if ($currentClass && $targetClassObj)
                                    <!-- Visual path diagram with animation capability -->
                                    <div class="flex items-center justify-center w-full max-w-2xl py-8"
                                         x-data="{ animateTransition: false }"
                                         x-on:play-animation.window="animateTransition = true; setTimeout(() => animateTransition = false, 3000);">
                                        <!-- Current Class -->
                                        <div class="text-center w-1/3">
                                            <div class="mx-auto w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center border border-gray-300 mb-2 relative"
                                                 :class="{'transition-all duration-1000 ease-in-out transform scale-90 -translate-y-1': animateTransition}">
                                                <i class="fas fa-users text-gray-600 text-lg"></i>
                                                
                                                <!-- Animated students going from current to target class -->
                                                <template x-if="animateTransition">
                                                    @for ($i = 0; $i < min(count($selectedStudents), 5); $i++)
                                                        <div class="absolute top-1/2 left-1/2 w-4 h-4 rounded-full bg-blue-500 transition-all duration-2000 delay-{{ $i * 200 }}"
                                                            :class="{'transform -translate-x-32 translate-y-0': animateTransition}"
                                                            style="z-index: 30; transition-timing-function: cubic-bezier(0.34, 1.56, 0.64, 1);"></div>
                                                    @endfor
                                                </template>
                                            </div>
                                            <div class="font-medium text-gray-900"
                                                :class="{'transition-all duration-1000 ease-in-out opacity-50': animateTransition}">
                                                {{ $currentClass->name }}
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1"
                                                :class="{'transition-all duration-1000 ease-in-out opacity-50': animateTransition}">
                                                Current Class
                                            </div>
                                        </div>
                                        
                                        <!-- Arrow and transition type with animation -->
                                        <div class="w-1/3 flex flex-col items-center">
                                            @if ($transitionType === 'promotion')
                                                <div class="h-0.5 w-16 bg-emerald-300 my-8 relative" x-ref="arrow">
                                                    <div class="absolute inset-0 bg-emerald-500 origin-left transform scale-x-0 transition-transform duration-1000"
                                                        :class="{'scale-x-100': animateTransition}"></div>
                                                </div>
                                                <div class="bg-emerald-500 text-white text-xs rounded-full px-3 py-1 -mt-4 transition-all duration-500"
                                                     :class="{'scale-125 shadow-md': animateTransition}">
                                                    <i class="fas fa-arrow-up mr-1"></i> Promotion
                                                </div>
                                            @elseif ($transitionType === 'demotion')
                                                <div class="h-0.5 w-16 bg-red-300 my-8 relative">
                                                    <div class="absolute inset-0 bg-red-500 origin-left transform scale-x-0 transition-transform duration-1000"
                                                        :class="{'scale-x-100': animateTransition}"></div>
                                                </div>
                                                <div class="bg-red-500 text-white text-xs rounded-full px-3 py-1 -mt-4 transition-all duration-500"
                                                     :class="{'scale-125 shadow-md': animateTransition}">
                                                    <i class="fas fa-arrow-down mr-1"></i> Demotion
                                                </div>
                                            @elseif ($transitionType === 'repetition')
                                                <div class="h-0.5 w-16 bg-amber-300 my-8 relative">
                                                    <div class="absolute inset-0 bg-amber-500 origin-left transform scale-x-0 transition-transform duration-1000"
                                                        :class="{'scale-x-100': animateTransition}"></div>
                                                </div>
                                                <div class="bg-amber-500 text-white text-xs rounded-full px-3 py-1 -mt-4 transition-all duration-500"
                                                     :class="{'scale-125 shadow-md': animateTransition}">
                                                    <i class="fas fa-redo mr-1"></i> Repetition
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Target Class with animation -->
                                        <div class="text-center w-1/3">
                                            <div class="mx-auto w-16 h-16 rounded-full flex items-center justify-center mb-2 relative transition-all duration-1000"
                                                 :class="{
                                                    'transform scale-110 shadow-lg': animateTransition,
                                                    'bg-emerald-100 border border-emerald-300': '{{ $transitionType }}' === 'promotion',
                                                    'bg-red-100 border border-red-300': '{{ $transitionType }}' === 'demotion',
                                                    'bg-amber-100 border border-amber-300': '{{ $transitionType }}' === 'repetition'
                                                 }">
                                                <i class="fas fa-users transition-all duration-500" 
                                                   :class="{
                                                        'transform scale-125': animateTransition,
                                                        'text-emerald-600': '{{ $transitionType }}' === 'promotion',
                                                        'text-red-600': '{{ $transitionType }}' === 'demotion',
                                                        'text-amber-600': '{{ $transitionType }}' === 'repetition'
                                                   }"></i>
                                            </div>
                                            <div class="font-medium transition-all duration-500"
                                                 :class="{
                                                    'transform scale-105': animateTransition,
                                                    'text-emerald-800': '{{ $transitionType }}' === 'promotion',
                                                    'text-red-800': '{{ $transitionType }}' === 'demotion',
                                                    'text-amber-800': '{{ $transitionType }}' === 'repetition'
                                                 }">{{ $targetClassObj->name }}</div>
                                            <div class="text-xs mt-1 transition-all duration-500"
                                                 :class="{
                                                    'text-emerald-600': '{{ $transitionType }}' === 'promotion',
                                                    'text-red-600': '{{ $transitionType }}' === 'demotion',
                                                    'text-amber-600': '{{ $transitionType }}' === 'repetition'
                                                 }">Target Class</div>
                                        </div>
                                    </div>
                                    
                                    <!-- Play animation button -->
                                    <button type="button" 
                                            class="mt-2 inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                                            @click="$dispatch('play-animation')"
                                            x-data="{ playing: false }"
                                            @click="playing = true; setTimeout(() => playing = false, 3000);"
                                            :disabled="playing"
                                            :class="{'opacity-50 cursor-not-allowed': playing}">
                                        <template x-if="!playing">
                                            <i class="fas fa-play mr-1.5 text-blue-500"></i>
                                        </template>
                                        <template x-if="playing">
                                            <svg class="animate-spin h-3 w-3 text-blue-500 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </template>
                                        <span x-text="playing ? 'Animating...' : 'Visualize Transition'"></span>
                                    </button>
                                    
                                    <!-- Transition description -->
                                    <div class="mt-4 text-sm text-gray-700 text-center">
                                        <p>
                                            @if ($transitionType === 'promotion')
                                                <span class="text-gray-600">You are promoting</span> 
                                                <span class="font-medium text-blue-700">{{ count($selectedStudents) }} student(s)</span> 
                                                <span class="text-gray-600">from</span> 
                                                <span class="font-medium text-gray-700">{{ $currentClass->name }}</span> 
                                                <span class="text-gray-600">to</span> 
                                                <span class="font-medium text-emerald-600">{{ $targetClassObj->name }}</span>
                                            @elseif ($transitionType === 'demotion')
                                                <span class="text-gray-600">You are demoting</span> 
                                                <span class="font-medium text-blue-700">{{ count($selectedStudents) }} student(s)</span> 
                                                <span class="text-gray-600">from</span> 
                                                <span class="font-medium text-gray-700">{{ $currentClass->name }}</span> 
                                                <span class="text-gray-600">to</span> 
                                                <span class="font-medium text-red-600">{{ $targetClassObj->name }}</span>
                                            @elseif ($transitionType === 'repetition')
                                                <span class="text-gray-600">You are setting</span> 
                                                <span class="font-medium text-blue-700">{{ count($selectedStudents) }} student(s)</span> 
                                                <span class="text-gray-600">to repeat</span> 
                                                <span class="font-medium text-amber-600">{{ $currentClass->name }}</span>
                                            @endif
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                    
                    <!-- Reason Textarea -->
                    <div class="mt-8">
                        <label for="reason" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                            <i class="fas fa-comment-alt text-blue-500 mr-2"></i>
                            Reason
                            @if ($transitionType !== 'promotion')
                                <span class="text-red-500 ml-1">*</span>
                            @endif
                        </label>
                        <div class="mt-1">
                            <textarea 
                                wire:model="reason" 
                                id="reason" 
                                rows="3" 
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md bg-gray-50 hover:bg-white transition-colors duration-200"
                                placeholder="Please provide a reason for this transition..."></textarea>
                        </div>
                        @if ($transitionType !== 'promotion')
                            <p class="mt-1.5 text-xs text-red-600 flex items-center">
                                <i class="fas fa-info-circle mr-1"></i>
                                A reason is required for {{ $transitionType }} transitions.
                            </p>
                        @else
                            <p class="mt-1.5 text-xs text-gray-500 flex items-center">
                                <i class="fas fa-info-circle mr-1"></i>
                                Optional for promotions, but recommended for record-keeping.
                            </p>
                        @endif
                        @error('reason')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg shadow-md p-6">
            <div class="flex">
                <div class="flex-shrink-0 bg-yellow-100 rounded-full p-3">
                    <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-5">
                    <h3 class="text-lg font-medium text-yellow-800">No students selected</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>You haven't selected any students for transition. Please go back and select at least one student to proceed.</p>
                    </div>
                    <div class="mt-4">
                        <button type="button" 
                                @click="activeStep = 3"
                                class="inline-flex items-center px-4 py-2 border border-yellow-300 shadow-sm text-sm font-medium rounded-md text-yellow-700 bg-white hover:bg-yellow-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Go Back to Student Selection
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
