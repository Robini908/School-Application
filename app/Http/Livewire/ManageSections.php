<?php

namespace App\Http\Livewire;

use App\User;
use App\Models\MyClass;
use App\Models\Section;
use Livewire\Component;
use App\Models\StudentRecord;
use App\Models\Subject;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class ManageSections extends Component
{
    public $sections;
    public $my_classes;
    public $teachers;
    public $selectedClass = null;
    public $name;
    public $allTeachersAssignedMessage = null;
    public $teacher_id;
    public $my_class_id;
    public $isCreating = false;
    public $isEditing = false;
    public $editSectionId = null;
    public $confirmingDelete = false;
    public $deleteSectionId = null;
    public $isAssigningTeacher = false;
    public $sectionDetails = null; // For storing the section details
    public $students = []; // For storing students in the section
    public $subjects = []; // For storing subjects related to the section

    protected $rules = [
        'name' => 'required|string',
        'my_class_id' => 'required|exists:my_classes,id',
        'teacher_id' => 'nullable|exists:users,id',
    ];

    public function mount()
    {
        $this->my_classes = MyClass::all();
        $this->teachers = User::where('user_type', 'teacher')->get();
        $this->loadSections();
        $this->checkAllTeachersAssigned();
    }

    public function showDetails($sectionId)
    {
        try {
            // Fetch the section details with related class and teacher
            $this->sectionDetails = Section::with(['my_class', 'teacher'])->findOrFail($sectionId);

            // Fetch students in this section
            $this->students = StudentRecord::where('section_id', $sectionId)->get();

            // Fetch all subjects from the database
            $this->subjects = Subject::all();
        } catch (ModelNotFoundException $e) {
            session()->flash('error', 'Section not found.');
        } catch (Exception $e) {
            session()->flash('error', 'An error occurred while fetching details.');
        }
    }

    public function closeDetails()
    {
        $this->sectionDetails = null; // Reset the section details
        $this->students = []; // Clear the students array
        $this->subjects = []; // Clear the subjects array
    }

    public function updatedTeacherId($value)
    {
        $this->checkAllTeachersAssigned();
    }

    public function updatedSelectedClass()
    {
        $this->loadSections();
        $this->checkAllTeachersAssigned();
    }

    public function checkAllTeachersAssigned()
    {
        $assignedTeachers = Section::whereNotNull('teacher_id')->distinct('teacher_id')->pluck('teacher_id');
        $totalTeachersCount = $this->teachers->count();

        if ($totalTeachersCount === 0) {
            $this->allTeachersAssignedMessage = 'No teachers available for assignment.';
        } elseif ($assignedTeachers->count() >= $totalTeachersCount) {
            $this->allTeachersAssignedMessage = 'All teachers are assigned to classes.';
        } else {
            $this->allTeachersAssignedMessage = null; // No message needed if some teachers are available
        }
    }

    public function loadSections()
    {
        $this->sections = Section::with('my_class', 'teacher')
            ->when($this->selectedClass, function ($query) {
                $query->where('my_class_id', $this->selectedClass);
            })
            ->get();
    }

    public function create()
    {
        $this->resetForm();
        $this->isCreating = true;
    }

    public function edit($sectionId)
    {
        try {
            $this->isEditing = true;
            $this->editSectionId = $sectionId;

            $section = Section::findOrFail($sectionId);
            $this->name = $section->name;
            $this->my_class_id = $section->my_class_id;
            $this->teacher_id = $section->teacher_id;

            // Check if there are available teachers for this section
            $this->checkAllTeachersAssigned();
        } catch (ModelNotFoundException $e) {
            session()->flash('error', 'Section not found for editing.');
        } catch (Exception $e) {
            session()->flash('error', 'An error occurred while loading the section for editing.');
        }
    }

    public function save()
    {
        $this->validate();

        try {
            // Check if the section name is unique for the given class when creating or editing
            $sectionExists = Section::where('name', $this->name)
                ->where('my_class_id', $this->my_class_id)
                ->when($this->isEditing, function ($query) {
                    // Exclude the current section when editing
                    $query->where('id', '!=', $this->editSectionId);
                })
                ->exists();

            if ($sectionExists) {
                session()->flash('error', 'There exists a stream with that name.Please use a different unique name');
                return;
            }

            if ($this->isEditing) {
                $section = Section::findOrFail($this->editSectionId);
                $section->update([
                    'name' => $this->name,
                    'my_class_id' => $this->my_class_id,
                    'teacher_id' => $this->teacher_id,
                ]);
                session()->flash('success', 'Section updated successfully.');
            } else {
                if ($this->allTeachersAssignedMessage) {
                    session()->flash('warning', 'All teachers are already assigned.');
                    return;
                }

                // Ensure teacher assigned is unique to the class
                $existingTeacher = Section::where('my_class_id', $this->my_class_id)
                    ->where('teacher_id', $this->teacher_id)
                    ->exists();

                if ($existingTeacher) {
                    session()->flash('warning', 'This teacher is already assigned to this class.');
                    return;
                }

                Section::create([
                    'name' => $this->name,
                    'my_class_id' => $this->my_class_id,
                    'teacher_id' => $this->teacher_id,
                ]);
                session()->flash('success', 'Section created successfully.');
            }

            $this->loadSections();
            $this->resetForm();
            $this->checkAllTeachersAssigned();
        } catch (ModelNotFoundException $e) {
            session()->flash('error', 'The section could not be found.');
        } catch (\Throwable $e) {
            // Log the error for further analysis
            \Log::error('Error saving section: ' . $e->getMessage(), ['exception' => $e]);
            session()->flash('error', 'An unexpected error occurred while saving the section.');
        }
    }


    public function assignTeacherSave()
    {
        $this->validate([
            'teacher_id' => 'required|exists:users,id',
        ]);

        try {
            $section = Section::findOrFail($this->editSectionId);
            $section->teacher_id = $this->teacher_id;
            $section->save();

            $this->loadSections();
            $this->isAssigningTeacher = false;
            $this->teacher_id = null;
            $this->checkAllTeachersAssigned();

            session()->flash('success', 'Teacher assigned successfully.');
        } catch (ModelNotFoundException $e) {
            session()->flash('error', 'Section not found for assigning teacher.');
        } catch (Exception $e) {
            session()->flash('error', 'An error occurred while assigning the teacher.');
        }
    }

    public function changeClassTeacher($sectionId)
    {
        try {
            $this->editSectionId = $sectionId;
            $this->teacher_id = Section::findOrFail($sectionId)->teacher_id;
            $this->isAssigningTeacher = true;

            $section = Section::findOrFail($sectionId);
            $this->name = $section->name;
            $this->my_class_id = $section->my_class_id;

            $this->checkAllTeachersAssigned();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to load teacher change form. ' . $e->getMessage());
        }
    }

    public function assignTeacher($sectionId)
    {
        try {
            $this->editSectionId = $sectionId;
            $this->teacher_id = null;
            $this->isAssigningTeacher = true;

            $section = Section::findOrFail($sectionId);
            $this->name = $section->name;
            $this->my_class_id = $section->my_class_id;
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to load teacher assignment form. ' . $e->getMessage());
        }
    }



    public function delete($sectionId)
    {
        $this->confirmingDelete = true;
        $this->deleteSectionId = $sectionId;
    }

    public function confirmDelete()
    {
        try {
            Section::findOrFail($this->deleteSectionId)->delete();
            $this->loadSections();
            $this->confirmingDelete = false;
            $this->deleteSectionId = null;
            $this->checkAllTeachersAssigned();

            session()->flash('success', 'Section deleted successfully.');
        } catch (ModelNotFoundException $e) {
            session()->flash('error', 'Section not found for deletion.');
        } catch (Exception $e) {
            session()->flash('error', 'An error occurred while deleting the section.');
        }
    }

    public function cancelDelete()
    {
        $this->confirmingDelete = false;
        $this->deleteSectionId = null;
    }

    public function closeForm()
    {
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->name = '';
        $this->my_class_id = null;
        $this->teacher_id = null;
        $this->isCreating = false;
        $this->isEditing = false;
        $this->editSectionId = null;
        $this->isAssigningTeacher = false;
    }

    public function render()
    {
        return view('livewire.manage-sections', [
            'isTeacherDropdownDisabled' => $this->isEditing && ($this->allTeachersAssignedMessage || $this->teachers->isEmpty()),
            'students' => $this->students,
            'subjects' => $this->subjects,
            'sectionDetails' => $this->sectionDetails,
        ]);
    }
}
