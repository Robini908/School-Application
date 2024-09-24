<?php

namespace App\Http\Livewire;

use App\Models\MyClass;
use App\Models\Exam;
use App\Models\Subject;
use App\Models\Section;
use App\Models\StudentRecord;
use App\Models\ExamMarks;
use Livewire\Component;
use Livewire\WithPagination;

class AssignExamsSubjectwise extends Component
{
    use WithPagination;

    public $selectedClass;
    public $selectedExamName;
    public $marksMessage = null; // Add a public property to store the message
    public $selectedExam;
    public $selectedSubject;
    public $selectedSection;
    public $students = [];
    public $marks = []; // Holds the marks assigned to each student
    public $selectedSubjectName; // Holds the selected subject name
    public $assignedMarks = []; // Holds the assigned marks for the selected exam and subject
    public $editingMarkId = null; // Tracks the currently editing mark

    // For filtering in the table view
    public $filterClass;
    public $filterExam;
    public $filterSubject;
    public $filterSection;

    public function render()
    {
        $classes = MyClass::all();
        $exams = $this->selectedClass ? Exam::where('class_id', $this->selectedClass)->get() : [];
        $subjects = Subject::all();
        $sections = $this->selectedClass ? Section::where('my_class_id', $this->selectedClass)->get() : [];

        // Fetch students based on the selected section
        $this->students = $this->selectedSection ? StudentRecord::where('section_id', $this->selectedSection)->get() : [];

        // Set the selected subject name based on the selected subject ID
        $this->selectedSubjectName = $this->selectedSubject ? Subject::find($this->selectedSubject)->subject_name : null;

        // Fetch assigned marks based on selected class, exam, and subject
        if ($this->selectedExam && $this->selectedSubject) {
            $this->assignedMarks = ExamMarks::where('exam_id', $this->selectedExam)
                ->where('subject_id', $this->selectedSubject)
                ->with('student')
                ->get();
        } else {
            $this->assignedMarks = []; // Reset if not filtering
        }

        // Filter assigned marks for the table view
        $assignedMarksForTable = $this->filterAssignedMarks();

        return view('livewire.assign-exams-subjectwise', compact('classes', 'exams', 'subjects', 'sections', 'assignedMarksForTable'));
    }

    public function updatedSelectedClass($classId)
    {
        $this->reset(['selectedExam', 'selectedSubject', 'selectedSection', 'marks', 'students', 'assignedMarks']);
    }

    public function updatedSelectedExam($examId)
    {
        $this->reset(['selectedSubject', 'selectedSection', 'marks', 'students']);
        $this->selectedExamName = Exam::find($examId)->name ?? null;
    }
  
    public function updatedSelectedSubject($subjectId)
    {
        $this->reset(['selectedSection', 'marks', 'students', 'assignedMarks']);
    }

    public function updatedSelectedSection($sectionId)
    {
        $this->reset('marks');

        // Fetch all students based on the selected section
        $allStudents = StudentRecord::where('section_id', $this->selectedSection)->get();

        // Filter out students who already have marks assigned for the selected exam and subject
        $this->students = $allStudents->filter(function ($student) {
            return !ExamMarks::where('student_id', $student->id)
                ->where('exam_id', $this->selectedExam)
                ->where('subject_id', $this->selectedSubject)
                ->exists();
        });

        // Initialize marks for students who haven't been assigned yet
        foreach ($this->students as $student) {
            $this->marks[$student->id] = null; // No marks yet for these students
        }
    }




    public function assignMarks()
    {
        // Ensure students is a collection
        $this->students = collect($this->students); // Convert to collection if it's not already

        // Assign marks to each student in the selected section
        foreach ($this->students as $student) {
            ExamMarks::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'exam_id' => $this->selectedExam,
                    'subject_id' => $this->selectedSubject,
                ],
                ['marks' => $this->marks[$student->id] ?? 0] // Default to 0 if no marks are provided
            );
        }

        // Check if all students in the selected section have been assigned marks
        $studentsWithoutMarks = $this->students->filter(function ($student) {
            return !ExamMarks::where([
                'student_id' => $student->id,
                'exam_id' => $this->selectedExam,
                'subject_id' => $this->selectedSubject,
            ])->exists();
        });

        if ($studentsWithoutMarks->isEmpty()) {
            // If all students have been assigned marks, set a success message
            $this->marksMessage = 'All students in this section have been assigned marks for ' . $this->selectedSubjectName . '!';
        } else {
            // If some students still need marks, show a general success message
            $this->marksMessage = 'Marks assigned successfully!';
        }

        // Reset fields after the assignment
        $this->reset(['selectedClass', 'selectedExam', 'selectedSubject', 'selectedSection', 'marks', 'students']);
        $this->refreshAssignedMarks(); // Refresh assigned marks to reflect updates
    }



    public function refreshAssignedMarks()
    {
        if ($this->selectedExam && $this->selectedSubject) {
            $this->assignedMarks = ExamMarks::where('exam_id', $this->selectedExam)
                ->where('subject_id', $this->selectedSubject)
                ->with('student')
                ->get();
        }
    }

    public function editMark($markId)
    {
        $this->editingMarkId = $markId;

        $examMark = ExamMarks::find($markId);
        if ($examMark) {
            $this->marks[$examMark->student_id] = $examMark->marks; // Pre-fill mark for editing
        }
    }

    public function updateMark()
    {
        if ($this->editingMarkId) {
            $examMark = ExamMarks::find($this->editingMarkId);
            if ($examMark) {
                $examMark->marks = $this->marks[$examMark->student_id];
                $examMark->save();

                session()->flash('message', 'Marks updated successfully!');
            }

            $this->editingMarkId = null; // Reset editing state
            $this->refreshAssignedMarks(); // Refresh marks after update
        }
    }

    private function filterAssignedMarks()
    {
        // Logic for filtering assigned marks if needed
        return $this->assignedMarks;
    }
}
