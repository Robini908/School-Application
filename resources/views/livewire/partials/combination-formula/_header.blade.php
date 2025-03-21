<!-- Header Section -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center space-y-4 lg:space-y-0">
        <div class="flex flex-col space-y-2">
            <h2 class="text-2xl font-bold text-gray-900">Exam Combination Analysis</h2>
            <p class="text-sm text-gray-600">Combine and analyze multiple exam results</p>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="mt-6 border-b border-gray-200">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <button 
                @click="activeTab = 'normal'" 
                :class="{ 'border-[#217346] text-[#217346]': activeTab === 'normal', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'normal' }"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all duration-200">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span>Normal Analysis</span>
                </div>
            </button>

            <button 
                @click="activeTab = 'combined'" 
                :class="{ 'border-[#217346] text-[#217346]': activeTab === 'combined', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'combined' }"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all duration-200">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Combined Analysis</span>
                </div>
            </button>
        </nav>
    </div>
</div> 