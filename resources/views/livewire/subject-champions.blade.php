<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h2 class="h5">Champion Leaderboard</h2>
        </div>
        <div class="card-body">
            <div class="mb-4">
                <label for="classSelect" class="form-label">Select Class:</label>
                <select wire:model="classId" id="classSelect" class="form-control">
                    <option value="">Select Class</option>
                    @foreach($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>

            @if($classId)
            <div class="mb-4">
                <label for="streamSelect" class="form-label">Select Stream:</label>
                <select wire:model="streamId" id="streamSelect" class="form-control">
                    <option value="">Select Stream</option>
                    @foreach($classes->find($classId)->sections as $section)
                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            @if($classId)
            <div class="mb-4">
                <label for="examSelect" class="form-label">Select Exam (Mandatory):</label>
                <select wire:model="examId" id="examSelect" class="form-control">
                    <option value="">Select Exam (Mandatory)</option>
                    @foreach($exams as $exam)
                    <option value="{{ $exam->id }}">{{ $exam->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            <button wire:click="getChampions" class="btn btn-primary">Get Champions</button>

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
                            <th scope="col">Admission Number</th> <!-- Added Admission Number Column -->
                            <th scope="col">Stream</th> <!-- Added Stream Column -->
                            <th scope="col">Marks</th>
                        </tr>git
                    </thead>
                    <tbody>
                        @foreach ($champions as $champion)
                        <tr>
                            <td>{{ $champion->subject->subject_name }}</td>
                            <td>{{ $champion->student->first_name }} {{ $champion->student->last_name }}</td>
                            <td>{{ $champion->student->adm_no }}</td> <!-- Display Admission Number -->
                            <td>{{ $champion->student->section->name }}</td> <!-- Display Stream Name -->
                            <td>{{ $champion->marks }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <script>
                $(document).ready(function() {
                    $('#championsTable').DataTable({
                        dom: 'Bfrtip',
                        buttons: [
                            'copy', 'excel', 'pdf', 'print'
                        ]
                    });
                });
            </script>
            @else
            <div class="mt-4 text-muted">No champions found for the selected criteria.</div>
            @endif

        </div>
    </div>
</div>
