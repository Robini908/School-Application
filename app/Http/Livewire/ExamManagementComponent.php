<?php

namespace App\Http\Livewire;

use App\Models\Exam;
use App\Models\Mark;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\Subject;
use Livewire\Component;
use App\Models\GradingRange;
use App\Models\GradingSystem;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Livewire\WithPagination;


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
    public $showGradingSystemDetails = false;
    public $showExamCard = true;
    public $marks;
    public $showGradingSystemForm = false; // Toggle grading system form
    public $newGradingSystemName;
    public $selectedGradingSystem = null; // Store the selected grading system
    public $gradingRanges = [];
    public $selectedSubjectId = null;
    public $gradingRangesBySubject = [];
    public $gradingSystemDetails = null;
    public $mySubjects;
    public $selectedClass; // For handling selected class
    public $selectedSection;
    public $classes; // Holds classes data
    public $sections = [];
    public $selectedSections = [];
    public $selectAllSections = false;
    public $isConfirmingDeleting = false; // Track whether the deletion is confirmed

    use WithPagination;



    // Load subjects when a grading system is selected
    public function updatedSelectedGradingSystem($gradingSystemId)
    {
        // Ensure the selected grading system ID is valid
        if (!$gradingSystemId) {
            $this->subjects = collect(); // Clear subjects if no grading system is selected
            return;
        }

        // Fetch subjects related to the selected grading system
        $this->subjects = Subject::whereHas('gradingRanges', function ($query) use ($gradingSystemId) {
            $query->where('grading_system_id', $gradingSystemId);
        })->get();

        // Reset selected subject and grading ranges
        $this->selectedSubjectId = null;
        $this->gradingRangesBySubject = collect(); // Initialize as an empty collection
    }



    public function updatedSelectedSubjectId($subjectId)
    {
        // Fetch grading ranges for the selected subject as a collection
        $this->gradingRangesBySubject = GradingRange::where('subject_id', $subjectId)->get();
    }







    public function mount($examId = null)
    {
        $this->resetForm();

        // Optionally set the examId if passed
        $this->examId = $examId;

        // Fetch all grading systems
        $this->gradingSystems = GradingSystem::all();

        // Initialize collections
        $this->gradingRanges = collect();
        $this->showGradingSystemDetails = false;
        $this->gradingSystemDetails = null;

        // Fetch all classes
        $this->classes = MyClass::all();

        // Fetch all subjects as a Collection
        $this->mySubjects = Subject::all();

        // Set the selected subject ID to the first subject if available
        $this->selectedSubjectId = $this->mySubjects->isNotEmpty() ? $this->mySubjects->first()->id : null;

        // Load grading system details if a grading system and subject are selected
        if ($this->selectedSubjectId) {
            $this->loadGradingSystemDetails();
        }

        // Initialize sections
        $this->sections = collect();
        $this->selectedSections = [];  // Array to hold selected section IDs
        $this->selectAllSections = false; // Control for selecting all sections

        // Initialize grading system ID
        $this->grading_system_id = null;

        // Fetch sections if a class is already selected
        if ($this->selectedClass) {
            $this->sections = MyClass::find($this->selectedClass)->sections ?? collect();
        }
    }



    public function loadGradingSystemDetails()
    {
        if ($this->selectedGradingSystem) {
            $this->gradingSystemDetails = GradingSystem::find($this->selectedGradingSystem);

            // Fetch grading ranges for the selected grading system and subject
            $this->gradingRangesBySubject = GradingRange::where('grading_system_id', $this->selectedGradingSystem)
                ->where('subject_id', $this->selectedSubjectId)
                ->get();

            $this->showGradingSystemDetails = true;
        } else {
            $this->showGradingSystemDetails = false;
            $this->gradingSystemDetails = null; // Clear details if no grading system selected
        }
    }


    public function render()
    {
        $exams = Exam::with(['classes', 'classes.sections']) // Eager load classes and their sections
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
            ->paginate(10); // Use paginate for full pagination
    
        // Fetch sections based on the selected class
        if ($this->selectedClass) {
            $this->sections = Section::where('my_class_id', $this->selectedClass)->get();
        } else {
            $this->sections = collect(); // Reset sections if no class is selected
        }
    
        return view('livewire.exam-management-component', [
            'exams' => $exams,
            'examDetails' => $this->examDetails,
            'sections' => $this->sections, // Pass sections to the view
        ]);
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




    public function confirmDelete($id)
    {
        $this->examId = $id; // Set the ID of the exam to delete
        $this->isConfirmingDeleting = true; // Show the modal
    }

    public function cancelDelete()
    {
        $this->isConfirmingDeleting = false; // Hide the modal
        $this->examId = null; // Reset the exam ID
    }

    public function deleteExam()
    {
        // Your logic to delete the exam using the stored exam ID
        Exam::find($this->examId)->delete();

        session()->flash('message', 'Exam deleted successfully.');

        $this->cancelDelete(); // Hide the modal after deletion
        $this->emit('examDeleted'); // Optional: Emit an event to refresh the exam list
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





    public function create()
    {
        $this->resetForm();
        $this->isCreating = true;
        $this->showExamCard = false; // Hide the card when creating
    }

    public function store()
    {
        // Define validation rules
        $rules = [
            'name' => 'required|string|max:255|unique:exams,name,' . $this->examId . ',id,year,' . $this->year . ',term,' . $this->term,
            'term' => 'required|integer',
            'year' => 'required|string|max:40',
            'grading_system_id' => 'required|exists:grading_systems,id',
            'selectedClass' => 'required|exists:my_classes,id',
            'selectedSections.*' => 'exists:sections,id', // Validate selected sections
        ];

        // Validate the form input
        $this->validate($rules);

        // Create or update the exam
        $exam = Exam::updateOrCreate(
            ['id' => $this->examId],
            [
                'name' => $this->name,
                'term' => $this->term,
                'year' => $this->year,
                'grading_system_id' => $this->grading_system_id,
                'class_id' => $this->selectedClass, // Include the class_id here
            ]
        );

        // Prepare data for syncing
        $syncData = [];
        if (!empty($this->selectedSections)) {
            foreach ($this->selectedSections as $sectionId) {
                $syncData[$this->selectedClass] = ['section_id' => $sectionId];
                $exam->classes()->syncWithoutDetaching($syncData);
            }
        }

        // Flash a success message
        session()->flash('message', $this->examId ? 'Exam updated successfully.' : 'Exam added successfully.');

        // Reset the form
        $this->resetForm();
    }






    public function toggleGradingSystemForm()
    {
        $this->showGradingSystemForm = !$this->showGradingSystemForm;
    }

    public function edit($id)
    {
        $exam = Exam::with(['classes.sections'])->findOrFail($id);

        $this->examId = $exam->id;
        $this->name = $exam->name;
        $this->term = $exam->term;
        $this->year = $exam->year;
        $this->grading_system_id = $exam->grading_system_id;

        // Load the selected class and its sections
        $this->selectedClass = $exam->classes->first()->id ?? null;
        $this->sections = $exam->classes->first()->sections ?? collect(); // Initialize sections based on the selected class

        // Collect selected sections
        $this->selectedSections = $exam->classes->flatMap(function ($class) {
            return $class->sections->pluck('id'); // Collect section IDs for all classes
        })->toArray();

        $this->isCreating = false;
        $this->isEditing = true;
        $this->showExamCard = false; // Hide the card when editing

    }



    public function getExamsProperty()
    {
        return Exam::orderBy('created_at', 'desc')->get();
    }


    public function viewGradingSystemDetails()
    {
        // Ensure a grading system is selected
        if ($this->grading_system_id) {
            // Fetch grading system details
            $this->gradingSystemDetails = GradingSystem::find($this->grading_system_id);

            // Ensure the grading system exists
            if ($this->gradingSystemDetails) {
                // Fetch grading ranges for the selected grading system and subject
                $this->gradingRangesBySubject = GradingRange::where('grading_system_id', $this->grading_system_id)
                    ->where('subject_id', $this->selectedSubjectId)
                    ->get();
            } else {
                // Clear details if grading system not found
                $this->gradingRangesBySubject = collect();
            }

            // Show the grading system details
            $this->showGradingSystemDetails = true;
        } else {
            // Reset if no grading system is selected
            $this->showGradingSystemDetails = false;
            $this->gradingSystemDetails = null;
            $this->gradingRangesBySubject = collect(); // Clear grading ranges
        }
    }





    public function closeGradingSystemDetails()
    {
        $this->showGradingSystemDetails = false;
        $this->selectedSubjectId = null;
    }

    public function resetForm()
    {
        // Resetting all form input values
        $this->name = '';
        $this->term = '';
        $this->year = '';
        $this->grading_system_id = null;
        $this->examId = null;
        $this->isCreating = false;
        $this->isEditing = false;
        $this->showGradingSystemDetails = false;
        $this->gradingSystemDetails = null;
        $this->selectedGradingSystem = null;
        $this->gradingRangesBySubject = collect();

        // Resetting class and section selection
        $this->selectedClass = null;   // Reset selected class
        $this->selectedSection = null; // Reset selected section
        $this->sections = collect();   // Clear sections for class

        // Show the card again when the form is reset
        $this->showExamCard = true;
    }

    public function updatedSelectedClass()
    {
        // Fetch sections based on the selected class
        $this->sections = Section::where('my_class_id', $this->selectedClass)->get();

        // Reset selected sections when class changes
        $this->selectedSections = [];
    }

    public function toggleSelectAllSections()
    {
        if ($this->selectAllSections) {
            // Ensure $this->sections is a collection before plucking
            if ($this->sections instanceof \Illuminate\Support\Collection) {
                $this->selectedSections = $this->sections->pluck('id')->toArray();
            }
        } else {
            // Deselect all sections
            $this->selectedSections = [];
        }
    }
}
