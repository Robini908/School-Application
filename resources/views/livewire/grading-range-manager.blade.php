<div x-data="{ instructionsVisible: false, isEditing: @entangle('isEditing').live, currentIndex: null, showForm: @entangle('showForm').live, isLoading: @entangle('isLoading').live }" class="card mt-2 col-12 p-3 shadow-lg border rounded"
    style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
    <h3 class="mb-4 text-center" style="color: #333; font-weight: bold; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);">
        Manage Grading Ranges</h3>
    <div class="form-group">

        <button wire:click="refreshGradingSystems" class="btn btn-primary mb-3" wire:loading.attr="disabled">
            <i class="fas fa-sync-alt"></i> Refresh Grading Systems
            <span wire:loading wire:target="refreshGradingSystems">
                <i class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></i>
            </span>
        </button>

        <div class="mb-4">
            <label for="grading-system" class="form-label">Select Grading System</label>
            <select wire:model.live="selectedGradingSystem" class="form-control" id="grading-system">
                <option value="">Select a Grading System</option>
                @foreach ($gradingSystems as $gradingSystem)
                    <option value="{{ $gradingSystem->id }}" wire:key="grading-system-{{ $gradingSystem->id }}">
                        {{ $gradingSystem->name }}
                    </option>
                @endforeach
            </select>
        </div>


        @if ($selectedGradingSystem)
            <div class="mb-4">
                <label for="subject" class="form-label">Select Subject</label>
                <select wire:model.live="subjectId" class="form-control" id="subject">
                    <option value="">Select a Subject</option>
                    @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                    @endforeach
                </select>
            </div>
        @endif
    </div>


    @if ($subjectId && $selectedGradingSystem)
        @if (!$hasAssignedRanges)
            <div x-data="{ moreDetails: false }" class="mt-2 mb-3 p-4"
                style="background: linear-gradient(135deg, #f0f4f8 50%, #e9ecef 100%);">
                <!-- Button to toggle details -->
                <button @click="moreDetails = !moreDetails"
                    class="btn btn-link text-primary font-weight-bold d-flex align-items-center text-left">
                    <!-- Font Awesome "important" icon -->
                    <i class="fas fa-exclamation-circle mr-2" style="font-size: 1.4rem; color: #e74c3c;"></i>
                    <span x-show="!moreDetails" class="text-primary">Important...</span>
                    <span x-show="moreDetails" class="text-danger">Close</span>
                </button>

                <!-- Collapsible content -->
                <div x-show="moreDetails" class="mt-1 p-3 text-left">
                    <p class="mb-2 text-danger font-italic">Make sure to review existing ranges to avoid conflicts.</p>
                    <p class="mb-2 text-danger font-italic">Consider how the ranges impact overall grading policies.</p>
                    <p class="mb-2 text-danger font-italic">Document any changes made for future reference.</p>
                </div>

                <!-- Grading ranges assignment header -->
                <h4 class="text-left">
                    Assign Grading Ranges for <strong
                        class="text-primary">{{ $subjects->find($subjectId)->subject_name }}</strong>
                    for grading system <strong
                        class="text-info">"{{ $gradingSystems->find($selectedGradingSystem)->name }}"</strong>
                </h4>
            </div>

            <div>
                <!-- Dropdown for Action Selection -->
                <div class="list-icons mb-4">
                    <div class="dropdown">
                        <!-- The dropdown button -->
                        <a href="#" class="btn btn-light border shadow-sm rounded-circle p-2"
                            data-toggle="dropdown" style="display: inline-flex; align-items: center;">
                            <i class="icon-menu9" style="font-size: 1.2rem; color: #6c757d;"></i>
                        </a> More Actions

                        <!-- Dropdown menu -->
                        <div class="dropdown-menu dropdown-menu-end shadow-lg rounded"
                            style="padding: 0.5rem; border: 1px solid #e5e7eb; background-color: #f9fafb;">
                            <button class="dropdown-item" type="button" wire:click="$set('activeAction', 'reuseSame')">
                                <i class="icon-pencil"></i> Re-use Grading Ranges (Same Grading System)
                            </button>
                            <button class="dropdown-item" type="button" wire:click="$set('activeAction', 'suggest')">
                                <i class="icon-lightbulb"></i> Suggest Grading Ranges
                            </button>
                            <button class="dropdown-item" type="button"
                                wire:click="$set('activeAction', 'reuseDifferent')">
                                <i class="icon-undo"></i> Re-use Grading Ranges (Different Grading System)
                            </button>
                        </div>
                    </div>
                </div>


                <!-- Conditional Rendering Based on Active Action -->
                @if ($activeAction === 'reuseSame')
                    <div class="alert alert-info">
                        <h6 class="mb-2">Select a Subject</h6>
                        <p>Select a subject from the dropdown to reuse grading ranges that are already established in
                            the
                            system.
                        </p>
                        <label for="reuseSubject" class="form-label">Subject:</label>
                        <select wire:model.live="reuseSubjectId" id="reuseSubject" class="form-control">
                            <option value="">Select a Subject</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                            @endforeach
                        </select>
                        <div class="mt-3">
                            <button type="button" wire:click="reuseGradingRanges"
                                class="btn btn-warning">Re-use</button>
                        </div>
                    </div>
                @elseif($activeAction === 'suggest')
                    <div class="alert alert-info">
                        <h6 class="mb-2">Suggest Grading Ranges</h6>
                        <p>Click the button below to generate new grading ranges based on performance data.</p>
                        <button type="button" wire:click="suggestGradingRanges" class="btn btn-success btn-sm ">
                            Suggest
                        </button>
                    </div>
                @elseif($activeAction === 'reuseDifferent')
                    <div class="alert alert-info">
                        <h5 class="mb-3">Reuse Grading Ranges (Different Grading System)</h5>
                        <div class="mb-3">
                            <label for="reuseGradingSystem" class="form-label">Select Grading System:</label>
                            <select wire:model.live="reuseGradingSystemId" id="reuseGradingSystem" class="form-control">
                                <option value="">Select a Grading System</option>
                                @foreach ($gradingSystems as $gradingSystem)
                                    <option value="{{ $gradingSystem->id }}">{{ $gradingSystem->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="reuseSubjectFromOtherSystem" class="form-label">Select Subject:</label>
                            <select wire:model.live="reuseSubjectFromOtherSystemId" id="reuseSubjectFromOtherSystem"
                                class="form-control">
                                <option value="">Select a Subject</option>
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-3">
                            <button type="button" wire:click="reuseGradingRangesFromOtherSystem"
                                class="btn btn-info">Re-use</button>
                        </div>
                    </div>
                @endif
            </div>
            <!-- Table for Defining Grading Ranges -->

            <div class="table-responsive ">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th rowspan="2" class="align-middle">Grading System</th>
                            <th rowspan="2" class="align-middle">Subject</th>
                            <th colspan="5">Grading Ranges</th>
                        </tr>
                        <tr>
                            <th>Range From</th>
                            <th>Range To</th>
                            <th>Grade</th>
                            <th>Remark</th>
                            <th>GPA</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ranges as $index => $range)
                            <tr wire:key="range-{{ $index }}"">
                                @if ($index === 0)
                                    <!-- Show grading system and subject names in the first row -->
                                    <td rowspan="{{ count($ranges) }}" class="align-middle">
                                        {{ $gradingSystems->find($selectedGradingSystem)->name }}</td>
                                    <td rowspan="{{ count($ranges) }}" class="align-middle">
                                        {{ $subjects->find($subjectId)->subject_name }}</td>
                                @endif
                                <td>
                                    <input type="number" wire:model.blur="ranges.{{ $index }}.range_from"
                                        class="form-control form-control-sm" placeholder="Range From" required>
                                </td>
                                <td>
                                    <input type="number" wire:model.blur="ranges.{{ $index }}.range_to"
                                        class="form-control form-control-sm" placeholder="Range To" required>
                                </td>
                                <td>
                                    <input type="text" wire:model.blur="ranges.{{ $index }}.grade"
                                        class="form-control form-control-sm" placeholder="Grade" required
                                        onkeypress="return /[A-Za-z+\-]/.test(event.key)"
                                        oninput="this.value = this.value.toUpperCase()">
                                </td>
                                <td>
                                    <input type="text" wire:model.blur="ranges.{{ $index }}.remark"
                                        class="form-control form-control-sm" placeholder="Remark">
                                </td>
                                <td>
                                    <input type="number" step="0.1"
                                        wire:model.blur="ranges.{{ $index }}.gpa"
                                        class="form-control form-control-sm" placeholder="GPA">
                                </td>
                                <td>
                                    @if ($index > 0)
                                        <button type="button" wire:click="removeRange({{ $index }})"
                                            class="btn btn-danger btn-sm">Remove</button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>


            <div class="d-flex justify-content-between mt-1">
                @if (!$isEditing)
                    @if (count($ranges) < 12)
                        <button type="button" wire:click="addRange" class="btn btn-secondary mb-2"
                            wire:loading.attr="disabled">
                            <i class="fas fa-plus"></i> Add More Ranges
                            <span wire:loading wire:target="addRange">
                                <i class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></i>
                            </span>
                        </button>
                    @else
                        <div class="alert alert-warning">You can only add up to 12 grading ranges for this subject.
                        </div>
                    @endif
                @endif
                <button type="submit" class="btn btn-success mt-2" wire:click="saveRanges"
                    wire:loading.attr="disabled">
                    {{ $isEditing ? 'Update' : 'Save' }}
                    <span wire:loading wire:target="saveRanges">
                        <i class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></i>
                    </span>
                </button>
                <hr class="my-4 font-bold"> <!-- Horizontal line for separation -->
            </div>

        @endif
        @if ($hasAssignedRanges)
            <div class="card my-4 p-4 shadow-lg">
                <h5 class="card-title text-primary font-weight-bold mb-3">
                    Grading Ranges Already Assigned
                </h5>
                <p class="card-text text-muted mb-2">
                    Grading ranges have already been assigned for the selected subject:
                    <strong>{{ $submittedRanges->first()->subject->subject_name }}</strong>
                    under the grading system:
                    <strong>{{ $submittedRanges->first()->gradingSystem->name }}</strong>.
                </p>

                <div class="mt-4">
                    <table class="table table-bordered table-striped mt-4">
                        <thead>
                            <tr>
                                <th>Range From</th>
                                <th>Range To</th>
                                <th>Grade</th>
                                <th>Remark</th>
                                <th>GPA</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($submittedRanges as $index => $submittedRange)
                                <tr>
                                    <!-- Range From -->
                                    <td>
                                        @if ($isEditing && $currentIndex == $index)
                                            <input type="text" class="form-control"
                                                wire:model="ranges.{{ $index }}.range_from">
                                        @else
                                            {{ $submittedRange->range_from }}
                                        @endif
                                    </td>

                                    <!-- Range To -->
                                    <td>
                                        @if ($isEditing && $currentIndex == $index)
                                            <input type="text" class="form-control"
                                                wire:model="ranges.{{ $index }}.range_to">
                                        @else
                                            {{ $submittedRange->range_to }}
                                        @endif
                                    </td>

                                    <!-- Grade -->
                                    <td>
                                        @if ($isEditing && $currentIndex == $index)
                                            <input type="text" class="form-control"
                                                wire:model="ranges.{{ $index }}.grade">
                                        @else
                                            {{ $submittedRange->grade }}
                                        @endif
                                    </td>

                                    <!-- Remark -->
                                    <td>
                                        @if ($isEditing && $currentIndex == $index)
                                            <input type="text" class="form-control"
                                                wire:model="ranges.{{ $index }}.remark">
                                        @else
                                            {{ $submittedRange->remark }}
                                        @endif
                                    </td>

                                    <!-- GPA -->
                                    <td>
                                        @if ($isEditing && $currentIndex == $index)
                                            <input type="text" class="form-control"
                                                wire:model="ranges.{{ $index }}.gpa">
                                        @else
                                            {{ $submittedRange->gpa }}
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <div class="d-flex justify-content-center">
                                            <!-- Edit Icon Button -->
                                            @if (!$isEditing || $currentIndex != $index)
                                                <button wire:click="editRange({{ $index }})"
                                                    class="btn btn-light btn-sm mx-2" data-toggle="tooltip"
                                                    data-placement="top" title="Edit range">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            @else
                                                <!-- Save/Cancel Button -->
                                                <button wire:click="submitRange({{ $index }})"
                                                    class="btn btn-success btn-sm mx-2" data-toggle="tooltip"
                                                    data-placement="top" title="Save changes">
                                                    <i class="fas fa-save"></i>
                                                </button>
                                                <button wire:click="cancelEdit" class="btn btn-secondary btn-sm mx-2"
                                                    data-toggle="tooltip" data-placement="top"
                                                    title="Cancel editing">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @endif

                                            <!-- Delete Icon Button -->
                                            <button wire:click="deleteRange({{ $submittedRange->id }})"
                                                class="btn btn-danger btn-sm mx-2" data-toggle="tooltip"
                                                data-placement="top" title="Remove range">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif



    @endif




</div>
