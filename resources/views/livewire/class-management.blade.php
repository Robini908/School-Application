<div>

    @if ($showForm)
        <div class="card p-3 shadow-lg border rounded" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
            <div class="card-header">
                <h4>{{ $isEditing ? 'Edit Class' : 'Add New Class' }}</h4>
            </div>
            <div class="card-body">
                <form wire:submit.prevent="saveClass">
                    <!-- Loading Indicator -->
                    @if ($loading)
                        <div class="text-center mb-3">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <p>Saving class, please wait...</p>
                        </div>
                    @endif

                    <!-- Class Name -->
                    <div class="form-group">
                        <label for="name">Class Name</label>
                        <input type="text" id="name" wire:model.defer="name" class="form-control"
                            placeholder="Enter class name">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Streams Section -->
                    <div class="mt-4">
                        <h5>Streams for {{ $name ?: 'this class' }}</h5>
                        <p class="text-muted">You can add one or more streams to this class.</p>

                        <!-- Add Stream Input Section -->
                        <div class="input-group mb-3">
                            <input type="text" wire:model.defer="streamName" class="form-control"
                                placeholder="Add New Stream" @if ($streamEditMode) disabled @endif>
                            <div class="input-group-append">
                                <button type="button" wire:click="addStream" class="btn btn-primary"
                                    @if ($streamEditMode) disabled @endif>
                                    <i class="fa fa-plus"></i> Add Stream
                                </button>
                            </div>
                        </div>
                        @error('streamName')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror

                        @if (empty($streams))
                            <div class="alert alert-info">
                                No streams added yet. Start by adding a stream for this class.
                            </div>
                        @endif

                        <!-- Streams List -->
                        @if ($streams)
                            <ul class="list-group mt-3">
                                @foreach ($streams as $index => $stream)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <span>{{ $stream['name'] }}</span>
                                            @if ($streamEditMode && $selectedStream === $index)
                                                <div class="mt-2">
                                                    <input type="text" wire:model.defer="streamName"
                                                        class="form-control" placeholder="Edit Stream Name">
                                                    @error('streamName')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                    <button type="button" wire:click="updateStream"
                                                        class="btn btn-success mt-2">
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                    <button type="button"
                                                        wire:click="$set('streamEditMode', false); $set('streamName', '')"
                                                        class="btn btn-secondary mt-2">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <button type="button" wire:click="editStream({{ $index }})"
                                                class="btn btn-sm btn-warning" data-toggle="tooltip"
                                                title="Edit Stream">
                                                <i class="fa fa-edit"></i>
                                            </button>

                                            <button type="button" wire:click="removeStream({{ $index }})"
                                                class="btn btn-sm btn-danger" data-toggle="tooltip"
                                                title="Remove Stream">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <!-- Save and Cancel Buttons -->
                    <div class="mt-4">
                        <button type="submit" class="btn btn-success" wire:loading.attr="disabled">
                            {{ $isEditing ? 'Update Class' : 'Save Class' }}
                        </button>
                        <button type="button" wire:click="resetForm" class="btn btn-secondary">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
    @if (!$showForm && !$isViewingClassTeacher)
        <div class="card  p-3 shadow-lg border rounded" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
            <div class="d-flex justify-content-between align-items-center">
                <div class="card-header">
                    <h4 class="mb-0">Classes</h4>
                </div>

                {{-- <!-- Add Class Button --> --}}
                <button wire:click="toggleClassForm" class="btn btn-primary mb-3">
                    {{ $editMode ? 'Cancel Edit' : 'New' }}
                </button>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>S/N</th>
                                <th>Class Name</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($classes as $class)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $class->name }}</td>
                                    <td class="text-center">
                                        <div class="btn-group dropleft">
                                            <button type="button"
                                                class="btn btn-outline-secondary btn-sm dropdown-toggle"
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
                                                <button wire:click="viewClassMaster({{ $class->id }})"
                                                    class="dropdown-item btn btn-warning btn-sm" data-toggle="tooltip"
                                                    title="View Class Master">
                                                    <i class="icon-eye"></i> View Class Master
                                                </button>

                                                <!-- Assign Teacher Button -->
                                                <button wire:click="toggleAssignTeacher({{ $class->id }})"
                                                    class="dropdown-item btn btn-info btn-sm" data-toggle="tooltip"
                                                    title="{{ $class->master ? 'Change Class Teacher' : 'Assign Class Teacher' }}">
                                                    <i class="icon-user-check"></i>
                                                    {{ $class->master ? 'Change Class Teacher' : 'Assign Class Teacher' }}
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



                                @if ($showInlineForm && $class->id === $selectedClassForAssignment)
                                    <tr>
                                        <td colspan="3">
                                            @if ($showInlineForm)
                                                <form wire:submit.prevent="saveStreamTeacher">
                                                    <div class="row align-items-end">
                                                        <div class="col">
                                                            <label for="streamTeacher">Select Teacher</label>
                                                            <select wire:model="streamTeacher" id="streamTeacher"
                                                                class="form-control" required>
                                                                <option value="">-- Select Teacher --</option>
                                                                @foreach ($teachers as $teacher)
                                                                    <option value="{{ $teacher->id }}">
                                                                        {{ $teacher->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('streamTeacher')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>

                                                        <div class="col">
                                                            <label for="session">Session</label>
                                                            <select wire:model="session" id="session"
                                                                class="form-control" required>
                                                                <option value="">-- Select Session --</option>
                                                                @foreach (range(date('Y'), 1900) as $year)
                                                                    <option value="{{ $year }}">
                                                                        {{ $year }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('session')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>

                                                        <div class="col-auto">
                                                            <button type="submit" class="btn btn-success">
                                                                {{ $streamTeacher && $session ? 'Update Teacher' : 'Assign Teacher' }}
                                                            </button>
                                                            <button type="button" wire:click="closeInlineForm"
                                                                class="btn btn-secondary">Cancel</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endif

                                <!-- Streams Table -->
                                @if ($isDisplayingStreams && $selectedClass && $selectedClass->id == $class->id)
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
                                                            @foreach ($selectedClass->sections as $stream)
                                                                <tr key="{{ $stream->id }}">
                                                                    <td>{{ $stream->name }}</td>
                                                                    <td>
                                                                        @if ($editingStreamId === $stream->id)
                                                                            <form
                                                                                wire:submit="assignStreamTeacher({{ $stream->id }})">
                                                                                <label class="form-label">Current
                                                                                    Class
                                                                                    Teacher:
                                                                                    <strong>{{ $stream->teacher ? $stream->teacher->name : 'Not Assigned' }}</strong></label>
                                                                                <label class="form-label">Current
                                                                                    Session/Year:
                                                                                    <strong>{{ $stream->session_year }}</strong></label>

                                                                                <div
                                                                                    class="form-group d-flex align-items-center">
                                                                                    <select
                                                                                        wire:model.live="selectedTeacherId"
                                                                                        class="form-control me-2"
                                                                                        style="width: auto;">
                                                                                        <option value="">
                                                                                            Select
                                                                                            Teacher</option>
                                                                                        @foreach ($teachers as $teacher)
                                                                                            <option
                                                                                                value="{{ $teacher->id }}">
                                                                                                {{ $teacher->name }}
                                                                                            </option>
                                                                                        @endforeach
                                                                                    </select>

                                                                                    <select
                                                                                        wire:model.live="sessionYear"
                                                                                        class="form-control me-2"
                                                                                        style="width: auto;">
                                                                                        <option value="">
                                                                                            Select
                                                                                            Year</option>
                                                                                        @for ($year = date('Y'); $year >= 2000; $year--)
                                                                                            <option
                                                                                                value="{{ $year }}">
                                                                                                {{ $year }}
                                                                                            </option>
                                                                                        @endfor
                                                                                    </select>

                                                                                    <button type="submit"
                                                                                        class="btn btn-success me-2">Save</button>
                                                                                    <button type="button"
                                                                                        class="btn btn-secondary"
                                                                                        wire:click="cancelEdit">Close</button>
                                                                                </div>
                                                                            </form>
                                                                        @else
                                                                            <span
                                                                                class="d-block mb-1">{{ $stream->teacher ? $stream->teacher->name : 'Not Assigned' }}</span>
                                                                        @endif
                                                                    </td>
                                                                    <td>{{ $stream->studentRecords->count() }}</td>
                                                                    <td class="text-center">
                                                                        <div class="btn-group dropleft">
                                                                            <button type="button"
                                                                                class="btn btn-outline-secondary btn-sm dropdown-toggle"
                                                                                data-toggle="dropdown"
                                                                                aria-haspopup="true"
                                                                                aria-expanded="false">
                                                                                Actions
                                                                            </button>
                                                                            <div class="dropdown-menu">
                                                                                <button
                                                                                    class="dropdown-item btn btn-info btn-sm"
                                                                                    wire:click="editStreamTeacher({{ $stream->id }})"
                                                                                    data-toggle="tooltip"
                                                                                    title="{{ $stream->teacher ? 'Change Teacher' : 'Assign Teacher' }}">
                                                                                    {{ $stream->teacher ? 'Change Class Teacher' : 'Assign Class Teacher' }}
                                                                                </button>
                                                                                <button
                                                                                    class="dropdown-item btn btn-info btn-sm"
                                                                                    wire:click="viewStreamStudents({{ $stream->id }})"
                                                                                    data-toggle="tooltip"
                                                                                    title="View Students">
                                                                                    View Students
                                                                                </button>

                                                                                <button
                                                                                    class="dropdown-item btn btn-info btn-sm"
                                                                                    wire:click="showStreamEntries({{ $stream->id }})"
                                                                                    data-toggle="tooltip"
                                                                                    title="Show Entries">
                                                                                    Show Entries
                                                                                </button>

                                                                                <button
                                                                                    class="dropdown-item btn btn-danger btn-sm"
                                                                                    wire:click="deleteStream({{ $stream->id }})"
                                                                                    data-toggle="tooltip"
                                                                                    title="Delete Stream">
                                                                                    Delete
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                    <button wire:click="$set('isDisplayingStreams', false)"
                                                        class="btn btn-secondary"
                                                        style="margin-top: 1rem; padding: .375rem .75rem; border-radius: .25rem;">
                                                        Close
                                                    </button>
                                                </div>

                                            </div>
                                        </td>
                                    </tr>
                                @endif
                                <!-- Entries (Students Count) -->
                                @if ($viewEntriesMode && $selectedClass && $selectedClass->id == $class->id)
                                    <tr>
                                        <td colspan="3">
                                            <div class="mt-3 card shadow-sm rounded-lg">
                                                <div class="card-header text-center bg-primary text-white">
                                                    <h5 class="mb-0">Entries for {{ $selectedClass->name }}</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <!-- Total Students & Gender Balance -->
                                                        <div class="col-md-12 mb-4">
                                                            <div class="card shadow-sm rounded-lg">
                                                                <div class="card-header bg-light">
                                                                    <strong>Total Students & Gender Balance</strong>
                                                                </div>
                                                                <div class="card-body">
                                                                    <p><strong>Total Students:</strong>
                                                                        {{ $studentsCount }}</p>

                                                                    <p><strong>Gender Balance:</strong></p>
                                                                    <ul class="list-group mb-0">
                                                                        <!-- Male Students -->
                                                                        <li class="list-group-item">
                                                                            <strong>Male:
                                                                                {{ $genderBalance['male'] }}</strong>
                                                                            <div class="mt-2"
                                                                                style="max-height: 150px; overflow-y: auto; font-size: 12px; line-height: 1.2;">
                                                                                {{ $genderBalance['male_names'] }}
                                                                            </div>
                                                                        </li>

                                                                        <!-- Female Students -->
                                                                        <li class="list-group-item">
                                                                            <strong>Female:
                                                                                {{ $genderBalance['female'] }}</strong>
                                                                            <div class="mt-2"
                                                                                style="max-height: 150px; overflow-y: auto; font-size: 12px; line-height: 1.2;">
                                                                                {{ $genderBalance['female_names'] }}
                                                                            </div>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Students Sharing Same Parent (Siblings) -->
                                                        <!-- Parent Header Styling -->
                                                        <div class="bg-light py-2 px-4 mb-4 rounded-lg shadow-sm">
                                                            <strong class="text-lg font-weight-bold text-primary"
                                                                style="color: #007bff;">Parents with more than one
                                                                student in this school:</strong>
                                                        </div>

                                                        <div class="col-md-12 mb-4">
                                                            <div class="row">
                                                                @foreach ($studentsByParent as $parent)
                                                                    <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                                                                        <div class="bg-light p-4 rounded-lg shadow-sm">
                                                                            <h5 class="font-weight-bold text-success">
                                                                                {{ $parent['parent_name'] }}</h5>
                                                                            <p><strong>Siblings:</strong></p>
                                                                            <ul class="list-unstyled">
                                                                                @foreach ($parent['students'] as $student)
                                                                                    <li
                                                                                        class="d-flex justify-content-between align-items-center mb-2">
                                                                                        <div
                                                                                            class="d-flex align-items-center">
                                                                                            <strong>{{ $student['name'] }}</strong>
                                                                                            <span
                                                                                                class="ml-2 text-muted text-sm">|</span>
                                                                                            <div
                                                                                                class="position-relative ml-2">
                                                                                                <span
                                                                                                    class="badge badge-info"
                                                                                                    style="background-color: #17a2b8;">{{ $student['status'] }}</span>
                                                                                                <!-- Arrow pointing to the badge -->
                                                                                                <span class="arrow"
                                                                                                    style="font-size: 1.2rem; position: absolute; top: 50%; left: -20px; transform: translateY(-50%); color: #17a2b8;">→</span>
                                                                                            </div>
                                                                                        </div>
                                                                                    </li>
                                                                                @endforeach
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>



                                                        <!-- Close Button -->
                                                        <div class="col-12 text-left mt-4">
                                                            <button wire:click="$set('viewEntriesMode', false)"
                                                                class="btn btn-secondary"
                                                                style="margin-top: 1rem; padding: .375rem .75rem; border-radius: .25rem;">
                                                                Close
                                                            </button>
                                                        </div>

                                                    </div>
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
    @endif


    <!-- Students Modal -->
    @if ($showStudentsModal)
        <!-- Modal backdrop -->
        <div class="modal fade show d-block" tabindex="-1" role="dialog" style="background: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-lg" role="document"
                style="max-width: 90%; margin-left: auto; margin-right: auto;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Students in {{ $modalStreamName }}</h5>
                        <button type="button" wire:click="$set('showStudentsModal', false)" class="close"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body table-responsive" style="max-height: 60vh; overflow-y: auto;">
                        <p>Total Students: {{ $modalStudentsCount }}</p>
                        <table class="table table-striped table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Admission Number</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Gender</th>
                                    <th>KCPE</th>
                                    <th>Phone</th>
                                    <th>Date of Birth</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($modalStudents as $student)
                                    <tr>
                                        <td>{{ $student->adm_no }}</td>
                                        <td>{{ $student->first_name }} {{ $student->middle_name }}
                                            {{ $student->last_name }}</td>
                                        <td>{{ $student->email }}</td>
                                        <td>{{ ucfirst($student->gender) }}</td>
                                        <td>{{ $student->kcpe }}</td>
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
    @if ($isViewingClassTeacher)
        <div class="card mt-3 shadow-lg rounded-lg border-2" style="border-color: #d1d5db;">
            <div class="card-header"
                style="background-color: #007bff; color: white; padding: 1rem; border-top-left-radius: .25rem; border-top-right-radius: .25rem;">
                <strong>Class Master Information</strong>
            </div>
            <div class="card-body"
                style="padding: 1.5rem; background-color: white; border-bottom-left-radius: .25rem; border-bottom-right-radius: .25rem;">
                <!-- Class Information -->
                <div class="mb-4">
                    <p style="font-size: 1.125rem; font-weight: 600; color: #4b5563;"><strong>Class:</strong>
                        {{ $class->name }}</p>
                    <p style="font-size: 1rem; color: #6b7280;"><strong>Class Teacher for "{{ $class->name }}" for
                            the year "{{ $classSession }}":</strong> {{ $classTeacher }}</p>
                </div>

                <!-- Teacher's Detailed Information -->
                @if ($teacher)
                    <div class="mt-6">
                        <h5 style="font-size: 1.25rem; font-weight: 700; color: #16a34a; margin-bottom: 1rem;">Teacher
                            Details</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong style="color: #374151;">Name:</strong> <span
                                        style="color: #1f2937;">{{ $teacher->name }}</span></p>
                                <p><strong style="color: #374151;">Phone:</strong> <span
                                        style="color: #1f2937;">{{ $teacher->phone ?? 'Not Available' }}</span></p>
                                <p><strong style="color: #374151;">Gender:</strong> <span
                                        style="color: #1f2937;">{{ $teacher->gender ?? 'Not Available' }}</span></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong style="color: #374151;">Code:</strong> <span
                                        style="color: #1f2937;">{{ $teacher->code ?? 'Not Available' }}</span></p>
                                <p><strong style="color: #374151;">Photo:</strong>
                                    @if ($teacher->photo)
                                        <img src="{{ $teacher->photo }}" alt="Teacher Photo"
                                            style="width: 6rem; height: 6rem; border-radius: 50%; object-fit: cover;">
                                    @else
                                        <span style="color: #6b7280;">No Photo Available</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                    <p style="color: #ef4444; margin-top: 1rem;">Teacher details are not available.</p>
                @endif

                <button wire:click="$set('isViewingClassTeacher', false)" class="btn btn-secondary"
                    style="margin-top: 1rem; padding: .375rem .75rem; border-radius: .25rem;">
                    Close
                </button>
            </div>
        </div>
    @endif



</div>
