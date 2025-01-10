<!-- Display the current year for the transition -->
<div class="mb-4 p-3 bg-light rounded">
    <p class="mb-0"><strong>Transition Year:</strong> {{ now()->year }}</p>
</div>

<!-- Target Class and Section (for promotions, demotions, and repetitions) -->
@if ($transitionType !== 'graduation')
    <div class="row mb-4">
        <div class="col-md-6">
            <label for="targetClassId" class="form-label fw-bold">Target Class</label>
            <select wire:model.live="targetClassId" id="targetClassId" class="form-select form-control shadow-sm">
                <option value="">Select Class</option>
                @foreach ($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                @endforeach
            </select>
        </div>
        <!-- Show Section Dropdown only if a Class is selected -->
        @if ($targetClassId)
            <div class="col-md-6">
                <label for="targetSectionId" class="form-label fw-bold">Target Section</label>
                <select wire:model.live="targetSectionId" id="targetSectionId"
                    class="form-select form-control shadow-sm">
                    <option value="">Select Section</option>
                    @foreach ($sections as $section)
                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                    @endforeach
                </select>
            </div>
        @endif
    </div>
@endif

<!-- Show Search Bar only if a Section is selected -->
@if ($targetSectionId)
    <div class="mb-4">
        <label for="searchTerm" class="form-label fw-bold">Search Students</label>
        <input wire:model.live="searchTerm" type="text" id="searchTerm" class="form-control shadow-sm"
            placeholder="Search by name or admission number">
    </div>
@endif

<!-- Show Student Selection only if a Section is selected -->
@if ($targetSectionId)
    <div class="mb-4">
        <label class="form-label fw-bold">Select Students</label>
        <div class="border rounded p-3" style="max-height: 400px; overflow-y: auto;">
            <div class="row">
                @if ($students->isEmpty())
                <div class="col-md-12 text-center text-secondary font-weight-bold">
                    <i class="fas fa-exclamation-circle me-2"></i>
                   
                        No students found in the selected section.
                    
                </div>
                @else
                    @foreach ($students as $student)
                        <div class="col-md-4 mb-3">
                            <div class="form-check">
                                <input wire:model="selectedStudents" class="form-check-input" type="checkbox"
                                    value="{{ $student->id }}" id="student{{ $student->id }}">
                                <label class="form-check-label" for="student{{ $student->id }}">
                                    <strong>{{ $student->first_name }} {{ $student->middle_name }}
                                        {{ $student->last_name }}</strong>
                                    <br>
                                    <small class="text-muted">Admission No: {{ $student->adm_no }}</small>
                                </label>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    <div class="mb-4">
        <label for="reason" class="form-label fw-bold">Reason</label>
        <textarea wire:model="reason" id="reason" class="form-control shadow-sm" rows="4"
            placeholder="Enter the reason for the transition"></textarea>
    </div>

    <div class="d-grid">
        <button type="submit" class="btn btn-success btn-sm shadow-sm">
            <i class="fas fa-save me-2"></i> Save Transition
        </button>
    </div>

@endif