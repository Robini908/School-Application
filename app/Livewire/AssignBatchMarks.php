<?php

namespace App\Livewire;

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
    public $classes;
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
                $examMark = ExamMarks::where([
                    'student_id' => $student->id,
                    'exam_id' => $this->selectedExam,
                    'subject_id' => $subject->id,
                ])->first();

                if ($examMark) {
                    $this->marks[$student->id][$subject->id] = $examMark->marks;
                } else {
                    $this->marks[$student->id][$subject->id] = null; // Initialize as null if no marks exist
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
                foreach ($this->subjects as $subject) {
                    // Only process the subjects that the student is enrolled in
                    if ($this->isStudentEnrolledInSubject($student->id, $subject->id)) {
                        $marks = $this->marks[$student->id][$subject->id] ?? null;

                        // Update or create the exam mark record for the student and subject
                        ExamMarks::updateOrCreate(
                            [
                                'student_id' => $student->id,
                                'exam_id' => $this->selectedExam,
                                'subject_id' => $subject->id,
                            ],
                            [
                                'grading_range_id' => null,
                                'marks' => $marks,
                            ]
                        );
                    }
                }
            }

            session()->flash('message', 'Marks successfully assigned!');
            $this->resetForm();
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while assigning marks: ' . $e->getMessage());
        } finally {
            $this->buttonText = 'Submit Marks';
        }
    }

    private function validateMarks()
    {
        $rules = [];
        foreach ($this->students as $student) {
            foreach ($this->subjects as $subject) {
                // Only validate marks for enrolled subjects
                if ($this->isStudentEnrolledInSubject($student->id, $subject->id)) {
                    $rules["marks.{$student->id}.{$subject->id}"] = [
                        'required',
                        'integer',
                        'min:0',
                        'max:100',
                    ];
                }
            }
        }
        $this->validate($rules);
    }

    private function isStudentEnrolledInSubject($studentId, $subjectId)
    {
        return StudentRecord::find($studentId)
            ? StudentRecord::find($studentId)->subjects->contains('id', $subjectId)
            : false;
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
        session()->flash('message', 'Mark deleted successfully!');
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
                        'grading_range_id' => null,
                        'marks' => $this->marks[$studentId][$subject->id] ?? null,
                    ]
                );
            }
        }

        // Remove the editable state after updating
        unset($this->editable[$studentId]);

        session()->flash('message', 'Marks successfully updated for student ' . $studentId . '!');
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
