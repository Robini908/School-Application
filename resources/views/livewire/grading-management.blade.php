<div>
    <x-flash-messages />
    @if($isCreating || $isEditing)
    <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}" class="mt-4 row g-3">
        <!-- Left Column: Grading System Fields -->
        <div class="col-md-6">
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h5 class="card-title">Grading System Details</h5>
                    <div class="form-group mb-3">
                        <label for="name" class="form-label">Grading System Name</label>
                        <input type="text" id="name" wire:model="name" class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" wire:model="description" class="form-control @error('description') is-invalid @enderror" rows="3" required></textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="effective_date" class="form-label">Effective Date</label>
                        <input type="date" id="effective_date" wire:model="effective_date" class="form-control @error('effective_date') is-invalid @enderror" required>
                        @error('effective_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="rules" class="form-label">Rules</label>
                        <textarea id="rules" wire:model="rules" class="form-control @error('rules') is-invalid @enderror" rows="5" required></textarea>
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
                    <h5 class="card-title alert text-info">The grading system will apply to  all subjects.</h5>
                    <div class="form-check mb-2">
                        <input type="checkbox" class="form-check-input" id="select-all" 
                               wire:model="selectedSubjects" value="all" checked disabled>
                        <label class="form-check-label" for="select-all">Select All Subjects</label>
                    </div>
                    <div class="form-check">
                        @foreach($subjects as $subject)
                        <div class="form-check mb-1" wire:key="subject-{{ $subject->id }}">
                            <input type="checkbox" class="form-check-input" id="subject-{{ $subject->id }}" 
                                   value="{{ $subject->id }}" wire:model="selectedSubjects" checked disabled>
                            <label class="form-check-label" for="subject-{{ $subject->id }}">
                                <span class="badge bg-info text-white me-2">{{ $subject->subject_name }}</span>
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
        
        
        

        <div class="col-12">
            <button type="submit" class="btn btn-primary">{{ $isEditing ? 'Update' : 'Create' }} Grading System</button>
            <button type="button" wire:click="cancel" class="btn btn-secondary">Cancel</button>
        </div>
    </form>
    @else
    <button wire:click="create" class="btn btn-primary mb-3">Add Grading System</button>

    <h3>Existing Grading Systems</h3>
    <div class="row g-3">
        @foreach($gradingSystems as $gradingSystem)
        <div class="col-md-6">
            <div class="card shadow-sm rounded-lg p-4 h-100">
                <h4 class="font-weight-bold text-center mb-3">{{ $gradingSystem->name }}</h4>

                <section class="border rounded p-2 mb-3">
                    <strong>Subjects:</strong>
                    <div class="d-flex flex-wrap gap-2 mt-1">
                        @foreach($gradingSystem->subjects as $subject)
                        <span class="badge bg-info text-white">{{ $subject->subject_name }}</span>
                        @endforeach
                    </div>
                </section>

                <p><strong>Effective Date:</strong> {{ $gradingSystem->effective_date }}</p>

                <section class="mb-2">
                    <strong>Rules:</strong>
                    <div class="overflow-auto h-32 border p-2 rounded">
                        <ol class="list-decimal pl-3">
                            @foreach(array_filter(explode("\n", $gradingSystem->rules)) as $rule)
                            <li>{{ trim($rule) }}</li>
                            @endforeach
                        </ol>
                    </div>
                </section>

                <section>
                    <strong>Description:</strong>
                    <div class="overflow-auto h-32 border p-2 rounded">
                        <p>{{ nl2br(e($gradingSystem->description)) }}</p>
                    </div>
                </section>

                <div class="mt-3 d-flex">
                    <button wire:click="edit({{ $gradingSystem->id }})" class="btn btn-warning btn-sm me-2">Edit</button>
                    <button wire:click="delete({{ $gradingSystem->id }})" class="btn btn-danger btn-sm">Delete</button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $gradingSystems->links() }}
        <!-- Pagination links -->
    </div>
    @endif

    @if (session()->has('message'))
    <div class="alert alert-success mt-3">{{ session('message') }}</div>
    @endif
</div>
