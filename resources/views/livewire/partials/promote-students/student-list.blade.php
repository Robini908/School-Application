<div>
    <h2 class="text-lg font-medium text-gray-800 mb-2">Select Students</h2>
    <p class="text-sm text-gray-600 mb-6">Choose the students you want to transition to the selected class.</p>
    
    
    @if ($selectedSection)
        <!-- Search bar with Google-inspired design -->
        <div class="mb-6 relative">
            <div class="relative flex w-full flex-wrap items-stretch">
                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                    <i class="fas fa-search text-blue-500"></i>
                </div>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search students by name or admission number..."
                    class="pl-12 py-3 pr-10 block w-full rounded-lg border-gray-300 bg-gray-50 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm hover:bg-white transition-colors duration-200"
                />
                @if($search)
                    <button 
                        wire:click="$set('search', '')" 
                        class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600"
                        aria-label="Clear search"
                    >
                        <i class="fas fa-times-circle text-lg"></i>
                    </button>
                @endif
            </div>
            
            <!-- Search status indicator -->
            @if($search)
                <div class="absolute top-full left-0 mt-1 text-xs text-gray-500 flex items-center">
                    <span>
                        @if($students->total() > 0)
                            Found {{ $students->total() }} results
                        @else
                            No results found
                        @endif
                        for "<span class="font-medium text-blue-600">{{ $search }}</span>"
                    </span>
                    <button 
                        wire:click="$set('search', '')" 
                        class="ml-2 text-blue-500 hover:text-blue-700 underline text-xs"
                    >
                        Clear search
                    </button>
                </div>
            @endif
        </div>

        <!-- Loading indicator during search or page changes -->
        <div wire:loading.delay wire:target="search, selectedStudents, gotoPage" class="mb-4">
            <div class="flex items-center justify-center bg-blue-50 text-blue-600 p-2 rounded-md border border-blue-100">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Updating student list...</span>
            </div>
        </div>

        @if ($students->isEmpty())
            <div class="rounded-lg {{ $search ? 'bg-yellow-50' : 'bg-blue-50' }} p-6 mb-4 shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-{{ $search ? 'search' : 'info-circle' }} text-{{ $search ? 'yellow' : 'blue' }}-400 text-lg"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-{{ $search ? 'yellow' : 'blue' }}-800 font-medium">
                            @if ($search)
                                No matching students found
                            @else
                                No students available for transition
                            @endif
                        </h3>
                        <p class="mt-2 text-sm text-{{ $search ? 'yellow' : 'blue' }}-700">
                            @if ($search)
                                Your search for "<span class="font-medium">{{ $search }}</span>" did not match any students. Try using different keywords or check the spelling.
                            @else
                                All students in this section have already been transitioned for the selected year.
                            @endif
                        </p>
                        @if ($search)
                            <button 
                                wire:click="$set('search', '')" 
                                class="mt-3 inline-flex items-center px-3 py-1.5 border border-yellow-300 shadow-sm text-xs font-medium rounded-full text-yellow-700 bg-yellow-50 hover:bg-yellow-100"
                            >
                                <i class="fas fa-times-circle mr-1"></i>
                                Clear search
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200 transition-all duration-200 hover:shadow-lg">
                <!-- Header with stats and actions -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex flex-wrap items-center justify-between">
                    <div class="text-sm text-gray-700 flex items-center">
                        <span class="bg-blue-100 text-blue-800 font-medium px-2.5 py-0.5 rounded-full text-xs flex items-center">
                            <i class="fas fa-users mr-1"></i>
                            {{ $students->total() }}
                        </span>
                        <span class="ml-2">students available</span>
                        @if(count($selectedStudents) > 0)
                            <span class="mx-2 text-gray-400">•</span>
                            <span class="bg-green-100 text-green-800 font-medium px-2.5 py-0.5 rounded-full text-xs flex items-center">
                                <i class="fas fa-check mr-1"></i>
                                {{ count($selectedStudents) }}
                            </span>
                            <span class="ml-2">selected</span>
                        @endif
                    </div>
                    <div class="flex items-center space-x-3">
                        @if (count($selectedStudents) > 0)
                            <button type="button" 
                                    class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                                    wire:click="$set('selectedStudents', [])">
                                <i class="fas fa-times mr-1.5"></i> Clear Selection
                            </button>
                        @endif
                        <button type="button" 
                                class="inline-flex items-center px-3 py-1.5 border border-blue-300 shadow-sm text-xs font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                                wire:click="$set('selectedStudents', {{ $students->pluck('id') }})">
                            <i class="fas fa-check-double mr-1.5"></i> Select All
                        </button>
                    </div>
                </div>
                
                <!-- Table -->
                <div class="overflow-hidden overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-50">
                                <th scope="col" class="w-16 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <input type="checkbox" 
                                               id="select-all-checkbox"
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded transition-colors duration-200"
                                               wire:click="$set('selectedStudents', {{ $students->count() === count($selectedStudents) ? '[]' : $students->pluck('id') }})"
                                               {{ $students->count() === count($selectedStudents) && $students->count() > 0 ? 'checked' : '' }}>
                                        <label for="select-all-checkbox" class="ml-2 text-xs text-gray-700 font-medium cursor-pointer">
                                            {{ $students->count() === count($selectedStudents) && $students->count() > 0 ? 'Deselect' : 'Select' }}
                                        </label>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Student
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Admission No
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($students as $student)
                                <tr class="hover:bg-blue-50 transition-colors duration-200 group" wire:key="student-{{ $student->id }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <input 
                                                type="checkbox" 
                                                id="student-{{ $student->id }}"
                                                wire:model.live="selectedStudents" 
                                                value="{{ $student->id }}" 
                                                class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded cursor-pointer transition-colors duration-200">
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <label for="student-{{ $student->id }}" class="cursor-pointer block">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 {{ in_array($student->id, $selectedStudents) ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-600' }} rounded-full flex items-center justify-center transition-colors duration-200 shadow-sm">
                                                    <span class="font-medium">{{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}</span>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium {{ in_array($student->id, $selectedStudents) ? 'text-blue-900' : 'text-gray-900' }} group-hover:text-blue-700 transition-colors duration-200">
                                                        {{ $student->first_name }} {{ $student->last_name }}
                                                    </div>
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        <span class="inline-flex items-center">
                                                            <i class="fas fa-user-graduate mr-1 text-gray-400"></i>
                                                            Student ID: {{ $student->id }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <span class="text-sm font-medium {{ in_array($student->id, $selectedStudents) ? 'text-blue-700' : 'text-gray-700' }} transition-colors duration-200">
                                            {{ $student->adm_no }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination with search parameter preservation -->
                <div class="bg-white px-6 py-4 border-t border-gray-200">
                    {{ $students->links() }}
                </div>
            </div>
            
            <!-- Selected students summary -->
            @if(count($selectedStudents) > 0)
                <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-100 shadow-sm flex justify-between items-center">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 rounded-full p-2">
                            <i class="fas fa-check text-blue-600"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-blue-800">
                                {{ count($selectedStudents) }} student{{ count($selectedStudents) > 1 ? 's' : '' }} selected for transition
                            </p>
                        </div>
                    </div>
                    <button 
                        type="button"
                        class="inline-flex items-center px-3 py-1 border border-blue-300 shadow-sm text-xs font-medium rounded-full text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-400"
                        wire:click="$set('selectedStudents', [])"
                    >
                        <i class="fas fa-times-circle mr-1"></i>
                        Clear selection
                    </button>
                </div>
            @endif
        @endif
    @else
        <div class="rounded-lg bg-blue-50 p-6 shadow-sm border border-blue-100">
            <div class="flex">
                <div class="flex-shrink-0 bg-blue-100 rounded-full p-3">
                    <i class="fas fa-info-circle text-blue-600 text-xl"></i>
                </div>
                <div class="ml-5">
                    <h3 class="text-lg font-medium text-blue-800">Select Class & Section</h3>
                    <div class="mt-2 text-base text-blue-700">
                        <p>Please select a class and section first to view the list of students available for transition.</p>
                    </div>
                    <div class="mt-4">
                        <button type="button" 
                                @click="activeStep = 2" 
                                class="inline-flex items-center px-4 py-2 border border-blue-300 shadow-sm text-sm font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Go to Class Selection
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div> 