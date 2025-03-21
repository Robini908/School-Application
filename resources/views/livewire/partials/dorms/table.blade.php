<!-- Dorms Table Component -->
<div class="bg-white dark:bg-gray-50 rounded-lg shadow-sm overflow-hidden">
    <!-- Search and Filters -->
    <div class="p-4 sm:px-6 border-b border-gray-200 dark:border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <!-- Left side - Search -->
        <div class="relative rounded-md shadow-sm max-w-md w-full">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </div>
            <input type="text" placeholder="Search dormitories..." class="block w-full pl-10 sm:text-sm border-gray-300 dark:border-gray-300 rounded-md focus:ring-emerald-500 focus:border-emerald-500 dark:bg-white dark:text-gray-800 dark:placeholder-gray-500">
        </div>
        
        <!-- Right side - Filter buttons -->
        <div class="flex items-center space-x-2">
            <span class="text-sm text-gray-500 dark:text-gray-600">Sort by:</span>
            <button class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-300 rounded-md text-sm font-medium text-gray-700 dark:text-gray-700 bg-white dark:bg-white hover:bg-gray-50 dark:hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                Name
                <svg class="ml-1.5 h-4 w-4 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
            <button class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-300 rounded-md text-sm font-medium text-gray-700 dark:text-gray-700 bg-white dark:bg-white hover:bg-gray-50 dark:hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                Capacity
                <svg class="ml-1.5 h-4 w-4 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-200">
            <thead class="bg-gray-50 dark:bg-gray-100">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-600 uppercase tracking-wider">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-600 uppercase tracking-wider">
                        Capacity
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-600 uppercase tracking-wider">
                        Occupancy ({{ date('Y') }})
                    </th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-600 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-white divide-y divide-gray-200 dark:divide-gray-200">
                @foreach($dorms as $dorm)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-50 transition-colors duration-150 ease-in-out">
                        <!-- Name -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full bg-green-100 dark:bg-green-100 text-green-600 dark:text-green-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-800">{{ $dorm->name }}</div>
                                    @if($dorm->description)
                                        <div class="text-sm text-gray-500 dark:text-gray-600 max-w-xs truncate">{{ $dorm->description }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        
                        <!-- Capacity -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-gray-800">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 dark:bg-emerald-100 text-emerald-800 dark:text-emerald-800">
                                    {{ $dorm->capacity }} beds
                                </span>
                            </div>
                        </td>
                        
                        <!-- Occupancy -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <!-- Clickable link to view students for current year -->
                            <button 
                                wire:click="setSelectedDormIdAndViewStudents({{ $dorm->id }}, '{{ date('Y') }}')" 
                                class="inline-flex items-center group"
                            >
                                @php
                                    $currentOccupancy = $this->getCurrentYearOccupancy($dorm->id);
                                    $percentage = ($dorm->capacity > 0) ? ($currentOccupancy / $dorm->capacity) * 100 : 0;
                                    
                                    $textColorClass = 'text-green-700 dark:text-green-700';
                                    $bgColorClass = 'bg-green-500';
                                    $bgColorLightClass = 'bg-green-100 dark:bg-green-100';
                                    
                                    if ($percentage >= 90) {
                                        $textColorClass = 'text-red-700 dark:text-red-700';
                                        $bgColorClass = 'bg-red-500';
                                        $bgColorLightClass = 'bg-red-100 dark:bg-red-100';
                                    } elseif ($percentage >= 70) {
                                        $textColorClass = 'text-yellow-700 dark:text-yellow-700';
                                        $bgColorClass = 'bg-yellow-500';
                                        $bgColorLightClass = 'bg-yellow-100 dark:bg-yellow-100';
                                    }
                                @endphp
                                
                                <div class="flex flex-col">
                                    <div class="flex items-center mb-1">
                                        <span class="text-sm font-medium {{ $textColorClass }} mr-2">
                                            {{ $currentOccupancy }} / {{ $dorm->capacity }}
                                        </span>
                                        <span class="text-xs text-gray-500 dark:text-gray-600">
                                            ({{ round($percentage) }}%)
                                        </span>
                                    </div>
                                    <div class="w-48 h-2 {{ $bgColorLightClass }} rounded-full overflow-hidden">
                                        <div class="{{ $bgColorClass }} h-full rounded-full" style="width: {{ min(100, $percentage) }}%"></div>
                                    </div>
                                </div>
                                
                                <div class="ml-2 text-emerald-600 dark:text-emerald-600 opacity-0 group-hover:opacity-100 transition-opacity duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </td>
                        
                        <!-- Actions -->
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <div x-data="{ open: false }" class="relative inline-block text-left">
                                <div>
                                    <button 
                                        @click="open = !open" 
                                        type="button" 
                                        class="inline-flex justify-center items-center p-2 rounded-full text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                    >
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                        </svg>
                                    </button>
                                </div>
                                
                                <div 
                                    x-show="open" 
                                    @click.away="open = false"
                                    x-transition:enter="transition ease-out duration-100" 
                                    x-transition:enter-start="transform opacity-0 scale-95" 
                                    x-transition:enter-end="transform opacity-100 scale-100" 
                                    x-transition:leave="transition ease-in duration-75" 
                                    x-transition:leave-start="transform opacity-100 scale-100" 
                                    x-transition:leave-end="transform opacity-0 scale-95" 
                                    class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white dark:bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 dark:divide-gray-200 focus:outline-none z-10"
                                >
                                    <div class="py-1">
                                        <!-- Edit -->
                                        <button 
                                            wire:click="editDorm({{ $dorm->id }})"
                                            @click="open = false"
                                            class="group flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-100 hover:text-gray-900 dark:hover:text-gray-900 w-full text-left"
                                        >
                                            <svg class="mr-3 h-5 w-5 text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                            Edit
                                        </button>
                                        
                                        <!-- Delete -->
                                        <button 
                                            wire:click="deleteDorm({{ $dorm->id }})"
                                            @click="open = false"
                                            class="group flex items-center px-4 py-2 text-sm text-red-600 dark:text-red-700 hover:bg-red-50 dark:hover:bg-red-50 hover:text-red-700 dark:hover:text-red-800 w-full text-left"
                                        >
                                            <svg class="mr-3 h-5 w-5 text-red-400 dark:text-red-500 group-hover:text-red-500 dark:group-hover:text-red-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                            Delete
                                        </button>
                                    </div>
                                    
                                    <div class="py-1">
                                        <!-- Assign Dorm Master -->
                                        <button 
                                            wire:click="assignDormMaster({{ $dorm->id }})"
                                            @click="open = false"
                                            class="group flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-100 hover:text-gray-900 dark:hover:text-gray-900 w-full text-left"
                                        >
                                            <svg class="mr-3 h-5 w-5 text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
                                            </svg>
                                            Assign Master
                                        </button>
                                        
                                        <!-- View Dorm Masters -->
                                        <button 
                                            wire:click="viewDormMasters({{ $dorm->id }})"
                                            @click="open = false"
                                            class="group flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-100 hover:text-gray-900 dark:hover:text-gray-900 w-full text-left"
                                        >
                                            <svg class="mr-3 h-5 w-5 text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                                            </svg>
                                            View Masters
                                        </button>
                                    </div>
                                    
                                    <div class="py-1">
                                        <!-- Add Students -->
                                        <button 
                                            wire:click="addStudents({{ $dorm->id }})"
                                            @click="open = false"
                                            class="group flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-100 hover:text-gray-900 dark:hover:text-gray-900 w-full text-left"
                                        >
                                            <svg class="mr-3 h-5 w-5 text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
                                            </svg>
                                            Add Students
                                        </button>
                                        
                                        <!-- View Occupancy -->
                                        <button 
                                            wire:click="viewOccupancy({{ $dorm->id }})"
                                            @click="open = false"
                                            class="group flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-100 hover:text-gray-900 dark:hover:text-gray-900 w-full text-left"
                                        >
                                            <svg class="mr-3 h-5 w-5 text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z" />
                                                <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z" />
                                            </svg>
                                            View Occupancy
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($dorms->hasPages())
        <div class="px-4 py-3 bg-gray-50 dark:bg-gray-100 border-t border-gray-200 dark:border-gray-200 sm:px-6">
            {{ $dorms->links() }}
        </div>
    @endif
</div>
