<?php

namespace App\Livewire;

use App\Models\MyClass;
use App\Models\Section;
use App\Models\Subject;
use Livewire\Component;
use App\Models\StudentRecord;
use App\Services\SubjectSelectionService;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Validation\ValidationException;

class SubjectSelectionComponent extends Component
{
    use LivewireAlert;

    public $classes;
    public $sections = [];
    public $unassignedStudents = [];
    public $students;
    public $subjects = [];
    public $studentCount;
    public $selectedClass = null;
    public $selectedSection = null;
    public $selectedStudents = [];

    public $selectedSubjects = [];
    public $numToSelect = null;
    public $showSubjectForm = false;
    public $showStudentCard = true;

    // Updated for Livewire 3 - using $listeners property
    protected $listeners = [
        'refreshComponent' => '$refresh',
        'hideStudentCard' => 'hideStudentCard',
        'showStudentCard' => 'showStudentCard'
    ];

    protected $subjectSelectionService;

    public function boot(SubjectSelectionService $subjectSelectionService)
    {
        $this->subjectSelectionService = $subjectSelectionService;
    }

    public function dehydrate()
    {
        // Emit a client-side event for debugging
        $this->dispatch('debug-sections', [
            'selectedClass' => $this->selectedClass,
            'sectionCount' => is_countable($this->sections) ? count($this->sections) : 0
        ]);
    }

    public function refreshComponent()
    {
        // Force a UI refresh and reload sections
        if ($this->selectedClass) {
            $this->loadSections();
        }
        
        // This is a placeholder method that triggers a refresh
        // No need to do anything else here as Livewire will re-render the component
    }

    public function mount()
    {
        // Initialize available classes
        $this->classes = MyClass::whereHas('subjectSelectionSetting', function ($query) {
            $query->where('is_subject_selection_enabled', true);
        })->get();

        // Initialize empty collections for other properties
        $this->sections = collect([]);
        $this->unassignedStudents = collect([]);  
        $this->students = collect([]);
        $this->subjects = collect([]);
        
        // Initialize other properties
        $this->studentCount = 0;
        $this->selectedStudents = [];
        $this->selectedSubjects = [];
        $this->showStudentCard = true;
        $this->showSubjectForm = false;
        
        // Debug initialization
        logger('Subject Selection Component Initialized');
    }

    public function toggleShowStudentCard()
    {
        $this->showStudentCard = true;
        $this->showSubjectForm = false;
        // Updated for Livewire 3
        $this->dispatch('subjectFormToggled', false);
    }

    public function hideStudentCard()
    {
        $this->showStudentCard = false;
    }

    public function showStudentCard()
    {
        $this->showStudentCard = true;
    }

    public function updatedSelectedClass($classId)
    {
        $this->loadSections($classId);
    }

    /**
     * Load sections for a given class ID
     */
    public function loadSections($classId = null)
    {
        $classId = $classId ?? $this->selectedClass;
        
        if (!empty($classId)) {
            // Load sections
            $this->sections = Section::where('my_class_id', $classId)->get();
            
            // Reset related fields
            $this->selectedSection = null;
            $this->students = collect([]);
            $this->unassignedStudents = collect([]);
            $this->selectedStudents = [];
            
            // Debug info with safe collection check
            $sectionCount = $this->sections instanceof \Illuminate\Support\Collection 
                ? $this->sections->count() 
                : count($this->sections);
            logger('Loading sections for class: ' . $classId . ' found: ' . $sectionCount);
        } else {
            $this->sections = collect([]);
            $this->selectedSection = null;
        }
    }

    public function showSubjectFormForStudents()
    {
        if (!empty($this->selectedStudents)) {
            // Fetch compulsory and elective subjects separately
            $compulsorySubjects = Subject::where('type', 'compulsory')->get();
            $electiveSubjects = Subject::where('type', 'elective')->get();

            // Always automatically select compulsory subjects (they cannot be unselected)
            $this->selectedSubjects = $compulsorySubjects->pluck('id')->toArray();

            // Pass both sets of subjects to the view with proper structure
            $this->subjects = [
                'compulsory' => $compulsorySubjects,
                'elective' => $electiveSubjects,
            ];
            
            // Show the subject form
            $this->showStudentCard = false;
            $this->showSubjectForm = true;
            
            // Log for debugging
            logger('Showing subject form with ' . count($this->selectedStudents) . ' students and ' . count($this->selectedSubjects) . ' pre-selected subjects');
            
            // Notify the frontend
            $this->dispatch('subjectFormToggled', true);
        } else {
            $this->showStudentCard = true;
            $this->showSubjectForm = false;
            $this->alert('error', 'No students were selected.');
        }
    }

    public function submitSubjectSelection()
    {
        if (empty($this->selectedStudents)) {
            $this->alert('error', 'Please select at least one student.');
            return;
        }

        try {
            foreach ($this->selectedStudents as $studentId) {
                $student = StudentRecord::findOrFail($studentId);

                // Fetch selected subjects including compulsory ones
                $compulsorySubjects = Subject::where('type', 'compulsory')->pluck('id')->toArray();
                $allSelectedSubjects = array_unique(array_merge($this->selectedSubjects, $compulsorySubjects));
                $selectedSubjects = Subject::find($allSelectedSubjects);

                $this->subjectSelectionService->validateSelection($student, $selectedSubjects);

                foreach ($selectedSubjects as $subject) {
                    $student->subjects()->syncWithoutDetaching($subject->id);
                }
            }

            $this->alert('success', 'Subjects selected and saved successfully!');
            $this->showStudentCard = true;
            $this->showSubjectForm = false;
            // Updated for Livewire 3
            $this->dispatch('subjectFormToggled', false);
            
            $this->resetForm();
            $this->refreshUnassignedStudents();
        } catch (ValidationException $e) {
            $this->alert('error', 'Validation Error: ' . implode(', ', $e->errors()['subject_selection']), [
                'position' => 'top',
                'showConfirmButton' => true,
                'confirmButtonText' => 'OK',
                'reverseButtons' => true,
                'timer' => 30000,
                'toast' => false,
            ]);
        } catch (\Exception $e) {
            $this->alert('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }

    public function updatedSelectedSection($sectionId)
    {
        $students = StudentRecord::where('section_id', $sectionId)->get();

        // Filter students who do not have any assigned subjects
        $this->unassignedStudents = $students->filter(function ($student) {
            return $student->subjects->isEmpty();
        });

        $this->studentCount = $students->count();
        $this->selectedStudents = [];
        $this->selectedSubjects = [];
    }

    public function selectAllStudents()
    {
        $studentsCollection = collect($this->unassignedStudents); // Ensure you're working with the current unassigned students
        $this->selectedStudents = $studentsCollection->pluck('id')->toArray(); // Get all student IDs
        $this->alert('success', count($this->selectedStudents) . ' students selected!');
    }

    public function selectSpecificStudents()
    {
        $studentsCollection = collect($this->unassignedStudents);

        if ($this->numToSelect && $this->numToSelect <= $studentsCollection->count()) {
            $this->selectedStudents = $studentsCollection->take($this->numToSelect)->pluck('id')->toArray();
            $this->alert('success', $this->numToSelect . ' students selected!');
        } else {
            $this->alert('error', 'Invalid number. Ensure it is within the total count of available students.');
        }
    }

    public function removeStudentFromSelection($studentId)
    {
        // Remove the student from the selectedStudents array
        $this->selectedStudents = array_diff($this->selectedStudents, [$studentId]);
    }

    public function clearSelection()
    {
        // Ensure these are always initialized as arrays
        $this->selectedStudents = [];
        
        // Only reset the elective subjects, keep compulsory ones
        $compulsorySubjectIds = Subject::where('type', 'compulsory')->pluck('id')->toArray();
        $this->selectedSubjects = $compulsorySubjectIds;
    }

    public function refreshUnassignedStudents()
    {
        if ($this->selectedSection) {
            $this->updatedSelectedSection($this->selectedSection);
        }
    }

    public function resetForm()
    {
        $this->showSubjectForm = false;
        $this->showStudentCard = true;
        $this->selectedStudents = [];
        $this->selectedSubjects = []; // This is an empty array, not null
        
        // Updated for Livewire 3
        $this->dispatch('subjectFormToggled', false);
    }

    /**
     * This hook runs whenever selectedSubjects is updated
     */
    public function updatedSelectedSubjects($value, $key)
    {
        try {
            // Don't do anything if we're actually trying to unset something
            // This delay is important to ensure Livewire's binding completes
            // before we modify the array again
            $this->skipRender();
            
            // Make sure selectedSubjects is initialized as an array
            if (!is_array($this->selectedSubjects)) {
                $this->selectedSubjects = [];
            }
            
            // Get compulsory subject IDs
            $compulsorySubjectIds = Subject::where('type', 'compulsory')->pluck('id')->toArray();
            
            // Ensure all compulsory subjects are always in the selection
            // without modifying any other selections the user has made
            foreach ($compulsorySubjectIds as $id) {
                if (!in_array($id, $this->selectedSubjects)) {
                    $this->selectedSubjects[] = $id;
                }
            }

            // Log safely (without causing string conversion errors)
            logger("Subject selection updated - " . count($this->selectedSubjects) . " total subjects selected");
        } catch (\Exception $e) {
            logger('Error in updatedSelectedSubjects: ' . $e->getMessage());
            // Don't reinitialize everything on error, just ensure compulsory subjects
            // are still there without replacing the entire array
            $this->selectedSubjects = array_unique(
                array_merge($this->selectedSubjects ?: [], $compulsorySubjectIds)
            );
        }
    }

    public function render()
    {
        return view('livewire.subject-selection-component');
    }
}
