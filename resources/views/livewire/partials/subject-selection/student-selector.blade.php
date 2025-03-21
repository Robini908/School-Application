@if ($selectedSection && !$unassignedStudents->isEmpty())
<!-- Student Selection Card -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6" 
     x-data="{ selectedCount: {{ count($selectedStudents) }}, totalCount: {{ $studentCount ?? 0 }} }" 
     x-init="
        document.addEventListener('livewire:initialized', () => {
            $watch('$wire.selectedStudents', value => {
                selectedCount = value ? value.length : 0;
            });
        });
     ">
    <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex justify-between items-center">
        <h3 class="text-base font-medium text-gray-900 flex items-center">
            <svg class="w-5 h-5 text-blue-600 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            Student Selection
        </h3>
        <span class="bg-blue-100 text-blue-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded">
            <span x-text="selectedCount"></span> / <span x-text="totalCount"></span> Selected
        </span>
    </div>
    
    <div class="p-4">
        <!-- Status Banner -->
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-md mb-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        There are <span class="font-semibold">{{ $studentCount }}</span> students in 
                        <span class="font-semibold text-green-700">{{ $classes->where('id', $selectedClass)->first()->name ?? 'Selected Class' }}</span> - 
                        <span class="font-semibold text-blue-600">{{ $sections->where('id', $selectedSection)->first()->name ?? 'Selected Section' }}</span>
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Selection Controls -->
        <div class="flex flex-wrap gap-2 mb-4">
            <button wire:click="selectAllStudents" 
                    wire:loading.attr="disabled" 
                    wire:target="selectAllStudents"
                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50">
                <svg wire:loading.remove wire:target="selectAllStudents" class="mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <svg wire:loading wire:target="selectAllStudents" class="animate-spin mr-1.5 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Select All</span>
            </button>
            
            <div class="inline-flex rounded-md shadow-sm">
                <input type="number" wire:model.live="numToSelect" placeholder="Number" min="1" max="{{ $studentCount }}"
                       class="border border-gray-300 rounded-l-md text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500">
                <button wire:click="selectSpecificStudents" 
                        wire:loading.attr="disabled" 
                        wire:target="selectSpecificStudents"
                        class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50">
                    <span wire:loading.remove wire:target="selectSpecificStudents">Select</span>
                    <svg wire:loading wire:target="selectSpecificStudents" class="animate-spin h-4 w-4 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </div>
            
            <button x-show="selectedCount > 0"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    wire:click="clearSelection" 
                    wire:loading.attr="disabled" 
                    wire:target="clearSelection"
                    class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50">
                <svg wire:loading.remove wire:target="clearSelection" class="mr-1.5 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <svg wire:loading wire:target="clearSelection" class="animate-spin mr-1.5 h-4 w-4 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Clear Selection</span>
            </button>
        </div>

        <!-- Student Grid -->
        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
            <h4 class="text-base font-medium text-gray-800 mb-3">Students without subject selection</h4>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach ($unassignedStudents as $student)
                    <label for="student-{{ $student->id }}" class="flex items-center p-3 rounded-lg border cursor-pointer bg-white hover:bg-blue-50 transition-colors {{ in_array($student->id, $selectedStudents) ? 'border-blue-500 ring-2 ring-blue-200' : 'border-gray-200' }}">
                        <input type="checkbox" id="student-{{ $student->id }}" wire:model.live="selectedStudents" value="{{ $student->id }}" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <div class="ml-3">
                            <div class="text-sm font-medium text-gray-900">{{ $student->first_name }} {{ $student->last_name }}</div>
                            <div class="text-xs text-gray-500">Adm No: {{ $student->adm_no }}</div>
                        </div>
                    </label>
                @endforeach
            </div>
            
            <!-- Action Buttons -->
            <div class="mt-4 flex justify-center">
                <button wire:click="showSubjectFormForStudents" 
                        wire:loading.attr="disabled" 
                        wire:target="showSubjectFormForStudents"
                        x-bind:disabled="selectedCount === 0"
                        x-bind:class="{'opacity-50 cursor-not-allowed': selectedCount === 0}"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg wire:loading.remove wire:target="showSubjectFormForStudents" class="mr-1.5 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    <svg wire:loading wire:target="showSubjectFormForStudents" class="animate-spin mr-1.5 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Assign Subjects to Selected Students</span>
                </button>
            </div>
        </div>
    </div>
</div>
@elseif ($selectedSection)
<!-- No Unassigned Students Banner -->
<div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg shadow-sm mb-6">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm font-medium text-green-800">
                All students in this section have been assigned subjects. Well done!
            </p>
        </div>
    </div>
</div>
@endif 