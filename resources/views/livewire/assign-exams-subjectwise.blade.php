<div class="container mt-4">
    
    <x-flash-messages />
    
    {{-- <div wire:loading>
        <div class="alert alert-info mb-4">Loading... Please wait.</div>
    </div> --}}

    {{-- Assigned Marks Card --}}
    <div class="card mb-4">
        <div class="card-body">
            <h3 class="h5 font-weight-bold">Assigned Exam Marks</h3>

            @if ($selectedClass && $selectedExam && $selectedSubject)
            @if (collect($assignedMarks)->isNotEmpty())
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Student (Admission No)</th>
                        <th>Assigned Marks</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($assignedMarks as $mark)
                    <tr>
                        <td>{{ $selectedSubjectName }}</td>
                        <td>{{ $mark->student->first_name }} {{ $mark->student->last_name }} ({{ $mark->student->adm_no
                            }})</td>
                        <td>
                            @if($editingMarkId === $mark->id)
                            <input type="number" wire:model.defer="marks.{{ $mark->student_id }}"
                                class="form-control" />
                            @else
                            {{ $mark->marks }}
                            @endif
                        </td>
                        <td>
                            @if($editingMarkId === $mark->id)
                            <button wire:click="updateMark({{ $mark->id }})"
                                class="btn btn-success btn-sm">Save</button>
                            <button wire:click="$set('editingMarkId', null)"
                                class="btn btn-secondary btn-sm">Cancel</button>
                            @else
                            <button wire:click="editMark({{ $mark->id }})" class="btn btn-warning btn-sm">Edit</button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p class="mt-4 text-info">No marks have been assigned for {{ $selectedSubjectName }}.</p>
            @endif
            @else
            <p class="mt-4 text-warning">Please select a class, exam, and subject to view the assigned marks.</p>
            @endif
        </div>
    </div>

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

            @if ($selectedSection && $students->isNotEmpty())
            <h5 class="h6 font-weight-bold">Students in {{ $selectedSubjectName }}:</h5>

            @if ($assignedMarks->count() === $students->count())
            <div class="alert alert-info mb-4">
                All students have been assigned marks for {{ $selectedSubjectName }} in the {{ $selectedExamName }}
                exam.
            </div>
            @else
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Student (Admission No)</th>
                        <th>Marks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                    @if(!$assignedMarks->contains('student_id', $student->id))
                    <tr>
                        <td>{{ $student->first_name }} {{ $student->last_name }} ({{ $student->adm_no }})</td>
                        <td><input type="number" wire:model="marks.{{ $student->id }}" class="form-control"
                                placeholder="Enter marks" min="0"></td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
            <button wire:click="assignMarks" class="btn btn-primary mt-3">Assign Marks</button>
            @endif
            @else
            <p class="mt-4 text-info">No students available in the selected stream for {{ $selectedSubjectName }}.</p>
            @endif

        </div>
    </div>
    @endif
</div>