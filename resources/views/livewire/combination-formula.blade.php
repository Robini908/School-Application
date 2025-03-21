<div x-data="{ 
    activeTab: 'normal',
    showFilters: false,
    showColumnSelector: false,
    selectedSubject: null,
    sortColumn: null,
    sortDirection: 'asc',
    selectedColumns: {
        student_name: true,
        adm_no: true
    }
}" class="space-y-6">
    
    @include('livewire.partials.combination-formula._header')

    <div class="space-y-6">
        <!-- Normal Analysis Tab -->
        <div x-show="activeTab === 'normal'" x-cloak>
            @if(!$showTable)
                @include('livewire.partials.combination-formula._filters')
                @include('livewire.partials.combination-formula._exam-display')
            @else
                <!-- Analysis Results -->
                <div class="space-y-6">
                    <!-- Back Button -->
                    <div class="flex justify-between items-center">
                        <button wire:click="backToExams" 
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#217346]">
                            <svg class="h-5 w-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to Exams
                        </button>
                    </div>

                    <!-- Analysis Tables -->
                    @if(count($selectedExams) > 1)
                        @include('livewire.partials.combination-formula._results-table')
                                                        @else
                        @include('livewire.partials.combination-formula._single-exam-table')
                    @endif
                </div>
            @endif
            </div>

            <!-- Combined Analysis Tab -->
        <div x-show="activeTab === 'combined'" x-cloak>
            @include('livewire.partials.combination-formula._filters')
            @include('livewire.partials.combination-formula._exam-display')
            @include('livewire.partials.combination-formula._exam-table')
            @include('livewire.partials.combination-formula._exam-form')
            @include('livewire.partials.combination-formula._results-table')
                            </div>
                                </div>

    <!-- Loading States -->
    <div wire:loading.flex class="fixed inset-0 bg-gray-900 bg-opacity-50 items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 flex items-center space-x-4">
            <svg class="animate-spin h-8 w-8 text-[#217346]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-lg font-medium text-gray-900">Processing...</span>
                                </div>
                            </div>

    <!-- Error Messages -->
                                @if ($errors->any())
        <div class="fixed bottom-4 right-4 bg-red-50 border-l-4 border-red-400 p-4 z-50" 
            x-data="{ show: true }" 
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-x-full"
            x-transition:enter-end="opacity-100 transform translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-x-0"
            x-transition:leave-end="opacity-0 transform translate-x-full">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                                    </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">
                        There were {{ count($errors) }} errors with your submission
                    </h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc list-inside">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                                </div>
                <div class="ml-auto pl-3">
                    <div class="-mx-1.5 -my-1.5">
                        <button @click="show = false" class="inline-flex rounded-md p-1.5 text-red-500 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <span class="sr-only">Dismiss</span>
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            </div>
                        @endif

    <!-- Success Messages -->
    @if (session()->has('message'))
        <div class="fixed bottom-4 right-4 bg-green-50 border-l-4 border-green-400 p-4 z-50"
            x-data="{ show: true }" 
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-x-full"
            x-transition:enter-end="opacity-100 transform translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-x-0"
            x-transition:leave-end="opacity-0 transform translate-x-full">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">
                        {{ session('message') }}
                                        </p>
                                    </div>
                <div class="ml-auto pl-3">
                    <div class="-mx-1.5 -my-1.5">
                        <button @click="show = false" class="inline-flex rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <span class="sr-only">Dismiss</span>
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                                    </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    </div>

@push('styles')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush
