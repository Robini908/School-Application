    <div class="card mt-2 col-12 p-3 shadow-lg border rounded"
        style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
        @if ($errors->has('grade'))
            <div class="alert alert-danger">{{ $errors->first('grade') }}</div>
        @endif

        @if (!$showForm)
            <div class="mb-4">
                <label for="grading_system" class="form-label">Select Grading System:</label>
                <div class="d-flex align-items-center">
                    <select wire:model.live="selectedGradingSystemId" class="form-control"
                        {{ $applyToAllSystems ? 'disabled' : '' }}>
                        @foreach ($gradingSystems as $system)
                            <option value="{{ $system->id }}">{{ $system->name }}</option>
                        @endforeach
                    </select>
                    <div wire:loading wire:target="selectedGradingSystemId"
                        class="spinner-border spinner-border-sm ms-2" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>



            <!-- Add New MeanGrade Button -->
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-info d-flex align-items-center fade show" role="alert"
                        style="animation: fadeIn 1s; border: 2px solid #17a2b8; border-radius: 0.5rem;">
                        <i class="bi bi-info-circle-fill me-2" style="font-size: 1.5rem; color: #17a2b8;"></i>
                        <div>
                            <h5 class="alert-heading fw-bold" style="color: #0c5460;">Important Notice!</h5>
                            <p class="mb-0" style="font-weight: 500;">These grades will be used in the calculation of
                                the mean grade during exam analysis.</p>
                        </div>
                    </div>

                    <style>
                        @keyframes fadeIn {
                            from {
                                opacity: 0;
                                transform: translateY(-10px);
                            }

                            to {
                                opacity: 1;
                                transform: translateY(0);
                            }
                        }
                    </style>


                    <script>
                        $(document).ready(function() {
                            // Optional: Add a timeout to automatically fade out the alert after 5 seconds
                            setTimeout(function() {
                                $(".alert").fadeOut("slow");
                            }, 5000); // 5000 milliseconds = 5 seconds
                        });
                    </script>


                    <div class="mb-4">
                        <button wire:click="showAddForm" class="btn btn-success" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="showAddForm">Add New MeanGrade</span>
                            <span wire:loading wire:target="showAddForm"><i class="fas fa-spinner fa-spin"></i></span>
                        </button>
                    </div>



                    <div class="card mb-4 shadow-sm">
                        <div class="card-header">
                            <h5 class="card-title">Grades for Grading System:
                                {{ $applyToAllSystems
                                    ? 'All Grading Systems'
                                    : $gradingSystems->find($selectedGradingSystemId)->name ?? 'None Selected' }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>MeanGrade</th>
                                        <th>Remark</th>
                                        <th>GPA</th>
                                        <th>Range From</th>
                                        <th>Range To</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (collect($gradesList)->isEmpty())
                                        <!-- Message When No Categories -->
                                        <div class="alert alert-warning text-center" role="alert">
                                            <i class="fas fa-exclamation-circle fa-2x text-warning mb-2"></i>
                                            <h5 class="mt-2">No Grades found!</h5>
                                            <p>Click the "Add New MeanGrade" button to create your first grade.</p>
                                        </div>
                                    @else
                                        @foreach ($gradesList as $index => $grade)
                                            <tr>
                                                <td>
                                                    @if ($grade['isEditing'])
                                                        <input wire:model="gradesList.{{ $index }}.grade"
                                                            type="text" class="form-control" maxlength="2"
                                                            oninput="this.value = this.value.toUpperCase()"
                                                            pattern="A|A\-|B\+|B|B\-|C\+|C|C\-|D\+|D|D\-|E"
                                                            title="Allowed grades: A, A-, B+, B, B-, C+, C, C-, D+, D, D-, E">
                                                        @error("gradesList.$index.grade")
                                                            <div class="text-danger small">{{ $message }}</div>
                                                        @enderror
                                                    @else
                                                        {{ $grade['grade'] }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($grade['isEditing'])
                                                        <input wire:model="gradesList.{{ $index }}.remark"
                                                            type="text" class="form-control"
                                                            oninput="this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1)">
                                                        @error("gradesList.$index.remark")
                                                            <div class="text-danger small">{{ $message }}</div>
                                                        @enderror
                                                    @else
                                                        {{ $grade['remark'] }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($grade['isEditing'])
                                                        <input wire:model="gradesList.{{ $index }}.gpa"
                                                            type="number" step="0.01" class="form-control"
                                                            max="13">
                                                    @else
                                                        {{ $grade['gpa'] }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($grade['isEditing'])
                                                        <input wire:model="gradesList.{{ $index }}.range_from"
                                                            type="number" class="form-control">
                                                    @else
                                                        {{ $grade['range_from'] }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($grade['isEditing'])
                                                        <input wire:model="gradesList.{{ $index }}.range_to"
                                                            type="number" class="form-control">
                                                    @else
                                                        {{ $grade['range_to'] }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($grade['isEditing'])
                                                        <!-- Update Icon -->
                                                        <i class="fas fa-check-circle text-success fs-5 mx-2"
                                                            wire:click="updateGrade({{ $index }})"
                                                            title="Update" data-bs-toggle="tooltip"
                                                            data-bs-placement="top" wire:loading.attr="disabled"
                                                            wire:target="updateGrade({{ $index }})">
                                                        </i>
                                                        <!-- Cancel Icon -->
                                                        <i class="fas fa-times-circle text-secondary fs-5 mx-2"
                                                            wire:click="cancelEdit({{ $index }})" title="Cancel"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            wire:loading.attr="disabled"
                                                            wire:target="cancelEdit({{ $index }})">
                                                        </i>
                                                    @else
                                                        <!-- Edit Icon -->
                                                        <i class="fas fa-edit text-primary fs-5 mx-2"
                                                            wire:click="editGrade({{ $index }})" title="Edit"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            wire:loading.attr="disabled"
                                                            wire:target="editGrade({{ $index }})">
                                                        </i>
                                                        <!-- Delete Icon -->
                                                        <i class="fas fa-trash-alt text-danger fs-5 mx-2"
                                                            wire:click="removeGradeInput({{ $index }})"
                                                            title="Delete" data-bs-toggle="tooltip"
                                                            data-bs-placement="top" wire:loading.attr="disabled"
                                                            wire:target="removeGradeInput({{ $index }})">
                                                        </i>
                                                    @endif

                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>


                            </table>

                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Form for Adding/Editing Multiple Grades in Tabular Format -->
            <div wire:ignore.self>
                <div class="alert alert-info">
                    <h2 class="h4 font-weight-bold mb-2">Grading System: {{ $gradingSystemName }}</h2>
                    <p class="small text-muted mb-4">{{ $gradingSystemDescription }}</p>
                </div>
                <form wire:submit="saveGrades" class="mb-4">
                    <table class="table table-bordered">


                        <thead>
                            <tr>
                                <th rowspan="2" class="align-middle">Grading System</th>

                                <th colspan="5">Mean Grades</th>
                            </tr>
                            <tr>
                                <th>MeanGrade</th>
                                <th>Remark</th>
                                <th>GPA</th>
                                <th>Range From</th>
                                <th>Range To</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($gradesList as $index => $grade)
                                <tr>
                                    @if ($index === 0)
                                        <!-- Show grading system and subject names in the first row -->
                                        <td rowspan="{{ count($gradesList) }}" class="align-middle">
                                            {{ $gradingSystemName }}</td>
                                    @endif
                                    <td>
                                        <input wire:model.blur="gradesList.{{ $index }}.grade" type="text"
                                            class="form-control" maxlength="2"
                                            oninput="this.value = this.value.toUpperCase()"
                                            pattern="^(A|A\-|B\+|B|B\-|C\+|C|C\-|D\+|D|D\-|E)$"
                                            title="Allowed grades: A, A-, B+, B, B-, C+, C, C-, D+, D, D-, E" required>
                                        @error("gradesList.$index.grade")
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </td>

                                    <td>
                                        <input wire:model.blur="gradesList.{{ $index }}.remark" type="text"
                                            class="form-control"
                                            oninput="this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1)">
                                        @error("gradesList.$index.remark")
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input wire:model.blur="gradesList.{{ $index }}.gpa" type="number"
                                            step="0.01" class="form-control" max="13">
                                        @error("gradesList.$index.gpa")
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input wire:model.blur="gradesList.{{ $index }}.range_from"
                                            type="number" class="form-control">
                                        @error("gradesList.$index.range_from")
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input wire:model.blur="gradesList.{{ $index }}.range_to"
                                            type="number" class="form-control">
                                        @error("gradesList.$index.range_to")
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        @if ($index > 0)
                                            <button type="button" wire:click="removeGradeInput({{ $index }})"
                                                class="btn btn-danger btn-sm" data-toggle="tooltip"
                                                data-placement="right" title="Remove MeanGrade">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Add More Grades Button -->
                    <div class="mt-2">
                        <button type="button" wire:click="addGradeInput" class="btn btn-info" data-toggle="tooltip"
                            data-placement="right" title="Add Another MeanGrade" wire:loading.attr="disabled"
                            wire:target="addGradeInput">
                            <span wire:loading.remove wire:target="addGradeInput"><i
                                    class="fas fa-plus-circle"></i></span>
                            <span wire:loading wire:target="addGradeInput"><i class="fas fa-spinner fa-spin"></i>
                                Adding...</span>
                        </button>
                    </div>


                    <!-- Save and Cancel Buttons -->
                    <div class="mt-3 d-flex align-items-center justify-content-start gap-2">
                        <!-- Save Grades -->
                        <button type="submit" class="btn btn-primary btn-sm px-3 py-1" wire:click="saveGrades"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="saveGrades">Save Grades</span>
                            <span wire:loading wire:target="saveGrades">
                                <i class="fas fa-spinner fa-spin"></i> Saving...
                            </span>
                        </button>

                        <!-- Save for All -->
                        <button type="button" class="btn btn-warning btn-sm px-3 py-1" wire:click="saveGradesForAll"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="saveGradesForAll">Save for All</span>
                            <span wire:loading wire:target="saveGradesForAll">
                                <i class="fas fa-spinner fa-spin"></i> Saving for All...
                            </span>
                        </button>

                        <!-- Cancel -->
                        <button type="button" class="btn btn-secondary btn-sm px-3 py-1" wire:click="cancelForm"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="cancelForm">Cancel</span>
                            <span wire:loading wire:target="cancelForm">
                                <i class="fas fa-spinner fa-spin"></i> Cancelling...
                            </span>
                        </button>
                    </div>

                </form>
            </div>
        @endif
    </div>
    </div>

    <script>
        $(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
