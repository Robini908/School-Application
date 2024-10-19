<div style="background-color: #f8f9fa; padding: 20px;">
    <h2 class="text-lg font-semibold mb-4">Manage Promotions and Demotions</h2>

    @if (session()->has('message'))
    <div class="alert alert-success" role="alert">
        {{ session('message') }}
    </div>
    @endif

   <!-- Promote/Demote Buttons with Icons -->
<div class="mb-4 align-items-center">
    <!-- Promote Button -->
    <button wire:click="showForm('promote')" class="btn btn-primary btn-sm me-2" data-toggle="tooltip"
        data-placement="top" title="Promote Students">
        <i class="fas fa-arrow-up"></i> Promote
    </button>

    <!-- Demote Button -->
    <button wire:click="showForm('demote')" class="btn btn-danger btn-sm me-2" data-toggle="tooltip" data-placement="top"
        title="Demote Students">
        <i class="fas fa-arrow-down"></i> Demote
    </button>
</div>

@if ($isShowingForm)
<div class="bg-light p-4 rounded shadow-sm">
    <h3 class="text-lg font-semibold mb-2">
        {{ $isEditing ? 'Edit ' : 'Add ' }}
        {{ $action === 'promote' ? 'Promotion' : 'Demotion' }}
    </h3>

    <form wire:submit="save">
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="selectedClassId" class="form-label">Select Class:</label>
                <select wire:model.live="selectedClassId" id="selectedClassId" class="form-control">
                    <option value="">Select Class</option>
                    @foreach($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>

            @if($selectedClassId)
            <div class="col-md-4">
                <label for="selectedSectionId" class="form-label">Filter by Section:</label>
                <select wire:model.live="selectedSectionId" id="selectedSectionId" class="form-control">
                    <option value="">Select Section</option>
                    @foreach ($sections as $section)
                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                    @endforeach
                </select>
                @error('selectedSectionId') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            @endif

            <div class="col-md-4">
                <label for="student_record_id" class="form-label">Select Student:</label>
                <select wire:model.live="student_record_id" id="student_record_id" class="form-control">
                    <option value="">Select a student</option>
                    @foreach ($students as $student)
                    <option value="{{ $student->id }}">{{ $student->first_name }} {{ $student->last_name }}</option>
                    @endforeach
                </select>
                @error('student_record_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Bulk Select Option -->
        <div class="form-group">
            <div class="form-check">
                <input wire:model.live="selectAll" type="checkbox" class="form-check-input" id="selectAll">
                <label class="form-check-label" for="selectAll">Select All Students</label>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="old_class_id" class="form-label">Old Class:</label>
                <input type="text" wire:model.live="old_class_id" id="old_class_id" class="form-control" readonly>
                @error('old_class_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="col-md-4 mb-3">
                <label for="old_section_id" class="form-label">Old Section:</label>
                <input type="text" wire:model.live="old_section_id" id="old_section_id" class="form-control" readonly>
                @error('old_section_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="new_class_id" class="form-label">{{ $action === 'promote' ? 'New Class:' : 'Old Class:' }}</label>
                <select wire:model.live="new_class_id" id="new_class_id" class="form-control">
                    <option value="">Select new class</option>
                    @foreach ($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
                @error('new_class_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="col-md-4 mb-3">
                <label for="new_section_id" class="form-label">{{ $action === 'promote' ? 'New Section:' : 'Old Section:' }}</label>
                <select wire:model.live="new_section_id" id="new_section_id" class="form-control">
                    <option value="">Select new section</option>
                    @foreach ($sections as $section)
                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                    @endforeach
                </select>
                @error('new_section_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Action Buttons Row -->
        <div class="mt-3">
            <button type="submit" class="btn btn-success btn-sm me-2">
                <i class="fas fa-save"></i> Save
                <span wire:loading wire:target="save" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            </button>
            <button type="button" wire:click="resetForm" class="btn btn-secondary btn-sm me-2">
                <i class="fas fa-times"></i> Cancel
            </button>

            <!-- Promote All Students -->
            @if ($action === 'promote')
            <button wire:click="promoteAll" class="btn btn-primary btn-sm">
                <i class="fas fa-arrow-up"></i> Promote All
            </button>
            @endif

            <!-- Demote All Students -->
            @if ($action === 'demote')
            <button wire:click="demoteAll" class="btn btn-danger btn-sm">
                <i class="fas fa-arrow-down"></i> Demote All
            </button>
            @endif
        </div>
    </form>
</div>
@endif







<h3 class="text-lg font-semibold mt-6 mb-4">Promotion/Demotion Records</h3>
<div class="table-responsive">
    <table class="table table-hover table-bordered table-striped" style="border-radius: 0.5rem; overflow: hidden;">
        <thead class="table-light">
            <tr class="text-center">
                <th>Student</th>
                <th>Old Class</th>
                <th>New Class</th>
                <th>Type</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($promotionsDemotions as $record)
            <tr class="text-center">
                <td>{{ $record->studentRecord->first_name }} {{ $record->studentRecord->last_name }}</td>
                <td>{{ $record->oldClass->name }}</td>
                <td>{{ $record->newClass->name }}</td>
                <td>{{ ucfirst($record->type) }}</td>
                <td>{{ \Carbon\Carbon::parse($record->event_date)->format('d M, Y') }}</td>
                <td>
                    <button wire:click="showForm('{{ $record->type }}', {{ $record->id }})"
                        class="btn btn-link text-primary" style="font-weight: bold;">Edit</button>
                    <button wire:click="delete({{ $record->id }})" class="btn btn-link text-danger" style="font-weight: bold;">Delete</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

</div>