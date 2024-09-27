<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\StudentRecord;
use Illuminate\Support\Facades\DB;

class ManageStudents extends Component
{
    use WithPagination;

    public $selectedStudent;
    protected $mystudents;
    protected $mystudent; // Change to protected
    public $formFilter = '';
    public $sectionFilter = '';
    public $statusFilter = '';

    public $forms = [];
    public $sections = [];
    public $statuses = ['Active', 'Inactive'];

    protected $listeners = [
        'refreshStudents' => 'loadStudents',
        'studentSelected' => 'selectStudent'
    ];

    public function mount()
    {
        $this->loadStudents();
        $this->loadFilterOptions();
    }

    public function loadStudents()
    {
        $query = DB::table('student_records')
            ->join('my_classes', 'student_records.my_class_id', '=', 'my_classes.id')
            ->join('sections', 'student_records.section_id', '=', 'sections.id')
            ->join('parent_details', 'student_records.parent_id_no', '=', 'parent_details.parent_id_no')
            ->select(
                'student_records.*',
                'my_classes.name as classname',
                'sections.name as sectionname',
                'parent_details.parent_first_name',
                'parent_details.parent_last_name',
                'parent_details.parent_phone_number'
            );

        // Apply filters independently
        if ($this->formFilter) {
            $query->where('my_classes.name', $this->formFilter);
        }

        if ($this->sectionFilter) {
            $query->where('sections.name', $this->sectionFilter);
        }

        if ($this->statusFilter) {
            $query->where('student_records.status', $this->statusFilter);
        }

        $this->mystudents = $query->orderBy('student_records.id', 'desc')->get();
    }

    public function loadFilterOptions()
    {
        // Fetch distinct forms from the database
        $this->forms = DB::table('my_classes')->pluck('name')->unique()->toArray();

        // If a form is selected, fetch corresponding sections
        if ($this->formFilter) {
            // Fetch the ID of the selected form
            $formId = DB::table('my_classes')->where('name', $this->formFilter)->value('id');

            // Fetch sections corresponding to the selected form ID
            $this->sections = DB::table('sections')
                ->where('my_class_id', $formId)
                ->pluck('name')
                ->unique()
                ->toArray();
        } else {
            $this->sections = []; // Reset sections if no form is selected
        }
    }

    public function updated($propertyName)
    {
        // Re-load students when a filter is updated
        if (in_array($propertyName, ['formFilter', 'sectionFilter', 'statusFilter'])) {
            $this->loadStudents();
            $this->loadFilterOptions();
        }
    }

    public function resetFilters()
    {
        $this->formFilter = '';
        $this->sectionFilter = '';
        $this->statusFilter = '';
        $this->loadStudents();
        $this->loadFilterOptions();
    }

    public function removeFilter($filterName)
    {
        if ($filterName === 'formFilter') {
            $this->formFilter = '';
        } elseif ($filterName === 'sectionFilter') {
            $this->sectionFilter = '';
        } elseif ($filterName === 'statusFilter') {
            $this->statusFilter = '';
        }

        $this->resetPage(); // Reset pagination if using it
    }

    public function deleteRecord($studentId)
    {
        // Logic to delete student record
        StudentRecord::find($studentId)->delete();
        $this->loadStudents(); // Refresh students after deletion
    }

    public function render()
    {
        $noResults = $this->mystudents->isEmpty();
        return view('livewire.manage-students', compact('noResults'))
            ->extends('layouts.app')
            ->section('content');
    }
}
