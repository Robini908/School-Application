<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\GradingGrade;
use App\Models\GradingSystem;
use Illuminate\Support\Facades\Validator;

class ManageGrades extends Component
{
    public $gradingSystemId;
    public $grades = [];
    public $newGrade = [];

    public $gradingSystems = []; // To store grading systems
    public $selectedGradingSystem; // To store the selected grading system
    public $isEditing = []; // Track editing state for each grading system
    public $isCreating = false;
    public $gradeIdBeingEdited = null;
    public $editedGradeIndex = null; // Track the row being edited
    public $showGradeForm = []; // Track form visibility for each grading system
    public $showForm = false; // Toggle form visibility
    public $newGrades = []; // Store multiple grades temporarily
    public $isEditingNewGrade = []; // Track which newGrade is in edit mode
    public $newRemark;
    public $newGpa;
    public $newDescription;
    public $newAdditionalInfo;
    public $selectedGradingSystemForReuse; // For selecting a system to reuse grades from
    public $gradingSystemsWithGrades = []; // To hold grading systems with grades


    public $loading = false;
    public $addedGrade = []; // Initialize the array for grades


    public $editingIndex = null; // Track which grade is being edited
    public $deleteGradeId = null;
    public $deleteGradingSystemId = null;
    public $isConfirmingDeleting = false;


    public function confirmDelete($gradingSystemId, $gradeId)
    {
        // Set the IDs and open the modal
        $this->deleteGradeId = $gradeId;
        $this->deleteGradingSystemId = $gradingSystemId;
        $this->isConfirmingDeleting = true;
    }

    public function cancelDelete()
    {
        // Close modal and reset variables
        $this->resetDeleteState();
    }

    public function deleteGrade()
    {
        try {
            // Find and delete the grade
            GradingGrade::where('grading_system_id', $this->deleteGradingSystemId)
                ->where('id', $this->deleteGradeId)
                ->delete();

            // Reset variables and close modal
            $this->resetDeleteState();
            $this->isConfirmingDeleting = false;
            $this->fetchGradingSystems();

            // Flash success message
            session()->flash('message', 'Grade deleted successfully!');
        } catch (\Exception $e) {
            // Flash error message if something goes wrong
            session()->flash('error', 'Failed to delete the grade.');
        }
    }


    private function resetDeleteState()
    {
        $this->deleteGradeId = null;
        $this->deleteGradingSystemId = null;
        $this->isConfirmingDeleting = false;
    }



    public function editGrade($gradeId, $index, $gradingSystemId)
    {
        $this->editedGradeIndex = $index;
        $this->gradingSystemId = $gradingSystemId;
        $this->isEditing[$gradingSystemId] = true;
    }


    public function mount()
    {
        // Initialize editing and form visibility state for each grading system
        $this->fetchGradingSystems();
        $this->gradingSystemsWithGrades = GradingSystem::whereHas('grades')->get();
    }

    public function reuseGrades()
    {
        if ($this->selectedGradingSystemForReuse) {
            $gradingSystem = GradingSystem::with('grades')->find($this->selectedGradingSystemForReuse);
            $this->addedGrade = $gradingSystem->grades->toArray(); // Reuse the grades
        }
    }

    // Fetch grading systems from the database with their associated grades
    public function fetchGradingSystems()
    {
        $this->gradingSystems = GradingSystem::with('grades')->get();

        // Initialize state variables for editing and displaying forms for each grading system
        foreach ($this->gradingSystems as $system) {
            $this->isEditing[$system->id] = false;
            $this->showGradeForm[$system->id] = false;
            $this->grades[$system->id] = $system->grades->toArray();
        }
    }

    public function toggleNewGradeForm()
    {
        $this->showForm = !$this->showForm; // Show/Hide form
    }



    public function addGrade()
    {
        // Validate inputs
        $this->validate([
            'newGrade' => 'required|string|max:255',
            'newRemark' => 'nullable|string|max:255',
            'newGpa' => 'nullable|numeric',
            'newDescription' => 'nullable|string|max:255',
            'newAdditionalInfo' => 'nullable|string|max:255',
        ]);

        // Create a new grade array
        $newGradeData = [
            'grade' => $this->newGrade,
            'remark' => $this->newRemark,
            'gpa' => $this->newGpa,
            'description' => $this->newDescription,
            'additional_info' => $this->newAdditionalInfo,
        ];

        // Push the new grade data to the addedGrade array
        $this->addedGrade[] = $newGradeData;

        // Clear the input fields after adding
        $this->newGrade = '';
        $this->newRemark = '';
        $this->newGpa = '';
        $this->newDescription = '';
        $this->newAdditionalInfo = '';
        $this->isConfirmingDeleting = false;
        $this->fetchGradingSystems();
    }

    // Method to remove a grade
    public function removeGrade($index)
    {
        unset($this->addedGrade[$index]);
        // Re-index the array after removal
        $this->addedGrade = array_values($this->addedGrade);
    }



    public function saveGrades()
    {
        $this->loading = true;

        try {
            // Loop through added grades and save each one to the database
            foreach ($this->addedGrade as $gradeData) {
                GradingGrade::create([
                    'grading_system_id' => $this->selectedGradingSystem,
                    'grade' => $gradeData['grade'],
                    'remark' => $gradeData['remark'],
                    'gpa' => $gradeData['gpa'] ?? null,
                    'description' => $gradeData['description'] ?? null,
                    'additional_info' => $gradeData['additional_info'] ?? null,
                ]);
            }

            $this->loading = false;

            // Reset after saving
            $this->addedGrade = [];
            $this->selectedGradingSystem = '';
            $this->newGrade = '';
            $this->newRemark = '';
            $this->newGpa = '';
            $this->newDescription = '';
            $this->newAdditionalInfo = '';

            // Flash success message
            session()->flash('message', 'Grades saved successfully!');
        } catch (\Exception $e) {
            $this->loading = false;

            // Flash error message
            session()->flash('error', 'Failed to save grades. Please try again.');
        }

        // Fetch grading systems after saving
        $this->fetchGradingSystems();
    }
    public function updateGrade($index)
    {
        // Ensure the grade data exists
        if (isset($this->addedGrade[$index])) {
            $gradeData = $this->addedGrade[$index];

            try {
                // Validate the input data before updating
                $this->validate([
                    'addedGrade.' . $index . '.grade' => 'required|string|max:255',
                    'addedGrade.' . $index . '.remark' => 'nullable|string|max:255',
                    'addedGrade.' . $index . '.description' => 'nullable|string|max:255',
                    'addedGrade.' . $index . '.additional_info' => 'nullable|string|max:255',
                ]);

                // Update or create the grade record in the database
                GradingGrade::updateOrCreate(
                    [
                        'grading_system_id' => $this->selectedGradingSystem,
                        'grade' => $gradeData['grade'], // This will be the unique identifier for the grade
                    ],
                    [
                        'remark' => $gradeData['remark'],
                        'gpa' => $gradeData['gpa'] ?? null, // Set GPA as null if not provided
                        'description' => $gradeData['description'],
                        'additional_info' => $gradeData['additional_info'],
                    ]
                );

                // Provide success feedback to the user
                session()->flash('message', 'Grade updated successfully!');
            } catch (\Exception $e) {
                // Provide error feedback to the user
                session()->flash('error', 'Failed to update the grade. Please try again.');
            }
        } else {
            // If the grade data does not exist, show an error
            session()->flash('error', 'Grade data not found.');
        }
    }




    public function saveEditedGrades($gradingSystemId)
    {
        try {
            $editedGrade = $this->grades[$gradingSystemId][$this->editedGradeIndex];

            // Save the updated grade to the database
            GradingGrade::where('id', $editedGrade['id'])->update([
                'grade' => $editedGrade['grade'],
                'remark' => $editedGrade['remark'],
                'gpa' => $editedGrade['gpa'],
                'description' => $editedGrade['description'],
                'additional_info' => $editedGrade['additional_info'],
            ]);

            // Reset editing state and fetch updated grading systems
            $this->isEditing[$gradingSystemId] = false;
            $this->fetchGradingSystems();

            // Provide success feedback to the user
            session()->flash('message', 'Grade saved successfully!');
        } catch (\Exception $e) {
            // Provide error feedback to the user
            session()->flash('error', 'Failed to save the grade. Please try again.');
        }
    }




    public function cancelEdit($gradingSystemId)
    {
        $this->isEditing[$gradingSystemId] = false;
        $this->editedGradeIndex = null;
        $this->gradingSystemId = null;
    }


    public function removeGradeInput($gradingSystemId, $index)
    {
        // Logic to remove the grade input, both from the form and the database if necessary
        unset($this->grades[$gradingSystemId][$index]);
    }


    public function render()
    {
        // Fetch all grading systems along with their associated grades
        $gradingSystems = GradingSystem::with('grades')->get();

        // Pass the grades array to the view for rendering
        return view('livewire.manage-grades', [
            'gradingSystems' => $gradingSystems,
            'grades' => $this->grades, // Ensure grades are passed to the view
        ]);
    }
}
