<div class="card  mt-2" wire:poll.5s>
    <h2 class="text-xl font-weight-bold mb-4 text-center">Expelled Students</h2>
    <x-flash-messages />

    @if (!$isReinstating && !$isExtendingExpulsion)
    <div class="row">
        @forelse ($expelledStudents as $student)
        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
            <div class="bg-white shadow-lg rounded-lg p-4 border border-gray-200">
                <h3 class="text-xl font-weight-bold text-center mb-2">{{ $student->first_name }} {{ $student->last_name }}</h3>

                <p class="mb-2"><strong>Admission Number:</strong> <span class="badge bg-primary">{{ $student->adm_no }}</span></p>
                <p class="mb-2"><strong>Expulsion Reason:</strong> <span class="badge bg-danger">{{ $student->expulsion_reason }}</span></p>
                <p class="mb-2"><strong>Expulsion Type:</strong> <span class="text-muted">{{ ucfirst($student->expulsion_type) }}</span></p>
                <p class="mb-2"><strong>Expulsion Date:</strong> <span class="text-muted">{{ $student->expulsion_date ? $student->expulsion_date->format('Y-m-d') : 'N/A' }}</span></p>
                <p class="mb-2"><strong>Expulsion End Date:</strong> <span class="text-muted">{{ $student->expulsion_end_date ? $student->expulsion_end_date->format('Y-m-d') : 'N/A' }}</span></p>

                <!-- Alert Box for Remaining Days and Time Since Expulsion -->
                <div class="alert alert-info mt-2">
                    <p class="mb-1"><strong>Remaining Days:</strong>
                        @if ($student->expulsion_end_date)
                        <span class="text-success">{{ $this->humanReadableCountdown($student->expulsion_end_date) }}</span>
                        @else
                        <span class="text-muted">N/A</span>
                        @endif
                    </p>
                    <p class="mb-0"><strong>Time Since Expulsion:</strong>
                        @if ($student->expulsion_date)
                        <span class="text-muted">{{ $this->humanReadableElapsedTime($student->expulsion_date) }}</span>
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
        @empty
        <div class="col-12 text-center py-3">
            <p class="text-lg font-weight-bold">No expelled students found.</p>
        </div>
        @endforelse
    </div>
    @endif

    {{-- Reinstate Student Logic --}}
    @if ($isReinstating)
    <div class="mt-4">
        <h3 class="text-lg font-weight-bold mb-3">Reinstate Student</h3>
        <p class="mb-3">Are you sure you want to reinstate the student?</p>
        <div class="d-flex justify-content-start gap-3">
            <button wire:click="confirmReinstatement" class="btn btn-success">Yes, Reinstate</button>
            <button wire:click="$set('isReinstating', false)" class="btn btn-danger">Cancel</button>
        </div>
    </div>
    @endif

    {{-- Extend Expulsion Logic --}}
    @if ($isExtendingExpulsion)
    <div class="mt-4">
        <h3 class="text-lg font-weight-bold mb-3">Extend Expulsion</h3>

        <label for="current_expulsion_end_date" class="form-label">Current Expulsion End Date:</label>
        <div class="alert alert-info">
            <strong>
                {{ $student ? ($student->expulsion_end_date ? $student->expulsion_end_date->toFormattedDateString() : 'N/A') : 'Student not found.' }}
            </strong>
        </div>

        <label for="new_expulsion_end_date" class="form-label">New Expulsion End Date:</label>
        <input type="date" wire:model="newExpulsionEndDate" class="form-control mb-2" />

        <div class="d-flex justify-content-start gap-3">
            <button wire:click="confirmExtension" class="btn btn-primary">Confirm Extension</button>
            <button wire:click="$set('isExtendingExpulsion', false)" class="btn btn-danger">Cancel</button>
        </div>
    </div>
    @endif
</div>
