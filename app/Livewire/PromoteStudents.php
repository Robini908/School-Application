<?php

namespace App\Livewire;

use App\Models\MyClass;
use App\Models\Section;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\StudentRecord;
use App\Models\StudentTransition;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class PromoteStudents extends Component
{
    use WithPagination;
    use LivewireAlert;

    public $selectedClass;
    public $selectedSection;
    public $search = '';
    public $selectedStudents = [];
    public $transition_type = '';
    public $targetClass;
    public $targetSection;
    public $transitionType = 'promotion';
    public $transitionYear;
    public $reason;

    protected $rules = [
        'selectedClass' => 'required|exists:my_classes,id',
        'selectedSection' => 'required|exists:sections,id',
        'targetClass' => 'required|exists:my_classes,id',
        'targetSection' => 'required|exists:sections,id',
        'transitionYear' => 'required|date_format:Y',
        'reason' => 'nullable|string',
    ];

    protected $messages = [
        'selectedClass.required' => 'Please select a class.',
        'selectedSection.required' => 'Please select a section.',
        'targetClass.required' => 'Please select a target class.',
        'targetSection.required' => 'Please select a target section.',
        'transitionYear.required' => 'The transition year is required.',
        'transitionYear.date_format' => 'The transition year must be in the format YYYY.',
    ];

    public function mount()
    {
        // Set the default transition year to the current year
        $this->transitionYear = now()->year;
    }

    public function render()
    {
        $classes = MyClass::all();
        $sections = $this->selectedClass ? Section::where('my_class_id', $this->selectedClass)->get() : [];

        // Filter students who have not been transitioned in the same year for the selected transition type
        $students = $this->selectedSection ? StudentRecord::where('section_id', $this->selectedSection)
            ->whereDoesntHave('transitions', function ($query) {
                $query->where('transition_year', $this->transitionYear)
                    ->whereIn('transition_type', ['promotion', 'demotion', 'repetition']); // Filter based on transition type
            })
            ->where(function ($query) {
                $query->where('first_name', 'like', '%' . $this->search . '%')
                    ->orWhere('last_name', 'like', '%' . $this->search . '%')
                    ->orWhere('adm_no', 'like', '%' . $this->search . '%');
            })
            ->paginate(10) : [];

        return view('livewire.promote-students', compact('classes', 'sections', 'students'));
    }



    public function updatedSelectedSection($value)
    {
        $this->selectedStudents = [];
    }


    public function promoteStudents()
    {
        // Validate the form inputs
        $this->validate();

        try {
            // Log the transition process for debugging
            Log::info('Transitioning students:', [
                'selectedStudents' => $this->selectedStudents,
                'targetClass' => $this->targetClass,
                'targetSection' => $this->targetSection,
                'transitionYear' => $this->transitionYear,
                'transitionType' => $this->transitionType,
            ]);

            // Validation 1: Ensure at least one student is selected
            if (empty($this->selectedStudents)) {
                $this->alert('error', 'No students selected for transition.');
                return;
            }

            // Validation 2: Ensure the target class is not the same as the current class (except for repetition)
            if ($this->transitionType !== 'repetition' && $this->selectedClass == $this->targetClass) {
                $this->alert('error', 'Students cannot be transitioned to the same class.');
                return;
            }

            // Determine the target class based on the transition type
            $currentClass = MyClass::find($this->selectedClass);
            $targetClass = MyClass::find($this->targetClass);

            if (!$currentClass || !$targetClass) {
                $this->alert('error', 'Invalid class selected.');
                return;
            }

            if ($this->transitionType === 'promotion') {
                // Logic for promotion: target class should be the next class
                $nextClass = MyClass::where('id', '>', $currentClass->id)
                    ->orderBy('id')
                    ->first();

                if (!$nextClass || $nextClass->id != $targetClass->id) {
                    $this->alert('error', 'Students can only be promoted to the next class in the sequence.');
                    return;
                }
            } elseif ($this->transitionType === 'demotion') {
                // Logic for demotion: target class should be the previous class
                $previousClass = MyClass::where('id', '<', $currentClass->id)
                    ->orderBy('id', 'desc')
                    ->first();

                if (!$previousClass || $previousClass->id != $targetClass->id) {
                    $this->alert('error', 'Students can only be demoted to the previous class in the sequence.');
                    return;
                }
            } elseif ($this->transitionType === 'repetition') {
                // Logic for repetition: target class and section should be the same as the current class and section
                $this->targetClass = $this->selectedClass;
                $this->targetSection = $this->selectedSection;
            }

            // Validation 5: Ensure the transition year is not in the past
            if ($this->transitionYear < now()->year) {
                $this->alert('error', 'Transition year cannot be in the past.');
                return;
            }

            // Validation 6: Ensure no duplicate transitions for the same student in the same year
            $existingTransitions = StudentTransition::whereIn('student_id', $this->selectedStudents)
                ->where('transition_year', $this->transitionYear)
                ->where('transition_type', $this->transitionType)
                ->exists();

            if ($existingTransitions) {
                $this->alert('error', 'One or more students already have a transition record for the selected year.');
                return;
            }

            // Validation 9: Ensure the user is an admin or teacher
            if (!in_array(auth()->user()->user_type, ['admin', 'teacher', 'super_admin'])) {
                $this->alert('error', 'You do not have permission to transition students.');
                return;
            }

            // Validation 10: Ensure the reason is provided if the transition type is not standard
            if (empty($this->reason) && $this->transitionType !== 'promotion' && $this->transitionType !== 'demotion') {
                $this->alert('error', 'A reason is required for non-standard transitions.');
                return;
            }

            // Create a transition record for each selected student
            foreach ($this->selectedStudents as $studentId) {
                StudentTransition::create([
                    'student_id' => $studentId,
                    'transition_year' => $this->transitionYear,
                    'transition_type' => $this->transitionType,
                    'target_class_id' => $this->targetClass,
                    'target_section_id' => $this->targetSection,
                    'reason' => $this->reason,
                    'decision_by' => auth()->id(),
                    'decision_date' => now(),
                ]);
            }

            // Display a success message using LivewireAlert
            $this->alert('success', 'Students transitioned successfully.');

            // Reset the form fields
            $this->reset(['selectedClass', 'selectedSection', 'selectedStudents', 'targetClass', 'targetSection', 'transitionYear', 'reason', 'transitionType']);
            // Re-fetch data to ensure fresh changes are seen
            $this->render(); // This will re-render the component and fetch fresh data
        } catch (\Exception $e) {
            // Log any errors that occur
            Log::error('Error transitioning students: ' . $e->getMessage());

            // Display an error message using LivewireAlert
            $this->alert('error', $e->getMessage());

            // Emit an event to reset the button state
            $this->dispatch('promotionError');
        }
    }

    public function updatedSelectedClass($value)
    {
        $this->selectedSection = null; // Reset the selected section
        $this->selectedStudents = []; // Reset the selected students

        // Automatically set the target class based on the transition type
        $currentClass = MyClass::find($value);
        if ($currentClass) {
            if ($this->transitionType === 'promotion') {
                // For promotion: target class should be the next class
                $nextClass = MyClass::where('id', '>', $currentClass->id)
                    ->orderBy('id')
                    ->first();
                $this->targetClass = $nextClass ? $nextClass->id : null;
            } elseif ($this->transitionType === 'demotion') {
                // For demotion: target class should be the previous class
                $previousClass = MyClass::where('id', '<', $currentClass->id)
                    ->orderBy('id', 'desc')
                    ->first();
                $this->targetClass = $previousClass ? $previousClass->id : null;
            } elseif ($this->transitionType === 'repetition') {
                // For repetition: target class should be the same as the current class
                $this->targetClass = $currentClass->id;
            }
        } else {
            $this->targetClass = null;
        }

        // Reset the target section when the target class changes
        $this->targetSection = null;
    }
}
