<div class="container mt-4">

    <!-- Session Alerts -->
    <x-flash-messages />

    <!-- Class Selection -->
    <div class="form-group">
        <label for="class">Select Class:</label>
        <div wire:loading wire:target="selectedClass">
            <div class="d-flex justify-content-center my-3">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
        <div wire:loading.remove>
            <select wire:model.lazy="selectedClass" class="form-control" id="class">
                <option value="">-- Select Class --</option>
                @foreach ($classes as $class)
                <option value="{{ $class->id }}">{{ $class->name }}</option>
                @endforeach
            </select>
        </div>
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
        <div wire:loading wire:target="selectedExam">
            <div class="d-flex justify-content-center my-3">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
        <div wire:loading.remove>
            <select wire:model.lazy="selectedExam" class="form-control" id="exam">
                <option value="">-- Select Exam --</option>
                @foreach ($exams as $exam)
                <option value="{{ $exam->id }}">{{ $exam->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    @endif

    <!-- Section Selection -->
    @if ($selectedExam)
    <div wire:loading wire:target="selectedSection">
        <div class="d-flex justify-content-center my-3">
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
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
            <div class="d-flex justify-content-center my-3">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
        <div class="mt-3">
            <div class="alert alert-info">
                <strong>Total Students in this Stream:</strong> {{ count($students) }}
            </div>
        </div>
        
        <form wire:submit.prevent="assignMarks" class="mt-3">
            <div class="table-responsive" style="overflow-x: auto;">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width: 50px;">S/N</th>
                            <th style="width: 200px;">Student Name</th>
                            <th style="width: 150px;">Admission No</th>
                            @foreach ($subjects as $subject)
                                <th style="width: 150px;">{{ $subject->subject_name }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @if ($students->isEmpty())
                            <tr>
                                <td colspan="{{ count($subjects) + 3 }}" class="text-danger text-center">
                                    No students available for this section.
                                </td>
                            </tr>
                        @else
                            @foreach ($students as $index => $student)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                                    <td>{{ $student->adm_no }}</td>
                                    @foreach ($subjects as $subject)
                                        <td style="width: 150px;">
                                            <input type="number" 
                                                   class="form-control" 
                                                   style="width: 150px;" 
                                                   wire:model.defer="marks.{{ $student->id }}.{{ $subject->id }}" 
                                                   min="0" max="100"
                                                   data-bs-toggle="tooltip" 
                                                   data-bs-placement="top" 
                                                   title="{{ $student->first_name }} {{ $student->last_name }} ({{ $student->adm_no }})">
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
        
        @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Initialize Bootstrap tooltips
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
        
                // Show tooltip on focus and input
                tooltipTriggerList.forEach(function(tooltipTriggerEl) {
                    tooltipTriggerEl.addEventListener('focus', function() {
                        var tooltip = bootstrap.Tooltip.getInstance(tooltipTriggerEl);
                        if (tooltip) {
                            tooltip.show();
                        }
                    });
        
                    tooltipTriggerEl.addEventListener('blur', function() {
                        var tooltip = bootstrap.Tooltip.getInstance(tooltipTriggerEl);
                        if (tooltip) {
                            tooltip.hide();
                        }
                    });
        
                    // Show tooltip on input
                    tooltipTriggerEl.addEventListener('input', function() {
                        var tooltip = bootstrap.Tooltip.getInstance(tooltipTriggerEl);
                        if (tooltip) {
                            tooltip.show();
                        }
                    });
                });
            });
        </script>
        @endpush
        
        
       
        
        
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

{{-- @push('styles')
<!-- Tooltip Styling and Script -->
<style>
    .custom-tooltip {
        position: absolute;
        background-color: #343a40;
        color: #fff;
        padding: 5px 10px;
        border-radius: 5px;
        display: none;
        z-index: 9999;
    }
</style>

@endpush --}}


