<?php

namespace App\Livewire;

use Livewire\Component;



class AllSubjectManagementActions extends Component
{
    public $activeAction = null;
    public $notifications = [];
    public $searchTerm = '';
    
    // Stats card properties
    public $totalSubjects = 0;
    public $subjectChange = 0;
    public $activeClasses = 0;
    public $studentsWithSelections = 0;
    public $studentChange = 0;

    public function mount()
    {
        $this->loadStats();
    }

    public function setAction($action)
    {
        $this->activeAction = $action;
    }

    public function loadStats()
    {
        // Get total subjects
        $this->totalSubjects = \App\Models\Subject::count();
        
        // Calculate subject change (example: new subjects in last 30 days)
        $lastMonth = now()->subDays(30);
        $this->subjectChange = \App\Models\Subject::where('created_at', '>=', $lastMonth)->count();
        
        // Get all classes (since we don't have a status column)
        $this->activeClasses = \App\Models\MyClass::count();
        
        // Get students with subject selections
        $this->studentsWithSelections = \App\Models\StudentRecord::has('subjects')->count();
        
        // Calculate student change (example: new selections in last 30 days)
        $this->studentChange = \App\Models\StudentRecord::has('subjects')
            ->where('updated_at', '>=', $lastMonth)
            ->count();
    }

    public function render()
    {
        return view('livewire.all-subject-management-actions');
    }
}
