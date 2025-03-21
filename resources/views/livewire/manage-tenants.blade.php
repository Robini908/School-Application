<div>
    <div class="container" style="margin: 0 auto; padding: 0 15px;">
        <div class="py-8">
            <div>
                <h2 style="font-size: 1.5rem; font-weight: 600; margin-bottom: 1rem;">Manage Tenants</h2>
            </div>
            <div class="my-2 d-flex flex-column flex-sm-row">
                <div class="block relative">
                    <button wire:click="create" class="btn btn-primary">
                        Create New Tenant
                    </button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th
                                style="text-align: left; font-size: 0.875rem; font-weight: 600; text-transform: uppercase; background-color: #f8f9fa;">
                                School Name
                            </th>
                            <th
                                style="text-align: left; font-size: 0.875rem; font-weight: 600; text-transform: uppercase; background-color: #f8f9fa;">
                                Domain
                            </th>
                            <th
                                style="text-align: left; font-size: 0.875rem; font-weight: 600; text-transform: uppercase; background-color: #f8f9fa;">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tenants as $tenant)
                            <tr>
                                <td>{{ $tenant->school_name }}</td>
                                <td>
                                    @if($tenant->domains->isNotEmpty())
                                        {{ $tenant->domains->first()->domain }}
                                    @else
                                        No domain assigned
                                    @endif
                                </td>
                                <td>
                                    <button wire:click="edit('{{ $tenant->id }}')" class="btn btn-success btn-sm">
                                        Edit
                                    </button>
                                    <button wire:click="delete('{{ $tenant->id }}')" class="btn btn-danger btn-sm">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-between align-items-center p-3 bg-white border-top">
                    {{ $tenants->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    @if ($isOpen)
        <div style="position: fixed; z-index: 10; inset: 0; overflow-y: auto;">
            <div
                style="display: flex; align-items: flex-end; justify-content: center; min-height: 100vh; padding: 1rem; text-align: center;">
                <div style="position: fixed; inset: 0; background-color: rgba(0, 0, 0, 0.5);"></div>
                <span style="display: inline-block; vertical-align: middle; height: 100vh;"></span>
                <div
                    style="display: inline-block; text-align: left; vertical-align: middle; background-color: white; border-radius: 0.5rem; box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15); transform: translateY(0); transition: transform 0.3s ease-out; width: 100%; max-width: 500px;">
                    <div style="padding: 1.25rem;">
                        <div style="display: flex; align-items: flex-start;">
                            <div style="margin-top: 0.75rem; text-align: left; width: 100%;">
                                <h3 style="font-size: 1.25rem; font-weight: 500; margin-bottom: 1rem;">
                                    {{ $tenant_id ? 'Edit Tenant' : 'Create Tenant' }}
                                </h3>
                                <div style="margin-top: 0.5rem;">
                                    <form>
                                        <div style="margin-bottom: 1rem;">
                                            <label for="school_name"
                                                style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.5rem;">School
                                                Name:</label>
                                            <input type="text"
                                                style="width: 100%; padding: 0.375rem 0.75rem; border: 1px solid #ced4da; border-radius: 0.25rem;"
                                                id="school_name" wire:model="school_name">
                                            @error('school_name')
                                                <span style="color: red; font-size: 0.875rem;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div style="margin-bottom: 1rem;">
                                            <label for="domain"
                                                style="display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.5rem;">Domain:</label>
                                            <input type="text"
                                                style="width: 100%; padding: 0.375rem 0.75rem; border: 1px solid #ced4da; border-radius: 0.25rem;"
                                                id="domain" wire:model="domain">
                                            @error('domain')
                                                <span style="color: red; font-size: 0.875rem;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="padding: 1rem; background-color: #f8f9fa; display: flex; justify-content: flex-end;">
                        <button wire:click.prevent="store"
                            style="margin-left: 0.5rem; padding: 0.375rem 0.75rem; background-color: #0d6efd; color: white; border: none; border-radius: 0.25rem;">
                            {{ $tenant_id ? 'Update' : 'Create' }}
                        </button>
                        <button wire:click="closeModal"
                            style="margin-left: 0.5rem; padding: 0.375rem 0.75rem; background-color: #6c757d; color: white; border: none; border-radius: 0.25rem;">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
