<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Exam;

class ManageMarks extends Component
{
    public $examId;
    public $marks;
    public $showModal = false;

    protected $rules = [
        'marks' => 'required|numeric|min:0'
    ];

    public function mount($examId)
    {
        $this->examId = $examId;
        $this->loadExam();
    }

    public function loadExam()
    {
        $exam = Exam::find($this->examId);
        if ($exam) {
            $this->marks = $exam->marks ?? 0;
        }
    }

    public function saveMarks()
    {
        $this->validate();

        $exam = Exam::find($this->examId);
        if ($exam) {
            $exam->marks = $this->marks;
            $exam->save();
            session()->flash('message', 'Marks assigned successfully.');
            $this->emit('marksAssigned');
        }
    }

    public function render()
    {
        return view('livewire.manage-marks');
    }
}

