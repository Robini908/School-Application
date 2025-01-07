@if ($showForm)
    <!-- Show Form for Creating or Editing -->
    <div style="border: 1px solid #007bff;">
        <div class="card-header bg-primary text-white">
            <h3>{{ $isEditing ? 'Edit Subject' : 'Create New Subject' }}</h3>
        </div>
        <div class="card-body" style="background-color: #f8f9fa;">
            <form wire:submit="{{ $isEditing ? 'update' : 'store' }}">
                <div class="form-group mb-3">
                    <label for="subject_name">Subject Name</label>
                    <input type="text" wire:model.live="subject_name" id="subject_name" class="form-control"
                        placeholder="Enter subject name" style="border-radius: 0.25rem;">
                    @error('subject_name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="subject_code">Subject Code</label>
                    <input type="text" wire:model.live="subject_code" id="subject_code" class="form-control"
                        placeholder="Enter subject code" style="border-radius: 0.25rem;">
                    @error('subject_code')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="abbreviation">Abbreviation</label>
                    <input type="text" wire:model.live="abbreviation" id="abbreviation" class="form-control"
                        placeholder="Enter abbreviation" style="border-radius: 0.25rem;">
                    @error('abbreviation')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Category Selection -->
                <div class="form-group mb-3">
                    <label for="category_id">Category</label>
                    <select wire:model.live="category_id" id="category_id" class="form-control"
                        style="border-radius: 0.25rem;">
                        <option value="">Select a Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>


                <!-- Informational Message with Add Button (Displayed Only During Creation) -->
                @if (!$isEditing)
                    <div class="alert alert-info mb-3"
                        style="border-radius: 0.25rem; background-color: #d9edf7; color: #31708f; transition: all 0.3s ease; padding: 10px;">
                        <strong>No category you're looking for?</strong> You can always add it by clicking this button!
                        <button type="button" wire:click="$toggle('showNewCategoryForm')"
                            class="btn btn-primary btn-sm float-end ms-2" data-toggle="tooltip" data-placement="top"
                            title="Add a new category">
                            <i class="fas fa-plus"></i> Add
                        </button>
                    </div>
                @endif

                <!-- Card for Managing Categories (Only Display During Creation) -->
                @if ($showNewCategoryForm && !$isEditing)
                    <div class="p-4 border rounded shadow mb-3" style="background-color: #e9ecef;">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-4">Manage Categories</h5>
                            <button type="button" wire:click="$set('showNewCategoryForm', false)"
                                class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Close">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="row">
                            <!-- Left Grid for Adding Categories -->
                            <div class="col-md-6 mb-4">
                                <!-- New Category Input -->
                                <div class="form-group mb-3">
                                    <label for="new_category">New Category Name</label>
                                    <input type="text" wire:model.live="new_category" id="new_category"
                                        class="form-control" placeholder="Enter new category"
                                        style="border-radius: 0.25rem;">
                                    <button type="button" wire:click="addCategory" class="btn btn-success mt-2"
                                        wire:loading.attr="disabled" data-toggle="tooltip" data-placement="top"
                                        title="Save the new category">
                                        <i class="fas fa-save"></i> Save
                                        <span wire:loading wire:target="addCategory"
                                            class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                    </button>
                                    @error('new_category')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Right Grid for Displaying Categories -->
                            <div class="col-md-6 mb-4">
                                <h6 class="mb-3">Available Categories</h6>
                                <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                                    <table class="table table-bordered table-hover fixedHeader">
                                        <thead>
                                            <tr>
                                                <th scope="col">Category Name</th>
                                                <th scope="col" class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($categories as $category)
                                                <tr>
                                                    <td>
                                                        @if ($editingCategoryId === $category->id)
                                                            <div class="input-group">
                                                                <input type="text" wire:model.live="edit_category"
                                                                    class="form-control"
                                                                    wire:keydown.enter="updateCategory"
                                                                    wire:keydown.escape="cancelEdit"
                                                                    placeholder="Edit category name" />

                                                            </div>
                                                        @else
                                                            <span>{{ $category->name }}</span>
                                                        @endif
                                                    </td>
                                                  
                                                    <td class="text-center">
                                                        @if ($editingCategoryId !== $category->id)
                                                            <button type="button"
                                                                wire:click="editCategory({{ $category->id }})"
                                                                class="btn btn-light btn-sm" data-toggle="tooltip"
                                                                data-placement="top" title="Edit category">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <button type="button"
                                                                wire:click="removeCategory({{ $category->id }})"
                                                                class="btn btn-danger btn-sm" data-toggle="tooltip"
                                                                data-placement="top" title="Remove category">
                                                                <i class="fas fa-trash-alt"></i>
                                                                <!-- Trash icon for removal -->
                                                            </button>
                                                        @endif
                                                        @if ($editingCategoryId === $category->id)

                                                        <button type="button" wire:click="updateCategory"
                                                            class="btn btn-warning btn-sm" data-toggle="tooltip"
                                                            data-placement="top" title="Update category">
                                                            <i class="fas fa-sync-alt"></i> 
                                                            <span wire:loading wire:target="updateCategory"
                                                                class="spinner-border spinner-border-sm"
                                                                role="status" aria-hidden="true"></span>
                                                        </button>
                                                        @endif
                                                    </td>

                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                @endif
                <div class="mt-2">
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                        {{ $isEditing ? 'Update' : 'Save' }}
                        <div wire:loading wire:target="{{ $isEditing ? 'update' : 'store' }}"
                            class="spinner-border spinner-border-sm ms-2" role="status"></div>
                    </button>
                    <button type="button" wire:click="cancel" class="btn btn-secondary">
                        Cancel
                    </button>


                </div>
                <div wire:dirty class="alert alert-warning"
                    style="font-size: 14px; font-weight: bold; color: #856404; background-color: #fff3cd; border: 1px solid #ffeeba; border-radius: 5px; padding: 10px; margin: 10px 0;">
                    <i class="bi bi-exclamation-circle-fill" style="margin-right: 5px; color: #856404;"></i>
                    Unsaved changes...
                </div>

            </form>
        </div>
    </div>
@else
    <!-- Show Table of Subjects -->
    <div class="mt-0">
        <div class="card-header justify-content-between d-flex align-items-center">
            <!-- Filter dropdown for categories -->
            <div class="float-end">
                <select wire:model.live="selectedCategory" wire:change="filterByCategory($event.target.value)"
                    class="form-control">
                    <option value="">All Subjects</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <button wire:click="create" class="btn btn-primary mb-2" wire:loading.attr="disabled">
                New
                <div wire:loading wire:target="create" class="spinner-border spinner-border-sm ms-2" role="status">
                </div>
            </button>
        </div>

        <div class="table-responsive">
            <!-- Table for all subjects or filtered subjects -->
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Subject Name</th>
                        <th scope="col">Code</th>
                        <th scope="col">Abbreviation</th>
                        <th scope="col">Category</th>
                        <th scope="col">Type</th> <!-- Added type column -->
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $subjectsToDisplay = $selectedCategory ? $filteredSubjects : $allSubjects;
                    @endphp

                    @foreach ($subjectsToDisplay as $subject)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $subject->subject_name }}</td>
                            <td>{{ $subject->subject_code }}</td>
                            <td>{{ $subject->abbreviation }}</td>
                            <td>{{ optional($subject->category)->name ?? 'No Category' }}</td>

                            <!-- Type dropdown -->
                            <td class="relative">
                                <!-- Type dropdown -->
                                <select wire:change="updateType({{ $subject->id }}, $event.target.value)"
                                    class="form-control" wire:model="subject.type">
                                    <option value="compulsory" @if ($subject->type === 'compulsory') selected @endif>
                                        Compulsory</option>
                                    <option value="elective" @if ($subject->type === 'elective') selected @endif>Elective
                                    </option>
                                </select>

                                <!-- Success message when the type is saved -->
                                @if (session()->has('type_saved_{{ $subject->id }}'))
                                    <div class="absolute right-0 top-0 mt-2 mr-2 text-sm text-green-500">
                                        Saved
                                    </div>
                                @endif
                            </td>


                            <td class="text-center">
                                <button wire:click="edit({{ $subject->id }})" class="btn btn-link p-0"
                                    wire:loading.attr="disabled" data-toggle="tooltip" data-placement="top"
                                    title="Edit">
                                    <i class="fas fa-edit"></i> <!-- Edit icon -->
                                    <div wire:loading wire:target="edit({{ $subject->id }})"
                                        class="spinner-border spinner-border-sm ms-2" role="status"></div>
                                </button>
                                <button wire:click="delete({{ $subject->id }})" class="btn btn-link p-0 text-danger"
                                    wire:loading.attr="disabled" data-toggle="tooltip" data-placement="top"
                                    title="Delete">
                                    <i class="fas fa-trash-alt"></i> <!-- Trash icon for delete -->
                                    <div wire:loading wire:target="delete({{ $subject->id }})"
                                        class="spinner-border spinner-border-sm ms-2" role="status"></div>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
