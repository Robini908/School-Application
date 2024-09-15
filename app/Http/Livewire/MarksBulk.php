<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\StudentRecord;

class MarksBulk extends Component
{
    public $my_classes;
    public $sections = [];
    public $students = [];

    public $my_class_id;
    public $section_id;

    public $selected = false;

    public function mount()
    {
        $this->my_classes = MyClass::all();
    }

    public function updatedMyClassId($classId)
    {
        $this->sections = Section::where('my_class_id', $classId)->get();
        $this->section_id = null; // reset section
    }

    public function updatedSectionId($sectionId)
    {
        $this->students = StudentRecord::where('section_id', $sectionId)->get();
        $this->selected = true;
    }

    public function render()
    {
        return view('livewire.marks-bulk');
    }
}
