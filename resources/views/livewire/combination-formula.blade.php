<div>
    <div class="card mb-4">
        <div class="card-header">
            <h5>Filter Students</h5>
        </div>
        <div class="card-body">
            <div class="mb-4">
                <div class="form-row mt-4 mx-2">
                    <!-- Year Filter (First) -->
                    <div class="col-md-3 mb-2">
                        <label for="examYear">Year:</label>
                        <input type="number" wire:model="selectedExamYear" id="examYear" class="form-control" placeholder="Enter Exam Year">
                    </div>

                    <!-- Class Filter -->
                    <div class="col-md-3 mb-2">
                        <label for="class">Class:</label>
                        <select wire:model="selectedClass" id="class" class="form-control select2">
                            <option value="">Select Class</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Year Admitted Filter -->
                    <div class="col-md-3 mb-2">
                        <label for="yearAdmitted">Year Admitted:</label>
                        <input type="number" wire:model="selectedYearAdmitted" id="yearAdmitted" class="form-control" placeholder="Enter Year Admitted">
                    </div>

                    <!-- Section Filter -->
                    <div class="col-md-3 mb-2">
                        <label for="section">Section:</label>
                        <select wire:model="selectedSection" id="section" class="form-control select2">
                            <option value="">Select Section</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Term Filter -->
                    <div class="col-md-3 mb-2">
                        <label for="term">Term:</label>
                        <select wire:model="selectedTerm" id="term" class="form-control select2">
                            <option value="">Select Term</option>
                            @foreach ($terms as $term)
                                <option value="{{ $term->id }}">{{ $term->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Exam Filter -->
                    <div class="col-md-3 mb-2">
                        <label for="exam">Exam:</label>
                        <select wire:model="selectedExam" id="exam" class="form-control select2">
                            <option value="">Select Exam</option>
                            @foreach ($exams as $exam)
                                <option value="{{ $exam->id }}">{{ $exam->name }}</option>
                            @endforeach
                        </select>
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
            <table class="table table-bordered mt-4">
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Subjects</th>
                        <th>Scores</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                    <tr>
                        <td>{{ $student->name }}</td>
                        <td>
                            @if(isset($examResults[$student->id]))
                                @foreach($examResults[$student->id] as $result)
                                    <div>{{ $result->subject->name }}</div>
                                @endforeach
                            @else
                                No results available
                            @endif
                        </td>
                        <td>
                            @if(isset($examResults[$student->id]))
                                @foreach($examResults[$student->id] as $result)
                                    <div>{{ $result->score }}</div>
                                @endforeach
                            @else
                                No scores available
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3">No students found for the selected filters.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
