<div class="container mt-4" x-data="{ showInstruction: true }">

    @if (session()->has('message'))
    <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    <div class="form-group">
        <label for="class">Select Class:</label>
        <select wire:model="selectedClass" class="form-control" id="class" @change="showInstruction = true">
            <option value="">-- Select Class --</option>
            @foreach ($classes as $class)
            <option value="{{ $class->id }}">{{ $class->name }}</option>
            @endforeach
        </select>
    </div>

    <template x-if="showInstruction && '{{ $selectedClass }}'">
        <div class="alert alert-info mt-3" x-init="$el.style.transition = 'opacity 0.5s';" x-show="showInstruction"
            x-transition.opacity.out.duration.1000ms>
            <strong>Instructions:</strong> You have selected the class <strong>{{ $selectedClassName }}</strong>.
            Now, please choose the exam from the list below to assign marks for students.


        </div>
    </template>

    @if ($selectedClass)
    <div class="form-group mt-3">
        <label for="exam">Select Exam:</label>
        <select wire:model="selectedExam" class="form-control" id="exam" @change="showInstruction = false">
            <option value="">-- Select Exam --</option>
            @foreach ($exams as $exam)
            <option value="{{ $exam->id }}">{{ $exam->name }}</option>
            @endforeach
        </select>
    </div>
    @endif

    @if ($selectedExam)
    @if ($students->isNotEmpty())
    <div class="alert alert-info mt-3" x-show.transition.opacity="!showInstruction">
        @if ($examDetails)
        You are now assigning marks for <strong>{{ $examDetails->name }}</strong> in <strong>{{ $selectedClassName
            }}</strong>.

        <p>Details on that exam are:</p>

        <div>
            <p><strong>Term:</strong> {{ $examDetails->term }}</p>
            <p><strong>Year:</strong> {{ $examDetails->year }}</p>
            <p>
                <strong>Grading System:</strong> {{ $examDetails->grading_system }}
                <button class="btn btn-sm btn-info ml-2" wire:click="toggleGradingRanges">
                    View Grading Ranges
                </button>
            </p>

            {{-- Display grading ranges --}}
            @if($showGradingRanges)
            <div class="mt-2">
                <h5>Grading Ranges</h5>
                <ul>
                    @foreach ($gradingRanges as $range)
                    <li>
                        <strong>{{ $range->range_from }} - {{ $range->range_to }}:</strong>
                        Grade: {{ $range->grade }} ({{ $range->remark }}), GPA: {{ $range->gpa }}
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
        @else
        <p>No exam selected.</p>
        @endif
    </div>



    <form wire:submit.prevent="assignMarks" class="mt-3">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-blue">
                    <tr>
                        <th>Student Name</th>
                        <th>Admission No</th>
                        @foreach ($subjects as $subject)
                        <th>{{ $subject->subject_name }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students as $student)
                    <tr>
                        <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                        <td>{{ $student->adm_no }}</td>
                        @foreach ($subjects as $subject)
                        <td>
                            <input type="number" class="form-control"
                                wire:model.defer="marks.{{ $student->id }}.{{ $subject->id }}" min="0" max="100"
                                style="width: 150px;" />
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <button type="submit" class="btn btn-primary mt-3" wire:loading.attr="disabled">
            <span wire:loading wire:target="assignMarks">Assigning...</span>
            <span wire:loading.remove wire:target="assignMarks">Submit Marks</span>
        </button>
    </form>
    @else
    <p class="text-danger mt-3">No students available for this class and exam combination.</p>
    @endif
    @endif
</div>