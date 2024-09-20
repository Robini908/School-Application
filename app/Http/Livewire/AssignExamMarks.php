<?php

namespace App\Http\Livewire;

use App\Models\StudentRecord;
use App\Models\ExamRecord;
use App\Models\MyClass;
use App\Models\Subject;
use App\Models\Exam;
use Livewire\Component;

class AssignExamMarks extends Component
{
    public $selectedClass;
    public $selectedExam;
    public $students;
    public $subjects;
    public $marks = [];
    public $successMessage;
    public $errorMessage;

    public $step = 1;  // For controlling the steps (1 for class selection, 2 for exam selection)

    /**
     * Mount the component and initialize data.
     */
    public function mount()
    {
        $this->students = collect();
        $this->subjects = collect();
        $this->selectedClass = '';
        $this->selectedExam = '';
    }

    /**
     * Step 1: Class Selection
     */
    public function updatedSelectedClass()
    {
        if ($this->selectedClass) {
            $this->students = StudentRecord::where('my_class_id', $this->selectedClass)->get();

            // Check if students are found in the class
            if ($this->students->isEmpty()) {
                $this->errorMessage = 'No students found for the selected class.';
            } else {
                $this->errorMessage = null;
                $this->step = 2;  // Proceed to Exam Selection
            }
        }
    }

    /**
     * Step 2: Exam Selection
     */
    public function updatedSelectedExam()
    {
        if ($this->selectedExam && $this->selectedClass) {
            // Fetch subjects for the selected class
            $this->subjects = Subject::whereHas('my_class', function ($query) {
                $query->where('id', $this->selectedClass);
            })->get();

            // Proceed to step 3 if subjects are found
            if ($this->subjects->isEmpty()) {
                $this->errorMessage = 'No subjects found for the selected class.';
            } else {
                $this->errorMessage = null;
                $this->step = 3;  // Proceed to mark assignment
            }
        }
    }

    /**
     * Assign marks to students for all subjects.
     */
    public function assignMarks()
    {
        if ($this->students->isEmpty()) {
            $this->errorMessage = 'No students found in the selected class.';
            return;
        }

        foreach ($this->students as $student) {
            foreach ($this->subjects as $subject) {
                if (isset($this->marks[$student->id][$subject->id])) {
                    // Save the marks for each student and subject for the selected exam
                    ExamRecord::updateOrCreate(
                        [
                            'student_id' => $student->id,
                            'my_class_id' => $this->selectedClass,
                            'exam_id' => $this->selectedExam,
                            'subject_id' => $subject->id
                        ],
                        [
                            'total' => $this->marks[$student->id][$subject->id],
                            'year' => date('Y')
                        ]
                    );
                }
            }
        }

        $this->successMessage = 'Marks successfully assigned for all students.';
        $this->reset(['marks']);  // Reset marks after submission
    }

    public function render()
    {
        // Fetch the list of classes for dropdown
        $classes = MyClass::all();
        // Fetch the list of exams for dropdown (Step 2)
        $exams = $this->selectedClass ? Exam::where('my_class_id', $this->selectedClass)->get() : collect();

        return view('livewire.assign-batch-marks', [
            'classes' => $classes,
            'exams' => $exams,
        ]);
    }
}
