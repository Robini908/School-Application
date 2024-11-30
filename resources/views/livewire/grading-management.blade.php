<div class="card mt-4 col-12 p-3 shadow-lg border rounded" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">

    <x-flash-messages />
    <div class="card-body">

        @if ($isCreating || $isEditing)
            <form wire:submit="{{ $isEditing ? 'update' : 'store' }}" class="mt-4 row g-3">
                <!-- Left Column: Grading System Fields -->
                <div class="col-md-6">
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Grading System Details</h5>

                            <!-- Grading System Name -->
                            <div class="form-group mb-3">
                                <label for="name" class="form-label">Grading System Name</label>
                                <input type="text" id="name" wire:model.live="name"
                                    class="form-control @error('name') is-invalid @enderror" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="form-group mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea id="description" wire:model.live="description" class="form-control @error('description') is-invalid @enderror"
                                    rows="3" required></textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Effective Date -->
                            <div class="form-group mb-3">
                                <label for="effective_date" class="form-label">Effective Date</label>
                                <input type="date" id="effective_date" wire:model.live="effective_date"
                                    class="form-control @error('effective_date') is-invalid @enderror" required>
                                @error('effective_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Rules -->
                            <div class="form-group mb-3">
                                <label for="rules" class="form-label">Rules</label>
                                <textarea id="rules" wire:model.live="rules" class="form-control @error('rules') is-invalid @enderror"
                                    rows="5" required></textarea>
                                @error('rules')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Subject Selection -->
                <div class="col-md-6">
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <h5 class="card-title alert text-info">The grading system will apply to all subjects.</h5>

                            <!-- Select All (Disabled) -->
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="select-all"
                                    wire:model.live="selectedSubjects" value="all" checked disabled>
                                <label class="form-check-label" for="select-all">Select All Subjects</label>
                            </div>

                            <!-- Subject List -->
                            <div class="form-check">
                                @foreach ($subjects as $subject)
                                    <div class="form-check mb-1" wire:key="subject-{{ $subject->id }}">
                                        <input type="checkbox" class="form-check-input" id="subject-{{ $subject->id }}"
                                            value="{{ $subject->id }}" wire:model.live="selectedSubjects" checked
                                            disabled>
                                        <label class="form-check-label" for="subject-{{ $subject->id }}">
                                            <span
                                                class="badge bg-info text-white me-2">{{ $subject->subject_name }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('selectedSubjects')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Form Buttons -->
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">{{ $isEditing ? 'Update' : 'Create' }} Grading
                        System</button>
                    <button type="button" wire:click="cancel" class="btn btn-secondary">Cancel</button>
                </div>
            </form>
        @else
            <!-- Add Grading System Button -->


            <!-- Existing Grading Systems Header -->
            <div class="d-flex justify-content-between mt-1">
                <h3 class="mb-4 text-center"
                    style="color: #333; font-weight: bold; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);">
                    Existing Grading Systems</h3>
                <button wire:click="create" class="btn btn-primary mb-3 align-items-center">
                    New
                </button>
            </div>


            <!-- Grading Systems List -->
            <div class="row g-3">
                @foreach ($gradingSystems as $gradingSystem)
                    <div class="col-md-6">
                        {{-- <div class="card shadow-lg border-light"> --}}
                        <div class="card p-3 shadow-lg border rounded"
                            style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">


                            <h4 class="font-weight-bold text-center mb-3" style="color: #007bff;">
                                {{ $gradingSystem->name }}
                            </h4>

                            <!-- Subjects Section -->
                            <section class="border rounded p-3 mb-3"
                                style="background-color: #f1f3f5; border-color: #dcdcdc;">
                                <strong style="color: #333;">Subjects:</strong>
                                <div class="d-flex flex-wrap mt-2">
                                    @foreach ($gradingSystem->subjects as $subject)
                                        <span class="badge bg-info text-white me-3 mb-2"
                                            style="font-size: 90%;">{{ $subject->subject_name }}</span>
                                    @endforeach
                                </div>
                            </section>

                            <!-- Effective Date -->
                            <p><strong style="color: #555;">Effective Date:</strong> <span
                                    style="color: #007bff;">{{ $gradingSystem->effective_date }}</span></p>

                            <!-- Rules Section -->
                            <section class="mb-2">
                                <strong style="color: #333;">Rules:</strong>
                                <div class="overflow-auto border rounded p-2"
                                    style="background-color: #f9fafb; height: 100px; border-color: #dcdcdc;">
                                    <ol style="padding-left: 20px;">
                                        @foreach (array_filter(explode("\n", $gradingSystem->rules)) as $rule)
                                            <li style="color: #555;">{{ trim($rule) }}</li>
                                        @endforeach
                                    </ol>
                                </div>
                            </section>

                            <!-- Description Section -->
                            <section>
                                <strong style="color: #333;">Description:</strong>
                                <div class="overflow-auto border rounded p-2"
                                    style="background-color: #f9fafb; height: 100px; border-color: #dcdcdc;">
                                    <p style="color: #555;">{!! nl2br(e($gradingSystem->description)) !!}</p>
                                </div>
                            </section>

                            <!-- Action Buttons -->
                            <div class="mt-3 d-flex justify-content-between">
                                <button wire:click="edit({{ $gradingSystem->id }})" class="btn btn-warning btn-sm"
                                    style="font-weight: bold; padding: 8px 15px; background-color: #ffca28; border-color: #ffca28;">
                                    Edit
                                </button>
                                <button wire:click="delete({{ $gradingSystem->id }})" class="btn btn-danger btn-sm"
                                    style="font-weight: bold; padding: 8px 15px;">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                <nav aria-label="Page navigation">
                    <ul>
                        {{ $gradingSystems->links() }}
                    </ul>
                </nav>
            </div>
        @endif

    </div>
</div>
