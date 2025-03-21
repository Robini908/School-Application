<!-- Students List Component for Dormitories -->
<div class="bg-white dark:bg-gray-50 rounded-lg shadow-sm overflow-hidden">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-200 bg-gray-50 dark:bg-gray-100 flex justify-between items-center">
        <h2 class="font-medium text-xl text-gray-800 dark:text-gray-800 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-green-500 dark:text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            Students in {{ $dormName }} - {{ $year }}
        </h2>
        <button 
            wire:click="closeStudentsList"
            class="text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-200 transition-colors duration-150"
        >
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>
    
    <!-- Search and Filters -->
    <div class="p-4 sm:px-6 border-b border-gray-200 dark:border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <!-- Left side - Search -->
        <div class="relative rounded-md shadow-sm max-w-md w-full">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </div>
            <input 
                wire:model.debounce.300ms="search" 
                type="text" 
                placeholder="Search students..." 
                class="block w-full pl-10 sm:text-sm border-gray-300 dark:border-gray-300 rounded-md focus:ring-emerald-500 focus:border-emerald-500 dark:bg-white dark:text-gray-800 dark:placeholder-gray-500"
            >
        </div>
        
        <!-- Right side - Filter buttons -->
        <div class="flex items-center space-x-2">
            <span class="text-sm text-gray-500 dark:text-gray-600">Filter by:</span>
            <select 
                wire:model="classFilter" 
                class="block w-full sm:w-auto pl-3 pr-10 py-1.5 text-base border-gray-300 dark:border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-md dark:bg-white dark:text-gray-800"
            >
                <option value="">All Classes</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    
    <!-- Students Data -->
    <div class="overflow-x-auto">
        @if(isset($studentsInDorm) && count($studentsInDorm) > 0)
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-200">
                <thead class="bg-gray-50 dark:bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-600 uppercase tracking-wider">
                            Student
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-600 uppercase tracking-wider">
                            Admission Number
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-600 uppercase tracking-wider">
                            Class
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-600 uppercase tracking-wider">
                            Section
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-600 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-white divide-y divide-gray-200 dark:divide-gray-200">
                    @foreach($studentsInDorm as $student)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-50 transition-colors duration-150 ease-in-out">
                            <!-- Student -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if(isset($student->photo_by) && $student->photo_by)
                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/uploads/'.$student->photo) }}" alt="{{ $student->name }}">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-green-100 dark:bg-green-100 flex items-center justify-center">
                                                <span class="text-green-600 dark:text-green-600 font-medium text-sm">
                                                    {{ substr($student->first_name ?? $student->name ?? '', 0, 1) }}{{ substr($student->last_name ?? '', 0, 1) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-800">
                                            {{ $student->first_name ?? '' }} {{ $student->last_name ?? '' }}
                                            @if(empty($student->first_name) && empty($student->last_name) && isset($student->name))
                                                {{ $student->name }}
                                            @endif
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-600">{{ $student->email ?? 'No email' }}</div>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Admission Number -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-gray-800">{{ $student->adm_no ?? 'N/A' }}</div>
                            </td>
                            
                            <!-- Class -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-gray-800">
                                    @if(isset($student->my_class) && $student->my_class)
                                        {{ $student->my_class->name }}
                                    @elseif(isset($student->class_name))
                                        {{ $student->class_name }}
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </td>
                            
                            <!-- Section -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-gray-800">
                                    @if(isset($student->section) && $student->section)
                                        {{ $student->section->name }}
                                    @elseif(isset($student->section_name))
                                        {{ $student->section_name }}
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </td>
                            
                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <button 
                                    wire:click="removeStudent({{ $student->id }})" 
                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-full shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-150"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Remove
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-200">
                {{ $studentsInDorm->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="py-12 flex flex-col items-center justify-center text-center px-6">
                <div class="bg-gray-100 dark:bg-gray-100 rounded-full p-5 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-800">No Students Found</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-600">
                    There are no students assigned to this dormitory for the selected year.
                </p>
                <button 
                    wire:click="showAddStudents" 
                    class="mt-6 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-full shadow-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors duration-150"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add Students
                </button>
            </div>
        @endif
    </div>
</div>
