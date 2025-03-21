<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="p-4 border-b flex justify-between items-center">
        <h3 class="text-lg font-medium text-gray-800">
            <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i> Student Expulsion
        </h3>
        <button wire:click="closeAction" class="text-gray-400 hover:text-gray-500 focus:outline-none transition-colors">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>
    
    <div class="p-6">
        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Warning: Permanent Action</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <p>You are about to expel <strong>{{ $selectedStudent->first_name }} {{ $selectedStudent->last_name }}</strong> ({{ $selectedStudent->adm_no }}) from {{ $selectedStudent->my_class->name ?? 'N/A' }} - {{ $selectedStudent->section->name ?? 'N/A' }}.</p>
                        <p class="mt-2">This is a serious disciplinary action that will permanently remove the student from the school.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="space-y-6">
            <!-- Expulsion form will go here -->
            <p class="text-center text-gray-700">Expulsion functionality is currently under development.</p>
            
            <div class="flex justify-end">
                <button wire:click="closeAction" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Close
                </button>
            </div>
        </div>
    </div>
</div> 