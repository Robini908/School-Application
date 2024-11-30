<div class="card shadow-lg border rounded" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">

    <div class="card-body">
        
        <!-- Toggle Between Forms and List -->
        @if ($isCreating || $isEditing || $showGradingSystemForm || $showGradingSystemDetails)
        <!-- Show Form -->

        <div class="card-header">
            {{ $examId ? 'Edit Exam' : 'Add Exam' }}
        </div>
        <div x-data="{ showInstructions: true }" class="card-body">
            <div>
                <!-- Informative Instruction -->
                @if ($showGradingSystemForm)
                <div x-show="showInstructions" class="alert alert-info mb-3" x-transition>
                    <strong>Click the "Add Grading System" button below:</strong><br><br>
                    After adding the grading system, ensure to add the grading ranges on the second tab "Manage Grading
                    Ranges".<br><br>
                    On that tab, select the grading system you've created, then select the subject.<br><br>
                    <strong>You can click the close button at the bottom when you're done.</strong><br><br>
                    You can also browse existing grading systems below to confirm the existing grading ranges and other
                    parameters.

                    <div class="mt-3">
                        <button type="button" class="btn btn-danger" @click="showInstructions = false">
                            Understood
                        </button>
                    </div>
                </div>


                <div x-show="!showInstructions" class="mt-3">
                    <div class="alert alert-info mt-2">
                        <strong>View the Instructions:</strong> To manage grading ranges effectively, ensure that the
                        grading system is set up correctly...
                        <button type="button" class="btn btn-link" @click="showInstructions = true">Read More</button>
                    </div>
                </div>

                <ul class="nav nav-tabs nav-tabs-highlight p-3" style="margin-bottom: 1rem;">
                    <li class="nav-item">
                        <a href="#grading-management" class="nav-link active" data-toggle="tab">Manage Grading
                            Systems</a>
                    </li>
                    <li class="nav-item">
                        <a href="#grading-range" class="nav-link" data-toggle="tab">Manage Grading Ranges</a>
                    </li>
                </ul>

                <div class="tab-content">
                    <!-- Grading Management Tab -->
                    <div class="tab-pane fade show active" id="grading-management">
                        @livewire('grading-management')
                    </div>
                    <div class="tab-pane fade p-1" id="grading-range">
                        <livewire:grading-range-manager />
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-3">
            <button type="button" wire:click="toggleGradingSystemForm" class="btn btn-secondary">
                <span>&larr; Done? Back to Exam creation</span>
            </button>
        </div>


        @elseif ($showGradingSystemDetails)

        <div class="card-header d-flex justify-content-between align-items-center">
            <button type="button" wire:click="closeGradingSystemDetails" class="btn btn-secondary">
                Close
            </button>
        </div>
        <div class="card">
            <div class="card-body">
                @if ($gradingSystemDetails)
                <p><strong>Name:</strong> <span class="text-primary">{{ $gradingSystemDetails->name }}</span></p>
                @else
                <p class="text-muted">No grading system details available.</p>
                @endif
                <hr>

                <!-- Subject Selection -->
                <div class="form-group">
                    <label for="subject" class="font-weight-bold">Select Subject</label>
                    <select id="subject" wire:model.live="selectedSubjectId" class="form-control"
                        wire:change="viewGradingSystemDetails">
                        <option value="">Select Subject</option>
                        @foreach ($mySubjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                        @endforeach
                    </select>
                </div>

                <h6 class="font-weight-bold mt-4">Grading Ranges for:
                    <span class="text-primary">{{ $selectedSubjectId ?
                        $mySubjects->find($selectedSubjectId)->subject_name : 'Please select a subject' }}</span>
                </h6>

                @if ($gradingRangesBySubject->count() > 0)
                <table class="table table-sm table-bordered mt-2">
                    <thead class="thead-light">
                        <tr>
                            <th>From</th>
                            <th>To</th>
                            <th>Grade</th>
                            <th>Remark</th>
                            <th>GPA</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($gradingRangesBySubject as $range)
                        <tr>
                            <td>{{ $range->range_from }}</td>
                            <td>{{ $range->range_to }}</td>
                            <td>{{ $range->grade }}</td>
                            <td>{{ $range->remark }}</td>
                            <td>{{ $range->gpa }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="text-muted">No grading ranges available for this subject.</p>
                @endif
            </div>
        </div>

            @else

            <!-- Exam Form -->
            <div class="card  p-3 shadow-lg border rounded" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
                <div class="card-body">
                    <form wire:submit="store" class="mt-4">
                        <!-- Exam Name -->
                        <div class="mb-4">
                            <label for="name" class="form-label">Exam Name</label>
                            <input type="text" id="name" wire:model.live="name" class="form-control form-control-lg"
                                placeholder="Exam Name" />
                            @error('name')
                            <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Term, Year, and Class Selection -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label for="term" class="form-label">Term</label>
                                <select id="term" wire:model.live="term" class="form-control form-select-lg">
                                    <option value="">Select Term</option>
                                    @foreach ($terms as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('term')
                                <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="year" class="form-label">Year</label>
                                <select id="year" wire:model.live="year" class="form-control form-select-lg">
                                    <option value="">Select Year</option>
                                    @foreach (range(2000, date('Y')) as $yearOption)
                                    <option value="{{ $yearOption }}">{{ $yearOption }}</option>
                                    @endforeach
                                </select>
                                @error('year')
                                <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="class" class="form-label">Class</label>
                                <select id="class" wire:model.live="selectedClass" class="form-control form-select-lg">
                                    <option value="">Select Class</option>
                                    @foreach ($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                                @error('selectedClass')
                                <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <!-- Stream Selection -->
                        <div class="mb-4">
                            @if ($selectedClass && $sections->count() > 0)
                            <label class="form-label">Select Stream</label>
                            <div class="d-flex align-items-center mb-2">
                                <input type="checkbox" id="selectAllSections" wire:model.live="selectAllSections"
                                    wire:click="toggleSelectAllSections">
                                <label for="selectAllSections" class="ms-2">Select All Streams</label>
                            </div>
                            <div class="row">
                                @foreach ($sections as $section)
                                <div class="col-6 col-md-4 col-lg-3 mb-2">
                                    <div class="form-check">
                                        <input type="checkbox" id="section{{ $section->id }}" value="{{ $section->id }}"
                                            wire:model.live="selectedSections" class="form-check-input">
                                        <label class="form-check-label" for="section{{ $section->id }}">{{
                                            $section->name
                                            }}</label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @error('selectedSections')
                            <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                            @endif
                        </div>

                        <!-- Grading System Selection -->
                        <div class="mb-4">
                            <label for="gradingSystem" class="form-label">Grading System</label>
                            @if ($gradingSystems->count() > 0)
                            <select id="gradingSystem" wire:model.live="grading_system_id"
                                class="form-control form-select-lg">
                                <option value="">Select Grading System</option>
                                @foreach ($gradingSystems as $gradingSystem)
                                <option value="{{ $gradingSystem->id }}">{{ $gradingSystem->name }}</option>
                                @endforeach
                            </select>
                            @error('grading_system_id')
                            <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                            <div class="mt-2">
                                @if ($grading_system_id)
                                <a href="#" wire:click="viewGradingSystemDetails"
                                    class="text-info d-flex align-items-center">
                                    <i class="fas fa-chevron-right me-2"></i>
                                    <span>View Details for {{ $gradingSystems->find($grading_system_id)->name }}</span>
                                </a>
                                @endif
                            </div>

                            @else
                            <div class="form-text text-muted mt-2">
                                There are no grading systems available!
                            </div>
                            @endif
                            <div class="form-text text-muted mt-2">
                                Not seeing the grading system you want? You can always add it.
                                <button type="button" wire:click="toggleGradingSystemForm"
                                    class="btn btn-info btn-sm text-decoration-none ms-2">
                                    Add Grading System
                                </button>
                            </div>
                        </div>




                        <!-- Submit and Cancel Buttons -->
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary me-2">
                                {{ $examId ? 'Update Exam' : 'Add Exam' }}
                            </button>
                            <button type="button" wire:click="resetForm" class="btn btn-secondary">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>





            @endif

        </div>
    </div>
    @else
    <!-- Show List -->
    <div class="row d-flex">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Exam List</h5>
        </div>
    
        <div class="justify-items-end">
            <button wire:click="create" class="btn btn-primary">Add Exam</button>
        </div>
    </div>
    
    <div class="mb-1" style="background-color: #f8f9fa;">
        <div class="form-row mt-4 mx-2">
            <!-- Filter by Name -->
            <div class="col-md-3 mb-3">
                <label for="filterName" class="font-weight-bold">Filter by Name</label>
                <input type="text" id="filterName" wire:model.live.debounce.500ms="filterName" class="form-control" placeholder="Search by name" />
                <div wire:loading wire:target="filterName" class="text-center my-1" style="background-color: #e9ecef; padding: 5px; border-radius: 5px;">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    <span>Loading...</span>
                </div>
            </div>
    
            <!-- Filter by Year -->
            <div class="col-md-3 mb-3">
                <label for="filterYear" class="font-weight-bold">Filter by Year</label>
                <select id="filterYear" wire:model.live="filterYear" class="form-control select2">
                    <option value="">Select Year</option>
                    @foreach (range(2000, date('Y')) as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
                <div wire:loading wire:target="filterYear" class="text-center my-1" style="background-color: #e9ecef; padding: 5px; border-radius: 5px;">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    <span>Loading...</span>
                </div>
            </div>
    
            <!-- Filter by Term -->
            <div class="col-md-3 mb-3">
                <label for="filterTerm" class="font-weight-bold">Filter by Term</label>
                <select id="filterTerm" wire:model.live="filterTerm" class="form-control select2">
                    <option value="">Select Term</option>
                    @foreach ($terms as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
                <div wire:loading wire:target="filterTerm" class="text-center my-1" style="background-color: #e9ecef; padding: 5px; border-radius: 5px;">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    <span>Loading...</span>
                </div>
            </div>
    
            <!-- Filter by Grading System -->
            <div class="col-md-3 mb-3">
                <label for="filterGradingSystem" class="font-weight-bold">Filter by Grading System</label>
                <select id="filterGradingSystem" wire:model.live="filterGradingSystem" class="form-control select2">
                    <option value="">Select Grading System</option>
                    @foreach ($gradingSystems as $gradingSystem)
                        <option value="{{ $gradingSystem->id }}">{{ $gradingSystem->name }}</option>
                    @endforeach
                </select>
                <div wire:loading wire:target="filterGradingSystem" class="text-center my-1" style="background-color: #e9ecef; padding: 5px; border-radius: 5px;">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    <span>Loading...</span>
                </div>
            </div>
        </div>
    
        <!-- Display applied filters with reset options -->
        <div class="mt-3">
            <div class="d-flex flex-wrap">
                <!-- Individual filter badges -->
                @if($filterName)
                    <span class="badge badge-info m-1">
                        Name: {{ $filterName }} 
                        <button wire:click="resetFilter('filterName')" class="btn-close" aria-label="Close"></button>
                    </span>
                @endif
                @if($filterYear)
                    <span class="badge badge-info m-1">
                        Year: {{ $filterYear }} 
                        <button wire:click="resetFilter('filterYear')" class="btn-close" aria-label="Close"></button>
                    </span>
                @endif
                @if($filterTerm)
                    <span class="badge badge-info m-1">
                        Term: {{ $terms[$filterTerm] }} 
                        <button wire:click="resetFilter('filterTerm')" class="btn-close" aria-label="Close"></button>
                    </span>
                @endif
                @if($filterGradingSystem)
                    <span class="badge badge-info m-1">
                        Grading System: {{ $gradingSystems->find($filterGradingSystem)->name ?? '' }} 
                        <button wire:click="resetFilter('filterGradingSystem')" class="btn-close" aria-label="Close"></button>
                    </span>
                @endif
                @if($filterName || $filterYear || $filterTerm || $filterGradingSystem)
                    <button wire:click="resetAllFilters" class="btn btn-secondary btn-sm m-1">Clear All Filters</button>
                @endif
            </div>
        </div>
    </div>
    
    <div class="card-body" style="background-color: #f8f9fa;">
        <div class="table-responsive">
            <x-data-table id="examsTable" title="Registered Examinations" message="List of registered Examinations in this school" :columns="['Name','Term','Year','Grading System','Classes','Streams','Actions']">
                @if($exams->count() === 0)
                    <tr><td colspan="7" class="text-center" style="background-color: #e9ecef;">No exams found.</td></tr>
                @else
                    @foreach($exams as $exam)
                        <tr>
                            <td>{{ $exam->name }}</td>
                            <td>{{ $terms[$exam->term] ?? 'N/A' }}</td>
                            <td>{{ $exam->year }}</td>
                            <td>{{ $exam->gradingSystem->name ?? 'N/A' }}</td>
                            <td>{{ $exam->classes->isNotEmpty() ? $exam->classes->pluck('name')->implode(', ') : 'N/A' }}</td>
                            <td>{{ $exam->classes->flatMap->sections->pluck('name')->implode(', ') ?? 'N/A' }}</td>
                            <td>
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="#" class="list-icons-item" data-toggle="dropdown"><i class="icon-menu9"></i></a>
                                        <div class="dropdown-menu dropdown-menu-left">
                                            <button wire:loading.attr="disabled" wire:loading.class="opacity-50" wire:target="edit({{ $exam->id }})" wire:click="edit({{ $exam->id }})" class="dropdown-item btn btn-warning btn-sm">
                                                <span wire:loading wire:target="edit({{ $exam->id }})">
                                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...
                                                </span>
                                                <span wire:loading.remove> <i class="icon-pencil"></i> Edit</span>
                                            </button>
                                            <button wire:loading.attr="disabled" wire:loading.class="opacity-50" wire:target="confirmDelete({{ $exam->id }})" wire:click="confirmDelete({{ $exam->id }})" class="dropdown-item btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteExamModal">
                                                <span wire:loading wire:target="confirmDelete({{ $exam->id }})">
                                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...
                                                </span>
                                                <span wire:loading.remove><i class="icon-trash"></i> Delete</span>
                                            </button>
                                            <button wire:loading.attr="disabled" wire:loading.class="opacity-50" wire:target="showDetails({{ $exam->id }})" wire:click="showDetails({{ $exam->id }})" class="dropdown-item btn btn-info btn-sm">
                                                <span wire:loading wire:target="showDetails({{ $exam->id }})">
                                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...
                                                </span>
                                                <span wire:loading.remove><i class="icon-info3"></i> Details</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </x-data-table>
    
            
    
            <!-- Pagination Links -->
            <div class="mt-4">{{ $exams->links() }}</div>
        </div>
    </div>

    
    
    @endif



    @if(!empty($examDetails) && isset($examDetails['id']) && !$showAssignMarksForm)
    <div class="modal fade show" style="display: block;" center-modal="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Exam Details</h5>
                    <button type="button" class="close" wire:click="$set('examDetails', [])">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>Name:</strong> {{ $examDetails['name'] }}</p>
                    <p><strong>Term:</strong> {{ $examDetails['term'] }}</p>
                    <p><strong>Year:</strong> {{ $examDetails['year'] }}</p>
                    <p><strong>Grading System:</strong> {{ $examDetails['grading_system'] }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="$set('examDetails', [])">Close</button>
                    @if(isset($examDetails['id']))
                    <!-- Button to assign marks -->
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif
    <!-- Delete Confirmation Modal -->
    @if($isConfirmingDeleting)
    <div class="modal fade show" id="deleteExamModal" tabindex="-1" role="dialog" aria-labelledby="deleteExamModalLabel"
        aria-hidden="true" style="display: block;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteExamModalLabel">Confirm Deletion</h5>
                    <button type="button" class="close" wire:click="cancelDelete" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this exam?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="cancelDelete">Cancel</button>
                    <button type="button" wire:click="deleteExam" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif

    <!-- Your existing delete button -->

</div>
</div>