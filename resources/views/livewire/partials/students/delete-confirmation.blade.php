<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="p-4 border-b flex justify-between items-center">
        <h3 class="text-lg font-medium text-gray-800">
            <i class="fas fa-trash text-red-500 mr-2"></i> Confirm Deletion
        </h3>
        <button wire:click="closeAction" class="text-gray-400 hover:text-gray-500 focus:outline-none transition-colors">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>
    
    <div class="p-6">
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        You're about to delete <strong>{{ $selectedStudent->first_name }} {{ $selectedStudent->middle_name }} {{ $selectedStudent->last_name }}</strong> (Admission No: {{ $selectedStudent->adm_no }}).
                        This will permanently remove all financial records, exams, and everything associated with this student from our system.
                    </p>
                </div>
            </div>
        </div>
        
        <p class="text-sm text-red-600 font-semibold mb-6">
            Please confirm this action. Once deleted, it cannot be undone.
        </p>
        
        <div class="flex justify-between">
            <button wire:click="confirmDelete" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                Yes, Delete
                <span wire:loading wire:target="confirmDelete" class="ml-2">
                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </span>
            </button>
            <button wire:click="cancelDelete" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Cancel
                <span wire:loading wire:target="cancelDelete" class="ml-2">
                    <svg class="animate-spin h-4 w-4 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </span>
            </button>
        </div>
    </div>
</div> 