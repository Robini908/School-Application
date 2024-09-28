<div x-data="{ instructionsVisible: false, isEditing: @entangle('isEditing'), currentIndex: null, showForm: @entangle('showForm'), isLoading: @entangle('isLoading') }"
    class="container my-4">
    <x-flash-messages />
    <div class="card">
        <div class="card-body">
            <div class="form-group">

                <button wire:click="refreshGradingSystems" class="btn btn-primary mb-3" wire:loading.attr="disabled">
                    <i class="fas fa-sync-alt"></i> Refresh Grading Systems
                    <span wire:loading wire:target="refreshGradingSystems">
                        <i class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></i>
                    </span>
                </button>

                <div class="mb-4">
                    <label for="grading-system" class="form-label">Select Grading System</label>
                    <select wire:model="selectedGradingSystem" class="form-control" id="grading-system">
                        <option value="">Select a Grading System</option>
                        @foreach($gradingSystems as $gradingSystem)
                        <option value="{{ $gradingSystem->id }}" wire:key="grading-system-{{ $gradingSystem->id }}">
                            {{ $gradingSystem->name }}
                        </option>
                        @endforeach
                    </select>
                </div>


                @if($selectedGradingSystem)
                <div class="mb-4">
                    <label for="subject" class="form-label">Select Subject</label>
                    <select wire:model="subjectId" class="form-control" id="subject">
                        <option value="">Select a Subject</option>
                        @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                        @endforeach
                    </select>
                </div>

            </div>
        </div>
    </div>

    @if($subjectId && $selectedGradingSystem)
    <div x-data="{ moreDetails: false }" class="mb-4">
        <div class="alert alert-info shadow p-4 rounded">
            <h5 class="font-weight-bold">You have selected:</h5>
            <p class="mb-1">Grading System: <strong>{{ $gradingSystems->find($selectedGradingSystem)->name }}</strong>
            </p>
            <p class="mb-1">Subject: <strong>{{ $subjects->find($subjectId)->subject_name }}</strong></p>


            <button @click="moreDetails = !moreDetails" class="btn btn-link">
                <span x-show="!moreDetails">Show More...</span>
                <span x-show="moreDetails">Show Less</span>
            </button>

            <div x-show="moreDetails">
                <p class="mb-2">Make sure to review existing ranges to avoid conflicts.</p>
                <p class="mb-2">Consider how the ranges impact overall grading policies.</p>
                <p class="mb-2">Document any changes made for future reference.</p>
            </div>
        </div>
    </div>





    <div class="card my-4 p-4 shadow-lg border border-gray-200">
        <h4>Grading Ranges for {{ $subjects->find($subjectId)->subject_name }}</h4>

        <!-- Dropdown for Action Selection -->
        <div class="list-icons mb-4">
            <div class="dropdown">
                <a href="#" class="list-icons-item" data-toggle="dropdown">
                    <i class="icon-menu9"></i> More Actions
                </a>

                <div class="dropdown-menu dropdown-menu-left">
                    <button class="dropdown-item" type="button" wire:click="$set('activeAction', 'reuseSame')">
                        <i class="icon-pencil"></i> Re-use Grading Ranges (Same Grading System)
                    </button>
                    <button class="dropdown-item" type="button" wire:click="$set('activeAction', 'suggest')">
                        <i class="icon-lightbulb"></i> Suggest Grading Ranges
                    </button>
                    <button class="dropdown-item" type="button" wire:click="$set('activeAction', 'reuseDifferent')">
                        <i class="icon-undo"></i> Re-use Grading Ranges (Different Grading System)
                    </button>
                </div>
            </div>
        </div>

        <!-- Conditional Rendering Based on Active Action -->
        @if($activeAction === 'reuseSame')
        <div class="alert alert-info">
            <h6 class="mb-2">Select a Subject</h6>
            <p>Select a subject from the dropdown to reuse grading ranges that are already established in the system.
            </p>
            <label for="reuseSubject" class="form-label">Subject:</label>
            <select wire:model="reuseSubjectId" id="reuseSubject" class="form-control">
                <option value="">Select a Subject</option>
                @foreach($subjects as $subject)
                <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                @endforeach
            </select>
            <div class="mt-3">
                <button type="button" wire:click="reuseGradingRanges" class="btn btn-warning">Re-use</button>
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
                <select wire:model="reuseGradingSystemId" id="reuseGradingSystem" class="form-control">
                    <option value="">Select a Grading System</option>
                    @foreach($gradingSystems as $gradingSystem)
                    <option value="{{ $gradingSystem->id }}">{{ $gradingSystem->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="reuseSubjectFromOtherSystem" class="form-label">Select Subject:</label>
                <select wire:model="reuseSubjectFromOtherSystemId" id="reuseSubjectFromOtherSystem"
                    class="form-control">
                    <option value="">Select a Subject</option>
                    @foreach($subjects as $subject)
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
    <div class="table-responsive">
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
                @foreach($ranges as $index => $range)
                <tr>
                    @if($index === 0)
                    <!-- Show grading system and subject names in the first row -->
                    <td rowspan="{{ count($ranges) }}" class="align-middle">{{
                        $gradingSystems->find($selectedGradingSystem)->name }}</td>
                    <td rowspan="{{ count($ranges) }}" class="align-middle">{{ $subjects->find($subjectId)->subject_name
                        }}</td>
                    @endif
                    <td>
                        <input type="number" wire:model.lazy="ranges.{{ $index }}.range_from"
                            class="form-control form-control-sm" placeholder="Range From" required>
                    </td>
                    <td>
                        <input type="number" wire:model.lazy="ranges.{{ $index }}.range_to"
                            class="form-control form-control-sm" placeholder="Range To" required>
                    </td>
                    <td>
                        <input type="text" wire:model.lazy="ranges.{{ $index }}.grade"
                            class="form-control form-control-sm" placeholder="Grade" required
                            onkeypress="return /[A-Za-z+\-]/.test(event.key)"
                            oninput="this.value = this.value.toUpperCase()">
                    </td>
                    <td>
                        <input type="text" wire:model.lazy="ranges.{{ $index }}.remark"
                            class="form-control form-control-sm" placeholder="Remark">
                    </td>
                    <td>
                        <input type="number" step="0.1" wire:model.lazy="ranges.{{ $index }}.gpa"
                            class="form-control form-control-sm" placeholder="GPA">
                    </td>
                    <td>
                        @if($index > 0)
                        <button type="button" wire:click="removeRange({{ $index }})"
                            class="btn btn-danger btn-sm">Remove</button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="my-4">
        @if(!$isEditing)
        @if(count($ranges) < 12) <button type="button" wire:click="addRange" class="btn btn-secondary mb-2"
            wire:loading.attr="disabled">
            <i class="fas fa-plus"></i> Add More Ranges
            <span wire:loading wire:target="addRange">
                <i class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></i>
            </span>
            </button>
            @else
            <div class="alert alert-warning">You can only add up to 12 grading ranges for this subject.</div>
            @endif
            @endif

            <hr class="my-4"> <!-- Horizontal line for separation -->

            <button type="submit" class="btn btn-primary mt-2" wire:click="saveRanges" wire:loading.attr="disabled">
                {{ $isEditing ? 'Update Range' : 'Save Ranges' }}
                <span wire:loading wire:target="saveRanges">
                    <i class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></i>
                </span>
            </button>
    </div>

    <!-- Loading spinner -->
    <div x-show="isLoading" class="text-center my-4" x-cloak>
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
        </div>
        <p class="mt-2">Saving... Please wait.</p>
    </div>






    @if($submittedRanges->isEmpty())
    <div class="alert alert-warning">No grading ranges submitted for this subject and grading system.<br><br>Consider
        adding the ranges in the form above </div>
    @else
    <div class="card my-4 p-4 shadow-lg">
        <h4 class="card-title text-primary font-weight-bold mb-3">
            Grading Ranges for:
        </h4>
        <p class="card-text text-muted">
            <strong>Grading System:</strong> <span class="font-weight-normal">{{
                $submittedRanges->first()->gradingSystem->name }}</span>
        </p>
        <p class="card-text text-muted">
            <strong>Subject:</strong> <span class="font-weight-normal">{{
                $submittedRanges->first()->subject->subject_name }}</span>
        </p>


        <div class="mt-4">
            <h5>Grading Ranges Analysis</h5>

            <table class="table table-bordered table-striped mt-4">
                <thead>
                    <tr>
                        <th>Range From</th>
                        <th>Range To</th>
                        <th>Grade</th>
                        <th>Remark</th>
                        <th>GPA</th>
                        <th>Mean Score</th>
                        <th>Mean Grade</th>
                        <th>Total Points</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($submittedRanges as $submittedRange)
                    <tr>
                        <td>{{ $submittedRange->range_from }}</td>
                        <td>{{ $submittedRange->range_to }}</td>
                        <td>{{ $submittedRange->grade }}</td>
                        <td>{{ $submittedRange->remark }}</td>
                        <td>{{ $submittedRange->gpa }}</td>
                        <td>{{ number_format($submittedRange->mean_score, 2) }}</td> <!-- Display Mean Score -->
                        <td>{{ $submittedRange->mean_grade }}</td> <!-- Display Mean Grade -->
                        <td>{{ number_format($submittedRange->total_points, 2) }}</td> <!-- Display Total Points -->
                        <td>
                            <button wire:click="editSubmittedRange({{ $submittedRange->id }})"
                                class="btn btn-warning btn-sm" wire:loading.attr="disabled">
                                Edit
                                <span wire:loading wire:target="editSubmittedRange({{ $submittedRange->id }})">
                                    <i class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></i>
                                </span>
                            </button>
                            <button wire:click="deleteRange({{ $submittedRange->id }})" class="btn btn-danger btn-sm"
                                wire:loading.attr="disabled">
                                Delete
                                <span wire:loading wire:target="deleteRange({{ $submittedRange->id }})">
                                    <i class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></i>
                                </span>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @endif
        @endif
        @endif



    </div>