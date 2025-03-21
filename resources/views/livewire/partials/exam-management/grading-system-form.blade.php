<div x-data="{ activeTab: 'systems' }">
    <!-- Instruction Alert -->
    <div x-show="showInstructions" 
         class="mb-6 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-md"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Instructions</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <p class="mb-2">After adding the grading system, ensure to add the grading ranges on the "Manage Grading Ranges" tab.</p>
                    <p class="mb-2">On that tab, select the grading system you've created, then select the subject.</p>
                    <p>You can also browse existing grading systems to confirm the existing grading ranges and other parameters.</p>
                </div>
                <div class="mt-4">
                    <button type="button" @click="showInstructions = false" 
                            class="inline-flex items-center px-3 py-1.5 border border-blue-300 shadow-sm text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Understood
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div x-show="!showInstructions" class="mb-4">
        <button type="button" @click="showInstructions = true" 
                class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs leading-4 font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="mr-1.5 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Show Instructions
        </button>
    </div>

    <!-- Tabs -->
    <div class="border-b border-gray-200 mb-6">
        <nav class="-mb-px flex space-x-8">
            <button @click="activeTab = 'systems'" 
                    :class="{ 'border-blue-500 text-blue-600': activeTab === 'systems', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'systems' }" 
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm focus:outline-none">
                Manage Grading Systems
            </button>
            <button @click="activeTab = 'ranges'" 
                    :class="{ 'border-blue-500 text-blue-600': activeTab === 'ranges', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'ranges' }" 
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm focus:outline-none">
                Manage Grading Ranges
            </button>
        </nav>
    </div>

    <!-- Tab Content -->
    <div x-show="activeTab === 'systems'" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100">
        @livewire('grading-management')
    </div>

    <div x-show="activeTab === 'ranges'" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100">
        <livewire:grading-range-manager />
    </div>
</div> 