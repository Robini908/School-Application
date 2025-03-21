<div>
    @if ($showGraduatedStudents)
        <div class="alert alert-info mt-2 mb-2">
            Students are automatically graduated when they meet the required conditions.
        </div>
    @endif
    <div class="p-2 d-flex justify-content-between align-items-center">
        <h4 class="card-title">Graduate Students</h4>
        <div class="col-md-3">
            <button wire:click="toggleView" class="btn btn-link">
                {{ $showGraduatedStudents ? 'Manually graduate students' : 'View Graduated Students' }}
            </button>
        </div>
    </div>
    <div class="card-body">
        <!-- Display Bootstrap Alerts for Informative Messages -->
        @if ($infoMessage)
            <div class="alert alert-info">
                {{ $infoMessage }}
            </div>
        @endif

        <!-- Toggle Button and Filters -->
        <div class="row mb-3">
            <!-- Search Input -->
            <div class="col-md-3">
                <input type="text" wire:model.live.debounce.300ms="search" class="form-control"
                    placeholder="Search students...">
            </div>

            <!-- Graduation Year Filter (Only for Graduated Students) -->
            @if ($showGraduatedStudents)
                <div class="col-md-3">
                    <select wire:model.live="filterGraduationYear" class="form-select form-control">
                        <option value="">Filter by Graduation Year</option>
                        @foreach ($years as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <!-- Class Selection Dropdown (Only for Eligible Students) -->
            @if (!$showGraduatedStudents)
                <div class="col-md-3">
                    <select wire:model.live="selectedClass" class="form-select form-control">
                        <option value="">Select Class</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>

        <!-- Graduated Students Table -->
        @if ($showGraduatedStudents)
            <div class="mt-3 mb-3">
                <button wire:click="exportGraduatedStudents" class="btn btn-primary">
                    <i class="fas fa-file-export"></i> PDF({{ $filterGraduationYear }})
                </button>
                <button wire:click="printBulkCertificates" class="btn btn-success ml-2" disabled>
                    <i class="fas fa-print"></i> Print Bulk Certificates
                </button>
            </div>
            
            <div>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" wire:model="selectAll" wire:click="toggleSelectAll" disabled>
                            </th>
                            <th>Name (Admission No)</th>
                            <th>Graduation Year</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($students as $student)
                            <tr>
                                <td>
                                    <input type="checkbox" wire:model="selectedStudents" value="{{ $student->student->id }}" disabled>
                                </td>
                                <td>{{ $student->student->first_name }} {{ $student->student->last_name }} ({{ $student->student->adm_no }})</td>
                                <td>{{ $student->transition_year }}</td>
                                <td class="text-center">
                                    <div class="list-icons">
                                        <div class="dropdown">
                                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                <i class="icon-menu9"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-left">
                                                <button wire:click="reinstateStudent({{ $student->id }})" class="dropdown-item">
                                                    <i class="fas fa-undo"></i> Reinstate
                                                </button>
                                                <button wire:click="viewGraduationDetails({{ $student->id }})" class="dropdown-item">
                                                    <i class="fas fa-eye"></i> View Details
                                                </button>
                                                <button wire:click="confirmDeleteGraduation({{ $student->id }})" class="dropdown-item">
                                                    <i class="fas fa-trash"></i> Delete Record
                                                </button>
                                                <button wire:click="printCertificate({{ $student->student->id }})" class="dropdown-item">
                                                    <i class="fas fa-print"></i> Print Certificate
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No graduated students found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @else
            <!-- Eligible Students Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Select</th>
                            <th>Name (Admission)</th>
                            <th>Graduation Progress</th>
                            <th>Time Remaining</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($students as $student)
                            @php
                                $progress = $this->calculateGraduationProgress($student);
                            @endphp
                            <tr>
                                <td>
                                    <input type="checkbox" wire:model.live="selectedStudents"
                                        value="{{ $student->id }}">
                                </td>
                                <td>
                                    {{ $student->first_name }} {{ $student->last_name }} <br>
                                    <small class="text-muted">{{ $student->adm_no }}</small>
                                </td>
                                <td>
                                    <div class="progress" style="height: 25px;">
                                        <div class="progress-bar bg-success" role="progressbar"
                                            style="width: {{ $progress['percentage'] }};">
                                            {{ $progress['percentage'] }}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    {{ $progress['remaining'] }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No students found for the selected class.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Pagination -->
        <div class="row mt-3">
            <div class="col-md-12">
                {{ $students->links() }}
            </div>
        </div>

        <!-- Graduation Button (Only for Eligible Students) -->
        @if (!$showGraduatedStudents && !empty($selectedStudents))
            <div class="row mt-3">

                <button wire:click="graduateStudents" class="btn btn-success btn-sm">
                    Graduate Selected Students
                </button>

            </div>
        @endif

       
        @if ($showReinstateModal)
    <div class="modal fade show" tabindex="-1" role="dialog" style="display: block; background: rgba(0, 0, 0, 0.5);">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">Confirm Reinstatement of {{ $studentName }}</h5>
                    <button type="button" class="close text-white" wire:click="cancelReinstate">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to reinstate this student?</p>
                    <p>This will remove their graduation record and set their status back to active.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="cancelReinstate">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-danger" wire:click="confirmReinstate">
                        Confirm Reinstatement
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
        <!-- Graduation Details Modal -->
        @if ($showGraduationDetailsModal && $selectedGraduationDetails)
            <div class="modal fade show" tabindex="-1" role="dialog"
                style="display: block; background: rgba(0, 0, 0, 0.5);">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">Graduation Details</h5>
                            <button type="button" class="close text-white" wire:click="closeGraduationDetailsModal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-primary">Student Details</h6>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">
                                            <strong>Name:</strong>
                                            {{ $selectedGraduationDetails->student->first_name }}
                                            {{ $selectedGraduationDetails->student->last_name }}
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Admission No:</strong>
                                            {{ $selectedGraduationDetails->student->adm_no }}
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Class:</strong>
                                            {{ $selectedGraduationDetails->student->my_class->name }}
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Stream:</strong>
                                            {{ $selectedGraduationDetails->student->section->name }}
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Year Admitted:</strong>
                                            {{ Carbon\Carbon::parse($selectedGraduationDetails->student->created_at)->year }}
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-primary">Graduation Details</h6>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">
                                            <strong>Graduation Year:</strong>
                                            {{ $selectedGraduationDetails->transition_year }}
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Reason:</strong> {{ $selectedGraduationDetails->reason ?? 'N/A' }}
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Decision By:</strong> {{ $selectedGraduationDetails->decision_by }}
                                        </li>
                                        <li class="list-group-item">
                                            <strong>Decision Date:</strong>
                                            {{ $selectedGraduationDetails->decision_date }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                wire:click="closeGraduationDetailsModal">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <!-- Delete Confirmation Modal -->
        @if ($showDeleteConfirmationModal)
            <div class="modal fade show" tabindex="-1" role="dialog"
                style="display: block; background: rgba(0, 0, 0, 0.5);">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">Confirm Deletion</h5>
                            <button type="button" class="close text-white" wire:click="cancelDelete">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to delete this graduation record?</p>
                            <p>This action cannot be undone.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="cancelDelete">
                                Cancel
                            </button>
                            <button type="button" class="btn btn-danger" wire:click="deleteGraduationRecord">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

</div>
