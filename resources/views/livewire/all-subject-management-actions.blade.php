<div class="card my-4 p-2 shadow-lg border ">

    <!-- Dropdown for Action Selection -->
    <div class="list-icons mb-0">
        <div class="dropdown">
            <a href="#" class="list-icons-item" data-toggle="dropdown">
                <i class="icon-menu9"></i> More Actions
            </a>

            <div class="dropdown-menu dropdown-menu-left">
                <button class="dropdown-item" type="button" wire:click="$set('activeAction', 'reuseSame')">
                    <i class="icon-pencil"></i> Manage Subjects
                </button>
                <button class="dropdown-item" type="button" wire:click="$set('activeAction', 'suggest')">
                    <i class="icon-lightbulb"></i> Choose Classes for subject selection
                </button>
                <button class="dropdown-item" type="button" wire:click="$set('activeAction', 'reuseDifferent')">
                    <i class="icon-undo"></i> Manage Students' Subject Selection
                </button>
            </div>
        </div>
    </div>

    <!-- Conditional Rendering Based on Active Action -->
    @if ($activeAction === 'reuseSame')
    <livewire:manage-subjectss lazy />
    @elseif($activeAction === 'suggest')
        <livewire:manage-subject-selection lazy />
    @elseif($activeAction === 'reuseDifferent')
        <livewire:subject-selection-component lazy />
    @endif
  

</div>
