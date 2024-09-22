<?php

namespace App\Http\Livewire;

use App\Models\GradingRange;
use App\Models\GradingSystem;
use App\Models\Subject;
use Livewire\Component;


class GradingRangeManager extends Component
{
    public $gradingSystems, $subjects, $selectedGradingSystem;
    public $ranges = [];
    public $subjectId;
    public $submittedRanges = [];
    public $isEditing = false;
    public $currentIndex = null;
    public $moreDetails = false;

    public function mount()
    {
        $this->gradingSystems = GradingSystem::all();
        $this->subjects = collect();
    }

    public function updatedSelectedGradingSystem($gradingSystemId)
    {
        $this->subjects = GradingSystem::find($gradingSystemId)->subjects;
        $this->reset(['subjectId', 'ranges', 'submittedRanges']);
    }

    public function updatedSubjectId()
    {
        $this->ranges = [];
        $this->addRange();
        $this->loadSubmittedRanges();
    }

    public function addRange()
    {
        $this->ranges[] = ['range_from' => '', 'range_to' => '', 'grade' => '', 'remark' => '', 'gpa' => ''];
    }

    public function removeRange($index)
    {
        unset($this->ranges[$index]);
        $this->ranges = array_values($this->ranges);
    }

    public function editRange($index)
    {
        $this->isEditing = true;
        $this->currentIndex = $index;
    }

    public function submitRanges()
    {
        $this->isEditing ? $this->updateRange() : $this->saveRanges();
    }

    public function updateRange()
    {
        $this->validateRanges();

        // Update the current range directly in the database
        $rangeToUpdate = GradingRange::find($this->submittedRanges[$this->currentIndex]->id);

        if ($rangeToUpdate) {
            $rangeToUpdate->update([
                'range_from' => $this->ranges[0]['range_from'], // Use the first range for editing
                'range_to' => $this->ranges[0]['range_to'],
                'grade' => $this->ranges[0]['grade'],
                'remark' => $this->ranges[0]['remark'],
                'gpa' => $this->ranges[0]['gpa'],
            ]);
        }

        $this->resetEditingState(); // Reset after editing
        session()->flash('message', 'Grading range updated successfully!');
        $this->loadSubmittedRanges(); // Refresh submitted ranges
    }

    public function saveRanges()
    {
        $this->validateRanges();

        foreach ($this->ranges as $range) {
            GradingRange::create([
                'range_from' => $range['range_from'],
                'range_to' => $range['range_to'],
                'grade' => $range['grade'],
                'remark' => $range['remark'],
                'gpa' => $range['gpa'],
                'grading_system_id' => $this->selectedGradingSystem,
                'subject_id' => $this->subjectId,
            ]);
        }

        session()->flash('message', 'Grading ranges saved successfully!');
        $this->reset(['subjectId', 'ranges']); // Reset fields after saving
        $this->loadSubmittedRanges();
    }

    public function editSubmittedRange($id)
    {
        $range = GradingRange::find($id);

        if ($range) {
            $this->ranges = [[
                'range_from' => $range->range_from,
                'range_to' => $range->range_to,
                'grade' => $range->grade,
                'remark' => $range->remark,
                'gpa' => $range->gpa,
            ]];

            $this->isEditing = true;
            $this->currentIndex = 0; // Set to the first and only range we are editing
        }
    }

    public function deleteRange($id)
    {
        GradingRange::destroy($id);
        session()->flash('message', 'Grading range deleted successfully!');
        $this->loadSubmittedRanges(); // Refresh the submitted ranges
    }

    protected function validateRanges()
    {
        foreach ($this->ranges as $index => $range) {
            $this->validate([
                "ranges.$index.range_from" => 'required|numeric',
                "ranges.$index.range_to" => 'required|numeric|gte:ranges.' . $index . '.range_from',
                "ranges.$index.grade" => 'required|string',
                "ranges.$index.remark" => 'nullable|string',
                "ranges.$index.gpa" => 'nullable|numeric',
            ]);
        }
    }

    public function loadSubmittedRanges()
    {
        $this->submittedRanges = GradingRange::with(['gradingSystem', 'subject'])
            ->where('grading_system_id', $this->selectedGradingSystem)
            ->where('subject_id', $this->subjectId)
            ->get();
    }

    protected function resetEditingState()
    {
        $this->isEditing = false;
        $this->currentIndex = null;
        $this->ranges = []; // Clear ranges after editing
    }

    public function render()
    {
        return view('livewire.grading-range-manager');
    }
} 