<!-- Main Component Container with Google-inspired Material Design -->
<div x-data="{ activeStep: 1, totalSteps: 4, isSubmitting: false, showSummary: true, transitionSuccess: false }" class="bg-white rounded-lg shadow-md">
    <!-- Header with more prominent styling -->
    <div class="px-6 py-5 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200 rounded-t-lg">
        <h1 class="text-2xl font-medium text-gray-800 flex items-center">
                    @if ($transitionType === 'promotion')
                <span class="text-emerald-600 flex items-center"><i class="fas fa-arrow-up text-lg bg-emerald-100 p-2 rounded-full mr-3"></i>Promote Students</span>
                    @elseif ($transitionType === 'demotion')
                <span class="text-red-600 flex items-center"><i class="fas fa-arrow-down text-lg bg-red-100 p-2 rounded-full mr-3"></i>Demote Students</span>
                    @elseif ($transitionType === 'repetition')
                <span class="text-amber-600 flex items-center"><i class="fas fa-redo text-lg bg-amber-100 p-2 rounded-full mr-3"></i>Repeat Students</span>
            @else
                <span class="text-blue-600 flex items-center"><i class="fas fa-exchange-alt text-lg bg-blue-100 p-2 rounded-full mr-3"></i>Student Transition</span>
                    @endif
        </h1>
        <p class="text-sm text-gray-600 mt-2">
            Move students between classes with ease. Follow the steps below to complete the process.
        </p>
            </div>

    <!-- Progress Stepper - Google Material Design inspired -->
    <div class="px-6 py-6 bg-white border-b border-gray-100">
        <div class="flex items-center justify-between max-w-3xl mx-auto">
            <template x-for="step in totalSteps" :key="step">
                <div class="flex-1 relative">
                    <!-- Step Circle with Enhanced styling -->
                    <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center shadow-sm transition-all duration-300" 
                         :class="{
                            'bg-blue-600 text-white ring-4 ring-blue-100 scale-110': activeStep === step,
                            'bg-green-500 text-white': activeStep > step,
                            'bg-gray-200 text-gray-500': activeStep < step
                         }">
                        <template x-if="activeStep > step">
                            <i class="fas fa-check"></i>
                        </template>
                        <template x-if="activeStep <= step">
                            <span x-text="step" class="font-medium"></span>
                        </template>
                    </div>
                    <!-- Step Label with better visibility -->
                    <div class="text-xs text-center mt-2 font-medium transition-all duration-300" 
                         :class="{
                            'text-blue-700 scale-110': activeStep === step,
                            'text-green-600': activeStep > step,
                            'text-gray-500': activeStep < step
                         }">
                        <template x-if="step === 1">Transition Type</template>
                        <template x-if="step === 2">Class & Section</template>
                        <template x-if="step === 3">Select Students</template>
                        <template x-if="step === 4">Finalize</template>
                    </div>
                    <!-- Connector Line with animation -->
                    <div x-show="step < totalSteps" class="absolute top-5 left-1/2 w-full h-1 transition-all duration-500" 
                         :class="{
                            'bg-green-500': activeStep > step,
                            'bg-gray-200': activeStep <= step
                         }">
                                    </div>
                                </div>
            </template>
                                    </div>
                                </div>
                    
    <!-- Summary Panel that shows current selections -->
    <div x-show="showSummary && activeStep > 1" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform -translate-y-4"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-4"
         class="px-6 py-4 bg-blue-50 border-b border-blue-100">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h3 class="text-sm font-medium text-blue-800 mb-1">Current Progress</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-2">
                            <i class="fas fa-exchange-alt text-blue-600"></i>
                                    </div>
                        <div>
                            <span class="text-gray-500">Transition:</span>
                            <span class="font-medium ml-1 text-gray-800">
                                @if($transitionType === 'promotion')
                                    <span class="text-emerald-600">Promotion</span>
                                @elseif($transitionType === 'demotion')
                                    <span class="text-red-600">Demotion</span>
                                @elseif($transitionType === 'repetition')
                                    <span class="text-amber-600">Repetition</span>
                                @else
                                    Not Selected
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center" x-show="activeStep >= 2">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-2">
                            <i class="fas fa-school text-blue-600"></i>
                        </div>
                        <div>
                            <span class="text-gray-500">Class & Section:</span>
                            <span class="font-medium ml-1 text-gray-800">
                                @if($selectedClass && $selectedSection)
                                    {{ optional($classes->firstWhere('id', $selectedClass))->name }} - 
                                    {{ optional($sections->firstWhere('id', $selectedSection))->name }}
                                @else
                                    Not Selected
                                    @endif
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center" x-show="activeStep >= 3">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-2">
                            <i class="fas fa-users text-blue-600"></i>
                        </div>
                        <div>
                            <span class="text-gray-500">Students:</span>
                            <span class="font-medium ml-1 text-gray-800">
                                {{ count($selectedStudents) }} selected
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <button @click="showSummary = false" class="text-gray-400 hover:text-gray-600" aria-label="Hide summary">
                <i class="fas fa-times-circle"></i>
            </button>
                        </div>
                    </div>

    <!-- Alert Messages with improved styling -->
    <div class="px-6 py-2">
        @if (session()->has('message'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md shadow-sm animate-fadeIn" role="alert">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">{{ session('message') }}</p>
                    </div>
                                    </div>
                                    </div>
                                @endif
        @if (session()->has('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-md shadow-sm animate-fadeIn" role="alert">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">{{ session('error') }}</p>
                    </div>
                            </div>
                        </div>
                    @endif
    </div>

    <!-- Form Content with card styling -->
    <form wire:submit.prevent="promoteStudents" @submit="isSubmitting = true" class="px-6 py-4">
        <!-- Improved Loading indicator with backdrop filter -->
        <div wire:loading.delay wire:target="selectedClass, selectedSection, search, promoteStudents" 
             class="fixed inset-0 bg-gray-900 bg-opacity-30 backdrop-blur-sm z-50 flex items-center justify-center transition-opacity duration-300">
            <div class="bg-white p-5 rounded-lg shadow-lg flex items-center space-x-4 animate-bounce-in">
                <svg class="animate-spin h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-gray-700 font-medium">Processing...</span>
                            </div>
                            </div>
        
        <!-- Step 1: Transition Type Selection -->
        <div x-show.transition.opacity.duration.500ms="activeStep === 1" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-x-4"
             x-transition:enter-end="opacity-100 transform translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform translate-x-0"
             x-transition:leave-end="opacity-0 transform -translate-x-4"
             class="max-w-4xl mx-auto">
            @include('livewire.partials.promote-students.transition-type-selector')
            
            <div class="flex justify-between mt-8">
                <div></div>
                <button type="button" 
                        @click="activeStep = 2; showSummary = true;" 
                        class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition ease-in-out duration-150 shadow-md hover:shadow-lg transform hover:-translate-y-0.5"
                        :disabled="!$wire.transitionType"
                        :class="{ 'opacity-50 cursor-not-allowed': !$wire.transitionType }">
                    Continue
                    <i class="fas fa-arrow-right ml-2"></i>
                </button>
                            </div>
                        </div>
        
        <!-- Step 2: Class and Section Selection -->
        <div x-show.transition.opacity.duration.500ms="activeStep === 2"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-x-4"
             x-transition:enter-end="opacity-100 transform translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform translate-x-0"
             x-transition:leave-end="opacity-0 transform -translate-x-4"
             class="max-w-4xl mx-auto">
            @include('livewire.partials.promote-students.class-section-selector')
            
            <div class="flex justify-between mt-8">
                <button type="button" 
                        @click="activeStep = 1" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition ease-in-out duration-150 hover:shadow">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back
                </button>
                <button type="button" 
                        @click="activeStep = 3" 
                        class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition ease-in-out duration-150 shadow-md hover:shadow-lg transform hover:-translate-y-0.5"
                        :disabled="!$wire.selectedClass || !$wire.selectedSection"
                        :class="{ 'opacity-50 cursor-not-allowed': !$wire.selectedClass || !$wire.selectedSection }">
                    Continue
                    <i class="fas fa-arrow-right ml-2"></i>
                </button>
                            </div>
                        </div>
        
        <!-- Step 3: Student Selection -->
        <div x-show.transition.opacity.duration.500ms="activeStep === 3"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-x-4"
             x-transition:enter-end="opacity-100 transform translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform translate-x-0"
             x-transition:leave-end="opacity-0 transform -translate-x-4"
             class="max-w-5xl mx-auto">
            @include('livewire.partials.promote-students.student-list')
            
            <div class="flex justify-between mt-8">
                <button type="button" 
                        @click="activeStep = 2" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition ease-in-out duration-150 hover:shadow">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back
                </button>
                <button type="button" 
                        @click="activeStep = 4" 
                        class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition ease-in-out duration-150 shadow-md hover:shadow-lg transform hover:-translate-y-0.5"
                        :disabled="$wire.selectedStudents.length === 0"
                        :class="{ 'opacity-50 cursor-not-allowed': $wire.selectedStudents.length === 0 }">
                    Continue
                    <i class="fas fa-arrow-right ml-2"></i>
                                </button>
                            </div>
                        </div>
        
        <!-- Step 4: Transition Details -->
        <div x-show.transition.opacity.duration.500ms="activeStep === 4"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-x-4"
             x-transition:enter-end="opacity-100 transform translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform translate-x-0"
             x-transition:leave-end="opacity-0 transform -translate-x-4"
             class="max-w-4xl mx-auto">
            @include('livewire.partials.promote-students.transition-details')
            
            <div class="flex justify-between mt-8">
                <button type="button" 
                        @click="activeStep = 3" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition ease-in-out duration-150 hover:shadow">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back
                </button>
                <button type="submit" 
                        @click="$dispatch('play-animation')"
                        class="inline-flex items-center px-6 py-3 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition ease-in-out duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                        :class="{'bg-emerald-600 hover:bg-emerald-700 focus:ring-emerald-500': transitionSuccess}"
                        :disabled="isSubmitting || !$wire.targetSection || !$wire.targetClass">
                    <!-- Loading spinner -->
                    <span x-show="isSubmitting && !transitionSuccess" class="inline-block mr-2">
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                    
                    <!-- Success check mark -->
                    <span x-show="transitionSuccess" class="inline-block mr-2">
                        <i class="fas fa-check-circle text-white"></i>
                    </span>

                    <span x-show="!isSubmitting && !transitionSuccess && $wire.transitionType === 'promotion'">Promote Students</span>
                    <span x-show="!isSubmitting && !transitionSuccess && $wire.transitionType === 'demotion'">Demote Students</span>
                    <span x-show="!isSubmitting && !transitionSuccess && $wire.transitionType === 'repetition'">Repeat Students</span>
                    
                    <span x-show="isSubmitting && !transitionSuccess && $wire.transitionType === 'promotion'">Promoting...</span>
                    <span x-show="isSubmitting && !transitionSuccess && $wire.transitionType === 'demotion'">Demoting...</span>
                    <span x-show="isSubmitting && !transitionSuccess && $wire.transitionType === 'repetition'">Repeating...</span>
                    
                    <span x-show="transitionSuccess">Completed!</span>
                </button>
            </div>
        </div>
    </form>

    <!-- Step indicator for mobile -->
    <div class="py-4 px-6 bg-gray-50 border-t border-gray-200 md:hidden">
        <div class="text-xs text-center text-gray-500">
            Step <span class="font-medium text-blue-600" x-text="activeStep"></span> of <span class="font-medium" x-text="totalSteps"></span>
        </div>
    </div>

    <!-- Custom event handler for promotion errors to reset the button state -->
    <script>
        document.addEventListener('livewire:init', () => {
            // Listen for promotion errors to reset the button state
            Livewire.on('promotionError', () => {
                Alpine.store('isSubmitting', false);
            });
            
            // Listen for refreshComponent event
            Livewire.on('refreshComponent', () => {
                console.log('Component refreshed');
            });
            
            // Listen for successful transition
            Livewire.on('transition-success', (message) => {
                // Show success state on button
                Alpine.store('transitionSuccess', true);
                
                // Show toast notification
                toast().success(message, 'Transition Complete');
                
                // Reset state after 2 seconds
                setTimeout(() => {
                    Alpine.store('transitionSuccess', false);
                    Alpine.store('isSubmitting', false);
                }, 2000);
            });
            
            // Listen for transition error
            Livewire.on('transition-error', (message) => {
                // Reset state
                Alpine.store('isSubmitting', false);
                
                // Show error toast
                toast().danger(message, 'Error');
            });
        });
        
        // Initialize Alpine store for component state
        document.addEventListener('alpine:init', () => {
            Alpine.store('promoteStudents', {
                activeStep: 1,
                lastSelectedSection: null,
                isSubmitting: false,
                transitionSuccess: false,
                
                updateSelectedSection(sectionId) {
                    this.lastSelectedSection = sectionId;
                    console.log('Section updated to:', sectionId);
                }
            });
        });
    </script>
</div>

<!-- Add some keyframe animations for a smoother UI -->
<style>
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes bounceIn {
        0% { transform: scale(0.8); opacity: 0; }
        50% { transform: scale(1.05); opacity: 0.9; }
        100% { transform: scale(1); opacity: 1; }
    }
    
    .animate-fadeIn {
        animation: fadeIn 0.3s ease-in-out;
    }
    
    .animate-bounce-in {
        animation: bounceIn 0.4s ease-in-out;
    }
    
    @keyframes studentTransition {
        0% { transform: translate(0, 0); opacity: 1; }
        50% { transform: translate(-100px, -20px); opacity: 0.7; }
        100% { transform: translate(-200px, 0); opacity: 0; }
    }
    
    .student-transition {
        animation: studentTransition 1.5s ease-in-out forwards;
    }
</style>