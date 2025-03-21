<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="p-4 border-b flex justify-between items-center">
        <h3 class="text-lg font-medium text-gray-800">
            <i class="fas fa-check-circle text-green-500 mr-2"></i> Approve Student
        </h3>
        <button wire:click="closeAction" class="text-gray-400 hover:text-gray-500 focus:outline-none transition-colors">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>
    
    <div class="p-6">
        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800">Approval Confirmation</h3>
                    <div class="mt-2 text-sm text-green-700">
                        <p>Please confirm that you want to approve <strong>{{ $selectedStudent->first_name }} {{ $selectedStudent->last_name }}</strong> who is enrolled in <strong>{{ $selectedStudent->my_class->name ?? 'N/A' }}</strong> - <strong>{{ $selectedStudent->section->name ?? 'N/A' }}</strong>.</p>
                        <p class="mt-2">This student was admitted on <strong>{{ $selectedStudent->year_admitted }}</strong>.</p>
                        <p class="mt-2">Their email address is <strong>{{ $selectedStudent->email ?? 'N/A' }}</strong>, and their parent is <strong>{{ $selectedStudent->parent_detail->parent_first_name ?? 'N/A' }} {{ $selectedStudent->parent_detail->parent_last_name ?? 'N/A' }}</strong>.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div x-data="{ showDisapprovalForm: false }" class="space-y-6">
            <div class="flex justify-between">
                <button wire:click="confirmApproval" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <i class="fas fa-check mr-2"></i> Approve
                    <span wire:loading wire:target="confirmApproval" class="ml-2">
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                </button>
                <button @click="showDisapprovalForm = !showDisapprovalForm" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <i class="fas fa-times mr-2"></i> Disapprove
                </button>
            </div>
            
            <div x-show="showDisapprovalForm" x-transition:enter="transition ease-out duration-200" 
                 x-transition:enter-start="opacity-0 transform scale-95" 
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-100" 
                 x-transition:leave-start="opacity-100 transform scale-100" 
                 x-transition:leave-end="opacity-0 transform scale-95" 
                 class="mt-4 p-4 bg-red-50 rounded-md">
                <h4 class="text-sm font-medium text-red-800 mb-2">Reason for Disapproval</h4>
                <div class="mt-1">
                    <textarea wire:model.live="disapprovalReason" rows="3" class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Please provide a reason for disapproval..."></textarea>
                    @error('disapprovalReason')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-4 flex justify-end">
                    <button wire:click="cancelApproval" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Confirm Disapproval
                        <span wire:loading wire:target="cancelApproval" class="ml-2">
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