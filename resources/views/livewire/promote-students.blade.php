<div x-data="{ isSubmitting: false }" x-init="Livewire.on('promotionError', () => { isSubmitting = false; })">
    <div>
        <div>
            <div>
                <!-- Dynamic heading based on transition type -->
                <h4 class="mb-0">
                    @if ($transitionType === 'promotion')
                        Promote Students
                    @elseif ($transitionType === 'demotion')
                        Demote Students
                    @elseif ($transitionType === 'repetition')
                        Repeat Students
                    @endif
                </h4>
            </div>
            <div>
                <!-- Display success/error messages -->
                @if (session()->has('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @endif
                @if (session()->has('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <form wire:submit.prevent="promoteStudents" @submit="isSubmitting = true">
                    <!-- Transition Type Dropdown -->
                    <div class="row mb-4 g-3" x-data="{ transitionType: '' }">
                        <div class="col-md-12">
                            <label class="form-label">Transition Type</label>
                            <div class="row">
                                <!-- Promotion Card -->
                                <div class="col-md-4">
                                    <div class="form-check card" 
                                         :style="transitionType === 'promotion' ? 'background-color: #198754; color: white;' : 'background-color: #f8f9fa; color: inherit;'">
                                        <input class="form-check-input visually-hidden" 
                                               type="radio" 
                                               wire:model.live="transitionType" 
                                               id="promotion" 
                                               value="promotion" 
                                               x-model="transitionType">
                                        <label class="card-body d-flex align-items-center justify-content-center p-3" 
                                               for="promotion" 
                                               style="cursor: pointer;">
                                            <i class="fas fa-arrow-up fa-2x"></i>
                                            <span class="ms-2">Promotion</span>
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Demotion Card -->
                                <div class="col-md-4">
                                    <div class="form-check card" 
                                         :style="transitionType === 'demotion' ? 'background-color: #dc3545; color: white;' : 'background-color: #f8f9fa; color: inherit;'">
                                        <input class="form-check-input visually-hidden" 
                                               type="radio" 
                                               wire:model.live="transitionType" 
                                               id="demotion" 
                                               value="demotion" 
                                               x-model="transitionType">
                                        <label class="card-body d-flex align-items-center justify-content-center p-3" 
                                               for="demotion" 
                                               style="cursor: pointer;">
                                            <i class="fas fa-arrow-down fa-2x"></i>
                                            <span class="ms-2">Demotion</span>
                                        </label>
                                    </div>
                                </div>
                    
                                <!-- Repetition Card -->
                                <div class="col-md-4">
                                    <div class="form-check card" 
                                         :style="transitionType === 'repetition' ? 'background-color: #ffc107; color: black;' : 'background-color: #f8f9fa; color: inherit;'">
                                        <input class="form-check-input visually-hidden" 
                                               type="radio" 
                                               wire:model.live="transitionType" 
                                               id="repetition" 
                                               value="repetition" 
                                               x-model="transitionType">
                                        <label class="card-body d-flex align-items-center justify-content-center p-3" 
                                               for="repetition" 
                                               style="cursor: pointer;">
                                            <i class="fas fa-redo fa-2x"></i>
                                            <span class="ms-2">Repetition</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @error('transitionType')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <hr class="my-4 border-0" style="height: 2px; background: linear-gradient(90deg, rgba(0,123,255,1) 0%, rgba(220,53,69,1) 50%, rgba(255,193,7,1) 100%);">
                    <!-- Class, Section, and Search Inputs -->
                    <div class="row mb-4 g-3">
                        <div class="col-md-4">
                            <label for="selectedClass" class="form-label">Select Class</label>
                            <select wire:model.live="selectedClass" id="selectedClass" class="form-select form-control">
                                <option value="">Select Class</option>
                                @foreach ($classes as $class)
                                    @if ($transitionType === 'promotion' && !$loop->last) <!-- Exclude the last class for promotion -->
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @elseif ($transitionType === 'demotion' && !$loop->first) <!-- Exclude the first class for demotion -->
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @elseif ($transitionType === 'repetition') <!-- Allow all classes for repetition -->
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('selectedClass')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="selectedSection" class="form-label">Select Section</label>
                            <select wire:model.live="selectedSection" id="selectedSection"
                                class="form-select form-control" {{ !$selectedClass ? 'disabled' : '' }}>
                                <option value="">Select Section</option>
                                @foreach ($sections as $section)
                                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                                @endforeach
                            </select>
                            @error('selectedSection')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="search" class="form-label">Search Students</label>
                            <input wire:model.live.debounce.300ms="search" type="text" class="form-control"
                                placeholder="Search by name or admission number"
                                {{ !$selectedClass || !$selectedSection ? 'disabled' : '' }}>
                        </div>
                    </div>

                    <!-- Student List Table -->
                    @if ($selectedSection)
                        <div class="row mb-4">
                            <div class="col-md-12">
                                @if ($students->isEmpty())
                                    <div class="alert alert-info">
                                        All students in this section have already been transitioned for the selected
                                        year.
                                    </div>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width: 50px;" class="text-center">Select</th>
                                                    <th>Name (Admission No)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($students as $student)
                                                    <tr>
                                                        <td class="text-center align-middle">
                                                            <input type="checkbox" wire:model.live="selectedStudents"
                                                                value="{{ $student->id }}" class="form-check-input"
                                                                style="width: 20px; height: 20px;">
                                                        </td>
                                                        <td class="align-middle">
                                                            <span class="fw-bold">{{ $student->first_name }}
                                                                {{ $student->last_name }}</span>
                                                            <span class="text-muted">({{ $student->adm_no }})</span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    {{ $students->links() }}
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Transition Details -->
                    @if (count($selectedStudents) > 0)
                        <div class="row mb-4 g-3">
                            <div class="col-md-4">
                                <label for="targetClass" class="form-label">Target Class</label>
                                <select wire:model.live="targetClass" id="targetClass" class="form-select form-control" disabled>
                                    <option value="">Select Target Class</option>
                                    @if ($targetClass)
                                        @foreach ($classes as $class)
                                            @if ($class->id == $targetClass)
                                                <option value="{{ $class->id }}" selected>{{ $class->name }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                                @error('targetClass')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="targetSection" class="form-label">Target Section</label>
                                <select wire:model.live="targetSection" id="targetSection" class="form-select form-control" {{ !$targetClass ? 'disabled' : '' }}>
                                    <option value="">Select Target Section</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                                    @endforeach
                                </select>
                                @error('targetSection')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="transitionYear" class="form-label">Transition Year</label>
                                <input wire:model="transitionYear" type="text" class="form-control"
                                    value="{{ now()->year }}" readonly>
                                @error('transitionYear')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label for="reason" class="form-label">Reason</label>
                                <textarea wire:model="reason" class="form-control" rows="3" placeholder="Enter reason for transition"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <!-- Dynamic button text based on transition type -->
                                <button type="submit" class="btn btn-success" wire:loading.attr="disabled">
                                    <!-- Default state (when not loading) -->
                                    <span wire:loading.remove>
                                        @if ($transitionType === 'promotion')
                                            Promote Selected Students
                                        @elseif ($transitionType === 'demotion')
                                            Demote Selected Students
                                        @elseif ($transitionType === 'repetition')
                                            Repeat Selected Students
                                        @endif
                                    </span>

                                    <!-- Loading state (when processing) -->
                                    <span wire:loading>
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                        <span>
                                            @if ($transitionType === 'promotion')
                                                Promoting...
                                            @elseif ($transitionType === 'demotion')
                                                Demoting...
                                            @elseif ($transitionType === 'repetition')
                                                Repeating...
                                            @endif
                                        </span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>