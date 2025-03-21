<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <!-- Form Header -->
    <div class="px-6 py-4 border-b border-gray-200 bg-white">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="bg-indigo-100 rounded-full p-2 mr-3">
                    <svg class="w-6 h-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-medium text-gray-900">{{ $isEditing ? 'Edit Subject' : 'Create New Subject' }}</h2>
                    <p class="mt-1 text-sm text-gray-500">{{ $isEditing ? 'Update the subject details' : 'Fill in the details to create a new subject' }}</p>
                </div>
            </div>
            <button wire:click="cancel" 
                    class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="-ml-0.5 mr-2 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to List
            </button>
        </div>
    </div>
    
    <!-- Form Content -->
    <div class="px-6 py-5 bg-white">
        <form wire:submit="{{ $isEditing ? 'update' : 'store' }}">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Subject Name -->
                <div>
                    <label for="subject_name" class="block text-sm font-medium text-gray-700">Subject Name</label>
                    <div class="mt-1">
                        <input type="text" 
                               wire:model.live="subject_name" 
                               id="subject_name" 
                               class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                               placeholder="Enter subject name">
                    </div>
                    @error('subject_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Subject Code -->
                <div>
                    <label for="subject_code" class="block text-sm font-medium text-gray-700">Subject Code</label>
                    <div class="mt-1">
                        <input type="text" 
                               wire:model.live="subject_code" 
                               id="subject_code" 
                               class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                               placeholder="Enter subject code">
                    </div>
                    @error('subject_code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Abbreviation -->
                <div>
                    <label for="abbreviation" class="block text-sm font-medium text-gray-700">Abbreviation</label>
                    <div class="mt-1">
                        <input type="text" 
                               wire:model.live="abbreviation" 
                               id="abbreviation" 
                               class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                               placeholder="Enter abbreviation">
                    </div>
                    @error('abbreviation')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Category Selection -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                    <div class="mt-1">
                        <select wire:model.live="category_id" 
                                id="category_id" 
                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <option value="">Select a Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Category Management Section -->
            @if (!$isEditing)
                <div class="mt-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-medium text-gray-700">Need to add a new category?</h3>
                        <button type="button" 
                                wire:click="$toggle('showNewCategoryForm')" 
                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ $showNewCategoryForm ? 'Hide' : 'Manage Categories' }}
                        </button>
                    </div>
                </div>
            @endif
            
            <!-- Category Management Form -->
            @if ($showNewCategoryForm && !$isEditing)
                <div class="mt-4 p-4 bg-gray-50 rounded-md border border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-medium text-gray-700">Manage Categories</h3>
                        <button type="button" 
                                wire:click="$set('showNewCategoryForm', false)" 
                                class="inline-flex items-center p-1.5 border border-transparent rounded-full text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Add New Category -->
                        <div>
                            <label for="new_category" class="block text-sm font-medium text-gray-700">New Category Name</label>
                            <div class="mt-1 flex">
                                <input type="text" 
                                       wire:model.live="new_category" 
                                       id="new_category" 
                                       class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                       placeholder="Enter new category name">
                                <button type="button" 
                                        wire:click="addCategory" 
                                        class="ml-3 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <span wire:loading.remove wire:target="addCategory">Save</span>
                                    <span wire:loading wire:target="addCategory">
                                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </span>
                                </button>
                            </div>
                            @error('new_category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Category List -->
                        <div>
                            <h4 class="block text-sm font-medium text-gray-700 mb-2">Available Categories</h4>
                            <div class="bg-white border border-gray-200 rounded-md shadow-sm max-h-40 overflow-y-auto">
                                <ul class="divide-y divide-gray-200">
                                    @forelse ($categories as $category)
                                        <li class="px-4 py-2 flex items-center justify-between text-sm">
                                            <span class="text-gray-700">{{ $category->name }}</span>
                                            <button type="button" 
                                                    wire:click="deleteCategory({{ $category->id }})" 
                                                    class="text-red-600 hover:text-red-900 focus:outline-none">
                                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </li>
                                    @empty
                                        <li class="px-4 py-3 text-sm text-gray-500 text-center">No categories available</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Form Actions -->
            <div class="mt-6 flex items-center justify-between">
                <div>
                    @if (session()->has('message'))
                        <div class="text-sm text-green-600">
                            <svg class="inline-block h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            {{ session('message') }}
                        </div>
                    @endif
                    
                    <div wire:dirty class="text-sm text-amber-600">
                        <svg class="inline-block h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        Unsaved changes...
                    </div>
                </div>
                
                <div class="flex space-x-3">
                    <button type="button" 
                            wire:click="cancel" 
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ $isEditing ? 'Update' : 'Save' }}
                        <span wire:loading wire:target="store,update" class="ml-2">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div> 