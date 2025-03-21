<div x-data="{ activeTab: 'dorms', darkMode: localStorage.getItem('darkMode') === 'true' }" 
     :class="darkMode ? 'bg-gray-100' : 'bg-gray-50'"
     class="min-h-screen font-sans text-gray-900 transition-colors duration-200 ease-in-out">
    
    <!-- Main Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
        
        <!-- Header Section -->
        @if(!$isCreating && !$isEditing && !$isAssigningDormMaster && !$isViewingDormMasters && 
            !$isAddingStudents && !$showOccupancyCard && !$showStudentsList)
            @include('livewire.partials.dorms.header')
            @endif

        <!-- Toast Notification Container (positioned fixed) -->
        <div 
            x-data="{ toasts: [] }"
            @notify.window="toasts.push({id: Date.now(), message: $event.detail.message, type: $event.detail.type || 'info', duration: $event.detail.duration || 3000})"
            class="fixed top-4 right-4 z-50 flex flex-col space-y-4"
            aria-live="assertive"
        >
            <template x-for="(toast, index) in toasts" :key="toast.id">
                <div
                    x-show="toast"
                    x-transition:enter="transform ease-out duration-300 transition"
                    x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                    x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    x-init="setTimeout(() => { if (toast.duration > 0) { toasts.splice(index, 1) } }, toast.duration)"
                    class="max-w-sm w-full shadow-lg rounded-lg pointer-events-auto overflow-hidden"
                    :class="{
                        'bg-white dark:bg-gray-50 border-l-4 border-green-500': toast.type === 'success',
                        'bg-white dark:bg-gray-50 border-l-4 border-emerald-500': toast.type === 'info',
                        'bg-white dark:bg-gray-50 border-l-4 border-yellow-500': toast.type === 'warning',
                        'bg-white dark:bg-gray-50 border-l-4 border-red-500': toast.type === 'danger',
                        'bg-white dark:bg-gray-50 border-l-4 border-purple-500': toast.type === 'debug'
                    }"
                >
                    <div class="p-4 flex">
                        <div class="flex-shrink-0" :class="{
                            'text-green-500': toast.type === 'success',
                            'text-emerald-500': toast.type === 'info',
                            'text-yellow-500': toast.type === 'warning',
                            'text-red-500': toast.type === 'danger',
                            'text-purple-500': toast.type === 'debug'
                        }">
                            <!-- Success Icon -->
                            <svg x-show="toast.type === 'success'" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            
                            <!-- Info Icon -->
                            <svg x-show="toast.type === 'info'" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            
                            <!-- Warning Icon -->
                            <svg x-show="toast.type === 'warning'" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            
                            <!-- Error Icon -->
                            <svg x-show="toast.type === 'danger'" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            
                            <!-- Debug Icon -->
                            <svg x-show="toast.type === 'debug'" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        
                        <div class="ml-3 w-0 flex-1">
                            <p class="text-sm font-medium" x-text="toast.message" :class="darkMode ? 'text-gray-800' : 'text-gray-900'"></p>
                        </div>
                        
                        <div class="ml-4 flex-shrink-0 flex">
                            <button 
                                @click="toasts.splice(index, 1)"
                                class="inline-flex rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500"
                            >
                                <span class="sr-only">Close</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Content Area -->
        <div class="mt-4">
            <!-- Dorm Table/Empty State -->
            @if(!$isCreating && !$isEditing && !$isAssigningDormMaster && !$isViewingDormMasters && 
                !$isAddingStudents && !$showOccupancyCard && !$showStudentsList)
                @if($dorms->count() > 0)
                    @include('livewire.partials.dorms.table')
                @else
                    @include('livewire.partials.dorms.empty-state')
                @endif
            @endif

            <!-- Create/Edit Form -->
            @if($isCreating || $isEditing)
                @include('livewire.partials.dorms.create-edit-form')
            @endif

            <!-- Assign Dorm Master Form -->
            @if($isAssigningDormMaster)
                @include('livewire.partials.dorms.dorm-master')
            @endif

            <!-- View Dorm Masters List -->
            @if($isViewingDormMasters)
                @include('livewire.partials.dorms.view-masters')
            @endif

            <!-- Add Students Form -->
            @if($isAddingStudents)
                @include('livewire.partials.dorms.add-students')
            @endif
            
            <!-- Occupancy Chart -->
            @if($showOccupancyCard && !$showStudentsList)
                @include('livewire.partials.dorms.occupancy-card')
            @endif

            <!-- Students List -->
            @if($showStudentsList)
                @include('livewire.partials.dorms.students-list')
            @endif
        </div>
    </div>
</div>

@script
    <script>
        // Register event listeners for toast notifications
        document.addEventListener('DOMContentLoaded', () => {
            // Listen for Livewire events
            Livewire.on('alert', ({type, message, options = {}}) => {
                const event = new CustomEvent('notify', {
                    detail: {
                        message: message,
                        type: type,
                        duration: options.duration || 3000
                    }
                });
                window.dispatchEvent(event);
            });
            
            // Infinite scroll for students list
            const studentsListContainer = document.querySelector('.students-list-container');
            if (studentsListContainer) {
                studentsListContainer.addEventListener('scroll', function() {
                    if (studentsListContainer.scrollTop + studentsListContainer.clientHeight >= studentsListContainer.scrollHeight - 10) {
                    @this.loadMore();
                }
            });
        }
        });
    </script>
@endscript
