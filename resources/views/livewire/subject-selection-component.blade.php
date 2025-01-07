<div>
    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="alert alert-success" style="border-radius: 5px;">
            {{ session('success') }}
        </div>
    @elseif (session()->has('error'))
        <div class="alert alert-danger" style="border-radius: 5px;">
            {{ session('error') }}
        </div>
    @endif

    <!-- Class and Section Selection -->
    @if ($showStudentCard)
        <h2 class="text-secondary font-semibold mb-2 p-2">
            Subject selection for students for the session 
        </h2>
            <div class="mb-4 d-flex justify-content-between align-items-center" style="gap: 10px;">
                <div class="form-group" style="flex: 1;">
                    <label for="class" class="form-label font-weight-bold">Select Class</label>
                    <select id="class" wire:model.live="selectedClass" class="form-control form-select">
                        <option value="">-- Select Class --</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>

                @if (!empty($sections))
                    <div class="form-group" style="flex: 1;">
                        <label for="section" class="form-label font-weight-bold">Select Section</label>
                        <select id="section" wire:model.live="selectedSection" class="form-select form-control">
                            <option value="">-- Select Section --</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>


            @if ($selectedSection && !$unassignedStudents->isEmpty())

                <div class="mb-3">
                    @if ($selectedSection && !$unassignedStudents->isEmpty())
                        <div class="alert alert-info shadow-sm p-3 rounded" role="alert"
                            style="background-color: #f0f8ff; border-left: 5px solid #17a2b8;">
                            There are <strong style="color: #28a745;">{{ $studentCount }}</strong> students in <span
                                style="color: #28a745;">
                                {{ $classes->where('id', $selectedClass)->first()->name ?? 'Selected Class' }}
                            </span> -
                            <span style="color: #007bff;">
                                {{ $sections->where('id', $selectedSection)->first()->name ?? 'Selected Section' }}
                            </span>,
                        </div>
                    @endif
                    <div>
                        <label for="select-all" class="form-label font-weight-bold">Select Students</label>
                        <div class="d-flex align-items-center mb-3" style="gap: 10px;">
                            <!-- Select All Button -->
                            <button class="btn btn-primary btn-sm" wire:click="selectAllStudents">
                                <i class="fas fa-users"></i> Select All
                            </button>

                            <!-- Input for Number of Students -->
                            <input type="number" wire:model.live="numToSelect" class="form-control"
                                placeholder="Number of students" style="width: 150px;">

                            <!-- Select Specific Number -->
                            <button class="btn btn-secondary btn-sm" wire:click="selectSpecificStudents">
                                <i class="fas fa-user-check"></i> Select Specific
                            </button>

                            <!-- Clear Selection -->
                            @if (count($selectedStudents) > 0)
                                <button class="btn btn-danger btn-sm" wire:click="clearSelection">
                                    <i class="fas fa-times"></i> Clear
                                </button>
                            @endif
                        </div>

                        <!-- Display Selected Students Count -->
                        <div>
                            <p>Selected Students: {{ count($selectedStudents) }} / {{ $studentCount }}</p>
                        </div>
                    </div>

                </div>
            @endif

            <!-- Select All Students and Clear Selection -->
            @if ($selectedSection)
                <div class="mb-3">

                    @if ($unassignedStudents->isEmpty())
                        <div class="alert alert-success shadow-sm p-3 rounded" role="alert"
                            style="background-color: #f2fff0; border-left: 5px solid #17a2b8;">
                            All students in this section have been assigned subjects.
                        </div>
                    @else
                        <div class="alert alert-warning">
                            Some students are yet to be assigned subjects. Please select and assign subjects.
                        </div>
                    @endif
                </div>

                @if (!$unassignedStudents->isEmpty())
                    <div class="card bg-light mb-4" style="padding: 20px;">
                        <h3 class="card-title font-weight-bold mb-3">
                            Students who haven't selected subjects in
                            <span style="color: #28a745;">
                                {{ $classes->where('id', $selectedClass)->first()->name ?? 'Selected Class' }}
                            </span> -
                            <span style="color: #007bff;">
                                {{ $sections->where('id', $selectedSection)->first()->name ?? 'Selected Section' }}
                            </span>,

                        </h3>
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                            @foreach ($unassignedStudents as $student)
                                <div class="col">
                                    <div class="card p-3 border rounded d-flex align-items-center">
                                        <!-- Checkbox with wire:model to bind to selectedStudents -->
                                        <input type="checkbox" wire:model="selectedStudents"
                                            value="{{ $student->id }}" style="margin-right: 10px;">
                                        <div>
                                            <p class="mb-1 font-weight-bold" style="font-size: 14px;">
                                                {{ $student->first_name }} {{ $student->last_name }}
                                            </p>
                                            <p class="mb-0" style="font-size: 12px; color: #6c757d;">Adm No:
                                                {{ $student->adm_no }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>


                        <div class="text-center mt-3">
                            <button wire:click="showSubjectFormForStudents" class="btn btn-info btn-sm">
                                Select Checked Students
                            </button>
                        </div>
                    </div>
                @endif
            @endif
    @endif






    <!-- Subject Selection Form for Selected Students -->
    @if ($showSubjectForm)
        @if (!empty($selectedStudents))
            <div class="mb-4">
                <h4 class="font-weight-bold">Selected Students:</h4>
                <div class="d-flex flex-wrap" style="gap: 10px;">
                    @foreach ($unassignedStudents as $student)
                        @if (in_array($student->id, $selectedStudents))
                            <span class="badge bg-primary text-white d-flex align-items-center" style="padding: 10px;">
                                {{ $student->first_name }} {{ $student->last_name }}
                                <i class="fas fa-times ms-2"
                                    wire:click="removeStudentFromSelection({{ $student->id }})"
                                    style="cursor: pointer;"></i>
                            </span>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif


        <div class="mt-4">
            @php
                $groupedSubjects = \App\Models\Subject::getSubjectsGroupedByCategoryAndType();
            @endphp

            @foreach ($groupedSubjects as $type => $categories)
                <fieldset class="border p-4 mb-4">
                    <legend class="font-weight-bold">{{ ucfirst($type) }} Subjects</legend>

                    @foreach ($categories as $category => $subjects)
                        <h5 class="mt-3 text-decoration-underline">{{ $category }}</h5>
                        <div class="row g-3">
                            @foreach ($subjects as $subject)
                                <div class="col-md-4">
                                    <div class="card shadow-sm" style="border: 1px solid #ddd;">
                                        <div class="card-body text-center">
                                            <h6 class="card-title text-primary">{{ $subject->subject_name }}</h6>
                                            <p class="card-text text-muted">Code: {{ $subject->subject_code }}</p>
                                            @if ($subject->is_compulsory)
                                                <input type="checkbox" value="{{ $subject->id }}"
                                                    class="form-check-input" disabled>
                                                <i class="fas fa-check text-success ms-2"
                                                    style="font-size: 1.5rem;"></i>
                                            @else
                                                <input type="checkbox" wire:model.live="selectedSubjects"
                                                    value="{{ $subject->id }}" class="form-check-input">
                                                <label class="form-check-label ms-2">
                                                    Select
                                                </label>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </fieldset>
            @endforeach

            <div class="mt-4 text-center">
                <button wire:click="submitSubjectSelection" class="btn btn-success">
                    Save Subject Selection
                </button>
                <button wire:click="toggleShowStudentCard" class="btn btn-danger">
                    Cancel
                </button>

            </div>
        </div>
    @endif


</div>
