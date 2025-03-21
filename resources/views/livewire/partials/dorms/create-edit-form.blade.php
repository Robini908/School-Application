<!-- Create/Edit Form Component -->
<div class="bg-white dark:bg-gray-50 rounded-lg shadow-sm overflow-hidden" 
     x-data="{ 
        isFormValid: false,
        name: @entangle('name').defer,
        capacity: @entangle('capacity').defer,
        description: @entangle('description').defer,
        validate() {
            this.isFormValid = this.name && this.capacity > 0;
        }
     }"
     x-init="validate(); $watch('name', () => validate()); $watch('capacity', () => validate())">
    
    <!-- Form Header -->
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-200 bg-gray-50 dark:bg-gray-100 flex justify-between items-center">
        <h2 class="font-medium text-xl text-gray-800 dark:text-gray-800 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-green-500 dark:text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            {{ $isEditing ? 'Edit Dormitory' : 'Create New Dormitory' }}
        </h2>
        <button 
            wire:click="resetForm"
            class="text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-200 transition-colors duration-150"
        >
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>
    
    <!-- Form Content -->
    <form wire:submit.prevent="saveDorm" class="px-6 py-5 space-y-6">
        <!-- Name Field -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-700">
                Dormitory Name <span class="text-red-500">*</span>
            </label>
            <div class="mt-1 relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 dark:text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                    </svg>
                </div>
                <input 
                    type="text" 
                    id="name" 
                    wire:model.defer="name"
                    x-model="name"
                    placeholder="Enter dormitory name" 
                    class="block w-full pl-10 pr-10 focus:outline-none sm:text-sm rounded-md py-2.5 border dark:bg-white dark:text-gray-800 dark:placeholder-gray-500
                        @error('name') border-red-300 text-red-900 dark:text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 
                        @else border-gray-300 dark:border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 @enderror" 
                    :class="{'border-red-300 dark:border-red-300': !name && name !== undefined}"
                >
                @error('name')
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                @enderror
            </div>
            @error('name')
                <p class="mt-2 text-sm text-red-600 dark:text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Capacity Field -->
        <div>
            <label for="capacity" class="block text-sm font-medium text-gray-700 dark:text-gray-700">
                Capacity <span class="text-red-500">*</span>
            </label>
            <div class="mt-1 relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 dark:text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                    </svg>
                </div>
                <input 
                    type="number" 
                    id="capacity" 
                    wire:model.defer="capacity"
                    x-model="capacity" 
                    placeholder="Enter dormitory capacity" 
                    min="1"
                    class="block w-full pl-10 pr-10 focus:outline-none sm:text-sm rounded-md py-2.5 border dark:bg-white dark:text-gray-800 dark:placeholder-gray-500
                        @error('capacity') border-red-300 text-red-900 dark:text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 
                        @else border-gray-300 dark:border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 @enderror"
                    :class="{'border-red-300 dark:border-red-300': (!capacity || capacity <= 0) && capacity !== undefined}"
                >
                @error('capacity')
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                @enderror
            </div>
            @error('capacity')
                <p class="mt-2 text-sm text-red-600 dark:text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Description Field -->
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-700">
                Description
            </label>
            <div class="mt-1 relative rounded-md shadow-sm">
                <div class="absolute top-3 left-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 dark:text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <textarea 
                    id="description" 
                    wire:model.defer="description"
                    x-model="description"
                    rows="4" 
                    placeholder="Enter dormitory description (optional)" 
                    class="block w-full pl-10 focus:outline-none sm:text-sm rounded-md border border-gray-300 dark:border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-white dark:text-gray-800 dark:placeholder-gray-500"
                ></textarea>
            </div>
            @error('description')
                <p class="mt-2 text-sm text-red-600 dark:text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Form Actions -->
        <div class="pt-5 border-t border-gray-200 dark:border-gray-200 flex justify-end space-x-3">
            <button 
                type="button"
                wire:click="resetForm" 
                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-300 shadow-sm text-sm font-medium rounded-full text-gray-700 dark:text-gray-700 bg-white dark:bg-white hover:bg-gray-50 dark:hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors duration-150"
            >
                Cancel
            </button>
            <button 
                type="submit"
                :disabled="!isFormValid"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-full shadow-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-150"
            >
                <svg class="mr-2 -ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                {{ $isEditing ? 'Update Dormitory' : 'Save Dormitory' }}
            </button>
        </div>
    </form>
</div>
