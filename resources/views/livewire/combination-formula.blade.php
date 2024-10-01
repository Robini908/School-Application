<div>
    <div class="card mb-4">
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
                            <input type="number" wire:model.debounce.500ms="selectedExamYear" id="examYear" class="form-control"
                                   placeholder="Enter Exam Year">
                            @if ($loading) <div class="spinner-border spinner-border-sm" role="status"></div> @endif
                        </div>
                    
                        <!-- Year Admitted Filter -->
                        <div class="col-md-3 mb-2">
                            <label for="yearAdmitted">Year Admitted:</label>
                            <input type="number" wire:model.debounce.500ms="selectedYearAdmitted" id="yearAdmitted" class="form-control"
                                   placeholder="Enter Year Admitted">
                            @if ($loading) <div class="spinner-border spinner-border-sm" role="status"></div> @endif
                        </div>
                    
                        <!-- Class Filter -->
                        <div class="col-md-3 mb-2">
                            <label for="class">Class:</label>
                            <select wire:model="selectedClass" id="class" class="form-control select2" wire:loading.attr="disabled">
                                <option value="">Select Class</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                            @if ($loading) <div class="spinner-border spinner-border-sm" role="status"></div> @endif
                        </div>
                    
                        <!-- Section Filter -->
                        <div class="col-md-3 mb-2">
                            <label for="section">Section:</label>
                            <select wire:model="selectedSection" id="section" class="form-control select2" wire:loading.attr="disabled">
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
                            <select wire:model="selectedTerm" id="term" class="form-control select2" wire:loading.attr="disabled">
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
                            <select wire:model="selectedExam" id="exam" class="form-control select2" wire:loading.attr="disabled">
                                <option value="">Select Exam</option>
                                @foreach ($exams as $exam)
                                    <option value="{{ $exam->id }}">{{ $exam->name }}</option>
                                @endforeach
                            </select>
                            @if ($loading) <div class="spinner-border spinner-border-sm" role="status"></div> @endif
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
            <div>
                
            
                @if ($selectedClass && $selectedExam)
                    <div class="mt-4">
                        <h3 class="text-lg font-semibold">
                            Students and Marks for Exam: {{ $exams->find($selectedExam)->name ?? 'N/A' }}
                        </h3>
            
                        <h4 class="text-md font-medium">
                            Class: {{ $classes->firstWhere('id', $selectedClass)->name ?? 'N/A' }} 
                            @if ($selectedSection) <!-- Show stream only if section is selected -->
                                | Stream: {{ $sections->firstWhere('id', $selectedSection)->stream ?? 'N/A' }}
                            @endif
                        </h4>
            
                        <div class="table-responsive">
                            <!-- Bootstrap class for responsive tables -->
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="table-primary">
                                        <th rowspan="2" class="align-middle">Student Name</th>
                                        <th colspan="{{ count($subjects) }}" class="text-center">Subjects</th>
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
                                            @foreach ($subjects as $subject)
                                                <td class="align-middle">{{ $mark['marks'][$subject->id] ?? 'N/A' }}</td>
                                            @endforeach
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ count($subjects) + 1 }}" class="text-center">No marks available for this exam.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>


        </div>
    </div>
</div>