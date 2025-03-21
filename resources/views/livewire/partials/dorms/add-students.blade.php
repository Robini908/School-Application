<!-- Add Students to Dormitory Component -->
<div class="bg-white dark:bg-gray-50 rounded-lg shadow-sm overflow-hidden" 
     x-data="{ 
        isFormValid: false,
        session: @entangle('session').defer,
        classId: @entangle('classId').defer,
        selectedStudents: @entangle('selectedStudents').defer,
        selectAll: false,
        validate() {
            this.isFormValid = this.session && this.classId && this.selectedStudents.length > 0;
        },
        toggleSelectAll() {
            const checkboxes = document.querySelectorAll('.student-checkbox');
            if (this.selectAll) {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = true;
                    const studentId = checkbox.value;
                    if (!this.selectedStudents.includes(studentId)) {
                        this.selectedStudents.push(studentId);
                    }
                });
            } else {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
                this.selectedStudents = [];
            }
            this.validate();
        }
     }"
     x-init="validate(); 
        $watch('session', () => validate()); 
        $watch('classId', () => validate());
        $watch('selectedStudents', () => validate());
        $watch('selectAll', () => toggleSelectAll());">
    
    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-200 bg-gray-50 dark:bg-gray-100 flex justify-between items-center">
        <h2 class="font-medium text-xl text-gray-800 dark:text-gray-800 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-green-500 dark:text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Add Students to {{ $dormName }}
        </h2>
        <button 
            wire:click="closeAddStudents"
            class="text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-200 transition-colors duration-150"
        >
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>
    
    <!-- Form Content -->
    <form wire:submit.prevent="assignStudentsToDorm" class="px-6 py-5 space-y-6">
        <!-- Selection Fields -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Session Field -->
            <div>
                <label for="session" class="block text-sm font-medium text-gray-700 dark:text-gray-700">
                    Session <span class="text-red-500">*</span>
                </label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <select 
                        id="session" 
                        wire:model="session"
                        x-model="session"
                        class="block w-full pl-10 pr-10 focus:outline-none sm:text-sm rounded-md py-2.5 border dark:bg-white dark:text-gray-800
                            @error('session') border-red-300 text-red-900 dark:text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 
                            @else border-gray-300 dark:border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 @enderror" 
                        :class="{'border-red-300 dark:border-red-300': !session && session !== undefined}"
                    >
                        <option value="">Select a Session</option>
                        @foreach($this->getYearsRange() as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                    @error('session')
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    @enderror
                </div>
                @error('session')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Class Field -->
            <div>
                <label for="classId" class="block text-sm font-medium text-gray-700 dark:text-gray-700">
                    Class <span class="text-red-500">*</span>
                </label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1M19 20a2 2 0 002-2V8a2 2 0 00-2-2h-5a2 2 0 00-2 2v12a2 2 0 002 2h5z" />
                        </svg>
                    </div>
                    <select 
                        id="classId" 
                        wire:model="classId"
                        x-model="classId"
                        class="block w-full pl-10 pr-10 focus:outline-none sm:text-sm rounded-md py-2.5 border dark:bg-white dark:text-gray-800
                            @error('classId') border-red-300 text-red-900 dark:text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 
                            @else border-gray-300 dark:border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 @enderror" 
                        :class="{'border-red-300 dark:border-red-300': !classId && classId !== undefined}"
                    >
                        <option value="">Select a Class</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                    @error('classId')
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    @enderror
                </div>
                @error('classId')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <!-- Students Selection -->
        <div class="mt-6">
            <!-- Search Box -->
            <div class="relative rounded-md shadow-sm max-w-md w-full mb-4">
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
            
            <!-- Select All Checkbox -->
            <div class="flex items-center mb-4">
                <input 
                    type="checkbox" 
                    id="selectAll" 
                    x-model="selectAll"
                    class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 dark:border-gray-300 rounded"
                >
                <label for="selectAll" class="ml-2 block text-sm text-emerald-700 dark:text-emerald-700">
                    Select All Students
                </label>
            </div>
            
            @if(session('error'))
                <div class="rounded-md bg-red-50 dark:bg-red-100 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400 dark:text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800 dark:text-red-900">{{ session('error') }}</h3>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Students List -->
            <div class="bg-white dark:bg-white shadow rounded-md overflow-hidden">
                @if(isset($availableStudents) && count($availableStudents) > 0)
                    <ul class="divide-y divide-gray-200 dark:divide-gray-200 max-h-80 overflow-y-auto">
                        @foreach($availableStudents as $student)
                            <li class="px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-50 transition-colors duration-150">
                                <div class="flex items-center">
                                    <input 
                                        type="checkbox" 
                                        id="student-{{ $student->id }}" 
                                        value="{{ $student->id }}" 
                                        wire:model="selectedStudents"
                                        class="student-checkbox h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 dark:border-gray-300 rounded"
                                    >
                                    <label for="student-{{ $student->id }}" class="ml-3 block">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                @if($student->photo_by)
                                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/uploads/'.$student->photo) }}" alt="{{ $student->name }}">
                                                @else
                                                    <div class="h-10 w-10 rounded-full bg-green-100 dark:bg-green-100 flex items-center justify-center">
                                                        <span class="text-green-600 dark:text-green-600 font-medium text-sm">{{ substr($student->name, 0, 2) }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-800">{{ $student->name }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-600">Adm: {{ $student->adm_no }} | Class: {{ $student->my_class->name ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    
                    <!-- Pagination -->
                    @if($availableStudents->hasPages())
                        <div class="px-6 py-3 bg-gray-50 dark:bg-gray-100 border-t border-gray-200 dark:border-gray-200">
                            {{ $availableStudents->links() }}
                        </div>
                    @endif
                @else
                    <div class="py-8 px-6 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <p class="mt-4 text-gray-500 dark:text-gray-600">No students available for the selected class or all students are already assigned to this dormitory.</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Selected Students Count -->
        @if(isset($selectedStudents) && is_array($selectedStudents) && count($selectedStudents) > 0)
            <div class="flex items-center bg-green-50 dark:bg-green-50 p-4 rounded-md">
                <div class="flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-400 dark:text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800 dark:text-green-800">
                        {{ count($selectedStudents) }} students selected
                    </p>
                </div>
                <div class="ml-auto">
                    <button 
                        type="button"
                        wire:click="clearSelection" 
                        class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded-full text-red-700 dark:text-red-700 bg-red-100 dark:bg-red-100 hover:bg-red-200 dark:hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-150"
                    >
                        Clear Selection
                    </button>
                </div>
            </div>
        @endif
        
        <!-- Form Actions -->
        <div class="pt-5 border-t border-gray-200 dark:border-gray-200 flex justify-end space-x-3">
            <button 
                type="button"
                wire:click="closeAddStudents" 
                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-300 shadow-sm text-sm font-medium rounded-full text-gray-700 dark:text-gray-700 bg-white dark:bg-white hover:bg-gray-50 dark:hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors duration-150"
            >
                Cancel
            </button>
            <button 
                type="submit"
                :disabled="!isFormValid"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-full shadow-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-150"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 -ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Add Selected Students
            </button>
        </div>
    </form>
</div>
