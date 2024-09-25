<div>
    <div class="container mt-5">
        <!-- Flash Message -->
        @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
        @endif

        <!-- Add Class Button -->
        <button wire:click="toggleClassForm" class="btn btn-primary mb-3">
            {{ $editMode ? 'Cancel Edit' : 'Add Class' }}
        </button>

        <!-- Class Form (Add/Edit) -->
        @if($editMode || !$editMode)
        <div class="card">
            <div class="card-header">
                <h4>{{ $editMode ? 'Edit Class' : 'Add New Class' }}</h4>
            </div>

            <div class="card-body">
                <form wire:submit.prevent="saveClass">
                    <!-- Class Name -->
                    <div class="form-group">
                        <label for="name">Class Name</label>
                        <input type="text" id="name" wire:model="name" class="form-control"
                            placeholder="Enter class name">
                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <!-- Streams Section -->
                    <div class="mt-4">
                        <h5>Streams for {{ $name ?: 'this class' }}</h5>
                        <p class="text-muted">You can add one or more streams to this class.</p>

                        <!-- Add Stream Input Section -->
                        <div class="input-group mb-3">
                            <input type="text" wire:model="streamName" class="form-control" placeholder="Add New Stream"
                                @if($streamEditMode) disabled @endif> <!-- Disable when editing -->
                            <div class="input-group-append">
                                <button type="button" wire:click="addStream" class="btn btn-primary"
                                    @if($streamEditMode) disabled @endif>
                                    <!-- Disable button when editing -->
                                    <i class="fa fa-plus"></i> Add Stream
                                </button>
                            </div>
                        </div>
                        @error('streamName') <span class="text-danger">{{ $message }}</span> @enderror

                        <!-- Display a message if no streams are added yet -->
                        @if(empty($streams))
                        <div class="alert alert-info">
                            No streams added yet. Start by adding a stream for this class.
                        </div>
                        @endif

                        <!-- Streams List -->
                        @if($streams)
                        <ul class="list-group mt-3">
                            @foreach($streams as $index => $stream)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <span>{{ $stream['name'] }}</span>
                                    @if($streamEditMode && $selectedStream === $index)
                                    <!-- Show editing inputs below selected stream -->
                                    <div class="mt-2">
                                        <input type="text" wire:model="streamName" class="form-control"
                                            placeholder="Edit Stream Name">
                                        @error('streamName') <span class="text-danger">{{ $message }}</span> @enderror
                                        <button type="button" wire:click="updateStream" class="btn btn-success mt-2">
                                            <i class="fa fa-check"></i> Update Stream
                                        </button>
                                        <button type="button"
                                            wire:click="$set('streamEditMode', false); $set('streamName', '')"
                                            class="btn btn-secondary mt-2">
                                            <i class="fa fa-times"></i> Cancel
                                        </button>
                                    </div>
                                    @endif
                                </div>
                                <div>
                                    <button type="button" wire:click="editStream({{ $index }})"
                                        class="btn btn-sm btn-warning" data-toggle="tooltip" title="Edit Stream">
                                        <i class="fa fa-edit"></i>
                                    </button>

                                    <button type="button" wire:click="removeStream({{ $index }})"
                                        class="btn btn-sm btn-danger" data-toggle="tooltip" title="Remove Stream">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                        @endif
                    </div>

                    <!-- Save Button -->
                    <div class="mt-4">
                        <button type="submit" class="btn btn-success">
                            {{ $editMode ? 'Update Class' : 'Save Class' }}
                        </button>

                        @if($editMode)
                        <button type="button" wire:click="resetForm" class="btn btn-secondary">Cancel</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
        <div class="card mt-4">
            <div class="card-header">
                <h4 class="mb-0">Classes</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Class Name</th>
                                <th>Class Master</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($classes as $class)
                            <tr>
                                <td>{{ $class->name }}</td>
                                <td>{{ $class->master ? $class->master->name : 'Not Assigned' }}</td>
                                <td class="text-center">
                                    <div class="btn-group dropleft">
                                        <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Actions
                                        </button>
                                        <div class="dropdown-menu">
                                            <!-- Edit Button -->
                                            <button wire:click="toggleClassForm({{ $class->id }})"
                                                class="dropdown-item btn btn-warning btn-sm" data-toggle="tooltip"
                                                title="Edit Class">
                                                <i class="icon-pencil"></i> Edit
                                            </button>
                                            <!-- Assign Teacher Button -->
                                            <button wire:click="toggleAssignTeacher({{ $class->id }})"
                                                class="dropdown-item btn btn-info btn-sm" data-toggle="tooltip"
                                                title="Assign Teacher">
                                                <i class="icon-user-check"></i> Assign Teacher
                                            </button>
                                            <!-- Other Actions -->
                                            <button wire:click="viewStreams({{ $class->id }})"
                                                class="dropdown-item btn btn-info btn-sm" data-toggle="tooltip"
                                                title="View Streams">
                                                <i class="icon-eye"></i> View Streams
                                            </button>
                                            <button wire:click="viewEntries({{ $class->id }})"
                                                class="dropdown-item btn btn-info btn-sm" data-toggle="tooltip"
                                                title="View Entries">
                                                <i class="icon-list2"></i> View Entries
                                            </button>
                                            <button wire:click="deleteClass({{ $class->id }})"
                                                class="dropdown-item btn btn-danger btn-sm" data-toggle="tooltip"
                                                title="Delete Class">
                                                <i class="icon-trash"></i> Delete
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            @if($showInlineForm && $class->id === $selectedClassForAssignment)
                            <tr>
                                <td colspan="3">
                                    <form wire:submit.prevent="saveStreamTeacher">
                                        <div class="form-row">
                                            <div class="col">
                                                <select wire:model="streamTeacher" class="form-control" required>
                                                    <option value="">-- Select Teacher --</option>
                                                    @foreach($teachers as $teacher)
                                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('streamTeacher')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col">
                                                <input type="text" wire:model="session" placeholder="Session"
                                                    class="form-control" required>
                                                @error('session')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-auto">
                                                <button type="submit" class="btn btn-success">Assign</button>
                                                <button type="button" wire:click="closeInlineForm"
                                                    class="btn btn-secondary">Cancel</button>
                                            </div>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                            @endif

                            <!-- Streams Table -->
                            @if($viewStreamsMode && $selectedClass && $selectedClass->id == $class->id)
                            <tr>
                                <td colspan="3">
                                    <div class="mt-3 card">
                                        <div class="card-header">
                                            <h5 class="mb-0">Streams for {{ $selectedClass->name }}</h5>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-sm table-bordered table-hover">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Stream Name</th>
                                                        <th>Stream Teacher</th>
                                                        <th>Student Count</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($selectedClass->sections as $stream)
                                                    <tr key="{{ $stream->id }}">
                                                        <td>{{ $stream->name }}</td>
                                                        <td>
                                                            @if($editingStreamId === $stream->id)
                                                            <form
                                                                wire:submit.prevent="assignStreamTeacher({{ $stream->id }})">
                                                                <label class="form-label">Current Class Teacher:
                                                                    <strong>{{ $stream->teacher ? $stream->teacher->name
                                                                        : 'Not Assigned' }}</strong></label>
                                                                <label class="form-label">Current Session/Year:
                                                                    <strong>{{ $stream->session_year }}</strong></label>

                                                                <div class="form-group d-flex align-items-center">
                                                                    <select wire:model="selectedTeacherId"
                                                                        class="form-control me-2" style="width: auto;">
                                                                        <option value="">Select Teacher</option>
                                                                        @foreach($teachers as $teacher)
                                                                        <option value="{{ $teacher->id }}">{{
                                                                            $teacher->name }}</option>
                                                                        @endforeach
                                                                    </select>

                                                                    <select wire:model="sessionYear"
                                                                        class="form-control me-2" style="width: auto;">
                                                                        <option value="">Select Year</option>
                                                                        @for($year = date('Y'); $year >= 2000; $year--)
                                                                        <option value="{{ $year }}">{{ $year }}</option>
                                                                        @endfor
                                                                    </select>

                                                                    <button type="submit"
                                                                        class="btn btn-success me-2">Save</button>
                                                                    <button type="button" class="btn btn-secondary"
                                                                        wire:click="cancelEdit">Close</button>
                                                                </div>
                                                            </form>
                                                            @else
                                                            <span class="d-block mb-1">{{ $stream->teacher ?
                                                                $stream->teacher->name : 'Not Assigned' }}</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $stream->studentRecords->count() }}</td>
                                                        <td class="text-center">
                                                            <div class="btn-group dropleft">
                                                                <button type="button"
                                                                    class="btn btn-outline-secondary btn-sm dropdown-toggle"
                                                                    data-toggle="dropdown" aria-haspopup="true"
                                                                    aria-expanded="false">
                                                                    Actions
                                                                </button>
                                                                <div class="dropdown-menu">
                                                                    <button class="dropdown-item btn btn-info btn-sm"
                                                                        wire:click="editStreamTeacher({{ $stream->id }})"
                                                                        data-toggle="tooltip"
                                                                        title="{{ $stream->teacher ? 'Change Teacher' : 'Assign Teacher' }}">
                                                                        {{ $stream->teacher ? 'Change Class Teacher' :
                                                                        'Assign Class Teacher' }}
                                                                    </button>
                                                                    <button class="dropdown-item btn btn-info btn-sm"
                                                                        wire:click="viewStreamStudents({{ $stream->id }})"
                                                                        data-toggle="tooltip" title="View Students">
                                                                        View Students
                                                                    </button>

                                                                    <button class="dropdown-item btn btn-info btn-sm"
                                                                        wire:click="showStreamEntries({{ $stream->id }})"
                                                                        data-toggle="tooltip" title="Show Entries">
                                                                        Show Entries
                                                                    </button>

                                                                    <button class="dropdown-item btn btn-danger btn-sm"
                                                                        wire:click="deleteStream({{ $stream->id }})"
                                                                        data-toggle="tooltip" title="Delete Stream">
                                                                        Delete
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endif
                            <!-- Entries (Students Count) -->
                            @if($viewEntriesMode && $selectedClass && $selectedClass->id == $class->id)
                            <tr>
                                <td colspan="3">
                                    <div class="mt-3 card">
                                        <div class="card-header">
                                            <h5 class="mb-0">Entries for {{ $selectedClass->name }}</h5>
                                        </div>
                                        <div class="card-body">
                                            <p>Total Students: {{ $studentsCount }}</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endif
                            @empty
                            <tr>
                                <td colspan="3" class="text-center">No classes found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Links -->
                <div>
                    {{ $classes->links() }}
                </div>
            </div>
        </div>

        <!-- Include Bootstrap JS for dropdowns and tooltips -->
        <script>
            $(function () {
                $('[data-toggle="tooltip"]').tooltip();
            });
        </script>


        @endif


        <!-- Students Modal -->
        @if($showStudentsModal)
        <div class="modal fade show d-block" tabindex="-1" role="dialog" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Students in {{ $modalStreamName }}</h5>
                        <button type="button" wire:click="$set('showStudentsModal', false)" class="close"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Total Students: {{ $modalStudentsCount }}</p>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Admission Number</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Gender</th>
                                    <th>Phone</th>
                                    <th>Date of Birth</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($modalStudents as $student)
                                <tr>
                                    <td>{{ $student->adm_no }}</td>
                                    <td>{{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}
                                    </td>
                                    <td>{{ $student->email }}</td>
                                    <td>{{ ucfirst($student->gender) }}</td>
                                    <td>{{ $student->phone }}</td>
                                    <td>{{ \Carbon\Carbon::parse($student->dob)->format('d M, Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click="$set('showStudentsModal', false)"
                            class="btn btn-secondary">Close</button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>