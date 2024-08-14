<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\StudentRecord;

class StudentDetailsModal extends Component
{
    public $studentId;
    public $studentDetails;

    protected $listeners = ['studentSelected' => 'loadStudentDetails'];

    public function mount()
    {
        // No need to set $studentId here as it will be set through the listener
    }

    public function loadStudentDetails($studentId)
    {
        $this->studentId = $studentId;
        $this->studentDetails = StudentRecord::with(['class', 'section', 'parent'])
            ->where('id', $studentId)
            ->first();
    }

    public function render()
    {
        return view('livewire.student-details-modal');
    }
}
