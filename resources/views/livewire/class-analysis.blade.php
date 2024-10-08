<div>
  

    <!-- Dropdown to select exam -->
    <div class="form-group">
        <label for="exam">Select Exam:</label>
        <select wire:model="examId" wire:change="getGradesCount" id="exam" class="form-control">
            <option value="">-- Choose Exam --</option>
            @foreach ($exams as $exam)
                <option value="{{ $exam->id }}">{{ $exam->name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Display error message if any -->
    @if ($errorMessage)
        <div class="alert alert-danger mt-3">
            {{ $errorMessage }}
        </div>
    @endif

    <!-- Show spinner while loading grades data -->
    <div wire:loading wire:target="examId" class="text-center">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading grades...</span>
        </div>
    </div>

    <!-- Grades table -->
    @if (!empty($gradesCount))
        <table class="table table-bordered table-hover mt-4">
            <thead class="thead-dark">
                <tr>
                    <th>Class</th>
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
                    <tr>
                        <td>{{ $classData['class_name'] }}</td>
                        @foreach ($classData['grades'] as $grade => $count)
                            <td>{{ $count }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Display message if no grades found -->
    @if (empty($gradesCount) && !$errorMessage)
        <div class="alert alert-info mt-4">
            No grades data found for the selected exam.
        </div>
    @endif
</div>
