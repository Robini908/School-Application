<div>
    <div class="card mb-4">
        @if ($errors->has('calculatePositions'))
        <div class="alert alert-danger">
            {{ $errors->first('calculatePositions') }}
        </div>
        @endif

        <div class="card-header">
            <h5>Filter Students</h5>
        </div>
        <div class="card-body">
            <div class="mb-4">
                <div class="form-row mt-4 mx-2">
                    <!-- First Row -->
                    <div class="row">
                        <!-- Year Filter -->
                        <div class="col-md-4 mb-2">
                            <label for="examYear">Year:</label>
                            <div class="input-group">
                                <input type="number" wire:model.live.debounce.500ms="selectedExamYear" id="examYear"
                                    class="form-control" placeholder="Enter Exam Year">
                                <div wire:loading wire:target="selectedExamYear" class="input-group-append">
                                    <span class="input-group-text">
                                        <div class="spinner-border spinner-border-sm" role="status"></div>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Year Admitted Filter -->
                        <div class="col-md-4 mb-2">
                            <label for="yearAdmitted">Year Admitted:</label>
                            <div class="input-group">
                                <input type="number" wire:model.live.debounce.500ms="selectedYearAdmitted"
                                    id="yearAdmitted" class="form-control" placeholder="Enter Year Admitted">
                                <div wire:loading wire:target="selectedYearAdmitted" class="input-group-append">
                                    <span class="input-group-text">
                                        <div class="spinner-border spinner-border-sm" role="status"></div>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Term Filter -->
                        <div class="col-md-4 mb-2">
                            <label for="term">Term:</label>
                            <div class="input-group">
                                <select wire:model.live="selectedTerm" id="term" class="form-control select2"
                                    wire:loading.attr="disabled">
                                    <option value="">Select Term</option>
                                    @foreach ($terms as $term)
                                    <option value="{{ $term->id }}">{{ $term->name }}</option>
                                    @endforeach
                                </select>
                                <div wire:loading wire:target="selectedTerm" class="input-group-append">
                                    <span class="input-group-text">
                                        <div class="spinner-border spinner-border-sm" role="status"></div>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Second Row -->
                    <div class="row mt-2">
                        <!-- Class Filter -->
                        <div class="col-md-4 mb-2">
                            <label for="class">Class:</label>
                            <div class="input-group">
                                <select wire:model.live="selectedClass" id="class" class="form-control select2"
                                    wire:loading.attr="disabled">
                                    <option value="">Select Class</option>
                                    @foreach ($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                                <div wire:loading wire:target="selectedClass" class="input-group-append">
                                    <span class="input-group-text">
                                        <div class="spinner-border spinner-border-sm mt-1" role="status"></div>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Section Filter -->
                        <div class="col-md-4 mb-2">
                            <label for="section">Section:</label>
                            <div class="input-group">
                                <select wire:model.live="selectedSection" id="section" class="form-control select2"
                                    wire:loading.attr="disabled">
                                    <option value="">Select Section</option>
                                    @foreach ($sections as $section)
                                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                                    @endforeach
                                </select>
                                <div wire:loading wire:target="selectedSection" class="input-group-append">
                                    <span class="input-group-text">
                                        <div class="spinner-border spinner-border-sm mt-1" role="status"></div>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Exam Filter -->
                        <div class="col-md-4 mb-2">
                            <label for="exam">Exam:</label>
                            <div class="input-group">
                                <select wire:model.live="selectedExam" id="exam" class="form-control select2"
                                    wire:loading.attr="disabled">
                                    <option value="">Select Exam</option>
                                    @foreach ($exams as $exam)
                                    <option value="{{ $exam->id }}">{{ $exam->name }}</option>
                                    @endforeach
                                </select>
                                <div wire:loading wire:target="selectedExam" class="input-group-append">
                                    <span class="input-group-text d-flex align-items-center">
                                        <div class="spinner-border spinner-border-sm mt-1" role="status"></div>
                                        <span class="ml-1">Just a moment...</span> <!-- Accompanying text -->
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>




                <!-- Display applied filters with "x" icon for individual reset -->
                <div class="mt-3">
                    @if($selectedExamYear)
                    <span class="badge badge-info">
                        Year: {{ $selectedExamYear }}
                        <button wire:click="resetFilter('selectedExamYear')" class="btn btn-sm btn-light">x</button>
                    </span>
                    @endif
                    @if($selectedClass)
                    <span class="badge badge-info">
                        Class: {{ optional($classes->firstWhere('id', $selectedClass))->name }}
                        <button wire:click="resetFilter('selectedClass')" class="btn btn-sm btn-light">x</button>
                    </span>
                    @endif
                    @if($selectedYearAdmitted)
                    <span class="badge badge-info">
                        Year Admitted: {{ $selectedYearAdmitted }}
                        <button wire:click="resetFilter('selectedYearAdmitted')" class="btn btn-sm btn-light">x</button>
                    </span>
                    @endif
                    @if($selectedSection)
                    <span class="badge badge-info">
                        Section: {{ optional($sections->firstWhere('id', $selectedSection))->name }}
                        <button wire:click="resetFilter('selectedSection')" class="btn btn-sm btn-light">x</button>
                    </span>
                    @endif
                    @if($selectedTerm)
                    <span class="badge badge-info">
                        Term: {{ optional($terms->firstWhere('id', $selectedTerm))->name }}
                        <button wire:click="resetFilter('selectedTerm')" class="btn btn-sm btn-light">x</button>
                    </span>
                    @endif
                    @if($selectedExam)
                    <span class="badge badge-info">
                        Exam: {{ optional($exams->firstWhere('id', $selectedExam))->name }}
                        <button wire:click="resetFilter('selectedExam')" class="btn btn-sm btn-light">x</button>
                    </span>
                    @endif
                </div>
            </div>

            <button wire:click="resetAllFilters" class="btn btn-secondary mb-3">Reset All Filters</button>






            <!-- Students Table -->

            <!-- Link to Google Fonts -->
            <link
                href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600&family=Open+Sans:wght@400;600&family=Montserrat:wght@400;500;600&display=swap"
                rel="stylesheet">

            <div>
                @if ($selectedClass && $selectedExam)
                <div class="mt-4">
                    <div class="mb-2 text-center">
                        <h3 style="font-size: 2.5rem; font-weight: 600; font-family: 'Montserrat', sans-serif;">
                            <strong>{{ $exams->find($selectedExam)->name ?? 'N/A' }}</strong> analysis for
                            <strong>{{ $classes->firstWhere('id', $selectedClass)->name ?? 'N/A' }}</strong>
                        </h3>
                        <h5
                            style="font-size: 1.5rem; font-weight: 500; color: #6c757d; font-family: 'Open Sans', sans-serif;">
                            Grading System used on this Exam is {{
                            $exams->find($selectedExam) ? optional($exams->find($selectedExam)->gradingSystem)->name
                            : 'N/A'
                            }}
                        </h5>
                    </div>

                    <!-- Total Students in Selected Class -->
                    <div class="mt-2">
                        <div class="alert alert-info" style="padding: 15px; border-radius: 5px;">
                            <h5 style="font-size: 1.25rem; text-align: center; font-family: 'Roboto', sans-serif;">
                                The total number of students who sat for the <strong>{{
                                    $exams->find($selectedExam)->name ?? 'N/A' }}</strong> exam in
                                <strong>{{ $classes->firstWhere('id', $selectedClass)->name ?? 'N/A' }}</strong> is
                                <strong>{{ count($marks) }}</strong>.
                            </h5>

                            <!-- Stream Counts based on Class Selection -->
                            <h6
                                style="font-size: 1.1rem; text-align: center; margin-top: 10px; font-family: 'Roboto', sans-serif;">
                                Stream distribution:</h6>
                            <div class="row mt-2" wire:loading.remove>
                                @foreach ($sections as $section)
                                @php
                                $streamStudentCount = collect($marks)->where('stream', $section->name)->count();
                                @endphp
                                <div class="col-6 col-md-4 mb-2">
                                    <div class="alert alert-light"
                                        style="padding: 10px; text-align: center; margin-bottom: 0; border-radius: 5px;">
                                        <strong>{{ $section->name }}</strong>: <span style="color: #007bff;">{{
                                            $streamStudentCount }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <!-- Loading Spinner -->
                            <div wire:loading style="text-align: center; margin-top: 10px;">
                                <div class="spinner-border spinner-border-sm" style="color: #007bff;" role="status">
                                </div>
                            </div>
                        </div>
                    </div>

                    @php
                    $naCountThreshold = 2; // Threshold for N/A counts
                    $hasCaution = false; // Flag for caution message
                    $naCount = 0; // N/A count initialization

                    // Check for N/A values in marks
                    foreach ($marks as $mark) {
                    foreach ($subjects as $subject) {
                    $subjectMark = $mark['marks'][$subject->id] ?? 'N/A';
                    $grade = $this->getGradeData($subjectMark, $exam->gradingSystem->id, $subject->id);
                    if ($subjectMark === 'N/A' || $grade === 'N/A') {
                    $naCount++;
                    }
                    }
                    }

                    // Set caution flag if N/A values exceed threshold
                    if ($naCount >= $naCountThreshold) {
                    $hasCaution = true;
                    }
                    @endphp

                    @if ($hasCaution)
                    <!-- Caution message -->
                    <div class="alert alert-danger position-sticky"
                        style="top: 0; z-index: 999; padding: 15px; text-align: left; border-radius: 5px;">
                        <strong style="font-size: 1.25rem; font-family: 'Roboto', sans-serif;">Important:</strong> We
                        have noticed the grading system '<strong>{{
                            optional($exams->find($selectedExam)->gradingSystem)->name ?? 'N/A' }}</strong>' has no
                        ranges defined for the subjects. Please ensure the ranges are carefully and fully defined
                        for enhanced exam analysis.
                    </div>

                    <div class="position-relative" style="pointer-events: none;">
                        <!-- Table behind caution -->
                        <div class="table table-responsive">
                            <table class="table table-bordered" style="width: 100%; border-radius: 5px;">

                                <thead>
                                    <tr class="table-primary">
                                        <th rowspan="2" class="align-middle" style="font-family: 'Roboto', sans-serif;">Student Name</th>
                                        <th rowspan="2" class="align-middle" style="font-family: 'Roboto', sans-serif;">Stream</th>
                                        <th colspan="{{ count($subjects) }}" class="text-center" style="font-family: 'Roboto', sans-serif;">
                                            Subjects
                                        </th>
                                        <th rowspan="2" class="align-middle text-center" style="font-family: 'Roboto', sans-serif;">
                                            Total Marks
                                        </th>
                                        <th rowspan="2" class="align-middle text-center" style="font-family: 'Roboto', sans-serif;">
                                            Total Points
                                        </th>
                                        <th rowspan="2" class="align-middle text-center" style="font-family: 'Roboto', sans-serif;">
                                            Class Position
                                        </th>
                                        <th rowspan="2" class="align-middle text-center" style="font-family: 'Roboto', sans-serif;">
                                            Stream Position
                                        </th>
                                        <th rowspan="2" class="align-middle text-center" style="font-family: 'Roboto', sans-serif;">
                                            Mean Score
                                        </th>
                                        <th rowspan="2" class="align-middle text-center" style="font-family: 'Roboto', sans-serif;">
                                            Mean Grade
                                        </th>
                                    </tr>
                                    <tr class="table-secondary">
                                        @foreach ($subjects as $subject)
                                            <th class="text-center" style="font-family: 'Roboto', sans-serif;">{{ $subject->subject_name }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($marks as $mark)
                                    <tr>
                                        <td class="align-middle">{{ $mark['student_name'] ?? '-' }}</td>
                                        <td class="align-middle">{{ $mark['stream'] ?? '-' }}</td>
                                
                                        @foreach ($subjects as $subject)
                                            @php
                                            $subjectMark = $mark['marks'][$subject->id] ?? '-';
                                            $gradeData = $this->getGradeData($subjectMark, $exam->gradingSystem->id, $subject->id);
                                            @endphp
                                            <td class="align-middle text-center">
                                                @if ($subjectMark === '-' || $gradeData['grade'] === '-')
                                                N/A
                                                @else
                                                {{ $subjectMark }} {{ $gradeData['grade'] }}
                                                @endif
                                            </td>
                                        @endforeach
                                
                                        <td class="align-middle text-center">{{ $mark['total_marks'] ?? '-' }}</td>
                                        <td class="align-middle text-center">{{ $mark['total_points'] ?? '-' }}</td>
                                        <td class="align-middle text-center">{{ $mark['position'] ?? '-' }}</td>
                                        <td class="align-middle text-center">{{ $mark['stream_position'] ?? '-' }}</td>
                                        <td class="align-middle text-center">{{ number_format($mark['mean_score'], 2) }}</td>
                                        <td class="align-middle text-center">
                                            @if (isset($mark['total_points']))
                                            {{ $this->getMeanGrade($mark['total_points'], $exam->gradingSystem->id) }}
                                            @else
                                            N/A
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="{{ count($subjects) + 8 }}" class="text-center">No marks available for this exam.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                                
                            </table>
                        
                            <!-- Pagination links -->
                            <div>
                                {{ $paginatedStudents->links() }} <!-- Updated variable for pagination links -->
                            </div>
                            
                        </div>
                        
                    </div>
                </div>

            </div>

            @else
            <!-- Table without caution -->
            <div class="table table-responsive">
                <table class="table table-bordered" style="width: 100%; border-radius: 5px;">
                    <thead>
                        <tr class="table-primary">
                            <th rowspan="2" class="align-middle" style="font-family: 'Roboto', sans-serif;">Student Name</th>
                            <th rowspan="2" class="align-middle" style="font-family: 'Roboto', sans-serif;">Stream</th>
                            <th colspan="{{ count($subjects) }}" class="text-center" style="font-family: 'Roboto', sans-serif;">
                                Subjects
                            </th>
                            <th rowspan="2" class="align-middle text-center" style="font-family: 'Roboto', sans-serif;">
                                Total Marks
                            </th>
                            <th rowspan="2" class="align-middle text-center" style="font-family: 'Roboto', sans-serif;">
                                Total Points
                            </th>
                            <th rowspan="2" class="align-middle text-center" style="font-family: 'Roboto', sans-serif;">
                                Class Position
                            </th>
                            <th rowspan="2" class="align-middle text-center" style="font-family: 'Roboto', sans-serif;">
                                Stream Position
                            </th>
                            <th rowspan="2" class="align-middle text-center" style="font-family: 'Roboto', sans-serif;">
                                Mean Score
                            </th>
                            <th rowspan="2" class="align-middle text-center" style="font-family: 'Roboto', sans-serif;">
                                Mean Grade
                            </th>
                        </tr>
                        <tr class="table-secondary">
                            @foreach ($subjects as $subject)
                                <th class="text-center" style="font-family: 'Roboto', sans-serif;">{{ $subject->subject_name }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($marks as $mark)
                        <tr>
                            <td class="align-middle">{{ $mark['student_name'] ?? '-' }}</td>
                            <td class="align-middle">{{ $mark['stream'] ?? '-' }}</td>
                    
                            @foreach ($subjects as $subject)
                                @php
                                $subjectMark = $mark['marks'][$subject->id] ?? '-';
                                $gradeData = $this->getGradeData($subjectMark, $exam->gradingSystem->id, $subject->id);
                                @endphp
                                <td class="align-middle text-center">
                                    @if ($subjectMark === '-' || $gradeData['grade'] === '-')
                                    N/A
                                    @else
                                    {{ $subjectMark }} {{ $gradeData['grade'] }}
                                    @endif
                                </td>
                            @endforeach
                    
                            <td class="align-middle text-center">{{ $mark['total_marks'] ?? '-' }}</td>
                            <td class="align-middle text-center">{{ $mark['total_points'] ?? '-' }}</td>
                            <td class="align-middle text-center">{{ $mark['position'] ?? '-' }}</td>
                            <td class="align-middle text-center">{{ $mark['stream_position'] ?? '-' }}</td>
                            <td class="align-middle text-center">{{ number_format($mark['mean_score'], 2) }}</td>
                            <td class="align-middle text-center">
                                @if (isset($mark['total_points']))
                                {{ $this->getMeanGrade($mark['total_points'], $exam->gradingSystem->id) }}
                                @else
                                N/A
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ count($subjects) + 8 }}" class="text-center">No marks available for this exam.</td>
                        </tr>
                        @endforelse
                    </tbody>
                    
                </table>
                
                <!-- Pagination links -->
                <div>
                    {{ $paginatedStudents->links() }} <!-- This will now work correctly -->
                </div>
                
            </div>
            
            @endif

            @elseif ($selectedClass && !$selectedExam)
            <div class="alert alert-warning mt-4">
                Please select an exam to view student marks.
            </div>
            @endif

            @if (!$selectedClass)
            <div class="alert alert-warning mt-4">
                Please select a class and an exam to view streams and student marks.
            </div>
            @endif
        </div>
    </div>
</div>
</div>
</div>