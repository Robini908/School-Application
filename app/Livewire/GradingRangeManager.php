<?php

namespace App\Livewire;

use App\Models\Subject;
use Livewire\Component;
use App\Models\ExamMarks;
use App\Models\GradingRange;
use App\Models\GradingSystem;
use Livewire\Attributes\Lazy;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Phpml\Classification\KNearestNeighbors;


#[Lazy]
class GradingRangeManager extends Component
{

    use LivewireAlert;
    public $gradingSystems, $subjects, $selectedGradingSystem;
    public $ranges = [];

    public $reuseSubjectId; // Store the selected subject ID for reuse
    public $subjectId;
    public $submittedRanges;
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
    public $hasAssignedRanges = false;

    protected $listeners = [
        'deleteRange'
    ];





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

            $this->alert('error', 'Please select a subject to reuse grading ranges.', [
                'position' => 'top', 
                'showConfirmButton' => true,
                'confirmButtonText' => 'OK', 

                'reverseButtons' => true, 
                'timer' => 30000,
                'toast' => false,
            ]);
            return;
        }

        // Fetch the subject names for better messaging
        $reuseSubject = Subject::whereHas('gradingRanges')->find($this->reuseSubjectId);
        $currentSubject = Subject::find($this->subjectId);

        if (!$reuseSubject || !$currentSubject) {
            $this->alert('error', 'Invalid subject selection. Please ensure the selected subjects are correct.', [
                'position' => 'top', // Position on screen (can be top, top-end, bottom, etc.)
                'showConfirmButton' => true, // Show a confirmation button
                'confirmButtonText' => 'OK', // Text on the confirm button

                'reverseButtons' => true, // Reverse the order of buttons
                'timer' => 30000, // Time before it automatically closes
                'toast' => false, // If you want it to be a toast notification or a modal
            ]);
            return;
        }

        // Check if the current subject already has grading ranges
        $alreadyExistingRanges = GradingRange::where('subject_id', $this->subjectId)
            ->where('grading_system_id', $this->selectedGradingSystem)
            ->exists();

        if ($alreadyExistingRanges) {
            $this->alert('error', 'Cannot reuse grading ranges from "' . $reuseSubject->subject_name . '" to "' . $currentSubject->subject_name . '" as the current subject already has existing ranges.', [
                'position' => 'top', // Position on screen (can be top, top-end, bottom, etc.)
                'showConfirmButton' => true, // Show a confirmation button
                'confirmButtonText' => 'OK', // Text on the confirm button

                'reverseButtons' => true, // Reverse the order of buttons
                'timer' => 30000, // Time before it automatically closes
                'toast' => false, // If you want it to be a toast notification or a modal
            ]);
            return;
        }

        // Fetch grading systems that already have grading ranges for the selected subject
        $existingGradingSystemsWithRanges = GradingRange::where('subject_id', $this->reuseSubjectId)
            ->pluck('grading_system_id')
            ->unique();

        // Check if the selected grading system has existing ranges
        if (!$existingGradingSystemsWithRanges->contains($this->selectedGradingSystem)) {

            $this->alert('error', 'The selected grading system does not have any existing grading ranges for reuse.', [
                'position' => 'top', // Position on screen (can be top, top-end, bottom, etc.)
                'showConfirmButton' => true, // Show a confirmation button
                'confirmButtonText' => 'OK', // Text on the confirm button

                'reverseButtons' => true, // Reverse the order of buttons
                'timer' => 30000, // Time before it automatically closes
                'toast' => false, // If you want it to be a toast notification or a modal
            ]);
            return;
        }

        // Fetch existing ranges for the selected subject and grading system
        $existingRanges = GradingRange::where('subject_id', $this->reuseSubjectId)
            ->where('grading_system_id', $this->selectedGradingSystem)
            ->get();

        // If no ranges found for reuse, show a warning message
        if ($existingRanges->isEmpty()) {

            $this->alert('error', 'No grading ranges found for the subject: ' . $reuseSubject->subject_name . '. Please check if the grading ranges exist.', [
                'position' => 'top', // Position on screen (can be top, top-end, bottom, etc.)
                'showConfirmButton' => true, // Show a confirmation button
                'confirmButtonText' => 'OK', // Text on the confirm button
                'showCancelButton' => true, // Show a cancel button

                'timer' => 30000, // Time before it automatically closes
                'toast' => false, // If you want it to be a toast notification or a modal
            ]);
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
        $this->alert('error', 'Grading ranges loaded successfully. You can now adjust them as needed.', [
            'position' => 'top', // Position on screen (can be top, top-end, bottom, etc.)
            'showConfirmButton' => true, // Show a confirmation button
            'confirmButtonText' => 'OK', // Text on the confirm button
            'showCancelButton' => true, // Show a cancel button

            'timer' => 30000, // Time before it automatically closes
            'toast' => false, // If you want it to be a toast notification or a modal
        ]);

        // Notify success for the completion of the process
        $this->alert('error', 'Grading ranges successfully loaded for reuse from "' . $reuseSubject->subject_name . '"!', [
            'position' => 'top', // Position on screen (can be top, top-end, bottom, etc.)
            'showConfirmButton' => true, // Show a confirmation button
            'confirmButtonText' => 'OK', // Text on the confirm button

            'reverseButtons' => true, // Reverse the order of buttons
            'timer' => 30000, // Time before it automatically closes
            'toast' => false, // If you want it to be a toast notification or a modal
        ]);
    }




    public function reuseGradingRangesFromOtherSystem()
    {
        // Validate the selected grading system and subject for reuse
        if (!$this->reuseGradingSystemId || !$this->reuseSubjectFromOtherSystemId) {
            $this->alert('error', 'Please select both a grading system and a subject to reuse grading ranges.', [
                'position' => 'top', // Position on screen (can be top, top-end, bottom, etc.)
                'showConfirmButton' => true, // Show a confirmation button
                'confirmButtonText' => 'OK', // Text on the confirm button

                'reverseButtons' => true, // Reverse the order of buttons
                'timer' => 30000, // Time before it automatically closes
                'toast' => false, // If you want it to be a toast notification or a modal
            ]);
            return;
        }

        // Fetch the subject and grading system names for better messaging
        $reuseGradingSystem = GradingSystem::find($this->reuseGradingSystemId);
        $reuseSubject = Subject::find($this->reuseSubjectFromOtherSystemId);
        $currentSubject = Subject::find($this->subjectId);
        $currentGradingSystem = GradingSystem::find($this->selectedGradingSystem);

        // Validate that both the grading systems and subjects exist
        if (!$reuseGradingSystem || !$reuseSubject || !$currentSubject || !$currentGradingSystem) {
            $this->alert('error', 'Invalid selection for grading system or subject. Please check your selections.', [
                'position' => 'top', // Position on screen (can be top, top-end, bottom, etc.)
                'showConfirmButton' => true, // Show a confirmation button
                'confirmButtonText' => 'OK', // Text on the confirm button

                'reverseButtons' => true, // Reverse the order of buttons
                'timer' => 30000, // Time before it automatically closes
                'toast' => false, // If you want it to be a toast notification or a modal
            ]);
            return;
        }

        // Fetch existing ranges for the selected subject and grading system
        $existingRanges = GradingRange::where('subject_id', $this->reuseSubjectFromOtherSystemId)
            ->where('grading_system_id', $this->reuseGradingSystemId)
            ->get();

        // If no ranges found for the selected subject and grading system, show an error message
        if ($existingRanges->isEmpty()) {
            $this->alert('error', 'No grading ranges found for the subject "' . $reuseSubject->subject_name . '" in the grading system "' . $reuseGradingSystem->name . '". Please ensure ranges are defined for this subject.', [
                'position' => 'top', // Position on screen (can be top, top-end, bottom, etc.)
                'showConfirmButton' => true, // Show a confirmation button
                'confirmButtonText' => 'OK', // Text on the confirm button

                'reverseButtons' => true, // Reverse the order of buttons
                'timer' => 30000, // Time before it automatically closes
                'toast' => false, // If you want it to be a toast notification or a modal
            ]);
            return;
        }

        // Check if the current subject already has grading ranges in the selected grading system
        $alreadyExistingRanges = GradingRange::where('subject_id', $this->subjectId)
            ->where('grading_system_id', $this->selectedGradingSystem)
            ->exists();

        if ($alreadyExistingRanges) {
            $this->alert('error', 'Cannot reuse grading ranges from "' . $reuseSubject->subject_name . '" in the grading system "' . $reuseGradingSystem->name . '" to "' . $currentSubject->subject_name . '" in the grading system "' . $currentGradingSystem->name . '" as the current subject already has existing ranges.', [
                'position' => 'top', // Position on screen (can be top, top-end, bottom, etc.)
                'showConfirmButton' => true, // Show a confirmation button
                'confirmButtonText' => 'OK', // Text on the confirm button

                'reverseButtons' => true, // Reverse the order of buttons
                'timer' => 30000, // Time before it automatically closes
                'toast' => false, // If you want it to be a toast notification or a modal
            ]);
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

        // Success  with the grading system and subject names
        $this->alert('sucess', 'Grading ranges successfully loaded for reuse from the subject "' . $reuseSubject->subject_name . '" in the grading system "' . $reuseGradingSystem->name . '"! You can now adjust them as needed.', [
            'position' => 'top', // Position on screen (can be top, top-end, bottom, etc.)
            'showConfirmButton' => true, // Show a confirmation button
            'confirmButtonText' => 'OK', // Text on the confirm button


            'reverseButtons' => true, // Reverse the order of buttons
            'timer' => 30000, // Time before it automatically closes
            'toast' => false, // If you want it to be a toast notification or a modal
        ]);
    }


public function suggestGradingRanges()
{
    // Check if the grading system already has existing ranges
    $existingRanges = GradingRange::where('grading_system_id', $this->gradingSystemId)->exists();

    // If ranges exist, don't suggest new ones
    if ($existingRanges) {
        $this->alert('error', 'Grading ranges already exist for the selected grading system.', [
                'position' => 'top', // Position on screen (can be top, top-end, bottom, etc.)
                'showConfirmButton' => true, // Show a confirmation button
                'confirmButtonText' => 'OK', // Text on the confirm button


                'reverseButtons' => true, // Reverse the order of buttons
                'timer' => 30000, // Time before it automatically closes
                'toast' => false, // If you want it to be a toast notification or a modal
        ]);
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

    $this->alert('success', 'New suggested grading ranges have been successfully populated.', [
            'position' => 'top', // Position on screen (can be top, top-end, bottom, etc.)
            'showConfirmButton' => true, // Show a confirmation button
            'confirmButtonText' => 'OK', // Text on the confirm button
            'reverseButtons' => true, // Reverse the order of buttons
            'timer' => 30000, // Time before it automatically closes
            'toast' => false, // If you want it to be a toast notification or a modal
    ]);
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

        // Initialize the ranges array with the data of the row being edited
        $submittedRange = $this->submittedRanges[$index];
        $this->ranges[$index] = [
            'range_from' => $submittedRange->range_from,
            'range_to' => $submittedRange->range_to,
            'grade' => $submittedRange->grade,
            'remark' => $submittedRange->remark,
            'gpa' => $submittedRange->gpa,
        ];
    }

    public function submitRange($index)
    {
        // Make sure we are editing the correct index
        $rangeToUpdate = $this->submittedRanges[$index];

        $rangeToUpdate->update([
            'range_from' => $this->ranges[$index]['range_from'],
            'range_to' => $this->ranges[$index]['range_to'],
            'grade' => $this->ranges[$index]['grade'],
            'remark' => $this->ranges[$index]['remark'],
            'gpa' => $this->ranges[$index]['gpa'],
        ]);

        $this->alert('success', 'Grading range updated successfully!');
        $this->resetEditingState();
        $this->$this->syncRanges();  // Refresh the ranges after updating
    }

    public function cancelEdit()
    {
        $this->resetEditingState();
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
        Log::info('Ranges to save:', $this->ranges);

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

        $this->alert('success', 'Grading ranges saved successfully!');

        // After saving, synchronize the ranges
        $this->syncRanges();

        // Reset and hide the form
        $this->reset(['ranges', 'showForm']);
        $this->isLoading = false; // Hide loading state
    }


    public function updateRange()
    {
        $this->isLoading = true; // Show loading state
        $this->validateRanges();

        // Log the current index and submitted ranges for debugging
        Log::info('Current index:', $this->currentIndex);
        Log::info('Submitted ranges:', $this->submittedRanges);

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
                $this->alert('error', 'Grading range not found for updating.');
            }
        } else {
            $this->alert('error', 'Invalid index for submitted ranges.');
        }

        $this->alert('success', 'Grading range updated successfully!');
        $this->resetEditingState();
        $this->$this->syncRanges();
        $this->isLoading = false; // Hide loading state
    }




    protected function resetEditingState()
    {
        $this->isEditing = false;
        $this->currentIndex = null;
        $this->ranges = [];
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
        $this->alert('success', 'Grading range deleted successfully!');

        $this->syncRanges(); // Refresh the submitted ranges
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



    public function updatedSubjectId()
    {
        // Reset ranges and initialize for the new subject
        $this->ranges = [];
        $this->addRange(); // Initialize ranges for the new subject

        // Synchronize the data for the new subject
        $this->syncRanges();

        // Ensure that the hasAssignedRanges flag is updated
        $this->hasAssignedRanges = $this->hasSubmittedRanges(); // Ensure flag is recalculated here

        // Load the submitted ranges
        $this->loadSubmittedRanges();
    }






    public function syncRanges()
    {
        // Reload submitted ranges and reset flags for synchronization
        $this->loadSubmittedRanges();

        // Ensure the correct display flag is set
        $this->resetRangesFlag();

        // Trigger the event for synchronization
        $this->dispatch('rangesUpdated');
    }



    public function loadSubmittedRanges()
    {
        // Reload the ranges from the database for the selected grading system and subject
        $this->submittedRanges = GradingRange::with(['gradingSystem', 'subject'])
            ->where('grading_system_id', $this->selectedGradingSystem)
            ->where('subject_id', $this->subjectId)
            ->get();
    
        // Set the flag using the helper method to check if ranges are assigned
        $this->hasAssignedRanges = $this->hasSubmittedRanges();  // Ensure it's updated immediately after loading the ranges
    }
    



    public function hasSubmittedRanges(): bool
    {
        // Check if there are any submitted ranges for the current grading system and subject
        return GradingRange::where('grading_system_id', $this->selectedGradingSystem)
            ->where('subject_id', $this->subjectId)
            ->exists();
    }

    public function resetRangesFlag()
    {
        // Reset the `hasAssignedRanges` flag to ensure correct rendering
        $this->hasAssignedRanges = $this->hasSubmittedRanges();
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
