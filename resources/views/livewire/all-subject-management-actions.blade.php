<div class="card max-h-screen p-3 shadow-lg border rounded"
    style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
    <!-- Card Header with Title and Dropdown -->
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            {{-- Card Title --}}
            {{-- <h5 class="card-title m-0 text-primary fw-bold">Manage Actions</h5> --}}



            {{-- Dropdown Menu --}}
            <div class="dropdown">
                <a href="#" class="btn btn-light border shadow-sm rounded-circle p-2" data-toggle="dropdown"
                    style="display: inline-flex; align-items: center;">
                    <i class="icon-menu9" style="font-size: 1.2rem; color: #6c757d;"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end shadow-lg rounded"
                    style="padding: 0.5rem; border: 1px solid #e5e7eb; background-color: #f9fafb;">
                    <!-- Manage Subjects -->
                    <button class="dropdown-item d-flex align-items-center" type="button"
                        wire:click="$set('activeAction', 'reuseSame')"
                        style="padding: 0.75rem; border-radius: 0.5rem; transition: all 0.3s; display: flex; align-items: center; gap: 0.75rem;">
                        <i class="fas fa-book" style="color: #3b82f6; font-size: 1.25rem;"></i>
                        <span style="font-weight: 600; font-size: 1rem; color: #374151;">Choose Classes for Subject
                            Selection</span>
                    </button>

                    <!-- Choose Classes for Subject Selection -->
                    <button class="dropdown-item d-flex align-items-center" type="button"
                        wire:click="$set('activeAction', 'suggest')"
                        style="padding: 0.75rem; border-radius: 0.5rem; transition: all 0.3s; display: flex; align-items: center; gap: 0.75rem;">
                        <i class="fas fa-chalkboard-teacher" style="color: #f59e0b; font-size: 1.25rem;"></i>
                        <span style="font-weight: 600; font-size: 1rem; color: #374151;">Students' Subject
                            Selection</span>
                    </button>

                    <!-- Manage Students' Subject Selection -->
                    <button class="dropdown-item d-flex align-items-center" type="button"
                        wire:click="$set('activeAction', 'reuseDifferent')"
                        style="padding: 0.75rem; border-radius: 0.5rem; transition: all 0.3s; display: flex; align-items: center; gap: 0.75rem;">
                        <i class="fas fa-user-graduate" style="color: #10b981; font-size: 1.25rem;"></i>
                        <span style="font-weight: 600; font-size: 1rem; color: #374151;">Manage Subject Selection</span>
                    </button>
                </div>
            </div>
            @if ($activeAction)
                <button wire:click="$set('activeAction', null)" class="btn btn-light rounded-circle p-2" title="Close">
                    <i class="fas fa-chevron-left" style="font-size: 1.25rem; color: #6c757d;"></i>
                </button>
            @endif
        </div>

        <!-- Divider -->
        <hr class="mt-0 mb-3" style="border-top: 2px solid #dee2e6;">

        <!-- Dynamic Content Section -->
        <div>
            @if ($activeAction === 'reuseSame')
                <livewire:manage-subject-selection lazy />
            @elseif($activeAction === 'suggest')
                <livewire:subject-selection-component lazy />
            @elseif($activeAction === 'reuseDifferent')
                <livewire:manage-student-subjects lazy />
            @else
                <livewire:manage-subjectss lazy />
            @endif
        </div>
    </div>
</div>
