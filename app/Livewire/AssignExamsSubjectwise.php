<?php

namespace App\Livewire;

use App\Models\Exam;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\Subject;
use Livewire\Component;
use App\Models\ExamMarks;
use Livewire\WithPagination;
use App\Models\StudentRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class AssignExamsSubjectwise extends Component
{
    use WithPagination;
    use LivewireAlert;

    public $selectedClass;
    public $specialGrades = [];
    public $selectedExamName;
    public $marksMessage = null; // Add a public property to store the success
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
    public $selectedClassName; // Holds the selected class name

    public $filterSection;

    public function mount()
    {
        // Initialize collections and variables
        $this->students = collect();
        $this->assignedMarks = collect();
        $this->marks = [];
        $this->editingMarkId = null;
        $this->selectedSubjectName = null;
        $this->selectedClass = null;
        $this->selectedSection = null;
        $this->selectedExam = null;
        $this->selectedSubject = null;
    }



    // Populate marks array for rendering in the view
    public function render()
    {
        $classes = MyClass::all();

        // Fetch exams for the selected class
        $exams = $this->selectedClass
            ? Exam::where('class_id', $this->selectedClass)->get()
            : collect();

        // Fetch subjects via the grading system associated with the selected exam
        $subjects = $this->selectedExam
            ? Subject::whereHas('gradingSystems', function ($query) {
                $query->where('grading_systems.id', Exam::find($this->selectedExam)->grading_system_id);
            })->get()
            : collect();

        $sections = $this->selectedClass
            ? Section::where('my_class_id', $this->selectedClass)->get()
            : collect();



        $this->students = StudentRecord::with(['section', 'subjects'])
            ->where('my_class_id', $this->selectedClass)
            ->where('section_id', $this->selectedSection)
            ->get();






        // Set selected names for class, exam, and subject
        $this->selectedClassName = $this->selectedClass
            ? MyClass::find($this->selectedClass)->class_name
            : null;
        $this->selectedExamName = $this->selectedExam
            ? Exam::find($this->selectedExam)->name
            : null;



        // Fetch marks assigned to students for the selected exam and subject
        $this->assignedMarks = ($this->selectedExam && $this->selectedSubject)
            ? ExamMarks::where('exam_id', $this->selectedExam)
            ->where('subject_id', $this->selectedSubject)
            ->with('student')
            ->get()
            : collect();

        // Populate marks for rendering
        $this->populateMarksArray();

        return view('livewire.assign-exams-subjectwise', [
            'classes' => $classes,
            'exams' => $exams,
            'subjects' => $subjects,
            'sections' => $sections,
            'assignedMarksForTable' => $this->assignedMarks,
            'selectedExamName' => $this->selectedExamName,
            'selectedSubjectName' => $this->selectedSubjectName,
        ]);
    }
    /**
     * Check if subject selection is enabled for a specific class.
     *
     * @param int $classId
     * @return bool
     */
    protected function isSubjectSelectionEnabled($classId)
    {
        // Check the MyClass model via the subjectSelectionSetting relationship
        $class = MyClass::find($classId);
        return $class?->subjectSelectionSetting?->is_subject_selection_enabled ?? false;
    }

    private function isStudentEnrolledInSubject($studentId, $subjectId)
    {
        $student = StudentRecord::find($studentId);

        if (!$student) {
            return false;
        }

        // Check if subject selection is enabled for the class
        $isSelectionEnabled = MyClass::find($student->my_class_id)
            ->subjectSelectionSetting
            ->is_subject_selection_enabled ?? false;

        // If subject selection is disabled, treat all students as enrolled
        if (!$isSelectionEnabled) {
            return true;
        }

        // Otherwise, check if the student is explicitly enrolled in the subject
        return $student->subjects->contains('id', $subjectId);
    }





    public function populateMarksArray()
    {
        foreach ($this->students as $student) {
            $assignedMark = $this->assignedMarks->firstWhere('student_id', $student->id);
            $this->marks[$student->id] = $assignedMark ? $assignedMark->marks : null;
        }
    }






    public function updatedSelectedClass($classId)
    {
        $this->reset(['selectedExam', 'selectedSubject', 'selectedSection', 'students', 'assignedMarks']);
    }


    public function updatedSelectedExam($examId)
    {
        $this->reset(['selectedSubject', 'selectedSection', 'students']);
    }


    public function updatedSelectedSection($sectionId)
    {
        $this->reset(['students', 'marks']); // Reset students and marks on section change.

        if ($sectionId && $this->selectedSubject) {
            // Filter by section and subject
            $this->students = StudentRecord::where('section_id', $sectionId)
                ->whereHas('subjects', function ($query) {
                    $query->where('subjects.id', $this->selectedSubject); // Ensure no ambiguity
                })->get();
        } elseif ($sectionId) {
            // Filter by section only
            $this->students = StudentRecord::where('section_id', $sectionId)->get();
        } else {
            $this->students = collect(); // Reset if no section is selected
        }

        $this->populateMarksArray(); // Update marks array for UI
    }



    public function updatedSelectedSubject($subjectId)
    {
        $this->reset(['students', 'marks', 'selectedSubjectName']); // Reset students, marks, and subject name on change.

        if ($subjectId) {
            $this->selectedSubjectName = Subject::find($subjectId)?->subject_name ?? 'Unknown Subject'; // Fetch subject name or fallback
        }

        if ($subjectId && $this->selectedSection) {
            $this->students = StudentRecord::where('section_id', $this->selectedSection)
                ->whereHas('subjects', function ($query) use ($subjectId) {
                    $query->where('subjects.id', $subjectId);
                })->get();
        } elseif ($subjectId) {
            $this->students = StudentRecord::whereHas('subjects', function ($query) use ($subjectId) {
                $query->where('subjects.id', $subjectId);
            })->get();
        } else {
            $this->students = collect(); // Reset if no subject is selected
        }

        $this->populateMarksArray(); // Update marks array for UI
    }




    public function assignMarks()
    {
        try {
            $this->validate([
                'selectedExam' => 'required|exists:exams,id',
                'selectedSubject' => 'required|exists:subjects,id',
                'selectedSection' => 'required|exists:sections,id',
                'marks' => 'nullable|array',
                'marks.*' => 'nullable|numeric|min:0|max:100',
                'specialGrades' => 'nullable|array',
                'specialGrades.*' => 'nullable|in:X,Y,Z',
            ]);

            DB::beginTransaction();

            $updatedCount = 0;
            $insertedCount = 0;
            $skippedCount = 0;
            $errorCount = 0;

            foreach ($this->students as $student) {
                $studentId = $student->id;
                $mark = $this->marks[$studentId] ?? null;
                $specialGrade = $this->specialGrades[$studentId] ?? null;

                // Skip if neither marks nor special grade is provided
                if (is_null($mark) && is_null($specialGrade)) {
                    $skippedCount++;
                    continue;
                }

                // Skip if the student is not enrolled in the subject
                if (!$this->isStudentEnrolledInSubject($studentId, $this->selectedSubject)) {
                    $skippedCount++;
                    continue;
                }

                try {
                    $examMark = ExamMarks::updateOrCreate(
                        [
                            'student_id' => $studentId,
                            'exam_id' => $this->selectedExam,
                            'subject_id' => $this->selectedSubject,
                        ],
                        [
                            'marks' => $mark,
                            'special_grade' => $specialGrade,
                        ]
                    );

                    if ($examMark->wasRecentlyCreated) {
                        $insertedCount++;
                    } else {
                        $updatedCount++;
                    }
                } catch (\Exception $e) {
                    $errorCount++;
                    Log::error("Error processing marks for student ID {$studentId}: " . $e->getMessage());
                }
            }

            DB::commit();

            if ($insertedCount > 0 || $updatedCount > 0) {
                $this->alert('success', "Marks/Grades assigned successfully! Inserted: {$insertedCount}, Updated: {$updatedCount}");
            }

            if ($skippedCount > 0) {
                $this->alert('warning', "{$skippedCount} students were skipped (unenrolled or no data provided).");
            }

            if ($errorCount > 0) {
                $this->alert('error', "{$errorCount} errors occurred while processing marks/grades. Check logs for details.");
            }

            $this->refreshAssignedMarks();
            $this->marks = [];
            $this->specialGrades = [];
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->alert('error', 'Validation error: ' . implode(', ', $e->errors()));
        } catch (\Exception $e) {
            DB::rollBack();
            $this->alert('error', 'An unexpected error occurred: ' . $e->getMessage());
            Log::error("Error in assignMarks: " . $e->getMessage());
        }
    }

    public function updatedMarks($value, $studentId)
    {
        // If marks are entered, clear the special grade for this student
        if (!empty($value)) {
            $this->specialGrades[$studentId] = null; // Clear special grade
        }
    }
    
    // When special grades are updated
    public function updatedSpecialGrades($value, $studentId)
    {
        // If a special grade is selected, clear the marks for this student
        if (!empty($value)) {
            $this->marks[$studentId] = null; // Clear marks
        }
    }
    
 

// Custom method to handle marks input
public function handleMarksInput($value, $studentId)
{
    // If marks are entered, clear and disable the special grade field for this student
    if (!empty($value)) {
        $this->specialGrades[$studentId] = null; // Clear special grade
    }
}

// Custom method to handle special grade selection
public function handleSpecialGradeInput($value, $studentId)
{
    // If a special grade is selected, clear the marks field for this student
    if (!empty($value)) {
        $this->marks[$studentId] = null; // Clear marks
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

        // Pre-fill the form with existing marks or special grade
        $assignedMark = ExamMarks::where('student_id', $studentId)
            ->where('exam_id', $this->selectedExam)
            ->where('subject_id', $this->selectedSubject)
            ->first();

        if ($assignedMark) {
            $this->marks[$studentId] = $assignedMark->marks;
            $this->specialGrades[$studentId] = $assignedMark->special_grade;
        }
    }

    public function updateMark($studentId)
    {
        $this->validate([
            "marks.$studentId" => 'nullable|numeric|min:0|max:100',
            "specialGrades.$studentId" => 'nullable|in:X,Y,Z',
        ]);

        // Ensure either marks or special grade is provided, but not both
        if (!empty($this->marks[$studentId]) && !empty($this->specialGrades[$studentId])) {
            $this->addError("marks.$studentId", 'Cannot provide both marks and a special grade.');
            return;
        }

        ExamMarks::updateOrCreate(
            [
                'student_id' => $studentId,
                'exam_id' => $this->selectedExam,
                'subject_id' => $this->selectedSubject,
            ],
            [
                'marks' => $this->marks[$studentId],
                'special_grade' => $this->specialGrades[$studentId],
            ]
        );

        $this->editingMarkId = null;
        $this->refreshAssignedMarks();
        $this->alert('success', 'Marks/Grade updated successfully.');
    }
}
