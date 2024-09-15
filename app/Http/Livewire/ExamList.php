<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Exam;
use App\Models\GradingSystem;
use App\Models\MyClass;

class ExamList extends Component
{
    public $name;
    public $term;
    public $grading_system_id;
    public $selectedClasses = [];
    public $editingExamId;
    
    public $gradingSystems;
    public $classes;
    public $exams = []; // Initialize as an empty array

    protected $rules = [
        'name' => 'required|string|max:255',
        'term' => 'required|integer',
        'grading_system_id' => 'required|integer',
        'selectedClasses' => 'required|array'
    ];

    public function mount()
    {
        $this->gradingSystems = GradingSystem::all();
        $this->classes = MyClass::all(); // Fetch all classes
        $this->exams = Exam::all()->toArray(); // Fetch all exams without pagination
    }

    public function render()
    {
        return view('livewire.exam-list');
    }

    public function addExam()
    {
        $this->validate();

        Exam::create([
            'name' => $this->name,
            'term' => $this->term,
            'grading_system_id' => $this->grading_system_id,
            'classes' => json_encode($this->selectedClasses), // Save as JSON
        ]);

        session()->flash('message', 'Exam created successfully!');
        $this->reset(['name', 'term', 'grading_system_id', 'selectedClasses']);
        $this->exams = Exam::all()->toArray(); // Update exams
    }

    public function editExam($id)
    {
        $exam = Exam::find($id);
        $this->name = $exam->name;
        $this->term = $exam->term;
        $this->grading_system_id = $exam->grading_system_id;
        $this->selectedClasses = json_decode($exam->classes, true); // Decode JSON

        $this->editingExamId = $id;
    }

    public function updateExam()
    {
        $this->validate();

        Exam::find($this->editingExamId)->update([
            'name' => $this->name,
            'term' => $this->term,
            'grading_system_id' => $this->grading_system_id,
            'classes' => json_encode($this->selectedClasses), // Save as JSON
        ]);

        session()->flash('message', 'Exam updated successfully!');
        $this->reset(['name', 'term', 'grading_system_id', 'selectedClasses']);
        $this->exams = Exam::all()->toArray(); // Update exams
    }

    public function deleteExam($id)
    {
        Exam::find($id)->delete();
        session()->flash('message', 'Exam deleted successfully!');
        $this->exams = Exam::all()->toArray(); // Update exams
    }
}
