<div class="container mt-1">
    <x-flash-messages />
    {{-- Filter Selection --}}
    <div>
        <h4 class="h5 text-muted">Filters for Viewing and Assigning Marks</h4>
        <div class="form-row mb-1">
            <!-- Class Selection -->
            <div class="col-md-4">
                <label for="class">Select Class:</label>
                <div class="input-group">
                    <select wire:model.live="selectedClass" id="class" class="form-control">
                        <option value="">-- Select Class --</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}" wire:key="class-{{ $class->id }}">
                                {{ $class->name }}
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
            @if ($selectedClass)
                <div class="col-md-4">
                    <label for="exam">Select Exam:</label>
                    <div class="input-group">
                        <select wire:model.live="selectedExam" id="exam" class="form-control">
                            <option value="">-- Select Exam --</option>
                            @foreach ($exams as $exam)
                                <option value="{{ $exam->id }}" wire:key="exam-{{ $exam->id }}">
                                    {{ $exam->name }}</option>
                            @endforeach
                        </select>
                        <div wire:loading wire:target="selectedExam" class="input-group-append">
                            <span class="input-group-text">
                                <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                            </span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Subject Selection -->
            @if ($selectedExam)
                <div class="col-md-4">
                    <label for="subject">Select Subject:</label>
                    <div class="input-group">
                        <select wire:model.live="selectedSubject" id="subject" class="form-control">
                            <option value="">-- Select Subject --</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}" wire:key="subject-{{ $subject->id }}">
                                    {{ $subject->subject_name }}</option>
                            @endforeach
                        </select>
                        <div wire:loading wire:target="selectedSubject" class="input-group-append">
                            <span class="input-group-text">
                                <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                            </span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Stream and Students Selection -->
    @if ($selectedClass && $selectedExam && $selectedSubject)
    <div>
        <!-- Header -->
        <h4 class="h5 text-muted mb-4">Marks Assignment for {{ $selectedSubjectName }}</h4>

        <!-- Stream Selection -->
        <div class="form-group mb-4 alert alert-info">
            <label class="font-weight-bold">Select the Stream:</label>
            <div class="d-flex flex-wrap">
                @foreach ($sections as $section)
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
            <!-- Students List -->
            <h5 class="h6 font-weight-bold mb-3">Students in {{ $selectedClassName }} - {{ $sections->where('id', $selectedSection)->first()->name ?? 'N/A' }} that sat for {{ $selectedSubjectName }}:</h5>

            @if ($students->isNotEmpty())
                @if ($assignedMarks->isEmpty())
                    <!-- No Marks Assigned -->
                    <div class="alert alert-warning mb-4">No marks have been assigned yet. Please enter the marks below:</div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Student (Admission No)</th>
                                <th>Marks</th>
                                <th>Special Grade</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $student)
                                <tr>
                                    <td>{{ $student->first_name }} {{ $student->last_name }} ({{ $student->adm_no }})</td>
                                    <td>
                                        @if ($this->isSubjectSelectionEnabled($selectedClass) && !$this->isStudentEnrolledInSubject($student->id, $selectedSubject))
                                            <input type="number" class="form-control" placeholder="Not Enrolled in {{ $selectedSubjectName }}" disabled style="background-color: #f8d7da; border-color: #f5c2c7; cursor: not-allowed; color: #dc3545; font-weight: bold;" title="Student not enrolled in this subject" />
                                        @else
                                            <input type="number" wire:model="marks.{{ $student->id }}" class="form-control" placeholder="Enter marks" min="0" max="100" @if (!empty($specialGrades[$student->id])) disabled @endif />
                                        @endif
                                    </td>
                                    <td>
                                        @if ($this->isSubjectSelectionEnabled($selectedClass) && !$this->isStudentEnrolledInSubject($student->id, $selectedSubject))
                                            <select class="form-control" disabled style="background-color: #f8d7da; border-color: #f5c2c7; cursor: not-allowed; color: #dc3545; font-weight: bold;" title="Student not enrolled in this subject">
                                                <option value="">Not Enrolled</option>
                                            </select>
                                        @else
                                            <select wire:model="specialGrades.{{ $student->id }}" class="form-control" @if (!empty($marks[$student->id])) disabled @endif>
                                                <option value="">Assign Special Grade</option>
                                                <option value="X">X - Absence</option>
                                                <option value="Y">Y - Malpractice</option>
                                                <option value="Z">Z - Misconduct</option>
                                            </select>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button wire:click="assignMarks" class="btn btn-primary mt-3">Assign Marks/Grades</button>
                @else
                    <!-- Marks Already Assigned -->
                    <h5 class="h6 font-weight-bold mb-3">Marks Assignment for {{ $selectedSubjectName }}:</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Student (Admission No)</th>
                                <th>Marks</th>
                                <th>Special Grade</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $student)
                                <tr>
                                    <td>{{ $student->first_name }} {{ $student->last_name }} ({{ $student->adm_no }})</td>
                                    <td>
                                        @if ($editingMarkId === $student->id)
                                            <input type="number" wire:model="marks.{{ $student->id }}" class="form-control" placeholder="Enter marks" min="0" max="100" @if ($this->getStudentSpecialGrade($student->id) || ($this->isSubjectSelectionEnabled($selectedClass) && !$this->isStudentEnrolledInSubject($student->id, $selectedSubject))) disabled @endif />
                                        @else
                                            @if ($this->getStudentMark($student->id))
                                                {{ $this->getStudentMark($student->id) }}
                                            @elseif ($this->isSubjectSelectionEnabled($selectedClass) && !$this->isStudentEnrolledInSubject($student->id, $selectedSubject))
                                                <input type="number" class="form-control" placeholder="Not Enrolled in {{ $selectedSubjectName }}" disabled style="background-color: #f8d7da; border-color: #f5c2c7; cursor: not-allowed; color: #dc3545; font-weight: bold;" title="Student not enrolled in this subject" />
                                            @else
                                                <input type="number" wire:model="marks.{{ $student->id }}" class="form-control" placeholder="Enter marks" min="0" max="100" />
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if ($editingMarkId === $student->id)
                                            <select wire:model="specialGrades.{{ $student->id }}" class="form-control" @if ($this->getStudentMark($student->id) || ($this->isSubjectSelectionEnabled($selectedClass) && !$this->isStudentEnrolledInSubject($student->id, $selectedSubject))) disabled @endif>
                                                <option value="">Select Grade</option>
                                                <option value="X">X - Absence</option>
                                                <option value="Y">Y - Malpractice</option>
                                                <option value="Z">Z - Misconduct</option>
                                            </select>
                                        @else
                                            @if ($this->getStudentSpecialGrade($student->id))
                                                {{ $this->getStudentSpecialGrade($student->id) }}
                                            @elseif ($this->isSubjectSelectionEnabled($selectedClass) && !$this->isStudentEnrolledInSubject($student->id, $selectedSubject))
                                                <select class="form-control" disabled style="background-color: #f8d7da; border-color: #f5c2c7; cursor: not-allowed; color: #dc3545; font-weight: bold;" title="Student not enrolled in this subject">
                                                    <option value="">Not Enrolled</option>
                                                </select>
                                            @else
                                                <select wire:model="specialGrades.{{ $student->id }}" class="form-control">
                                                    <option value="">Select Grade</option>
                                                    <option value="X">X - Absence</option>
                                                    <option value="Y">Y - Malpractice</option>
                                                    <option value="Z">Z - Misconduct</option>
                                                </select>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if ($this->getStudentMark($student->id) || $this->getStudentSpecialGrade($student->id))
                                            @if ($editingMarkId === $student->id)
                                                <button wire:click="updateMark({{ $student->id }})" class="btn btn-success btn-sm">Save</button>
                                                <button wire:click="$set('editingMarkId', null)" class="btn btn-secondary btn-sm">Cancel</button>
                                            @else
                                                <button wire:click="editMark({{ $student->id }})" class="btn btn-warning btn-sm">Edit</button>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button wire:click="assignMarks" class="btn btn-primary mt-3">Assign Marks/Grades</button>
                @endif
            @else
                <!-- No Students Found -->
                <p class="mt-4 text-info">No students found in the selected stream for {{ $selectedSubjectName }}.</p>
            @endif
        @else
            <!-- No Stream Selected -->
            <p class="mt-4 text-warning">Please select a stream to view the students.</p>
        @endif
    </div>
@endif

</div>
