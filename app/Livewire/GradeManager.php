<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\GradingGrade;
use App\Models\GradingSystem;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class GradeManager extends Component
{  use LivewireAlert;
    public $gradingSystems;
    public $selectedGradingSystemId = null;
    public $applyToAllSystems = false;
    public $gradesList = [];
    public $duplicates;
    public $showForm = false;
    public $gradingSystemName;
    public $gradingSystemDescription;

    public function mount()
    {
        $this->gradingSystems = GradingSystem::all();
        $this->selectedGradingSystemId = $this->gradingSystems->first()->id ?? null;
        $this->loadGrades();

        // Ensure at least one default row is present
        if (empty($this->gradesList)) {
            $this->gradesList[] = $this->createDefaultGrade();
        }
    }

    public function updatedSelectedGradingSystemId()
    {
        $this->loadGrades();
    }

    public function saveGradesForAll()
    {
        // Validate grades
        foreach ($this->gradesList as $index => $grade) {
            $this->validateGrade($index);
        }

        // Check for duplicates
        $uniqueGrades = [];
        $duplicates = [];
        foreach ($this->gradesList as $grade) {
            if (in_array($grade['grade'], $uniqueGrades)) {
                $duplicates[] = $grade['grade'];
            } else {
                $uniqueGrades[] = $grade['grade'];
            }
        }

        if (!empty($duplicates)) {
            $this->alert('error', 'The following grades are duplicates and were not applied: ' . implode(', ', $duplicates));
        }

        // Database transaction for updates
        DB::transaction(function () use ($duplicates) {
            foreach ($this->gradingSystems as $system) {
                foreach ($this->gradesList as $grade) {
                    if (in_array($grade['grade'], $duplicates)) {
                        continue; // Skip duplicates
                    }

                    GradingGrade::updateOrCreate(
                        [
                            'id' => $grade['id'] ?? null,
                            'grading_system_id' => $system->id,
                        ],
                        array_merge($grade, ['grading_system_id' => $system->id])
                    );
                }
            }
        });

        $this->loadGrades();
        $this->showForm = false;
        $this->alert('success', 'Grades applied successfully to all grading systems!');
    }

    public function loadGrades()
    {
        $gradingSystem = GradingSystem::find($this->selectedGradingSystemId);
        $this->gradingSystemName = $gradingSystem->name ?? 'N/A';
        $this->gradingSystemDescription = $gradingSystem->description ?? 'No description available.';

        $this->gradesList = GradingGrade::where('grading_system_id', $this->selectedGradingSystemId)
            ->get()
            ->map(function ($grade) {
                return [
                    'id' => $grade->id,
                    'grade' => $grade->grade,
                    'remark' => $grade->remark,
                    'gpa' => $grade->gpa,
                    'range_from' => $grade->range_from,
                    'range_to' => $grade->range_to,
                    'isEditing' => false,
                ];
            })
            ->toArray();
    }

    protected function createDefaultGrade()
    {
        return [
            'id' => null,
            'grade' => '',
            'remark' => '',
            'gpa' => null,
            'range_from' => null,
            'range_to' => null,
            'isEditing' => false,
        ];
    }

    public function showAddForm()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function addGradeInput()
    {
        $this->gradesList[] = $this->createDefaultGrade();
    }

    public function removeGradeInput($index)
    {
        if (count($this->gradesList) > 1) {
            unset($this->gradesList[$index]);
            $this->gradesList = array_values($this->gradesList); // Re-index the array
            $this->alert('success', 'Grade removed successfully!'); // Success success
        } else {
            $this->alert('error', 'You must have at least one grade.');
        }
    }

    public function editGrade($index)
    {
        $this->gradesList[$index]['isEditing'] = true; // Set the selected grade to editing mode
    }

    public function updateGrade($index)
    {
        $validatedData = $this->validateGrade($index);

        // Update the grade
        GradingGrade::updateOrCreate(
            ['id' => $this->gradesList[$index]['id']],
            array_merge($validatedData, ['grading_system_id' => $this->selectedGradingSystemId])
        );

        // Update local state
        $this->gradesList[$index]['isEditing'] = false;
        $this->loadGrades(); // Reload grades to refresh the frontend
        $this->alert('success', 'Grade updated successfully!');
    }

    public function saveGrades()
    {
        foreach ($this->gradesList as $index => $grade) {
            $this->validateGrade($index);
        }

        foreach ($this->gradesList as $grade) {
            GradingGrade::updateOrCreate(
                ['id' => $grade['id'] ?? null],
                array_merge($grade, ['grading_system_id' => $this->selectedGradingSystemId])
            );
        }

        $this->showForm = false; // Hide the form after saving
        $this->loadGrades(); // Reload grades

        $this->alert('success', 'Grades saved successfully!');
    }

    public function cancelEdit($index)
    {
        $this->gradesList[$index]['isEditing'] = false; // Exit editing mode without saving
    }

    public function cancelForm()
    {
        $this->showForm = false; // Hide the form
        $this->loadGrades(); // Reload grades
    }

    public function deleteGrade($index)
    {
        if (isset($this->gradesList[$index]['id'])) {
            GradingGrade::destroy($this->gradesList[$index]['id']); // Delete from the database
            unset($this->gradesList[$index]); // Remove from list
            $this->gradesList = array_values($this->gradesList); // Re-index the array
            $this->alert('success', 'Grade deleted successfully!'); // Success success
        } else {
            $this->alert('error', 'Grade not found for deletion.');
        }
    }

    private function validateGrade($index)
    {
        return $this->validate([
            'gradesList.' . $index . '.grade' => 'required|string|unique:grading_grades,grade,except,id|max:2|in:A,A-,B+,B,B-,C+,C,C-,D+,D,D-,E',
            'gradesList.' . $index . '.remark' => 'nullable|string',
            'gradesList.' . $index . '.gpa' => 'required|numeric|max:13.00', // Updated max to allow decimals
            'gradesList.' . $index . '.range_from' => 'required|numeric',
            'gradesList.' . $index . '.range_to' => 'required|numeric|gt:gradesList.' . $index . '.range_from',
        ]);
    }

    public function resetForm()
    {
        $this->gradesList = []; // Clear the current grades list
        $this->showForm = false; // Hide the form
        $this->selectedGradingSystemId = $this->gradingSystems->first()->id ?? null; // Reset to the first grading system
        $this->applyToAllSystems = false; // Reset the apply to all systems checkbox

        // Re-initialize with default grade
        $this->gradesList[] = $this->createDefaultGrade();
    }

    public function render()
    {
        return view('livewire.grade-manager'); // Ensure your layout is set correctly
    }
}
