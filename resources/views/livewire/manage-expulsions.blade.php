<div class="card mt-2" wire:poll.5s>
    <div class="card-body">
        <h2 class="text-center font-weight-bold mb-4">Suspended Students</h2>
        <x-flash-messages />

        @if (!$isReinstating && !$isExtendingExpulsion)
        <div class="row">
            @forelse ($expelledStudents as $student)
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <div class="card shadow-lg border-light">
                    <div class="card-body">
                        <h3 class="text-center font-weight-bold mb-2">{{ $student->first_name }} {{ $student->last_name }}</h3>

                        <p class="mb-2"><strong>Admission Number:</strong> <span class="badge bg-primary">{{ $student->adm_no }}</span></p>
                        <p class="mb-2"><strong>Suspension Reason:</strong> <span class="badge bg-danger">{{ $student->suspension_reason }}</span></p>
                        <p class="mb-2"><strong>Suspension Type:</strong> <span class="text-muted">{{ ucfirst($student->suspension_type) }}</span></p>
                        <p class="mb-2"><strong>Suspension Date:</strong> <span class="text-muted">{{ $student->suspension_date ? $student->suspension_date->format('Y-m-d') : 'N/A' }}</span></p>
                        <p class="mb-2"><strong>Suspension End Date:</strong> <span class="text-muted">{{ $student->suspension_end_date ? $student->suspension_end_date->format('Y-m-d') : 'N/A' }}</span></p>

                        <!-- Alert Box for Remaining Days and Time Since Suspension -->
                        <div class="alert alert-info mt-2">
                            <p class="mb-1"><strong>Remaining Days:</strong>
                                @if ($student->suspension_end_date)
                                <span class="text-success">{{ $this->humanReadableCountdown($student->suspension_end_date) }}</span>
                                @else
                                <span class="text-muted">N/A</span>
                                @endif
                            </p>
                            <p class="mb-0"><strong>Time Since Suspension:</strong>
                                @if ($student->suspension_date)
                                <span class="text-muted">{{ $this->humanReadableElapsedTime($student->suspension_date) }}</span>
                                @else
                                <span class="text-muted">N/A</span>
                                @endif
                            </p>
                        </div>

                        <div class="d-flex justify-content-between mt-3">
                            <button wire:click="reinstate({{ $student->id }})" class="btn btn-success btn-sm">Reinstate</button>
                            <button wire:click="extendExpulsion({{ $student->id }})" class="btn btn-primary btn-sm">Extend</button>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-3">
                <p class="font-weight-bold">No suspended students found.</p>
            </div>
            @endforelse
        </div>
        @endif

        {{-- Reinstate Student Logic --}}
        @if ($isReinstating)
        <div class="mt-4">
            <h3 class="font-weight-bold mb-3">Reinstate Student</h3>
            <p class="mb-3">Are you sure you want to reinstate the student?</p>
            <div class="d-flex gap-3">
                <button wire:click="confirmReinstatement" class="btn btn-success">Yes, Reinstate</button>
                <button wire:click="$set('isReinstating', false)" class="btn btn-danger">Cancel</button>
            </div>
        </div>
        @endif

        {{-- Extend Suspension Logic --}}
        @if ($isExtendingExpulsion)
        <div class="mt-4">
            <h3 class="font-weight-bold mb-3">Extend Suspension</h3>

            <label for="current_suspension_end_date" class="form-label">Current Suspension End Date:</label>
            <div class="alert alert-info">
                <strong>
                    {{ $student ? ($student->suspension_end_date ? $student->suspension_end_date->toFormattedDateString() : 'N/A') : 'Student not found.' }}
                </strong>
            </div>

            <label for="new_suspension_end_date" class="form-label">New Suspension End Date:</label>
            <input type="date" wire:model.live="newExpulsionEndDate" class="form-control mb-2" />

            <div class="d-flex gap-3">
                <button wire:click="confirmExtension" class="btn btn-primary">Confirm Extension</button>
                <button wire:click="$set('isExtendingExpulsion', false)" class="btn btn-danger">Cancel</button>
            </div>
        </div>
        @endif
    </div>
</div>
