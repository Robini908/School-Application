@extends('layouts.master')
@section('page_title', 'Grading System Management')
@section('content')

<div x-data="{ activeTab: 'grading-management' }" class="bg-white rounded-lg shadow-sm overflow-hidden">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-5 border-b border-gray-200">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="flex items-center mb-4 md:mb-0">
                <div class="bg-blue-100 rounded-full p-2 mr-3">
                    <svg class="w-6 h-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-medium text-gray-900">Grading System Management</h1>
                    <p class="mt-1 text-sm text-gray-600">Manage grading systems, ranges, and mean grade configurations</p>
            </div>
            </div>

            <div class="flex items-center space-x-2">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>
                <button type="button" onclick="window.print()" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print
                </button>
                <button x-show="activeTab === 'grading-management'" 
                        type="button" 
                        onclick="Livewire.dispatch('openModal', { component: 'create-grading-system' })"
                        class="inline-flex items-center px-3 py-2 border border-transparent shadow-sm text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    New Grading System
                </button>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="border-b border-gray-200">
        <nav class="px-6 -mb-px flex space-x-6 overflow-x-auto">
            <button @click="activeTab = 'grading-management'" 
                    :class="{'border-blue-500 text-blue-600': activeTab === 'grading-management', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'grading-management'}"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm focus:outline-none transition duration-150 ease-in-out">
                <div class="flex items-center">
                    <svg class="mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Grading Systems
                </div>
            </button>
            
            <button @click="activeTab = 'grading-range'" 
                    :class="{'border-blue-500 text-blue-600': activeTab === 'grading-range', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'grading-range'}"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm focus:outline-none transition duration-150 ease-in-out">
                <div class="flex items-center">
                    <svg class="mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Grading Ranges
                </div>
            </button>
            
            <button @click="activeTab = 'meangrade-range'" 
                    :class="{'border-blue-500 text-blue-600': activeTab === 'meangrade-range', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'meangrade-range'}"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm focus:outline-none transition duration-150 ease-in-out">
                <div class="flex items-center">
                    <svg class="mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                    </svg>
                    Mean Grade Ranges
                </div>
            </button>
        </nav>
    </div>

    <!-- Tab Content -->
    <div class="p-6">
        <!-- Grading Management Tab -->
        <div x-show="activeTab === 'grading-management'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
            <livewire:grading-management lazy />
        </div>
        
        <!-- Grading Range Tab -->
        <div x-show="activeTab === 'grading-range'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-cloak>
            <livewire:grading-range-manager lazy/>
        </div>
        
        <!-- Mean Grade Range Tab -->
        <div x-show="activeTab === 'meangrade-range'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-cloak>
            <livewire:grade-manager lazy />
        </div>
    </div>
</div>

<!-- Add Alpine.js if not already included -->
@push('js')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush

@endsection