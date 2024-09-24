<div class="p-4">
    <h2 class="text-lg font-semibold mb-4">Assign Marks</h2>

    @if (session()->has('message'))
        <div class="mb-4 text-green-600">
            {{ session('message') }}
        </div>
    @endif

    <div class="mb-4">
        <label for="class" class="block">Class</label>
        <select wire:model="selectedClass" id="class" class="form-select">
            <option value="">Select Class</option>
            @foreach($classes as $class)
                <option value="{{ $class->id }}">{{ $class->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-4">
        <label for="exam" class="block">Exam</label>
        <select wire:model="selectedExam" id="exam" class="form-select">
            <option value="">Select Exam</option>
            @foreach($exams as $exam)
                <option value="{{ $exam->id }}">{{ $exam->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-4">
        <label for="subject" class="block">Subject</label>
        <select wire:model="selectedSubject" id="subject" class="form-select">
            <option value="">Select Subject</option>
            @foreach($subjects as $subject)
                <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-4">
        <label for="section" class="block">Section</label>
        <select wire:model="selectedSection" id="section" class="form-select">
            <option value="">Select Section</option>
            @foreach($this->sections ?? [] as $section)
                <option value="{{ $section->id }}">{{ $section->name }}</option>
            @endforeach
        </select>
    </div>

    @if ($students)
        <h3 class="text-lg font-semibold mt-4">Students</h3>
        <table class="min-w-full mt-4 border">
            <thead>
                <tr>
                    <th class="border">Student Name</th>
                    <th class="border">Marks</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                    <tr>
                        <td class="border">{{ $student->first_name }} {{ $student->last_name }}</td>
                        <td class="border">
                            <input type="number" wire:model.defer="marks.{{ $student->id }}" class="form-input" />
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="mt-4">
        <button wire:click="assignMarks" class="bg-blue-500 text-white px-4 py-2 rounded">
            Assign Marks
        </button>
    </div>
</div>
