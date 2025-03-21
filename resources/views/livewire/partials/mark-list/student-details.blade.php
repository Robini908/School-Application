@if ($studentDetails)
    <div class="space-y-8">
        <!-- Header with Student Info -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="relative">
                <div class="absolute inset-0 bg-[#217346] opacity-10"></div>
                <div class="relative px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-6">
                            <div class="h-20 w-20 rounded-full bg-[#217346] flex items-center justify-center text-white text-2xl font-semibold">
                                {{ substr($studentDetails['student_name'], 0, 2) }}
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">{{ $studentDetails['student_name'] }}</h2>
                                <div class="mt-1 flex items-center space-x-4 text-sm text-gray-600">
                                    <span class="flex items-center">
                                        <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        {{ $studentDetails['adm_no'] }}
                                    </span>
                                    <span class="flex items-center">
                                        <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        {{ $studentDetails['class_name'] }} - {{ $studentDetails['section_name'] }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex space-x-3">
                            <button wire:click="exportStudentDetailsPDF" 
                                class="inline-flex items-center px-4 py-2 bg-[#217346] text-white rounded-lg shadow-sm text-sm font-medium hover:bg-[#1a5c38] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#217346] transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Export PDF
                            </button>
                            <button x-on:click="currentStep = 2" 
                                class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg shadow-sm text-sm font-medium hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Exam Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Performance Summary -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Performance Summary</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div class="bg-[#217346]/5 rounded-lg p-4">
                            <dt class="text-sm font-medium text-gray-600">Total Marks</dt>
                            <dd class="mt-2 text-3xl font-bold text-[#217346]">{{ $studentDetails['total_marks'] }}</dd>
                        </div>
                        <div class="bg-blue-50 rounded-lg p-4">
                            <dt class="text-sm font-medium text-gray-600">Mean Score</dt>
                            <dd class="mt-2 text-3xl font-bold text-blue-600">{{ $studentDetails['mean_score'] }}</dd>
                        </div>
                        <div class="bg-purple-50 rounded-lg p-4">
                            <dt class="text-sm font-medium text-gray-600">Position</dt>
                            <dd class="mt-2 text-3xl font-bold text-purple-600">{{ $studentDetails['position'] }}</dd>
                        </div>
                        <div class="bg-amber-50 rounded-lg p-4">
                            <dt class="text-sm font-medium text-gray-600">Grade</dt>
                            <dd class="mt-2 text-3xl font-bold text-amber-600">{{ $studentDetails['grade'] }}</dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Exam Details -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Exam Information</h3>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-600">Exam Name</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $studentDetails['exam_name'] }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-600">Academic Year</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $studentDetails['academic_year'] }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-600">Grading System</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $gradingSystemDetails['name'] }}</dd>
                            <dd class="mt-1 text-sm text-gray-500">{{ $gradingSystemDetails['description'] }}</dd>
                            <dd class="mt-1 text-sm text-gray-500">Effective from: {{ \Carbon\Carbon::parse($gradingSystemDetails['effective_date'])->format('M d, Y') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Subject Marks Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-900">Subject Performance</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Marks</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($studentDetails['marks'] as $subject => $mark)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $subject }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if (isset($mark['special_grade']))
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $mark['special_grade'] === 'X' ? 'bg-red-100 text-red-800' : 
                                               ($mark['special_grade'] === 'Y' ? 'bg-yellow-100 text-yellow-800' : 
                                                'bg-gray-100 text-gray-800') }}">
                                            {{ $mark['special_grade'] }}
                                        </span>
                                    @else
                                        <div class="text-sm font-semibold {{ $mark['marks'] >= 75 ? 'text-[#217346]' : 'text-gray-900' }}">
                                            {{ $mark['marks'] }}
                                            @if($mark['marks'] >= 75)
                                                <svg class="inline-block ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $mark['grade'] === 'A' ? 'bg-green-100 text-green-800' : 
                                           ($mark['grade'] === 'B' ? 'bg-blue-100 text-blue-800' : 
                                           ($mark['grade'] === 'C' ? 'bg-yellow-100 text-yellow-800' : 
                                           ($mark['grade'] === 'D' ? 'bg-orange-100 text-orange-800' : 
                                            'bg-red-100 text-red-800'))) }}">
                                        {{ $mark['grade'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-600">{{ $mark['remarks'] }}</div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@else
    <div class="flex items-center justify-center h-96">
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900">No Student Details Available</h3>
            <p class="mt-2 text-sm text-gray-500">Please select a student from the marks table to view their details.</p>
        </div>
    </div>
@endif 