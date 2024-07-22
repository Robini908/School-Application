<div>
    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @elseif (session()->has('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Search Bar -->
    <div class="form-group">
        <input type="text" wire:model.debounce.300ms="search" class="form-control" placeholder="Search classes...">
    </div>

    <!-- Tabs -->
    <ul class="nav nav-tabs nav-tabs-highlight">
        <li class="nav-item">
            <a href="#all-classes" class="nav-link active" data-toggle="tab">Manage Classes</a>
        </li>
        <li class="nav-item">
            <a href="#new-class" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i> Create New Class</a>
        </li>
    </ul>

    <div class="tab-content">
        <!-- All Classes Tab -->
        <div class="tab-pane fade show active" id="all-classes">
            <table class="table datatable-button-html5-columns">
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Name</th>
                        <th>Entry</th>
                        <th>Class Master</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($my_classes as $c)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $c->name }}</td>
                            <td>{{ $students->where('user_type', 'student')->where('class_id', $c->id)->count() }}</td>
                            <td>{{ $c->teacher ? $c->teacher->name : 'No teacher assigned' }}</td>
                            <td class="text-center">
                                <div>
                                    <button wire:click="viewClass({{ $c->id }})" class="btn btn-link">View class</button>
                                </div>
                                <div class="list-icons">
                                    <div class="dropdown">
                                        <a href="#" class="list-icons-item" data-toggle="dropdown">
                                            <i class="icon-menu9"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-left">
                                            @can('update-class')
                                                <a wire:click="edit({{ $c->id }})" class="dropdown-item"><i class="icon-pencil"></i> Edit</a>
                                            @endcan
                                            @can('delete-class')
                                                <a wire:click="delete({{ $c->id }})" class="dropdown-item"><i class="icon-trash"></i> Delete</a>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $my_classes->links() }}
        </div>

        <!-- New Class Tab -->
        <div class="tab-pane fade" id="new-class">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-info border-0 alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                        <span>When a class is created, a Section will be automatically created for the class, you can edit it or add more sections to the class at <a target="_blank" href="{{ route('sections.index') }}">Manage Sections</a></span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <form wire:submit.prevent="{{ $classId ? 'update' : 'store' }}">
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label font-weight-semibold">Name <span class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <input wire:model="name" type="text" class="form-control" placeholder="Name of Class">
                                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">
                                {{ $classId ? 'Update Class' : 'Submit form' }} <i class="icon-paperplane ml-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Class Modal -->
    <div wire:ignore.self class="modal fade" id="editClassModal" tabindex="-1" role="dialog" aria-labelledby="editClassModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editClassModalLabel">Edit Class</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="update">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input wire:model="name" type="text" class="form-control" id="name" placeholder="Name of Class">
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Update Class</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div wire:ignore.self class="modal fade" id="deleteClassModal" tabindex="-1" role="dialog" aria-labelledby="deleteClassModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteClassModalLabel">Delete Class</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this class?</p>
                    <button wire:click="delete({{ $classId }})" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:load', function () {
        Livewire.on('openEditModal', () => {
            $('#editClassModal').modal('show');
        });
        
        Livewire.on('closeEditModal', () => {
            $('#editClassModal').modal('hide');
        });

        Livewire.on('openDeleteModal', () => {
            $('#deleteClassModal').modal('show');
        });

        Livewire.on('closeDeleteModal', () => {
            $('#deleteClassModal').modal('hide');
        });

        Livewire.on('refreshClasses', () => {
            window.livewire.find('{{ $this->id }}').call('render');
        });
    });
</script>
@endpush
