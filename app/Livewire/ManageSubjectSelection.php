<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\MyClass;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\SubjectSelectionSetting;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ManageSubjectSelection extends Component
{
    use LivewireAlert;

    public $classes;
    public $settings = [];
    public $activeAction = null;

    public $search = '';
    public $deadline;
    public $filteredClasses = [];
    public $filter = 'all';
    public $previousSettings = []; // To store previous state for reverting
    public $rateLimit = 5; // Max toggles per minute
    public $lastToggleTime = null; // Track the last toggle time for rate limiting

    protected $listeners = ['updateDeadline'];

    public function mount()
    {
        $this->loadClasses();
    }


    // Load classes with synchronization
    // public function loadClasses()
    // {
    //     $this->classes = MyClass::with('subjectSelectionSetting')->get();
    //     foreach ($this->classes as $class) {
    //         $this->settings[$class->id] = $class->subjectSelectionSetting ? $class->subjectSelectionSetting->is_subject_selection_enabled : false;
    //     }

    //     $query = MyClass::query();
    //     if ($this->filter !== 'all') {
    //         $query->where('is_selected', $this->filter === 'selected');
    //     }
    //     $this->filteredClasses = $query->get();
    // }

    public function loadClasses()
    {
        // Load all classes with their subject selection settings
        $this->classes = MyClass::with('subjectSelectionSetting')->get();

        // Load subject selection settings for each class
        foreach ($this->classes as $class) {
            $this->settings[$class->id] = $class->subjectSelectionSetting
                ? $class->subjectSelectionSetting->is_subject_selection_enabled
                : false;
        }

        // Apply the filter to show selected classes
        $query = MyClass::query();
        if ($this->filter !== 'all') {
            $query->whereHas('subjectSelectionSetting', function ($q) {
                $q->where('is_subject_selection_enabled', true);
            });
        }
        $this->filteredClasses = $query->get();
    }



    // Revert a toggle change


    // Toggle the selection, ensuring dependencies are intact and no concurrency issues
    public function toggleSelection($classId)
    {
        // Check rate limiting
        if ($this->checkRateLimit()) {
            $retryTime = Carbon::parse($this->lastToggleTime)->addMinutes(1)->diffForHumans();
            $this->alert('error', "Rate limit exceeded. Please try again after {$retryTime}.");
            return;
        }

        // Store the previous state for possible revert
        $this->previousSettings[$classId] = $this->settings[$classId];

        $currentStatus = $this->settings[$classId];

        // Simulate dependency check
        if ($this->hasDependencies($classId)) {
            $this->alert('error', "This class has dependencies. You can't toggle this setting.");
            return;
        }

        // Handle transaction for concurrency
        DB::transaction(function () use ($classId, $currentStatus) {
            SubjectSelectionSetting::updateOrCreate(
                ['class_id' => $classId],
                ['is_subject_selection_enabled' => !$currentStatus]
            );

            // Update the local state
            $this->settings[$classId] = !$currentStatus;
            $this->loadClasses();
        });

        // Feedback after successful toggle
        $className = MyClass::find($classId)->name;
        $status = $this->settings[$classId] ? 'enabled' : 'disabled';
        $this->alert('success', "Subject selection for '{$className}' has been {$status}.", [
            'position' => 'top',
            'showConfirmButton' => true,
            'confirmButtonText' => 'OK',
            'reverseButtons' => true,
            'timer' => 30000,
            'toast' => false,
        ]);
    }






    // Check rate limit
    public function checkRateLimit()
    {
        $currentTime = now();
        $lastToggleTime = $this->lastToggleTime ? Carbon::parse($this->lastToggleTime) : null;

        if ($lastToggleTime && $lastToggleTime->diffInMinutes($currentTime) < 1) {
            return true; // Rate limit exceeded (less than a minute)
        }

        // Update last toggle time
        $this->lastToggleTime = $currentTime;
        return false;
    }

    // Check if the class has dependencies (dummy function for demo)
    public function hasDependencies($classId)
    {
        // Add logic to check for dependencies (e.g., checking if the subject is being used by another class)
        return false;
    }

    // Check rate limiting to prevent too many changes in a short period


    // Save settings to the database (this is an additional helper to save state)
    public function saveSettings($classId, $status)
    {
        SubjectSelectionSetting::updateOrCreate(
            ['class_id' => $classId],
            ['is_subject_selection_enabled' => $status]
        );
    }



    // Filter classes based on search and selection status
    public function getFilteredClasses()
    {
        $filtered = $this->classes;

        if ($this->filter === 'selected') {
            $filtered = $filtered->filter(fn($class) => $this->settings[$class->id]);
        } elseif ($this->filter === 'not_selected') {
            $filtered = $filtered->filter(fn($class) => !$this->settings[$class->id]);
        }

        if ($this->search) {
            $filtered = $filtered->filter(fn($class) => str_contains(strtolower($class->name), strtolower($this->search)));
        }

        return $filtered;
    }

    public function render()
    {
        return view('livewire.manage-subject-selection', [
            'filteredClasses' => $this->getFilteredClasses(),
        ]);
    }
}
