<?php

namespace App\Http\Livewire;

use App\Models\Exam;
use App\Models\Mark;
use App\Models\Subject;
use Livewire\Component;
use App\Models\GradingSystem;
use Illuminate\Validation\Rule;

class ExamManagementComponent extends Component
{
    public $name, $term, $year, $grading_system_id;
    public $examId; // Make sure this is defined
    public $isCreating = false, $isEditing = false;
    public $terms = [
        1 => 'First Term',
        2 => 'Second Term',
        3 => 'Third Term',
    ];
    public $gradingSystems;
    public $deleteId;
    public $examDetails = [];
    public $confirmingDelete = false;  // Add a property to handle delete confirmation

    // Filter properties
    public $filterName = '';
    public $showExamDetailsModal = false; // New property to control modal visibility

    public $filterYear = '';
    public $filterTerm = '';
    public $filterGradingSystem = '';
    public $showAssignMarksForm = false;

    public $showManageMarksModal = false;
    public $students = [];
    public $subjects = [];
    public $marks;

    public function mount($examId = null)
    {
        // Optionally set the examId if passed
        $this->examId = $examId;
        $this->gradingSystems = GradingSystem::all();
    }

    public function render()
    {
        $exams = Exam::query()
            ->when($this->filterName, function ($query) {
                $query->where('name', 'like', '%' . $this->filterName . '%');
            })
            ->when($this->filterYear, function ($query) {
                $query->where('year', $this->filterYear);
            })
            ->when($this->filterTerm, function ($query) {
                $query->where('term', $this->filterTerm);
            })
            ->when($this->filterGradingSystem, function ($query) {
                $query->where('grading_system_id', $this->filterGradingSystem);
            })
            ->get();

        return view('livewire.exam-management-component', [
            'exams' => $exams,
            'examDetails' => $this->examDetails,
        ]);
    }

    public function goToAssignMarks()
    {
        // Redirect to the route without the examId
        return redirect()->route('exams.assignExamMarks');
    }


    public function resetFilter($filter)
    {
        $this->$filter = '';
    }

    public function resetAllFilters()
    {
        $this->filterName = '';
        $this->filterYear = '';
        $this->filterTerm = '';
        $this->filterGradingSystem = '';
    }

    public function create()
    {
        $this->resetForm();
        $this->isCreating = true;
        $this->isEditing = false;
    }

    public function store()
    {
        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('exams', 'name')
                    ->where('year', $this->year)
                    ->where('term', $this->term)
                    ->ignore($this->examId)
            ],
            'term' => 'required|integer',
            'year' => 'required|string|max:40',
            'grading_system_id' => 'required|exists:grading_systems,id',
        ];

        $this->validate($rules);

        Exam::updateOrCreate(['id' => $this->examId], [
            'name' => $this->name,
            'term' => $this->term,
            'year' => $this->year,
            'grading_system_id' => $this->grading_system_id,
        ]);

        session()->flash('message', $this->examId ? 'Exam updated successfully.' : 'Exam added successfully.');
        $this->resetForm();
    }

    public function edit($id)
    {
        $exam = Exam::find($id);
        $this->examId = $exam->id;
        $this->name = $exam->name;
        $this->term = $exam->term;
        $this->year = $exam->year;
        $this->grading_system_id = $exam->grading_system_id;
        $this->isCreating = false;
        $this->isEditing = true;
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->confirmingDelete = true;  // Show the delete confirmation modal
    }

    public function deleteConfirmed()
    {
        if ($this->deleteId) {
            Exam::destroy($this->deleteId);
            session()->flash('message', 'Exam deleted successfully.');
            $this->deleteId = null;
            $this->confirmingDelete = false;
        }
    }

    public function cancelDelete()
    {
        $this->deleteId = null;
        $this->confirmingDelete = false;  // Hide the delete confirmation modal
    }



    public function showDetails($id)
    {
        $exam = Exam::find($id);

        if ($exam) {
            $this->examDetails = [
                'id' => $exam->id,
                'name' => $exam->name,
                'term' => $this->terms[$exam->term] ?? 'N/A',
                'year' => $exam->year,
                'grading_system' => $exam->gradingSystem->name ?? 'N/A',
            ];
            $this->showExamDetailsModal = true; // Show the modal
        } else {
            $this->examDetails = [];
            $this->showExamDetailsModal = false;
        }
    }



    public function resetFilters()
    {
        $this->filterName = '';
        $this->filterYear = '';
        $this->filterTerm = '';
        $this->filterGradingSystem = '';
    }

    private function resetForm()
    {
        $this->name = '';
        $this->term = '';
        $this->year = '';
        $this->grading_system_id = '';
        $this->examId = null; // Reset the examId
        $this->isCreating = false;
        $this->isEditing = false;
    }
}
