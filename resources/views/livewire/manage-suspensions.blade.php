<div class="card mt-3  p-3 shadow-lg border rounded max-w-full"
    style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);" wire:poll.10s>
    <div class="card-body">
        <div>
            <h2 class="text-center font-weight-bold mb-4">Suspended Students</h2>

            @if (!$isReinstating && !$isExtendingSuspension)
            <div wire:poll.1s="checkSuspensions" class="row">
                @forelse ($suspendedStudents as $student)
                    <div class="col-lg-6 col-md-6 col-12 mb-4">
                        <div class="card p-3 shadow-lg border rounded"
                            style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
                            <div class="d-flex justify-content-end mb-4">
                                <!-- Export to PDF Button -->
                                <i wire:click="downloadStudentSuspension({{ $student->id }})"
                                    class="fas fa-file-pdf text-danger mx-1"
                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="Download Suspension Information"
                                    style="font-size: 24px; cursor: pointer; padding: 10px; border: 1px solid #17a2b8; border-radius: 5px; background-color: #f8f9fa;">
                                </i>
                                <div wire:loading wire:target="downloadStudentSuspension({{ $student->id }})"
                                    class="spinner-border spinner-border-sm text-primary ms-2" role="status">
                                </div>
                            </div>
        
                            <div class="card-body">
                                <h3 class="text-center fw-bold mb-2">{{ $student->adm_no }}</h3>
                                <p>
                                    {{ $student->first_name }} {{ $student->last_name }} was suspended because of 
                                    <strong class="text-danger">{{ $student->suspension_reason }}</strong>.<br>
                                    It will be a <strong>{{ ucfirst($student->suspension_type) }}</strong> suspension.<br>
                                    {{ $student->first_name }} was suspended on 
                                    <strong>{{ $student->suspension_date ? $student->suspension_date->format('l, F jS, Y \a\t h:i A') : 'N/A' }}</strong>, 
                                    and will resume studies from 
                                    <strong>{{ $student->suspension_end_date ? $student->suspension_end_date->format('l, F jS, Y \a\t h:i A') : 'N/A' }}</strong>.
                                </p>
        
                                <!-- Alert Box for Remaining Days and Time Since Suspension -->
                                <div class="alert alert-info mt-2">
                                    <p class="mb-1">
                                        <strong>Remaining Days:</strong>
                                        @if ($student->suspension_end_date)
                                            <span class="text-success">{{ $this->humanReadableCountdown($student->suspension_end_date) }}</span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </p>
                                    <p class="mb-0">
                                        <strong>Time Since Suspension:</strong>
                                        @if ($student->suspension_date)
                                            <span class="text-muted">{{ $this->humanReadableElapsedTime($student->suspension_date) }}</span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </p>
                                </div>
        
                                <div class="d-flex justify-content-between mt-3">
                                    <!-- Reinstate Button -->
                                    <div class="d-flex align-items-center">
                                        <button wire:click="reinstate({{ $student->id }})"
                                            class="btn btn-success btn-sm">
                                            Reinstate
                                        </button>
                                        <div wire:loading wire:target="reinstate({{ $student->id }})"
                                            class="spinner-border spinner-border-sm ms-2" role="status">
                                        </div>
                                    </div>
        
                                    <!-- Extend Suspension Button -->
                                    <div class="d-flex align-items-center">
                                        <button wire:click="extendSuspension({{ $student->id }})"
                                            class="btn btn-primary btn-sm">
                                            Extend
                                        </button>
                                        <div wire:loading wire:target="extendSuspension({{ $student->id }})"
                                            class="spinner-border spinner-border-sm ms-2" role="status">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-warning w-100 text-center" role="alert">
                        <i class="fas fa-exclamation-circle fa-2x text-warning mb-2"></i>
                        <h5 class="mt-2">No suspended students found!</h5>
                        <p>Suspended students will be visible here.</p>
                    </div>
                @endforelse
            </div>
        @endif
        

            {{-- Reinstate Student Logic --}}
            @if ($isReinstating)
                <div class="mt-4">
                    <h3 class="font-weight-bold mb-3">Reinstate
                    </h3>
                    <p class="mb-3">Are you sure you want to reinstate the student?</p>
                    <div class="d-flex justify-content-left align-items-center">
                        <div class="d-flex align-items-center">
                            <button wire:click="confirmReinstatement" class="btn btn-success btn-sm">
                                Yes, Reinstate
                            </button>
                            <div wire:loading wire:target="confirmReinstatement"
                                class="spinner-border spinner-border-sm ms-2" role="status"></div>
                        </div>
                        <button wire:click="$set('isReinstating', false)" class="btn btn-sm btn-danger">Cancel</button>
                    </div>
                </div>
            @endif

            {{-- Extend Suspension Logic --}}
            @if ($isExtendingSuspension)
                <div class="mt-4">
                    <h3 class="font-weight-bold mb-3">Extend Suspension</h3>

                    <label for="current_suspension_end_date" class="form-label">Current Suspension End Date:</label>
                    <div class="alert alert-info">
                        <strong>
                            {{ $student ? ($student->suspension_end_date ? $student->suspension_end_date->toFormattedDateString() : 'N/A') : 'Student not found.' }}
                        </strong>
                    </div>

                    <label for="new_suspension_end_date" class="form-label">New Suspension End Date:</label>
                    <input type="date" wire:model.live="newSuspensionEndDate" class="form-control mb-2" />

                    <div class="d-flex justify-content-left align-items-center">
                        <div class="d-flex align-items-center">
                            <button wire:click="confirmExtension" class="btn btn-primary">
                                Confirm Extension
                            </button>
                            <div wire:loading wire:target="confirmExtension"
                                class="spinner-border spinner-border-sm ms-2" role="status"></div>
                        </div>
                        <button wire:click="$set('isExtendingSuspension', false)" class="btn btn-danger">Cancel</button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
