<div x-data="{ isReinstating: @entangle('isReinstating'), isExtendingSuspension: @entangle('isExtendingSuspension') }" 
     class="bg-white rounded-lg shadow-sm overflow-hidden" 
     wire:poll.10s>
    
    <div class="p-6">
        <!-- Header Section -->
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-medium text-gray-900 flex items-center">
                <svg class="w-5 h-5 mr-2 text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                Suspension Management
            </h2>
        </div>

        <!-- Main Content -->
        <div wire:poll.1s="checkSuspensions">
            <!-- Suspended Students Grid -->
            <div x-show="!isReinstating && !isExtendingSuspension" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100">
                
                @if($suspendedStudents->isEmpty())
                    <div class="bg-yellow-50 rounded-lg p-8 text-center">
                        <svg class="mx-auto h-12 w-12 text-yellow-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-yellow-800">No suspended students</h3>
                        <p class="mt-2 text-sm text-yellow-700">
                            There are currently no students under suspension. Suspended students will appear here.
                        </p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($suspendedStudents as $student)
                            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-300">
                                <!-- Card Header -->
                                <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                                    <div class="flex items-center">
                                        <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-yellow-100 text-yellow-500 mr-3">
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </span>
        <div>
                                            <h3 class="text-sm font-medium text-gray-900">{{ $student->first_name }} {{ $student->last_name }}</h3>
                                            <p class="text-xs text-gray-500">{{ $student->adm_no }}</p>
                                        </div>
                                    </div>
                                    <button wire:click="downloadStudentSuspension({{ $student->id }})" 
                                            class="inline-flex items-center p-1.5 border border-transparent rounded-full shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                            title="Download Suspension PDF">
                                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                        </svg>
                                        <span class="sr-only">Download</span>
                                    </button>
                                </div>
                                
                                <!-- Card Body -->
                                <div class="p-4">
                                    <div class="mb-4">
                                        <div class="text-sm text-gray-600 mb-2">
                                            <span class="font-medium text-gray-900">Reason:</span> 
                                            <span class="text-red-600 font-medium">{{ $student->suspension_reason }}</span>
                                        </div>
                                        <div class="text-sm text-gray-600 mb-2">
                                            <span class="font-medium text-gray-900">Type:</span> 
                                            <span class="capitalize">{{ $student->suspension_type }}</span>
                                        </div>
                                        <div class="text-sm text-gray-600 mb-2">
                                            <span class="font-medium text-gray-900">Suspended on:</span> 
                                            <span>{{ $student->suspension_date ? $student->suspension_date->format('M j, Y') : 'N/A' }}</span>
                                        </div>
                                        <div class="text-sm text-gray-600">
                                            <span class="font-medium text-gray-900">Resumption date:</span> 
                                            <span>{{ $student->suspension_end_date ? $student->suspension_end_date->format('M j, Y') : 'N/A' }}</span>
                                </div>
                            </div>
        
                                    <!-- Countdown Box -->
                                    <div class="bg-blue-50 rounded-md p-3 mb-4">
                                        <div class="flex justify-between items-center text-xs text-blue-800">
                                            <div>
                                                <span class="font-medium">Remaining:</span>
                                                <span class="ml-1 text-blue-600">
                                        @if ($student->suspension_end_date)
                                                        {{ $this->humanReadableCountdown($student->suspension_end_date) }}
                                        @else
                                                        N/A
                                        @endif
                                                </span>
                                            </div>
                                            <div>
                                                <span class="font-medium">Elapsed:</span>
                                                <span class="ml-1">
                                        @if ($student->suspension_date)
                                                        {{ $this->humanReadableElapsedTime($student->suspension_date) }}
                                        @else
                                                        N/A
                                        @endif
                                                </span>
                                            </div>
                                        </div>
                                </div>
        
                                    <!-- Action Buttons -->
                                    <div class="flex justify-between">
                                        <button wire:click="reinstate({{ $student->id }})"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                            <svg class="mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Reinstate
                                            <span wire:loading wire:target="reinstate({{ $student->id }})" class="ml-1">
                                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </span>
                                        </button>
        
                                        <button wire:click="extendSuspension({{ $student->id }})"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <svg class="mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                            Extend
                                            <span wire:loading wire:target="extendSuspension({{ $student->id }})" class="ml-1">
                                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            
            <!-- Reinstate Student Form -->
            <div x-show="isReinstating" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-95"
                 class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                
                <div class="px-4 py-3 bg-green-50 border-b border-green-200">
                    <h3 class="text-lg font-medium text-green-800 flex items-center">
                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Reinstate Student
                    </h3>
                </div>
                
                <div class="p-6">
                    <div class="bg-green-50 rounded-md p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">
                                    Are you sure you want to reinstate this student? This will remove all suspension records and allow the student to resume normal activities.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button wire:click="$set('isReinstating', false)" 
                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </button>
                        
                        <button wire:click="confirmReinstatement" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Yes, Reinstate
                            <span wire:loading wire:target="confirmReinstatement" class="ml-2">
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Extend Suspension Form -->
            <div x-show="isExtendingSuspension" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-95"
                 class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                
                <div class="px-4 py-3 bg-blue-50 border-b border-blue-200">
                    <h3 class="text-lg font-medium text-blue-800 flex items-center">
                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Extend Suspension Period
                    </h3>
                </div>
                
                <div class="p-6">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Current Suspension End Date</label>
                        <div class="bg-gray-100 rounded-md p-3 text-sm text-gray-800 font-medium">
                            {{ $student ? ($student->suspension_end_date ? $student->suspension_end_date->format('l, F j, Y') : 'N/A') : 'Student not found.' }}
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label for="newSuspensionEndDate" class="block text-sm font-medium text-gray-700 mb-1">New Suspension End Date</label>
                        <input type="date" 
                               wire:model.live="newSuspensionEndDate" 
                               id="newSuspensionEndDate"
                               class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                               min="{{ now()->toDateString() }}">
                        @error('newSuspensionEndDate')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button wire:click="$set('isExtendingSuspension', false)" 
                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </button>
                        
                        <button wire:click="confirmExtension" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Confirm Extension
                            <span wire:loading wire:target="confirmExtension" class="ml-2">
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                            </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
