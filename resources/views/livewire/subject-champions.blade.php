<div class="card  p-3 shadow-lg border rounded" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">

    <div class="card-header">
        <h2 class="h5">Champion Leaderboard</h2>
    </div>
    <div class="card-body">
        <div class="form-row mb-4">
            <!-- Class Selection -->
            <div class="col-md-4">
                <label for="classSelect" class="form-label">Select Class:</label>
                <div class="input-group">
                    <select wire:model.live="classId" id="classSelect" class="form-control">
                        <option value="">Select Class</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                    <div wire:loading wire:target="classId" class="input-group-append">
                        <span class="input-group-text">
                            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                        </span>
                    </div>
                </div>
            </div>

            @if ($classId)
                <!-- Stream Selection -->
                <div class="col-md-4">
                    <label for="streamSelect" class="form-label">Select Stream:</label>
                    <div class="input-group">
                        <select wire:model.live="streamId" id="streamSelect" class="form-control">
                            <option value="">Select Stream</option>
                            @foreach ($classes->find($classId)->sections as $section)
                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                            @endforeach
                        </select>
                        <div wire:loading wire:target="streamId" class="input-group-append">
                            <span class="input-group-text">
                                <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                            </span>
                        </div>
                    </div>
                </div>
            @endif

            @if ($classId)
                <!-- Exam Selection -->
                <div class="col-md-4">
                    <label for="examSelect" class="form-label">Select Exam (Mandatory):</label>
                    <div class="input-group">
                        <select wire:model.live="examId" id="examSelect" class="form-control">
                            <option value="">Select Exam (Mandatory)</option>
                            @foreach ($exams as $exam)
                                <option value="{{ $exam->id }}">{{ $exam->name }}</option>
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

            <!-- Term and Year Filtering -->
            @if ($examId)

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="term">Term</label>
                        <select wire:model.live="term" id="term" class="form-control"
                            {{ !$classId ? 'disabled' : '' }}>
                            <option value="">Select Term</option>
                            @foreach ($terms as $term)
                                <option value="{{ $term }}">{{ $term }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="year">Year</label>
                        <select wire:model.live="year" id="year" class="form-control"
                            {{ !$classId ? 'disabled' : '' }}>
                            <option value="">Select Year</option>
                            @foreach ($years as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

        </div>

        <!-- Get Champions Button -->
        @if ($examId)
            <button wire:click="getChampions" class="btn btn-primary d-flex align-items-center">
                Get Champions
                <div wire:loading wire:target="getChampions" class="spinner-border spinner-border-sm text-light ms-2"
                    role="status"></div>
            </button>
        @endif

        @if ($errorMessage && $classId && $examId)
            <div class="text-danger mt-2">{{ $errorMessage }}</div> <!-- Display error message -->
        @endif

        @if ($champions && $champions->isNotEmpty())
            <h3 class="h6 mt-4">Champions for {{ $examId ? $exams->find($examId)->name : '' }}
                @if ($classId)
                    in {{ $classes->find($classId)->name }}
                @endif
                @if ($streamId)
                    - {{ $classes->find($classId)->sections->find($streamId)->name }}
                @endif
            </h3>
            <div class="d-flex justify-content-end mb-4">
                <!-- Export to PDF Button -->
                <button wire:click="exportPDF" wire:loading.attr="disabled" wire:loading.class="btn-secondary"
                    wire:target="exportPDF" class="btn btn-danger btn-sm mx-1">
                    <i class="fas fa-file-pdf"></i>
                    <span wire:loading.remove wire:target="exportPDF">PDF</span>
                    <span wire:loading wire:target="exportPDF">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    </span>
                </button>

                <!-- Export to Excel Button -->
                <button wire:click="exportExcel" wire:loading.attr="disabled" wire:loading.class="btn-secondary"
                    wire:target="exportExcel" class="btn btn-success btn-sm mx-1">
                    <i class="fas fa-file-excel"></i>
                    <span wire:loading.remove wire:target="exportExcel">Excel</span>
                    <span wire:loading wire:target="exportExcel">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    </span>
                </button>
            </div>


            <div class="table-responsive">


                <table id="championsTable" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Subject</th>
                            <th scope="col">Student</th>
                            <th scope="col">Admission Number</th>
                            <th scope="col">Stream</th>
                            <th scope="col">Marks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($champions as $champion)
                            @if ($champion && $champion->subject && $champion->student && $champion->student->section)
                                <tr>
                                    <td>{{ $champion->subject->subject_name }}</td>
                                    <td>{{ $champion->student->first_name }} {{ $champion->student->last_name }}</td>
                                    <td>{{ $champion->student->adm_no }}</td>
                                    <td>{{ $champion->student->section->name }}</td>
                                    <td>{{ $champion->marks }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>





            @if ($errorMessage)
                <div class="alert alert-danger">
                    {{ $errorMessage }}
                </div>
            @endif
        @else
            <div class="mt-4 text-muted">No champions found for the selected criteria.</div>
        @endif
    </div>

</div>
