<?php

namespace App\Livewire;

use App\Models\MyClass;
use App\Models\Section;
use App\Models\Subject;
use Livewire\Component;
use App\Models\StudentRecord;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Services\SubjectSelectionService;
use Illuminate\Validation\ValidationException;
use Jantinnerezo\LivewireAlert\LivewireAlert; // Import the trait

class SubjectSelectionComponent extends Component
{
    use LivewireAlert; // Use the trait

    public $classId;
    public $studentId;
    public $class_id;
    public $section_id;
    public $student;
    public $sectionId;
    public $rechooseSubjectId = null; // To keep track of the subject being re-chosen
    public $sameCategorySubjects = []; // To store subjects of the same category for the dropdown
    public $newSubjectId = null;
    public $subjectsSelected = false;

    public $selectedSubjects = [];
    public $classes;
    public $sections = [];
    public $students = [];
    public $subjects = [];

    protected $subjectSelectionService;

    public function boot(SubjectSelectionService $subjectSelectionService)
    {
        $this->subjectSelectionService = $subjectSelectionService;
    }

    public function mount()
    {
        $this->classes = MyClass::all();  // Fetch all classes
        $this->sections = collect();      // Initialize empty sections array
        $this->students = collect();      // Initialize empty students array
        $this->subjects = collect();      // Initialize empty subjects array
        $this->selectedSubjects = [];     // Initialize empty selected subjects array
    }


    public function updatedClassId($value)
    {
        // Fetch sections based on the selected class
        $this->sections = Section::where('my_class_id', $value)->get();

        // Reset student-related data when class is changed
        $this->students = [];
        $this->student = null; // Reset the student details
        $this->reset(['sectionId', 'studentId', 'selectedSubjects']);
    }

    public function updatedSectionId($value)
    {
        // Fetch students based on the selected section and class
        $this->students = StudentRecord::where('my_class_id', $this->classId)
            ->where('section_id', $value)->get();

        // Reset student-related data when section is changed
        $this->student = null; // Reset the student details
        $this->reset(['studentId', 'selectedSubjects']);
    }


    public function updatedStudentId($value)
    {
        $this->reset(['selectedSubjects', 'subjectsSelected']);
        // Retrieve the selected student along with their class and section
        $this->student = StudentRecord::with('my_class', 'section')->find($value);

        // Check if the student exists
        if ($this->student) {
            // Retrieve all subjects
            $this->subjects = Subject::all();

            // Auto-select the subjects associated with the student
            $this->selectedSubjects = $this->student->subjects->pluck('id')->toArray();

            // Automatically select compulsory subjects and add them to the selectedSubjects
            $compulsorySubjects = Subject::where('type', 'compulsory')->pluck('id')->toArray();
            $this->selectedSubjects = array_merge($this->selectedSubjects, $compulsorySubjects);

            // Remove duplicates
            $this->selectedSubjects = array_unique($this->selectedSubjects);

            // Check if any subjects are selected
            $this->subjectsSelected = count($this->selectedSubjects) > 0;
        } else {
            // If the student does not exist, reset the selectedSubjects
            $this->selectedSubjects = [];
            $this->subjectsSelected = false;
        }
    }





    public function submit()
    {
        $student = StudentRecord::findOrFail($this->studentId);
        $selectedSubjects = Subject::whereIn('id', $this->selectedSubjects)->get();

        try {
            // Validate subject selection
            $this->subjectSelectionService->validateSelection($student, new Collection($selectedSubjects));

            // Sync selected subjects to the student record
            $student->subjects()->sync($this->selectedSubjects);

            // Success: Display SweetAlert confirmation
            $this->alert('success', 'Subjects selected successfully!', [
                'position' => 'top-end',
                'timer' => 1500,
                'toast' => true,
                'showConfirmButton' => false,
            ]);

            $this->subjectsSelected = true; // After submitting, show the list of selected subjects
        } catch (ValidationException $e) {
            // Display SweetAlert error with formatted message and confirmation button
            $this->alert('error', 'Error: ' . $e->getMessage(), [
                'position' => 'top',
                'showConfirmButton' => true,
                'confirmButtonText' => 'OK',
                'showCancelButton' => true,
                'cancelButtonText' => 'Cancel',
                'reverseButtons' => true,
                'timer' => 30000,
                'toast' => false,
            ]);
        }
    }





    public function loadRechooseOptions($subjectId)
    {
        $this->rechooseSubjectId = $subjectId; // Set the current subject to be re-chosen
        $subject = Subject::find($subjectId);

        // Retrieve subjects in the same category, excluding the current subject
        $this->sameCategorySubjects = Subject::where('category_id', $subject->category_id)
            ->where('id', '!=', $subjectId)
            ->get();
    }


    public function updateSubjectSelection($oldSubjectId)
    {
        if ($this->newSubjectId) {
            // Check if the new subject is already selected by the student
            if (in_array($this->newSubjectId, $this->selectedSubjects)) {
                // Emit an error message if the subject is already selected
                $this->alert('error', 'This subject has already been selected. Please choose another subject.', [
                    'position' => 'top',
                    'showConfirmButton' => true,
                    'confirmButtonText' => 'Rechoose Subject',

                    'reverseButtons' => true,
                    'timer' => 30000,
                    'toast' => false,
                ]);
                return;
            }

            // Remove the old subject and add the new one to the selected subjects
            if (($key = array_search($oldSubjectId, $this->selectedSubjects)) !== false) {
                $this->selectedSubjects[$key] = $this->newSubjectId;
            }

            // Check if the new subject is already attached to the student to avoid duplicates
            if ($this->student && !$this->student->subjects->contains($this->newSubjectId)) {
                try {
                    // Remove the old subject and add the new one to the selected subjects
                    $this->student->subjects()->detach($oldSubjectId);
                    $this->student->subjects()->attach($this->newSubjectId);

                    // Clear the selection after updating
                    $this->rechooseSubjectId = null;
                    $this->newSubjectId = null;
                    $this->sameCategorySubjects = [];

                    // Emit a success message
                    $this->alert('success', 'Subject reselected successfully.', [
                        'position' => 'top-end',
                        'timer' => 1500,
                        'toast' => true,
                        'showConfirmButton' => false,
                    ]);
                } catch (\Exception $e) {
                    // Log the error and show an alert to the user
                    Log::error('Error updating subject: ' . $e->getMessage());

                    $this->alert('error', 'There was an error while updating the subject. Please try again.', [
                        'position' => 'top',
                        'showConfirmButton' => true,
                        'confirmButtonText' => 'OK',
                        'reverseButtons' => true,
                        'timer' => 30000,
                        'toast' => false,
                    ]);
                }
            } else {
                // Emit an error if the subject is already attached to the student
                $this->alert('error', 'This subject has already been selected for this student.', [
                    'position' => 'top',
                    'showConfirmButton' => true,
                    'confirmButtonText' => 'Choose Another Subject',
                    'reverseButtons' => true,
                    'timer' => 30000,
                    'toast' => false,
                ]);
            }
        }
    }


    // Method to deregister a subject
    public function deregisterSubject($subjectId)
    {
        // Remove the subject from the selected subjects
        if (($key = array_search($subjectId, $this->selectedSubjects)) !== false) {
            unset($this->selectedSubjects[$key]);
        }

        // Update the student's record in the database
        if ($this->student) {
            try {
                $this->student->subjects()->detach($subjectId);

                // Refresh the subjects list
                $this->subjectsSelected = count($this->selectedSubjects) > 0;

                // Emit a success message
                $this->alert('success', 'Subject deregistered successfully.', [
                    'position' => 'top',
                    'showConfirmButton' => true,
                    'confirmButtonText' => 'OK',
                    'reverseButtons' => true,
                    'timer' => 30000,
                    'toast' => false,
                ]);
            } catch (\Exception $e) {
                // Log the error and show an alert to the user
                Log::error('Error deregistering subject: ' . $e->getMessage());

                $this->alert('error', 'There was an error while deregistering the subject. Please try again.', [
                    'position' => 'top',
                    'showConfirmButton' => true,
                    'confirmButtonText' => 'OK',
                    'reverseButtons' => true,
                    'timer' => 30000,
                    'toast' => false,
                ]);
            }
        }
    }

    public function render()
    {
        return view('livewire.subject-selection-component', [
            'classes' => $this->classes,
            'sections' => $this->sections,
            'students' => $this->students,
            'subjects' => $this->subjects,
            'selectedSubjects' => $this->selectedSubjects,
            'subjectsSelected' => $this->subjectsSelected,
        ]);
    }
}
