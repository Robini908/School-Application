<div>
    <div class="card-body">
        @if ($errors->has('calculatePositions'))
            <div class="alert alert-danger">
                {{ $errors->first('calculatePositions') }}
            </div>
        @endif

        <div class="mt-1" x-data="{ activeTab: 'normal' }">
            <!-- Tab Headers -->
            <div class="card-header bg-light">
                <ul class="nav nav-tabs card-header-tabs" id="analysisTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" :class="{ 'active': activeTab === 'normal' }"
                            @click="activeTab = 'normal'" id="normal-tab" type="button" role="tab"
                            :aria-selected="activeTab === 'normal'">
                            <span class="fw-bold">Normal Analysis</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" :class="{ 'active': activeTab === 'combined' }"
                            @click="activeTab = 'combined'" id="combined-tab" type="button" role="tab"
                            :aria-selected="activeTab === 'combined'">
                            <span class="fw-bold">Combined Analysis</span>
                        </button>
                    </li>
                </ul>
            </div>

            <!-- Tab Content -->

            <div x-show="activeTab === 'normal'">
                <div class="mb-1">
                    <div class="form-row mt-4 mx-2">
                        <!-- First Row -->
                        <div class="row">
                            <!-- Year Filter -->
                            <div class="col-md-4 mb-3">
                                <label for="examYear" class="form-label">Year</label>
                                <div class="input-group">
                                    <input type="number" wire:model.live.debounce.500ms="selectedExamYear"
                                        id="examYear" class="form-control" placeholder="Enter Exam Year" disabled>
                                    <div wire:loading wire:target="selectedExamYear" class="input-group-append">
                                        <span class="input-group-text">
                                            <div class="spinner-border spinner-border-sm" role="status"></div>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Year Admitted Filter -->
                            <div class="col-md-4 mb-3">
                                <label for="yearAdmitted" class="form-label">Year Admitted</label>
                                <div class="input-group">
                                    <input type="number" wire:model.live.debounce.500ms="selectedYearAdmitted"
                                        id="yearAdmitted" class="form-control" placeholder="Enter Year Admitted"
                                        disabled>
                                    <div wire:loading wire:target="selectedYearAdmitted" class="input-group-append">
                                        <span class="input-group-text">
                                            <div class="spinner-border spinner-border-sm" role="status"></div>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Term Filter -->
                            <div class="col-md-4 mb-3">
                                <label for="term" class="form-label">Term</label>
                                <div class="input-group">
                                    <select wire:model.live="selectedTerm" id="term" class="form-control select2"
                                        wire:loading.attr="disabled" disabled>
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
                            <div class="col-md-4 mb-3">
                                <label for="class" class="form-label">Class</label>
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
                            <div class="col-md-4 mb-3">
                                <label for="section" class="form-label">Section</label>
                                <div class="input-group">
                                    <select wire:model.live="selectedSection" id="section"
                                        class="form-control select2" wire:loading.attr="disabled" disabled>
                                        <option value="">Select Section</option>
                                        @foreach ($sections as $section)
                                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                                        @endforeach
                                    </select>
                                    <div wire:loading wire:target="selectedSection" class="input-group-append">
                                        <span class="input-group-text">
                                            <div class="spinner-border spinner-border-sm mt-1" role="status">
                                            </div>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Exam Filter -->
                            <div class="col-md-4 mb-3">
                                <label for="exam" class="form-label">Exam</label>
                                <div class="input-group">
                                    <select wire:model.live="selectedExam" id="exam"
                                        class="form-control select2" wire:loading.attr="disabled">
                                        <option value="">Select Exam</option>
                                        @foreach ($exams as $exam)
                                            <option value="{{ $exam->id }}">{{ $exam->name }}</option>
                                        @endforeach
                                    </select>
                                    <div wire:loading wire:target="selectedExam" class="input-group-append">
                                        <span class="input-group-text d-flex align-items-center">
                                            <div class="spinner-border spinner-border-sm mt-1" role="status">
                                            </div>
                                            <span class="ml-1">Just a moment...</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Display applied filters with "x" icon for individual reset -->
                    <div class="row mt-2">
                        <!-- Applied Filters -->
                        <div class="col">
                            @if ($selectedExamYear)
                                <span class="badge badge-info">
                                    Year: {{ $selectedExamYear }}
                                    <button wire:click="resetFilter('selectedExamYear')"
                                        class="btn btn-sm btn-light ml-1">x</button>
                                </span>
                            @endif
                            @if ($selectedClass)
                                <span class="badge badge-info">
                                    Class: {{ optional($classes->firstWhere('id', $selectedClass))->name }}
                                    <button wire:click="resetFilter('selectedClass')"
                                        class="btn btn-sm btn-light ml-1">x</button>
                                </span>
                            @endif
                            @if ($selectedYearAdmitted)
                                <span class="badge badge-info">
                                    Year Admitted: {{ $selectedYearAdmitted }}
                                    <button wire:click="resetFilter('selectedYearAdmitted')"
                                        class="btn btn-sm btn-light ml-1">x</button>
                                </span>
                            @endif
                            @if ($selectedSection)
                                <span class="badge badge-info">
                                    Section: {{ optional($sections->firstWhere('id', $selectedSection))->name }}
                                    <button wire:click="resetFilter('selectedSection')"
                                        class="btn btn-sm btn-light ml-1">x</button>
                                </span>
                            @endif
                            @if ($selectedExam)
                                <span class="badge badge-info">
                                    Exam: {{ optional($exams->firstWhere('id', $selectedExam))->name }}
                                    <button wire:click="resetFilter('selectedExam')"
                                        class="btn btn-sm btn-light ml-1">x</button>
                                </span>
                            @endif
                        </div>
                        <!-- Reset All Filters Button -->
                        <div class="col-auto">
                            @if ($selectedClass)
                                <button wire:click="resetAllFilters" class="btn btn-secondary btn-sm ml-2"
                                    wire:loading.attr="disabled" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="Reset Selections">
                                    <i class="fas fa-sync-alt"></i> <!-- Font Awesome sync icon -->
                                </button>
                            @endif
                        </div>
                    </div>

                </div>

                <link
                    href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600&family=Open+Sans:wght@400;600&family=Montserrat:wght@400;500;600&display=swap"
                    rel="stylesheet">

                <div>
                    @if ($selectedClass && $selectedExam)
                        <div class="mt-4">
                            <div class="mb-2 text-center">
                                <h3
                                    style="font-size: 2.5rem; font-weight: 600; font-family: 'Montserrat', sans-serif;">
                                    <strong>{{ $exams->find($selectedExam)->name ?? 'N/A' }}</strong> analysis for
                                    <strong>{{ $classes->firstWhere('id', $selectedClass)->name ?? 'N/A' }}</strong>
                                </h3>
                                <h5
                                    style="font-size: 1.5rem; font-weight: 500; color: #6c757d; font-family: 'Open Sans', sans-serif;">
                                    Grading System used on this Exam is
                                    {{ $exams->find($selectedExam) ? optional($exams->find($selectedExam)->gradingSystem)->name : 'N/A' }}
                                </h5>
                            </div>

                            <!-- Total Students in Selected Class -->
                            <div class="mt-2">
                                <div class="alert alert-info" style="padding: 15px; border-radius: 5px;">
                                    <h5
                                        style="font-size: 1.25rem; text-align: center; font-family: 'Roboto', sans-serif;">
                                        The total number of students who sat for the
                                        <strong>{{ $exams->find($selectedExam)->name ?? 'N/A' }}</strong> exam in
                                        <strong>{{ $classes->firstWhere('id', $selectedClass)->name ?? 'N/A' }}</strong>
                                        is
                                        <strong>{{ count($marks) }}</strong>.
                                    </h5>

                                    <!-- Stream Counts based on Class Selection -->
                                    <h6
                                        style="font-size: 1.1rem; text-align: center; margin-top: 10px; font-family: 'Roboto', sans-serif;">
                                        Stream distribution:</h6>
                                    <div class="row mt-2" wire:loading.remove>
                                        @foreach ($sections as $section)
                                            @php
                                                $streamStudentCount = collect($marks)
                                                    ->where('stream', $section->name)
                                                    ->count();
                                            @endphp
                                            <div class="col-6 col-md-4 mb-2">
                                                <div class="alert alert-light"
                                                    style="padding: 10px; text-align: center; margin-bottom: 0; border-radius: 5px;">
                                                    <strong>{{ $section->name }}</strong>: <span
                                                        style="color: #007bff;">{{ $streamStudentCount }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Loading Spinner -->
                                    <div wire:loading style="text-align: center; margin-top: 10px;">
                                        <div class="spinner-border spinner-border-sm" style="color: #007bff;"
                                            role="status">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered" style="width: 100%; border-radius: 5px;">
                                    <thead>
                                        <tr class="table-primary">
                                            <th rowspan="2" class="align-middle">Student Name</th>
                                            <th rowspan="2" class="align-middle">Stream</th>
                                            <th colspan="{{ count($subjects) }}" class="text-center">Subjects
                                            </th>
                                            <th rowspan="2" class="align-middle text-center">Total Marks</th>
                                            <th rowspan="2" class="align-middle text-center">Total Points</th>
                                            <th rowspan="2" class="align-middle text-center">Class Position
                                            </th>
                                            <th rowspan="2" class="align-middle text-center">Stream Position
                                            </th>
                                            <th rowspan="2" class="align-middle text-center">Mean Score</th>
                                            <th rowspan="2" class="align-middle text-center">Mean Grade</th>
                                        </tr>
                                        <tr class="table-secondary">
                                            @foreach ($subjects as $subject)
                                                <th class="text-center">{{ $subject->subject_name }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($marks as $mark)
                                            @php
                                                $rowClass = '';
                                                if ($mark['has_special_grade']) {
                                                    $rowClass = 'table-warning'; // Yellow for special grades
                                                }
                                            @endphp
                                            <tr class="{{ $rowClass }}">
                                                <td class="align-middle">{{ $mark['student_name'] ?? '-' }}</td>
                                                <td class="align-middle">{{ $mark['stream'] ?? '-' }}</td>
                                                @foreach ($subjects as $subject)
                                                    @php
                                                        $subjectMark = $mark['marks'][$subject->id] ?? '--';
                                                        $grade = $mark['grades'][$subject->id] ?? '--';
                                                    @endphp
                                                    <td class="align-middle text-center">
                                                        @if ($subjectMark === '--' && $grade === '--')
                                                            --
                                                        @else
                                                            {{ $subjectMark !== '--' ? $subjectMark : '' }}
                                                            {{ $grade }}
                                                        @endif
                                                    </td>
                                                @endforeach
                                                <td class="align-middle text-center">
                                                    {{ $mark['total_marks'] ?? '-' }}
                                                </td>
                                                <td class="align-middle text-center">
                                                    {{ $mark['total_points'] ?? '-' }}
                                                </td>
                                                <td class="align-middle text-center">
                                                    {{ $mark['position'] ?? '-' }}
                                                </td>
                                                <td class="align-middle text-center">
                                                    {{ $mark['stream_position'] ?? '-' }}
                                                </td>
                                                <td class="align-middle text-center">
                                                    {{ number_format($mark['mean_score'], 2) }}</td>
                                                <td class="align-middle text-center">
                                                    @if ($mark['has_special_grade'])
                                                        {{ $mark['mean_grade'] }}
                                                        <!-- Display the dominant special grade -->
                                                    @else
                                                        @if (isset($mark['total_points']))
                                                            {{ \App\Helpers\StudentHelper::getMeanGrade($mark['total_points'], $exam->gradingSystem->id) }}
                                                        @else
                                                            N/A
                                                        @endif
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="{{ count($subjects) + 8 }}" class="text-center">No
                                                    marks
                                                    available for this exam.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>

            </div>

            <!-- Combined Analysis Tab -->
            <div x-show="activeTab === 'combined'">
                <div class="mb-2">
                    <!-- Search, Term, and Year Filters in the Same Row -->
                    <div class="container-fluid p-4">
                        <div class="row mb-3 align-items-end">
                            <!-- Class Selection -->
                            <div class="col-md-3 mb-3">
                                <label for="class" class="form-label">Class</label>
                                <select wire:model.live="selectedClass" id="class" class="form-control">
                                    <option value="">Select Class</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Conditional Elements -->
                            @if ($selectedClass)
                                <!-- Search Exams -->
                                <div class="col-md-3 mb-3">
                                    <label for="search" class="form-label">Search Exams</label>
                                    <input type="text" wire:model.live="search" id="search"
                                        class="form-control" placeholder="Search by exam name...">
                                </div>

                                <!-- Term Selection -->
                                <div class="col-md-3 mb-3">
                                    <label for="term" class="form-label">Term</label>
                                    <select wire:model.live="selectedTerm" id="term" class="form-control">
                                        <option value="">All Terms</option>
                                        <option value="1">Term 1</option>
                                        <option value="2">Term 2</option>
                                        <option value="3">Term 3</option>
                                    </select>
                                </div>

                                <!-- Year Selection -->
                                <div class="col-md-2 mb-3">
                                    <label for="year" class="form-label">Year</label>
                                    <select wire:model.live="selectedYear" id="year" class="form-control"
                                        style="width: 100%;">
                                        <option value="">All Years</option>
                                        @foreach (range(date('Y') - 5, date('Y')) as $year)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Reset Button -->
                                <div class="col-md-1 mb-3">
                                    @if ($selectedClass || !empty($selectedExams))
                                        <button wire:click="resetFields" class="btn btn-secondary ml-2"
                                            wire:loading.attr="disabled" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="Reset Selections">
                                            <i class="fas fa-sync-alt"></i> <!-- Font Awesome sync icon -->
                                        </button>
                                    @endif
                                </div>

                            @endif
                        </div>

                        <!-- Exams Table -->


                        <!-- Analyze and Save Button -->
                        @if ($selectedClass)
                            <!-- Progress Bar and Remaining Percentage -->
                            <div class="mb-3 mt-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="w-100 me-2">
                                        @php
                                            $totalPercentage = array_sum(array_map('intval', $examPercentages));
                                            $progressColor = 'bg-success'; // Default color
                                            $message = 'Total: 100%'; // Default message

                                            if ($totalPercentage < 100) {
                                                $progressColor = 'bg-warning'; // Warning color for below 100%
                                                $message = 'Remaining: ' . (100 - $totalPercentage) . '%';
                                            } elseif ($totalPercentage > 100) {
                                                $progressColor = 'bg-danger'; // Error color for exceeding 100%
                                                $message = 'Percentage exceeded!';
                                                $totalPercentage = 100; // Cap the progress bar at 100%
                                            }
                                        @endphp

                                        <!-- Progress Bar -->
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar {{ $progressColor }}" role="progressbar"
                                                style="width: {{ $totalPercentage }}%;"
                                                aria-valuenow="{{ $totalPercentage }}" aria-valuemin="0"
                                                aria-valuemax="100">
                                                {{ $totalPercentage }}%
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-nowrap">
                                        {{ $message }}
                                    </div>
                                </div>
                            </div>

                            <!-- Exams Table -->
                            <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-primary sticky-top">
                                        <tr>
                                            <th class="text-center">Select</th>
                                            <th>Percentage Contribution</th>
                                            <th>Exam Name</th>
                                            <th>Year</th>
                                            <th>Term</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($this->exams as $exam)
                                            <tr wire:key="exam-row-{{ $exam->id }}">
                                                <!-- Select Checkbox -->
                                                <td class="text-center">
                                                    <div class="form-check">
                                                        <input type="checkbox" wire:model.live="selectedExams"
                                                            value="{{ $exam->id }}"
                                                            id="exam_{{ $exam->id }}"
                                                            class="form-check-input @error('selectedExams') is-invalid @enderror">
                                                        @error('selectedExams')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </td>

                                                <!-- Percentage Input -->
                                                <td>
                                                    <div class="input-group">
                                                        <input type="number"
                                                            wire:model.live="examPercentages.{{ $exam->id }}"
                                                            class="form-control @error('examPercentages.' . $exam->id) is-invalid @enderror"
                                                            min="0" max="100"
                                                            oninput="this.value = Math.abs(this.value) > 100 ? 100 : Math.abs(this.value)"
                                                            {{ !in_array($exam->id, $this->selectedExams ?? []) ? 'disabled' : '' }}
                                                            aria-label="Percentage contribution for {{ $exam->name }}">
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                    @error('examPercentages.' . $exam->id)
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </td>

                                                <!-- Exam Details -->
                                                <td>{{ $exam->name }}</td>
                                                <td>{{ $exam->year }}</td>
                                                <td>Term {{ $exam->term }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">No exams found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                                <!-- Display General Errors -->
                                @if ($errors->any())
                                    <div class="alert alert-danger mt-3">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                            <!-- Prompt to select exams -->
                            @if (empty($selectedExams))
                                <div class="alert alert-info mt-3">
                                    Please select one or more exams to assign percentages.
                                </div>
                            @endif

                            @if (!empty($selectedExams))
                                @if ($errors->any())
                                    <div class="alert alert-danger mt-3">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <!-- Display Selected Exams -->
                                <div class="mb-3">
                                    <h6 class="mb-2">Selected Exams:</h6>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach ($selectedExams as $index => $examId)
                                            @php
                                                $exam = $this->exams->firstWhere('id', $examId);
                                            @endphp
                                            @if ($exam)
                                                <div class="badge bg-primary d-flex align-items-center gap-2">
                                                    <span>{{ $exam->name }} ({{ $exam->year }} - Term
                                                        {{ $exam->term }})</span>
                                                    <button wire:click="removeSelectedExam({{ $examId }})"
                                                        class="btn btn-sm btn-link p-0" style="color: white;">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Analyze and Save Button -->
                                @if (count($selectedExams) > 1)
                                    <div class="mt-4">
                                        <!-- Button for Analyze and Save -->
                                        <button wire:click="analyzeCombinedResults" class="btn btn-success bt-sm"
                                            wire:loading.attr="disabled">
                                            <span wire:loading.remove wire:target="analyzeCombinedResults">Analyze and
                                                Save</span>
                                            <span wire:loading wire:target="analyzeCombinedResults">
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                    aria-hidden="true"></span>
                                                Analyzing Combined Results...
                                            </span>
                                        </button>

                                        <!-- Button for Quick Analysis -->
                                        <button wire:click="quickAnalyzeCombinedResults"
                                            class="btn btn-success btn-sm ml-2" wire:loading.attr="disabled">
                                            <span wire:loading.remove wire:target="quickAnalyzeCombinedResults">
                                                Quick Analyze
                                            </span>
                                            <span wire:loading wire:target="quickAnalyzeCombinedResults">
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                    aria-hidden="true"></span>
                                                Analyzing Combined Results...
                                            </span>
                                        </button>
                                    </div>
                                @else
                                    <div class="alert alert-warning">
                                        Please select at least two exams to analyze.
                                    </div>
                                @endif
                            @endif
                        @else
                            <p class="font-weight-bold text-info">
                                Please select a class to view exams.
                            </p>
                        @endif
                        <!-- Analyze Button -->

                        <!-- Display Combined Results Table -->
                        @if (!empty($combinedResults) && $showTable && count($selectedExams) > 1)
                            <h4 class="mt-4 mb-3" style="font-size: 24px; color: #2c3e50; font-weight: bold;">
                                Combined Exam Analysis
                            </h4>
                            <p class="lead mb-4" style="font-size: 18px; color: #34495e;">
                                Exam was combined to create <strong
                                    class="font-weight-bold text-success">{{ $customExamName }}</strong>
                            </p>
                            <div class="mb-4 p-3 bg-light rounded" style="border: 1px solid #ddd;">
                                <div class="d-flex justify-content-between align-items-center border-bottom pb-2"
                                    style="font-size: 16px; color: #34495e;">
                                    <span>
                                        <strong style="color: #2c3e50;">Created from:</strong>
                                        <span class="font-weight-bold"
                                            style="color: #16a085;">{{ implode(', ', $selectedExamNames) }}</span>
                                    </span>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered" style="width: 100%; border-radius: 5px;">
                                    <thead>
                                        <!-- Main Header -->
                                        <tr class="table-primary">
                                            <th rowspan="2" class="align-middle">Student Name</th>
                                            <th rowspan="2" class="align-middle">Stream</th>
                                            <th colspan="{{ count($subjects) }}" class="text-center">Subjects</th>
                                            <th rowspan="2" class="align-middle text-center">Total Marks</th>
                                            <th rowspan="2" class="align-middle text-center">Total Points</th>
                                            <th rowspan="2" class="align-middle text-center">Class Position</th>
                                            <th rowspan="2" class="align-middle text-center">Stream Position</th>
                                            <th rowspan="2" class="align-middle text-center">Mean Score</th>
                                            <th rowspan="2" class="align-middle text-center">Mean Grade</th>
                                        </tr>
                                        <!-- Subject Names -->
                                        <tr class="table-secondary">
                                            @foreach ($subjects as $subject)
                                                <th class="text-center">{{ $subject->subject_name }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($combinedResults as $result)
                                            @php
                                                $rowClass = '';
                                                if ($result['has_special_grade']) {
                                                    $rowClass = 'table-warning'; // Yellow for special grades
                                                }
                                            @endphp
                                            <tr class="{{ $rowClass }}">
                                                <td class="align-middle">{{ $result['student_name'] ?? '-' }}</td>
                                                <td class="align-middle">{{ $result['stream'] ?? '-' }}</td>
                                                @foreach ($subjects as $subject)
                                                    @php
                                                        $subjectMark = $result['marks'][$subject->id] ?? '--';
                                                        $grade = $result['grades'][$subject->id] ?? '--';
                                                    @endphp
                                                    <td class="align-middle text-center">
                                                        @if ($subjectMark === '--' && $grade === '--')
                                                            --
                                                        @else
                                                            {{ $subjectMark !== '--' ? $subjectMark : '' }}
                                                            {{ $grade }}
                                                        @endif
                                                    </td>
                                                @endforeach
                                                <td class="align-middle text-center">{{ $result['total_marks'] }}</td>
                                                <td class="align-middle text-center">{{ $result['total_points'] }}
                                                </td>
                                                <td class="align-middle text-center">{{ $result['position'] ?? '-' }}
                                                </td>
                                                <td class="align-middle text-center">
                                                    {{ $result['stream_position'] ?? '-' }}</td>
                                                <td class="align-middle text-center">{{ $result['mean_score'] }}</td>
                                                <td class="align-middle text-center">
                                                    @if ($result['has_special_grade'])
                                                        {{ $result['mean_grade'] }}
                                                    @else
                                                        {{ $result['mean_grade'] }}
                                                    @endif
                                                </td>
                                                <!-- Add the Generate Report Button -->
                                                <td class="align-middle text-center">
                                                    <button wire:click="generateReport({{ $result['student_id'] }})"
                                                        class="btn btn-sm btn-primary">
                                                        Generate Report
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                        @if ($showCombinedExamForm && count($selectedExams) > 1)
                            <div class="card mt-4 col-md-12 p-3 shadow-lg border rounded"
                                style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
                                <div class="text-success">
                                    <h5 class="card-title mb-0">Now let's ensure the combined exam is successfully
                                        saved along with exam marks for each student</h5>
                                </div>
                                <div class="card-body">
                                    <!-- User-Friendly Message -->
                                    <div class="alert alert-info mb-4" wire:ignore>
                                        <p>
                                            Please provide a name, term, and year for the new exam. This will allow you
                                            to create a new exam record based on the combined data.
                                        </p>
                                        <p class="mb-0">
                                            Below are the exams that have been combined and their respective grading
                                            systems:
                                        </p>
                                    </div>

                                    <!-- Display Combined Exam Names and Grading Systems in a Table -->
                                    <div class="mb-4">
                                        <h6>Combined Exams:</h6>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Exam Name</th>
                                                    <th>Grading System</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($selectedExamNames as $index => $examName)
                                                    <tr>
                                                        <td>{{ $examName }}</td>
                                                        <td>{{ $gradingSystemNames[$index] ?? 'Unknown' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Custom Exam Name -->
                                    <div class="mb-3">
                                        <label for="customExamName" class="form-label">Custom Exam Name</label>
                                        <input type="text" wire:model="customExamName" id="customExamName"
                                            class="form-control" placeholder="Enter custom exam name (e.g., Endterm)">
                                        @error('customExamName')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Term Selection -->
                                    <div class="mb-3">
                                        <label for="customExamTerm" class="form-label">Term</label>
                                        <select wire:model="customExamTerm" id="customExamTerm" class="form-control">
                                            <option value="1">Term 1</option>
                                            <option value="2">Term 2</option>
                                            <option value="3">Term 3</option>
                                        </select>
                                        @error('customExamTerm')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Year Selection -->
                                    <div class="mb-3">
                                        <label for="customExamYear" class="form-label">Year</label>
                                        <input type="number" wire:model="customExamYear" id="customExamYear"
                                            class="form-control" placeholder="Enter year (e.g., 2023)">
                                        @error('customExamYear')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button wire:click="saveCombinedExam" class="btn btn-primary"
                                        wire:loading.attr="disabled">
                                        <span wire:loading.remove wire:target="saveCombinedExam">Save</span>
                                        <span wire:loading wire:target="saveCombinedExam">
                                            <span class="spinner-border spinner-border-sm" role="status"
                                                aria-hidden="true"></span>
                                            Saving, Just a moment...
                                        </span>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
