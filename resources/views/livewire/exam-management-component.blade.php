<div class="container my-5">
    <h2 class="text-center mb-4">Exam Management</h2>

    <!-- Form for Adding or Editing an Exam -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form wire:submit.prevent="{{ $examId ? 'updateExam' : 'addExam' }}">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Exam Name:</label>
                        <input type="text" wire:model="name" id="name" class="form-control" placeholder="Enter Exam Name" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="term" class="form-label">Term:</label>
                        <input type="text" wire:model="term" id="term" class="form-control" placeholder="Enter Term" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="year" class="form-label">Year:</label>
                        <input type="number" wire:model="year" id="year" class="form-control" placeholder="Enter Year" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="grading_system_id" class="form-label">Grading System:</label>
                        <select wire:model="grading_system_id" id="grading_system_id" class="form-select" required>
                            <option value="">Select Grading System</option>
                            @foreach($gradingSystems as $gradingSystem)
                                <option value="{{ $gradingSystem->id }}">{{ $gradingSystem->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">{{ $examId ? 'Update' : 'Add' }} Exam</button>
                </div>
            </form>
        </div>
    </div>

    <!-- List of Exams -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Exams List</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Name</th>
                        <th>Term</th>
                        <th>Year</th>
                        <th>Grading System</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($exams as $exam)
                        <tr>
                            <td>{{ $exam->name }}</td>
                            <td>{{ $exam->term }}</td>
                            <td>{{ $exam->year }}</td>
                            <td>{{ $exam->GradingSytem->name }}</td>
                            <td>
                                <button wire:click="editExam({{ $exam->id }})" class="btn btn-sm btn-outline-warning me-1">Edit</button>
                                <button wire:click="deleteExam({{ $exam->id }})" class="btn btn-sm btn-outline-danger">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
