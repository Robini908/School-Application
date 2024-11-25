<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\MyClass;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\SubjectSelectionSetting;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class SubjectSelectionDeadline extends Component
{
    use LivewireAlert;

    public $classes;
    public $settings = [];
    public $search = '';
    public $selectedClassCount = 0;
    public $deadline;
    public $filteredClasses = [];
    public $filter = 'all';
    public $previousSettings = []; // To store previous state for reverting
    public $rateLimit = 5; // Max toggles per minute
    public $lastToggleTime = null; // Track the last toggle time for rate limiting

    protected $listeners = ['updateDeadline'];

    public function mount()
    {
        // Load classes and their associated settings
        $this->loadClasses();
    }


    public function setDeadline()
    {
        // If no classes are selected, reset deadline
        if ($this->selectedClassCount === 0) {
            SubjectSelectionSetting::whereYear('created_at', Carbon::now()->year)
                ->update(['deadline' => null]);

            $this->deadline = null; // Clear the deadline in the component
            $this->alert('error', 'No classes selected. Deadline reset for the current year.');
            return;
        }

        // Update the deadline for selected classes
        $selectedClasses = SubjectSelectionSetting::where('is_subject_selection_enabled', true)
            ->whereYear('created_at', Carbon::now()->year)
            ->get();

        foreach ($selectedClasses as $setting) {
            $setting->update([
                'deadline' => Carbon::parse($this->deadline)
            ]);
        }

        $this->alert('success', 'Deadline successfully set for all selected classes.');
    }

    public function render()
    {
        return view('livewire.subject-selection-deadline');
    }

    // Get remaining countdown in seconds
    
    public function getCountdown()
    {
        // If no deadline is set, return 0
        if (!$this->deadline) {
            return 0;
        }

        // Calculate the remaining time until the deadline
        $deadline = Carbon::parse($this->deadline);
        $now = Carbon::now();
        $remaining = $deadline->diffInSeconds($now);

        return max($remaining, 0);
    }


    // Load classes with synchronization
    public function loadClasses()
    {
        $this->classes = MyClass::with('subjectSelectionSetting')->get();

        // Load subject selection settings for each class
        foreach ($this->classes as $class) {
            $this->settings[$class->id] = $class->subjectSelectionSetting
                ? $class->subjectSelectionSetting->is_subject_selection_enabled
                : false;
        }

        $this->selectedClassCount = SubjectSelectionSetting::where('is_subject_selection_enabled', true)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        // Retrieve the deadline from the database
        $setting = SubjectSelectionSetting::whereYear('created_at', Carbon::now()->year)->first();
        if ($setting && $setting->deadline) {
            $this->deadline = Carbon::parse($setting->deadline)->format('Y-m-d\TH:i');
        } else {
            // Set a default deadline if not found
            $this->deadline = Carbon::now()->addHours(1)->format('Y-m-d\TH:i');
        }

       
    }

   


}
