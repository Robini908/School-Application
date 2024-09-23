<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\MyClass;
use App\Models\StudentRecord;
use App\Models\Exam;
use App\Models\Section;
use App\Models\Subject;
use App\Models\GradingRange;

class AssignBatchMarks extends Component
{
    public $classes;
    public $selectedClass = null;
    public $selectedClassName = '';
    public $exams = [];
    public $selectedExam = null;
    public $examDetails = null;
    public $sections = []; // To hold sections dynamically
    public $subjects = [];
    public $marks = [];
    public $gradingRanges = [];
    public $showGradingRanges = false;

    public function mount()
    {
        $this->classes = MyClass::all();
        $this->subjects = Subject::all();
    }

    // Fetch sections and exams after a class is selected
    public function updatedSelectedClass($classId)
    {
        $class = MyClass::find($classId);
        $this->selectedClassName = $class ? $class->name : '';

        // Fetch exams linked with the selected class via the pivot table `exam_class_section`
        $this->exams = Exam::whereHas('classes', function ($query) use ($classId) {
            $query->where('class_id', $classId);
        })->get();

        // Fetch students grouped by section
        $this->sections = StudentRecord::where('my_class_id', $classId)
            ->with('section')
            ->get()
            ->groupBy('section_id'); // Group students by section_id

        $this->selectedExam = null;
        $this->marks = [];
    }

    // Fetch students section-wise after an exam is selected
    public function updatedSelectedExam($examId)
    {
        $this->examDetails = Exam::find($examId);

        if ($this->examDetails && $this->examDetails->grading_system_id) {
            $this->gradingRanges = GradingRange::where('grading_system_id', $this->examDetails->grading_system_id)->get();
        } else {
            $this->gradingRanges = [];
        }

        if ($this->selectedClass) {
            // Fetch students grouped by section for the selected class and exam
            $this->students = StudentRecord::with('section')
                ->where('my_class_id', $this->selectedClass)
                ->orderBy('section_id')
                ->get()
                ->groupBy('section_id');
        }
    }

    public function toggleGradingRanges()
    {
        $this->showGradingRanges = !$this->showGradingRanges;
    }

    public function assignMarks()
    {
        foreach ($this->students as $sectionId => $sectionStudents) {
            foreach ($sectionStudents as $student) {
                foreach ($this->subjects as $subject) {
                    $this->validate([
                        "marks.{$student->id}.{$subject->id}" => 'required|numeric|min:0|max:100',
                    ]);
                }
            }
        }

        foreach ($this->students as $sectionId => $sectionStudents) {
            foreach ($sectionStudents as $student) {
                foreach ($this->subjects as $subject) {
                    // Save marks logic here, e.g.:
                    // StudentRecord::find($student->id)->marks()->updateOrCreate([
                    //     'subject_id' => $subject->id,
                    // ], [
                    //     'marks' => $this->marks[$student->id][$subject->id],
                    // ]);
                }
            }
        }

        session()->flash('message', 'Marks successfully assigned!');
    }

    public function render()
    {
        return view('livewire.assign-batch-marks');
    }
}
