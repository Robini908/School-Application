<div class="tab-pane fade show active container-fluid" id="manage-students">
    <div class="card-body">
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
                    @if($formFilter)
                    <div class="badge bg-primary me-1 d-flex align-items-center">
                        Form: {{ $formFilter }}
                        <button class="btn btn-close btn-close-white ms-1" wire:click="removeFilter('formFilter')"
                            aria-label="Close">
                            <i class="icon-close"></i> <!-- Replace with the appropriate icon class -->
                        </button>
                    </div>
                    @endif
                    @if($sectionFilter)
                    <div class="badge bg-secondary me-1 d-flex align-items-center">
                        Stream: {{ $sectionFilter }}
                        <button class="btn btn-close btn-close-white ms-1" wire:click="removeFilter('sectionFilter')"
                            aria-label="Close">
                            <i class="icon-close"></i> <!-- Replace with the appropriate icon class -->
                        </button>
                    </div>
                    @endif
                    @if($statusFilter)
                    <div class="badge bg-success me-1 d-flex align-items-center">
                        Status: {{ $statusFilter }}
                        <button class="btn btn-close btn-close-white ms-1" wire:click="removeFilter('statusFilter')"
                            aria-label="Close">
                            <i class="icon-close"></i> <!-- Replace with the appropriate icon class -->
                        </button>
                    </div>
                    @endif

                    {{-- Reset Filter Button --}}

                    @if($formFilter || $sectionFilter || $statusFilter)
                    <button class="btn btn-danger ms-2" wire:click="resetFilters">
                        <i class="icon-reset"></i> <!-- Use an appropriate icon class for the reset action -->
                        Reset Filters
                    </button>
                    @endif
                </div>
            </div>

        </div>

        <!-- Conditional Reset Button -->

    </div>

    <!-- Data Table -->
    <table id="studentTable" class="table table-responsive table-hover table-bordered">
        <thead class="table-light">
            <tr>
                <th>Admission</th>
                <th>Student Photo</th>
                <th>Name</th>
                <th>Gender</th>
                <th>Class</th>
                <th>Section</th>
                <th>Status</th>
                <th>Parent Name</th>
                <th>Parent Contact</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
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
                    <img src="{{ asset($student->photo) }}" alt="Student Photo" class="img-thumbnail"
                        style="width: 50px; height: 50px;">
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
                <td>{{ $student->classname }}</td>
                <td>{{ $student->sectionname }}</td>
                <td>
                    <span class="badge bg-{{ $student->status == 'Active' ? 'success' : 'warning' }}">
                        {{ $student->status }}
                    </span>
                </td>
                <td>{{ $student->parent_first_name }} {{ $student->parent_last_name }}</td>
                <td>{{ $student->parent_phone_number }}</td>
                <td class="column-responsive">
                    <div class="list-icons">
                        <div class="dropdown">
                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                <i class="icon-menu9"></i>
                            </a>

                            <div class="dropdown-menu dropdown-menu-left">
                                {{-- Edit Button --}}
                                <button wire:click="editStudent({{ $student->id }})" class="dropdown-item">
                                    <i class="icon-pencil"></i> Edit
                                </button>

                                {{-- Delete Button (with modal trigger) --}}
                                <button wire:click="confirmDelete({{ $student->id }})" class="dropdown-item"
                                    data-toggle="modal" data-target="#deleteStudentModal">
                                    <i class="icon-trash"></i> Delete
                                </button>

                                {{-- View Details Button --}}
                                <button wire:click="viewStudent({{ $student->id }})" class="dropdown-item">
                                    <i class="icon-info3"></i> View Details
                                </button>

                                {{-- Favorite Button --}}
                                <button wire:click="favoriteStudent({{ $student->id }})" class="dropdown-item">
                                    <i class="icon-star"></i> Favorite
                                </button>

                                {{-- Approve Button --}}
                                <button wire:click="approveStudent({{ $student->id }})" class="dropdown-item">
                                    <i class="icon-checkmark"></i> Approve
                                </button>

                                {{-- Reject Button --}}
                                <button wire:click="rejectStudent({{ $student->id }})" class="dropdown-item">
                                    <i class="icon-cross"></i> Reject
                                </button>
                            </div>
                        </div>
                    </div>
                </td>



                </td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
</div>
</div>