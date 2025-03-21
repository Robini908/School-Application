<?php

namespace App\Livewire;

use App\Models\Exam;
use App\Models\Mark;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\Subject;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use App\Models\GradingRange;
use App\Models\GradingSystem;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Livewire\WithPagination;


class ExamManagementComponent extends Component
{

    use LivewireAlert;
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
    public $showGradingSystemForm = false;
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
    public $perPage = 10;
    protected $paginationTheme = 'bootstrap';
    use WithPagination;
    public $gradingSystemName;
    public $gradingSystemDescription;
    public $gradingSystemEffectiveDate;
    public $gradingSystemRules;
    public $gradingSystemErrors = [];



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
        // Handle both use cases in a single method
        
        // Case 1: When viewing grading system details
        if ($this->showGradingSystemDetails && $this->gradingSystemDetails) {
            $this->gradingRangesBySubject = GradingRange::where('grading_system_id', $this->gradingSystemDetails->id)
                ->where('subject_id', $this->selectedSubjectId)
                ->get();
        } 
        // Case 2: When fetching grading ranges for a selected subject
        else if ($subjectId) {
        // Fetch grading ranges for the selected subject as a collection
        $this->gradingRangesBySubject = GradingRange::where('subject_id', $subjectId)->get();
        }
        else {
            $this->gradingRangesBySubject = collect();
        }
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
            ->paginate($this->perPage);// Use paginate for full pagination
    
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

        $this->alert('success', 'Exam deleted successfully.');

        $this->cancelDelete(); // Hide the modal after deletion
        $this->dispatch('examDeleted'); // Optional: Emit an event to refresh the exam list
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
        try {
            $this->validate([
                'name' => 'required|string|max:255',
                'term' => 'required|string',
                'year' => 'required|numeric',
            'grading_system_id' => 'required|exists:grading_systems,id',
                // Add other validation rules as needed
            ], [
                'name.required' => 'Please enter an exam name.',
                'term.required' => 'Please select a term.',
                'year.required' => 'Please enter a year.',
                'grading_system_id.required' => 'Please select a grading system.',
                'grading_system_id.exists' => 'The selected grading system does not exist.',
            ]);

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
                }
                $exam->classes()->syncWithoutDetaching($syncData);
        }

            // Flash a success message
        $this->alert('success', $this->examId ? 'Exam updated successfully.' : 'Exam added successfully.');

        // Reset the form
        $this->resetForm();
            
            // Reset errors after successful save
            $this->resetErrorBag();
        } catch (\Exception $e) {
            // Handle any unexpected errors
            $this->alert('error', 'Failed to create exam: ' . $e->getMessage());
        }
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
        $this->sections = $exam->classes->first()->sections ?? collect();

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
        if (!$this->grading_system_id) {
            $this->alert('error', 'Please select a grading system first.');
            return;
        }

        // Load the grading system with its subjects and ranges
        $gradingSystem = GradingSystem::with(['subjects', 'gradingRanges.subject'])->find($this->grading_system_id);
        
        if (!$gradingSystem) {
            $this->alert('error', 'Grading system not found.');
            return;
        }

        // Store the grading system details for the view
        $this->gradingSystemDetails = $gradingSystem;
        
        // Get all subjects for the dropdown
        $this->mySubjects = Subject::all();
        
        // Set the flag to show the grading system details view
        $this->showGradingSystemDetails = true;
    }





    public function closeGradingSystemDetails()
    {
        $this->showGradingSystemDetails = false;
        $this->gradingSystemDetails = null;
        $this->selectedSubjectId = null;
        $this->gradingRangesBySubject = collect();
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

        // Also reset the grading system form
        $this->showGradingSystemForm = false;
        $this->resetGradingSystemForm();
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
            if ($this->sections instanceof Collection) {
                $this->selectedSections = $this->sections->pluck('id')->toArray();
            }
        } else {
            // Deselect all sections
            $this->selectedSections = [];
        }
    }

    public function updatedGradingSystemId($value)
    {
        if ($value === 'create_new') {
            $this->showGradingSystemForm = true;
            $this->resetGradingSystemForm();
        } else {
            $this->showGradingSystemForm = false;
        }
    }

    public function resetGradingSystemForm()
    {
        $this->gradingSystemName = '';
        $this->gradingSystemDescription = '';
        $this->gradingSystemEffectiveDate = now()->format('Y-m-d');
        $this->gradingSystemRules = '';
        $this->gradingSystemErrors = [];
        $this->resetErrorBag(['gradingSystemName', 'gradingSystemDescription', 'gradingSystemEffectiveDate', 'gradingSystemRules']);
    }

    public function cancelGradingSystemForm()
    {
        $this->showGradingSystemForm = false;
        $this->grading_system_id = null; // Reset the dropdown
        $this->resetGradingSystemForm();
    }

    public function saveGradingSystem()
    {
        // Validate the form
        $this->validate([
            'gradingSystemName' => 'required|string|max:255',
            'gradingSystemEffectiveDate' => 'required|date|after_or_equal:today',
            'gradingSystemDescription' => 'nullable|string|max:1000',
            'gradingSystemRules' => 'nullable|string',
        ], [
            'gradingSystemName.required' => 'Please enter a name for the grading system.',
            'gradingSystemEffectiveDate.required' => 'Please select an effective date.',
            'gradingSystemEffectiveDate.date' => 'The effective date must be a valid date.',
            'gradingSystemEffectiveDate.after_or_equal' => 'The effective date must be today or later.',
        ]);

        try {
            // Format the description and rules
            $formattedDescription = nl2br(htmlentities($this->gradingSystemDescription));
            $formattedRules = implode("\n", array_map('trim', array_filter(explode("\n", $this->gradingSystemRules))));

            // Create the new grading system
            $gradingSystem = GradingSystem::create([
                'name' => $this->gradingSystemName,
                'description' => $formattedDescription,
                'effective_date' => $this->gradingSystemEffectiveDate,
                'rules' => $formattedRules,
            ]);

            // Get all subjects and attach them to the grading system
            $subjects = Subject::all();
            $gradingSystem->subjects()->sync($subjects->pluck('id')->toArray());

            // Update the grading systems list
            $this->gradingSystems = GradingSystem::all();
            
            // Select the newly created grading system
            $this->grading_system_id = $gradingSystem->id;
            
            // Hide the form
            $this->showGradingSystemForm = false;
            
            // Show success message
            $this->alert('success', 'Grading system created successfully.');
            
            // Reset the form
            $this->resetGradingSystemForm();
            
        } catch (\Exception $e) {
            // Handle any errors
            $this->alert('error', 'Failed to create grading system: ' . $e->getMessage());
        }
    }
}
