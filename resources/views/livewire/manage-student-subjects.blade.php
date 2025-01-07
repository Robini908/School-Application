<div>
    @if ($showStudentList)
        <!-- Search Bar -->
        <div class="input-group mb-4">
            <input type="text" wire:model.live="searchTerm" class="form-control"
                placeholder="Search students by name or subject...">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>

        <!-- Student List -->
        <div>
            @if ($students->isEmpty())
                <p class="text-muted text-center">No students found.</p>
            @else
                <div class="row row-cols-1 row-cols-md-2 g-4">
                    @foreach ($students as $student)
                        <div class="col">
                            <div class="card shadow-sm mb-3"
                                style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <div>
                                        <h5 class="card-title text-primary">{{ $student->first_name }}
                                            {{ $student->last_name }}</h5>
                                        <p class="card-text"><small class="text-muted">Subjects:
                                                {{ $student->subjects->pluck('subject_name')->join(', ') }}</small></p>
                                        @if ($student->subjects->count() < 7 || $student->subjects->count() > 8)
                                            <div class="alert alert-warning mt-2" role="alert">
                                                <i class="fas fa-exclamation-triangle"></i> {{ $student->first_name }}
                                                {{ $student->last_name }} has {{ $student->subjects->count() }}
                                                subjects. Must be between 7-8.
                                            </div>
                                        @else
                                            <p class="card-text"><small class="text-muted text-success">Total Subjects:
                                                    {{ $student->subjects->count() }}</small></p>
                                        @endif
                                        <button wire:click="selectStudent({{ $student->id }})"
                                            class="btn btn-outline-primary mt-2 align-self-end">
                                            Manage
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-3 d-flex justify-content-center">
                    {{ $students->links() }}
                </div>
            @endif
        </div>
    @endif

    @if ($showSubjectManagement && $student)
        <div class="card-header d-flex justify-content-between align-items-center bg-transparent">
            <h4 class="mb-0 text-primary">{{ $student->first_name }} {{ $student->last_name }} <span
                    class="text-secondary">is enrolled in </span> <strong>{{ $student->subjects->count() }}
                    subjects</strong></h4>


            <button wire:click="closeCard" class="btn btn-outline-secondary" data-bs-toggle="tooltip"
                data-bs-placement="top" title="Close Management">
                <i class="fas fa-times"></i> Close
            </button>
        </div>
        <div>
            <div class="row row-cols-1 row-cols-md-3 g-2">
                @foreach ($student->subjects as $subject)
                    <div class="col">

                        <div class="card col-md-12" style="background-color: #f8f9fa; border: 1px solid #ddd;">
                            <div class="card-body">
                                <div>
                                    <h5 class="card-title text-dark">
                                        {{ $subject->subject_name }} ({{ $subject->subject_code }})
                                        @if ($subject->type === 'compulsory')
                                            <span class="badge bg-success ms-2 py-2 px-3" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Compulsory Subject">
                                                <i class="fas fa-check-circle"></i> Compulsory
                                            </span>
                                        @endif
                                    </h5>

                                    @if ($rechooseSubjectId === $subject->id)
                                        <select wire:model="newSubjectId" class="form-select form-control mt-2"
                                            wire:change="updateSubjectSelection({{ $subject->id }})"
                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Select a new subject">
                                            <option value="">Select a subject</option>
                                            @foreach ($sameCategorySubjects as $option)
                                                <option value="{{ $option->id }}">{{ $option->subject_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                                @if ($subject->type === 'elective')
                                    <div class="btn-group mt-3 align-self-end">
                                        <button wire:click="loadRechooseOptions({{ $subject->id }})"
                                            class="btn btn-outline-warning" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="Rechoose this subject">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                        <button wire:click="deregisterSubject({{ $subject->id }})"
                                            class="btn btn-outline-danger" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="Deregister this subject">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
