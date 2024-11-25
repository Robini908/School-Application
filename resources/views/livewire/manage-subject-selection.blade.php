<div
    style="background: linear-gradient(135deg, #f9fafb, #e5e7eb); padding: 30px; border-radius: 15px; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);">
    <h2 class="text-center mb-4"
        style="font-weight: bold; font-size: 1.8rem; color: #374151; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);">
        <i class="fas fa-cogs" style="color: #4f46e5;"></i> Manage Subject Selection for Classes
    </h2>
    <!-- Admin Deadline Set -->


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
                <button class="dropdown-item" type="button" wire:click="$set('activeAction', 'suggest')">
                    <i class="icon-lightbulb"></i> Suggest Grading Ranges
                </button>
                <button class="dropdown-item" type="button" wire:click="$set('activeAction', 'reuseDifferent')">
                    <i class="icon-undo"></i> Re-use Grading Ranges (Different Grading System)
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

    <div class="d-flex justify-content-between mb-4 align-items-center">
        <!-- Search Bar -->
        <div class="input-group w-50">
            <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
            <input type="text" wire:model.live="search" class="form-control" placeholder="Search classes..."
                style="border-radius: 0 5px 5px 0;">
        </div>
        <!-- Filter Dropdown -->
        <select wire:model.live="filter" class="form-select w-25" style="border-radius: 5px;">
            <option value="all">All Classes</option>
            <option value="selected">Selected</option>
            <option value="not_selected">Not Selected</option>
        </select>
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
                        <td colspan="3" style="text-align: center; color: #6b7280; font-style: italic;">
                            <i class="fas fa-info-circle"></i> No classes match your criteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>




</div>
