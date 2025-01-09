<?php

namespace App\Livewire;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use App\Models\MyClass;
use App\Models\StudentRecord;
use App\Models\Exam;
use App\Models\Section;
use App\Models\Subject;
use App\Models\GradingRange;
use App\Models\ExamMarks;

class AssignBatchMarks extends Component
{
    use LivewireAlert;
    public $classes;

    public $specialGrades = [];
    public $selectedClass = null;
    public $selectedClassName = '';
    public $buttonText = 'Submit Marks';
    public $exams = [];
    public $selectedExam = null;
    public $examDetails = null;
    public $sections = [];

    public $streamName;
    public $selectedSection = null;
    public $students = [];
    public $subjects = [];
    public $marks = [];
    public $gradingRanges = [];
    public $editingMarks = []; // Track editing state
    protected $listeners = ['saveMarks'];

    public function mount()
    {
        $this->classes = MyClass::all();
        $this->subjects = Subject::all();
        // Load students and subjects (you can customize this based on your logic)
        $this->students = StudentRecord::all();

        // Initialize marks and specialGrades arrays
        foreach ($this->students as $student) {
            foreach ($this->subjects as $subject) {
                $this->marks[$student->id][$subject->id] = null;
                $this->specialGrades[$student->id][$subject->id] = null;
            }
        }
    }

    public function updatedSelectedClass($classId)
    {
        $class = MyClass::find($classId);
        $this->selectedClassName = $class ? $class->name : '';

        $this->exams = Exam::whereHas('classes', function ($query) use ($classId) {
            $query->where('class_id', $classId);
        })->get();

        $this->selectedExam = null;
        $this->selectedSection = null;
        $this->marks = [];
        $this->students = [];
    }

    public function updatedSelectedExam($examId)
    {
        $this->examDetails = Exam::find($examId);

        if ($this->examDetails && $this->examDetails->grading_system_id) {
            $this->gradingRanges = GradingRange::where('grading_system_id', $this->examDetails->grading_system_id)->get();
        } else {
            $this->gradingRanges = [];
        }

        if ($this->selectedClass) {
            $this->sections = Section::where('my_class_id', $this->selectedClass)->get();
            $this->selectedSection = null;
            $this->students = [];
        }
    }

    public function updatedSelectedSection($sectionId)
    {
        $this->students = StudentRecord::with(['section', 'subjects'])
            ->where('my_class_id', $this->selectedClass)
            ->where('section_id', $sectionId)
            ->get();

        // Load existing marks and identify students not enrolled in each subject
        $this->loadExistingMarks();
    }

    private function loadExistingMarks()
    {
        foreach ($this->students as $student) {
            foreach ($this->subjects as $subject) {
                // Fetch the exam mark for the student and subject
                $examMark = ExamMarks::where([
                    'student_id' => $student->id,
                    'exam_id' => $this->selectedExam,
                    'subject_id' => $subject->id,
                ])->first();

                if ($examMark) {
                    // Load marks if they exist
                    $this->marks[$student->id][$subject->id] = $examMark->marks;

                    // Load special grade if it exists
                    if ($examMark->special_grade) {
                        $this->specialGrades[$student->id][$subject->id] = $examMark->special_grade;
                    } else {
                        $this->specialGrades[$student->id][$subject->id] = null; // Initialize as null if no special grade exists
                    }
                } else {
                    // Initialize marks and special grades as null if no record exists
                    $this->marks[$student->id][$subject->id] = null;
                    $this->specialGrades[$student->id][$subject->id] = null;
                }
            }
        }
    }



    public function saveMarks($marks)
    {
        $this->marks = $marks;
        $this->assignMarks();
    }

    public function assignMarks()
    {
        $this->validateMarks();

        try {
            foreach ($this->students as $student) {
                $isSubjectSelectionEnabled = $this->isSubjectSelectionEnabled($student->my_class_id);

                foreach ($this->subjects as $subject) {
                    if ($isSubjectSelectionEnabled && !$this->isStudentEnrolledInSubject($student->id, $subject->id)) {
                        continue; // Skip if not enrolled
                    }

                    $marks = $this->marks[$student->id][$subject->id] ?? null;
                    $specialGrade = $this->specialGrades[$student->id][$subject->id] ?? null;

                    // Ensure either marks or special grade is provided, but not both
                    if (!empty($marks) && !empty($specialGrade)) {
                        $this->addError("marks.{$student->id}.{$subject->id}", 'Cannot provide both marks and a special grade.');
                        continue;
                    }

                    // Update or create the exam mark record for the student and subject
                    ExamMarks::updateOrCreate(
                        [
                            'student_id' => $student->id,
                            'exam_id' => $this->selectedExam,
                            'subject_id' => $subject->id,
                        ],
                        [
                            'marks' => $marks,
                            'special_grade' => $specialGrade,
                        ]
                    );
                }
            }

            // Refresh the data to reflect the changes
            $this->students = StudentRecord::whereHas('examMarks', function ($query) {
                $query->where('exam_id', $this->selectedExam);
            })->get();

            $this->alert('success', 'Marks/Grades successfully assigned!');
        } catch (\Exception $e) {
            $this->alert('error', 'An error occurred while assigning marks/grades: ' . $e->getMessage());
        } finally {
            $this->buttonText = 'Submit Marks/Grades';
        }
        $this->resetErrorBag();
    }
    private function validateMarks()
    {
        $rules = [];
        foreach ($this->students as $student) {
            $isSubjectSelectionEnabled = $this->isSubjectSelectionEnabled($student->my_class_id);

            foreach ($this->subjects as $subject) {
                if (!$isSubjectSelectionEnabled || $this->isStudentEnrolledInSubject($student->id, $subject->id)) {
                    $rules["marks.{$student->id}.{$subject->id}"] = [
                        'nullable',
                        'integer',
                        'min:0',
                        'max:100',
                    ];
                    $rules["specialGrades.{$student->id}.{$subject->id}"] = [
                        'nullable',
                        'in:X,Y,Z',
                    ];
                }
            }
        }

        $this->validate($rules);
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



    private function resetForm()
    {
        $this->marks = [];
        $this->selectedSection = null;
        $this->selectedExam = null;
        $this->selectedClass = null;
        $this->editingMarks = []; // Reset editing state
    }

    public function deleteMark($studentId, $subjectId)
    {
        ExamMarks::where([
            'student_id' => $studentId,
            'exam_id' => $this->selectedExam,
            'subject_id' => $subjectId,
        ])->delete();

        unset($this->marks[$studentId][$subjectId]);
        $this->alert('success', 'Mark deleted successfully!');
    }

    public function toggleEdit($studentId, $subjectId)
    {
        $this->editingMarks["{$studentId}.{$subjectId}"] = !($this->editingMarks["{$studentId}.{$subjectId}"] ?? false);
    }

    public $editable = []; // To track which students are editable



    public function editMarks($studentId)
    {
        // Toggle the editable state for the specific student
        if (isset($this->editable[$studentId])) {
            unset($this->editable[$studentId]);
        } else {
            $this->editable[$studentId] = true;
        }
    }

    public function updateMarks($studentId)
    {
        $this->validateMarksForStudent($studentId); // Validate for the specific student

        foreach ($this->subjects as $subject) {
            // Only update marks if the student is enrolled in the subject
            if ($this->isStudentEnrolledInSubject($studentId, $subject->id)) {
                ExamMarks::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'exam_id' => $this->selectedExam,
                        'subject_id' => $subject->id,
                    ],
                    [
                        'marks' => $this->marks[$studentId][$subject->id] ?? null,
                        'special_grade' => $this->specialGrades[$studentId][$subject->id] ?? null,
                    ]
                );
            }
        }

        // Remove the editable state after updating
        unset($this->editable[$studentId]);

        $this->alert('success', 'Marks/Grades successfully updated for student ' . $studentId . '!');
    }


    private function validateMarksForStudent($studentId)
    {
        $rules = [];
        foreach ($this->subjects as $subject) {
            $rules["marks.{$studentId}.{$subject->id}"] = [
                'required',
                'integer',
                'min:0',
                'max:100',
            ];
        }
        $this->validate($rules);
    }


    public function render()
    {
        return view('livewire.assign-batch-marks');
    }
}
