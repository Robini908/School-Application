<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\MyClass;
use App\Models\StudentRecord;
use App\Models\Exam;
use App\Models\Subject;
use App\Models\GradingRange; // Add GradingRange model

class AssignBatchMarks extends Component
{
    public $classes;
    public $selectedClass = null;
    public $selectedClassName = '';
    public $exams = [];
    public $selectedExam = null;
    public $selectedExamName = '';
    public $students = [];
    public $subjects = [];
    public $marks = [];
    public $examDetails = null;
    public $gradingRanges = []; // For grading ranges
    public $showGradingRanges = false; // To control visibility

    public function mount()
    {
        $this->classes = MyClass::all();
        $this->subjects = Subject::all();
        $this->examDetails = Exam::all();
    }

    public function updatedSelectedClass($classId)
    {
        $class = MyClass::find($classId);
        $this->selectedClassName = $class ? $class->name : '';
        $this->exams = Exam::all();
        $this->students = StudentRecord::where('my_class_id', $classId)->get();
        $this->selectedExam = null;
        $this->marks = [];
    }

     // Update to hold single exam details

    public function updatedSelectedExam($examId)
    {
        // Fetch the selected exam details
        $this->examDetails = Exam::find($examId);
    
        // Fetch grading ranges based on the selected exam's grading system
        if ($this->examDetails && $this->examDetails->grading_system_id) {
            $this->gradingRanges = GradingRange::where('grading_system_id', $this->examDetails->grading_system_id)->get();
        } else {
            $this->gradingRanges = [];
        }
    }
    

    // Toggle the grading ranges visibility
    public function toggleGradingRanges()
    {
        $this->showGradingRanges = !$this->showGradingRanges;
    }

    public function assignMarks()
    {
        foreach ($this->students as $student) {
            foreach ($this->subjects as $subject) {
                $this->validate([
                    "marks.{$student->id}.{$subject->id}" => 'required|numeric|min:0|max:100',
                ]);
            }
        }

        foreach ($this->students as $student) {
            foreach ($this->subjects as $subject) {
                // Logic to save marks
            }
        }

        session()->flash('message', 'Marks successfully assigned!');
    }

    public function render()
    {
        return view('livewire.assign-batch-marks');
    }
}
