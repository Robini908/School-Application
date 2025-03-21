<div class="p-3 border rounded" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">

    <div class="card-body">
        <div class="form-row mb-3">
            <!-- Class Selection -->
            <div class="col-md-4">
                <label for="class" class="form-label">Select Class</label>
                <div class="input-group">
                    <select wire:model.live="classId" id="class" class="form-control">
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

            <!-- Section Selection -->
           
                
           
            @if ($classId)
            <div class="col-md-4">
                <label for="section" class="form-label">Select Stream (Optional, for stream analysis)</label>
                <div class="input-group">
                    <select wire:model.live="sectionId" id="section" class="form-control">
                        <option value="">All Sections</option>
                        @foreach ($sections as $section)
                            <option value="{{ $section->id }}">{{ $section->name }}</option>
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

            <!-- Exam Selection -->
            @if ($classId)
            <div class="col-md-4">
                <label for="exam" class="form-label">Select Exam</label>
                <div class="input-group">
                    <select wire:model.live="examId" id="exam" class="form-control">
                        <option value="">Select Exam</option>
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
        </div>

        <!-- Get Subject Analysis Button -->
        @if ($examId)
        <div class="mb-3">
            <button wire:click="getGradesCount" class="btn btn-primary d-flex align-items-center">
                Get Subject Analysis
                <div wire:loading wire:target="getGradesCount" class="spinner-border spinner-border-sm text-light ms-2"
                    role="status"></div>
            </button>
        </div>
        @endif

        @if ($errorMessage)
            <div class="alert alert-danger mt-3">{{ $errorMessage }}</div>
        @endif

        @if (!empty($gradesCount) && is_array($gradesCount) && count($gradesCount) > 0)
            <div class="table-responsive mt-4">
                <table class="table table-striped table-bordered table-hover">
                    <!-- Header row displaying the selected information -->
                    <thead>
                        <tr>
                            <th colspan="13" class="text-center">
                                Subject Analysis for
                                @if ($classId)
                                    <strong>{{ $classes->where('id', $classId)->first()->name }}</strong>
                                @endif
                                @if ($sectionId)
                                    @if ($classId)
                                        <strong> - {{ $sections->where('id', $sectionId)->first()->name }}</strong>
                                    @else
                                        <strong>{{ $sections->where('id', $sectionId)->first()->name }}</strong>
                                    @endif
                                @endif
                                <br>
                                <strong>Name of Exam:</strong> {{ $exams->where('id', $examId)->first()->name }}
                            </th>
                        </tr>
                        <!-- Fixed header for grades -->
                        <tr>
                            <th>Subject</th>
                            <th>A</th>
                            <th>A-</th>
                            <th>B+</th>
                            <th>B</th>
                            <th>B-</th>
                            <th>C+</th>
                            <th>C</th>
                            <th>C-</th>
                            <th>D+</th>
                            <th>D</th>
                            <th>D-</th>
                            <th>E</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Loop through each subject's grades -->
                        @foreach ($gradesCount as $grade)
                            <tr>
                                <td class="text-start">{{ $grade['subject_name'] }}</td>
                                <td class="text-center">{{ $grade['grades']['A'] ?? 0 }}</td>
                                <td class="text-center">{{ $grade['grades']['A-'] ?? 0 }}</td>
                                <td class="text-center">{{ $grade['grades']['B+'] ?? 0 }}</td>
                                <td class="text-center">{{ $grade['grades']['B'] ?? 0 }}</td>
                                <td class="text-center">{{ $grade['grades']['B-'] ?? 0 }}</td>
                                <td class="text-center">{{ $grade['grades']['C+'] ?? 0 }}</td>
                                <td class="text-center">{{ $grade['grades']['C'] ?? 0 }}</td>
                                <td class="text-center">{{ $grade['grades']['C-'] ?? 0 }}</td>
                                <td class="text-center">{{ $grade['grades']['D+'] ?? 0 }}</td>
                                <td class="text-center">{{ $grade['grades']['D'] ?? 0 }}</td>
                                <td class="text-center">{{ $grade['grades']['D-'] ?? 0 }}</td>
                                <td class="text-center">{{ $grade['grades']['E'] ?? 0 }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <!-- Display message when no data is available -->
            <div class="mt-4 text-center">
                <p>No data available for analysis.</p>
            </div>
        @endif
    </div>
</div>
