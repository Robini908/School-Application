@extends('layouts.master')

@section('page_title', 'Manage Exam Marks Allocation')

@section('content')

<div x-data="{ 
    activeTab: 'bulk-exam',
    showMoreInfo: false,
    showAlert: true,
    showSidePanel: false,
    isAnalysisTool(tab) {
        return Object.keys(this.analysisTabs).includes(tab);
    },
    getActiveTabDetails() {
        return this.isAnalysisTool(this.activeTab) 
            ? this.analysisTabs[this.activeTab] 
            : this.mainTabs[this.activeTab] || {};
    },
    mainTabs: {
        'bulk-exam': { 
            name: 'Assign Marks', 
            icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
            description: 'Assign marks to subjects for each class'
        },
        'subject-wise-exam': { 
            name: 'Bulk Upload', 
            icon: 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
            description: 'Upload marks in bulk for multiple subjects'
        },
        'mark-list-management': { 
            name: 'Manage List', 
            icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
            description: 'View and edit existing marks entries'
        }
    },
    analysisTabs: {
        'exam-analysis': { 
            name: 'Exam Analysis', 
            icon: 'M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z',
            description: 'Analyze exam performance metrics'
        },
        'subject-champions': { 
            name: 'Subject Champions', 
            icon: 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z',
            description: 'View top performing students by subject'
        },
        'subject-analysis': { 
            name: 'Subject Analysis', 
            icon: 'M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z',
            description: 'Analyze subject-wise performance trends'
        },
        'class-analysis': { 
            name: 'Class Analysis', 
            icon: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
            description: 'View class-wise performance metrics'
        }
    }
}" class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-6">
        <!-- Back Navigation -->
        <nav class="mb-6 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('exams.set') }}" 
                   class="group flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                    <svg class="mr-2 h-5 w-5 text-gray-400 group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Back to Exams
                </a>
                <span class="text-gray-300">/</span>
                <span class="text-gray-500" x-text="getActiveTabDetails().name || 'Marks Allocation'"></span>
    </div>
            <button @click="showSidePanel = !showSidePanel"
                    class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    :class="{ 'bg-blue-50 border-blue-300 text-blue-700': isAnalysisTool(activeTab) }">
                <svg class="h-5 w-5 mr-2" 
                     :class="isAnalysisTool(activeTab) ? 'text-blue-500' : 'text-gray-400'"
                     xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span x-text="isAnalysisTool(activeTab) ? 'View Analysis' : 'Analysis Tools'"></span>
            </button>
        </nav>

        <div class="flex space-x-6">
            <!-- Main Content -->
            <div class="flex-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <!-- Google-style Material Header -->
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h1 class="text-xl font-medium text-white" x-text="getActiveTabDetails().name || 'Exam Marks Allocation'"></h1>
                                <p class="mt-1 text-sm text-white/80" x-text="getActiveTabDetails().description || 'Manage and allocate examination marks efficiently'"></p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <button @click="showAlert = !showAlert" 
                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-full text-white bg-white/10 hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-indigo-600 focus:ring-white">
                                    <svg class="h-4 w-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Help
                                </button>
                            </div>
                        </div>
        </div>

                    <!-- Alert Section -->
                    <div x-show="showAlert" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 transform translate-y-0"
                         x-transition:leave-end="opacity-0 transform -translate-y-2"
                         class="relative bg-blue-50 px-4 py-3">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3 flex-1">
                                <h3 class="text-sm font-medium text-blue-800">Important Information</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p>Please ensure all marks are allocated correctly before final submission.</p>
                                </div>
                            </div>
                </div>
            </div>

                    <!-- Main Tabs (only shown when not in analysis mode) -->
                    <div class="border-b border-gray-200" x-show="!isAnalysisTool(activeTab)">
                        <div class="px-6">
                            <div class="flex -mb-px space-x-8">
                                <template x-for="(details, id) in mainTabs" :key="id">
                                    <button 
                                        @click="activeTab = id" 
                                        class="group relative py-4 px-1 text-sm font-medium text-center focus:outline-none"
                                        :class="activeTab === id ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300'">
                                        <div class="flex items-center">
                                            <svg class="h-5 w-5 mr-2" 
                                                 :class="activeTab === id ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-500'"
                                                 xmlns="http://www.w3.org/2000/svg" 
                                                 fill="none" 
                                                 viewBox="0 0 24 24" 
                                                 stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="details.icon" />
                                            </svg>
                                            <span x-text="details.name"></span>
                                        </div>
                        </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Content -->
                    <div class="p-6">
                        <!-- Main Tab Contents -->
                        <div x-show="!isAnalysisTool(activeTab)">
                            <div x-show="activeTab === 'bulk-exam'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <livewire:assign-exams-subjectwise lazy />
                </div>
                            <div x-show="activeTab === 'subject-wise-exam'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <livewire:assign-batch-marks lazy />
                </div>
                            <div x-show="activeTab === 'mark-list-management'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <livewire:mark-list-management lazy />
                            </div>
                </div>

                        <!-- Analysis Tab Contents -->
                        <div x-show="isAnalysisTool(activeTab)">
                            <div x-show="activeTab === 'exam-analysis'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <livewire:combination-formula lazy />
                </div>
                            <div x-show="activeTab === 'subject-champions'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <livewire:subject-champions lazy />
                            </div>
                            <div x-show="activeTab === 'subject-analysis'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                                <livewire:subject-analysis lazy />
                            </div>
                            <div x-show="activeTab === 'class-analysis'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                                <livewire:class-analysis lazy />
                            </div>
                        </div>
                    </div>
                </div>
                </div>

            <!-- Side Panel for Analysis Tools -->
            <div x-show="showSidePanel"
                 x-transition:enter="transform transition ease-in-out duration-300"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transform transition ease-in-out duration-300"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="translate-x-full"
                 class="w-80 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Analysis Tools</h3>
                    <p class="mt-1 text-sm text-gray-500">Advanced analysis and reporting tools</p>
                </div>
                <div class="p-4">
                    <div class="space-y-2">
                        <template x-for="(details, id) in analysisTabs" :key="id">
                            <button 
                                @click="activeTab = id; showSidePanel = false"
                                class="w-full flex items-center px-3 py-2 text-sm font-medium rounded-md hover:bg-gray-50"
                                :class="activeTab === id ? 'text-blue-600 bg-blue-50' : 'text-gray-700'">
                                <svg class="h-5 w-5 mr-3" 
                                     :class="activeTab === id ? 'text-blue-600' : 'text-gray-400'"
                                     xmlns="http://www.w3.org/2000/svg" 
                                     fill="none" 
                                     viewBox="0 0 24 24" 
                                     stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="details.icon" />
                                </svg>
                                <span x-text="details.name"></span>
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
