<div>
    <h1 class="my-4">Manage User Types</h1>



    <!-- Conditionally Display Form or Table -->
    @if ($showForm)
        <!-- Form for Adding/Editing User Types -->
        <div>
            <div class="card-header">
                {{ $editMode ? 'Edit User Type' : 'Add New User Type' }}
            </div>
            <div class="card-body">
                <form wire:submit.prevent="saveUserType">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" id="title" wire:model="title" class="form-control" required>
                        @error('title')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" id="name" wire:model="name" class="form-control" required>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="level" class="form-label">Level</label>
                        <input type="number" id="level" wire:model="level" class="form-control" required>
                        @error('level')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            {{ $editMode ? 'Update' : 'Save' }}
                        </button>
                        <button type="button" wire:click="resetForm" class="btn btn-secondary">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    @else
        <div class="d-flex justify-content-between align-items-center">
            <!-- Search Bar -->
            <div class="mb-4">
                <input type="text" wire:model.live="search" placeholder="Search user types..."
                    class="form-control col-md-12">
            </div>
            <div class="mb-4">
                <button wire:click="showAddForm" class="btn btn-primary">New</button>
            </div>

        </div>

        <!-- Table to Display User Types -->
        @if (empty($userTypes))
            <div class="mt-2 align-middle">
                <h2>No user found</h2>
            </div>
        @else
            <div>
                <div class="card-header">
                    User Types List
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Title</th>
                                    <th>Name</th>
                                    <th>Level</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($userTypes->count() === 0)
                                    <tr>
                                        <td colspan="7" class="text-center" style="background-color: #e9ecef;">No
                                            usertype found.</td>
                                    </tr>
                                @else
                                    @foreach ($userTypes as $userType)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $userType->title }}</td>
                                            <td>{{ $userType->name }}</td>
                                            <td>{{ $userType->level }}</td>
                                            <td>
                                                <button wire:click="edit({{ $userType->id }})"
                                                    class="btn btn-sm btn-primary">Edit</button>
                                                <button wire:click="confirmDelete({{ $userType->id }})"
                                                    class="btn btn-sm btn-danger">Delete</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination Links -->
                    <div class="mt-4">
                        {{ $userTypes->links() }}
                    </div>
                </div>
            </div>
        @endif
    @endif

    <!-- Delete Confirmation Modal -->
    @if ($showDeleteModal)
        <div class="modal fade show" tabindex="-1" style="display: block;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Delete</h5>
                        <button type="button" wire:click="$set('showDeleteModal', false)" class="btn-close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this user type? This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click="$set('showDeleteModal', false)"
                            class="btn btn-secondary">Cancel</button>
                        <button type="button" wire:click="delete" class="btn btn-danger">Delete</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>
