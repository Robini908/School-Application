<div class="container mt-4">
    <x-flash-messages />



    {{-- Filter Selection --}}
    <div class="card">
        <div class="card-body">
            <h4 class="h5 text-muted">Filters for Viewing and Assigning Marks</h4>

            <div class="form-row mb-3">
                <!-- Class Selection -->
                <div class="col-md-4">
                    <label for="class">Select Class:</label>
                    <div class="input-group">
                        <select wire:model.live="selectedClass" id="class" class="form-control">
                            <option value="">-- Select Class --</option>
                            @foreach($classes as $class)
                            <option value="{{ $class->id }}" wire:key="class-{{ $class->id }}">{{ $class->name }}
                            </option>
                            @endforeach
                        </select>
                        <div wire:loading wire:target="selectedClass" class="input-group-append">
                            <span class="input-group-text">
                                <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Exam Selection -->
                <div class="col-md-4">
                    <label for="exam">Select Exam:</label>
                    <div class="input-group">
                        <select wire:model.live="selectedExam" id="exam" class="form-control">
                            <option value="">-- Select Exam --</option>
                            @foreach($exams as $exam)
                            <option value="{{ $exam->id }}" wire:key="exam-{{ $exam->id }}">{{ $exam->name }}</option>
                            @endforeach
                        </select>
                        <div wire:loading wire:target="selectedExam" class="input-group-append">
                            <span class="input-group-text">
                                <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Subject Selection -->
                <div class="col-md-4">
                    <label for="subject">Select Subject:</label>
                    <div class="input-group">
                        <select wire:model.live="selectedSubject" id="subject" class="form-control">
                            <option value="">-- Select Subject --</option>
                            @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" wire:key="subject-{{ $subject->id }}">{{
                                $subject->subject_name }}</option>
                            @endforeach
                        </select>
                        <div wire:loading wire:target="selectedSubject" class="input-group-append">
                            <span class="input-group-text">
                                <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stream and Students Selection -->
    @if ($selectedClass && $selectedExam && $selectedSubject)
    <div class="card mt-4">
        <div class="card-body">
            <h4 class="h5 text-muted">Marks Assignment for {{ $selectedSubjectName }}</h4>

            <div class="form-group mb-4 alert alert-info">
                <label>Select the Stream:</label>
                <div class="d-flex flex-wrap">
                    @foreach($sections as $section)
                    <div class="form-check mr-4 mb-2">
                        <input type="radio" wire:model.live="selectedSection" value="{{ $section->id }}"
                            id="section_{{ $section->id }}" class="form-check-input"
                            wire:key="section-{{ $section->id }}">
                        <label for="section_{{ $section->id }}" class="form-check-label">{{ $section->name }}</label>

                        @if ($selectedSection == $section->id)
                        <div wire:loading wire:target="selectedSection" class="mt-1">
                            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>



            @if ($selectedSection)
            <h5 class="h6 font-weight-bold">Students in {{ $sections->where('id', $selectedSection)->first()->name ??
                'N/A' }} that sat for {{ $selectedSubjectName }}:</h5>

            @if (collect($assignedMarksForTable)->isNotEmpty() || collect($students)->isNotEmpty())
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
                        <td><input type="number" wire:model="marks.{{ $student->id }}" class="form-control"
                                placeholder="Enter marks" min="0" max="100"></td>
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
                            <input type="number" wire:model="marks.{{ $student->id }}" class="form-control"
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
                        <td><input type="number" wire:model="marks.{{ $student->id }}" class="form-control"
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