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




    protected $subjectSelectionService;

    public function boot(SubjectSelectionService $subjectSelectionService)
    {
        $this->subjectSelectionService = $subjectSelectionService;
    }

    public function mount()
    {
        $this->classes = MyClass::whereHas('subjectSelectionSetting', function ($query) {
            $query->where('is_subject_selection_enabled', true);
        })->get();
        $this->showStudentCard = true;
    }

    public function updatedSelectedClass($classId)
    {
        $this->sections = Section::where('my_class_id', $classId)->get();
        $this->selectedSection = null;
        $this->students = [];
    }



    public function showSubjectFormForStudents()
    {
        $this->showStudentCard = false;

        if (!empty($this->selectedStudents)) {
            // Fetch compulsory and elective subjects separately
            $compulsorySubjects = Subject::where('type', 'compulsory')->get();
            $electiveSubjects = Subject::where('type', 'elective')->get();

            // Automatically select compulsory subjects
            $this->selectedSubjects = $compulsorySubjects->pluck('id')->toArray();

            // Pass both sets of subjects to the view
            $this->subjects = [
                'compulsory' => $compulsorySubjects,
                'elective' => $electiveSubjects,
            ];
            $this->showSubjectForm = true;
        } else {
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
        // $this->alert('success', message: 'Student removed from selection.');
    }



    public function clearSelection()
    {
        $this->selectedStudents = [];
        $this->selectedSubjects = [];
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
        $this->selectedClass = null;
        $this->selectedSection = null;
        $this->selectedStudents = [];
        $this->selectedSubjects = [];
    }

    public function render()
    {
        return view('livewire.subject-selection-component');
    }
}
