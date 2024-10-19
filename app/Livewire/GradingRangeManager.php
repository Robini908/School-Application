<?php

namespace App\Livewire;

use App\Models\Subject;
use Livewire\Component;
use App\Models\ExamMarks;
use App\Models\GradingRange;
use App\Models\GradingSystem;


class GradingRangeManager extends Component
{
    public $gradingSystems, $subjects, $selectedGradingSystem;
    public $ranges = [];
    public $reuseSubjectId; // Store the selected subject ID for reuse
    public $subjectId;
    public $submittedRanges = [];
    public $showForm = true; // Flag to control form visibility
    public $isLoading = false; // Flag for loading state
    public $isEditing = false;
    public $currentIndex = null;
    public $activeAction = null;
    public $examId = null;
    public $moreDetails = false;
    public $gradingSystemId = null;

    public $reuseGradingSystemId;
    public $reuseSubjectFromOtherSystemId;



    public function mount()
    {
        $this->gradingSystems = GradingSystem::all();
        $this->subjects = collect();
        $this->refreshGradingSystems();
    }



    public function reuseGradingRanges()
    {
        // Validate the selected subject for reuse
        if (!$this->reuseSubjectId) {
            session()->flash('error', 'Please select a subject to reuse grading ranges.');
            return;
        }

        // Fetch the subject names for better messaging
        $reuseSubject = Subject::whereHas('gradingRanges')->find($this->reuseSubjectId);
        $currentSubject = Subject::find($this->subjectId);

        if (!$reuseSubject || !$currentSubject) {
            session()->flash('error', 'Invalid subject selection. Please ensure the selected subjects are correct.');
            return;
        }

        // Check if the current subject already has grading ranges
        $alreadyExistingRanges = GradingRange::where('subject_id', $this->subjectId)
            ->where('grading_system_id', $this->selectedGradingSystem)
            ->exists();

        if ($alreadyExistingRanges) {
            session()->flash('error', 'Cannot reuse grading ranges from "' . $reuseSubject->subject_name . '" to "' . $currentSubject->subject_name . '" as the current subject already has existing ranges.');
            return;
        }

        // Fetch grading systems that already have grading ranges for the selected subject
        $existingGradingSystemsWithRanges = GradingRange::where('subject_id', $this->reuseSubjectId)
            ->pluck('grading_system_id')
            ->unique();

        // Check if the selected grading system has existing ranges
        if (!$existingGradingSystemsWithRanges->contains($this->selectedGradingSystem)) {
            session()->flash('error', 'The selected grading system does not have any existing grading ranges for reuse.');
            return;
        }

        // Fetch existing ranges for the selected subject and grading system
        $existingRanges = GradingRange::where('subject_id', $this->reuseSubjectId)
            ->where('grading_system_id', $this->selectedGradingSystem)
            ->get();

        // If no ranges found for reuse, show a warning message
        if ($existingRanges->isEmpty()) {
            session()->flash('warning', 'No grading ranges found for the subject: ' . $reuseSubject->subject_name . '. Please check if the grading ranges exist.');
            return;
        }

        // Populate the form with the existing ranges for reuse
        $this->ranges = $existingRanges->map(function ($range) {
            return [
                'range_from' => $range->range_from,
                'range_to' => $range->range_to,
                'grade' => $range->grade,
                'remark' => $range->remark,
                'gpa' => $range->gpa,
            ];
        })->toArray();

        // Inform the user that grading ranges have been loaded successfully
        session()->flash('info', 'Grading ranges loaded successfully. You can now adjust them as needed.');

        // Notify success for the completion of the process
        session()->flash('success', 'Grading ranges successfully loaded for reuse from "' . $reuseSubject->subject_name . '"!');
    }




    public function reuseGradingRangesFromOtherSystem()
    {
        // Validate the selected grading system and subject for reuse
        if (!$this->reuseGradingSystemId || !$this->reuseSubjectFromOtherSystemId) {
            session()->flash('error', 'Please select both a grading system and a subject to reuse grading ranges.');
            return;
        }

        // Fetch the subject and grading system names for better messaging
        $reuseGradingSystem = GradingSystem::find($this->reuseGradingSystemId);
        $reuseSubject = Subject::find($this->reuseSubjectFromOtherSystemId);
        $currentSubject = Subject::find($this->subjectId);
        $currentGradingSystem = GradingSystem::find($this->selectedGradingSystem);

        // Validate that both the grading systems and subjects exist
        if (!$reuseGradingSystem || !$reuseSubject || !$currentSubject || !$currentGradingSystem) {
            session()->flash('error', 'Invalid selection for grading system or subject. Please check your selections.');
            return;
        }

        // Fetch existing ranges for the selected subject and grading system
        $existingRanges = GradingRange::where('subject_id', $this->reuseSubjectFromOtherSystemId)
            ->where('grading_system_id', $this->reuseGradingSystemId)
            ->get();

        // If no ranges found for the selected subject and grading system, show an error message
        if ($existingRanges->isEmpty()) {
            session()->flash('error', 'No grading ranges found for the subject "' . $reuseSubject->subject_name . '" in the grading system "' . $reuseGradingSystem->name . '". Please ensure ranges are defined for this subject.');
            return;
        }

        // Check if the current subject already has grading ranges in the selected grading system
        $alreadyExistingRanges = GradingRange::where('subject_id', $this->subjectId)
            ->where('grading_system_id', $this->selectedGradingSystem)
            ->exists();

        if ($alreadyExistingRanges) {
            session()->flash('error', 'Cannot reuse grading ranges from "' . $reuseSubject->subject_name . '" in the grading system "' . $reuseGradingSystem->name . '" to "' . $currentSubject->subject_name . '" in the grading system "' . $currentGradingSystem->name . '" as the current subject already has existing ranges.');
            return;
        }

        // Populate the form with the existing ranges for reuse
        $this->ranges = $existingRanges->map(function ($range) {
            return [
                'range_from' => $range->range_from,
                'range_to' => $range->range_to,
                'grade' => $range->grade,
                'remark' => $range->remark,
                'gpa' => $range->gpa,
            ];
        })->toArray();

        // Success message with the grading system and subject names
        session()->flash('success', 'Grading ranges successfully loaded for reuse from the subject "' . $reuseSubject->subject_name . '" in the grading system "' . $reuseGradingSystem->name . '"! You can now adjust them as needed.');
    }


    public function suggestGradingRanges()
    {
        // Check if the grading system already has existing ranges
        $existingRanges = GradingRange::where('grading_system_id', $this->gradingSystemId)->exists();

        // If ranges exist, don't suggest new ones
        if ($existingRanges) {
            session()->flash('error', 'Grading ranges already exist for the selected grading system.');
            return;
        }

        // Define grades and their corresponding GPAs
        $grades = ['A', 'A-', 'B+', 'B', 'B-', 'C+', 'C', 'C-', 'D+', 'D', 'D-', 'E'];
        $gpas = [12, 11, 10, 9, 8, 7, 6, 5, 4, 3, 2, 1]; // Corresponding GPAs in the same order

        // Initialize suggested ranges
        $suggestedRanges = [];

        // Set ranges dynamically to ensure they cover 0 to 100
        $rangeLimits = [100, 90, 81, 72, 63, 54, 45, 36, 27, 18, 10, 8, 0];

        // Loop through the range limits to create dynamic ranges
        for ($i = 0; $i < count($rangeLimits) - 1; $i++) {
            $rangeFrom = $rangeLimits[$i + 1]; // Starting point for the current range
            $rangeTo = $rangeLimits[$i];       // Ending point for the current range

            // Prepare the suggested range
            $suggestedRanges[] = [
                'range_from' => $rangeFrom,
                'range_to' => $rangeTo,
                'grade' => $grades[$i],
                'remark' => $this->generateDynamicRemark($grades[$i], $this->getPerformanceDataFromAlternativeSource()),
                'gpa' => $this->suggestGPA($this->getPerformanceDataFromAlternativeSource(), $grades[$i], $gpas[$i]),
            ];
        }

        // Set the generated ranges to the form field
        $this->ranges = $suggestedRanges;

        session()->flash('message', 'New suggested grading ranges have been successfully populated.');
    }


    // Function to get performance data from an alternative source (mock example)
    private function getPerformanceDataFromAlternativeSource()
    {
        return [
            ['grade' => 'A', 'percentage' => 95],
            ['grade' => 'B+', 'percentage' => 72],
            ['grade' => 'C', 'percentage' => 55],
        ];
    }

    // Advanced remark generation based on historical data
    private function generateDynamicRemark($grade, $historicalData)
    {
        $baseRemarks = [
            'A' => 'Outstanding performance! Keep it up!',
            'A-' => 'Very good! A solid understanding of the material.',
            'B+' => 'Good work! You’re on the right track.',
            'B' => 'Above average performance; consider additional review.',
            'B-' => 'Average performance; focus on areas of improvement.',
            'C+' => 'Satisfactory but strive for more consistency.',
            'C' => 'Fair; consider seeking help for challenging topics.',
            'C-' => 'Needs improvement; additional study is recommended.',
            'D+' => 'Poor; you may need to revisit the core concepts.',
            'D' => 'Very poor; consider a study plan for recovery.',
            'D-' => 'Fail; significant improvement needed.',
            'E' => 'Fail; seek immediate assistance and resources.',
        ];

        foreach ($historicalData as $data) {
            if ($data['grade'] === $grade) {
                if ($data['percentage'] > 80) {
                    return $baseRemarks[$grade] . ' Your past performance supports this.';
                } elseif ($data['percentage'] < 40) {
                    return $baseRemarks[$grade] . ' Consider extra support.';
                }
            }
        }

        return $baseRemarks[$grade] ?? 'No Remark available';
    }

    // Suggest GPA based on historical performance and default value
    private function suggestGPA($historicalData, $grade, $defaultGpa)
    {
        foreach ($historicalData as $data) {
            if ($data['grade'] === $grade) {
                if ($data['percentage'] > 90) {
                    return min($defaultGpa + 1, 12); // Increase for high performance, max at 12
                } elseif ($data['percentage'] < 60) {
                    return max($defaultGpa - 1, 0); // Decrease for low performance
                }
            }
        }
        return $defaultGpa; // Return default if no adjustments are made
    }



    public function refreshGradingSystems()
    {
        // Fetch grading systems with a loading state
        $this->gradingSystems = GradingSystem::all(); // Reload grading systems
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

    public function saveRanges()
    {
        $this->isLoading = true; // Show loading state
        $this->validateRanges();

        // Log the ranges for debugging
        \Log::info('Ranges to save:', $this->ranges);

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
        $this->reset(['subjectId', 'ranges', 'showForm']); // Reset and hide the form
        $this->loadSubmittedRanges();
        $this->isLoading = false; // Hide loading state
    }


    public function updateRange()
    {
        $this->isLoading = true; // Show loading state
        $this->validateRanges();

        // Log the current index and submitted ranges for debugging
        \Log::info('Current index:', $this->currentIndex);
        \Log::info('Submitted ranges:', $this->submittedRanges);

        // Ensure current index is within bounds
        if (isset($this->submittedRanges[$this->currentIndex])) {
            $rangeToUpdate = GradingRange::find($this->submittedRanges[$this->currentIndex]->id);

            if ($rangeToUpdate) {
                $rangeToUpdate->update([
                    'range_from' => $this->ranges[0]['range_from'],
                    'range_to' => $this->ranges[0]['range_to'],
                    'grade' => $this->ranges[0]['grade'],
                    'remark' => $this->ranges[0]['remark'],
                    'gpa' => $this->ranges[0]['gpa'],
                ]);
            } else {
                session()->flash('error', 'Grading range not found for updating.');
            }
        } else {
            session()->flash('error', 'Invalid index for submitted ranges.');
        }

        session()->flash('message', 'Grading range updated successfully!');
        $this->resetEditingState();
        $this->loadSubmittedRanges();
        $this->isLoading = false; // Hide loading state
    }


    protected function resetEditingState()
    {
        $this->isEditing = false;
        $this->currentIndex = null;
        $this->ranges = [];
        $this->showForm = false; // Close the form 
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




    public function render()
    {
        // Fetch dynamic potential scores based on student marks for the specific exam
        $examMarks = ExamMarks::where('exam_id', $this->examId) // Assuming you have an examId to filter by
            ->get();

        $potentialScores = $examMarks->pluck('marks')->toArray(); // Get marks as an array

        // Perform analysis using the potential scores

        return view('livewire.grading-range-manager', [
            'submittedRanges' => $this->submittedRanges,
        ]);
    }
}
