<?php

namespace App\Http\Livewire;

use App\Models\MyClass;
use App\Models\Section;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\StudentRecord;
use Illuminate\Support\Facades\DB;

class ManageStudents extends Component
{
    use WithPagination;

    public $selectedStudent;
    protected $mystudents;
    public $showDeleteModal = false;
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
        $this->mystudents = collect(); // Initialize as empty collection
        $this->fetchStudents(); // Fetch students on mount
        // Other methods can be called here as needed
        $this->loadFilterOptions(); // Load filter options if needed
    }
    public function fetchStudents()
    {
        $this->mystudents = StudentRecord::with(['my_class', 'section', 'parent_detail'])
            ->orderBy('id', 'desc')
            ->get();
    }




    public function loadStudents()
    {
        // Start the query with eager loading of related models
        $query = StudentRecord::with(['my_class', 'section', 'parent_detail'])
            ->orderBy('id', 'desc');

        // Apply filters if necessary
        if (!empty($this->formFilter)) {
            $query->whereHas('my_class', function ($q) {
                $q->where('name', $this->formFilter);
            });
        }

        if (!empty($this->sectionFilter)) {
            $query->whereHas('section', function ($q) {
                $q->where('name', $this->sectionFilter);
            });
        }

        if (!empty($this->statusFilter)) {
            $query->where('status', $this->statusFilter);
        }

        // Execute the query and get the results
        $this->mystudents = $query->get();
    }

    public function loadFilterOptions()
    {
        // Fetch distinct forms (my_classes) using the MyClass model
        $this->forms = MyClass::pluck('name')->unique()->toArray();

        // If a form is selected, fetch corresponding sections
        if ($this->formFilter) {
            // Fetch sections corresponding to the selected form using the relationship
            $this->sections = Section::whereHas('my_class', function ($query) {
                $query->where('name', $this->formFilter);
            })
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
        $this->selectedStudent = StudentRecord::find($studentId);
        $this->showDeleteModal = true; // Show delete confirmation modal
    }

    public function confirmDelete()
    {
        if ($this->selectedStudent) {
            // Attempt to delete the selected student record
            try {
                $this->selectedStudent->delete();
                session()->flash('success', 'Student deleted successfully.'); // Success message
            } catch (\Exception $e) {
                session()->flash('error', 'Failed to delete the student: ' . $e->getMessage()); // Error message
            }

            // Refresh the students list after deletion
            $this->fetchStudents();

            // Reset the selected student and hide the modal
            $this->selectedStudent = null;
            $this->showDeleteModal = false;
        }
    }


    public function cancelDelete()
    {
        $this->showDeleteModal = false; // Cancel deletion and close the modal
    }



    public function studentExpulsion($studentId)
    {
        // Logic to expel student
        $this->selectedStudent = StudentRecord::find($studentId);
        // Perform expulsion logic here
    }

    public function suspendStudent($studentId)
    {
        // Logic to suspend student
        $this->selectedStudent = StudentRecord::find($studentId);
        // Perform suspension logic here
    }

    public function render()
    {
        return view('livewire.manage-students', [
            'noResults' => $this->mystudents->isEmpty(),
        ]);
    }
}
