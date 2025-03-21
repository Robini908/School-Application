<div>
    <!-- Flash Message -->
    @if (session()->has('message'))
        <div class="alert alert-success mt-3">
            {{ session('message') }}
        </div>
    @endif

    <div class="card shadow-lg border rounded">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Manage Sections</h4>
        </div>
        <div class="card-body">
            <!-- Search Bar -->
            <div class="mb-4">
                <input type="text" wire:model.live="search" class="form-control" placeholder="Search sections...">
            </div>

            <!-- Add New Section Button -->
            <button wire:click="$toggle('showForm')" class="btn btn-success mb-4">
                <i class="fas fa-plus"></i> Add New Section
            </button>

            <!-- Add/Edit Form -->
            @if ($showForm)
                <div class="mb-4">
                    <form wire:submit.prevent="{{ $editSectionId ? 'updateSection' : 'addSection' }}">
                        <div class="form-group">
                            <label for="name">Section Name</label>
                            <input type="text" wire:model.live="name" class="form-control" placeholder="Enter section name">
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">
                                {{ $editSectionId ? 'Update Section' : 'Add Section' }}
                            </button>
                            <button type="button" wire:click="resetForm" class="btn btn-secondary">Cancel</button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- Sections Table -->
            @if ($sections->isEmpty())
                <div class="alert alert-info">
                    No sections found. Start by adding a new section.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Section Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sections as $section)
                                <tr>
                                    <td>
                                        @if ($editing[$section->id] ?? false)
                                            <form wire:submit.prevent="updateSection({{ $section->id }})">
                                                <div class="form-group mb-0">
                                                    <input type="text" wire:model.live="name" class="form-control form-control-sm" placeholder="Edit section name">
                                                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                            </form>
                                        @else
                                            {{ $section->name }}
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <!-- Edit Button -->
                                            @if ($editing[$section->id] ?? false)
                                                <button wire:click="updateSection({{ $section->id }})" class="btn btn-sm btn-success" title="Save">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button wire:click="resetForm" class="btn btn-sm btn-secondary" title="Cancel">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @else
                                                <button wire:click="editSection({{ $section->id }})" class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            @endif

                                            <!-- Delete Button -->
                                            <button wire:click="deleteSection({{ $section->id }})" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this section?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Include Alpine.js -->
