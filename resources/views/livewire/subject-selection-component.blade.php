<div x-data="{ showMore: false }" class="p-4 bg-light shadow-sm rounded">
    <!-- Info Alert Header -->
    <span class="alert alert-info d-flex align-items-center mb-4 border shadow-sm rounded" role="alert">
        <i class="fas fa-info-circle fa-2x text-primary me-3"></i>
        <h4 class="mb-0 font-weight-bold text-dark">Subject Selection</h4>
    </span>


    <!-- Instructions List -->
    <div class="list-group shadow-sm rounded p-3 mb-4">
        <!-- First Instruction Always Visible -->
        <div class=" list-group-item border-0 shadow-sm rounded mb-1">
            <i class="fas fa-circle-notch text-success me-2"></i>
            <strong>Select Your Class and Section:</strong> Choose the class and section to begin the subject
            selection process.
            <span x-show="!showMore" class="text-info">... <button @click="showMore = true" class="btn btn-link p-0">See
                    More</button></span>
        </div>

        <!-- Additional Instructions (Initially Hidden) -->
        <div class="list-group-item border-0 shadow-sm rounded mb-1" x-show="showMore">
            <i class="fas fa-circle-notch text-info me-2"></i>
            <strong>Select Subjects:</strong> After selecting a student, choose from the available subjects for that
            student. Compulsory subjects are automatically added.
        </div>
        <div class="list-group-item border-0 shadow-sm rounded mb-1" x-show="showMore">
            <i class="fas fa-circle-notch text-warning me-2"></i>
            <strong>Edit Subjects:</strong> You can edit the subject selection if needed. Simply re-select or deselect
            subjects as required.
        </div>
        <div class="list-group-item border-0 shadow-sm rounded mb-1" x-show="showMore">
            <i class="fas fa-circle-notch text-success me-2"></i>
            <strong>Save Your Selection:</strong> Make sure to save your subject selection once done. Your changes will
            be saved to the system.
        </div>
        <!-- See Less Button -->
        <div x-show="showMore" class="text-center mt-3">
            <button @click="showMore = false" class="btn btn-link text-info">
                <span>See Less</span>
            </button>
        </div>

    </div>
    <div class="p-4"
        style="padding-top: 20px; background-color: #f9fafb; border-radius: 8px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);">

        <!-- Displaying the student selection and class details if the student is selected and subjects are not chosen -->
        @if ($student && !$subjectsSelected)
            <div class="mb-4">
                <h3 class="text-xl font-semibold">
                    You’re about to select subjects for
                    <strong>{{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}</strong>
                    in
                    <strong>Class: {{ $student->my_class->name }}</strong>
                    |
                    <strong>Section: {{ $student->section->name }}</strong>
                </h3>
            </div>
        @endif

        <!-- Class, Section, and Student Filters in a row -->
        <div class="row mb-4">
            <!-- Class Dropdown -->
            <div class="col-md-4">
                <label for="classId" class="form-label">Class</label>
                <select id="classId" wire:model.live="classId" class="form-select form-control">
                    <option value="">Select Class</option>
                    @foreach ($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Section Dropdown -->
            <div class="col-md-4">
                <label for="sectionId" class="form-label">Section</label>
                <select id="sectionId" wire:model.live="sectionId" class="form-select form-control">
                    <option value="">Select Section</option>
                    @foreach ($sections as $section)
                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Student Dropdown -->
            <div class="col-md-4">
                <label for="studentId" class="form-label">Student</label>
                <select id="studentId" wire:model.live="studentId" class="form-select form-control">
                    <option value="">Select Student</option>
                    @foreach ($students as $student)
                        <option value="{{ $student->id }}">{{ $student->first_name }} {{ $student->last_name }}
                            ({{ $student->adm_no }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Subjects Form: Display only when no subjects are selected -->
        @if ($student && !$subjectsSelected)
            <form wire:submit.prevent="submit">
                <!-- Subjects Grid (Only Displayed if Subjects are Not Selected) -->
                @if (!$subjectsSelected)
                    <div class="row mb-4">
                        @foreach ($subjects as $subject)
                            <div class="col-md-4 mb-3">
                                <div class="card"
                                    style="background-color: #f9fafb; border: 1px solid #ddd; 
                                                @if ($subject->type === 'compulsory') opacity: 0.5; pointer-events: none; background-color: #e0f7e0; @endif">
                                    <div class="card-body">
                                        <!-- Subject Checkbox with FontAwesome -->
                                        <div class="form-check">
                                            <!-- Compulsory Subject Checkbox -->
                                            <input type="checkbox" value="{{ $subject->id }}"
                                                wire:model="selectedSubjects" id="subject{{ $subject->id }}"
                                                class="form-check-input"
                                                @if (in_array($subject->id, $selectedSubjects)) checked @endif
                                                @if ($subject->type === 'compulsory') disabled @endif
                                                style="transform: scale(1.5);">
                                            <label class="form-check-label" for="subject{{ $subject->id }}">
                                                @if ($subject->type === 'compulsory')
                                                    <i class="fas fa-check-circle"
                                                        style="color: blue; background-color: #e0f7e0; padding: 10px; border-radius: 50%;"></i>
                                                @endif
                                                {{ $subject->subject_name }}
                                            </label>
                                        </div>

                                        <!-- Subject Info -->
                                        <div class="mt-3">
                                            <strong>Type:</strong> {{ $subject->type }} <br>
                                            <strong>Abbreviation:</strong> {{ $subject->abbreviation }} <br>
                                            <strong>Prerequisite:</strong>
                                            @if ($subject->prerequisite)
                                                {{ $subject->prerequisite->subject_name }}
                                            @else
                                                None
                                            @endif
                                            <br>
                                            <strong>Category:</strong> {{ $subject->category->name }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Submit Button -->
                @if (!$subjectsSelected)
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">
                            Submit Selection
                        </button>
                    </div>
                @endif
            </form>

        @endif

        <!-- List of Selected Subjects (Displayed after submission) -->
        @if ($subjectsSelected)
            <div class="mt-4">
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    @foreach ($selectedSubjects as $subjectId)
                        @php
                            $subject = App\Models\Subject::find($subjectId);
                        @endphp
                        <div class="col">
                            <div class="card" style="background-color: #f8f9fa; border: 1px solid #ddd;">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-check-circle text-success me-2"
                                                style="font-size: 1.5rem;"></i>
                                            <strong class="text-dark"
                                                style="font-size: 1.1rem;">{{ $subject->subject_name }}</strong>
                                        </div>
                                        <div>
                                            <!-- Deregister Icon with Tooltip -->
                                            <i class="fas fa-trash-alt text-danger fs-5"
                                                wire:click="deregisterSubject({{ $subject->id }})" title="Deregister"
                                                data-bs-toggle="tooltip" data-bs-placement="top"></i>

                                            <!-- Rechoose Icon with Tooltip -->
                                            <i class="fas fa-sync-alt text-primary fs-5 ms-3"
                                                wire:click="loadRechooseOptions({{ $subject->id }})" title="Rechoose"
                                                data-bs-toggle="tooltip" data-bs-placement="top"></i>
                                        </div>
                                    </div>

                                    <!-- Rechoose Dropdown, Visible Only When Activated -->
                                    @if ($subjectId == $rechooseSubjectId)
                                        <div class="mt-3">
                                            <label for="rechoose-subject-{{ $subjectId }}"
                                                class="form-label">Choose a new subject:</label>
                                            <select id="rechoose-subject-{{ $subjectId }}" class="form-select"
                                                wire:model.live="newSubjectId"
                                                wire:change="updateSubjectSelection({{ $subjectId }})">
                                                <option value="">-- Select a Subject --</option>
                                                @foreach ($sameCategorySubjects as $newSubject)
                                                    <option value="{{ $newSubject->id }}">
                                                        {{ $newSubject->subject_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif

                                    <div class="mt-3">
                                        <small class="text-muted">Category: {{ $subject->category->name }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

</div>
