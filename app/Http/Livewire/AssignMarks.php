<?php

namespace App\Http\Livewire;

use App\Models\ExamMarks;
use App\Models\Section;
use App\Models\Subject;
use App\Models\StudentRecord;
use Livewire\Component;

class AssignMarks extends Component
{
    public $subjectId; // Subject for which marks are assigned
    public $sectionId; // Section selected
    public $students = []; // Store student marks
    public $sections; // Available sections for the selected subject

    // Constructor to initialize properties
    public function __construct()
    {
        parent::__construct(); // Call the parent constructor if any
        $this->sections = []; // Initialize sections
    }

    // Mount method to set initial data
    public function mount($subjectId)
    {
        $this->subjectId = $subjectId;
        $this->loadSections(); // Load sections for the selected subject
    }

    // Load sections based on the subject
    public function loadSections()
    {
        $this->sections = Section::where('subject_id', $this->subjectId)->get();
    }

    // Assign marks to students
    public function assignMarks()
    {
        foreach ($this->students as $studentId => $marks) {
            ExamMarks::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'subject_id' => $this->subjectId,
                ],
                ['marks' => $marks]
            );
        }

        session()->flash('message', 'Marks assigned successfully!');
        $this->reset('students'); // Reset students after assigning marks
    }

    public function render()
    {
        $students = StudentRecord::where('section_id', $this->sectionId)->get();

        return view('livewire.assign-marks', [
            'students' => $students,
            'sections' => $this->sections,
        ]);
    }
}
