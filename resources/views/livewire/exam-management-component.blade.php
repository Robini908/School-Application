<div class="container mt-4">
    <!-- Flash Message -->
    @if (session()->has('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
    @endif

    <!-- Toggle Between Forms and List -->
    @if ($isCreating || $isEditing)
    <!-- Show Form -->
    <div class="card mb-4">
        <div class="card-header">
            {{ $examId ? 'Edit Exam' : 'Add Exam' }}
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-info border-0 alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>

                    <span>You are creating an Exam for the Current Session <strong>{{ Qs::getSetting('current_session')
                            }}</strong></span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="store">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" wire:model="name" class="form-control" placeholder="Enter exam name" />
                    @error('name') <small class="form-text text-danger">{{ $message }}</small> @enderror
                </div>


                <div class="form-group">
                    <label for="term">Term</label>
                    <select id="term" wire:model="term" class="form-control select2">
                        <option value="">Select Term</option>
                        @foreach ($terms as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('term') <small class="form-text text-danger">{{ $message }}</small> @enderror
                </div>


                <div class="form-group">
                    <label for="year">Year</label>
                    <select id="year" wire:model="year" class="form-control select2">
                        <option value="">Select Year</option>
                        @foreach (range(2000, date('Y')) as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                    @error('year') <small class="form-text text-danger">{{ $message }}</small> @enderror
                </div>


                <div class="form-group">
                    <label for="grading_system_id">Grading System</label>
                    <select id="grading_system_id" wire:model="grading_system_id" class="form-control select2">
                        <option value="">Select Grading System</option>
                        @foreach ($gradingSystems as $gradingSystem)
                        <option value="{{ $gradingSystem->id }}">{{ $gradingSystem->name }}</option>
                        @endforeach
                    </select>
                    @error('grading_system_id') <small class="form-text text-danger">{{ $message }}</small> @enderror
                </div>


                <button type="submit" class="btn btn-primary">
                    {{ $examId ? 'Update Exam' : 'Add Exam' }}
                </button>

                <button type="button" wire:click="$set('isCreating', false); $set('isEditing', false);"
                    class="btn btn-secondary ml-2">
                    Cancel
                </button>
                
            </form>
        </div>
    </div>
    @else
    <!-- Show List -->
    <div class="mb-4">
        <!-- Filter Options -->
        <div class="mb-4">
            <div class="form-row">
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

        <!-- Exam List -->
        <div class="card">
            <div class="card-header">
                Exam List
                <button wire:click="create" class="btn btn-primary float-right">Add Exam</button>
            </div>
            <div class="card-body">
                <table class="table table-stripped datatable-button-html5-columns">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Term</th>
                            <th>Year</th>
                            <th>Grading System</th>
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
                            <td class="column-responsive">
                                <button wire:click="edit({{ $exam->id }})" class="btn btn-sm btn-warning">Edit</button>
                                <button wire:click="confirmDelete({{ $exam->id }})"
                                    class="btn btn-sm btn-danger">Delete</button>
                                <button wire:click="showDetails({{ $exam->id }})"
                                    class="btn btn-sm btn-info">Details</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No exams found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination Links -->
                {{-- <div class="mt-4">
                    {{ $exams->links() }}
                </div> --}}
            </div>
        </div>
    </div>
    @endif


    <!-- Existing Exam Details Modal -->
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

    <!-- Conditional Form for Assigning Marks -->
    {{-- @if($showAssignMarksForm)
    <div class="modal fade show" style="display: block;" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Assign Marks for : {{ $examDetails['name'] }}</h5>
                    <button type="button" class="close" wire:click="closeAssignMarksForm">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Form to assign marks -->
                    <form wire:submit.prevent="submitMarks">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Student</th>
                                        @foreach($subjects as $subject)
                                        <th>{{ $subject->subject_name }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $student)
                                    <tr>
                                        <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                                        @foreach($subjects as $subject)
                                        <td>
                                            <input type="number" class="form-control"
                                                wire:model.defer="marks[{{ $student->id }}][{{ $subject->id }}]"
                                                placeholder="Enter marks">
                                        </td>
                                        @endforeach
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-secondary"
                            wire:click="closeAssignMarksForm">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif --}}

    <!-- Delete Confirmation Modal -->
    @if ($confirmingDelete)
    <div class="modal fade show" style="display: block;" aria-modal="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Deletion</h5>
                    <button type="button" class="close" wire:click="cancelDelete">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this exam? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="cancelDelete">Cancel</button>
                    <button type="button" class="btn btn-danger" wire:click="deleteConfirmed">Delete</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>