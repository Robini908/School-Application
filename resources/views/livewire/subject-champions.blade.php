<div class="container mt-2">
    <div class="card">
        <div class="card-header">
            <h2 class="h5">Champion Leaderboard</h2>
        </div>
        <div class="card-body">
            <div class="form-row mb-4">
                <!-- Class Selection -->
                <div class="col-md-4">
                    <label for="classSelect" class="form-label">Select Class:</label>
                    <div class="input-group">
                        <select wire:model="classId" id="classSelect" class="form-control">
                            <option value="">Select Class</option>
                            @foreach($classes as $class)
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

                @if($classId)
                <!-- Stream Selection -->
                <div class="col-md-4">
                    <label for="streamSelect" class="form-label">Select Stream:</label>
                    <div class="input-group">
                        <select wire:model="streamId" id="streamSelect" class="form-control">
                            <option value="">Select Stream</option>
                            @foreach($classes->find($classId)->sections as $section)
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

                @if($classId)
                <!-- Exam Selection -->
                <div class="col-md-4">
                    <label for="examSelect" class="form-label">Select Exam (Mandatory):</label>
                    <div class="input-group">
                        <select wire:model="examId" id="examSelect" class="form-control">
                            <option value="">Select Exam (Mandatory)</option>
                            @foreach($exams as $exam)
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

            <!-- Get Champions Button -->
            <button wire:click="getChampions" class="btn btn-primary d-flex align-items-center">
                Get Champions
                <div wire:loading wire:target="getChampions" class="spinner-border spinner-border-sm text-light ms-2"
                    role="status"></div>
            </button>

            @if($errorMessage)
            <div class="text-danger mt-2">{{ $errorMessage }}</div> <!-- Display error message -->
            @endif

            @if($champions && $champions->isNotEmpty())
            <h3 class="h6 mt-4">Champions for {{ $examId ? $exams->find($examId)->name : '' }}
                @if($classId) in {{ $classes->find($classId)->name }} @endif
                @if($streamId) - {{ $classes->find($classId)->sections->find($streamId)->name }} @endif
            </h3>

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
                        <tr>
                            <td>{{ $champion->subject->subject_name }}</td>
                            <td>{{ $champion->student->first_name }} {{ $champion->student->last_name }}</td>
                            <td>{{ $champion->student->adm_no }}</td>
                            <td>{{ $champion->student->section->name }}</td>
                            <td>{{ $champion->marks }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- <!-- Pagination Links -->
                <div class="mt-3">
                    {{ $champions->links() }}
                   
                </div> --}}

            </div>


            @else
            <div class="mt-4 text-muted">No champions found for the selected criteria.</div>
            @endif
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Initialize DataTable only if champions are present
        if ($('#championsTable tbody tr').length > 0) {
            $('#championsTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'excel', 'pdf', 'print'
                ]
            });
        }
    });
</script>