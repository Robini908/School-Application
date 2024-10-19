<div>
    <h2 class="text-xl font-bold mb-4">Assign Batch Marks</h2>

    <!-- Step 1: Class Selection -->
    @if($step === 1)
        <div class="mb-4">
            <label for="class" class="block text-gray-700">Select Class</label>
            <select wire:model.live="selectedClass" id="class" class="border rounded w-full p-2">
                <option value="">-- Select Class --</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Error Message for No Students -->
        @if($errorMessage)
            <div class="bg-red-500 text-white p-2 rounded mb-4">
                {{ $errorMessage }}
            </div>
        @endif
    @endif

    <!-- Step 2: Exam Selection -->
    @if($step === 2)
        <div class="mb-4">
            <label for="exam" class="block text-gray-700">Select Exam</label>
            <select wire:model.live="selectedExam" id="exam" class="border rounded w-full p-2">
                <option value="">-- Select Exam --</option>
                @foreach($exams as $exam)
                    <option value="{{ $exam->id }}">{{ $exam->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Error Message for No Subjects -->
        @if($errorMessage)
            <div class="bg-red-500 text-white p-2 rounded mb-4">
                {{ $errorMessage }}
            </div>
        @endif
    @endif

    <!-- Step 3: Assign Marks for Subjects -->
    @if($step === 3 && $students->isNotEmpty() && $subjects->isNotEmpty())
        <table class="min-w-full border">
            <thead>
                <tr>
                    <th class="border px-4 py-2">Student Name</th>
                    <th class="border px-4 py-2">Admission Number</th>
                    @foreach($subjects as $subject)
                        <th class="border px-4 py-2">{{ $subject->subject_name }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                    <tr>
                        <td class="border px-4 py-2">{{ $student->first_name }} {{ $student->last_name }}</td>
                        <td class="border px-4 py-2">{{ $student->adm_no }}</td>
                        @foreach($subjects as $subject)
                            <td class="border px-4 py-2">
                                <input type="number" wire:model="marks.{{ $student->id }}.{{ $subject->id }}" class="border rounded p-2 w-full" placeholder="Enter marks">
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>

        <button wire:click="assignMarks" class="mt-4 btn btn-primary">Assign Marks</button>
    @endif

    <!-- Success Message -->
    @if($successMessage)
        <div class="bg-green-500 text-white p-2 rounded mb-4">
            {{ $successMessage }}
        </div>
    @endif
</div>
