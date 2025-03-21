<div x-data="{ 
    showHelp: localStorage.getItem('subject-selection-help') === null ? true : JSON.parse(localStorage.getItem('subject-selection-help')),
    init() {
        // Listen for Livewire events that might affect Alpine state
        // Updated for Livewire 3: using Livewire.on() is deprecated, we need to use addEventListener
        document.addEventListener('livewire:initialized', () => {
            // Set up event listeners after Livewire is initialized
            Livewire.on('subjectFormToggled', (event) => {
                const showSubjectForm = event[0]; // In Livewire 3, the data is passed as an array
                // This ensures Alpine state is synchronized with Livewire state
                this.$wire.set('showSubjectForm', showSubjectForm);
                this.$wire.set('showStudentCard', !showSubjectForm);
            });
            
            // Listen for sections update event
            Livewire.on('sectionsUpdated', (event) => {
                console.log('Sections updated event received', event);
                // Force a re-render of the component
                this.$nextTick(() => {
                    // Additional logic if needed
                });
            });
            
            // Debug event for sections data
            Livewire.on('debug-sections', (data) => {
                console.log('Debug sections data:', data);
                // Manually refresh UI if needed
                if (data && data[0] && data[0].selectedClass) {
                    console.log(`Class ${data[0].selectedClass} has ${data[0].sectionCount} sections`);
                    this.$nextTick(() => {
                        // Force UI update if needed
                    });
                }
            });
        });

        // Watch for direct Livewire property changes
        $watch('$wire.showSubjectForm', value => {
            if (value === true) {
                this.$wire.set('showStudentCard', false);
            }
        });

        $watch('$wire.showStudentCard', value => {
            if (value === true) {
                this.$wire.set('showSubjectForm', false);
            }
        });
        
        // Watch for selectedClass changes
        $watch('$wire.selectedClass', value => {
            if (value) {
                console.log('Class selected:', value);
                // Manual trigger to refresh sections
                this.$wire.refreshComponent();
            }
        });
    }
}" x-init="init()" class="bg-white rounded-lg shadow-sm overflow-hidden">

    <!-- Help Panel (Collapsible) -->
    <div x-show="showHelp" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-2"
         class="bg-blue-50 p-4 border-b border-blue-100">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-sm font-medium text-blue-800">Subject Selection Guide</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ol class="list-decimal list-inside space-y-1 ml-1">
                        <li>Select a class and section to view students</li>
                        <li>Select students who need to choose subjects</li>
                        <li>Assign subjects to the selected students</li>
                        <li>Review and save your selections</li>
                    </ol>
                </div>
                <div class="mt-4">
                    <button type="button" 
                            @click="showHelp = false; localStorage.setItem('subject-selection-help', 'false');" 
                            class="inline-flex items-center px-2.5 py-1.5 border border-blue-300 shadow-sm text-xs leading-4 font-medium rounded text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Don't show again
                    </button>
                </div>
                                        </div>
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button type="button" 
                            @click="showHelp = false" 
                            class="inline-flex rounded-md p-1.5 text-blue-500 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <span class="sr-only">Dismiss</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                            </button>
                </div>
            </div>
                                        </div>
                                    </div>

    <div class="p-6">
        <div x-show="$wire.showStudentCard" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100">
            <!-- Header with Flash Messages -->
            @include('livewire.partials.subject-selection.header')
            
            <!-- Class and Section Selection -->
            @include('livewire.partials.subject-selection.class-section-selector')
            
            <!-- Student Selection -->
            @include('livewire.partials.subject-selection.student-selector')
        </div>
        
        <!-- Subject Selection Form -->
        <div x-show="$wire.showSubjectForm" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100">
            @include('livewire.partials.subject-selection.subject-form')
        </div>
    </div>
    
    <!-- Show Help Button (Only visible when help is hidden) -->
    <div x-show="!showHelp" class="px-6 pb-4 -mt-2">
        <button type="button" 
                @click="showHelp = true" 
                class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs leading-4 font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="mr-1.5 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Show Help
        </button>
    </div>
</div>
