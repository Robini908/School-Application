<div class="card  p-3 shadow-lg border rounded" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">


    @if ($isCreating || $isEditing || $isAssigningTeacher || $sectionDetails)

        <div class="card-body">
            <x-flash-messages />

            @if ($isCreating)
                <h5>Create New Stream</h5>
                @if ($teachers->isEmpty())
                    <div class="alert alert-danger">No teachers available. Please add teachers before creating a
                        stream.</div>
                @elseif($allTeachersAssignedMessage === 'All teachers are assigned to classes.')
                    <div class="alert alert-danger">Sorry!The creation of new streams is disabled.<br><br>All
                        teachers are
                        currently assigned to classes.<br><br> Please add more teachers to be able to select any
                        among them and
                        assign the <strong>Class Teacher role</strong>.
                    </div>
                @else
                    <div class="alert alert-info">Please fill in the details to create a new stream.</div>
                @endif
            @elseif($isEditing)
                <h5>Edit Stream</h5>
                @if ($teachers->isEmpty())
                    <div class="alert alert-danger">No teachers available. You cannot edit this stream without
                        teachers.</div>
                @elseif($allTeachersAssignedMessage === 'All teachers are assigned to classes.')
                    <div class="alert alert-info">All teachers are
                        currently assigned to classes.<br><br>You cannot therefore update the <strong>Class
                            Teacher</strong> of
                        this stream.
                    </div>
                @else
                    <div class="alert alert-info">Modify the details of the selected stream as needed.</div>
                @endif
            @elseif($isAssigningTeacher)
                <h5>Change Class Teacher for Stream: {{ $name }}</h5>
                @if ($teachers->isEmpty())
                    <div class="alert alert-danger">No teachers available. You cannot assign a class teacher to this
                        section.
                    </div>
                @elseif($allTeachersAssignedMessage === 'All teachers are assigned to classes.')
                    <div class="alert alert-warning">All teachers are currently assigned to classes.<br><br> You
                        cannot
                        therefore change or assign a Class
                        teacher of this stream.<br><br> Consider adding more teachers</div>
                @else
                    <div class=""></div>
                @endif
            @elseif($sectionDetails)
            @endif



            @if ($isCreating || $isEditing)
                <form wire:submit="save">
                    <div class="form-group">
                        <label for="name">Stream Name</label>
                        <input wire:model.live="name" type="text" class="form-control" id="name"
                            placeholder="Enter stream name" required>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="class">Select Class</label>
                        <select wire:model.live="my_class_id" class="custom-select" id="class" required>
                            <option value="">Select Class</option>
                            @foreach ($my_classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                        @error('my_class_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="teacher">Select Teacher</label>
                        <select wire:model.live="teacher_id" class="custom-select"
                            @if ($isTeacherDropdownDisabled) disabled @endif>
                            <option value="">Select Teacher</option>
                            @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                        @error('teacher_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-success"
                        {{ $isCreating && $allTeachersAssignedMessage ? 'disabled' : '' }}>Save</button>
                    <button type="button" wire:click="closeForm" class="btn btn-secondary">Close</button>
                </form>
            @elseif($isAssigningTeacher)
                <p>Current Class Teacher: {{ $sections->find($editSectionId)->teacher->name ?? 'Not Assigned' }}</p>
                <select wire:model.live="teacher_id" class="custom-select">
                    <option value="">Select a Teacher</option>
                    @foreach ($teachers as $teacher)
                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                    @endforeach
                </select>
                <button wire:click="assignTeacherSave" class="btn btn-success mt-2"
                    {{ $allTeachersAssignedMessage ? 'disabled' : '' }}>Save Assignment</button>
                <button wire:click="resetForm" class="btn btn-secondary mt-2">Cancel</button>
            @elseif($sectionDetails)
                <h2 class="text-2xl font-semibold mb-4"> {{ $sectionDetails->my_class->name }}
                    {{ $sectionDetails->name }}
                    Details</h2>

                <div class="mb-4">
                    <p class="h5"><strong>Class Teacher:</strong>
                        {{ $sectionDetails->teacher ? $sectionDetails->teacher->name : 'None' }}
                    </p>
                    @if (!$sectionDetails->teacher)
                        <!-- Check if there's no teacher assigned -->
                        <button wire:click="assignTeacher({{ $sectionDetails->id }})" class="btn btn-info">Assign
                            Teacher</button>
                    @endif
                </div>


                <h4 class="h5 mt-6 mb-2">Students in this Stream:</h4>
                @if ($students->isEmpty())
                    <div class="alert alert-danger">No students are enrolled in this section.</div>
                @else
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th>Photo</th>
                                    <th>Admission No</th>
                                    <th>First Name</th>
                                    <th>Middle Name</th>
                                    <th>Last Name</th>
                                    <th>Gender</th>
                                    <th>Email</th>
                                    <th>Year Admitted</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($students as $student)
                                    <tr>
                                        <td>
                                            @if ($student->photo)
                                                <img src="{{ asset($student->photo) }}" alt="Student Photo"
                                                    class="img-thumbnail" style="width: 50px; height: 50px;">
                                            @else
                                                <span class="text-muted">No Image</span>
                                            @endif
                                        </td>
                                        <td>{{ $student->adm_no }}</td>
                                        <td>{{ $student->first_name }}</td>
                                        <td>{{ $student->middle_name }}</td>
                                        <td>{{ $student->last_name }}</td>
                                        <td>{{ $student->gender }}</td>
                                        <td>{{ $student->email }}</td>
                                        <td>{{ $student->year_admitted }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                @endif




                <h4 class="h5 mt-6 mb-2">Subjects done in this stream:</h4>
                @if ($subjects->isEmpty())
                    <div class="alert alert-danger">No subjects are available in the system.</div>
                @else
                    <div class="table-responsive">
                        <x-data-table id="subjectTable" title="Subject List" message="List of available subjects"
                            :columns="['Name', 'Code', 'Abbreviation']">
                            @if ($subjects->count() === 0)
                                <tr>
                                    <td colspan="3" class="text-center">No subjects found.</td>
                                </tr>
                            @else
                                @foreach ($subjects as $subject)
                                    <tr>
                                        <td>{{ $subject->subject_name }}</td>
                                        <td>{{ $subject->subject_code }}</td>
                                        <td>{{ $subject->abbreviation }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </x-data-table>
                    </div>

                @endif

                <div class="mt-4">
                    <button wire:click="closeDetails" class="btn btn-secondary">Close</button>
                </div>
            @endif
        </div>

    @endif

    @if (!$isCreating && !$isEditing && !$isAssigningTeacher && !$sectionDetails)

        <div class="d-flex justify-content-between align-items-center">
            <div class="p-3">
                <label for="classFilter" class="form-label">Filter by Class:</label>
                <select wire:model.live="selectedClass" id="classFilter" class="custom-select">
                    <option value="">All Classes</option>
                    @foreach ($my_classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2">
                <button wire:click="create" class="btn btn-primary d-flex align-items-center">New</button>
               
            </div>
        </div>

        <div class="card-body">
            
                                    
            <h5 class="card-title">Streams List</h5>
            <div class="table-responsive">
                <table class="table  datatable-button-html5-columns">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Stream name</th>
                            <th>Class </th>
                            <th>Class Teacher</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sections as $section)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $section->name }}</td>
                                <td>{{ $section->my_class->name }}</td>
                                <td>{{ $section->teacher ? $section->teacher->name : 'Not Assigned' }}</td>

                                <td class="text-center">
                                    <div class="list-icons">
                                        <div class="dropdown">
                                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                <i class="icon-menu9"></i>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-left">
                                                {{-- Edit Button --}}
                                                <button wire:click="edit({{ $section->id }})"
                                                    class="dropdown-item btn btn-warning">
                                                    <i class="icon-pencil"></i> Edit
                                                </button>

                                                {{-- Assign or Change Teacher based on condition --}}
                                                @if (!$section->teacher)
                                                    <button wire:click="assignTeacher({{ $section->id }})"
                                                        class="dropdown-item btn btn-info">
                                                        <i class="icon-user"></i> Assign Teacher
                                                    </button>
                                                @else
                                                    <button wire:click="changeClassTeacher({{ $section->id }})"
                                                        class="dropdown-item btn btn-primary">
                                                        <i class="icon-user-check"></i> Change Class Teacher
                                                    </button>
                                                @endif

                                                {{-- Delete Button --}}
                                                <button wire:click="delete({{ $section->id }})"
                                                    class="dropdown-item btn btn-danger">
                                                    <i class="icon-trash"></i> Delete
                                                </button>

                                                {{-- Show Details Button --}}
                                                <button wire:click="showDetails({{ $section->id }})"
                                                    class="dropdown-item btn btn-secondary">
                                                    <i class="icon-info3"></i> Details
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- <div class="mt-2">
                    {{ $this->sections->links() }}  <!-- This will render the pagination links -->
                </div> --}}
            </div>
        </div>

    @endif

    @if ($confirmingDelete)
        <div class="modal fade show" style="display: block;" tabindex="-1" role="dialog"
            aria-labelledby="confirmDeleteLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteLabel">Confirm Delete</h5>
                        <button type="button" class="close" wire:click="cancelDelete" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this section?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="cancelDelete">Cancel</button>
                        <button type="button" class="btn btn-danger" wire:click="confirmDelete">Delete</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Overlay background for the modal -->
        <div class="modal-backdrop fade show"></div>
    @endif
