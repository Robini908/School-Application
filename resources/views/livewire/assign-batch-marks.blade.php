<div class="container mt-4">

    <!-- Session Alerts -->
    <x-flash-messages />

    <!-- Class Selection -->
    <div class="form-group">
        <label for="class">Select Class:</label>
        <select wire:model.lazy="selectedClass" class="form-control" id="class">
            <option value="">-- Select Class --</option>
            @foreach ($classes as $class)
            <option value="{{ $class->id }}">{{ $class->name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Selected Class Information -->
    @if ($selectedClass)
    <div class="alert alert-info mt-3">
        <strong>Instructions:</strong> You have selected the class <strong>{{ $selectedClassName }}</strong>.
        Now, please choose the exam from the list below to assign marks for students.
    </div>
    @endif

    <!-- Exam Selection -->
    @if ($selectedClass)
    <div class="form-group mt-3">
        <label for="exam">Select Exam:</label>
        <select wire:model.lazy="selectedExam" class="form-control" id="exam">
            <option value="">-- Select Exam --</option>
            @foreach ($exams as $exam)
            <option value="{{ $exam->id }}">{{ $exam->name }}</option>
            @endforeach
        </select>
    </div>
    @endif

    <!-- Section Selection -->
    @if ($selectedExam)
    <div wire:loading wire:target="selectedSection">
        <p>Loading students for selected section...</p>
    </div>

    <div wire:loading.remove>
        @if ($sections->isNotEmpty())
        <div class="form-group mt-3">
            <label>Select Section:</label>
            <div class="d-flex flex-wrap">
                @foreach ($sections as $section)
                <div class="form-check me-3">
                    <input type="radio" class="form-check-input" wire:model.lazy="selectedSection"
                        value="{{ $section->id }}" id="section-{{ $section->id }}" />
                    <label class="form-check-label" for="section-{{ $section->id }}">{{ $section->name }}</label>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Marks Assignment Form -->
        @if ($selectedSection)
        <div wire:loading wire:target="assignMarks">
            <p>Loading student marks...</p>
        </div>

        <form wire:submit.prevent="assignMarks" class="mt-3">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Admission No</th>
                            @foreach ($subjects as $subject)
                            <th>{{ $subject->subject_name }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @if ($students->isEmpty())
                        <tr>
                            <td colspan="{{ count($subjects) + 2 }}" class="text-danger text-center">
                                No students available for this section.
                            </td>
                        </tr>
                        @else
                        @foreach ($students as $student)
                        <tr>
                            <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                            <td>{{ $student->adm_no }}</td>
                            @foreach ($subjects as $subject)
                            <td>
                                <input type="number" class="form-control" style="width: 120px;"
                                    wire:model.defer="marks.{{ $student->id }}.{{ $subject->id }}" min="0" max="100" />
                                @error("marks.{$student->id}.{$subject->id}")
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            <button type="submit" class="btn btn-primary mt-3">
                {{ $buttonText }}
            </button>
        </form>
        @else
        <p class="text-danger mt-3">You have not selected any section. Please select a section to proceed.</p>
        @endif
        @else
        <p class="text-danger mt-3">No sections available for this class and exam combination.</p>
        @endif
    </div>
    @endif

    <!-- View Assigned Marks -->
    @if ($selectedExam && $selectedSection)
    <h3 class="mt-4">Assigned Marks for Section: {{ $sections->find($selectedSection)->name }}</h3>
    <h5>Exam: {{ $exams->find($selectedExam)->name ?? 'N/A' }}</h5>
    <h5>Grading System: {{ $exams->find($selectedExam)->gradingSystem->name ?? 'N/A' }}</h5>
    <div class="table-responsive mt-3">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Admission No</th>
                    @foreach ($subjects as $subject)
                    <th>{{ $subject->subject_name }}</th>
                    @endforeach
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($students as $student)
                <tr>
                    <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                    <td>{{ $student->adm_no }}</td>
                    @foreach ($subjects as $subject)
                    @php
                    $examMark = \App\Models\ExamMarks::where([
                    'student_id' => $student->id,
                    'exam_id' => $selectedExam,
                    'subject_id' => $subject->id,
                    ])->first();
                    @endphp
                    <td>
                        @if (isset($editable[$student->id]))
                        <input type="number" class="form-control" style="width: 120px;"
                            wire:model.defer="marks.{{ $student->id }}.{{ $subject->id }}" min="0" max="100"
                            placeholder="{{ $examMark ? $examMark->marks : 'Add marks' }}" />
                        @else
                        <span>{{ $examMark ? $examMark->marks : 'N/A' }}</span>
                        @endif
                    </td>
                    @endforeach
                    <td>
                        @if (isset($editable[$student->id]))
                        <button wire:click="updateMarks({{ $student->id }})" class="btn btn-success btn-sm mt-1">
                            Update
                        </button>


                        @else
                        <button wire:click="editMarks({{ $student->id }})" class="btn btn-primary btn-sm mt-1">
                            Edit
                        </button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

</div>