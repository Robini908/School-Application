@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    .step-active { @apply bg-blue-600 text-white; }
    .step-completed { @apply bg-green-600 text-white; }
    .step-inactive { @apply bg-gray-100 text-gray-500; }
</style>
@endpush

<div class="h-full bg-gray-50" x-data="{ 
    currentStep: 1,
    steps: [
        { id: 1, name: 'Select Criteria', icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2' },
        { id: 2, name: 'View Marks', icon: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z' },
        { id: 3, name: 'Student Details', icon: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z' }
    ],
    goBack() {
        if (this.currentStep > 1) {
            this.currentStep--;
        }
    }
}">
    <!-- Page Header -->
    <div class="bg-white shadow">
        <div class="px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h1 class="text-lg font-semibold text-gray-900">Mark List Management</h1>
                        <p class="text-sm text-gray-500">Manage and view student marks across different classes and exams</p>
                    </div>
                </div>
            </div>
            </div>
            </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Steps Progress -->
        <div class="mb-8">
            <nav class="flex items-center justify-center" aria-label="Progress">
                <ol class="flex items-center w-full max-w-3xl">
                    <template x-for="(step, index) in steps" :key="step.id">
                        <li class="relative w-full" :class="{ 'pr-8': index !== steps.length - 1 }">
                            <div class="flex items-center">
                                <div :class="{
                                    'step-completed': currentStep > step.id,
                                    'step-active': currentStep === step.id,
                                    'step-inactive': currentStep < step.id
                                }" class="h-10 w-10 rounded-full flex items-center justify-center transition-all duration-200">
                                    <template x-if="currentStep > step.id">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </template>
                                    <template x-if="currentStep <= step.id">
                                        <span x-text="step.id"></span>
                                    </template>
            </div>
                                <div x-show="index !== steps.length - 1" :class="{
                                    'bg-green-600': currentStep > step.id,
                                    'bg-gray-200': currentStep <= step.id
                                }" class="w-full h-0.5 transition-colors duration-200"></div>
                            </div>
                            <div class="absolute -bottom-6 left-1/2 transform -translate-x-1/2 w-max">
                                <span class="text-sm font-medium" :class="{
                                    'text-blue-600': currentStep === step.id,
                                    'text-gray-900': currentStep > step.id,
                                    'text-gray-500': currentStep < step.id
                                }" x-text="step.name"></span>
                            </div>
                        </li>
                    </template>
                </ol>
            </nav>
                        </div>

        <!-- Content Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <!-- Navigation Buttons -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <button x-show="currentStep > 1" 
                        @click="goBack"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back
                    </button>
                            </div>
                        </div>

            <!-- Content Sections -->
            <div class="p-6">
                <!-- Step 1: Selection Criteria -->
                <div x-show="currentStep === 1" 
                    x-transition:enter="transform transition-all ease-in-out duration-300"
                    x-transition:enter-start="opacity-0 translate-x-4"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transform transition-all ease-in-out duration-300"
                    x-transition:leave-start="opacity-100 translate-x-0"
                    x-transition:leave-end="opacity-0 -translate-x-4">
                    @include('livewire.partials.mark-list.selection-criteria')
                    </div>

                <!-- Step 2: Marks Table -->
                <div x-show="currentStep === 2"
                    x-transition:enter="transform transition-all ease-in-out duration-300"
                    x-transition:enter-start="opacity-0 translate-x-4"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transform transition-all ease-in-out duration-300"
                    x-transition:leave-start="opacity-100 translate-x-0"
                    x-transition:leave-end="opacity-0 -translate-x-4">
                    @include('livewire.partials.mark-list.marks-table')
                </div>

                <!-- Step 3: Student Details -->
                <div x-show="currentStep === 3"
                    x-transition:enter="transform transition-all ease-in-out duration-300"
                    x-transition:enter-start="opacity-0 translate-x-4"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transform transition-all ease-in-out duration-300"
                    x-transition:leave-start="opacity-100 translate-x-0"
                    x-transition:leave-end="opacity-0 -translate-x-4">
                    @include('livewire.partials.mark-list.student-details')
                                </div>
                            </div>
                        </div>
                    </div>
</div>
