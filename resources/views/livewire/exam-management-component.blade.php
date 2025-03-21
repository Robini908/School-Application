<div x-data="{ 
    activeTab: 'exams',
    showInstructions: true,
    showGradingDetails: @entangle('showGradingSystemDetails'),
    isCreating: @entangle('isCreating'),
    isEditing: @entangle('isEditing'),
    showGradingForm: @entangle('showGradingSystemForm')
}" class="bg-white rounded-lg shadow-sm overflow-hidden">

    <!-- Header Section -->
    <div class="border-b border-gray-200 px-6 py-4">
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-medium text-gray-900 flex items-center">
                <svg class="w-6 h-6 mr-2 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                Exam Management
            </h1>
            
            <!-- Action Buttons -->
            <div class="flex space-x-3" x-show="!isCreating && !isEditing && !showGradingForm && !showGradingDetails">
                <button wire:click="create" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    New Exam
                </button>
                <button wire:click="toggleGradingSystemForm" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="mr-2 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Manage Grading
                                </button>
            </div>

            <!-- Back Button (when in forms) -->
            <div x-show="isCreating || isEditing || showGradingForm || showGradingDetails">
                <button 
                    x-show="!showGradingDetails" 
                    wire:click="resetForm" 
                    class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back
                </button>
                <button 
                    x-show="showGradingDetails" 
                    wire:click="closeGradingSystemDetails" 
                    class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back
                </button>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="p-6">
        <!-- Grading System Form -->
        @if ($showGradingSystemForm)
            @include('livewire.partials.exam-management.grading-system-form')
        
        <!-- Grading System Details -->
        @elseif ($showGradingSystemDetails)
            @include('livewire.partials.exam-management.grading-system-details')
        
        <!-- Exam Create/Edit Form -->
        @elseif ($isCreating || $isEditing)
            @include('livewire.partials.exam-management.exam-form')
        
        <!-- Exams List -->
            @else
            @include('livewire.partials.exam-management.exams-list')
            @endif
    </div>

    <!-- Modals -->
    @include('livewire.partials.exam-management.modals')
</div>
