<div>
    <x-flash-messages />

    <div class="mb-4">
        <input type="text" wire:model="search" placeholder="Search classes..." class="form-control">
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>S/N</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($my_classes as $index => $class)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $class->name }}</td>
                    <td>
                        <button wire:click="edit({{ $class->id }})" class="btn btn-warning">Edit</button>
                        <button wire:click="delete({{ $class->id }})" class="btn btn-danger">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $my_classes->links() }}

    <div class="mt-4">
        <h5>{{ $classId ? 'Edit Class' : 'Create Class' }}</h5>
        <form wire:submit.prevent="{{ $classId ? 'update' : 'create' }}">
            <div class="form-group">
                <label>Name</label>
                <input type="text" wire:model="name" class="form-control" placeholder="Class Name" required>
            </div>
            <button type="submit" class="btn btn-primary">{{ $classId ? 'Update' : 'Create' }}</button>
        </form>
    </div>
</div>
