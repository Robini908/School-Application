<div class="card  p-3 shadow-lg border rounded" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">

    <!-- Session Alerts -->
    <x-flash-messages />

    <!-- Class Selection -->
    <div class="form-group col-span-6">
        <label for="class">Select Class:</label>
        <select wire:model.live="selectedClass" class="form-control" id="class">
            <option value="">-- Select Class --</option>
            @foreach ($classes as $class)
                <option value="{{ $class->id }}">{{ $class->name }}</option>
            @endforeach
        </select>

        <div wire:loading wire:target="selectedClass">
            <div class="d-flex justify-content-center my-3">
                <div class="spinner-border spinner-border-sm text-primary" role="status"></div>

            </div>
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
            <select wire:model.live="selectedExam" class="form-control" id="exam">
                <option value="">-- Select Exam --</option>
                @foreach ($exams as $exam)
                    <option value="{{ $exam->id }}">{{ $exam->name }}</option>
                @endforeach
            </select>
            <div wire:loading wire:target="selectedExam">
                <div class="d-flex justify-content-center my-3">
                    <div class="spinner-border spinner-border-sm text-primary" role="status"></div>

                </div>
            </div>

        </div>
    @endif

    <!-- Section Selection -->
    @if ($selectedExam)


        <div>
            @if ($sections->isNotEmpty())


                <div class="form-group mb-4 alert alert-info">
                    <label>Select the Stream:</label>
                    <div class="d-flex flex-wrap">
                        @foreach ($sections as $section)
                            <div class="form-check mr-4 mb-2">
                                <input type="radio" wire:model.live="selectedSection" value="{{ $section->id }}"
                                    id="section_{{ $section->id }}" class="form-check-input"
                                    wire:key="section-{{ $section->id }}">
                                <label for="section_{{ $section->id }}"
                                    class="form-check-label">{{ $section->name }}</label>

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
                <div class="mt-3">
                    <div class="alert alert-info">
                        <b>{{ count($students) }}</b> students found in this section.
                    </div>
                </div>
            
                <form wire:submit.prevent="assignMarks" class="mt-3" id="marks-form">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" style="width: 5%;">#</th>
                                    <th scope="col" style="width: 25%;">Student Name</th>
                                    <th scope="col" style="width: 15%;">Admission No</th>
                                    @foreach ($subjects as $subject)
                                        <th scope="col" style="width: 20%;">{{ $subject->subject_name }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @if ($students->isEmpty())
                                    <tr>
                                        <td colspan="{{ count($subjects) + 3 }}" class="text-center text-muted">
                                            No students available for this section.
                                        </td>
                                    </tr>
                                @else
                                    @foreach ($students as $index => $student)
                                        @php
                                            $isSubjectSelectionEnabled = $this->isSubjectSelectionEnabled($student->my_class_id);
                                        @endphp
                                        <tr>
                                            <th scope="row">{{ $index + 1 }}</th>
                                            <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                                            <td>{{ $student->adm_no }}</td>
                                            @foreach ($subjects as $subject)
                                                <td>
                                                    @if (!$isSubjectSelectionEnabled || $student->subjects->contains($subject->id))
                                                        <div class="d-flex gap-2 align-items-center">
                                                            <input type="number" class="form-control"
                                                                wire:model="marks.{{ $student->id }}.{{ $subject->id }}"
                                                                min="0" max="100" placeholder="Marks"
                                                                style="width: 100px;"
                                                                @if (!empty($this->specialGrades[$student->id][$subject->id])) disabled @endif>
                                                            <select class="form-control"
                                                                wire:model="specialGrades.{{ $student->id }}.{{ $subject->id }}"
                                                                style="width: 100px;"
                                                                @if (!empty($this->marks[$student->id][$subject->id])) disabled @endif>
                                                                <option value="">Grade</option>
                                                                <option value="X">X</option>
                                                                <option value="Y">Y</option>
                                                                <option value="Z">Z</option>
                                                            </select>
                                                        </div>
                                                        @error("marks.{$student->id}.{$subject->id}")
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                        @error("specialGrades.{$student->id}.{$subject->id}")
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    @else
                                                        <span class="text-muted">Not Enrolled</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
            
                    <div class="mt-3 text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> {{ $buttonText }}
                        </button>
                    </div>
                </form>
            @else
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-exclamation-circle"></i> Please select a section to proceed.
                </div>
            @endif
            @else
                <p class="text-danger mt-3">No sections available for this class and exam combination.</p>
            @endif
        </div>

    @endif
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize Bootstrap tooltips
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
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

                // Real-time validation for marks input fields
                const inputs = document.querySelectorAll('.mark-input');
                const successAlert = document.getElementById('success-alert');

                // Function to validate all inputs and highlight empty ones
                function validateMarks() {
                    let allValid = true;

                    // Loop through each input field
                    inputs.forEach(input => {
                        if (input.value === '') {
                            // If input is empty, highlight it with a red border
                            input.style.border = '2px solid red';
                            allValid = false;
                        } else {
                            // If input is filled, remove any red border
                            input.style.border = '';
                        }
                    });

                    // Display success message if all fields are valid
                    if (allValid) {
                        successAlert.style.display = 'block';
                        successAlert.innerText = 'All marks have been assigned successfully!';
                    } else {
                        successAlert.style.display = 'none'; // Hide success alert if any field is invalid
                    }
                }

                // Initial validation when the form loads
                validateMarks();

                // Real-time validation as user interacts with the form
                inputs.forEach(input => {
                    input.addEventListener('input', function() {
                        validateMarks(); // Validate fields as they are updated
                    });
                });
            });
        </script>
    @endpush

</div>
