<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ParentDetail;
use App\Models\StudentRecord;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\User;
use App\Models\Subject;

class ParentChildClass extends Component
{
    use WithPagination;

    public $parent;
    public $students;
    public $selectedStudent;
    public $viewMode = 'class'; // Default view mode
    public $perPage = 20; // Number of items per page for pagination
    public $showChat = false; // To toggle chat interface

    public function mount()
    {
        // Fetch the logged-in parent's details
        $this->parent = ParentDetail::where('user_id', auth()->id())->first();

        if ($this->parent) {
            // Fetch all students associated with this parent
            $this->students = $this->parent->student_records;

            // Select the first student by default
            $this->selectedStudent = $this->students->first();
        }
    }

    public function selectStudent($studentId)
    {
        // Set the selected student
        $this->selectedStudent = StudentRecord::find($studentId);
        $this->resetPage(); // Reset pagination when a new student is selected
    }

    public function setViewMode($mode)
    {
        $this->viewMode = $mode;
        $this->resetPage(); // Reset pagination when view mode changes
    }

    public function toggleChat()
    {
        $this->showChat = !$this->showChat;
    }

    public function render()
    {
        // Fetch additional details for the selected student
        $studentDetails = null;
        $classDetails = null;
        $sectionDetails = null;
        $classTeacher = null;
        $subjects = null;
        $classmates = null;
        $streamMates = null;

        if ($this->selectedStudent) {
            // Fetch class and section details
            $classDetails = $this->selectedStudent->my_class;
            $sectionDetails = $this->selectedStudent->section;

            // Fetch class teacher for the current year/session
            if ($classDetails) {
                $currentYear = date('Y');
                $classTeacher = $classDetails->teachers()
                    ->wherePivot('session', $currentYear)
                    ->first();
            }

            // Fetch subjects
            $subjects = $this->selectedStudent->subjects;

            // Fetch classmates with pagination
            if ($classDetails) {
                $classmates = StudentRecord::where('my_class_id', $classDetails->id)
                    ->where('id', '!=', $this->selectedStudent->id) // Exclude the selected student
                    ->paginate($this->perPage);
            }

            // Fetch stream mates with pagination
            if ($sectionDetails) {
                $streamMates = StudentRecord::where('section_id', $sectionDetails->id)
                    ->where('id', '!=', $this->selectedStudent->id) // Exclude the selected student
                    ->paginate($this->perPage);
            }
        }

        return view('livewire.parent-child-class', [
            'parent' => $this->parent,
            'students' => $this->students,
            'selectedStudent' => $this->selectedStudent,
            'classDetails' => $classDetails,
            'sectionDetails' => $sectionDetails,
            'classTeacher' => $classTeacher,
            'subjects' => $subjects,
            'classmates' => $classmates,
            'streamMates' => $streamMates,
            'viewMode' => $this->viewMode,
            'showChat' => $this->showChat,
        ]);
    }
}
