    <div x-data="{
    deadline: @entangle('deadline').live,
    countdown: '',
    timeRemaining: {
        weeks: 0,
        days: 0,
        hours: 0,
        minutes: 0,
        seconds: 0
    },
    showDeadlinePanel: true,
        updateCountdown() {
            const deadline = new Date(this.deadline);
            const now = new Date();
            const diffInMilliseconds = deadline - now;
    
            if (diffInMilliseconds <= 0) {
                this.countdown = 'Time\'s up!';
            this.timeRemaining = { weeks: 0, days: 0, hours: 0, minutes: 0, seconds: 0 };
                return;
            }
    
            const weeks = Math.floor(diffInMilliseconds / (1000 * 60 * 60 * 24 * 7));
            const days = Math.floor((diffInMilliseconds % (1000 * 60 * 60 * 24 * 7)) / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diffInMilliseconds % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((diffInMilliseconds % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diffInMilliseconds % (1000 * 60)) / 1000);
    
        this.timeRemaining = { weeks, days, hours, minutes, seconds };
            this.countdown = `${weeks}w ${days}d ${hours}h ${minutes}m ${seconds}s`;
        },
        init() {
            setInterval(() => {
                this.updateCountdown();
            }, 1000);
            this.updateCountdown(); // Initial update
        }
}" x-init="init()" class="w-full">

    <!-- Component Header with Close Icon -->
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-medium text-gray-900">Subject Selection Deadline</h3>
        <button wire:click="$dispatch('closeDeadlineComponent')" class="text-gray-400 hover:text-gray-500 focus:outline-none transition-colors" title="Close deadline component">
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    
    <div x-show="showDeadlinePanel" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95">

    <!-- Deadline Picker -->
    <div class="space-y-4">
        <div>
            <label for="deadline" class="block text-sm font-medium text-gray-700">Set Subject Selection Deadline</label>
            <div class="mt-1 relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <input type="datetime-local" 
                       id="deadline" 
                       wire:model.blur="deadline" 
                       class="pl-10 focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
            </div>
            <p class="mt-1 text-xs text-gray-500">Students will be able to select subjects until this date and time.</p>
        </div>
        
        <!-- Save Button -->
        <div>
            <button wire:click="setDeadline"
                    wire:loading.attr="disabled" 
                    wire:target="setDeadline"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg wire:loading.remove wire:target="setDeadline" class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <svg wire:loading wire:target="setDeadline" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span wire:loading.remove wire:target="setDeadline">Save Deadline</span>
                <span wire:loading wire:target="setDeadline">Saving...</span>
            </button>
        </div>
    </div>

    <!-- Countdown Display -->
        @if ($selectedClassCount > 0)
        <div class="mt-6">
            <h3 class="text-sm font-medium text-gray-700">Time Remaining for Subject Selection</h3>
            
            <!-- Modern Countdown Timer -->
            <div class="mt-3 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 border border-blue-100">
                <div class="grid grid-cols-5 gap-2 text-center">
                    <!-- Weeks -->
                    <div class="flex flex-col">
                        <div class="bg-white rounded-lg shadow-sm p-3 mb-1">
                            <span class="text-2xl font-bold text-blue-700" x-text="timeRemaining.weeks"></span>
                        </div>
                        <span class="text-xs font-medium text-gray-500">Weeks</span>
                    </div>
                    
                    <!-- Days -->
                    <div class="flex flex-col">
                        <div class="bg-white rounded-lg shadow-sm p-3 mb-1">
                            <span class="text-2xl font-bold text-blue-700" x-text="timeRemaining.days"></span>
                        </div>
                        <span class="text-xs font-medium text-gray-500">Days</span>
                    </div>
                    
                    <!-- Hours -->
                    <div class="flex flex-col">
                        <div class="bg-white rounded-lg shadow-sm p-3 mb-1">
                            <span class="text-2xl font-bold text-blue-700" x-text="timeRemaining.hours"></span>
                        </div>
                        <span class="text-xs font-medium text-gray-500">Hours</span>
                    </div>
                    
                    <!-- Minutes -->
                    <div class="flex flex-col">
                        <div class="bg-white rounded-lg shadow-sm p-3 mb-1">
                            <span class="text-2xl font-bold text-blue-700" x-text="timeRemaining.minutes"></span>
                        </div>
                        <span class="text-xs font-medium text-gray-500">Minutes</span>
                    </div>
                    
                    <!-- Seconds -->
                    <div class="flex flex-col">
                        <div class="bg-white rounded-lg shadow-sm p-3 mb-1">
                            <span class="text-2xl font-bold text-blue-700" x-text="timeRemaining.seconds"></span>
                        </div>
                        <span class="text-xs font-medium text-gray-500">Seconds</span>
                    </div>
                </div>
                
                <!-- Status Message -->
                <div class="mt-3 text-center">
                    <p class="text-sm text-blue-700">
                        <span x-show="deadline && new Date(deadline) > new Date()">
                            Students can select subjects until <span x-text="new Date(deadline).toLocaleString()"></span>
                        </span>
                        <span x-show="deadline && new Date(deadline) <= new Date()" class="text-red-600 font-medium">
                            The deadline has passed. Subject selection is now closed.
                        </span>
                        <span x-show="!deadline" class="text-amber-600 font-medium">
                            No deadline has been set yet.
                        </span>
                    </p>
                </div>
            </div>
        </div>
        @else
        <!-- No Classes Selected Message -->
        <div class="mt-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        No classes have been selected for subject selection. Please select at least one class before setting a deadline.
                    </p>
                </div>
            </div>
            </div>
        @endif
    </div>
    
    <!-- Hidden State Message -->
    <div x-show="!showDeadlinePanel" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="text-center py-6">
        <button @click="showDeadlinePanel = true" 
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Show Deadline Settings
        </button>
    </div>
</div>