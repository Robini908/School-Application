<!-- Assign Dorm Master Component -->
<div class="bg-white dark:bg-gray-50 rounded-lg shadow-sm overflow-hidden" 
     x-data="{ 
        isFormValid: false,
        teacherId: @entangle('teacherId').defer,
        session: @entangle('session').defer,
        validate() {
            this.isFormValid = this.teacherId && this.session;
        }
     }"
     x-init="validate(); $watch('teacherId', () => validate()); $watch('session', () => validate())">
    
    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-200 bg-gray-50 dark:bg-gray-100 flex justify-between items-center">
        <h2 class="font-medium text-xl text-gray-800 dark:text-gray-800 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-green-500 dark:text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            Assign Dorm Master
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
    <form wire:submit.prevent="saveDormMaster" class="px-6 py-5 space-y-6">
        <!-- Teacher Field -->
        <div>
            <label for="teacherId" class="block text-sm font-medium text-gray-700 dark:text-gray-700">
                Teacher <span class="text-red-500">*</span>
            </label>
            <div class="mt-1 relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <select 
                    id="teacherId" 
                    wire:model.defer="teacherId"
                    x-model="teacherId"
                    class="block w-full pl-10 pr-10 focus:outline-none sm:text-sm rounded-md py-2.5 border dark:bg-white dark:text-gray-800
                        @error('teacherId') border-red-300 text-red-900 dark:text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 
                        @else border-gray-300 dark:border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 @enderror" 
                    :class="{'border-red-300 dark:border-red-300': !teacherId && teacherId !== undefined}"
                >
                    <option value="">Select a Teacher</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                    @endforeach
                </select>
                @error('teacherId')
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                @enderror
            </div>
            @error('teacherId')
                <p class="mt-2 text-sm text-red-600 dark:text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Session Field -->
        <div>
            <label for="session" class="block text-sm font-medium text-gray-700 dark:text-gray-700">
                Session <span class="text-red-500">*</span>
            </label>
            <div class="mt-1 relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <select 
                    id="session" 
                    wire:model.defer="session"
                    x-model="session"
                    class="block w-full pl-10 pr-10 focus:outline-none sm:text-sm rounded-md py-2.5 border dark:bg-white dark:text-gray-800
                        @error('session') border-red-300 text-red-900 dark:text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 
                        @else border-gray-300 dark:border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 @enderror" 
                    :class="{'border-red-300 dark:border-red-300': !session && session !== undefined}"
                >
                    <option value="">Select a Session</option>
                    @foreach($this->getYearsRange() as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
                @error('session')
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                @enderror
            </div>
            @error('session')
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
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 -ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
                </svg>
                Assign Dorm Master
            </button>
        </div>
    </form>
</div>
