<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="p-4 border-b flex justify-between items-center">
        <h3 class="text-lg font-medium text-gray-800">
            <i class="fas fa-envelope text-blue-500 mr-2"></i> Send Email to {{ $selectedStudent->first_name }} {{ $selectedStudent->last_name }} ({{ $selectedStudent->adm_no }})
        </h3>
        <button wire:click="closeAction" class="text-gray-400 hover:text-gray-500 focus:outline-none transition-colors">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>
    
    <div class="p-6">
        <!-- TinyMCE Editor for Email Content -->
        <div class="mb-6">
            <label for="tinyMCE" class="block text-sm font-medium text-gray-700 mb-1">Email Content:</label>
            <div class="mt-1">
                <textarea id="tinyMCE" wire:model="notificationContent" rows="6" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"></textarea>
                @error('notificationContent')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- File Upload -->
        <div class="mb-6">
            <label for="file" class="block text-sm font-medium text-gray-700 mb-1">Attach a File:</label>
            <div class="mt-1 flex items-center">
                <label class="w-full flex items-center px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 cursor-pointer">
                    <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                    </svg>
                    <span>Choose file</span>
                    <input id="file" type="file" wire:model.live="file" class="sr-only">
                </label>
            </div>
            @error('file')
                <p class="mt-1 text-sm text-red-600">{{ $message ?? 'Invalid file' }}</p>
            @enderror
            
            @if($file)
                <div class="mt-2 flex items-center text-sm text-gray-500">
                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>File selected: {{ $file->getClientOriginalName() }}</span>
                </div>
            @endif
        </div>

        <!-- Buttons -->
        <div class="flex justify-end">
            <button wire:click="sendStudentMail({{ $selectedStudent->id }})" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-paper-plane mr-2"></i> Send Mail
                <span wire:loading wire:target="sendStudentMail({{ $selectedStudent->id }})" class="ml-2">
                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </span>
            </button>
        </div>
    </div>
</div> 