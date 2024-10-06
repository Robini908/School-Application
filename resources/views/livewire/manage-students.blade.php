<div class="container">
    <x-flash-messages />

    {{-- Header or Page Title --}}
    <h1 class="text-center text-2xl font-bold mb-4">Manage Students</h1>

    <div class="card">

        <div class="row g-3 align-items-center m-1">
            <div class="col-auto">
                <label for="formFilter" class="form-label">Form: </label>
            </div>
            <div class="col-auto">
                <select id="form" class="form-control p-1" wire:model="formFilter">
                    <option value="">Select Form...</option>
                    <option value="">All</option>
                    @foreach($forms as $form)
                    <option value="{{ $form }}">{{ $form }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <label class="col-form-label">Stream:</label>
            </div>
            <div class="col-auto">
                <select id="section" class="form-control p-1" wire:model="sectionFilter">
                    <option value="">Select Stream...</option>
                    <option value="">All</option>
                    @if($formFilter)
                    @foreach($sections as $section)
                    <option value="{{ $section }}">{{ $section }}</option>
                    @endforeach
                    @endif
                </select>
            </div>
            <div class="col-auto">
                <label class="col-form-label">Status:</label>
            </div>
            <div class="col-auto">
                <select id="status" class="form-control p-1" wire:model="statusFilter">
                    <option value="">Select Status...</option>
                    <option value="">All</option>
                    @foreach($statuses as $status)
                    <option value="{{ $status }}">{{ $status }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Applied Filters Section -->
        <div class="m-2">
            <h5>Applied Filters:</h5>
            <div class="d-flex flex-wrap align-items-center">
                {{-- Form Filter --}}
                @if($formFilter)
                <div class="badge bg-primary me-1 d-flex align-items-center">
                    Form: {{ $formFilter }}
                    <button class="btn btn-close btn-close-white ms-1" wire:click="removeFilter('formFilter')"
                        aria-label="Close">
                        <i class="icon-close"></i>
                    </button>
                    <!-- Spinner for Form Filter -->
                    <div wire:loading wire:target="removeFilter('formFilter')"
                        class="spinner-border spinner-border-sm text-light ms-2" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                @endif

                {{-- Section Filter --}}
                @if($sectionFilter)
                <div class="badge bg-secondary me-1 d-flex align-items-center">
                    Stream: {{ $sectionFilter }}
                    <button class="btn btn-close btn-close-white ms-1" wire:click="removeFilter('sectionFilter')"
                        aria-label="Close">
                        <i class="icon-close"></i>
                    </button>
                    <!-- Spinner for Section Filter -->
                    <div wire:loading wire:target="removeFilter('sectionFilter')"
                        class="spinner-border spinner-border-sm text-light ms-2" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                @endif

                {{-- Status Filter --}}
                @if($statusFilter)
                <div class="badge bg-success me-1 d-flex align-items-center">
                    Status: {{ $statusFilter }}
                    <button class="btn btn-close btn-close-white ms-1" wire:click="removeFilter('statusFilter')"
                        aria-label="Close">
                        <i class="icon-close"></i>
                    </button>
                    <!-- Spinner for Status Filter -->
                    <div wire:loading wire:target="removeFilter('statusFilter')"
                        class="spinner-border spinner-border-sm text-light ms-2" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                @endif

                {{-- Reset Filter Button --}}
                @if($formFilter || $sectionFilter || $statusFilter)
                <button class="btn btn-danger ms-2" wire:click="resetFilters">
                    <i class="icon-reset"></i> Reset Filters
                    <!-- Spinner for Reset Filters -->
                    <div wire:loading wire:target="resetFilters"
                        class="spinner-border spinner-border-sm text-light ms-2" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </button>
                @endif
            </div>
        </div>

    </div>

    {{-- Display different views based on the boolean flags --}}
    @if ($isEditingStudent)
    {{-- Editing Student Page --}}
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3>Edit Student</h3>
            <button wire:click="closeAction" class="btn btn-secondary">Close</button>
        </div>
        <div class="card-body">
            {{-- Form for editing student --}}
            <form>
                <div class="form-group">
                    <label for="studentName">Name:</label>
                    <input type="text" id="studentName" class="form-control" wire:model="selectedStudent.name">
                </div>
                {{-- Other form fields can go here --}}
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>

    @elseif ($isViewingDetails)
    {{-- Viewing Student Details Page --}}
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3>Student Details</h3>
            <button wire:click="closeAction" class="btn btn-secondary">Close</button>
        </div>
        <div class="card-body">
            <p><strong>Name:</strong> {{ $selectedStudent->name }}</p>
            <p><strong>Class:</strong> {{ $selectedStudent->my_class->name }}</p>
            {{-- Other student details can go here --}}
        </div>
    </div>

    @elseif ($isDeleting)
    {{-- Confirm Delete Page --}}
    <div class="card mb-4">
        <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
            <h3>Confirm Delete</h3>
            <button wire:click="closeAction" class="btn btn-secondary">Close</button>
        </div>
        <div class="card-body">
            <p>Are you sure you want to delete {{ $selectedStudent->name }}?</p>
            <button wire:click="confirmDelete" class="btn btn-danger">Yes, Delete</button>
            <button wire:click="cancelDelete" class="btn btn-secondary">Cancel</button>
        </div>
    </div>

    @elseif ($isExpellingStudent)
    {{-- Expelling Student Page --}}




    @elseif ($isSuspendingStudent)
    {{-- Suspending Student Page --}}
    <div class="card mb-3">
        <div class="card-header text-white d-flex justify-content-between align-items-center p-2">
            <h4 class="mb-0 text-truncate">
                {{ $selectedStudent->status === 'suspended' ? 'Reinstate Student' : 'Suspend Student' }}: 
                {{ $selectedStudent->first_name }} {{ $selectedStudent->last_name }}
            </h4>
            <button wire:click="closeAction" class="btn btn-light btn-sm rounded-pill">Close</button>
        </div>
    
        <div class="card-body p-3">
            <!-- Student Photo and Info Section -->
            <div class="d-flex align-items-center mb-2">
                <img src="{{ asset($selectedStudent->photo) }}" alt="Student Photo"
                    class="img-fluid rounded-circle shadow-sm me-3" style="width: 80px; height: 80px;">
                <div>
                    <h6 class="text-muted">Class: <span class="font-weight-bold">{{ $selectedStudent->my_class->name }}</span></h6>
                    <h6 class="text-muted">Status: <span class="font-weight-bold">{{ $selectedStudent->status }}</span></h6>
                    <h6 class="text-muted">Section: <span class="font-weight-bold">{{ $selectedStudent->section->name }}</span></h6>
                    <h6 class="text-muted">Admission No: <span class="font-weight-bold">{{ $selectedStudent->adm_no }}</span></h6>
                </div>
            </div>
    
            <div class="row mb-2">
                <div class="col-md-6">
                    <h6 class="text-muted">Parent: <span class="font-weight-bold">{{ optional($selectedStudent->parent_detail)->name ?? 'N/A' }}</span></h6>
                    <h6 class="text-muted">Year Admitted: <span class="font-weight-bold">{{ $selectedStudent->year_admitted }}</span></h6>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted">KCPE Score: <span class="font-weight-bold">{{ $selectedStudent->kcpe }}</span></h6>
                    <h6 class="text-muted">Email: <span class="font-weight-bold">{{ $selectedStudent->email }}</span></h6>
                </div>
            </div>
    
            <!-- Suspend or Reinstate Actions -->
            <div class="text-center mb-2">
                @if ($selectedStudent->status === 'suspended')
                    <p class="mb-2">Are you sure you want to reinstate this student?</p>
                    <div class="d-flex justify-content-center">
                        <button wire:click="reinstateStudent" class="btn btn-success mx-2 rounded-pill">Reinstate</button>
                        <button wire:click="cancelAction" class="btn btn-secondary mx-2 rounded-pill">Cancel</button>
                    </div>
                @else
                    <p class="mb-2">Are you sure you want to suspend this student?</p>
    
                    <!-- Reason for Suspension -->
                    <div class="form-group mb-2">
                        <label for="suspensionReason" class="form-label">Reason for Suspension</label>
                        <textarea wire:model="suspensionReason" id="suspensionReason" class="form-control" rows="2" required></textarea>
                        @error('suspensionReason') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
    
                    <!-- Type of Suspension -->
                    <div class="form-group mb-2">
                        <label for="suspensionType" class="form-label">Type of Suspension</label>
                        <select wire:model="suspensionType" id="suspensionType" class="form-control" required>
                            <option value="">Select Type</option>
                            <option value="dismissal">Dismissal</option>
                            <option value="withdrawal">Withdrawal</option>
                            <option value="permanent_exclusion">Permanent Exclusion</option>
                        </select>
                        @error('suspensionType') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
    
                    <!-- Duration of Suspension -->
                    <div class="form-group mb-3">
                        <label for="suspensionEndDate" class="form-label">Suspension End Date</label>
                        <input type="date" wire:model="suspensionEndDate" id="suspensionEndDate" class="form-control" required>
                        @error('suspensionEndDate') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
    
                    <div class="d-flex justify-content-center align-items-center">
                        <button wire:click="confirmStudentSuspension" class="btn btn-danger mx-2 rounded-pill">
                            Suspend
                        </button>
                        <div wire:loading wire:target="confirmStudentSuspension" >
                            <div class="spinner-border text-danger ms-2" role="status" style="height: 1rem; width: 1rem;"></div>
                        </div>
                        <button wire:click="cancelSuspend" class="btn btn-secondary mx-2 rounded-pill">Cancel</button>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    @elseif ($isViewingHistoryDetails)
    {{-- Viewing Student History Page --}}
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3>Student History</h3>
            <button wire:click="closeAction" class="btn btn-secondary">Close</button>
        </div>
        <div class="card-body">
            {{-- Display student history details --}}
            <p>{{ $selectedStudent->history }}</p>
        </div>
    </div>

    @elseif ($isApproving)
    {{-- Approving Student Page --}}
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title">Approve Student</h5>
                <button wire:click="closeAction" class="btn btn-secondary btn-sm">Close</button>
            </div>
    
            <p class="text-muted">Are you sure you want to approve the following student?</p>
    
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="text-muted">Name</h6>
                    <p class="lead">{{ $selectedStudent->first_name }} {{ $selectedStudent->last_name }}</p>
    
                    <h6 class="text-muted">Email</h6>
                    <p class="lead">{{ $selectedStudent->email ?? 'N/A' }}</p>
                </div>
    
                <div class="col-md-6">
                    <h6 class="text-muted">Parent</h6>
                    <p class="lead">{{ $selectedStudent->parent_detail->parent_first_name ?? 'N/A' }} {{ $selectedStudent->parent_detail->parent_last_name ?? 'N/A' }}</p>
    
                    <h6 class="text-muted">Class</h6>
                    <p class="lead">{{ $selectedStudent->my_class->name ?? 'N/A' }}</p>
    
                    <h6 class="text-muted">Section</h6>
                    <p class="lead">{{ $selectedStudent->section->name ?? 'N/A' }}</p>
                </div>
            </div>
    
            <!-- Action Buttons: Approve and Disapprove -->
            <div class="d-flex mt-4" style="gap: 10px;">
                <div class="d-flex align-items-center">
                    <button wire:click="confirmApproval" class="btn btn-success">Approve</button>
                    <div wire:loading wire:target="confirmApproval" class="spinner-border spinner-border-sm ms-2" role="status"></div>
                </div>
                <div class="d-flex align-items-center">
                    <button wire:click="toggleDisapproval" class="btn btn-danger">Disapprove</button>
                    <div wire:loading wire:target="toggleDisapproval" class="spinner-border spinner-border-sm ms-2" role="status"></div>
                </div>
            </div>
    
            <!-- Disapproval Section -->
            @if ($isDisapproving)
            <div class="mt-4">
                <div class="card card-body bg-light">
                    <label for="disapprovalReason"><strong>Reason for Disapproval:</strong></label>
                    <textarea wire:model="disapprovalReason" id="disapprovalReason" class="form-control" placeholder="Provide reason for disapproval"></textarea>
                    <div class="d-flex justify-content-between mt-2">
                        <div class="d-flex align-items-center">
                            <button wire:click="cancelApproval" class="btn btn-danger">Submit Disapproval</button>
                            <div wire:loading wire:target="cancelApproval" class="spinner-border spinner-border-sm ms-2" role="status"></div>
                        </div>
                        <button wire:click="toggleDisapproval" class="btn btn-secondary">Close</button>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    
    @elseif ($isSendingStudentMail)
    {{-- Send Email to Student Page --}}
    <div class="card mb-4">
        <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Send Email to Student</h3>
            <button wire:click="closeAction" class="btn btn-secondary btn-sm">Close</button>
        </div>

        <div class="card-body">
            <!-- Display Student Details -->
            <div class="mb-4">
                <h5>Student Details</h5>
                <div class="row">
                    <div class="col-md-4">
                        <p><strong>Name:</strong> {{ $selectedStudent->first_name }} {{ $selectedStudent->last_name }}
                        </p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Stream:</strong> {{ $selectedStudent->stream }}</p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Class:</strong> {{ $selectedStudent->class }}</p>
                    </div>
                </div>
            </div>

            <!-- Rich Text Editor for Email Content -->
            <div class="mb-4">
                <label for="notificationContent" class="form-label">Email Content:</label>
                <div wire:ignore>
                    <input id="notificationContent" type="hidden" name="notificationContent"
                        wire:model.defer="notificationContent">
                    <trix-editor input="notificationContent" class="trix-content" style="min-height: 150px;">
                    </trix-editor>
                </div>
            </div>

            <!-- File Upload -->
            <div class="mb-4">
                <label for="file" class="form-label">Attach a File:</label>
                <input type="file" wire:model="file" class="form-control form-control-sm">
                @error('file')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="d-flex justify-content-end" style="gap: 10px;">
                <button wire:click="sendStudentMail({{ $selectedStudent->id }})" class="btn btn-primary btn-sm">
                    Send Mail
                </button>
                <button wire:click="cancelReject" class="btn btn-secondary btn-sm">Cancel</button>
            </div>
        </div>
    </div>

    @else
   
    {{-- Default student list if no actions are being performed --}}
    <div class="table-responsive" wire:poll.10s="fetchStudents">
        <x-data-table id="studentTable" title="Student List" message="List of registered students"
            :columns="['Admission', 'Student Photo', 'Name', 'Gender', 'Class', 'Section', 'Status', 'Parent Name', 'Parent Contact', 'Actions']">
            @if($noResults)
            <tr>
                <td colspan="9" class="text-center">No students found for the selected filters.</td>
            </tr>
            @else
            @foreach($this->mystudents as $student)
            <tr @if($student->is_suspended) style="background-color: #f8d7da;" @endif>
                <td>{{ $student->adm_no }}</td>
                <td>
                    @if ($student->photo)
                    <img src="{{ asset($student->photo) }}" alt="Student Photo" class="img-thumbnail"
                        style="width: 50px; height: 50px;">
                    @else
                    <span class="text-muted">No Image</span>
                    @endif
                </td>
                <td>
                    <span>
                        @if ($student['is_suspended'])
                        <span class="badge badge-danger">{{ $student['suspension_type'] }}</span>
                        @endif
                        <a href="{{ route('student.info', ['id' => $student['id']]) }}">
                            {{ $student['first_name'] }} {{ $student['last_name'] }}
                        </a>
                    </span>
                </td>
    
                <td>{{ $student->gender }}</td>
                <td>{{ $student->my_class->name ?? 'N/A' }}</td>
                <td>{{ $student->section->name ?? 'N/A' }}</td>
                <td>
                    <span class="badge {{ $student->status == 'Active' ? 'badge-success' : 'badge-warning' }}">
                        {{ $student->status }}
                    </span>
                </td>
                <td>{{ $student->parent_detail->parent_first_name ?? 'N/A' }} {{
                    $student->parent_detail->parent_last_name ?? 'N/A' }}</td>
                <td>{{ $student->parent_detail->parent_phone_number ?? 'N/A' }}</td>
                <td class="text-center">
                    <div class="list-icons">
                        <div class="dropdown @if($student->is_suspended) bg-light @endif">
                            <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown"
                                aria-expanded="false">
                                <i class="icon-menu9"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right @if($student->is_suspended) bg-white @endif">
                                <li>
                                    <button wire:click="editStudent({{ $student->id }})"
                                        class="dropdown-item @if($student->is_suspended) disabled @endif">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                </li>
                                <li>
                                    <button wire:click="deleteRecord({{ $student->id }})"
                                        class="dropdown-item @if($student->is_suspended) disabled @endif">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </li>
                                <li>
                                    <button wire:click="viewStudent({{ $student->id }})" class="dropdown-item">
                                        <i class="bi bi-eye"></i> View Details
                                    </button>
                                </li>
                                <li>
                                    <button wire:click="suspendStudent({{ $student->id }})"
                                        class="dropdown-item @if($student->is_suspended) disabled @endif">
                                        <i class="bi bi-pause"></i> Suspension
                                    </button>
                                </li>
                                <li>
                                    <button wire:click="favoriteStudent({{ $student->id }})" class="dropdown-item">
                                        <i class="bi bi-star"></i> Student History
                                    </button>
                                </li>
                                <li>
                                    <button wire:click="approveStudent({{ $student->id }})"
                                        class="dropdown-item @if($student->is_suspended) disabled @endif">
                                        <i class="bi bi-check"></i> Approve
                                    </button>
                                </li>
                                <li>
                                    <button wire:click="studentExpulsion({{ $student->id }})"
                                        class="dropdown-item @if($student->is_expelled) disabled @endif">
                                        <i class="bi bi-exclamation-triangle"></i> Expulsion
                                    </button>
                                </li>
                                <li>
                                    <button wire:click="isSendingStudentMail({{ $student->id }})"
                                        class="dropdown-item @if($student->is_suspended) disabled @endif">
                                        <i class="bi bi-x"></i> Send Mail
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
            @endif
        </x-data-table>
    </div>
    

    @endif
</div>