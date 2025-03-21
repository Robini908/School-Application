<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="p-4 border-b">
        <h3 class="text-lg font-medium text-gray-800">
            <i class="fas fa-user-graduate text-blue-500 mr-2"></i> Student List
        </h3>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admission</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Year</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Photo</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Section</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parent</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @if ($students->isEmpty())
                    <tr>
                        <td colspan="11" class="px-6 py-4 text-center text-sm text-gray-500">
                            <div class="flex flex-col items-center justify-center py-6">
                                <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No students found</h3>
                                <p class="mt-1 text-sm text-gray-500">Try adjusting your search or filter criteria.</p>
                            </div>
                        </td>
                    </tr>
                @else
                    @foreach ($students as $student)
                        <tr class="{{ $student->is_suspended ? 'bg-red-50' : '' }} hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $student->adm_no }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $student->year_admitted }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($student->photo)
                                    <img src="{{ asset($student->photo) }}" alt="Student Photo" class="h-10 w-10 rounded-full object-cover">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-500 text-xs">{{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            @if ($student->is_suspended)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 mr-1">
                                                    {{ $student->suspension_type }}
                                                </span>
                                            @endif
                                            <a href="{{ route('student.info', (['id' => $student->id])) }}" class="hover:text-blue-600 transition-colors">
                                                {{ $student->first_name }} {{ $student->last_name }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $student->gender }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $student->my_class->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $student->section->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $student->status == 'Active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $student->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $student->parent_detail->parent_first_name ?? 'N/A' }} {{ $student->parent_detail->parent_last_name ?? '' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $student->parent_detail->parent_phone_number ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div x-data="{ open: false }" class="relative inline-block text-left">
                                    <div>
                                        <button @click="open = !open" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
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
                                            <button wire:click="editStudent({{ $student->id }})" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 w-full text-left {{ $student->is_suspended ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $student->is_suspended ? 'disabled' : '' }}>
                                                <i class="fas fa-edit text-blue-500 mr-3 group-hover:text-blue-700"></i> Edit
                                                <div wire:loading wire:target="editStudent({{ $student->id }})" class="ml-auto">
                                                    <svg class="animate-spin h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                </div>
                                            </button>
                                            
                                            <button wire:click="deleteRecord({{ $student->id }})" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-700 w-full text-left {{ $student->is_suspended ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $student->is_suspended ? 'disabled' : '' }}>
                                                <i class="fas fa-trash text-red-500 mr-3 group-hover:text-red-700"></i> Delete
                                                <div wire:loading wire:target="deleteRecord({{ $student->id }})" class="ml-auto">
                                                    <svg class="animate-spin h-4 w-4 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                </div>
                                            </button>
                                        </div>
                                        
                                        <div class="py-1">
                                            <button wire:click="viewStudent({{ $student->id }})" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 w-full text-left">
                                                <i class="fas fa-envelope text-indigo-500 mr-3 group-hover:text-indigo-700"></i> Send Mail
                                                <div wire:loading wire:target="viewStudent({{ $student->id }})" class="ml-auto">
                                                    <svg class="animate-spin h-4 w-4 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                </div>
                                            </button>
                                            
                                            <button wire:click="suspendStudent({{ $student->id }})" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 w-full text-left {{ $student->is_suspended ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $student->is_suspended ? 'disabled' : '' }}>
                                                <i class="fas fa-pause text-yellow-500 mr-3 group-hover:text-yellow-700"></i> Suspension
                                                <div wire:loading wire:target="suspendStudent({{ $student->id }})" class="ml-auto">
                                                    <svg class="animate-spin h-4 w-4 text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                </div>
                                            </button>
                                        </div>
                                        
                                        <div class="py-1">
                                            <button wire:click="approveStudent({{ $student->id }})" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 w-full text-left {{ $student->is_suspended ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $student->is_suspended ? 'disabled' : '' }}>
                                                <i class="fas fa-check text-green-500 mr-3 group-hover:text-green-700"></i> Approve
                                                <div wire:loading wire:target="approveStudent({{ $student->id }})" class="ml-auto">
                                                    <svg class="animate-spin h-4 w-4 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                </div>
                                            </button>
                                            
                                            <button wire:click="studentExpulsion({{ $student->id }})" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-700 w-full text-left {{ $student->is_expelled ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $student->is_expelled ? 'disabled' : '' }}>
                                                <i class="fas fa-exclamation-triangle text-red-500 mr-3 group-hover:text-red-700"></i> Expulsion
                                                <div wire:loading wire:target="studentExpulsion({{ $student->id }})" class="ml-auto">
                                                    <svg class="animate-spin h-4 w-4 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                    </svg>
                                                </div>
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
    
    <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6">
        {{ $students->links() }}
    </div>
</div> 