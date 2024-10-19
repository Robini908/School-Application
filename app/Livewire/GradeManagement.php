<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\GradingSystem;
use App\Models\GradingGrade;
use Illuminate\Support\Collection;

class GradeManagement extends Component
{
    public $grading_system_id;
    public $grades = [];
    public $gradingSystems;

    // Validation rules
    protected $rules = [
        'grading_system_id' => 'required|exists:grading_systems,id',
        'grades.*.grade' => 'required|string|max:10',
        'grades.*.remark' => 'nullable|string|max:255',
        'grades.*.gpa' => 'required|numeric|min:0|max:4',
        'grades.*.description' => 'nullable|string|max:255',
        'grades.*.additional_info' => 'nullable|string|max:255',
    ];

    public function mount()
    {
        // Get all available grading systems
        $this->gradingSystems = GradingSystem::all();

        // Initialize one grade row
        $this->addGradeRow();
    }

    // Add a new grade row
    public function addGradeRow()
    {
        $this->grades[] = [
            'grade' => '',
            'remark' => '',
            'gpa' => '',
            'description' => '',
            'additional_info' => '',
        ];
    }

    // Remove a grade row by index
    public function removeGradeRow($index)
    {
        unset($this->grades[$index]);
        $this->grades = array_values($this->grades); // Reindex the array
    }

    // Save all grades for the selected grading system
    public function saveGrades()
    {
        $this->validate();

        foreach ($this->grades as $gradeData) {
            GradingGrade::create([
                'grading_system_id' => $this->grading_system_id,
                'grade' => $gradeData['grade'],
                'remark' => $gradeData['remark'],
                'gpa' => $gradeData['gpa'],
                'description' => $gradeData['description'],
                'additional_info' => $gradeData['additional_info'],
            ]);
        }

        session()->flash('success', 'Grades saved successfully!');
        $this->resetForm();
    }

    // Reset the form after saving
    public function resetForm()
    {
        $this->grading_system_id = null;
        $this->grades = [];
        $this->addGradeRow();
    }

    public function render()
    {
        return view('livewire.grade-management');
    }
}
