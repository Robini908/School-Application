<diva>
    <h2 class="text-center mb-4"
        style="font-weight: bold; font-size: 1.8rem; color: #374151; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);">
        <i class="fas fa-cogs" style="color: #4f46e5;"></i> Manage Subject Selection for Classes
    </h2>

    <!-- Dropdown for Action Selection -->
    <div class="list-icons mb-4">
        <div class="dropdown">
            <a href="#" class="list-icons-item" data-toggle="dropdown">
                <i class="icon-menu9"></i> More Actions
            </a>

            <div class="dropdown-menu dropdown-menu-left">
                <button class="dropdown-item" type="button" wire:click="$set('activeAction', 'selectDeadline')">
                    <i class="icon-pencil"></i> Set Deadline (Subject selection end date)
                </button>
            </div>
        </div>
    </div>

    <!-- Conditional Rendering Based on Active Action -->
    @if ($activeAction === 'selectDeadline')
        <div class="alert alert-info">
            @livewire('subject-selection-deadline')
        </div>
    @endif

    <!-- Search Bar -->
    <div class="d-flex justify-content-between mb-4 align-items-center">
        <div class="input-group w-50">
            <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
            <input type="text" wire:model.live="search" class="form-control" placeholder="Search classes..."
                style="border-radius: 0 5px 5px 0;">
        </div>
    </div>

    <!-- Classes Table -->
    <div style="overflow-x: auto;">
        <table class="table table-hover table-striped table-bordered"
            style="background-color: #ffffff; border-radius: 10px; overflow: hidden;">
            <thead style="background-color: #6b7280; color: #ffffff; text-align: center;">
                <tr>
                    <th>Class Name</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($filteredClasses as $class)
                    <tr>
                        <td style="text-align: center; vertical-align: middle; font-weight: bold; color: #1f2937;">
                            {{ $class->name }}
                        </td>
                        <td style="text-align: center; vertical-align: middle;">
                            @if ($settings[$class->id])
                                <span class="badge bg-success text-white"><i class="fas fa-check-circle"></i>
                                    Selected</span>
                            @else
                                <span class="badge bg-secondary text-white"><i class="fas fa-times-circle"></i> Not
                                    Selected</span>
                            @endif
                        </td>
                        <td style="text-align: center; vertical-align: middle;">
                            @if ($settings[$class->id])
                                <button class="btn btn-outline-danger btn-sm"
                                    wire:click="toggleSelection({{ $class->id }})">
                                    <i class="fas fa-times"></i> Deselect
                                </button>
                            @else
                                <button class="btn btn-outline-success btn-sm"
                                    wire:click="toggleSelection({{ $class->id }})">
                                    <i class="fas fa-check"></i> Select
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">No Classes Found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</diva>
