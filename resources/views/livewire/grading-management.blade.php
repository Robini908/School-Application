<div x-data="{ 
    activeTab: 'list',
    showConfirmDelete: false,
    deleteId: null
}" class="bg-white rounded-lg shadow-sm overflow-hidden">

    <!-- Flash Messages -->
    <div class="px-6 py-2">
    <x-flash-messages />
    </div>

    <!-- Main Content Area -->
    <div class="px-6 py-4">
        @if ($isCreating || $isEditing)
            <!-- Grading System Form -->
            <div class="bg-white rounded-lg overflow-hidden">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        {{ $isEditing ? 'Edit Grading System' : 'Create New Grading System' }}
                    </h3>
                    <div class="mt-2 max-w-xl text-sm text-gray-500">
                        <p>{{ $isEditing ? 'Update the details of your grading system.' : 'Fill in the details to create a new grading system.' }}</p>
                    </div>
                    
                    <form wire:submit="{{ $isEditing ? 'update' : 'store' }}" class="mt-5 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <!-- Left Column: Grading System Fields -->
                        <div class="sm:col-span-3 space-y-6">
                            <!-- Grading System Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Grading System Name</label>
                                <div class="mt-1">
                                <input type="text" id="name" wire:model.live="name"
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <div class="mt-1">
                                    <textarea id="description" wire:model.live="description" 
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                        rows="3"></textarea>
                                </div>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Effective Date -->
                            <div>
                                <label for="effective_date" class="block text-sm font-medium text-gray-700">Effective Date</label>
                                <div class="mt-1">
                                <input type="date" id="effective_date" wire:model.live="effective_date"
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                                @error('effective_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            </div>

                        <!-- Right Column: Rules and Subjects -->
                        <div class="sm:col-span-3 space-y-6">
                            <!-- Rules -->
                            <div>
                                <label for="rules" class="block text-sm font-medium text-gray-700">Rules</label>
                                <div class="mt-1">
                                    <textarea id="rules" wire:model.live="rules" 
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                        rows="5"></textarea>
                                </div>
                                @error('rules')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                </div>

                            <!-- Subject Selection -->
                    <div>
                                <div class="flex items-center justify-between">
                                    <label class="block text-sm font-medium text-gray-700">Applicable Subjects</label>
                                    <span class="text-xs text-blue-600 font-medium">All subjects are selected</span>
                                </div>
                                <div class="mt-2 border border-gray-200 rounded-md p-3 bg-gray-50 max-h-48 overflow-y-auto">
                                    <div class="grid grid-cols-2 gap-2">
                                @foreach ($subjects as $subject)
                                            <div class="flex items-center" wire:key="subject-{{ $subject->id }}">
                                                <input type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                                    id="subject-{{ $subject->id }}"
                                                    value="{{ $subject->id }}" 
                                                    wire:model.live="selectedSubjects" 
                                                    checked
                                            disabled>
                                                <label for="subject-{{ $subject->id }}" class="ml-2 block text-sm text-gray-700 truncate">
                                                    {{ $subject->subject_name }}
                                        </label>
                                    </div>
                                @endforeach
                                    </div>
                            </div>
                            @error('selectedSubjects')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                    </div>
                </div>

                <!-- Form Buttons -->
                        <div class="sm:col-span-6 flex justify-end space-x-3">
                            <button type="button" wire:click="cancel" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Cancel
                            </button>
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                {{ $isEditing ? 'Update' : 'Create' }}
                                <span wire:loading wire:target="{{ $isEditing ? 'update' : 'store' }}" class="ml-2">
                                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </span>
                            </button>
                </div>
                    </form>
                </div>
            </div>
        @else
            <!-- Grading Systems List -->
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Name</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Description</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Effective Date</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Subjects</th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                @foreach ($gradingSystems as $gradingSystem)
                            <tr>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                {{ $gradingSystem->name }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    {!! nl2br(e($gradingSystem->description)) !!}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($gradingSystem->effective_date)->format('M d, Y') }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach ($gradingSystem->subjects->take(5) as $subject)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $subject->subject_name }}
                                </span>
                                        @endforeach
                                        @if($gradingSystem->subjects->count() > 5)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                +{{ $gradingSystem->subjects->count() - 5 }} more
                                            </span>
                                        @endif
                                </div>
                                </td>
                                <td class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <div class="flex space-x-2">
                                        <button wire:click="edit({{ $gradingSystem->id }})" class="text-gray-400 hover:text-blue-500 transition-colors duration-200">
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                </button>
                                        <button 
                                            @click="deleteId = {{ $gradingSystem->id }}; showConfirmDelete = true" 
                                            class="text-gray-400 hover:text-red-500 transition-colors duration-200">
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $gradingSystems->links() }}
            </div>
        @endif
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="showConfirmDelete" 
         x-cloak
         class="fixed z-10 inset-0 overflow-y-auto" 
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div x-show="showConfirmDelete" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                 aria-hidden="true"></div>

            <!-- Modal panel -->
            <div x-show="showConfirmDelete" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Delete Grading System
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to delete this grading system? This action cannot be undone.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" 
                            wire:click="delete()" 
                            x-on:click="showConfirmDelete = false"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Delete
                    </button>
                    <button type="button" 
                            @click="showConfirmDelete = false" 
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
            </div>

    <!-- Dirty Form Warning -->
    <div wire:dirty class="fixed bottom-4 right-4 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-md shadow-lg z-50">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-yellow-700">
                    You have unsaved changes. Don't forget to save!
                </p>
            </div>
        </div>
    </div>
</div>
