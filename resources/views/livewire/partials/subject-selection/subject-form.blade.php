@php
    use App\Models\Subject;
@endphp

@if ($showSubjectForm)
<div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden"
     x-data="{
        selectedStudentCount: {{ count($selectedStudents) }},
        selectedSubjectCount: {{ count($selectedSubjects) }},
        init() {
            document.addEventListener('livewire:initialized', () => {
                $watch('$wire.selectedStudents', value => {
                    this.selectedStudentCount = value ? value.length : 0;
                });
                $watch('$wire.selectedSubjects', value => {
                    this.selectedSubjectCount = value ? value.length : 0;
                });
            });
        }
     }"
     x-init="init()">
    <!-- Google-inspired header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-medium text-white flex items-center">
                <svg class="w-5 h-5 text-white mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                Subject Selection
            </h3>
            
            <div class="flex items-center gap-4">
                <div class="bg-white/20 backdrop-blur-sm rounded-full px-3 py-1 text-sm text-white">
                    <span class="font-medium" x-text="selectedStudentCount"></span> students
                </div>
                <div class="bg-white/20 backdrop-blur-sm rounded-full px-3 py-1 text-sm text-white">
                    <span class="font-medium" x-text="selectedSubjectCount"></span> subjects
                </div>
            </div>
        </div>
    </div>
    
    <div class="p-4">
        <!-- Google-style Material panel for selected students -->
        <div class="mb-4 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
            <div class="px-4 py-3 bg-gray-100 border-b border-gray-200 flex justify-between items-center">
                <h4 class="text-sm font-medium text-gray-700 flex items-center">
                    <svg class="w-4 h-4 text-blue-600 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Selected Students ({{ count($selectedStudents) }})
                </h4>
                @if(count($selectedStudents) > 0)
                <button wire:click="clearSelection" class="text-xs text-gray-600 hover:text-blue-600">
                    Clear selection
                </button>
                @endif
            </div>
            <div class="p-3 max-h-32 overflow-y-auto">
        @if (!empty($selectedStudents))
                <div class="flex flex-wrap gap-2">
                    @foreach ($unassignedStudents as $student)
                        @if (in_array($student->id, $selectedStudents))
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                {{ $student->first_name }} {{ $student->last_name }}
                                <button type="button" 
                                        wire:click="removeStudentFromSelection({{ $student->id }})" 
                                            class="flex-shrink-0 ml-1 h-4 w-4 rounded-full inline-flex items-center justify-center text-blue-500 hover:text-blue-600 hover:bg-blue-200 focus:outline-none">
                                        <svg class="h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </span>
                        @endif
                    @endforeach
                </div>
                @else
                    <div class="text-center py-2 text-sm text-gray-500">
                        No students selected
                    </div>
                @endif
            </div>
        </div>

        <!-- Subject Selection with tabular layout -->
        <div x-data="{ 
            activeTab: 'elective',
            compulsorySubjects: @js($subjects['compulsory'] ?? Subject::where('type', 'compulsory')->get()->pluck('id')->toArray()),
            ensureCompulsorySelected() {
                if (Array.isArray(this.compulsorySubjects)) {
                    this.compulsorySubjects.forEach(id => {
                        if (!$wire.selectedSubjects.includes(id)) {
                            $wire.selectedSubjects.push(id);
                        }
                    });
                }
            },
            init() {
                // Ensure elective tab is active by default
                this.activeTab = 'elective';
                
                // Watch for changes to selectedSubjects
                $watch('$wire.selectedSubjects', () => {
                    this.ensureCompulsorySelected();
                });
                
                // Initial check to ensure compulsory subjects
                this.ensureCompulsorySelected();
            },
            tabs: {
                @php
                    $groupedSubjects = \App\Models\Subject::getSubjectsGroupedByCategoryAndType();
                    $tabsJson = [];
                    foreach ($groupedSubjects as $type => $categories) {
                        $tabsJson[] = "'$type': '$type'";
                    }
                    echo implode(',', $tabsJson);
                @endphp
            }
        }" x-init="init()" class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <!-- Google-style Tabs -->
            <div class="border-b border-gray-200">
                <div class="flex px-4">
                    <template x-for="(label, name) in tabs" :key="name">
                        <button 
                            @click="activeTab = name" 
                            :class="{
                                'border-blue-600 text-blue-600': activeTab === name, 
                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== name
                            }"
                            class="py-3 px-4 border-b-2 font-medium text-sm focus:outline-none transition-all duration-100" 
                            x-text="label.charAt(0).toUpperCase() + label.slice(1) + ' Subjects'">
                        </button>
                    </template>
                </div>
            </div>
            
            <!-- Tab Content with tabular layout -->
            @foreach ($groupedSubjects as $type => $categories)
                <div x-show="activeTab === '{{ $type }}'" class="divide-y divide-gray-200">
                    <div class="px-4 py-3 bg-gray-50 flex justify-between items-center">
                        <div class="flex items-center">
                            <h4 class="text-sm font-medium text-gray-700">
                            {{ ucfirst($type) }} Subjects
                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $type === 'compulsory' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                            @php
                                $count = 0;
                                foreach ($categories as $category) {
                                    $count += count($category);
                                }
                                echo $count;
                            @endphp
                            </span>
                        </h4>
                        </div>
                        
                        @if($type === 'elective')
                            <div class="flex items-center">
                                <span class="text-xs text-gray-500 mr-2">
                                    Select subjects for students
                                </span>
                                <button wire:click="$refresh" 
                                        class="text-xs bg-blue-50 hover:bg-blue-100 text-blue-600 font-medium py-1 px-2 rounded inline-flex items-center">
                                    <svg class="w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Update
                                </button>
                            </div>
                        @else
                            <div class="text-xs text-gray-500">
                                Automatically selected
                            </div>
                        @endif
                    </div>

                        @foreach ($categories as $category => $subjects)
                        <div class="overflow-hidden">
                            <div class="px-4 py-2 bg-gray-100 border-y border-gray-200">
                                <h5 class="text-xs uppercase tracking-wide font-semibold text-gray-600">{{ $category }}</h5>
                            </div>
                            
                            <div class="divide-y divide-gray-100">
                                <!-- Google-style Tabular Layout -->
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-8">
                                                Select
                                            </th>
                                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Subject Name
                                            </th>
                                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Code
                                            </th>
                                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($subjects as $subject)
                                            <tr class="{{ $subject->is_compulsory ? 'bg-green-50' : '' }} hover:bg-gray-50 transition-colors">
                                                <td class="px-4 py-2 whitespace-nowrap">
                                                @if ($subject->is_compulsory)
                                                        <input type="checkbox" checked disabled class="h-4 w-4 text-green-600 border-green-300 rounded cursor-not-allowed">
                                                @else
                                                        <input type="checkbox" 
                                                               wire:model.defer="selectedSubjects" 
                                                               wire:key="subject-{{ $subject->id }}"
                                                               value="{{ $subject->id }}" 
                                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded transition-colors">
                                                @endif
                                                </td>
                                                <td class="px-4 py-2 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $subject->subject_name }}
                                            </div>
                                                </td>
                                                <td class="px-4 py-2 whitespace-nowrap">
                                                    <div class="text-sm text-gray-500">
                                                        {{ $subject->subject_code }}
                                        </div>
                                                </td>
                                                <td class="px-4 py-2 whitespace-nowrap">
                                                    @if ($subject->is_compulsory)
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                            Required
                                                        </span>
                                                    @else
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                            Elective
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        @endforeach
                </div>
            @endforeach
        </div>

        <!-- Action Buttons in Google style -->
        <div class="mt-4 flex justify-end space-x-2">
            <button wire:click="toggleShowStudentCard" 
                    wire:loading.attr="disabled" 
                    wire:target="toggleShowStudentCard"
                    class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                <svg wire:loading.remove wire:target="toggleShowStudentCard" class="mr-1.5 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <svg wire:loading wire:target="toggleShowStudentCard" class="animate-spin mr-1.5 h-4 w-4 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Cancel</span>
            </button>
            <button wire:click="submitSubjectSelection" 
                    wire:loading.attr="disabled" 
                    wire:target="submitSubjectSelection"
                    x-bind:disabled="selectedStudentCount === 0"
                    x-bind:class="{'opacity-50 cursor-not-allowed': selectedStudentCount === 0}"
                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                <svg wire:loading.remove wire:target="submitSubjectSelection" class="mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <svg wire:loading wire:target="submitSubjectSelection" class="animate-spin mr-1.5 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span wire:loading.remove wire:target="submitSubjectSelection">Save Selection</span>
                <span wire:loading wire:target="submitSubjectSelection">Saving...</span>
            </button>
        </div>
    </div>
</div>
@endif 