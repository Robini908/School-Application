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
                    <!-- Year Filter (First) -->
                    <div class="row">
                        <!-- Year Filter -->
                        <div class="col-md-3 mb-2">
                            <label for="examYear">Year:</label>
                            <input type="number" wire:model.debounce.500ms="selectedExamYear" id="examYear"
                                class="form-control" placeholder="Enter Exam Year">
                            @if ($loading) <div class="spinner-border spinner-border-sm" role="status"></div> @endif
                        </div>

                        <!-- Year Admitted Filter -->
                        <div class="col-md-3 mb-2">
                            <label for="yearAdmitted">Year Admitted:</label>
                            <input type="number" wire:model.debounce.500ms="selectedYearAdmitted" id="yearAdmitted"
                                class="form-control" placeholder="Enter Year Admitted">
                            @if ($loading) <div class="spinner-border spinner-border-sm" role="status"></div> @endif
                        </div>

                        <!-- Class Filter -->
                        <div class="col-md-3 mb-2">
                            <label for="class">Class:</label>
                            <select wire:model="selectedClass" id="class" class="form-control select2"
                                wire:loading.attr="disabled">
                                <option value="">Select Class</option>
                                @foreach ($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                            <!-- Spinner shown during loading -->
                            <div wire:loading wire:target="selectedClass" class="spinner-border spinner-border-sm mt-1"
                                role="status" aria-hidden="true"></div>
                        </div>

                        <!-- Section Filter -->
                        <div class="col-md-3 mb-2">
                            <label for="section">Section:</label>
                            <select wire:model="selectedSection" id="section" class="form-control select2"
                                wire:loading.attr="disabled">
                                <option value="">Select Section</option>
                                @foreach ($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                                @endforeach
                            </select>
                            @if ($loading) <div class="spinner-border spinner-border-sm" role="status"></div> @endif
                        </div>

                        <!-- Term Filter -->
                        <div class="col-md-3 mb-2">
                            <label for="term">Term:</label>
                            <select wire:model="selectedTerm" id="term" class="form-control select2"
                                wire:loading.attr="disabled">
                                <option value="">Select Term</option>
                                @foreach ($terms as $term)
                                <option value="{{ $term->id }}">{{ $term->name }}</option>
                                @endforeach
                            </select>
                            @if ($loading) <div class="spinner-border spinner-border-sm" role="status"></div> @endif
                        </div>

                        <!-- Exam Filter -->
                        <div class="col-md-3 mb-2">
                            <label for="exam">Exam:</label>
                            <select wire:model="selectedExam" id="exam" class="form-control select2"
                                wire:loading.attr="disabled">
                                <option value="">Select Exam</option>
                                @foreach ($exams as $exam)
                                <option value="{{ $exam->id }}">{{ $exam->name }}</option>
                                @endforeach
                            </select>
                            <!-- Spinner shown during loading -->
                            <div wire:loading wire:target="selectedExam" class="spinner-border spinner-border-sm mt-1"
                                role="status" aria-hidden="true"></div>
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
                            <button wire:click="resetFilter('selectedYearAdmitted')"
                                class="btn btn-sm btn-light">x</button>
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
                <div>
                    @if ($selectedClass && $selectedExam)
                    <div class="mt-4">
                        <h3 class="text-lg font-semibold">
                            Students and Marks for Exam: {{ $exams->find($selectedExam)->name ?? 'N/A' }}
                        </h3>

                        <h4 class="text-md font-medium">
                            Class: {{ $classes->firstWhere('id', $selectedClass)->name ?? 'N/A' }}
                        </h4>

                        <!-- Total Students in Selected Class -->
                        <div class="mt-2">
                            <div class="alert alert-info">
                                <!-- Class Name and Total Entries -->
                                <strong>{{ $classes->firstWhere('id', $selectedClass)->name ?? 'N/A' }}</strong> Streams
                                <span>- Total Entries: {{ count($marks) }}</span>

                                <!-- Stream Counts based on Class Selection -->
                                <div class="row mt-2" wire:loading.remove>
                                    @foreach ($sections as $section)
                                    @php
                                    $streamStudentCount = collect($marks)->where('stream', $section->name)->count();
                                    @endphp
                                    <div class="col-6 col-md-4 mb-2">
                                        <div class="alert alert-light text-center mb-0 p-1">
                                            <strong>{{ $section->name }}</strong>: <span class="text-primary">{{
                                                $streamStudentCount }}</span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                <!-- Loading Spinner -->
                                <div wire:loading class="text-center mt-2">
                                    <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Table for Marks -->

                        <body>
                            <div class="table-responsive">
                                <table id="marksTable" class="table table-bordered">
                                    <thead>
                                        <tr class="table-primary">
                                            <th rowspan="2" class="align-middle">Student Name</th>
                                            <th rowspan="2" class="align-middle">Stream</th>
                                            <th colspan="{{ count($subjects) }}" class="text-center">Subjects</th>
                                            <th rowspan="2" class="align-middle text-center">Total Marks</th>
                                            <th rowspan="2" class="align-middle text-center">Total Points</th>
                                            <th rowspan="2" class="align-middle text-center">Class Position</th>
                                            <th rowspan="2" class="align-middle text-center">Stream Position</th>
                                            <th rowspan="2" class="align-middle text-center">Mean Score</th>
                                        </tr>
                                        <tr class="table-secondary">
                                            @foreach ($subjects as $subject)
                                            <th class="text-center">{{ $subject->subject_name }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($marks as $mark)
                                        <tr key="{{ $mark['student_id'] }}">
                                            <td class="align-middle">{{ $mark['student_name'] ?? 'N/A' }}</td>
                                            <td class="align-middle">{{ $mark['stream'] ?? 'N/A' }}</td>
                                            @foreach ($subjects as $subject)
                                            @php
                                            // Get the mark for the current subject
                                            $subjectMark = $mark['marks'][$subject->id] ?? 'N/A';
                                            // Determine the grade for the mark
                                            $grade = $this->getGrade($subjectMark, $exam->gradingSystem->id,
                                            $subject->id);
                                            @endphp
                                            <td class="align-middle">
                                                {{ $subjectMark !== 'N/A' ? "{$subjectMark} ({$grade})" : 'N/A' }}
                                            </td>
                                            @endforeach
                                            <td class="align-middle text-center">{{ $mark['total_marks'] ?? 'N/A' }}
                                            </td>
                                            <td class="align-middle text-center">{{ $mark['total_points'] ?? 'N/A' }}
                                            </td>
                                            <td class="align-middle text-center">{{ $mark['position'] ?? 'N/A' }}</td>
                                            <td class="align-middle text-center">{{ $mark['stream_position'] ?? 'N/A' }}
                                            </td>
                                            <td class="align-middle text-center">
                                                @if (count($subjects) > 0)
                                                {{ number_format($mark['total_marks'] / count($subjects), 2) }}
                                                @else
                                                N/A
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="{{ count($subjects) + 8 }}" class="text-center">No marks
                                                available for this exam.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <script>
                                $(document).ready(function() {
                                    $('#marksTable').DataTable({
                                        dom: 'Bfrtip',
                                        buttons: [
                                            'copy', 'excel', 'pdf', 'print'
                                        ]
                                    });
                                });
                            </script>
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