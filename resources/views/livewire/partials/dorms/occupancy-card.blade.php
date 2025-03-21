<!-- Occupancy Card Component -->
<div class="bg-white dark:bg-gray-50 rounded-lg shadow-sm overflow-hidden" x-data="{ 
    occupancyData: @js($occupancyData),
    init() {
        if (this.occupancyData.length > 0) {
            this.initChartJS();
            this.animateCounters();
        }
    },
    initChartJS() {
        // If using Chart.js, you would initialize it here
    },
    animateCounters() {
        const totalOccupancy = this.occupancyData.reduce((sum, data) => sum + data.occupancy, 0);
        const averageOccupancy = (totalOccupancy / this.occupancyData.length).toFixed(2);
        const maxOccupancy = Math.max(...this.occupancyData.map(data => data.occupancy));
        
        this.animateValue('totalOccupancyCounter', 0, totalOccupancy, 1500);
        this.animateValue('averageOccupancyCounter', 0, averageOccupancy, 1500);
        this.animateValue('maxOccupancyCounter', 0, maxOccupancy, 1500);
    },
    animateValue(id, start, end, duration) {
        let startTimestamp = null;
        const element = document.getElementById(id);
        if (!element) return;
        
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            const value = Math.floor(progress * (end - start) + start);
            element.textContent = value;
            if (progress < 1) {
                window.requestAnimationFrame(step);
            }
        };
        window.requestAnimationFrame(step);
    }
}">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-200 bg-gray-50 dark:bg-gray-100 flex justify-between items-center">
        <h2 class="font-medium text-xl text-gray-800 dark:text-gray-800 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-green-500 dark:text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            Occupancy Over the Years - {{ $dormName }}
        </h2>
        <button 
            wire:click="closeOccupancyCard"
            class="text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-200 transition-colors duration-150"
        >
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>
    
    <!-- Key Stats Summary -->
    @if(count($occupancyData) > 0)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 px-6 py-4 border-b border-gray-200 dark:border-gray-200">
            <div class="bg-green-50 dark:bg-green-50 rounded-lg p-4 text-center shadow-sm">
                <div class="inline-flex items-center justify-center p-3 bg-green-100 dark:bg-green-100 rounded-full mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-700 dark:text-green-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <p class="text-sm font-medium text-green-800 dark:text-green-800 mb-1">Total Students</p>
                <p class="text-3xl font-bold text-green-900 dark:text-green-900" id="totalOccupancyCounter">0</p>
            </div>
            <div class="bg-emerald-50 dark:bg-emerald-50 rounded-lg p-4 text-center shadow-sm">
                <div class="inline-flex items-center justify-center p-3 bg-emerald-100 dark:bg-emerald-100 rounded-full mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-700 dark:text-emerald-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <p class="text-sm font-medium text-emerald-800 dark:text-emerald-800 mb-1">Average Occupancy</p>
                <p class="text-3xl font-bold text-emerald-900 dark:text-emerald-900" id="averageOccupancyCounter">0</p>
            </div>
            <div class="bg-teal-50 dark:bg-teal-50 rounded-lg p-4 text-center shadow-sm">
                <div class="inline-flex items-center justify-center p-3 bg-teal-100 dark:bg-teal-100 rounded-full mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-teal-700 dark:text-teal-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
                <p class="text-sm font-medium text-teal-800 dark:text-teal-800 mb-1">Max Occupancy</p>
                <p class="text-3xl font-bold text-teal-900 dark:text-teal-900" id="maxOccupancyCounter">0</p>
            </div>
        </div>
    @endif
    
    <!-- Occupancy Data -->
    <div class="p-6">
        @if(count($occupancyData) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-200">
                    <thead class="bg-gray-50 dark:bg-gray-100">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-600 uppercase tracking-wider">
                                Year
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-600 uppercase tracking-wider">
                                Occupancy
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-600 uppercase tracking-wider">
                                Percentage
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-white divide-y divide-gray-200 dark:divide-gray-200">
                        @foreach($occupancyData as $data)
                            @php
                                $occupancyPercentage = ($data->occupancy / $dormCapacity) * 100;
                                
                                $progressColor = 'bg-green-500';
                                $textColor = 'text-green-600 dark:text-green-600';
                                if ($occupancyPercentage >= 90) {
                                    $progressColor = 'bg-red-500';
                                    $textColor = 'text-red-600 dark:text-red-600';
                                } elseif ($occupancyPercentage >= 70) {
                                    $progressColor = 'bg-yellow-500';
                                    $textColor = 'text-yellow-600 dark:text-yellow-600';
                                }
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-50 transition-colors duration-150 ease-in-out">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-800">{{ $data->year }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button 
                                        wire:click="viewStudents('{{ $data->year }}')" 
                                        class="inline-flex items-center text-emerald-600 dark:text-emerald-600 hover:text-emerald-900 dark:hover:text-emerald-800 focus:outline-none focus:underline"
                                    >
                                        <span class="font-medium">{{ $data->occupancy }}</span>
                                        <span class="text-gray-500 dark:text-gray-500 mx-1">/</span>
                                        <span>{{ $dormCapacity }}</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-full bg-gray-200 dark:bg-gray-200 rounded-full h-2.5 mr-2 max-w-[150px]">
                                            <div class="{{ $progressColor }} h-2.5 rounded-full" style="width: {{ $occupancyPercentage }}%"></div>
                                        </div>
                                        <span class="text-sm font-medium {{ $textColor }}">{{ round($occupancyPercentage, 1) }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <!-- Empty State -->
            <div class="py-12 flex flex-col items-center justify-center text-center">
                <div class="bg-gray-100 dark:bg-gray-100 rounded-full p-5 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-800">No Occupancy Data</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-600">
                    There is no occupancy data available for this dormitory yet.
                </p>
            </div>
        @endif
    </div>
</div>
