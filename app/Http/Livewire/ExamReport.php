<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Exam;
use App\Models\MyClass;
use App\Models\Section;

class ExamReport extends Component
{
    public $exam_id;
    public $my_class_id;
    public $section_id;
    public $exams = [];
    public $my_classes = [];
    public $sections = [];
    public $selected = false;

    public function mount()
    {
        // Initialize the data needed for the form
        $this->exams = Exam::all();
        $this->my_classes = MyClass::all();
    }

    public function updatedMyClassId($class_id)
    {
        $this->sections = Section::where('my_class_id', $class_id)->get();
        $this->section_id = null; // Reset section_id when class changes
    }

    public function submit()
    {
        $this->validate([
            'exam_id' => 'required|exists:exams,id',
            'my_class_id' => 'required|exists:my_classes,id',
            'section_id' => 'required|exists:sections,id',
        ]);

        // Handle form submission logic here
        // For example, batch update logic or any other processing needed

        session()->flash('message', 'Errors fixed successfully.');
    }

    public function render()
    {
        return view('livewire.exam-report');
    }
}
