<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="p-4 border-b flex justify-between items-center">
        <h3 class="text-lg font-medium text-gray-800">
            <i class="fas fa-pause-circle text-yellow-500 mr-2"></i> 
            {{ $selectedStudent->status === 'suspended' ? 'Reinstate Student' : 'Suspend Student' }}
        </h3>
        <button wire:click="closeAction" class="text-gray-400 hover:text-gray-500 focus:outline-none transition-colors">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>
    
    <div class="p-6">
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        <strong>{{ $selectedStudent->first_name }} {{ $selectedStudent->last_name }}</strong> 
                        ({{ $selectedStudent->adm_no }}) from {{ $selectedStudent->my_class->name ?? 'N/A' }} - 
                        {{ $selectedStudent->section->name ?? 'N/A' }} admitted on {{ $selectedStudent->year_admitted }}
                    </p>
                </div>
            </div>
        </div>

        @if ($selectedStudent->status === 'suspended')
            <div class="text-center mb-6">
                <p class="text-gray-700 mb-4">Are you sure you want to reinstate this student? This will restore their normal access and privileges.</p>
                <div class="flex justify-center space-x-4">
                    <button wire:click="reinstateStudent" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <i class="fas fa-user-check mr-2"></i> Reinstate
                        <span wire:loading wire:target="reinstateStudent" class="ml-2">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                    </button>
                    <button wire:click="closeAction" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </button>
                </div>
            </div>
        @else
            <div class="space-y-6">
                <!-- Reason for Suspension -->
                <div>
                    <label for="suspensionReason" class="block text-sm font-medium text-gray-700">Reason for Suspension</label>
                    <div class="mt-1">
                        <textarea wire:model.live="suspensionReason" id="suspensionReason" rows="3" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"></textarea>
                        @error('suspensionReason')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Type of Suspension -->
                <div>
                    <label for="suspensionType" class="block text-sm font-medium text-gray-700">Type of Suspension</label>
                    <div class="mt-1">
                        <select wire:model.live="suspensionType" id="suspensionType" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <option value="">Select Type</option>
                            <option value="dismissal">Dismissal</option>
                            <option value="withdrawal">Withdrawal</option>
                            <option value="permanent_exclusion">Permanent Exclusion</option>
                        </select>
                        @error('suspensionType')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Duration of Suspension -->
                <div>
                    <label for="suspensionEndDate" class="block text-sm font-medium text-gray-700">Suspension End Date</label>
                    <div class="mt-1">
                        <input type="date" wire:model.live="suspensionEndDate" id="suspensionEndDate" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        @error('suspensionEndDate')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-between">
                    <button wire:click="confirmStudentSuspension" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                        <i class="fas fa-pause mr-2"></i> Suspend
                        <span wire:loading wire:target="confirmStudentSuspension" class="ml-2">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                    </button>
                    <button wire:click="closeAction" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </button>
                </div>
            </div>
        @endif
    </div>
</div> 