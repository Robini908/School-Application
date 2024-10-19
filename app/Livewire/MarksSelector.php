<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Exam;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\Subject;

class MarksSelector extends Component
{
    public $exams;
    public $my_classes;
    public $sections;
    public $subjects;

    public $exam_id;
    public $my_class_id;
    public $section_id;
    public $subject_id;

    public $selected = false;

    public function mount()
    {
        $this->exams = Exam::all();
        $this->my_classes = MyClass::all();
        $this->sections = collect();
        $this->subjects = collect();
    }

    public function updatedMyClassId($classId)
    {
        $this->sections = Section::where('my_class_id', $classId)->get();
        $this->subjects = Subject::where('my_class_id', $classId)->get();
        $this->section_id = null; // reset section
        $this->subject_id = null; // reset subject
    }

    public function render()
    {
        return view('livewire.marks-selector');
    }
}
