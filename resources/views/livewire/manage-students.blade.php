<div class="card mt-4 col-12 p-3 shadow-lg border rounded" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">

    {{-- Header or Page Title --}}
    @if (
        !(
            $isEditingStudent ||
            $isViewingDetails ||
            $isDeleting ||
            $isApproving ||
            $isSuspendingStudent ||
            $isExpellingStudent
        ))
        <div class="d-flex justify-content-sm-between ">
            <div class="mb-2">
                <h1 class="text-center text-2xl font-bold">Manage Students</h1>
            </div>
            {{-- <button wire:click="generatePdfReport" wire:loading.attr="disabled" wire:loading.class="btn-secondary"
                wire:target="generatePdfReport" class="btn btn-danger p-1 btn-sm ">
                <i class="fas fa-file-pdf"></i>
                <span wire:loading.remove wire:target="generatePdfReport">PDF</span>
                <span wire:loading wire:target="generatePdfReport">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                </span>
            </button> --}}
        </div>
    @endif

    {{-- Display different views based on the boolean flags --}}

    @if ($isEditingStudent)
        <div wire:key="edit-student-{{ $selectedStudent->id }}">
            <!-- Button placed on the far right -->
            <div class="d-flex justify-content-end mb-3">
                <button wire:click="closeAction" class="btn btn-secondary">Close</button>
            </div>
            <!-- Edit Student Component -->
            <livewire:edit-student :studentId="$selectedStudent->id" />
        </div>
    @elseif ($isViewingDetails && $selectedStudent)
    <div>
        <div>
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="mb-0">Send Email to {{ $selectedStudent->first_name }}
                    {{ $selectedStudent->last_name }}({{ $selectedStudent->adm_no }})</h3>
                <button wire:click="closeAction" class="btn btn-light btn-sm">Close</button>
            </div>
    
            <div class="card-body">
                <!-- TinyMCE Editor for Email Content -->
                <div class="mb-4">
                    <label for="tinyMCE" class="form-label text-primary">Email Content:</label>
                    <textarea id="tinyMCE" wire:model="notificationContent" class="form-control"></textarea>
                    @error('notificationContent')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
    
                <!-- File Upload -->
                <div class="mb-4">
                    <label for="file" class="form-label text-primary">Attach a File:</label>
                    <input type="file" wire:model.live="file" class="form-control form-control-sm">
                    @error('file')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
    
                <!-- Buttons -->
                <div class="d-flex justify-content-end gap-2">
                    <button wire:click="cancelReject" class="btn btn-outline-secondary btn-sm">Cancel</button>
                    <button wire:click="sendStudentMail({{ $selectedStudent->id }})" class="btn btn-primary btn-sm">
                        Send Mail
                        <span wire:loading wire:target="sendStudentMail({{ $selectedStudent->id }})"
                            class="spinner-border spinner-border-sm ms-2"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    @elseif ($isDeleting)
        {{-- Confirm Delete Page --}}
        {{-- <div class="card mt-2 col-12 p-3 shadow-lg border rounded" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);"> --}}
        <div class="card-body">
            <p>
                <strong>Confirm Deletion</strong>
            </p>
            <div class="alert alert-info shadow-sm p-4 rounded" role="alert"
                style="background-color: #f0f8ff; border-left: 5px solid #17a2b8;">

                You're about to delete <strong>{{ $selectedStudent->first_name }}
                    {{ $selectedStudent->middle_name }}
                    {{ $selectedStudent->last_name }}</strong> (Admission No: {{ $selectedStudent->adm_no }}).
                This will permanently remove all financial records, exams, and everything associated with this
                student
                from our system.
            </div>
            <p class="font-bold text-danger">
                Please confirm this action. Once deleted, it cannot be undone.
            </p>
            <div class="d-flex justify-content-between">
                <button wire:click="confirmDelete" class="btn btn-danger">Yes, Delete
                    <div wire:loading wire:target="confirmDelete"
                        class="spinner-border spinner-border-sm text-light ms-2" role="status"></div>
                </button>
                <button wire:click="cancelDelete" class="btn btn-secondary">Cancel
                    <div wire:loading wire:target="cancelDelete"
                        class="spinner-border spinner-border-sm text-light ms-2" role="status"></div>
                </button>
            </div>
        </div>
        {{-- </div> --}}
    @elseif ($isExpellingStudent)
        {{-- Expelling Student Page --}}


        <div class="card-body">
            <p class="text-lg text-semibold">
                Students Expulsion
            </p>
            <button wire:click="closeAction" class="btn btn-secondary">Close</button>
        </div>
    @elseif ($isSuspendingStudent)
        {{-- Suspending Student Page --}}
        <div>
            <div class="text-danger p-2">
                <h4 class="mb-0 text-truncate">
                    {{ $selectedStudent->status === 'suspended' ? 'Reinstate Student' : 'Suspend Student' }}:</br>
                    <strong class="text-success">{{ $selectedStudent->first_name }}
                        {{ $selectedStudent->last_name }}
                        ({{ $selectedStudent->adm_no }}) from {{ $selectedStudent->my_class->name }} -
                        {{ $selectedStudent->section->name }} admitted on
                        {{ $selectedStudent->year_admitted }}</strong>
                </h4>
            </div>

            <!-- Suspend or Reinstate Actions -->
            <div class="text-center mb-2">
                @if ($selectedStudent->status === 'suspended')
                    <div class="d-flex justify-content-center">
                        <button wire:click="reinstateStudent" class="btn btn-success mx-2">Reinstate</button>
                        <button wire:click="cancelAction" class="btn btn-secondary mx-2">Cancel</button>
                    </div>
                @else
                    <!-- Reason for Suspension -->
                    <div class="form-group">
                        <label for="suspensionReason" class="form-label">Reason for Suspension</label>
                        <textarea wire:model.live="suspensionReason" id="suspensionReason" class="form-control" rows="2" required></textarea>
                        @error('suspensionReason')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Type of Suspension -->
                    <div class="form-group">
                        <label for="suspensionType" class="form-label">Type of Suspension</label>
                        <select wire:model.live="suspensionType" id="suspensionType" class="form-control" required>
                            <option value="">Select Type</option>
                            <option value="dismissal">Dismissal</option>
                            <option value="withdrawal">Withdrawal</option>
                            <option value="permanent_exclusion">Permanent Exclusion</option>
                        </select>
                        @error('suspensionType')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Duration of Suspension -->
                    <div class="form-group">
                        <label for="suspensionEndDate" class="form-label">Suspension End Date</label>
                        <input type="date" wire:model.live="suspensionEndDate" id="suspensionEndDate"
                            class="form-control" required>
                        @error('suspensionEndDate')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-start align-items-center">
                        <button wire:click="confirmStudentSuspension"
                            class="btn btn-danger btn-sm mx-2">Suspend</button>
                        <div wire:loading wire:target="confirmStudentSuspension">
                            <div class="spinner-border text-danger ms-2" role="status"
                                style="height: 1rem; width: 1rem;"></div>
                        </div>
                        <button wire:click="closeAction" class="btn btn-secondary btn-sm mx-2">Cancel</button>
                    </div>
                @endif
            </div>
        </div>
    @elseif ($isViewingHistoryDetails)
        <div class="text-success">info</div>
    @elseif ($isApproving)
        {{-- Approving Student Page --}}

        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title">Approve Student</h5>
                <button wire:click="closeAction" class="btn btn-secondary btn-sm">Close</button>
            </div>

            <p class="text-muted">Are you sure you want to approve/Disapprove the following student?</p>
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="alert alert-info shadow-sm p-4 rounded" role="alert"
                        style="background-color: #f0f8ff; border-left: 5px solid #17a2b8;">
                        <h5 class="mb-3">
                            Approval Confirmation
                        </h5>
                        <p class="mb-2">
                            Please confirm that you want to approve <strong>{{ $selectedStudent->first_name }}
                                {{ $selectedStudent->last_name }}</strong>
                            who is enrolled in <strong>{{ $selectedStudent->my_class->name }}</strong> -
                            <strong>{{ $selectedStudent->section->name }}</strong>.
                        </p>
                        <p class="mb-2">
                            This student was admitted on <strong>{{ $selectedStudent->year_admitted }}</strong>.
                        </p>
                        <p class="mb-2">
                            Their email address is <strong>{{ $selectedStudent->email ?? 'N/A' }}</strong>, and
                            their
                            parent is <strong>{{ $selectedStudent->parent_detail->parent_first_name ?? 'N/A' }}
                                {{ $selectedStudent->parent_detail->parent_last_name ?? 'N/A' }}</strong>.
                        </p>
                    </div>
                </div>
            </div>


            <!-- Action Buttons: Approve and Disapprove -->
            <div class="d-flex mt-4" style="gap: 10px;">
                <div class="d-flex align-items-center">
                    <button wire:click="confirmApproval" class="btn btn-success">Approve</button>
                    <div wire:loading wire:target="confirmApproval" class="spinner-border spinner-border-sm ms-2"
                        role="status"></div>
                </div>
                <div class="d-flex align-items-center">
                    <button wire:click="toggleDisapproval" class="btn btn-danger">Disapprove</button>
                    <div wire:loading wire:target="toggleDisapproval" class="spinner-border spinner-border-sm ms-2"
                        role="status"></div>
                </div>
            </div>

            <!-- Disapproval Section -->
            @if ($isDisapproving)
                <div class="mt-4">
                    <div class="card card-body bg-light">
                        <label for="disapprovalReason"><strong>Reason for Disapproval:</strong></label>
                        <textarea wire:model.live="disapprovalReason" id="disapprovalReason" class="form-control"
                            placeholder="Provide reason for disapproval"></textarea>
                        <div class="d-flex justify-content-between mt-2">
                            <div class="d-flex align-items-center">
                                <button wire:click="cancelApproval" class="btn btn-danger">Submit
                                    Disapproval</button>
                                <div wire:loading wire:target="cancelApproval"
                                    class="spinner-border spinner-border-sm ms-2" role="status"></div>
                            </div>
                            <button wire:click="toggleDisapproval" class="btn btn-secondary">Close</button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @elseif ($isSendingStudentMail)
        {{-- Send Email to Student Page --}}
    @else
        {{-- Default student list if no actions are being performed --}}
        <div class="table-responsive" wire:poll.30s="fetchStudents">

            <div class="card">

                <div class="row g-3 align-items-center d-flex m-1">
                    <div class="col-12 col-md-3">
                        <label for="formFilter" class="form-label">Form:</label>
                        <select id="form" class="form-select form-control p-1" wire:model.live="formFilter">
                            <option value="">Select Form...</option>
                            <option value="">All</option>
                            @foreach ($forms as $form)
                                <option value="{{ $form }}">{{ $form }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-3">
                        <label for="section" class="form-label">Stream:</label>
                        <select id="section" class="form-select form-control p-1" wire:model.live="sectionFilter">
                            <option value="">Select Stream...</option>
                            <option value="">All</option>
                            @if ($formFilter)
                                @foreach ($sections as $section)
                                    <option value="{{ $section }}">{{ $section }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-12 col-md-3">
                        <label for="status" class="form-label">Status:</label>
                        <select id="status" class="form-select form-control p-1" wire:model.live="statusFilter">
                            <option value="">Select Status...</option>
                            <option value="">All</option>
                            @foreach ($statuses as $status)
                                <option value="{{ $status }}">{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-3">
                        <label for="year" class="form-label">Year:</label>
                        <select wire:model.live="transitionYearFilter" class="form-select form-control">
                            <option value="">Select Transition Year</option>
                            @foreach ($transitionYears as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="p-2">
                            <div class="input-group">
                                <input type="text" wire:model.live="searchTerm" class="form-control" placeholder="Search students...">
                                <span class="input-group-text bg-transparent border-start-0">
                                    <i class="fas fa-search text-muted"></i> <!-- Font Awesome search icon -->
                                </span>
                            </div>
                        </div>
                    </div>


                </div>


                <!-- Applied Filters Section -->
                <div class="m-2 justify-content-between">
                    <div class="d-flex flex-wrap align-items-center">
                        {{-- Form Filter --}}
                        @if ($formFilter)
                            <div class="text-success me-1 d-flex align-items-center">
                                Form: {{ $formFilter }}
                                <button class="btn btn-close btn-close-white ms-1 p-0"
                                    wire:click="removeFilter('formFilter')" aria-label="Close">
                                    <i class="fas fa-times"></i>
                                </button>
                                <!-- Spinner for Form Filter -->
                                <div wire:loading wire:target="removeFilter('formFilter')"
                                    class="spinner-border spinner-border-sm text-light ms-2" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        @endif

                        {{-- Section Filter --}}
                        @if ($sectionFilter)
                            <div class="text-success me-1 d-flex align-items-center">
                                Stream: {{ $sectionFilter }}
                                <button class="btn btn-close btn-close-white ms-1 p-0"
                                    wire:click="removeFilter('sectionFilter')" aria-label="Close">
                                    <i class="fas fa-times"></i>
                                </button>
                                <!-- Spinner for Section Filter -->
                                <div wire:loading wire:target="removeFilter('sectionFilter')"
                                    class="spinner-border spinner-border-sm text-light ms-2" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        @endif

                        {{-- Status Filter --}}
                        @if ($statusFilter)
                            <div class="text-success me-1 d-flex align-items-center">
                                Status: {{ $statusFilter }}
                                <button class="btn btn-close btn-close-white ms-1 p-0"
                                    wire:click="removeFilter('statusFilter')" aria-label="Close">
                                    <i class="fas fa-times"></i>
                                </button>
                                <!-- Spinner for Status Filter -->
                                <div wire:loading wire:target="removeFilter('statusFilter')"
                                    class="spinner-border spinner-border-sm text-light ms-2" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        @endif

                        {{-- Reset Filter Icon --}}
                        @if ($formFilter || $sectionFilter || $statusFilter)
                            <span class="ms-2" wire:click="resetFilters" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="Reset Filters" style="cursor: pointer;">
                                <i class="fas fa-sync-alt text-danger" style="font-size: 1.5rem;"></i>
                                <!-- Spinner for Reset Filters -->
                                <div wire:loading wire:target="resetFilters"
                                    class="spinner-border spinner-border-sm text-light ms-2" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </span>
                        @endif

                    </div>
                </div>


                <x-data-table id="studentTable" title="Student List" message="List of registered students"
                    :columns="[
                        'Admission',
                        'Admission Year',
                        'Student Photo',
                        'Name',
                    
                        'Gender',
                        'Class',
                        'Section',
                        'Status',
                        'Parent Name',
                        'Parent Contact',
                        'Actions',
                    ]">

                    @if ($students->isEmpty())
                        <tr>
                            <td colspan="9" class="text-center">No students found for the selected filters.
                            </td>
                        </tr>
                    @else
                        @foreach ($students as $student)
                            <tr @if ($student->is_suspended) style="background-color: #f8d7da;" @endif>
                                <td>{{ $student->adm_no }}</td>
                                <td>{{ $student->year_admitted }}</td>
                                <td>
                                    @if ($student->photo)
                                        <img src="{{ asset($student->photo) }}" alt="Student Photo"
                                            class="img-thumbnail" style="width: 50px; height: 50px;">
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
                                    <span
                                        class="badge {{ $student->status == 'Active' ? 'badge-success' : 'badge-warning' }}">
                                        {{ $student->status }}
                                    </span>
                                </td>
                                <td>{{ $student->parent_detail->parent_first_name ?? 'N/A' }}
                                    {{ $student->parent_detail->parent_last_name ?? 'N/A' }}</td>
                                <td>{{ $student->parent_detail->parent_phone_number ?? 'N/A' }}</td>
                                <td class="text-center">
                                    <div class="list-icons">
                                        <div class="dropdown @if ($student->is_suspended) bg-light @endif">
                                            <!-- Dropdown Toggle Button -->
                                            <button type="button" class="btn btn-link dropdown-toggle"
                                                data-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i> <!-- FontAwesome ellipsis icon -->
                                            </button>

                                            <!-- Dropdown Menu -->
                                            <ul
                                                class="dropdown-menu dropdown-menu-right @if ($student->is_suspended) bg-white @endif">
                                                <!-- Edit Button -->
                                                <li>
                                                    <button wire:click="editStudent({{ $student->id }})"
                                                        class="dropdown-item @if ($student->is_suspended) disabled @endif"
                                                        wire:loading.attr="disabled">
                                                        <i class="fas fa-edit text-primary"></i> Edit
                                                        <span wire:loading
                                                            wire:target="editStudent({{ $student->id }})"
                                                            class="spinner-border spinner-border-sm text-primary"></span>
                                                    </button>
                                                </li>

                                                <!-- Delete Button -->
                                                <li>
                                                    <button wire:click="deleteRecord({{ $student->id }})"
                                                        class="dropdown-item @if ($student->is_suspended) disabled @endif"
                                                        wire:loading.attr="disabled">
                                                        <i class="fas fa-trash text-danger"></i> Delete
                                                        <span wire:loading
                                                            wire:target="deleteRecord({{ $student->id }})"
                                                            class="spinner-border spinner-border-sm text-danger"></span>
                                                    </button>
                                                </li>

                                                <!-- View Details Button -->
                                                <li>
                                                    <button wire:click="viewStudent({{ $student->id }})"
                                                        class="dropdown-item" wire:loading.attr="disabled">
                                                        <i class="fas fa-eye text-info"></i> Send Mail
                                                        <span wire:loading
                                                            wire:target="viewStudent({{ $student->id }})"
                                                            class="spinner-border spinner-border-sm text-info"></span>
                                                    </button>
                                                </li>

                                                <!-- Suspension Button -->
                                                <li>
                                                    <button wire:click="suspendStudent({{ $student->id }})"
                                                        class="dropdown-item @if ($student->is_suspended) disabled @endif"
                                                        wire:loading.attr="disabled">
                                                        <i class="fas fa-pause text-warning"></i> Suspension
                                                        <span wire:loading
                                                            wire:target="suspendStudent({{ $student->id }})"
                                                            class="spinner-border spinner-border-sm text-warning"></span>
                                                    </button>
                                                </li>

                                                {{-- <!-- Student History Button -->
                                                    <li>
                                                        <button wire:click="favoriteStudent({{ $student->id }})"
                                                            class="dropdown-item"
                                                            wire:loading.attr="disabled">
                                                            <i class="fas fa-star text-success"></i> Student History
                                                            <span wire:loading wire:target="favoriteStudent({{ $student->id }})" class="spinner-border spinner-border-sm text-success"></span>
                                                        </button>
                                                    </li> --}}

                                                <!-- Approve Button -->
                                                <li>
                                                    <button wire:click="approveStudent({{ $student->id }})"
                                                        class="dropdown-item @if ($student->is_suspended) disabled @endif"
                                                        wire:loading.attr="disabled">
                                                        <i class="fas fa-check text-success"></i> Approve
                                                        <span wire:loading
                                                            wire:target="approveStudent({{ $student->id }})"
                                                            class="spinner-border spinner-border-sm text-success"></span>
                                                    </button>
                                                </li>

                                                <!-- Expulsion Button -->
                                                <li>
                                                    <button wire:click="studentExpulsion({{ $student->id }})"
                                                        class="dropdown-item @if ($student->is_expelled) disabled @endif"
                                                        wire:loading.attr="disabled">
                                                        <i class="fas fa-exclamation-triangle text-danger"></i>
                                                        Expulsion
                                                        <span wire:loading
                                                            wire:target="studentExpulsion({{ $student->id }})"
                                                            class="spinner-border spinner-border-sm text-danger"></span>
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
                <div class="mt-4">
                    {{ $students->links() }}
                </div>
            </div>
    @endif
</div>
