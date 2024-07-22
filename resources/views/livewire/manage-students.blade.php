<div class="tab-pane fade show active" id="manage-students">
    <div class="card">
        <div class="card-body">
            <!-- Dashboard Overview -->
            <div class="container mt-4">
                <div class="row mb-4">
                    <!-- Dashboard Cards -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card bg-info text-white dashboard-card">
                            <div class="card-body">
                                <h5 class="card-title">Total Submissions</h5>
                                <p class="card-text">{{ $totalSubmissions }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="card bg-success text-white dashboard-card">
                            <div class="card-body">
                                <h5 class="card-title">Approved Submissions</h5>
                                <p class="card-text">{{ $approvedSubmissions }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="card bg-warning text-white dashboard-card">
                            <div class="card-body">
                                <h5 class="card-title">Pending Submissions</h5>
                                <p class="card-text">{{ $pendingSubmissions }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="card bg-danger text-white dashboard-card">
                            <div class="card-body">
                                <h5 class="card-title">Disapproved Submissions</h5>
                                <p class="card-text">{{ $disapprovedSubmissions }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <table class="table datatable-button-html5-columns">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Gender</th>
                            <th>Class</th>
                            <th>Section</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                        <tr>
                            <td>{{ $student->user ? $student->user->name : 'N/A' }}</td>
                            <td>{{ $student->user ? $student->user->email : 'N/A' }}</td>
                            <td>{{ $student->user ? $student->user->gender : 'N/A' }}</td>
                            <td>{{ $student->my_class ? $student->my_class->name : 'N/A' }}</td>
                            <td>{{ $student->section ? $student->section->name : 'N/A' }}</td>
                            <td>{{ $student->status }}</td>
                            <td>
                                <button wire:click="viewStudent({{ $student->id }})" class="btn btn-sm btn-info" data-toggle="modal" data-target="#detailsModal">Details</button>
                                @if($student->status === 'pending')
                                <button wire:click="approveSubmission({{ $student->id }})" class="btn btn-sm btn-success">Approve</button>
                                <button wire:click="disapproveSubmission({{ $student->id }})" class="btn btn-sm btn-danger">Disapprove</button>
                                @endif
                                <button wire:click="editStudent({{ $student->id }})" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal">Edit</button>
                                <button wire:click="deleteStudent({{ $student->id }})" class="btn btn-sm btn-danger">Delete</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal for Student Details -->
    <div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">Student Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if($selectedStudent)
                    <p><strong>Name:</strong> {{ $selectedStudent->user->name }}</p>
                    <p><strong>Email:</strong> {{ $selectedStudent->user->email }}</p>
                    <p><strong>Gender:</strong> {{ $selectedStudent->user->gender }}</p>
                    <p><strong>Class:</strong> {{ $selectedStudent->my_class->name }}</p>
                    <p><strong>Section:</strong> {{ $selectedStudent->section->name }}</p>
                    <p><strong>Status:</strong> {{ $selectedStudent->status }}</p>
                    @else
                    <p>No student selected.</p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

       <!-- Modal for Editing Student -->
       <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Student</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if($selectedStudent)
                    <form wire:submit.prevent="updateStudent">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" wire:model.defer="selectedStudent.user.name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" wire:model.defer="selectedStudent.user.email" required>
                        </div>
                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <select class="form-control" id="gender" wire:model.defer="selectedStudent.user.gender" required>
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="class">Class</label>
                            <input type="text" class="form-control" id="class" wire:model.defer="selectedStudent.my_class.name" required>
                        </div>
                        <div class="form-group">
                            <label for="section">Section</label>
                            <input type="text" class="form-control" id="section" wire:model.defer="selectedStudent.section.name" required>
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" wire:model.defer="selectedStudent.status" required>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="disapproved">Disapproved</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                    @else
                    <p>No student selected for editing.</p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    

    @push('scripts')
    <script>
        Livewire.on('openModal', () => {
            $('#detailsModal').modal('show');
        });

        Livewire.on('openEditModal', () => {
            $('#editModal').modal('show');
        });

        Livewire.on('closeEditModal', () => {
            $('#editModal').modal('hide');
        });

        Livewire.on('statusUpdated', () => {
            alert('Status updated successfully.');
        });

        Livewire.on('showDeleteConfirmation', () => {
        $('#deleteConfirmationModal').modal('show');
    });

    Livewire.on('studentDeleted', () => {
        $('#deleteConfirmationModal').modal('hide');
        alert('Student deleted successfully.');
    });
    </script>
    @endpush
</div>

