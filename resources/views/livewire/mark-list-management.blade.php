<div class="container p-4 bg-white rounded shadow">
    <h2 class="h4 mb-4">Mark List Management</h2>
    <x-flash-messages />
    <!-- Show selection UI only if not showing details -->
    @if (!$showingDetails)
    <div class="mb-3">
        <label for="class" class="form-label">Select Class:</label>
        <select wire:model="classId" id="class" class="form-control" wire:key="class-selection">
            <option value="">Select Class</option>
            @foreach ($classes as $class)
            <option value="{{ $class->id }}" wire:key="class-{{ $class->id }}">{{ $class->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="exam" class="form-label">Select Exam:</label>
        <select wire:model="examId" id="exam" class="form-control" wire:key="exam-selection">
            <option value="">Select Exam</option>
            @foreach ($exams as $exam)
            <option value="{{ $exam->id }}" wire:key="exam-{{ $exam->id }}">{{ $exam->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="section" class="form-label">Select Section:</label>
        <select wire:model="sectionId" id="section" class="form-control" wire:key="section-selection">
            <option value="">Select Section</option>
            @foreach ($sections as $section)
            <option value="{{ $section->id }}" wire:key="section-{{ $section->id }}">{{ $section->name }}</option>
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
                <tr wire:key="student-{{ $mark['adm_no'] }}">
                    <td>{{ $mark['student_name'] ?? 'N/A' }}</td>
                    <td>{{ $mark['adm_no'] ?? 'N/A' }}</td>
                    @foreach ($marks->first()['marks'] as $subjectName => $subjectMark)
                    <td>{{ $mark['marks'][$subjectName] ?? 'N/A' }}</td>
                    @endforeach
                    <td>
                        <button wire:click="fetchStudentDetails('{{ $mark['adm_no'] }}')" class="btn btn-info">Generate
                            Report</button>
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

    <div class="card mt-4" wire:key="details-card">
        <div class="card-header bg-primary text-white">
            <h5>Details for Admission No: {{ $selectedAdmNo ?? 'N/A' }}</h5>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-3 d-flex align-items-center justify-content-center">
                    @if (!empty($studentAdditionalDetails['photo']))
                    <img src="{{ asset($studentAdditionalDetails['photo']) }}" alt="Student Photo" class="img-thumbnail"
                        style="width: 100px; height: 100px;">
                    @else
                    <span class="text-muted">No Image</span>
                    @endif
                </div>
                <div class="col-md-9">
                    <h4 class="font-weight-bold">
                        {{ $studentAdditionalDetails['first_name'] ?? 'N/A' }}
                        {{ $studentAdditionalDetails['middle_name'] ?? '' }}
                        {{ $studentAdditionalDetails['last_name'] ?? '' }}
                    </h4>
                    <div class="row mt-2">
                        <div class="col-6"><strong>Class:</strong> {{ $studentAdditionalDetails['class_name'] ?? 'N/A'
                            }}</div>
                        <div class="col-6"><strong>Section:</strong> {{ $studentAdditionalDetails['section_name'] ??
                            'N/A' }}</div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-6"><strong>Gender:</strong> {{ $studentAdditionalDetails['gender'] ?? 'N/A' }}
                        </div>
                        <div class="col-6"><strong>Admission No:</strong> {{ $selectedAdmNo ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>

            @if (!empty($studentDetails))
            <!-- Display Exam Name -->
            <h6 class="font-weight-bold mb-3">Exam: {{ $examName ?? 'N/A' }}</h6>

            <!-- Display Grading System Name -->
            <h6 class="font-weight-bold mb-3">Grading System: {{ $gradingSystemDetails['name'] ?? 'N/A' }}</h6>

            <!-- Display Grading System Description -->
            <p class="text-muted mb-2">Description: {{ $gradingSystemDetails['description'] ?? 'N/A' }}</p>

            <!-- Subject Marks and Grades -->
            <h6 class="font-weight-bold mb-3">Subject Marks & Grades</h6>
            <table class="table table-bordered table-striped text-center">
                <thead class="thead-light">
                    <tr>
                        <th>Subject</th>
                        <th>Marks</th>
                        <th>Grade</th>
                        <th>Remark</th>
                        <th>GPA</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($studentDetails as $detail)
                    <tr wire:key="subject-{{ $loop->index }}">
                        <td>{{ $detail['subject_name'] ?? 'N/A' }}</td>
                        <td>{{ $detail['marks'] ?? 'N/A' }}</td>
                        <td>{{ $detail['grade'] ?? 'N/A' }}</td>
                        <td>{{ $detail['remark'] ?? 'N/A' }}</td>
                        <td>{{ $detail['gpa'] ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>

                <div class="card mt-4">
                    <div class="card-header">
                        <h6>Overall Performance</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Total Marks:</strong> {{ $totalMarks ?? 'N/A' }}</p>
                                <p><strong>Mean Grade:</strong> {{ $meanGrade ?? 'N/A' }}</p>
                                <p><strong>Mean Score:</strong> {{ $meanScore ?? 'N/A' }}</p>
                                <p><strong>Total Points:</strong> {{ $totalPoints ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Position in Class:</strong> {{ $classPosition ?? 'N/A' }}</p>
                                <p><strong>Position in Stream:</strong> {{ $streamPosition ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

            </table>


            @else
            <p class="text-muted text-center">No details found for this student.</p>
            @endif
        </div>

        <div class="card-footer text-center">
            <button wire:click="closeDetails" class="btn btn-danger btn-sm">Close</button>
        </div>
    </div>


    @endif
</div>