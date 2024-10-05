<div class="container mt-4">
    <x-flash-messages />



    {{-- Filter Selection --}}
    <div class="card">
        <div class="card-body">
            <h4 class="h5 text-muted">Filters for Viewing and Assigning Marks</h4>

            <div class="form-group mb-3">
                <label for="class">Select Class:</label>
                <select wire:model="selectedClass" id="class" class="form-control">
                    <option value="">-- Select Class --</option>
                    @foreach($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
                <div wire:loading wire:target="selectedClass" class="text-info"><small>Loading class data...</small>
                </div>
            </div>

            <div class="form-group mb-3">
                <label for="exam">Select Exam:</label>
                <select wire:model="selectedExam" id="exam" class="form-control">
                    <option value="">-- Select Exam --</option>
                    @foreach($exams as $exam)
                    <option value="{{ $exam->id }}">{{ $exam->name }}</option>
                    @endforeach
                </select>
                <div wire:loading wire:target="selectedExam" class="text-info"><small>Loading exam data...</small></div>
            </div>

            <div class="form-group mb-3">
                <label for="subject">Select Subject:</label>
                <select wire:model="selectedSubject" id="subject" class="form-control">
                    <option value="">-- Select Subject --</option>
                    @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                    @endforeach
                </select>
                <div wire:loading wire:target="selectedSubject" class="text-info"><small>Loading subject data...</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Stream and Students Selection --}}
    @if ($selectedClass && $selectedExam && $selectedSubject)
    <div class="card mt-4">
        <div class="card-body">
            <h4 class="h5 text-muted">Marks Assignment for {{ $selectedSubjectName }}</h4>

            <div class="form-group mb-4 alert alert-info">
                <label>Select the Stream:</label>
                <div class="d-flex flex-wrap">
                    @foreach($sections as $section)
                    <div class="form-check mr-3">
                        <input type="radio" wire:model="selectedSection" value="{{ $section->id }}"
                            id="section_{{ $section->id }}" class="form-check-input">
                        <label for="section_{{ $section->id }}" class="form-check-label">{{ $section->name }}</label>
                    </div>
                    @endforeach
                </div>
            </div>

            @if ($selectedSection)
            @if (collect($assignedMarksForTable)->isNotEmpty() || collect($students)->isNotEmpty())
            <h5 class="h6 font-weight-bold">Students in {{ $selectedSubjectName }}:</h5>

            @if (collect($assignedMarks)->isEmpty())
            <div class="alert alert-warning">No marks have been assigned yet. Please enter the marks below:</div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Student (Admission No)</th>
                        <th>Marks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                    <tr>
                        <td>{{ $student->first_name }} {{ $student->last_name }} ({{ $student->adm_no }})</td>
                        <td><input type="number" wire:model.defer="marks.{{ $student->id }}" class="form-control" placeholder="Enter marks" min="0" max="100"></td>
                        @error("marks.{$student->id}")
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <button wire:click="assignMarks" class="btn btn-primary mt-3">Assign Marks</button>
            @else
            <h5 class="h6 font-weight-bold">Marks Assignment for {{ $selectedSubjectName }}:</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Student (Admission No)</th>
                        <th>Marks</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                    <tr>
                        <td>{{ $student->first_name }} {{ $student->last_name }} ({{ $student->adm_no }})</td>
                        @if (collect($assignedMarks)->contains('student_id', $student->id))
                        <td>
                            @if($editingMarkId === $student->id)
                            <input type="number" wire:model.defer="marks.{{ $student->id }}" class="form-control"
                                min="0" max="100" />
                            @else
                            {{ collect($assignedMarks)->where('student_id', $student->id)->first()->marks ?? 'N/A' }}
                            @endif
                        </td>
                        <td>
                            @if($editingMarkId === $student->id)
                            <button wire:click="updateMark({{ $student->id }})"
                                class="btn btn-success btn-sm">Save</button>
                            <button wire:click="$set('editingMarkId', null)"
                                class="btn btn-secondary btn-sm">Cancel</button>
                            @else
                            <button wire:click="editMark({{ $student->id }})"
                                class="btn btn-warning btn-sm">Edit</button>
                            @endif
                        </td>
                        @else
                        <td><input type="number" wire:model.defer="marks.{{ $student->id }}" class="form-control"
                                placeholder="Enter marks" min="0" max="100"></td>
                        <td></td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <button wire:click="assignMarks" class="btn btn-primary mt-3">Assign Marks</button>
            @endif
            @else
            <p class="mt-4 text-info">No students found in the selected stream for {{ $selectedSubjectName }}.</p>
            @endif
            @else
            <p class="mt-4 text-warning">Please select a stream to view the students.</p>
            @endif
        </div>
    </div>
    @endif

</div>