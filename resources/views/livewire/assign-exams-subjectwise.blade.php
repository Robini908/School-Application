{{-- {!! $this->getFlashMessages() !!} --}}

<div x-data="{ 
    currentStep: 1,
    totalSteps: 3,
    showHelp: false,
    init() {
        this.$watch('currentStep', value => {
            // Trigger Alpine's reactivity
            this.$nextTick(() => {
                // Add any additional logic here
            });
        });
    }
}" class="min-h-screen bg-gray-50 py-6">
    
    <!-- Main Content Container -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center space-x-2">
                    <span class="text-sm font-medium text-gray-700">Progress</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        Step <span x-text="currentStep"></span> of <span x-text="totalSteps"></span>
                        </span>
                    </div>
                <div class="text-sm text-gray-500">
                    <span x-text="currentStep === 1 ? 'Select Class & Subject' : (currentStep === 2 ? 'Choose Stream' : 'Assign Marks')"></span>
                </div>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-1">
                <div class="bg-blue-600 h-1 rounded-full transition-all duration-300"
                     :style="'width: ' + (currentStep / totalSteps * 100) + '%'"></div>
            </div>
        </div>

        <!-- Back Navigation -->
        <div x-show="currentStep > 1" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform -translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             class="mb-4">
            <button @click="currentStep--" 
                    class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                <svg class="mr-2 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back
            </button>
        </div>

        <!-- Cards Container with Transitions -->
        <div class="space-y-6">
            <!-- Step 1: Filters -->
            <div x-show="currentStep === 1" 
                 x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full">
                @include('livewire.partials.exam-marks.filters')
                
                <!-- Next Button -->
                <div class="mt-6 flex justify-end">
                    <button @click="currentStep++"
                            :disabled="!$wire.selectedClass || !$wire.selectedExam || !$wire.selectedSubject"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200">
                        Continue to Stream Selection
                        <svg class="ml-2 -mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                        </div>
                    </div>

            <!-- Step 2: Streams -->
            <div x-show="currentStep === 2"
                 x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full">
                @include('livewire.partials.exam-marks.streams')
                
                <!-- Next Button -->
                <div class="mt-6 flex justify-end">
                    <button @click="currentStep++"
                            :disabled="!$wire.selectedSection"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200">
                        Continue to Student List
                        <svg class="ml-2 -mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Step 3: Students List -->
            <div x-show="currentStep === 3"
                 x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full">
                @include('livewire.partials.exam-marks.students-list')
                        </div>
                    </div>
                </div>

    <!-- Quick Actions FAB -->
    <div class="fixed bottom-6 right-6 flex flex-col space-y-4">
        <!-- Help Button -->
        <button @click="showHelp = !showHelp"
                class="flex items-center justify-center w-14 h-14 rounded-full bg-white shadow-lg border border-gray-200 text-blue-600 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </button>

        <!-- Section Toggle Button -->
        <div class="relative" x-data="{ showMenu: false }">
            <button @click="showMenu = !showMenu"
                    class="flex items-center justify-center w-14 h-14 rounded-full bg-blue-600 shadow-lg text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <div x-show="showMenu"
                 @click.away="showMenu = false"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 class="absolute bottom-full right-0 mb-2 w-48 rounded-lg bg-white shadow-lg border border-gray-200 py-1">
                <button @click="showFilters = !showFilters; showMenu = false"
                        class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                    <span x-text="showFilters ? 'Hide Filters' : 'Show Filters'"></span>
                </button>
                @if($selectedSubject)
                <button @click="showStreams = !showStreams; showMenu = false"
                        class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                    <span x-text="showStreams ? 'Hide Streams' : 'Show Streams'"></span>
                </button>
                @endif
                @if($selectedSubject && $selectedSection)
                <button @click="showStudents = !showStudents; showMenu = false"
                        class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                    <span x-text="showStudents ? 'Hide Students' : 'Show Students'"></span>
                </button>
            @endif
            </div>
        </div>
    </div>

    <!-- Help Dialog -->
    <div x-show="showHelp" 
         class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                    <div class="absolute right-0 top-0 pr-4 pt-4">
                        <button @click="showHelp = false" class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-base font-semibold leading-6 text-gray-900">How to Assign Marks</h3>
                            <div class="mt-2">
                                <div class="text-sm text-gray-500 space-y-2">
                                    <p>1. Select the class, exam, and subject from the filters section</p>
                                    <p>2. Choose the appropriate stream to view students</p>
                                    <p>3. Enter marks or special grades for each student</p>
                                    <p>4. Click "Assign Marks" to save your entries</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
