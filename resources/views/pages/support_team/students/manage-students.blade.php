@extends('layouts.master')
@section('page_title', 'Student Management')
@section('content')

<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div x-data="{ activeTab: '{{ Qs::userIsParent() ? 'admit-student' : 'manage-students' }}' }">
        <!-- Header with tabs -->
        <div class="border-b border-gray-200">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex">
                        <h1 class="text-xl font-semibold text-gray-800 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            Student Management
                        </h1>
                    </div>
                </div>
                
                <!-- Material Design inspired tabs -->
                <div class="flex -mb-px overflow-x-auto">
                    <!-- Manage Students Tab (Admin Only) -->
            @if (!Qs::userIsParent())
                        <button @click="activeTab = 'manage-students'" 
                                :class="{ 'border-blue-500 text-blue-600': activeTab === 'manage-students', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'manage-students' }" 
                                class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm focus:outline-none transition-colors whitespace-nowrap">
                            <svg :class="{ 'text-blue-500': activeTab === 'manage-students', 'text-gray-400 group-hover:text-gray-500': activeTab !== 'manage-students' }" 
                                class="mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            Manage Students
                        </button>
            @endif

                    <!-- Admit Student Tab (All Users) -->
                    <button @click="activeTab = 'admit-student'" 
                            :class="{ 'border-blue-500 text-blue-600': activeTab === 'admit-student', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'admit-student' }" 
                            class="group {{ !Qs::userIsParent() ? 'ml-8' : '' }} inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm focus:outline-none transition-colors whitespace-nowrap">
                        <svg :class="{ 'text-blue-500': activeTab === 'admit-student', 'text-gray-400 group-hover:text-gray-500': activeTab !== 'admit-student' }" 
                            class="mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        {{ Qs::userIsParent() ? 'Admit My Child' : 'Admit New Student' }}
                    </button>

                    <!-- Manage Suspensions Tab (All Users) -->
                    <button @click="activeTab = 'manage-suspensions'" 
                            :class="{ 'border-blue-500 text-blue-600': activeTab === 'manage-suspensions', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'manage-suspensions' }" 
                            class="group ml-8 inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm focus:outline-none transition-colors whitespace-nowrap">
                        <svg :class="{ 'text-blue-500': activeTab === 'manage-suspensions', 'text-gray-400 group-hover:text-gray-500': activeTab !== 'manage-suspensions' }" 
                            class="mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        {{ Qs::userIsParent() ? 'My Child\'s Suspensions' : 'Manage Suspensions' }}
                    </button>

                    <!-- Add Bulk Tab (Admin Only) -->
                    @if (!Qs::userIsParent())
                        <button @click="activeTab = 'addbulk'" 
                                :class="{ 'border-blue-500 text-blue-600': activeTab === 'addbulk', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'addbulk' }" 
                                class="group ml-8 inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm focus:outline-none transition-colors whitespace-nowrap">
                            <svg :class="{ 'text-blue-500': activeTab === 'addbulk', 'text-gray-400 group-hover:text-gray-500': activeTab !== 'addbulk' }" 
                                class="mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            Bulk Import
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tab content with smooth transitions -->
        <div class="relative">
            <!-- Manage Students Tab Content (Admin Only) -->
            @if (!Qs::userIsParent())
                <div x-show="activeTab === 'manage-students'" 
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-95">
                    <livewire:manage-students lazy />
                </div>
            @endif

            <!-- Admit New Student Tab Content (All Users) -->
            <div x-show="activeTab === 'admit-student'" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95"
                class="p-6">
                <div class="bg-blue-50 rounded-lg p-4 mb-6 flex items-start">
                    <svg class="h-6 w-6 text-blue-400 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Admission Information</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>
                                {{ Qs::userIsParent() ? 
                                    'Complete the form below to admit your child. All fields marked with an asterisk (*) are required.' : 
                                    'Complete the form below to admit a new student. All fields marked with an asterisk (*) are required.' 
                                }}
                            </p>
                        </div>
                    </div>
                </div>
                <livewire:admit-student lazy />
            </div>

            <!-- Manage Suspensions Tab Content (All Users) -->
            <div x-show="activeTab === 'manage-suspensions'" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95"
                class="p-6">
                <div class="bg-yellow-50 rounded-lg p-4 mb-6 flex items-start">
                    <svg class="h-6 w-6 text-yellow-400 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">
                            {{ Qs::userIsParent() ? 'Child Suspension Records' : 'Suspension Management' }}
                        </h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>
                                {{ Qs::userIsParent() ? 
                                    'View your child\'s suspension history and current status.' : 
                                    'This section allows you to manage student suspensions, including viewing current suspensions and reinstating students.' 
                                }}
                            </p>
                        </div>
                    </div>
                </div>
                @if (Qs::userIsParent())
                    {{-- <livewire:parent-manage-suspensions lazy /> --}}
                @else
                    <livewire:manage-suspensions lazy />
                @endif
            </div>

            <!-- Bulk Import Tab Content (Admin Only) -->
            @if (!Qs::userIsParent())
                <div x-show="activeTab === 'addbulk'" 
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-95"
                    class="p-6">
                    <div class="bg-green-50 rounded-lg p-4 mb-6 flex items-start">
                        <svg class="h-6 w-6 text-green-400 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-800">Bulk Student Import</h3>
                            <div class="mt-2 text-sm text-green-700">
                                <p>Upload a CSV file to add multiple students at once. Please ensure your file follows the required format.</p>
                                <p class="mt-1">You can <a href="#" class="font-medium underline">download a template</a> to get started.</p>
                            </div>
                        </div>
                    </div>
                    <livewire:addbulk lazy />
                </div>
            @endif
        </div>
    </div>
</div>

@endsection