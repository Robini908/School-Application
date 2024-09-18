<?php

namespace App\Http\Livewire;

use App\Models\Exam;
use App\Models\GradingSystem;
use Livewire\Component;

class ExamManagementComponent extends Component
{
    public $exams;              // To store list of exams
    public $name;               // Exam name input
    public $term;               // Exam term input
    public $year;               // Exam year input
    public $grading_system_id;  // Selected grading system
    public $examId;             // To identify which exam to update

    // Validation rules
    protected $rules = [
        'name' => 'required|string|max:255',
        'term' => 'required|string',
        'year' => 'required|numeric',
        'grading_system_id' => 'required|exists:grading_systems,id',
    ];

    public function mount()
    {
        $this->exams = Exam::with('GradingSytem')->get();  // Fetch exams with grading system
    }

    public function addExam()
    {
        $this->validate();  // Validate inputs

        Exam::create([
            'name' => $this->name,
            'term' => $this->term,
            'year' => $this->year,
            'grading_system_id' => $this->grading_system_id,
        ]);

        $this->resetForm();  // Clear form fields after saving
        $this->exams = Exam::with('GradingSytem')->get();  // Refresh exam list
    }

    public function editExam($id)
    {
        $exam = Exam::findOrFail($id);
        $this->examId = $exam->id;
        $this->name = $exam->name;
        $this->term = $exam->term;
        $this->year = $exam->year;
        $this->grading_system_id = $exam->grading_system_id;
    }

    public function updateExam()
    {
        $this->validate();

        if ($this->examId) {
            $exam = Exam::findOrFail($this->examId);
            $exam->update([
                'name' => $this->name,
                'term' => $this->term,
                'year' => $this->year,
                'grading_system_id' => $this->grading_system_id,
            ]);

            $this->resetForm();
            $this->exams = Exam::with('GradingSytem')->get();  // Refresh exam list
        }
    }

    public function deleteExam($id)
    {
        $exam = Exam::findOrFail($id);
        $exam->delete();
        $this->exams = Exam::with('GradingSytem')->get();  // Refresh exam list
    }

    public function resetForm()
    {
        $this->name = '';
        $this->term = '';
        $this->year = '';
        $this->grading_system_id = null;
        $this->examId = null;
    }

    public function render()
    {
        return view('livewire.exam-management-component', [
            'gradingSystems' => GradingSystem::all()  // Send grading systems to the view
        ]);
    }
}
