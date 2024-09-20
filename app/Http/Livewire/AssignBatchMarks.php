<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\MyClass; // Assuming this is your class model
use App\Models\StudentRecord; // Assuming this is your student record model
use App\Models\Exam;
use App\Models\Subject;

class AssignBatchMarks extends Component
{
    public $classes; // List of all classes
    public $selectedClass = null; // Holds the selected class
    public $selectedClassName = ''; // Holds the name of the selected class
    public $exams = []; // List of exams for the selected class
    public $selectedExam = null; // Holds the selected exam
    public $selectedExamName = ''; // Holds the name of the selected exam
    public $students = []; // List of students for the selected class
    public $subjects = []; // List of all subjects
    public $marks = []; // To store marks for each student per subject

    

    public function mount()
    {
        // Fetch all classes on component mount
        $this->classes = MyClass::all(); // Fetch all classes
        $this->subjects = Subject::all(); // Fetch all subjects
    }

    public function updatedSelectedClass($classId)
    {
        // Fetch the selected class
        $class = MyClass::find($classId);
        $this->selectedClassName = $class ? $class->name : '';

        // Fetch all exams (you can modify this to filter exams based on the selected class if needed)
        $this->exams = Exam::all();

        // Fetch students based on the selected class using 'my_class_id'
        $this->students = StudentRecord::where('my_class_id', $classId)->get();

        // Reset the selected exam and marks
        $this->selectedExam = null;
        $this->marks = [];
    }



    public function updatedSelectedExam($examId)
    {
        // When an exam is selected, store its name
        $exam = Exam::find($examId);
        $this->selectedExamName = $exam ? $exam->name : '';
    }

    public function assignMarks()
    {
        // Validate marks
        foreach ($this->students as $student) {
            foreach ($this->subjects as $subject) {
                $this->validate([
                    "marks.{$student->id}.{$subject->id}" => 'required|numeric|min:0|max:100', // Example validation
                ]);
            }
        }

        // Loop through students and assign marks
        foreach ($this->students as $student) {
            foreach ($this->subjects as $subject) {
                // Save marks logic here (e.g., store in database)
                // Example: Mark::create([...]);
            }
        }

        // Set a success message
        session()->flash('message', 'Marks successfully assigned!');
    }

    public function render()
    {
        return view('livewire.assign-batch-marks');
    }
}
