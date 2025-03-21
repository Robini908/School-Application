<!-- Empty State Component -->
<div class="bg-white dark:bg-gray-50 rounded-lg shadow-sm overflow-hidden">
    <div class="flex flex-col items-center justify-center p-12 text-center">
        <div class="w-24 h-24 mb-6 rounded-full bg-green-100 dark:bg-green-100 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-green-600 dark:text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
        </div>
        
        <h2 class="text-2xl font-medium text-gray-900 dark:text-gray-800 mb-3">No Dormitories Yet</h2>
        
        <p class="text-gray-500 dark:text-gray-600 max-w-md mb-8">
            You haven't added any dormitories to your school yet. Get started by creating your first dormitory to organize student housing.
        </p>
        
        <button 
            wire:click="create"
            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-full shadow-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors duration-150"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 00-1 1v5H4a1 1 0 100 2h5v5a1 1 0 102 0v-5h5a1 1 0 100-2h-5V4a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            Add First Dormitory
        </button>
        
        <p class="mt-4 text-sm text-gray-500 dark:text-gray-600">
            Adding dormitories will help you manage student accommodation efficiently.
        </p>
    </div>
</div>
