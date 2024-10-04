<div class="container py-6">
    {{-- Display Flash Message --}}

    <x-flash-messages />

    {{-- Add Grade Button at the Top --}}
    {{-- Add Grade Button --}}
    <div class="mb-4 text-right">
        <button wire:click="toggleNewGradeForm" class="btn btn-primary">
            {{ $showForm ? 'Hide Form' : 'Add New Grade' }}
        </button>
    </div>

    {{-- List Grading Systems in Cards --}}

    {{-- List Grading Systems in Cards --}}
    <div class="row">
        @foreach ($gradingSystems as $gradingSystem)
        <div class="card col-md-12 mb-4 shadow-sm">
            <div class="card-body">
                <h2 class="card-title">
                    {{ $gradingSystem->name }} Grading System
                </h2>
                {{-- Table for displaying grades for this grading system --}}
                <table class="table table-bordered mt-3">
                    <thead class="thead-light">
                        <tr>
                            <th>Grade</th>
                            <th>Remark</th>
                            <th>GPA</th>
                            <th>Description</th>
                            <th>Range From</th> <!-- Updated range column -->
                            <th>Range To</th> <!-- Updated range column -->
                            <th>Additional Info</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($gradingSystem->grades as $index => $grade)
                        <tr>
                            <td>
                                @if ($isEditing[$gradingSystem->id] && $editedGradeIndex === $index && $gradingSystemId
                                === $gradingSystem->id)
                                <input wire:model.defer="grades.{{ $gradingSystem->id }}.{{ $editedGradeIndex }}.grade"
                                    type="text" class="form-control" />
                                @else
                                {{ $grade->grade }}
                                @endif
                            </td>
                            <td>
                                @if ($isEditing[$gradingSystem->id] && $editedGradeIndex === $index && $gradingSystemId
                                === $gradingSystem->id)
                                <input wire:model.defer="grades.{{ $gradingSystem->id }}.{{ $editedGradeIndex }}.remark"
                                    type="text" class="form-control" />
                                @else
                                {{ $grade->remark }}
                                @endif
                            </td>
                            <td>
                                @if ($isEditing[$gradingSystem->id] && $editedGradeIndex === $index && $gradingSystemId
                                === $gradingSystem->id)
                                <input wire:model.defer="grades.{{ $gradingSystem->id }}.{{ $editedGradeIndex }}.gpa"
                                    type="number" step="0.01" class="form-control" />
                                @else
                                {{ $grade->gpa }}
                                @endif
                            </td>
                            <td>
                                @if ($isEditing[$gradingSystem->id] && $editedGradeIndex === $index && $gradingSystemId
                                === $gradingSystem->id)
                                <textarea
                                    wire:model.defer="grades.{{ $gradingSystem->id }}.{{ $editedGradeIndex }}.description"
                                    class="form-control"></textarea>
                                @else
                                {{ $grade->description }}
                                @endif
                            </td>
                            <td>
                                @if ($isEditing[$gradingSystem->id] && $editedGradeIndex === $index && $gradingSystemId
                                === $gradingSystem->id)
                                <input
                                    wire:model.defer="grades.{{ $gradingSystem->id }}.{{ $editedGradeIndex }}.range_from"
                                    type="number" class="form-control" />
                                @else
                                {{ $grade->range_from }}
                                <!-- Display range_from data -->
                                @endif
                            </td>
                            <td>
                                @if ($isEditing[$gradingSystem->id] && $editedGradeIndex === $index && $gradingSystemId
                                === $gradingSystem->id)
                                <input
                                    wire:model.defer="grades.{{ $gradingSystem->id }}.{{ $editedGradeIndex }}.range_to"
                                    type="number" class="form-control" />
                                @else
                                {{ $grade->range_to }}
                                <!-- Display range_to data -->
                                @endif
                            </td>
                            <td>
                                @if ($isEditing[$gradingSystem->id] && $editedGradeIndex === $index && $gradingSystemId
                                === $gradingSystem->id)
                                <textarea
                                    wire:model.defer="grades.{{ $gradingSystem->id }}.{{ $editedGradeIndex }}.additional_info"
                                    class="form-control"></textarea>
                                @else
                                {{ $grade->additional_info }}
                                @endif
                            </td>
                            <td>
                                @if ($isEditing[$gradingSystem->id] && $editedGradeIndex === $index && $gradingSystemId
                                === $gradingSystem->id)
                                <button wire:click="saveEditedGrades({{ $gradingSystem->id }})"
                                    class="btn btn-success btn-sm">Save</button>
                                <button wire:click="cancelEdit({{ $gradingSystem->id }})"
                                    class="btn btn-secondary btn-sm">Cancel</button>
                                @else
                                <button wire:click="editGrade({{ $grade->id }}, {{ $index }}, {{ $gradingSystem->id }})"
                                    class="btn btn-primary btn-sm">Edit</button>
                                <button type="button"
                                    wire:click="confirmDelete({{ $gradingSystem->id }}, {{ $grade->id }})"
                                    class="btn btn-danger btn-sm" data-toggle="modal"
                                    data-target="#deleteConfirmationModal">
                                    Delete
                                </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No grades available for this system.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endforeach
    </div>

    <div>
        {{-- Form to Add New Grades (Visible Only When $showForm is True) --}}
        @if ($showForm)
        <div class="card">
            <div class="card-header">
                <h4>Add New Grades</h4>
            </div>

            <div class="card-body">
                <form wire:submit.prevent="saveGrades">
                    <!-- Loading Indicator -->
                    @if($loading)
                    <div class="text-center mb-3">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <p>Saving grades, please wait...</p>
                    </div>
                    @endif

                    <!-- Grading System Selection -->
                    <div class="form-group">
                        <label for="gradingSystem">Select Grading System</label>
                        <select id="gradingSystem" wire:model="selectedGradingSystem" class="form-control">
                            <option value="">Choose Grading System</option>
                            @foreach ($gradingSystems as $system)
                            <option value="{{ $system->id }}">{{ $system->name }}</option>
                            @endforeach
                        </select>
                        @error('selectedGradingSystem') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <!-- Grades Section -->
                    <div class="mt-4">
                        <h5 class="mb-3">
                            Grades for {{ $selectedGradingSystem ? $gradingSystems->firstWhere('id',
                            $selectedGradingSystem)->name : 'this grading system' }}
                        </h5>
                        <p class="text-muted">You can add one or more grades to this grading system.</p>

                        <!-- Add Grade Input Section -->
                        <div class="form-group mb-3">
                            <label for="newGrade" class="form-label">Grade</label>
                            <input id="newGrade" type="text" wire:model="newGrade" class="form-control"
                                placeholder="Enter Grade"
                                oninput="this.value = this.value.toUpperCase().replace(/\d+/g, '')" />
                            @error('newGrade') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="newRemark" class="form-label">Remark</label>
                            <input id="newRemark" type="text" wire:model="newRemark" class="form-control"
                                placeholder="Enter Remark"
                                oninput="this.value = this.value.toUpperCase().replace(/\d+/g, '')" />
                            @error('newRemark') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="newGpa" class="form-label">GPA</label>
                            <input id="newGpa" type="text" wire:model="newGpa" class="form-control"
                                placeholder="Enter GPA" />
                            @error('newGpa') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="newDescription" class="form-label">Description</label>
                            <input id="newDescription" type="text" wire:model="newDescription" class="form-control"
                                placeholder="Enter Description" />
                            @error('newDescription') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="newAdditionalInfo" class="form-label">Additional Info</label>
                            <input id="newAdditionalInfo" type="text" wire:model="newAdditionalInfo"
                                class="form-control" placeholder="Enter Additional Info" />
                            @error('newAdditionalInfo') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Range Input Section -->
                        <div class="form-group mb-3">
                            <label for="newRangeFrom" class="form-label">Range From</label>
                            <input id="newRangeFrom" type="number" wire:model="newRangeFrom" class="form-control"
                                placeholder="Enter Range From" />
                            @error('newRangeFrom') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="newRangeTo" class="form-label">Range To</label>
                            <input id="newRangeTo" type="number" wire:model="newRangeTo" class="form-control"
                                placeholder="Enter Range To" />
                            @error('newRangeTo') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Add Grade Button -->
                        <div class="form-group">
                            <button type="button" wire:click="addGrade" class="btn btn-primary btn-block">
                                <i class="fa fa-plus"></i> Add Grade
                            </button>
                        </div>

                        <!-- Display message if no grades are added -->
                        @if(empty($addedGrade))
                        <div class="alert alert-info mt-3">No grades added yet. Start by adding a grade for this grading
                            system.</div>
                        @else
                        <!-- Grades List -->
                        <ul class="list-group mt-3">
                            @foreach($addedGrade as $index => $grade)
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-md-2">
                                        <input type="text" wire:model.lazy="addedGrade.{{ $index }}.grade"
                                            class="form-control" placeholder="Grade"
                                            oninput="this.value = this.value.toUpperCase().replace(/\d+/g, '')" />
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" wire:model.lazy="addedGrade.{{ $index }}.remark"
                                            class="form-control" placeholder="Remark"
                                            oninput="this.value = this.value.toUpperCase().replace(/\d+/g, '')" />
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" wire:model.lazy="addedGrade.{{ $index }}.description"
                                            class="form-control" placeholder="Description" />
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" wire:model.lazy="addedGrade.{{ $index }}.additional_info"
                                            class="form-control" placeholder="Additional Info" />
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" wire:model.lazy="addedGrade.{{ $index }}.range_from"
                                            class="form-control" placeholder="Range From" />
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" wire:model.lazy="addedGrade.{{ $index }}.range_to"
                                            class="form-control" placeholder="Range To" />
                                    </div>
                                </div>
                                <div class="mt-2 d-flex justify-content-end">
                                    <button type="button" wire:click="updateGrade({{ $index }})"
                                        class="btn btn-primary btn-sm">
                                        <i class="fa fa-edit"></i> Update...
                                    </button>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                        @endif
                    </div>

                    <!-- Save Button -->
                    <div class="mt-4">
                        <button type="submit" class="btn btn-success" wire:loading.attr="disabled">
                            Save Grades
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>




    <!-- Modal -->
    @if($isConfirmingDeleting)
    <div class="modal fade show" id="deleteGradeModal" tabindex="-1" role="dialog"
        aria-labelledby="deleteGradeModalLabel" aria-hidden="true" style="display: block;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteGradeModalLabel">Confirm Grade Deletion</h5>
                    <button type="button" class="close" wire:click="cancelDelete" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this grade? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="cancelDelete">Cancel</button>
                    <button type="button" wire:click="deleteGrade" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif



</div>