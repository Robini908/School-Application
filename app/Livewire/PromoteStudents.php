<?php

namespace App\Livewire;

use App\Models\MyClass;
use App\Models\Section;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\StudentRecord;
use App\Models\StudentTransition;
use Illuminate\Support\Facades\Log;
use Usernotnull\Toast\Concerns\WireToast;

class PromoteStudents extends Component
{
    use WithPagination;
    use WireToast;

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

    // Add debug property to track student count
    public $debug = [];

    // Protected properties for listeners and rules
    protected $listeners = ['refreshComponent' => '$refresh'];
    
    // Define queryString to persist the search parameter in URL
    protected $queryString = ['search' => ['except' => '']];

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
    
    public function hydrate()
    {
        // For any debugging or state maintenance after hydration
        Log::info('PromoteStudents component hydrated');
    }

    // Reset pagination when search is updated
    public function updatedSearch()
    {
        $this->resetPage();
        Log::info('Search updated', ['search' => $this->search]);
    }

    public function render()
    {
        $classes = MyClass::all();
        $sections = $this->selectedClass ? Section::where('my_class_id', $this->selectedClass)->get() : [];

        // Filter students who have not been transitioned in the same year for the selected transition type
        $students = collect([]);
        
        if ($this->selectedSection) {
            $students = StudentRecord::where('section_id', $this->selectedSection)
            ->whereDoesntHave('transitions', function ($query) {
                $query->where('transition_year', $this->transitionYear)
                        ->whereIn('transition_type', ['promotion', 'demotion', 'repetition']);
            })
            ->where(function ($query) {
                $query->where('first_name', 'like', '%' . $this->search . '%')
                    ->orWhere('last_name', 'like', '%' . $this->search . '%')
                    ->orWhere('adm_no', 'like', '%' . $this->search . '%');
            })
                ->paginate(10);
            
            // Debug information
            $this->debug = [
                'section_id' => $this->selectedSection,
                'student_count' => $students->count(),
                'total_students' => $students->total(),
                'search_term' => $this->search,
            ];
            
            Log::info('Students query executed', [
                'section_id' => $this->selectedSection,
                'student_count' => $students->count(),
                'total' => $students->total(),
                'search' => $this->search
            ]);
        }

        return view('livewire.promote-students', compact('classes', 'sections', 'students'));
    }

    public function updatedSelectedSection($value)
    {
        $this->selectedStudents = [];
        $this->search = ''; // Reset search when changing section
        $this->resetPage();
        Log::info('Section updated', ['section_id' => $value]);
        $this->dispatch('refreshComponent');
    }
    
    public function updatedSelectedClass($value)
    {
        $this->selectedSection = null; // Reset the selected section
        $this->selectedStudents = []; // Reset the selected students
        $this->search = ''; // Reset search when changing class
        $this->resetPage();

        // Automatically set the target class based on the transition type
        $currentClass = MyClass::find($value);
        
        if (!$currentClass) {
            $this->targetClass = null;
            $this->targetSection = null;
            $this->dispatch('refreshComponent');
            return;
        }
        
        // Find next and previous classes for transitions
        if ($this->transitionType === 'promotion') {
            // For promotion: target class should be the next class in sequence
            $nextClass = MyClass::where('id', '>', $currentClass->id)
                ->orderBy('id')
                ->first();
                
            if ($nextClass) {
                $this->targetClass = $nextClass->id;
                Log::info('Target class set for promotion', [
                    'current_class' => $currentClass->name,
                    'target_class' => $nextClass->name
                ]);
            } else {
                $this->targetClass = null;
                Log::warning('No next class available for promotion from ' . $currentClass->name);
            }
        } elseif ($this->transitionType === 'demotion') {
            // For demotion: target class should be the previous class in sequence
            $previousClass = MyClass::where('id', '<', $currentClass->id)
                ->orderBy('id', 'desc')
                ->first();
                
            if ($previousClass) {
                $this->targetClass = $previousClass->id;
                Log::info('Target class set for demotion', [
                    'current_class' => $currentClass->name,
                    'target_class' => $previousClass->name
                ]);
            } else {
                $this->targetClass = null;
                Log::warning('No previous class available for demotion from ' . $currentClass->name);
            }
        } elseif ($this->transitionType === 'repetition') {
            // For repetition: target class should be the same as the current class
            $this->targetClass = $currentClass->id;
            Log::info('Target class set for repetition', [
                'current_class' => $currentClass->name,
                'target_class' => $currentClass->name
            ]);
        }

        // Reset the target section when the target class changes
        $this->targetSection = null;
        $this->dispatch('refreshComponent');
    }

    public function updatedTargetClass($value)
    {
        // Reset the target section when the target class changes
        $this->targetSection = null;
        
        if ($value) {
            Log::info('Target class updated', ['target_class_id' => $value]);
        }
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
                toast()
                    ->danger('No students selected for transition.')
                    ->push();
                
                $this->dispatch('transition-error', 'No students selected for transition.');
                return;
            }

            // Validation 2: Ensure a target class is set (except for repetition where the current class might be used)
            if (!$this->targetClass) {
                if ($this->transitionType === 'promotion') {
                    toast()
                        ->danger('No higher class available for promotion.')
                        ->push();
                    
                    $this->dispatch('transition-error', 'No higher class available for promotion.');
                    return;
                } elseif ($this->transitionType === 'demotion') {
                    toast()
                        ->danger('No lower class available for demotion.')
                        ->push();
                    
                    $this->dispatch('transition-error', 'No lower class available for demotion.');
                    return;
                } else {
                    toast()
                        ->danger('Invalid target class.')
                        ->push();
                    
                    $this->dispatch('transition-error', 'Invalid target class.');
                    return;
                }
            }

            // Validation 3: Ensure the target class is not the same as the current class (except for repetition)
            if ($this->transitionType !== 'repetition' && $this->selectedClass == $this->targetClass) {
                toast()
                    ->danger('Students cannot be transitioned to the same class (use repetition for this).')
                    ->push();
                
                $this->dispatch('transition-error', 'Students cannot be transitioned to the same class.');
                return;
            }

            // Determine the target class based on the transition type
            $currentClass = MyClass::find($this->selectedClass);
            $targetClass = MyClass::find($this->targetClass);

            if (!$currentClass || !$targetClass) {
                toast()
                    ->danger('Invalid class selected.')
                    ->push();
                return;
            }

            if ($this->transitionType === 'promotion') {
                // Logic for promotion: target class should be the next class
                $nextClass = MyClass::where('id', '>', $currentClass->id)
                    ->orderBy('id')
                    ->first();

                if (!$nextClass || $nextClass->id != $targetClass->id) {
                    toast()
                        ->danger('Students can only be promoted to the next class in the sequence.')
                        ->push();
                    return;
                }
            } elseif ($this->transitionType === 'demotion') {
                // Logic for demotion: target class should be the previous class
                $previousClass = MyClass::where('id', '<', $currentClass->id)
                    ->orderBy('id', 'desc')
                    ->first();

                if (!$previousClass || $previousClass->id != $targetClass->id) {
                    toast()
                        ->danger('Students can only be demoted to the previous class in the sequence.')
                        ->push();
                    return;
                }
            } elseif ($this->transitionType === 'repetition') {
                // Logic for repetition: target class and section should be the same as the current class and section
                if ($this->targetClass != $this->selectedClass) {
                $this->targetClass = $this->selectedClass;
                }
            }

            // Validation 5: Ensure the transition year is not in the past
            if ($this->transitionYear < now()->year) {
                toast()
                    ->danger('Transition year cannot be in the past.')
                    ->push();
                return;
            }

            // Validation 6: Ensure no duplicate transitions for the same student in the same year
            $existingTransitions = StudentTransition::whereIn('student_id', $this->selectedStudents)
                ->where('transition_year', $this->transitionYear)
                ->where('transition_type', $this->transitionType)
                ->exists();

            if ($existingTransitions) {
                toast()
                    ->danger('One or more students already have a transition record for the selected year.')
                    ->push();
                return;
            }

            // Validation 9: Ensure the user is an admin or teacher
            if (!in_array(auth()->user()->user_type, ['admin', 'teacher', 'super_admin'])) {
                toast()
                    ->danger('You do not have permission to transition students.')
                    ->push();
                return;
            }

            // Validation 10: Ensure the reason is provided if the transition type is not standard promotion
            if (empty($this->reason) && $this->transitionType !== 'promotion') {
                toast()
                    ->danger('A reason is required for ' . $this->transitionType . '.')
                    ->push();
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

            // Display a success message using Toast
            $currentClass = MyClass::find($this->selectedClass);
            $targetClass = MyClass::find($this->targetClass);
            
            $message = '';
            $icon = '';
            
            if ($this->transitionType === 'promotion') {
                $message = count($this->selectedStudents) . ' student(s) promoted from ' . $currentClass->name . ' to ' . $targetClass->name . ' successfully.';
                $icon = '<i class="fas fa-arrow-up mr-2"></i>';
            } elseif ($this->transitionType === 'demotion') {
                $message = count($this->selectedStudents) . ' student(s) demoted from ' . $currentClass->name . ' to ' . $targetClass->name . ' successfully.';
                $icon = '<i class="fas fa-arrow-down mr-2"></i>';
            } elseif ($this->transitionType === 'repetition') {
                $message = count($this->selectedStudents) . ' student(s) set to repeat ' . $currentClass->name . ' successfully.';
                $icon = '<i class="fas fa-redo mr-2"></i>';
            }
            
            // Send success message to toast and trigger animation
            toast()
                ->success($icon . $message)
                ->doNotSanitize()
                ->pushOnNextPage();
            
            // Dispatch event for frontend animation and success state
            $this->dispatch('transition-success', $message);

            // Reset the form fields
            $this->reset(['selectedClass', 'selectedSection', 'selectedStudents', 'targetClass', 'targetSection', 'reason', 'search']);
            $this->transitionYear = now()->year; // Reset to current year
            
            // Re-fetch data to ensure fresh changes are seen
            $this->dispatch('refreshComponent');
        } catch (\Exception $e) {
            // Log any errors that occur
            Log::error('Error transitioning students: ' . $e->getMessage());

            // Display an error message using Toast
            toast()
                ->danger('Error: ' . $e->getMessage())
                ->push();

            // Emit an event to reset the button state and show error
            $this->dispatch('promotionError');
            $this->dispatch('transition-error', 'Error: ' . $e->getMessage());
        }
    }
}
