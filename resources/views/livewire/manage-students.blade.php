<div x-data="{ activeTab: 'students' }" class="bg-gray-50 min-h-screen p-4">
    <div class="max-w-7xl mx-auto">
        <!-- Back button - shown when in any action view -->
        @if ($isEditingStudent || $isViewingDetails || $isDeleting || $isApproving || $isSuspendingStudent || $isExpellingStudent)
            <button wire:click="closeAction" class="mb-4 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm transition-colors">
                <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Students
            </button>
        @endif

        <!-- Main content area -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <!-- Header -->
            @if (!(
            $isEditingStudent ||
            $isViewingDetails ||
            $isDeleting ||
            $isApproving ||
            $isSuspendingStudent ||
            $isExpellingStudent
        ))
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row justify-between items-center">
                        <h1 class="text-xl font-semibold text-gray-800 mb-4 sm:mb-0">
                            <svg class="inline-block w-6 h-6 mr-2 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path d="M12 14l9-5-9-5-9 5 9 5z" />
                                <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                            </svg>
                            Manage Students
                        </h1>
                        <div class="flex space-x-2">
                            <a href="{{ route('students.create') }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm transition-colors">
                                <svg class="mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Add
                            </a>
                            <button wire:click="generatePdfReport" wire:loading.attr="disabled" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 shadow-sm transition-colors">
                                <svg class="mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                <span wire:loading.remove wire:target="generatePdfReport">PDF</span>
                <span wire:loading wire:target="generatePdfReport">
                                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                </span>
            </button>
                    </div>
                </div>
            </div>

                <!-- Search and Filter Bar -->
                <div class="px-6 py-3 bg-gray-50 border-b border-gray-200">
                    <div class="flex flex-col md:flex-row space-y-3 md:space-y-0 md:space-x-4">
                        <div class="flex-1 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input wire:model.live.debounce.300ms="search" type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Search students...">
        </div>
                        
                        <div class="flex space-x-2">
                            <select wire:model.live="formFilter" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option value="">All Forms</option>
                            @foreach ($forms as $form)
                                <option value="{{ $form }}">{{ $form }}</option>
                            @endforeach
                        </select>
                            
                            <select wire:model.live="statusFilter" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="suspended">Suspended</option>
                                <option value="expelled">Expelled</option>
                                <option value="graduated">Graduated</option>
                        </select>
                            
                            <button wire:click="resetFilters" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="mr-1.5 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Reset
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Student Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parent</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                    @if ($students->isEmpty())
                        <tr>
                                    <td colspan="5" class="px-6 py-10 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="h-12 w-12 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <h3 class="mt-2 text-sm font-medium text-gray-900">No students found</h3>
                                            <p class="mt-1 text-sm text-gray-500">Try adjusting your search or filter criteria.</p>
                                        </div>
                            </td>
                        </tr>
                    @else
                        @foreach ($students as $student)
                                    <tr class="{{ $student->is_suspended ? 'bg-red-50' : '' }} hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                    @if ($student->photo)
                                                        <img src="{{ asset($student->photo) }}" alt="Student Photo" class="h-10 w-10 rounded-full object-cover">
                                    @else
                                                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                            <span class="text-blue-600 font-medium text-sm">{{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}</span>
                                                        </div>
                                    @endif
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $student->first_name }} {{ $student->last_name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500 flex items-center">
                                                        <span class="mr-2">{{ $student->adm_no }}</span>
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                            {{ $student->gender }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $student->my_class->name ?? 'N/A' }}</div>
                                            <div class="text-sm text-gray-500">{{ $student->section->name ?? 'N/A' }}</div>
                                </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($student->is_suspended)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Suspended
                                                </span>
                                            @elseif ($student->is_expelled)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Expelled
                                                </span>
                                            @elseif ($student->is_graduated)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Graduated
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    Active
                                                </span>
                                        @endif
                                </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $student->parent_detail->parent_first_name ?? 'N/A' }} {{ $student->parent_detail->parent_last_name ?? '' }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $student->parent_detail->parent_phone ?? 'N/A' }}
                                            </div>
                                </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div x-data="{ open: false }" class="relative inline-block text-left">
                                                <div>
                                                    <button @click="open = !open" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" id="options-menu" aria-expanded="true" aria-haspopup="true">
                                                        Actions
                                                        <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </div>

                                                <div x-show="open" @click.away="open = false" 
                                                    x-transition:enter="transition ease-out duration-100" 
                                                    x-transition:enter-start="transform opacity-0 scale-95" 
                                                    x-transition:enter-end="transform opacity-100 scale-100" 
                                                    x-transition:leave="transition ease-in duration-75" 
                                                    x-transition:leave-start="transform opacity-100 scale-100" 
                                                    x-transition:leave-end="transform opacity-0 scale-95" 
                                                    class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none z-10">
                                                    <div class="py-1">
                                                        <button wire:click="editStudent({{ $student->id }})" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 w-full text-left">
                                                            <svg class="mr-3 h-5 w-5 text-blue-500 group-hover:text-blue-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                            </svg>
                                                            Edit
                                                    </button>
                                                        
                                                        <button wire:click="viewStudent({{ $student->id }})" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 w-full text-left">
                                                            <svg class="mr-3 h-5 w-5 text-indigo-500 group-hover:text-indigo-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                            </svg>
                                                            Email
                                                    </button>
                                                    </div>
                                                    
                                                    <div class="py-1">
                                                        <button wire:click="approveStudent({{ $student->id }})" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 w-full text-left">
                                                            <svg class="mr-3 h-5 w-5 text-green-500 group-hover:text-green-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            Approve
                                                    </button>
                                                        
                                                        <button wire:click="suspendStudent({{ $student->id }})" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 w-full text-left {{ $student->is_suspended ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $student->is_suspended ? 'disabled' : '' }}>
                                                            <svg class="mr-3 h-5 w-5 text-yellow-500 group-hover:text-yellow-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                            </svg>
                                                            Suspension
                                                        </button>
                                                        
                                                        <button wire:click="studentExpulsion({{ $student->id }})" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-700 w-full text-left {{ $student->is_expelled ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $student->is_expelled ? 'disabled' : '' }}>
                                                            <svg class="mr-3 h-5 w-5 text-red-500 group-hover:text-red-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                            </svg>
                                                            Expulsion
                                                    </button>
                                                    </div>
                                                    
                                                    <div class="py-1">
                                                        <button wire:click="deleteRecord({{ $student->id }})" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-700 w-full text-left">
                                                            <svg class="mr-3 h-5 w-5 text-red-500 group-hover:text-red-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                            Delete
                                                    </button>
                                                    </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-3 bg-gray-50 border-t border-gray-200">
                    {{ $students->links() }}
                </div>
            @elseif ($isEditingStudent)
                <!-- Edit Student View -->
                @include('livewire.partials.students.edit-student')
                
            @elseif ($isViewingDetails && $selectedStudent)
                <!-- Send Email View -->
                @include('livewire.partials.students.send-email')
                
            @elseif ($isDeleting)
                <!-- Delete Confirmation View -->
                @include('livewire.partials.students.delete-confirmation')
                
            @elseif ($isExpellingStudent)
                <!-- Student Expulsion View -->
                @include('livewire.partials.students.student-expulsion')
                
            @elseif ($isSuspendingStudent)
                <!-- Student Suspension View -->
                @include('livewire.partials.students.student-suspension')
                
            @elseif ($isApproving)
                <!-- Student Approval View -->
                @include('livewire.partials.students.student-approval')
                
            @endif
                </div>
            </div>
</div>
