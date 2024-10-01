<?php

namespace App\Http\Livewire;

use App\Models\Exam;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\Subject;
use Livewire\Component;
use App\Models\ExamMarks;
use Livewire\WithPagination;
use App\Models\StudentRecord;
use Illuminate\Support\Facades\DB;

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
    public $assignedMarks; // Holds the assigned marks for the selected exam and subject
    public $editingMarkId = null; // Tracks the currently editing mark

    // For filtering in the table view
    public $filterClass;
    public $filterExam;
    public $filterSubject;
    public $filterSection;

    public function mount()
    {
        $this->students = collect(); // Initialize as an empty Collection
        $this->assignedMarks = collect();
    }

    public function render()
    {
        $classes = MyClass::all();
        $exams = $this->selectedClass ? Exam::where('class_id', $this->selectedClass)->get() : collect();
        $subjects = Subject::all();
        $sections = $this->selectedClass ? Section::where('my_class_id', $this->selectedClass)->get() : collect();

        // Fetch students based on the selected section
        $this->students = $this->selectedSection ? StudentRecord::where('section_id', $this->selectedSection)->get() : collect();

        // Set the selected subject name based on the selected subject ID
        $this->selectedSubjectName = $this->selectedSubject ? Subject::find($this->selectedSubject)->subject_name : null;

        // Fetch assigned marks based on selected class, exam, and subject
        if ($this->selectedExam && $this->selectedSubject) {
            $this->assignedMarks = ExamMarks::where('exam_id', $this->selectedExam)
                ->where('subject_id', $this->selectedSubject)
                ->with('student')
                ->get();
        } else {
            $this->assignedMarks = collect(); // Use an empty Collection
        }

        // Filter assigned marks for the table view
        $assignedMarksForTable = $this->filterAssignedMarks(); // This will return a Collection

        return view('livewire.assign-exams-subjectwise', compact('classes', 'exams', 'subjects', 'sections', 'assignedMarksForTable'));
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

    

    public function getTotalStudentsCountProperty()
    {
        return $this->students ? $this->students->count() : 0;
    }

    public function getAssignedMarksCountProperty()
    {
        if ($this->selectedExam && $this->selectedSubject) {
            return ExamMarks::where('exam_id', $this->selectedExam)
                ->where('subject_id', $this->selectedSubject)
                ->distinct('student_id')
                ->count('student_id');
        }
        return 0; // Return 0 if no exam or subject is selected
    }

    public function getUnassignedMarksCountProperty()
    {
        return max(0, $this->totalStudentsCount - $this->assignedMarksCount);
    }

    protected function calculateAssignedMarks()
    {
        if ($this->selectedExam && $this->selectedSubject) {
            $this->assignedMarks = ExamMarks::where('exam_id', $this->selectedExam)
                ->where('subject_id', $this->selectedSubject)
                ->get();
        } else {
            $this->assignedMarks = collect(); // Reset if no exam or subject selected
        }
    }


    // Count total number of students in the selected section


    // public function render()
    // {
    //     $classes = MyClass::all();
    //     $exams = $this->selectedClass ? Exam::where('class_id', $this->selectedClass)->get() : collect();
    //     $subjects = Subject::all();
    //     $sections = $this->selectedClass ? Section::where('my_class_id', $this->selectedClass)->get() : collect();

    //     // Fetch students based on the selected section
    //     $this->students = $this->selectedSection ? StudentRecord::where('section_id', $this->selectedSection)->get() : collect();

    //     // Set the selected subject name based on the selected subject ID
    //     $this->selectedSubjectName = $this->selectedSubject ? Subject::find($this->selectedSubject)->subject_name : null;

    //     // Fetch assigned marks based on selected class, exam, and subject
    //     if ($this->selectedExam && $this->selectedSubject) {
    //         $this->assignedMarks = ExamMarks::where('exam_id', $this->selectedExam)
    //             ->where('subject_id', $this->selectedSubject)
    //             ->with('student')
    //             ->get();
    //     } else {
    //         $this->assignedMarks = collect(); // Use an empty Collection
    //     }

    //     // Filter assigned marks for the table view
    //     $assignedMarksForTable = $this->filterAssignedMarks(); // This will return a Collection

    //     return view('livewire.assign-exams-subjectwise', compact('classes', 'exams', 'subjects', 'sections', 'assignedMarksForTable'));
    // }

    public function filterAssignedMarks()
    {
        // Return the assigned marks directly as a Collection
        return $this->assignedMarks;
    }

    

    public function updatedSelectedClass($classId)
    {
        $this->reset(['selectedExam', 'selectedSubject', 'selectedSection', 'marks', 'students', 'assignedMarks']);
    }

    public function updatedSelectedExam($examId)
    {
        $this->reset(['selectedSubject', 'selectedSection', 'marks', 'students']);
        $this->selectedExamName = Exam::find($examId)->name ?? null;
        $this->calculateAssignedMarks();
    }

    public function updatedSelectedSubject($subjectId)
    {
        $this->reset(['selectedSection', 'marks', 'students', 'assignedMarks']);
        $this->calculateAssignedMarks();
    }

    // public function updatedSelectedSection($sectionId)
    // {
    //     $this->reset('marks');

    //     // Fetch all students based on the selected section
    //     $allStudents = StudentRecord::where('section_id', $this->selectedSection)->get();

    //     // Filter out students who already have marks assigned for the selected exam and subject
    //     $this->students = $allStudents->filter(function ($student) {
    //         return !ExamMarks::where('student_id', $student->id)
    //             ->where('exam_id', $this->selectedExam)
    //             ->where('subject_id', $this->selectedSubject)
    //             ->exists();
    //     });

    //     // Initialize marks for students who haven't been assigned yet
    //     foreach ($this->students as $student) {
    //         $this->marks[$student->id] = null; // No marks yet for these students
    //     }
    // }

    public function assignMarks()
    {
        try {
            // Validate the required fields
            $this->validate([
                'selectedExam' => 'required|exists:exams,id',
                'selectedSubject' => 'required|exists:subjects,id',
                'selectedSection' => 'required|exists:sections,id',
                'marks' => 'required|array',
                'marks.*' => 'nullable|numeric|min:0|max:100',
            ]);

            $updatedCount = 0;
            $insertedCount = 0;
            $skippedCount = 0;
            $errorCount = 0;

            DB::beginTransaction();

            foreach ($this->marks as $studentId => $mark) {
                if (is_null($mark)) {
                    $skippedCount++;
                    continue;
                }

                if (!is_numeric($mark) || $mark < 0 || $mark > 100) {
                    $errorCount++;
                    continue;
                }

                try {
                    $examMark = ExamMarks::updateOrCreate(
                        [
                            'student_id' => $studentId,
                            'exam_id' => $this->selectedExam,
                            'subject_id' => $this->selectedSubject,
                        ],
                        ['marks' => $mark]
                    );

                    if ($examMark->wasRecentlyCreated) {
                        $insertedCount++;
                    } else {
                        $updatedCount++;
                    }
                } catch (\Exception $e) {
                    $errorCount++;
                    \Log::error("Error assigning marks for student ID {$studentId}: " . $e->getMessage());
                }
            }

            DB::commit();

            // Set messages based on the results
            if ($insertedCount > 0 || $updatedCount > 0) {
                session()->flash('success', "Marks assigned successfully! ");
            }

            if ($skippedCount > 0) {
                session()->flash('warning', "{$skippedCount} students were skipped due to null marks.");
            }

            if ($errorCount > 0) {
                session()->flash('error', "{$errorCount} errors occurred while assigning marks. Please check the logs for details.");
            }

            if ($insertedCount == 0 && $updatedCount == 0 && $skippedCount == 0 && $errorCount == 0) {
                session()->flash('info', "No changes were made. All marks remained the same.");
            }

            // Refresh the assigned marks
            $this->refreshAssignedMarks();

            // Reset only the marks array, keeping other selections intact
            $this->marks = [];
        } catch (\Illuminate\Validation\ValidationException $e) {
            session()->flash('error', 'Validation failed: ' . implode(', ', $e->errors()));
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'An unexpected error occurred: ' . $e->getMessage());
            \Log::error("Error in assignMarks: " . $e->getMessage());
        }
    }

    public function refreshAssignedMarks()
    {
        if ($this->selectedExam && $this->selectedSubject) {
            $this->assignedMarks = ExamMarks::where('exam_id', $this->selectedExam)
                ->where('subject_id', $this->selectedSubject)
                ->with('student')
                ->get();
        } else {
            $this->assignedMarks = collect(); // Reset to empty collection if no exam or subject is selected
        }
    }



    public function editMark($studentId)
    {
        $this->editingMarkId = $studentId;

        $examMark = ExamMarks::where('student_id', $studentId)
            ->where('exam_id', $this->selectedExam)
            ->where('subject_id', $this->selectedSubject)
            ->first();

        if ($examMark) {
            $this->marks[$studentId] = $examMark->marks; // Pre-fill mark for editing
        } else {
            $this->marks[$studentId] = null; // Set to null if no marks exist
        }
    }

    public function updateMark($studentId)
    {
        if ($this->editingMarkId) {
            $examMark = ExamMarks::where('student_id', $studentId)
                ->where('exam_id', $this->selectedExam)
                ->where('subject_id', $this->selectedSubject)
                ->first();

            if ($examMark) {
                $examMark->marks = $this->marks[$studentId];
                $examMark->save();

                session()->flash('message', 'Marks updated successfully!');
            }

            $this->editingMarkId = null; // Reset editing state
            $this->refreshAssignedMarks(); // Refresh marks after update
        }
    }
}
