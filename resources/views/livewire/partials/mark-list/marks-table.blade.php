@if ($marks && $marks->isNotEmpty())
    <div class="space-y-6" x-data="{ 
        showFilters: false,
        showColumnSelector: false,
        selectedSubject: null,
        sortColumn: null,
        sortDirection: 'asc',
        selectedColumns: {
            student_name: true,
            adm_no: true
        },
        sort(column) {
            if(this.sortColumn === column) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortColumn = column;
                this.sortDirection = 'asc';
            }
        },
        toggleAllColumns() {
            const allSelected = Object.values(this.selectedColumns).every(value => value);
            const newValue = !allSelected;
            Object.keys(this.selectedColumns).forEach(key => {
                this.selectedColumns[key] = newValue;
            });
        },
        isColumnSelected(column) {
            return this.selectedColumns[column] ?? false;
        }
    }"
    x-init="() => {
        // Initialize subject columns
        @foreach ($marks->first()['marks'] as $subjectName => $value)
            selectedColumns['{{ $subjectName }}'] = true;
        @endforeach
    }">
        <!-- Header Section with Stats and Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center space-y-4 lg:space-y-0">
                <!-- Stats -->
                <div class="flex flex-col space-y-2">
                    <h2 class="text-xl font-semibold text-gray-900">Student Marks</h2>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-2">
                            <div class="h-2.5 w-2.5 rounded-full bg-blue-600"></div>
                            <span class="text-sm text-gray-600">{{ count($marks) }} Students</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="h-2.5 w-2.5 rounded-full bg-emerald-500"></div>
                            <span class="text-sm text-gray-600">{{ count($marks->first()['marks']) }} Subjects</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap items-center gap-3">
                    <!-- Column Selector Button -->
                    <button @click="showColumnSelector = !showColumnSelector"
                        class="inline-flex items-center px-4 py-2 bg-[#217346] border border-[#185a34] rounded-md text-sm font-medium text-white hover:bg-[#185a34] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#217346] transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                        </svg>
                        Columns
                    </button>

                    <button @click="showFilters = !showFilters"
                        class="inline-flex items-center px-4 py-2 bg-[#217346] border border-[#185a34] rounded-md text-sm font-medium text-white hover:bg-[#185a34] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#217346] transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filters
                    </button>

                    <!-- Bulk Export Buttons -->
                    <button wire:click="exportToPDF(JSON.stringify(selectedColumns))" 
                        class="inline-flex items-center px-4 py-2 bg-[#217346] border border-[#185a34] rounded-md text-sm font-medium text-white hover:bg-[#185a34] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#217346] transition-all duration-200"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-not-allowed">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        <span wire:loading.remove wire:target="exportToPDF">Export PDF</span>
                        <span wire:loading wire:target="exportToPDF" class="inline-flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Exporting...
                        </span>
                    </button>

                    <button wire:click="exportToExcel(JSON.stringify(selectedColumns))"
                        class="inline-flex items-center px-4 py-2 bg-[#217346] border border-[#185a34] rounded-md text-sm font-medium text-white hover:bg-[#185a34] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#217346] transition-all duration-200"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-not-allowed">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        <span wire:loading.remove wire:target="exportToExcel">Export Excel</span>
                        <span wire:loading wire:target="exportToExcel" class="inline-flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Exporting...
                        </span>
                    </button>
                </div>
            </div>

            <!-- Column Selector Panel -->
            <div x-show="showColumnSelector" 
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-2"
                class="mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-medium text-gray-900">Select Columns to Export</h3>
                        <button @click="toggleAllColumns()" 
                            class="text-sm text-[#217346] hover:text-[#185a34] font-medium">
                            Toggle All
                        </button>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        <!-- Basic Info Columns -->
                        <label class="inline-flex items-center">
                            <input type="checkbox" x-model="selectedColumns.student_name" class="rounded border-gray-300 text-[#217346] focus:ring-[#217346]">
                            <span class="ml-2 text-sm text-gray-700">Student Name</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="checkbox" x-model="selectedColumns.adm_no" class="rounded border-gray-300 text-[#217346] focus:ring-[#217346]">
                            <span class="ml-2 text-sm text-gray-700">Admission No</span>
                        </label>
                        
                        <!-- Subject Columns -->
                        @foreach ($marks->first()['marks'] as $subjectName => $value)
                            <label class="inline-flex items-center">
                                <input type="checkbox" x-model="selectedColumns['{{ $subjectName }}']" class="rounded border-gray-300 text-[#217346] focus:ring-[#217346]">
                                <span class="ml-2 text-sm text-gray-700">{{ $subjectName }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Filters Panel -->
            <div x-show="showFilters" 
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-2"
                class="mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subject Filter</label>
                        <select x-model="selectedSubject" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#217346] focus:ring-[#217346] sm:text-sm">
                            <option value="">All Subjects</option>
                            @foreach ($marks->first()['marks'] as $subjectName => $value)
                                <option value="{{ $subjectName }}">{{ $subjectName }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Excel-like Table Container -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-[#E2EFDA] border-b border-gray-300">
                            <th scope="col" class="sticky left-0 z-10 bg-[#E2EFDA] px-6 py-3 text-left border-r border-gray-300 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)]">
                                <div class="flex items-center space-x-2 text-xs font-medium text-gray-700 uppercase tracking-wider">
                                    <span>Student Name</span>
                                    <button @click="sort('student_name')" class="text-gray-600 hover:text-gray-800">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                        </svg>
                                    </button>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left border-r border-gray-300">
                                <div class="flex items-center space-x-2 text-xs font-medium text-gray-700 uppercase tracking-wider">
                                    <span>Admission No</span>
                                    <button @click="sort('adm_no')" class="text-gray-600 hover:text-gray-800">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                        </svg>
                                    </button>
                                </div>
                            </th>
                            @foreach ($marks->first()['marks'] as $subjectName => $value)
                                <th scope="col" class="px-6 py-3 text-left border-r border-gray-300">
                                    <div class="flex items-center space-x-2 text-xs font-medium text-gray-700 uppercase tracking-wider">
                                        <span>{{ $subjectName }}</span>
                                        <button @click="sort('{{ $subjectName }}')" class="text-gray-600 hover:text-gray-800">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                            </svg>
                                        </button>
                                    </div>
                                </th>
                            @endforeach
                            <th scope="col" class="px-6 py-3 text-left border-r border-gray-300">
                                <span class="text-xs font-medium text-gray-700 uppercase tracking-wider">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @foreach ($marks as $mark)
                            @php
                                $hasSpecialGrade = collect($mark['marks'])->contains(
                                    fn($value) => in_array($value, ['X', 'Y', 'Z']),
                                );
                            @endphp
                            <tr wire:key="student-{{ $mark['adm_no'] }}"
                                class="{{ $hasSpecialGrade ? 'bg-red-50' : '' }} hover:bg-[#EDF3EB] border-b border-gray-300 transition-colors duration-150">
                                <td class="sticky left-0 z-10 px-6 py-4 whitespace-nowrap border-r border-gray-300 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] {{ $hasSpecialGrade ? 'bg-red-50' : 'bg-white' }} hover:bg-[#EDF3EB]">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 flex-shrink-0 rounded-full bg-[#217346] flex items-center justify-center">
                                            <span class="text-sm font-medium text-white">
                                                {{ substr($mark['student_name'], 0, 2) }}
                                            </span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $mark['student_name'] }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap border-r border-gray-300">
                                    <div class="text-sm text-gray-900">{{ $mark['adm_no'] }}</div>
                                </td>
                                @foreach ($marks->first()['marks'] as $subjectName => $subjectMark)
                                    <td class="px-6 py-4 whitespace-nowrap border-r border-gray-300">
                                        @if (in_array($mark['marks'][$subjectName] ?? '--', ['X', 'Y', 'Z']))
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-sm text-xs font-medium 
                                                {{ $mark['marks'][$subjectName] === 'X' ? 'bg-red-100 text-red-800' : 
                                                   ($mark['marks'][$subjectName] === 'Y' ? 'bg-yellow-100 text-yellow-800' : 
                                                    'bg-gray-100 text-gray-800') }}">
                                                {{ $mark['marks'][$subjectName] }}
                                            </span>
                                        @else
                                            <div class="flex items-center">
                                                <div class="text-sm text-gray-900 tabular-nums">{{ $mark['marks'][$subjectName] ?? '--' }}</div>
                                                @if(($mark['marks'][$subjectName] ?? 0) >= 75)
                                                    <svg class="ml-2 h-4 w-4 text-[#217346]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                @endforeach
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium border-r border-gray-300">
                                    <button wire:click="fetchStudentDetails('{{ $mark['adm_no'] }}')"
                                        x-on:click="currentStep = 3"
                                        class="inline-flex items-center px-3 py-1.5 bg-[#217346] text-white border border-[#185a34] rounded-md shadow-sm text-sm font-medium hover:bg-[#185a34] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#217346] transition-all duration-200">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        View Details
                                        <div wire:loading wire:target="fetchStudentDetails('{{ $mark['adm_no'] }}')"
                                            class="ml-2">
                                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </div>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@else
    <div class="text-center py-12">
        <div class="bg-gray-50 rounded-xl p-12 max-w-lg mx-auto">
            <div class="flex flex-col items-center">
                <div class="rounded-full bg-[#217346] p-4">
                    <svg class="h-12 w-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No marks available</h3>
                <p class="mt-2 text-sm text-gray-500">Please select a class, exam, and section to view marks.</p>
            </div>
        </div>
    </div>
@endif 