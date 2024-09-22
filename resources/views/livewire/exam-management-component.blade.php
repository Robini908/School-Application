<div class="card">
    <style>
        .custom-badge {
            font-size: 0.9rem;
            /* Adjust the font size */
            padding: 0.4rem 0.6rem;
            /* Smaller padding */
            border-radius: 0.5rem;
            /* Adjust border radius if needed */
        }

        .custom-badge input {
            margin-right: 0.5rem;
            /* Space between checkbox and badge text */
            transform: scale(1.2);
            /* Slightly larger checkbox */
        }
    </style>

    <div class="card-body">
        <!-- Flash Message -->
        @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif

        @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
        @endif

        <!-- Error messages for validation -->
        @foreach ($errors->all() as $error)
        <div class="alert alert-danger">{{ $error }}</div>
        @endforeach


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
                    After adding the grading system, ensure to add the grading ranges on the second tab "Manage
                    Grading
                    Ranges".<br><br>
                    On that tab, select the grading system you've created, then select the subject.<br><br>
                    <strong>You can click the close button at the bottom when you're done.</strong><br><br>
                    You can also browse existing grading systems below to confirm the existing grading ranges and
                    other
                    parameters.

                    <div class="mt-3">
                        <button type="button" class="btn btn-danger" @click="showInstructions = false">
                            Understood
                        </button>
                    </div>
                </div>

                <div x-show="!showInstructions" class="mt-3">


                    <div class="alert alert-info mt-2" x-show="!showInstructions" x-transition>
                        <strong>View the Instructions:</strong> To manage grading ranges effectively, ensure that
                        the
                        grading system is set up correctly...
                        <button type="button" class="btn btn-link" @click="showInstructions = true">Read
                            More</button>
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
                <select id="subject" wire:model="selectedSubjectId" class="form-control"
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

        @else

        <!-- Exam Form -->
        <form wire:submit.prevent="store" class="container mt-4">
            <div class="row">
                <!-- Exam Name -->
                <div class="col-12 mb-3">
                    <div class="form-group">
                        <label for="name">Exam Name</label>
                        <input type="text" id="name" wire:model="name" class="form-control"
                            placeholder="Enter exam name" />
                        @error('name')
                        <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Term, Year, Class Selection -->
                <div class="col-12 col-md-4 mb-3">
                    <div class="form-group">
                        <label for="term">Term</label>
                        <select id="term" wire:model="term" class="form-control">
                            <option value="">Select Term</option>
                            @foreach ($terms as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('term')
                        <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="col-12 col-md-4 mb-3">
                    <div class="form-group">
                        <label for="year">Year</label>
                        <select id="year" wire:model="year" class="form-control">
                            <option value="">Select Year</option>
                            @foreach (range(2000, date('Y')) as $yearOption)
                            <option value="{{ $yearOption }}">{{ $yearOption }}</option>
                            @endforeach
                        </select>
                        @error('year')
                        <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="col-12 col-md-4 mb-3">
                    <div class="form-group">
                        <label for="class">Class</label>
                        <select id="class" wire:model="selectedClass" class="form-control">
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
            </div>

            <div class="row">
                <!-- Sections Selection -->
                <div class="col-12 mb-3">
                    @if ($selectedClass && $sections->count() > 0)
                    <div class="form-group">
                        <label>Select Sections</label>
                        <div class="d-flex align-items-center mb-2">
                            <input type="checkbox" id="selectAllSections" wire:model="selectAllSections"
                                wire:click="toggleSelectAllSections">
                            <label for="selectAllSections" class="ml-2">Select All Sections</label>
                        </div>
                        <div class="row">
                            @foreach ($sections as $section)
                            <div class="col-6 col-md-4 col-lg-3 mb-2">
                                <span class="badge custom-badge d-flex align-items-center">
                                    <input type="checkbox" id="section{{ $section->id }}" value="{{ $section->id }}"
                                        wire:model="selectedSections" class="form-check-input mr-2">
                                    {{ $section->name }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                        @error('selectedSections')
                        <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    @endif
                </div>

            </div>

            <div class="row">
                <!-- Grading System Selection -->
                <div class="col-12 col-md-6 mb-3">
                    @if ($gradingSystems->count() > 0)
                    <div class="form-group">
                        <label for="gradingSystem">Select Grading System</label>
                        <select id="gradingSystem" wire:model="grading_system_id" class="form-control">
                            <option value="">Select Grading System</option>
                            @foreach ($gradingSystems as $gradingSystem)
                            <option value="{{ $gradingSystem->id }}">{{ $gradingSystem->name }}</option>
                            @endforeach
                        </select>
                        @error('grading_system_id')
                        <small class="form-text text-danger">{{ $message }}</small>
                        @enderror

                        <div class="mt-2">
                            <div class="d-flex justify-content-between">
                                @if ($grading_system_id)
                                <button type="button" wire:click="viewGradingSystemDetails" class="btn btn-info btn-sm">
                                    View Details for {{ $gradingSystems->find($grading_system_id)->name }}
                                </button>
                                @endif
                                <button type="button" wire:click="toggleGradingSystemForm"
                                    class="btn btn-primary btn-sm">Add Grading System</button>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary mt-3">
                        {{ $examId ? 'Update Exam' : 'Add Exam' }}
                    </button>
                    <button type="button" wire:click="resetForm" class="btn btn-secondary ml-2 mt-3">Cancel</button>
                </div>
            </div>
        </form>




        @endif

    </div>
</div>
@else
<!-- Show List -->
<div class="card">

    <div class="mb-4">
        <div class="form-row mt-4 mx-2">
            <div class="col-md-3 mb-2">
                <label for="filterName">Filter by Name</label>
                <input type="text" id="filterName" wire:model.live="filterName" class="form-control"
                    placeholder="Search by name" />
            </div>
            <div class="col-md-3 mb-2">
                <label for="filterYear">Filter by Year</label>
                <select id="filterYear" wire:model="filterYear" class="form-control select2">
                    <option value="">Select Year</option>
                    @foreach (range(2000, date('Y')) as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <label for="filterTerm">Filter by Term</label>
                <select id="filterTerm" wire:model="filterTerm" class="form-control select2">
                    <option value="">Select Term</option>
                    @foreach ($terms as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <label for="filterGradingSystem">Filter by Grading System</label>
                <select id="filterGradingSystem" wire:model="filterGradingSystem" class="form-control select2">
                    <option value="">Select Grading System</option>
                    @foreach ($gradingSystems as $gradingSystem)
                    <option value="{{ $gradingSystem->id }}">{{ $gradingSystem->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <!-- Display applied filters with "x" icon for individual reset -->
        <div class="mt-3">
            @if($filterName)
            <span class="badge badge-info">
                Name: {{ $filterName }}
                <button wire:click="resetFilter('filterName')" class="btn btn-sm btn-light">x</button>
            </span>
            @endif
            @if($filterYear)
            <span class="badge badge-info">
                Year: {{ $filterYear }}
                <button wire:click="resetFilter('filterYear')" class="btn btn-sm btn-light">x</button>
            </span>
            @endif
            @if($filterTerm)
            <span class="badge badge-info">
                Term: {{ $terms[$filterTerm] }}
                <button wire:click="resetFilter('filterTerm')" class="btn btn-sm btn-light">x</button>
            </span>
            @endif
            @if($filterGradingSystem)
            <span class="badge badge-info">
                Grading System: {{ $gradingSystems->find($filterGradingSystem)->name ?? '' }}
                <button wire:click="resetFilter('filterGradingSystem')" class="btn btn-sm btn-light">x</button>
            </span>
            @endif
            @if($filterName || $filterYear || $filterTerm || $filterGradingSystem)
            <button wire:click="resetAllFilters" class="btn btn-secondary mt-2">Clear All Filters</button>
            @endif
        </div>
    </div>
    <div class="card-header">
        Exam List
        <button wire:click="create" class="btn btn-primary float-right">Add Exam</button>
    </div>
    <div class="card-body">
        <table class="table table-striped datatable-button-html5-columns">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Term</th>
                    <th>Year</th>
                    <th>Grading System</th>
                    <th>Classes</th>
                    <th>Sections</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($exams as $exam)
                <tr>
                    <td>{{ $exam->name }}</td>
                    <td>{{ $terms[$exam->term] ?? 'N/A' }}</td>
                    <td>{{ $exam->year }}</td>
                    <td>{{ $exam->gradingSystem->name ?? 'N/A' }}</td>
                    <td>
                        @if ($exam->classes && $exam->classes->isNotEmpty())
                        {{ $exam->classes->pluck('name')->implode(', ') }}
                        @else
                        N/A
                        @endif
                    </td>
                    <td>
                        @if ($exam->classes && $exam->classes->isNotEmpty())
                        @foreach ($exam->classes as $class)
                        @if ($class->sections && $class->sections->isNotEmpty())
                        {{ $class->sections->pluck('name')->implode(', ') }}@if (!$loop->last), @endif
                        @else
                        N/A
                        @endif
                        @endforeach
                        @else
                        N/A
                        @endif
                    </td>
                    <td class="column-responsive">
                        <button wire:click="edit({{ $exam->id }})" class="btn btn-warning btn-sm">Edit</button>
                        <button wire:click="confirmDelete({{ $exam->id }})" class="btn btn-danger btn-sm"
                            data-toggle="modal" data-target="#deleteExamModal">Delete</button>
                        <button wire:click="showDetails({{ $exam->id }})" class="btn btn-info btn-sm">Details</button>


                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">No exams found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
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
                <a href="{{ route('exams.assignExamMarks') }}" class="btn btn-info">Assign Marks</a>


                @endif
            </div>
        </div>
    </div>
</div>
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
@endif

<!-- Your existing delete button -->

</div>
</div>