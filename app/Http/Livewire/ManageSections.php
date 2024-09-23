<?php

namespace App\Http\Livewire;

use App\User;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\Subject;
use Livewire\Component;
use App\Models\StudentRecord;

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
        // Fetch the section details with related class and teacher
        $this->sectionDetails = Section::with(['my_class', 'teacher'])->findOrFail($sectionId);
        $this->students = StudentRecord::where('section_id', $sectionId)->get();
        $this->subjects = Subject::all();
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
        $this->isEditing = true;
        $this->editSectionId = $sectionId;

        $section = Section::findOrFail($sectionId);
        $this->name = $section->name;
        $this->my_class_id = $section->my_class_id;
        $this->teacher_id = $section->teacher_id;

        // Check if there are available teachers for this section
        $this->checkAllTeachersAssigned();
    }

    public function assignTeacher($sectionId)
    {
        $this->editSectionId = $sectionId;
        $this->teacher_id = null;
        $this->isAssigningTeacher = true;

        $section = Section::findOrFail($sectionId);
        $this->name = $section->name;
        $this->my_class_id = $section->my_class_id;
    }

    public function save()
    {
        $this->validate();

        if ($this->isEditing) {
            $section = Section::findOrFail($this->editSectionId);
            $section->update([
                'name' => $this->name,
                'my_class_id' => $this->my_class_id,
                'teacher_id' => $this->teacher_id,
            ]);
        } else {
            if ($this->allTeachersAssignedMessage) {
                return; // Prevent creation if all teachers are assigned
            }

            // Ensure teacher assigned is unique to the class
            $existingTeacher = Section::where('my_class_id', $this->my_class_id)
                ->where('teacher_id', $this->teacher_id)
                ->exists();

            if ($existingTeacher) {
                return; // Handle duplicate assignment if needed
            }

            Section::create([
                'name' => $this->name,
                'my_class_id' => $this->my_class_id,
                'teacher_id' => $this->teacher_id,
            ]);
        }

        $this->loadSections();
        $this->resetForm();
        $this->checkAllTeachersAssigned(); // Check again after save
    }

    public function assignTeacherSave()
    {
        $this->validate([
            'teacher_id' => 'required|exists:users,id',
        ]);

        $section = Section::findOrFail($this->editSectionId);
        $section->teacher_id = $this->teacher_id;
        $section->save();

        $this->loadSections();
        $this->isAssigningTeacher = false;
        $this->teacher_id = null;
        $this->checkAllTeachersAssigned(); // Check again after assigning teacher
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

    public function delete($sectionId)
    {
        $this->confirmingDelete = true;
        $this->deleteSectionId = $sectionId;
    }

    public function confirmDelete()
    {
        Section::findOrFail($this->deleteSectionId)->delete();
        $this->loadSections();
        $this->confirmingDelete = false;
        $this->deleteSectionId = null;
        $this->checkAllTeachersAssigned(); // Check again after deletion
    }

    public function cancelDelete()
    {
        $this->confirmingDelete = false;
        $this->deleteSectionId = null;
    }

    public function changeClassTeacher($sectionId)
    {
        $this->editSectionId = $sectionId;
        $this->teacher_id = Section::findOrFail($sectionId)->teacher_id;
        $this->isAssigningTeacher = true;

        $section = Section::findOrFail($sectionId);
        $this->name = $section->name;
        $this->my_class_id = $section->my_class_id;

        $this->checkAllTeachersAssigned();
    }

    public function closeForm()
    {
        $this->resetForm();
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
