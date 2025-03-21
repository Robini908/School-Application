<div x-data="{
    searchFocused: false,
    studentTooltip: null,
    subjectTooltip: null,
    init() {
        // Initialize tooltips using Alpine.js
        this.setupTooltips();
    },
    setupTooltips() {
        this.$nextTick(() => {
            // Add tooltip functionality as needed
        });
    }
}" x-init="init()" class="bg-white rounded-lg shadow-sm overflow-hidden">

    @if ($showStudentList)
        <!-- Google-style Material Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-5">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <h2 class="text-xl font-medium text-white">Manage Student Subjects</h2>
                
                <!-- Google-inspired Search Bar -->
                <div class="relative w-full md:w-auto md:min-w-[300px]" @click.away="searchFocused = false">
                    <div class="relative" :class="{'ring-2 ring-blue-400 shadow-lg': searchFocused}">
                        <input 
                            type="text" 
                            wire:model.live="searchTerm"
                            @focus="searchFocused = true"
                            placeholder="Search students by name or subject..." 
                            class="w-full bg-white/10 backdrop-blur-sm focus:bg-white text-sm text-white focus:text-gray-900 placeholder-white/70 focus:placeholder-gray-500 rounded-full py-2 pl-10 pr-4 outline-none transition-all duration-200"
                        >
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-white/70" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Statistics Bar -->
        <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2 text-sm text-gray-600">
                    <span>Total students: <span class="font-semibold">{{ $students->total() }}</span></span>
                    <span class="hidden md:inline">•</span>
                    <span class="hidden md:inline">Subject registration period: <span class="font-semibold text-green-600">Active</span></span>
                </div>
        <div>
                    <span class="inline-flex rounded-full bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-800">
                        {{ now()->format('F Y') }} Term
                    </span>
                </div>
            </div>
        </div>

        <!-- Student List with Google Material Cards -->
        <div class="p-6">
            @if ($students->isEmpty())
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No students found</h3>
                    <p class="mt-1 text-sm text-gray-500">Try adjusting your search parameters.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach ($students as $student)
                        <div 
                            x-data="{ hover: false }" 
                            @mouseenter="hover = true" 
                            @mouseleave="hover = false"
                            class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden"
                            :class="{'ring-2 ring-blue-300': hover}"
                        >
                            <div class="p-5">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900">{{ $student->first_name }} {{ $student->last_name }}</h3>
                                        
                                        <!-- Subject count indicator with progress bar -->
                                        <div class="mt-3">
                                            <div class="flex items-center justify-between">
                                                <span class="text-sm font-medium text-gray-700">Subject enrollment</span>
                                                <span class="text-sm font-medium {{ ($student->subjects->count() < 7 || $student->subjects->count() > 8) ? 'text-amber-600' : 'text-green-600' }}">
                                                    {{ $student->subjects->count() }}/8
                                                </span>
                                            </div>
                                            <div class="mt-1 w-full bg-gray-200 rounded-full h-2">
                                                <div class="h-2 rounded-full {{ ($student->subjects->count() < 7 || $student->subjects->count() > 8) ? 'bg-amber-500' : 'bg-green-500' }}" 
                                                    style="width: {{ min(100, ($student->subjects->count() / 8) * 100) }}%"></div>
                                            </div>
                                        </div>
                                        
                                        <!-- Subject list -->
                                        <div class="mt-3">
                                            <h4 class="text-xs font-medium uppercase tracking-wide text-gray-500 mb-2">Enrolled Subjects</h4>
                                            <div class="flex flex-wrap gap-1.5">
                                                @forelse($student->subjects->take(3) as $subject)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium {{ $subject->type === 'compulsory' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                                        {{ $subject->subject_name }}
                                                    </span>
                                                @empty
                                                    <span class="text-xs text-gray-500">No subjects enrolled</span>
                                                @endforelse
                                                
                                                @if($student->subjects->count() > 3)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-800">
                                                        +{{ $student->subjects->count() - 3 }} more
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- Warning for incorrect subject count -->
                                        @if ($student->subjects->count() < 7 || $student->subjects->count() > 8)
                                            <div class="mt-3 flex items-center p-2 bg-amber-50 border border-amber-200 rounded-md">
                                                <svg class="h-5 w-5 text-amber-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                </svg>
                                                <span class="text-xs text-amber-800">
                                                    Student has {{ $student->subjects->count() }} subjects. Required: 7-8 subjects.
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    </div>

                                <!-- Action button -->
                                <div class="mt-4 text-right">
                                    <button 
                                        wire:click="selectStudent({{ $student->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="selectStudent({{ $student->id }})"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                                    >
                                        <svg class="mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Manage Subjects
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="mt-6">
                    {{ $students->links() }}
                </div>
            @endif
        </div>
    @endif

    @if ($showSubjectManagement && $student)
        <!-- Subject Management View -->
        <div 
            x-data="{ showBackConfirm: false }" 
            class="bg-white shadow-sm border border-gray-200 rounded-lg overflow-hidden"
        >
            <!-- Student Subject Management Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4 flex justify-between items-center">
                <h3 class="text-lg font-medium text-white flex items-center">
                    <svg class="w-5 h-5 text-white mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    {{ $student->first_name }} {{ $student->last_name }}'s Subjects
                </h3>
                
                <!-- Back Button with confirmation if changes were made -->
                <button 
                    @click="showBackConfirm ? $wire.closeCard() : showBackConfirm = true" 
                    @click.away="showBackConfirm = false" 
                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-white/20 hover:bg-white/30 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white focus:ring-offset-blue-600 transition-colors"
                >
                    <svg x-show="!showBackConfirm" class="mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span x-show="!showBackConfirm">Back to List</span>
                    <span x-show="showBackConfirm" x-transition>Confirm return?</span>
            </button>
        </div>
            
            <!-- Enrollment Status Bar -->
            <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-700">Subject enrollment status:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $student->subjects->count() >= 7 && $student->subjects->count() <= 8 ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                            {{ $student->subjects->count() >= 7 && $student->subjects->count() <= 8 ? 'Complete' : 'Incomplete' }}
                        </span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-40 bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 mr-2">
                            <div class="h-2.5 rounded-full {{ $student->subjects->count() >= 7 && $student->subjects->count() <= 8 ? 'bg-green-600' : 'bg-amber-500' }}" 
                                style="width: {{ min(100, ($student->subjects->count() / 8) * 100) }}%"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-700">{{ $student->subjects->count() }}/8</span>
                    </div>
                </div>
            </div>
            
            <!-- Subject List -->
            <div class="p-6">
                <!-- Compact table layout for subjects -->
                <div class="bg-white overflow-hidden border border-gray-200 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($student->subjects as $subject)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-medium text-gray-900">{{ $subject->subject_name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ $subject->subject_code }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($subject->type === 'compulsory')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <svg class="h-3.5 w-3.5 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Required
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <svg class="h-3.5 w-3.5 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                                </svg>
                                                Elective
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        @if($subject->type === 'elective')
                                            <div class="flex space-x-2">
                                                <button 
                                                    wire:click="loadRechooseOptions({{ $subject->id }})"
                                                    class="text-amber-600 hover:text-amber-900 inline-flex items-center"
                                                    title="Change subject"
                                                >
                                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                    </svg>
                                                    <span class="ml-1">Change</span>
                                                </button>
                                                
                                                <button 
                                                    wire:click="deregisterSubject({{ $subject->id }})"
                                                    class="text-red-600 hover:text-red-900 inline-flex items-center"
                                                    title="Remove subject"
                                                >
                                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    <span class="ml-1">Remove</span>
                                                </button>
                                            </div>
                                            
                                            @if($rechooseSubjectId === $subject->id)
                                                <div class="mt-3 bg-gray-50 p-3 rounded-md border border-gray-200">
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Select new subject:</label>
                                                    <select 
                                                        wire:model="newSubjectId" 
                                            wire:change="updateSubjectSelection({{ $subject->id }})"
                                                        class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                                                    >
                                                        <option value="">Please select...</option>
                                                        @foreach($sameCategorySubjects as $option)
                                                            <option value="{{ $option->id }}">{{ $option->subject_name }}</option>
                                            @endforeach
                                        </select>
                                                </div>
                                            @endif
                                        @else
                                            <span class="text-gray-400 text-xs">Cannot modify required subject</span>
                                    @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No subjects registered for this student.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                                </div>
                
                <!-- Quick Actions -->
                <div class="mt-6 flex justify-between items-center">
                    <div>
                        <button 
                            wire:click="closeCard" 
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            <svg class="mr-2 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to Student List
                                        </button>
                                    </div>
                    
                    <div>
                        <button 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            onclick="Livewire.emit('openModal', 'add-student-subject-modal', {{ json_encode(['studentId' => $student->id]) }})"
                        >
                            <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add New Subject
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
