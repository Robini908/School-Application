<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\StudentRecord;
use App\Models\StudentTransition;
use App\Models\MyClass;
use App\Models\Section;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ManageStudentTransitions extends Component
{
    use LivewireAlert;

    public $showPromotions = false;
    public $showDemotions = false;
    public $showRepetitions = false;
    public $showGraduations = false;

    public $transitionType;
    public $targetClassId;
    public $targetSectionId;
    public $reason;
    public $selectedStudents = [];

    public $classes;
    public $sections = [];
    public $students;
    public $searchTerm = '';

    public function mount()
    {
        $this->classes = MyClass::orderBy('id', 'asc')->get(); // Order classes by ID (assuming higher ID = higher class)
        $this->students = collect();
    }

    public function setTransitionType($type)
    {
        $this->transitionType = $type;
    }

    public function showPromotionsCard()
    {
        $this->resetFlags();
        $this->showPromotions = true;
        $this->transitionType = 'promotion';
    }

    public function showDemotionsCard()
    {
        $this->resetFlags();
        $this->showDemotions = true;
        $this->transitionType = 'demotion';
    }

    public function showRepetitionsCard()
    {
        $this->resetFlags();
        $this->showRepetitions = true;
        $this->transitionType = 'repetition';
    }

    public function showGraduationsCard()
    {
        $this->resetFlags();
        $this->showGraduations = true;
        $this->transitionType = 'graduation';
    }

    public function resetFlags()
    {
        $this->showPromotions = false;
        $this->showDemotions = false;
        $this->showRepetitions = false;
        $this->showGraduations = false;
    }

    public function updatedTargetClassId($value)
    {
        $this->sections = Section::where('my_class_id', $value)->get();
        $this->targetSectionId = null;
        $this->students = collect();
    }

    public function updatedTargetSectionId($value)
    {
        $this->searchStudents();
    }

    public function updatedSearchTerm()
    {
        $this->searchStudents();
    }

    protected function searchStudents()
    {
        if ($this->targetSectionId) {
            $this->students = StudentRecord::where('section_id', $this->targetSectionId)
                ->when($this->searchTerm, function ($query) {
                    $query->where(function ($q) {
                        $q->where('first_name', 'like', '%' . $this->searchTerm . '%')
                          ->orWhere('middle_name', 'like', '%' . $this->searchTerm . '%')
                          ->orWhere('last_name', 'like', '%' . $this->searchTerm . '%')
                          ->orWhere('adm_no', 'like', '%' . $this->searchTerm . '%');
                    });
                })
                ->get();
        } else {
            $this->students = collect();
        }
    }

    public function saveTransition()
    {
        try {
            $this->validate([
                'selectedStudents' => 'required|array|min:1',
                'transitionType' => 'required|in:promotion,demotion,repetition,graduation',
                'targetClassId' => 'required_if:transitionType,promotion,demotion,repetition|exists:my_classes,id',
                'targetSectionId' => 'required_if:transitionType,promotion,demotion,repetition|exists:sections,id',
                'reason' => 'nullable|string',
            ]);

            foreach ($this->selectedStudents as $studentId) {
                $student = StudentRecord::find($studentId);
                $currentClassId = $student->my_class_id;

                // Determine the target class based on the transition type
                if ($this->transitionType === 'promotion') {
                    // Get the next class (higher ID)
                    $targetClass = MyClass::where('id', '>', $currentClassId)
                        ->orderBy('id', 'asc')
                        ->first();
                } elseif ($this->transitionType === 'demotion') {
                    // Get the previous class (lower ID)
                    $targetClass = MyClass::where('id', '<', $currentClassId)
                        ->orderBy('id', 'desc')
                        ->first();
                } else {
                    // For repetition, target class remains the same
                    $targetClass = MyClass::find($currentClassId);
                }

                if ($targetClass) {
                    StudentTransition::create([
                        'student_id' => $studentId,
                        'transition_year' => now()->year,
                        'transition_type' => $this->transitionType,
                        'target_class_id' => $targetClass->id,
                        'target_section_id' => $this->targetSectionId,
                        'reason' => $this->reason,
                        'decision_by' => Auth::id(),
                        'decision_date' => now()->toDateString(),
                    ]);
                }
            }

            $this->resetForm();
            $this->alert('success', 'Transition saved successfully!');
        } catch (\Exception $e) {
            $this->alert('error', 'An error occurred while saving the transition: ' . $e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->reset([
            'selectedStudents',
            'targetClassId',
            'targetSectionId',
            'reason',
        ]);
        $this->resetFlags();
    }

    public function render()
    {
        return view('livewire.manage-student-transitions');
    }
}