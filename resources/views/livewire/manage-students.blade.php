<div class="tab-pane fade show active container-fluid" id="manage-students">
    <div class="card-body">
        <x-flash-messages />
        <div class="card container-fluid m-2 px-4 pt-0.5" style="width:98%;">
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
                        <button class="btn btn-close btn-close-white ms-1" wire:click="removeFilter('formFilter')" aria-label="Close">
                            <i class="icon-close"></i>
                        </button>
                        <!-- Spinner for Form Filter -->
                        <div wire:loading wire:target="removeFilter('formFilter')" class="spinner-border spinner-border-sm text-light ms-2" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    @endif
            
                    {{-- Section Filter --}}
                    @if($sectionFilter)
                    <div class="badge bg-secondary me-1 d-flex align-items-center">
                        Stream: {{ $sectionFilter }}
                        <button class="btn btn-close btn-close-white ms-1" wire:click="removeFilter('sectionFilter')" aria-label="Close">
                            <i class="icon-close"></i>
                        </button>
                        <!-- Spinner for Section Filter -->
                        <div wire:loading wire:target="removeFilter('sectionFilter')" class="spinner-border spinner-border-sm text-light ms-2" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    @endif
            
                    {{-- Status Filter --}}
                    @if($statusFilter)
                    <div class="badge bg-success me-1 d-flex align-items-center">
                        Status: {{ $statusFilter }}
                        <button class="btn btn-close btn-close-white ms-1" wire:click="removeFilter('statusFilter')" aria-label="Close">
                            <i class="icon-close"></i>
                        </button>
                        <!-- Spinner for Status Filter -->
                        <div wire:loading wire:target="removeFilter('statusFilter')" class="spinner-border spinner-border-sm text-light ms-2" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    @endif
            
                    {{-- Reset Filter Button --}}
                    @if($formFilter || $sectionFilter || $statusFilter)
                    <button class="btn btn-danger ms-2" wire:click="resetFilters">
                        <i class="icon-reset"></i> Reset Filters
                        <!-- Spinner for Reset Filters -->
                        <div wire:loading wire:target="resetFilters" class="spinner-border spinner-border-sm text-light ms-2" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </button>
                    @endif
                </div>
            </div>
            
        </div>

        <!-- Conditional Reset Button -->

    </div>

    <!-- Data Table -->
    <div class="table-responsive">
        <x-data-table id="studentTable" title="Student List" message="List of registered students" 
            :columns="['Admission', 'Student Photo', 'Name', 'Gender', 'Class', 'Section', 'Status', 'Parent Name', 'Parent Contact', 'Actions']">
            @if($noResults)
            <tr>
                <td colspan="10" class="text-center">No students found for the selected filters.</td>
            </tr>
            @else
            @foreach($this->mystudents as $student)
            <tr>
                <td>{{ $student->adm_no }}</td>
                <td>
                    @if ($student->photo)
                    <img src="{{ asset($student->photo) }}" alt="Student Photo" class="img-thumbnail" style="width: 50px; height: 50px;">
                    @else
                    <span class="text-muted">No Image</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('student.info', ['id' => $student->id]) }}">
                        {{ $student->first_name }} {{ $student->last_name }}
                    </a>
                </td>
                <td>{{ $student->gender }}</td>
                <td>{{ $student->my_class->name ?? 'N/A' }}</td>
                <td>{{ $student->section->name ?? 'N/A' }}</td>
                <td>
                    <span class="badge {{ $student->status == 'Active' ? 'badge-success' : 'badge-warning' }}">
                        {{ $student->status }}
                    </span>
                </td>
                <td>{{ $student->parent_detail->parent_first_name ?? 'N/A' }} {{ $student->parent_detail->parent_last_name ?? 'N/A' }}</td>
                <td>{{ $student->parent_detail->parent_phone_number ?? 'N/A' }}</td>
                <td class="text-center">
                    <div class="list-icons">
                        <div class="dropdown">
                            <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <i class="icon-menu9"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li>
                                    <button wire:click="editStudent({{ $student->id }})" class="dropdown-item">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                </li>
                                <li>
                                    <button wire:click="deleteRecord({{ $student->id }})" class="dropdown-item">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </li>
                                <li>
                                    <button wire:click="viewStudent({{ $student->id }})" class="dropdown-item">
                                        <i class="bi bi-eye"></i> View Details
                                    </button>
                                </li>
                                <li>
                                    <button wire:click="studentExpulsion({{ $student->id }})" class="dropdown-item">
                                        <i class="bi bi-exclamation-triangle"></i> Expulsion
                                    </button>
                                </li>
                                <li>
                                    <button wire:click="suspendStudent({{ $student->id }})" class="dropdown-item">
                                        <i class="bi bi-pause"></i> Suspension
                                    </button>
                                </li>
                                <li>
                                    <button wire:click="favoriteStudent({{ $student->id }})" class="dropdown-item">
                                        <i class="bi bi-star"></i> Student History
                                    </button>
                                </li>
                                <li>
                                    <button wire:click="approveStudent({{ $student->id }})" class="dropdown-item">
                                        <i class="bi bi-check"></i> Approve
                                    </button>
                                </li>
                                <li>
                                    <button wire:click="rejectStudent({{ $student->id }})" class="dropdown-item">
                                        <i class="bi bi-x"></i> Reject
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
    
    

    @if ($showDeleteModal)
    <div class="modal" tabindex="-1" role="dialog" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="close" wire:click="cancelDelete">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this student?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="cancelDelete">Cancel</button>
                    <button type="button" class="btn btn-danger" wire:click="confirmDelete">Delete</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>


</div>