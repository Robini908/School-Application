<!-- Single Exam Analysis Table -->
@if($showTable && $selectedExam)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">Exam Analysis Results</h2>
            <p class="mt-1 text-sm text-gray-600">
                Results for <span class="font-medium text-[#217346]">{{ $classes->firstWhere('id', $selectedClass)?->name ?? 'Selected Class' }}</span>
            </p>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th scope="col" class="sticky top-0 z-10 bg-gray-50 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Student Name
                        </th>
                        <th scope="col" class="sticky top-0 z-10 bg-gray-50 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Stream
                        </th>
                        @foreach($subjects as $subject)
                            <th scope="col" class="sticky top-0 z-10 bg-gray-50 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $subject->subject_name }}
                            </th>
                        @endforeach
                        <th scope="col" class="sticky top-0 z-10 bg-gray-50 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Total Marks
                        </th>
                        <th scope="col" class="sticky top-0 z-10 bg-gray-50 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Total Points
                        </th>
                        <th scope="col" class="sticky top-0 z-10 bg-gray-50 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Class Position
                        </th>
                        <th scope="col" class="sticky top-0 z-10 bg-gray-50 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Stream Position
                        </th>
                        <th scope="col" class="sticky top-0 z-10 bg-gray-50 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Mean Score
                        </th>
                        <th scope="col" class="sticky top-0 z-10 bg-gray-50 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Mean Grade
                        </th>
                        <th scope="col" class="sticky top-0 z-10 bg-gray-50 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($marks as $result)
                        <tr class="{{ $result['has_special_grade'] ? 'bg-red-50' : '' }} hover:bg-gray-50">
                            <!-- Student Name -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-[#217346] flex items-center justify-center">
                                        <span class="text-xs font-medium text-white">
                                            {{ substr($result['student_name'], 0, 2) }}
                                        </span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $result['student_name'] }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Stream -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $result['stream'] }}</div>
                            </td>

                            <!-- Subject Marks -->
                            @foreach($subjects as $subject)
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $mark = $result['marks'][$subject->id] ?? '--';
                                        $grade = $result['grades'][$subject->id] ?? '--';
                                    @endphp
                                    
                                    @if($mark === '--' && $grade === '--')
                                        <span class="text-gray-400">--</span>
                                    @else
                                        <div class="flex items-center space-x-1">
                                            @if(is_numeric($mark))
                                                <span class="text-sm text-gray-900">{{ number_format($mark, 1) }}</span>
                                                <span class="text-xs text-gray-500">{{ $grade }}</span>
                                                @if($mark >= 75)
                                                    <svg class="h-4 w-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                @endif
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ 
                                                    $mark === 'X' ? 'bg-red-100 text-red-800' :
                                                    ($mark === 'Y' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')
                                                }}">
                                                    {{ $mark }}
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                            @endforeach

                            <!-- Total Marks -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ number_format($result['total_marks'], 1) }}
                                </div>
                            </td>

                            <!-- Total Points -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ number_format($result['total_points'], 1) }}
                                </div>
                            </td>

                            <!-- Class Position -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $result['position'] }}
                                </div>
                            </td>

                            <!-- Stream Position -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $result['stream_position'] }}
                                </div>
                            </td>

                            <!-- Mean Score -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ number_format($result['mean_score'], 2) }}
                                </div>
                            </td>

                            <!-- Mean Grade -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ 
                                    $result['has_special_grade'] ? 'bg-red-100 text-red-800' : 'bg-[#E2EFDA] text-[#217346]'
                                }}">
                                    {{ $result['mean_grade'] }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="generateReport({{ $result['student_id'] }})"
                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-[#217346] hover:bg-[#1a5c38] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#217346]">
                                    <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Report
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif 