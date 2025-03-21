<!-- Header Component -->
<div class="bg-white dark:bg-gray-50 rounded-lg shadow-sm overflow-hidden mb-6">
    <!-- Top section with title and actions -->
    <div class="p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
            <!-- Title and Description -->
            <div class="max-w-3xl">
                <h1 class="text-2xl font-medium text-gray-900 dark:text-gray-800 tracking-tight leading-tight">
                    Manage Dormitories
                </h1>
                <p class="mt-2 text-gray-600 dark:text-gray-600">
                    Create and manage dormitories, assign dorm masters, and track student occupancy. 
                    Efficiently organize your school's residential management from one place.
                </p>
            </div>

            <!-- Action Button -->
            @if($dorms->count() > 0)
                <button 
                    wire:click="create"
                    class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-full shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-colors duration-150 ease-in-out"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1.5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 00-1 1v5H4a1 1 0 100 2h5v5a1 1 0 102 0v-5h5a1 1 0 100-2h-5V4a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    New Dormitory
                </button>
            @endif
        </div>
    </div>

    <!-- Stats Cards Section -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 p-6 bg-gray-50 dark:bg-gray-100 border-t border-gray-200 dark:border-gray-200">
        <!-- Total Dorms Card -->
        <div class="bg-white dark:bg-white rounded-lg shadow-sm p-4 flex items-center space-x-4">
            <div class="rounded-full bg-green-100 dark:bg-green-100 p-3 flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 dark:text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-500">Total Dormitories</p>
                <p class="text-2xl font-semibold text-gray-900 dark:text-gray-800">{{ $dorms->count() }}</p>
            </div>
        </div>

        <!-- Total Capacity Card -->
        <div class="bg-white dark:bg-white rounded-lg shadow-sm p-4 flex items-center space-x-4">
            <div class="rounded-full bg-emerald-100 dark:bg-emerald-100 p-3 flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-600 dark:text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-500">Total Capacity</p>
                <p class="text-2xl font-semibold text-gray-900 dark:text-gray-800">{{ $dorms->sum('capacity') }}</p>
            </div>
        </div>

        <!-- Current Occupancy Card -->
        @php
            $totalOccupancy = 0;
            $totalCapacity = $dorms->sum('capacity');
            
            foreach($dorms as $dorm) {
                $totalOccupancy += $this->getCurrentYearOccupancy($dorm->id);
            }
            
            $occupancyPercentage = $totalCapacity > 0 ? ($totalOccupancy / $totalCapacity) * 100 : 0;
        @endphp
        <div class="bg-white dark:bg-white rounded-lg shadow-sm p-4 flex items-center space-x-4">
            <div class="rounded-full bg-teal-100 dark:bg-teal-100 p-3 flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-teal-600 dark:text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-500">Current Occupancy</p>
                <p class="text-2xl font-semibold text-gray-900 dark:text-gray-800">{{ $totalOccupancy }} / {{ $totalCapacity }}</p>
            </div>
        </div>

        <!-- Occupancy Rate Card -->
        <div class="bg-white dark:bg-white rounded-lg shadow-sm p-4 flex items-center space-x-4">
            <div class="rounded-full bg-emerald-100 dark:bg-emerald-100 p-3 flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-600 dark:text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-500">Occupancy Rate</p>
                <div class="flex items-center">
                    <p class="text-2xl font-semibold text-gray-900 dark:text-gray-800">{{ round($occupancyPercentage) }}%</p>
                    <div class="ml-2 flex h-2 w-16 overflow-hidden rounded-full bg-gray-200 dark:bg-gray-200">
                        <div 
                            class="h-full rounded-full transition-all duration-500" 
                            style="width: {{ $occupancyPercentage }}%"
                            :class="{
                                'bg-green-500': {{ $occupancyPercentage }} < 70,
                                'bg-yellow-500': {{ $occupancyPercentage }} >= 70 && {{ $occupancyPercentage }} < 90,
                                'bg-red-500': {{ $occupancyPercentage }} >= 90
                            }"
                        ></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
