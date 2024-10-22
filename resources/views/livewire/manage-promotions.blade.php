<div x-data="{ step: 1, search: '', selectAll: false }" class="container mt-5">
    <!-- Step 1: Filter and Select Students -->
    <div class="card" x-show="step === 1">
        <div class="card-header">
            <h4>Step 1: Filter and Select Students</h4>
        </div>
        <div class="card-body">
            <!-- Alert for already promoted streams -->
            @if (!empty($promotedStreams))
            <div class="alert alert-warning">
                <strong>Warning!</strong> Promotion has already been done for the following streams:
                <ul>
                    @foreach($promotedStreams as $stream)
                    <li>{{ $stream }}</li>
                    @endforeach
                </ul>
                <p>Number of students not promoted in the current section: <strong>{{ $notPromotedCount }}</strong></p>
            </div>
            @endif

            <!-- Filter Form -->
            <form wire:submit.prevent="selectStudents" @submit.prevent="step = 2">
                <div class="row">
                    <!-- Class Selection -->
                    <div class="col-md-6 form-group" style="margin-bottom: 15px;">
                        <label for="class">Select Class</label>
                        
                        <div class="d-flex align-items-center">
                            <select wire:model.live="selectedClass" class="form-control">
                                <option value="">Choose Class</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                
                            <!-- Spinner placed outside the select field -->
                            <div wire:loading wire:target="selectedClass" class="ml-2">
                                <i class="fas fa-spinner fa-spin text-primary" style="font-size: 1.2rem;"></i>
                            </div>
                        </div>
                        
                        @error('selectedClass')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <!-- Section Selection -->
                    @if (!empty($sections))
                        <div class="col-md-6 form-group" style="margin-bottom: 15px;">
                            <label for="section">Select Section</label>
                            
                            <div class="d-flex align-items-center">
                                <select wire:model.live="selectedSection" class="form-control">
                                    <option value="">Choose Section</option>
                                    @foreach($sections as $section)
                                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                                    @endforeach
                                </select>
                
                                <!-- Spinner placed outside the select field -->
                                <div wire:loading wire:target="selectedSection" class="ml-2">
                                    <i class="fas fa-spinner fa-spin text-primary" style="font-size: 1.2rem;"></i>
                                </div>
                            </div>
                
                            @error('selectedSection')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif
                </div>
                


                <!-- Student Selection -->
                @if (!empty($students))
                <div class="form-group" x-data="{
                    search: '',
                    selectAll: false,
                    toggleSelectAll() {
                        this.selectAll = !this.selectAll;
                        if (this.selectAll) {
                            $wire.selectAllStudents(true);
                        } else {
                            $wire.selectAllStudents(false);
                        }
                    }
                }">
                    <!-- Search Box -->
                    <div class="input-group mb-3" style="max-width: 400px;">
                        <input type="text" class="form-control" placeholder="Search students..." x-model.debounce.500ms="search"
                            style="padding: 0.5rem; font-size: 0.9rem;">
                        <div class="input-group-append">
                            <span class="input-group-text" style="background-color: #f1f1f1;">
                                <i class="fas fa-search"></i> <!-- Font Awesome search icon -->
                            </span>
                        </div>
                    </div>

                   
                    <div class="form-check mb-3 d-flex align-items-center">
                        <input type="checkbox" class="form-check-input" id="selectAll" x-model="selectAll" @click="toggleSelectAll()">
                        <label class="form-check-label ms-2" for="selectAll">Select All</label>
                    
                        <!-- Optimized Loading Spinner for Bulk Operation -->
                        <div wire:loading wire:target="selectAllStudents" class="spinner-border spinner-border-sm ms-2" role="status"></div>
                    </div>
                    
                    <!-- Student Selection Box with Optimized Search and Rendering -->
                    <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto; background-color: #f8f9fa;">
                        <!-- Search Input with Debounce -->
                    
                        <div class="row">
                            @foreach($students as $student)
                            <div class="col-md-3 mb-2" 
                                 x-show="search === '' || '{{ $student->first_name }} {{ $student->last_name }}'.toLowerCase().includes(search.toLowerCase())">
                                <div class="form-check">
                                    <!-- Student Checkbox with Debounced Livewire Update -->
                                    <input type="checkbox" wire:model.defer="selectedStudents" value="{{ $student->id }}" class="form-check-input">
                                    <label class="form-check-label">
                                        {{ $student->first_name }} {{ $student->last_name }}
                                    </label>
                    
                                    <!-- Show Spinner for Individual Student Loading -->
                                    <div wire:loading wire:target="selectedStudents.{{ $student->id }}" class="spinner-border spinner-border-sm ms-2" role="status"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    @error('selectedStudents')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                @endif

                <!-- Next Button -->
                <button type="submit" class="btn btn-primary mt-3" {{ empty($students) ? 'disabled' : '' }}>
                    Next
                    <div wire:loading wire:target="selectStudents" class="spinner-border spinner-border-sm ms-2"
                        role="status"></div>
                </button>
            </form>
        </div>
    </div>



    <!-- Step 2: Choose New Class, Section, and Academic Year -->
    <div class="card" x-show="step === 2" style="background-color: #f9f9f9;">
       
        <div class="card-body">
            <div class="alert alert-info ">
                <h4 class="">Step 2: Choose New Class, Section, and Academic Year</h4>
            </div>
            <form wire:submit.prevent="promoteStudents">
                <div class="container">
                    <!-- First Row -->
                    <div class="row mb-3">
                        <!-- New Class Selection -->
                        <div class="col-md-4">
                            <div class="form-group bg-light p-3 rounded">
                                <label for="new_class">Select New Class</label>
                                
                                <div class="d-flex align-items-center">
                                    <select wire:model.live="newClass" class="form-control">
                                        <option value="">Choose Class</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                                        @endforeach
                                    </select>
                                    
                                    <!-- Spinner placed outside the select field -->
                                    <div wire:loading wire:target="newClass" class="ml-2">
                                        <i class="fas fa-spinner fa-spin text-primary" style="font-size: 1.2rem;"></i>
                                    </div>
                                </div>
                                
                                @error('newClass')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                
                                @if ($suggestedClass)
                                    <small class="text-success">
                                        Suggested Next Class: {{ $classes->find($suggestedClass)->name }}
                                    </small>
                                @endif
                            </div>
                        </div>
                        
                        <!-- New Section Selection (conditional) -->
                        @if (!empty($newSections))
                            <div class="col-md-4">
                                <div class="form-group bg-light p-3 rounded">
                                    <label for="new_section">Select New Section</label>
                        
                                    <div class="d-flex align-items-center">
                                        <select wire:model.live="newSection" class="form-control">
                                            <option value="">Choose Section</option>
                                            @foreach($newSections as $section)
                                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                                            @endforeach
                                        </select>
                                        
                                        <!-- Spinner placed outside the select field -->
                                        <div wire:loading wire:target="newSection" class="ml-2">
                                            <i class="fas fa-spinner fa-spin text-primary" style="font-size: 1.2rem;"></i>
                                        </div>
                                    </div>
                                    
                                    @error('newSection')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        @endif
                        
    
                        <!-- Promotion Date -->
                        <div class="col-md-4">
                            <div class="form-group bg-light p-3 rounded">
                                <label for="eventDate">Promotion Date</label>
                                <input type="date" wire:model="eventDate" class="form-control">
                                @error('eventDate')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
    
                    <!-- Second Row -->
                    <div class="row mb-3">
                        <!-- Current Academic Year -->
                        <div class="col-md-4">
                            <div class="form-group bg-light p-3 rounded">
                                <label for="academicYear">Current Academic Year</label>
                                <input type="text" wire:model="academicYear" class="form-control" readonly>
                            </div>
                        </div>
    
                        <!-- Next Academic Year -->
                        <div class="col-md-4">
                            <div class="form-group bg-light p-3 rounded">
                                <label for="nextAcademicYear">Next Academic Year</label>
                                <input type="text" wire:model="nextAcademicYear" class="form-control" readonly>
                            </div>
                        </div>
    
                        <!-- Reason for Promotion -->
                        <div class="col-md-4">
                            <div class="form-group bg-light p-3 rounded">
                                <label for="reason">Reason for Promotion</label>
                                <textarea wire:model="reason" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
    
                    <!-- Third Row (Buttons) -->
                    <div class="row">
                        <div class="col-md-12 ">
                            <!-- Submit and Back Buttons -->
                            <button type="submit" class="btn btn-success">
                                Promote Students
                                <!-- Show Spinner when promoting students -->
                                <div wire:loading wire:target="promoteStudents" class="spinner-border spinner-border-sm ms-2"
                                    role="status"></div>
                            </button>
                            <button type="button" class="btn btn-secondary ms-2" @click="step = 1">Back</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
</div>