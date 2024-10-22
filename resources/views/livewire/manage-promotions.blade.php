<div x-data="{ step: 1, search: '', selectAll: false }" class="container mt-5">
    <!-- Step 1: Filter and Select Students -->
    <div class="card" x-show="step === 1">
        <div class="card-header">
            <h4>Step 1: Filter and Select Students</h4>
        </div>
        <div class="card-body">


            <!-- Step 1 Form: Filter by Class, Section, and Select Students -->
            <form wire:submit.prevent="selectStudents" @submit="step = 2">
                <!-- Class Selection -->
                <div class="form-group">
                    <label for="class">Select Class</label>
                    <select wire:model.live="selectedClass" class="form-control">
                        <option value="">Choose Class</option>
                        @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedClass')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Section Selection (conditional) -->
                @if (!empty($sections))
                <div class="form-group">
                    <label for="section">Select Section</label>
                    <select wire:model.live="selectedSection" class="form-control">
                        <option value="">Choose Section</option>
                        @foreach($sections as $section)
                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedSection')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                @endif

                <!-- Student Selection (conditional) -->
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
                    <label for="student">Select Students</label>

                    <!-- Search Input -->
                    <input type="text" placeholder="Search students..." class="form-control mb-3" x-model="search" />

                    <!-- Select All Checkbox -->
                    <div class="form-check mb-3 d-flex align-items-center">
                        <input type="checkbox" class="form-check-input" id="selectAll" x-model="selectAll"
                            @click="toggleSelectAll()">
                        <label class="form-check-label ms-2" for="selectAll">Select All</label>

                        <!-- Show Spinner when "Select All" is being processed -->
                        <div wire:loading wire:target="selectAllStudents" class="spinner-border spinner-border-sm ms-2"
                            role="status"></div>
                    </div>

                    <div class="border rounded p-3" style="max-height: 400px; overflow-y: auto;">
                        <!-- List of Students -->
                        <div class="row">
                            @foreach($students as $student)
                            <div class="col-md-3"
                                x-show="search === '' || '{{ $student->first_name }} {{ $student->last_name }}'.toLowerCase().includes(search.toLowerCase())">
                                <div class="form-check">
                                    <!-- Individual checkboxes for students -->
                                    <input type="checkbox" wire:model.live="selectedStudents" value="{{ $student->id }}"
                                        class="form-check-input">
                                    <label class="form-check-label">
                                        {{ $student->first_name }} {{ $student->last_name }}
                                    </label>

                                    <!-- Show Spinner when the specific student is being processed -->
                                    <div wire:loading wire:target="selectedStudents.{{ $student->id }}"
                                        class="spinner-border spinner-border-sm ms-2" role="status"></div>
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
                <button type="submit" class="btn btn-primary" {{ empty($students) ? 'disabled' : '' }}>
                    Next
                    <!-- Show Spinner when promoting students -->
                    <div wire:loading wire:target="selectStudents" class="spinner-border spinner-border-sm ms-2"
                        role="status"></div>
                </button>
            </form>
        </div>

        <div class="card-header">
            <h4>Students Not Promoted</h4>
        </div>
        {{-- <div class="card-body">
            @if ($notPromotedStudents->isEmpty())
            <div class="alert alert-warning">No students found who were not promoted.</div>
            @else
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Class</th>
                            <th>Section</th>
                            <th>Reason for Not Promotion</th>
                            <th>Actions</th> <!-- New column for actions -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($notPromotedStudents as $index => $student)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $student->first_name }}</td>
                            <td>{{ $student->last_name }}</td>
                            <td>{{ $student->class->name ?? 'N/A' }}</td>
                            <td>{{ $student->section->name ?? 'N/A' }}</td>
                            <td>{{ $student->not_promotion_reason }}</td>
                            <td>
                                <!-- Placeholder buttons for demote and repeat actions -->
                                <button wire:click="demoteStudent({{ $student->id }})" class="btn btn-danger btn-sm"
                                    title="Demote Student">
                                    Demote
                                </button>
                                <button wire:click="repeatStudent({{ $student->id }})" class="btn btn-warning btn-sm"
                                    title="Repeat Student">
                                    Repeat
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
        --}}
    </div>


    <!-- Step 2: Choose New Class, Section, and Academic Year -->
    <div class="card" x-show="step === 2">
        <div class="card-header">
            <h4>Step 2: Choose New Class, Section, and Academic Year</h4>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="promoteStudents">
                <!-- New Class Selection -->
                <div class="form-group">
                    <label for="new_class">Select New Class</label>
                    <select wire:model.live="newClass" class="form-control">
                        <option value="">Choose Class</option>
                        @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                    @error('newClass')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror

                    @if ($suggestedClass)
                    <small class="text-success">
                        Suggested Next Class: {{ $classes->find($suggestedClass)->name }}
                    </small>
                    @endif
                </div>

                <!-- New Section Selection (conditional) -->
                @if (!empty($newSections))
                <div class="form-group">
                    <label for="new_section">Select New Section</label>
                    <select wire:model.live="newSection" class="form-control">
                        <option value="">Choose Section</option>
                        @foreach($newSections as $section)
                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                    @error('newSection')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                @endif

                <!-- Promotion Date -->
                <div class="form-group">
                    <label for="eventDate">Promotion Date</label>
                    <input type="date" wire:model.live="eventDate" class="form-control">
                    @error('eventDate')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Academic Years -->
                <div class="form-group">
                    <label for="academicYear">Current Academic Year</label>
                    <input type="text" wire:model.live="academicYear" class="form-control" readonly>
                </div>

                <div class="form-group">
                    <label for="nextAcademicYear">Next Academic Year</label>
                    <input type="text" wire:model.live="nextAcademicYear" class="form-control" readonly>
                </div>

                <!-- Reason for Promotion -->
                <div class="form-group">
                    <label for="reason">Reason for Promotion</label>
                    <textarea wire:model.live="reason" class="form-control"></textarea>
                </div>

                <!-- Submit and Back Buttons -->
                <button type="submit" class="btn btn-success">
                    Promote Students
                    <!-- Show Spinner when promoting students -->
                    <div wire:loading wire:target="promoteStudents" class="spinner-border spinner-border-sm ms-2"
                        role="status"></div>
                </button>
                <button type="button" class="btn btn-secondary" @click="step = 1">Back</button>

            </form>
        </div>
    </div>
</div>