<div>
    <!-- Dropdown to select exam -->
    <div class="form-group">
        <label for="exam">Select Exam:</label>
        <select wire:model.live="examId" wire:change="getGradesCount" id="exam" class="form-control">
            <option value="">-- Choose Exam --</option>
            @foreach ($exams as $exam)
                <option value="{{ $exam->id }}">{{ $exam->name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Display error message if any -->
    @if ($errorMessage)
        <div class="alert alert-danger mt-3" style="border-radius: 0.5rem; font-weight: bold;">
            <strong>Error!</strong> {{ $errorMessage }}
        </div>
    @endif

    <!-- Show spinner while loading grades data -->
    <div wire:loading wire:target="examId" class="text-center">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading grades...</span>
        </div>
    </div>

    <!-- Display the exam analysis header -->
    @if ($examId && !empty($gradesCount) && !$errorMessage)
        <h3 class="mt-4 text-primary font-weight-bold">Analysis for Exam: <span class="text-success">{{ $examName }}</span> - Class: <span class="text-success">{{ $className }}</span></h3>
    @endif

    <!-- Grades table: Display only if no error and grades data exists -->
    @if (!empty($gradesCount) && !$errorMessage)
        <table class="table table-bordered table-hover mt-4" style="border-radius: 0.5rem; overflow: hidden;">
            <thead class="thead-light">
                <tr>
                    <th rowspan="2">Class</th>
                    <th rowspan="2">Section</th>
                    <th rowspan="2">Student Count</th>
                    <th rowspan="2">Mean Score</th>
                    <th colspan="14" class="text-center">Grades Distribution</th>
                </tr>
                <tr>
                    <th>A</th>
                    <th>A-</th>
                    <th>B+</th>
                    <th>B</th>
                    <th>B-</th>
                    <th>C+</th>
                    <th>C</th>
                    <th>C-</th>
                    <th>D+</th>
                    <th>D</th>
                    <th>D-</th>
                    <th>E</th>
                    <th>F</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($gradesCount as $classId => $classData)
                    @php $sectionCount = count($classData['sections']); @endphp
                    @foreach ($classData['sections'] as $sectionId => $sectionData)
                        <tr>
                            @if ($loop->first)
                                <td rowspan="{{ $sectionCount }}">{{ $classData['class_name'] }}</td> <!-- Class name displayed only once -->
                            @endif
                            <td>{{ $sectionData['section_name'] }}</td>
                            <td>{{ $sectionData['student_count'] }}</td> <!-- Display student count -->
                            <td>{{ number_format($sectionData['mean_score'], 2) }}</td> <!-- Display mean score -->
                            @foreach ($sectionData['grades'] as $grade => $count)
                                <td>{{ $count }}</td> <!-- Display grade counts -->
                            @endforeach
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Display message if no grades found -->
    @if (empty($gradesCount) && !$errorMessage)
        <div class="alert alert-info mt-4" style="border-radius: 0.5rem; font-weight: bold;">
            <strong>Note:</strong> No grades data found for the selected exam: <span class="text-success">{{ $examName }}</span>.
        </div>
    @endif

    <!-- Additional logic to set error message if there are no grades -->
    @if (empty($gradesCount) && $examId)
        @php
            $errorMessage = "No student results found for the selected exam: " . $examName;
        @endphp
    @endif
</div>
