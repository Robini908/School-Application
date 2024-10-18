<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\MyClass;
use App\Models\ExamMarks;

class SubjectChampions extends Component
{
    public $classId;
    public $streamId;
    public $examId;
    public $champions;
    public $classes;
    public $exams = [];
    public $errorMessage;

    public function mount()
    {
        $this->classes = MyClass::with('sections')->get();
        $this->champions = collect(); // Initialize champions
    }

    public function updatedClassId()
    {
        $this->streamId = null;
        $this->examId = null;
        $this->exams = [];
        $this->champions = collect(); // Reset champions
        $this->errorMessage = null;

        if ($this->classId) {
            $this->exams = MyClass::find($this->classId)->exams;
        }
    }

    public function updatedStreamId()
    {
        $this->examId = null;
        $this->champions = collect(); // Reset champions
        $this->errorMessage = null;
    }

    public function updatedExamId()
    {
        $this->champions = collect(); // Reset champions
        $this->errorMessage = null;
        $this->getChampions(); // Fetch champions for the newly selected exam
    }

    public function getChampions()
    {
        if (!$this->examId) {
            $this->errorMessage = 'Please select an exam to view champions.';
            return;
        }

        $query = ExamMarks::with(['student', 'subject'])
            ->where('exam_id', $this->examId);

        if ($this->classId) {
            $query->whereHas('student', function ($q) {
                $q->where('my_class_id', $this->classId);
            });
        }

        if ($this->streamId) {
            $query->whereHas('student', function ($q) {
                $q->where('section_id', $this->streamId);
            });
        }

        // Group by subject_id and find the maximum marks per subject
        $this->champions = $query->get()
            ->groupBy('subject_id')
            ->map(function ($marks) {
                // Find the highest marks
                $highestMarks = $marks->max('marks');

                // Return all students who achieved the highest marks
                return $marks->where('marks', $highestMarks);
            })->flatten(1); // Flatten to keep all tied champions

        if ($this->champions->isEmpty()) {
            $this->errorMessage = 'No champions found for the selected criteria.';
        }
    }

    public function render()
    {
        return view('livewire.subject-champions', [
            'champions' => $this->champions,
            'errorMessage' => $this->errorMessage,
        ]);
    }
}
