<div class="container mt-1">
    <!-- Student Selection -->
    @if (Qs::userIsParent())
        @if ($students->count() > 1)
            <div class="mb-4">
                <label class="form-label fw-bold">Select Student</label>
                <div class="d-flex flex-wrap gap-3">
                    @foreach ($students as $student)
                        <div class="form-check">
                            <input type="radio" id="student-{{ $student->id }}" wire:model="selectedStudent"
                                wire:change="selectStudent({{ $student->id }})" value="{{ $student->id }}"
                                class="form-check-input">
                            <label for="student-{{ $student->id }}" class="form-check-label">
                                {{ $student->name }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        @elseif($students->count() === 1)
            <div class="alert alert-info">
                You have only one child: <strong>{{ $students->first()->name }}</strong>.
            </div>
        @endif
    @endif

    <!-- Chat Interface -->
    @if ($selectedStudent && $classTeacher)
        <div class="position-fixed bottom-0 end-0 mb-1 me-1">
            <!-- Fixed position at the bottom right corner -->
            <button wire:click="toggleChat" class="btn btn-primary" data-toggle="tooltip" data-placement="top"
                title="Chat with Teacher">
                <i class="fas fa-comments"></i> <!-- Font Awesome chat icon -->
                {{ $showChat ? 'Close Chat' : '' }} <!-- Only show text when chat is open -->
            </button>
        </div>

        @if ($showChat)
            <div class="container mt-1 p-4" style="max-width: 1000px;">
                <h2 class="mb-3 fw-bold">Chat with Class Teacher</h2>
                @livewire('chat-interface', [
                    'selectedStudent' => $selectedStudent instanceof \Illuminate\Database\Eloquent\Collection ? $selectedStudent->toArray() : $selectedStudent,
                    'classTeacher' => $classTeacher instanceof \Illuminate\Database\Eloquent\Collection ? $classTeacher->toArray() : $classTeacher,
                ])
            </div>
        @endif
    @endif

    <!-- View Mode Toggle -->
    @if (!$showChat)
        <div class="mb-4">
            <button wire:click="setViewMode('class')"
                class="btn {{ $viewMode === 'class' ? 'btn-primary' : 'btn-outline-primary' }} btn-sm me-2">Class
                View</button>
            <button wire:click="setViewMode('stream')"
                class="btn {{ $viewMode === 'stream' ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">Stream
                View</button>
        </div>

        <!-- Class Details -->
        @if ($viewMode === 'class')
            <div class="mb-4">
                <h2 class="mb-3 fw-bold">Class Details</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                    <div><strong>Class:</strong> {{ $classDetails->name ?? 'N/A' }}</div>
                    <div><strong>Class Teacher:</strong> {{ $classTeacher->name ?? 'N/A' }}</div>
                    <div><strong>Session:</strong> {{ date('Y') }}</div> <!-- Display current year/session -->
                </div>
            </div>

            <!-- Classmates -->
            <div class="mb-4">
                <h2 class="mb-3 fw-bold">Classmates</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                    @forelse($classmates as $classmate)
                        <div
                            style="border: 1px solid #e0e0e0; border-radius: 8px; padding: 1rem; background-color: #f9f9f9; transition: transform 0.2s, box-shadow 0.2s;">
                            <div>{{ $classmate->first_name }} {{ $classmate->last_name }} - {{ $classmate->adm_no }}
                            </div>
                        </div>
                    @empty
                        <div
                            style="border: 1px solid #e0e0e0; border-radius: 8px; padding: 1rem; background-color: #f9f9f9;">
                            No classmates found.
                        </div>
                    @endforelse
                </div>
                <!-- Pagination for Classmates -->
                {{ $classmates->links() }}
            </div>
        @endif

        <!-- Stream Details -->
        @if ($viewMode === 'stream')
            <div class="mb-4">
                <h2 class="mb-3 fw-bold">Stream Details</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                    <div><strong>Stream:</strong> {{ $sectionDetails->name ?? 'N/A' }}</div>
                </div>
            </div>

            <!-- Stream Mates -->
            <div class="mb-4">
                <h2 class="mb-3 fw-bold">Stream Mates</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                    @forelse($streamMates as $streamMate)
                        <div
                            style="border: 1px solid #e0e0e0; border-radius: 8px; padding: 1rem; background-color: #f9f9f9; transition: transform 0.2s, box-shadow 0.2s;">
                            <div> {{ $streamMate->first_name }} {{ $streamMate->last_name }}-
                                {{ $streamMate->adm_no }}</div>
                        </div>
                    @empty
                        <div
                            style="border: 1px solid #e0e0e0; border-radius: 8px; padding: 1rem; background-color: #f9f9f9;">
                            No stream mates found.
                        </div>
                    @endforelse
                </div>
                <!-- Pagination for Stream Mates -->
                {{ $streamMates->links() }}
            </div>
        @endif

        <!-- Subjects -->
        <div class="mb-4">
            <h2 class="mb-3 fw-bold">Subjects</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                @forelse($subjects as $subject)
                    <div
                        style="border: 1px solid #e0e0e0; border-radius: 8px; padding: 1rem; background-color: #f9f9f9; transition: transform 0.2s, box-shadow 0.2s;">
                        <div><strong>Subject:</strong> {{ $subject->name }}</div>
                    </div>
                @empty
                    <div
                        style="border: 1px solid #e0e0e0; border-radius: 8px; padding: 1rem; background-color: #f9f9f9;">
                        No subjects found.
                    </div>
                @endforelse
            </div>
        </div>
    @endif
</div>
