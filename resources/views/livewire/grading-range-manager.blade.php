<div x-data="{ instructionsVisible: false, isEditing: @entangle('isEditing'), currentIndex: null }"
    class="container my-4">
    @if (session()->has('message'))
    <div class="alert alert-success mt-3">{{ session('message') }}</div>
    @endif






    <div class="card">
        <div class="card-body">
            <div class="form-group">

                <div class="mb-4">
                    <label for="grading-system" class="form-label">Select Grading System</label>
                    <select wire:model="selectedGradingSystem" class="form-control" id="grading-system">
                        <option value="">Select a Grading System</option>
                        @foreach($gradingSystems as $gradingSystem)
                        <option value="{{ $gradingSystem->id }}">{{ $gradingSystem->name }}</option>
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
        <form wire:submit.prevent="submitRanges" class="mb-4 overflow-x-auto">
            <h4>Grading Ranges for {{ $subjects->find($subjectId)->subject_name }}</h4>

            <table class="table table-bordered table-striped w-full">
                <thead>
                    <tr>
                        <th rowspan="2">Grading System</th>
                        <th rowspan="2">Subject</th>
                        <th colspan="4">Grading Ranges</th>
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
                        <!-- Only show grading system and subject names in the first row -->
                        <td rowspan="{{ count($ranges) }}">{{ $gradingSystems->find($selectedGradingSystem)->name }}
                        </td>
                        <td rowspan="{{ count($ranges) }}">{{ $subjects->find($subjectId)->subject_name }}</td>
                        @endif
                        <td>
                            <input type="number" wire:model.lazy="ranges.{{ $index }}.range_from"
                                class="form-control w-24" placeholder="Range From" required>
                        </td>
                        <td>
                            <input type="number" wire:model.lazy="ranges.{{ $index }}.range_to"
                                class="form-control w-24" placeholder="Range To" required>
                        </td>
                        <td>
                            <input type="text" wire:model.lazy="ranges.{{ $index }}.grade" class="form-control w-24"
                                placeholder="Grade" required onkeypress="return /[A-Za-z+\-]/.test(event.key)"
                                oninput="this.value = this.value.toUpperCase()">
                        </td>

                        <td>
                            <input type="text" wire:model.lazy="ranges.{{ $index }}.remark" class="form-control w-24"
                                placeholder="Remark">
                        </td>
                        <td>
                            <input type="number" step="0.1" wire:model.lazy="ranges.{{ $index }}.gpa"
                                class="form-control w-24" placeholder="GPA">
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

            <div class="my-4">
                @if(!$isEditing)
                @if(count($ranges) < 12) <button type="button" wire:click="addRange" class="btn btn-secondary mb-2">
                    <i class="fas fa-plus"></i> Add More Ranges
                    </button>
                    @else
                    <div class="alert alert-warning">You can only add up to 12 grading ranges for this subject.</div>
                    @endif
                    @endif

                    <hr class="my-4"> <!-- Horizontal line for separation -->
                    <button type="submit" class="btn btn-primary mt-2">
                        {{ $isEditing ? 'Update Range' : 'Save Ranges' }}
                    </button>
            </div>
        </form>

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
                @foreach($submittedRanges as $submittedRange)
                <tr>
                    <td>{{ $submittedRange->range_from }}</td>
                    <td>{{ $submittedRange->range_to }}</td>
                    <td>{{ $submittedRange->grade }}</td>
                    <td>{{ $submittedRange->remark }}</td>
                    <td>{{ $submittedRange->gpa }}</td>
                    <td>
                        <button wire:click="editSubmittedRange({{ $submittedRange->id }})"
                            class="btn btn-warning btn-sm">Edit</button>
                        <button wire:click="deleteRange({{ $submittedRange->id }})"
                            class="btn btn-danger btn-sm">Delete</button>
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