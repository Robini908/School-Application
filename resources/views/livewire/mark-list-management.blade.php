<div class="card  p-3 shadow-lg border rounded" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
    <h2 class="h4 mb-4">Mark List Management</h2>
    <x-flash-messages />
    <!-- Show selection UI only if not showing details -->
    @if (!$showingDetails)
        <div class="form-row mb-3">
            <!-- Class Selection -->
            <div class="col-md-4">
                <label for="class" class="form-label">Select Class:</label>
                <div class="input-group">
                    <select wire:model.live="classId" id="class" class="form-control" wire:key="class-selection">
                        <option value="">Select Class</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}" wire:key="class-{{ $class->id }}">
                                {{ $class->name }}</option>
                        @endforeach
                    </select>
                    <div wire:loading wire:target="classId" class="input-group-append">
                        <span class="input-group-text">
                            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Exam Selection -->
            @if ($classId)
                <div class="col-md-4">
                    <label for="exam" class="form-label">Select Exam:</label>
                    <div class="input-group">
                        <select wire:model.live="examId" id="exam" class="form-control" wire:key="exam-selection">
                            <option value="">Select Exam</option>
                            @foreach ($exams as $exam)
                                <option value="{{ $exam->id }}" wire:key="exam-{{ $exam->id }}">
                                    {{ $exam->name }}</option>
                            @endforeach
                        </select>
                        <div wire:loading wire:target="examId" class="input-group-append">
                            <span class="input-group-text">
                                <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                            </span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Section Selection -->
            @if ($examId)

                <div class="col-md-4">
                    <label for="section" class="form-label">Select Section:</label>
                    <div class="input-group">
                        <select wire:model.live="sectionId" id="section" class="form-control"
                            wire:key="section-selection">
                            <option value="">Select Section</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}" wire:key="section-{{ $section->id }}">
                                    {{ $section->name }}
                                </option>
                            @endforeach
                        </select>
                        <div wire:loading wire:target="sectionId" class="input-group-append">
                            <span class="input-group-text">
                                <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                            </span>
                        </div>
                    </div>
                </div>
            @endif

        </div>

        <!-- Fetch Marks Button -->
        @if ($sectionId)
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
        @endif


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

                                    <button wire:click ="fetchStudentDetails('{{ $mark['adm_no'] }}')"
                                        class="btn btn-primary d-flex align-items-center">
                                        Generate Report
                                        <div wire:loading wire:target="fetchStudentDetails('{{ $mark['adm_no'] }}')"
                                            class="spinner-border spinner-border-sm text-light ms-2" role="status">
                                        </div>
                                    </button>
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
        <link
            href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Georgia:wght@400&family=Courier+New:wght@400&family=Montserrat:wght@400;700&family=Poppins:wght@400;600&family=Open+Sans:wght@400;600&display=swap"
            rel="stylesheet">

        <div class="card p-3 shadow-lg border rounded" wire:key="details-card"
            style="background: linear-gradient(135deg, #f8f9fa, #e9ecef); overflow: hidden;">
            <div class="d-flex justify-content-end mb-4">
                <!-- Export to PDF Button -->
                <button wire:click="exportToPDF" wire:loading.attr="disabled" wire:loading.class="btn-secondary"
                    wire:target="exportToPDF" class="btn btn-danger btn-sm mx-1">
                    <i class="fas fa-file-pdf"></i>
                    <span wire:loading.remove wire:target="exportToPDF">PDF</span>
                    <span wire:loading wire:target="exportToPDF">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    </span>
                </button>

                <!-- Export to Excel Button -->
                <button wire:click="exportToExcel" wire:loading.attr="disabled" wire:loading.class="btn-secondary"
                    wire:target="exportToExcel" class="btn btn-success btn-sm mx-1">
                    <i class="fas fa-file-excel"></i>
                    <span wire:loading.remove wire:target="exportToExcel">Excel</span>
                    <span wire:loading wire:target="exportToExcel">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    </span>
                </button>

                <button wire:click="closeDetails" class="btn btn-danger btn-sm"
                    style="border-radius: 5px; font-family: 'Roboto', sans-serif;">Close</button>
            </div>

            <div class="card-header bg-primary text-white"
                style="border-top-left-radius: 15px; border-top-right-radius: 15px;">
                <h5
                    style="font-family: 'Montserrat', sans-serif; font-size: 24px; text-align: center; letter-spacing: 1px;">
                    Details for Admission No: {{ $selectedAdmNo ?? 'N/A' }}</h5>
            </div>
            <div class="card-body" style="background-color: #f9f9f9;">
                <div class="row mb-4">
                    <div class="col-md-3 d-flex align-items-center justify-content-center">
                        @if (!empty($studentAdditionalDetails['photo']))
                            <img src="{{ asset($studentAdditionalDetails['photo']) }}" alt="Student Photo"
                                class="img-thumbnail"
                                style="width: 100px; height: 100px; border-radius: 50%; border: 2px solid #007bff;">
                        @else
                            <span class="text-muted"
                                style="font-size: 16px; font-family: 'Courier New', monospace;">No
                                Image</span>
                        @endif
                    </div>
                    <div class="col-md-9">
                        <h4
                            style="font-weight: bold; font-family: 'Georgia', serif; font-size: 20px; color: #333; text-transform: capitalize;">
                            {{ $studentAdditionalDetails['first_name'] ?? 'N/A' }}
                            {{ $studentAdditionalDetails['middle_name'] ?? '' }}
                            {{ $studentAdditionalDetails['last_name'] ?? '' }}
                        </h4>
                        <div class="row mt-2">
                            <div class="col-6" style="font-family: 'Poppins', sans-serif;">
                                <strong>Class:</strong>
                                {{ $studentAdditionalDetails['class_name'] ?? 'N/A' }}
                            </div>
                            <div class="col-6" style="font-family: 'Poppins', sans-serif;">
                                <strong>Section:</strong>
                                {{ $studentAdditionalDetails['section_name'] ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="row mt-1">
                            <div class="col-6" style="font-family: 'Poppins', sans-serif;">
                                <strong>Gender:</strong> {{ $studentAdditionalDetails['gender'] ?? 'N/A' }}
                            </div>
                            <div class="col-6" style="font-family: 'Poppins', sans-serif;">
                                <strong>Admission No:</strong> {{ $selectedAdmNo ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                </div>

                @if (!empty($studentDetails))
                    <h6
                        style="font-weight: bold; font-family: 'Courier New', monospace; font-size: 18px; margin-top: 20px; color: #555;">
                        Exam: {{ $examName ?? 'N/A' }}</h6>
                    <h6
                        style="font-weight: bold; font-family: 'Courier New', monospace; font-size: 18px; color: #555;">
                        Grading
                        System: {{ $gradingSystemDetails['name'] ?? 'N/A' }}</h6>
                    <p class="text-muted mb-2" style="font-size: 16px; color: #777;">Description:
                        {{ $gradingSystemDetails['description'] ?? 'N/A' }}</p>

                    <h6
                        style="font-weight: bold; font-family: 'Courier New', monospace; font-size: 18px; margin-top: 20px;">
                        Subject Marks & Grades</h6>
                    <table class="table table-bordered table-striped text-center"
                        style="border-radius: 8px; overflow: hidden;">
                        <thead class="thead-light" style="background-color: #007bff; color: white;">
                            <tr>
                                <th style="font-family: 'Montserrat', sans-serif;">Subject</th>
                                <th style="font-family: 'Montserrat', sans-serif;">Marks</th>
                                <th style="font-family: 'Montserrat', sans-serif;">Grade</th>
                                <th style="font-family: 'Montserrat', sans-serif;">Remark</th>
                                <th style="font-family: 'Montserrat', sans-serif;">GPA</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($studentDetails as $detail)
                                <tr wire:key="subject-{{ $loop->index }}">
                                    <td style="font-family: 'Roboto', sans-serif;">
                                        {{ $detail['subject_name'] ?? 'N/A' }}
                                    </td>
                                    <td style="font-family: 'Roboto', sans-serif;">{{ $detail['marks'] ?? 'N/A' }}
                                    </td>
                                    <td style="font-family: 'Roboto', sans-serif;">{{ $detail['grade'] ?? 'N/A' }}
                                    </td>
                                    <td style="font-family: 'Roboto', sans-serif;">{{ $detail['remark'] ?? 'N/A' }}
                                    </td>
                                    <td style="font-family: 'Roboto', sans-serif;">{{ $detail['gpa'] ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="card mt-4" style="border-radius: 15px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
                        <div class="card-header" style="background-color: #f8f9fa;">
                            <h6 style="font-weight: bold; text-align: center; font-family: 'Montserrat', sans-serif;">
                                Overall
                                Performance</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p style="font-family: 'Roboto', sans-serif;"><strong>Total Marks:</strong>
                                        {{ $totalMarks ?? 'N/A' }}</p>
                                    <p style="font-family: 'Roboto', sans-serif;"><strong>Mean Score:</strong>
                                        {{ $meanScore ?? 'N/A' }}</p>
                                    <p style="font-family: 'Roboto', sans-serif;"><strong>Total Points:</strong>
                                        {{ $totalPoints ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p style="font-family: 'Roboto', sans-serif;"><strong>Position in Class:</strong>
                                        {{ $classPosition ?? 'N/A' }}</p>
                                    <p style="font-family: 'Roboto', sans-serif;"><strong>Position in Stream:</strong>
                                        {{ $streamPosition ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <p class="text-muted text-center" style="font-size: 16px; color: #777;">No details found for this
                        student.
                    </p>
                @endif
            </div>


        </div>
    @endif

</div>
