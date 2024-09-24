<div class="container p-4 bg-white rounded shadow">
    <h2 class="h4 mb-4">Mark List Management</h2>

    <!-- Show selection UI only if not showing details -->
    @if (!$showingDetails)
    <div class="mb-3">
        <label for="class" class="form-label">Select Class:</label>
        <select wire:model="classId" id="class" class="form-control">
            <option value="">Select Class</option>
            @foreach ($classes as $class)
            <option value="{{ $class->id }}">{{ $class->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="exam" class="form-label">Select Exam:</label>
        <select wire:model="examId" id="exam" class="form-control">
            <option value="">Select Exam</option>
            @foreach ($exams as $exam)
            <option value="{{ $exam->id }}">{{ $exam->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="section" class="form-label">Select Section:</label>
        <select wire:model="sectionId" id="section" class="form-control">
            <option value="">Select Section</option>
            @foreach ($sections as $section)
            <option value="{{ $section->id }}">{{ $section->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-4">
        <button wire:click="fetchMarks" class="btn btn-primary" wire:loading.attr="disabled"
            wire:loading.class="btn-secondary">
            <span wire:loading.remove>Fetch Marks</span>
            <span wire:loading>
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Loading...
            </span>
        </button>
    </div>

    @if ($marks && $marks->isNotEmpty())
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>Student Name</th>
                    <th>Admission Number</th>
                    @foreach ($marks->first()['marks'] as $subjectName => $value)
                    <th>{{ $subjectName }}</th>
                    @endforeach
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($marks as $mark)
                <tr>
                    <td>{{ $mark['student_name'] ?? 'N/A' }}</td>
                    <td>{{ $mark['adm_no'] ?? 'N/A' }}</td>
                    @foreach ($marks->first()['marks'] as $subjectName => $subjectMark)
                    <td>{{ $mark['marks'][$subjectName] ?? 'N/A' }}</td>
                    @endforeach
                    <td>
                        <button wire:click="fetchStudentDetails('{{ $mark['adm_no'] }}')"
                            class="btn btn-info">Details</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <p class="mt-4 text-muted">No marks available for the selected criteria.</p>
    @endif
    @endif

    <!-- Details Card -->
    @if ($showingDetails)
    <div class="card mt-4">
        <div class="card-header">
            <h5>Details for Admission No: {{ $selectedAdmNo }}</h5>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-3 d-flex align-items-center">
                    @if ($studentAdditionalDetails['photo'])
                    <img src="{{ asset($studentAdditionalDetails['photo']) }}" alt="Student Photo" class="img-thumbnail"
                        style="width: 50px; height: 50px;">
                    @else
                    <span class="text-muted">No Image</span>
                    @endif
                </div>
                <div class="col-md-9">
                    <h6 class="font-weight-bold">{{ $studentAdditionalDetails['first_name'] }}
                        {{ $studentAdditionalDetails['middle_name'] }}
                        {{ $studentAdditionalDetails['last_name'] }}</h6>
                    <div class="row">
                        <div class="col-6"><strong>Class:</strong> {{ $studentAdditionalDetails['class_name'] }}</div>
                        <div class="col-6"><strong>Section:</strong> {{ $studentAdditionalDetails['section_name'] }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6"><strong>Gender:</strong> {{ $studentAdditionalDetails['gender'] }}</div>
                        <div class="col-6"><strong>Admission No:</strong> {{ $selectedAdmNo }}</div>
                    </div>
                </div>
            </div>

            @if (!empty($studentDetails))
            <h6 class="font-weight-bold">Subject Marks</h6>
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>Subject</th>
                        <th>Marks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($studentDetails as $detail)
                    <tr>
                        <td>{{ $detail['subject_name'] }}</td>
                        <td>{{ $detail['marks'] ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p class="text-muted">No details found for this student.</p>
            @endif
        </div>

        <div class="card-footer text-center">
            <button wire:click="closeDetails" class="btn btn-danger btn-sm">Close</button>
        </div>
    </div>


    @endif

</div>