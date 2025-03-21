<div class="bg-white">
    <div class="mb-6">
        <h2 class="text-lg font-medium text-gray-900">{{ $isEditing ? 'Edit Exam' : 'Create New Exam' }}</h2>
        <p class="mt-1 text-sm text-gray-500">
            {{ $isEditing ? 'Update the exam details below.' : 'Fill in the details to create a new exam.' }}
        </p>
    </div>

    @if($showGradingSystemForm)
        <!-- Inline Grading System Creation Form -->
        <div class="bg-blue-50 p-4 rounded-lg mb-6">
            <h3 class="text-lg font-medium text-blue-900 mb-3">Create New Grading System</h3>
            
            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <!-- Grading System Name -->
                <div class="sm:col-span-3">
                    <label for="gradingSystemName" class="block text-sm font-medium text-gray-700">Name</label>
                    <div class="mt-1">
                        <input type="text" wire:model="gradingSystemName" id="gradingSystemName" 
                               class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                    </div>
                    @error('gradingSystemName')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Effective Date -->
                <div class="sm:col-span-3">
                    <label for="gradingSystemEffectiveDate" class="block text-sm font-medium text-gray-700">Effective Date</label>
                    <div class="mt-1">
                        <input type="date" wire:model="gradingSystemEffectiveDate" id="gradingSystemEffectiveDate" 
                               class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                    </div>
                    @error('gradingSystemEffectiveDate')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="sm:col-span-6">
                    <label for="gradingSystemDescription" class="block text-sm font-medium text-gray-700">Description</label>
                    <div class="mt-1">
                        <textarea wire:model="gradingSystemDescription" id="gradingSystemDescription" rows="3"
                                  class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"></textarea>
                    </div>
                    @error('gradingSystemDescription')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Rules -->
                <div class="sm:col-span-6">
                    <label for="gradingSystemRules" class="block text-sm font-medium text-gray-700">Rules</label>
                    <div class="mt-1">
                        <textarea wire:model="gradingSystemRules" id="gradingSystemRules" rows="3"
                                  class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"></textarea>
                    </div>
                    @error('gradingSystemRules')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-4 flex justify-end space-x-3">
                <button type="button" wire:click="cancelGradingSystemForm" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancel
                </button>
                <button type="button" wire:click="saveGradingSystem" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Save Grading System
                    <span wire:loading wire:target="saveGradingSystem" class="ml-2">
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                </button>
            </div>
        </div>
    @endif

    <!-- Sticky Create Grading System Button -->
    @if(!$showGradingSystemForm && !$grading_system_id)
        <div class="fixed bottom-4 right-4 z-10">
            <button type="button" 
                    wire:click="$set('grading_system_id', 'create_new')" 
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-transform transform hover:scale-105">
                <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Create New Grading System
            </button>
        </div>
    @endif

    <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}">
        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
            <!-- Exam Name -->
            <div class="sm:col-span-3">
                <label for="name" class="block text-sm font-medium text-gray-700">Exam Name</label>
                <div class="mt-1">
                    <input type="text" wire:model="name" id="name" 
                           class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                </div>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Term -->
            <div class="sm:col-span-3">
                <label for="term" class="block text-sm font-medium text-gray-700">Term</label>
                <div class="mt-1">
                    <select wire:model="term" id="term" 
                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        <option value="">Select Term</option>
                        @foreach($terms as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                @error('term')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Year -->
            <div class="sm:col-span-3">
                <label for="year" class="block text-sm font-medium text-gray-700">Year</label>
                <div class="mt-1">
                    <input type="number" wire:model="year" id="year" 
                           class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                </div>
                @error('year')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Grading System -->
            <div class="sm:col-span-3">
                <label for="grading_system_id" class="block text-sm font-medium text-gray-700">Grading System</label>
                <div class="mt-1 flex items-center">
                    <select wire:model.live="grading_system_id" id="grading_system_id" 
                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        <option value="">Select Grading System</option>
                        @foreach($gradingSystems as $system)
                            <option value="{{ $system->id }}">{{ $system->name }}</option>
                        @endforeach
                        <option value="create_new" class="font-medium text-blue-600">+ Create New Grading System</option>
                    </select>
                    <button type="button" 
                            wire:click="viewGradingSystemDetails" 
                            class="ml-2 inline-flex items-center p-1.5 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            {{ !$grading_system_id || $grading_system_id === 'create_new' ? 'disabled' : '' }}>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                @error('grading_system_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                
                <!-- Grading System Info Tooltip -->
                @if($grading_system_id && $grading_system_id !== 'create_new')
                    <div class="mt-2">
                        <p class="text-sm text-blue-600">
                            <span class="font-medium">Tip:</span> Click the eye icon to view grading ranges for this system
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <div class="mt-8 flex justify-end">
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                {{ $isEditing ? 'Update Exam' : 'Create Exam' }}
                <span wire:loading wire:target="{{ $isEditing ? 'update' : 'store' }}" class="ml-2">
                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </span>
            </button>
        </div>
    </form>
</div> 