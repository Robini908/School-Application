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
    public $students;
    public $subjects = [];
    public $studentCount;
    public $selectedClass = null;
    public $selectedSection = null;
    public $selectedStudents = [];
    public $selectedSubjects = [];
    public $numToSelect = null;
    public $showSubjectForm = false;

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
    }

    public function updatedSelectedClass($classId)
    {
        $this->sections = Section::where('my_class_id', $classId)->get();
        $this->selectedSection = null;
        $this->students = [];
    }

    public function updatedSelectedSection($sectionId)
    {
        $this->students = StudentRecord::where('section_id', $sectionId)->get();
        $this->studentCount = $this->students->count();
        $this->selectedStudents = [];
        $this->selectedSubjects = [];
    }

    public function selectAllStudents()
    {
        $this->selectedStudents = $this->students->pluck('id')->toArray();
    }

    public function selectSpecificStudents()
    {
        if ($this->numToSelect && $this->numToSelect <= count($this->students)) {
            $this->selectedStudents = $this->students->take($this->numToSelect)->pluck('id')->toArray();
        } else {
            $this->alert('error', 'Invalid number of students selected.');
        }
    }

    public function clearSelection()
    {
        $this->selectedStudents = [];
        $this->selectedSubjects = [];
    }

    public function showSubjectFormForStudents()
    {
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
        if (empty($this->selectedSubjects)) {
            $this->alert('error', 'Please select at least one subject.');
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
                    $student->subjects()->attach($subject->id);
                }
            }

            $this->alert('success', 'Subjects selected and saved successfully!');
            $this->resetForm();
        } catch (ValidationException $e) {
            $this->alert('error', 'Validation Error: ' . implode(', ', $e->errors()['subject_selection']), [
                'position' => 'top', // Position on screen (can be top, top-end, bottom, etc.)
                'showConfirmButton' => true, // Show a confirmation button
                'confirmButtonText' => 'OK', // Text on the confirm button

                'reverseButtons' => true, // Reverse the order of buttons
                'timer' => 30000, // Time before it automatically closes
                'toast' => false, // If you want it to be a toast notification or a modal
            ]);
        } catch (\Exception $e) {
            $this->alert('error', 'An unexpected error occurred: ' . $e->getMessage());
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
