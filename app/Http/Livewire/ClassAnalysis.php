<?php

namespace App\Http\Livewire;

use App\Models\Exam;
use App\Models\MyClass;
use App\Models\ExamMarks;
use Livewire\Component;

class ClassAnalysis extends Component
{
    public $examId;   // Selected exam ID
    public $gradesCount = []; // Array to hold grades count for each class
    public $exams = []; // List of exams
    public $errorMessage;

    public function mount()
    {
        // Load all exams
        $this->exams = Exam::all();
        $this->gradesCount = [];
    }

    public function updatedExamId()
    {
        $this->gradesCount = []; // Reset grades count
        $this->errorMessage = null; // Clear error message

        if ($this->examId) {
            $exam = Exam::with('gradingSystem')->find($this->examId);

            if ($exam) {
                $this->getGradesCount(); // Fetch grades count after selecting the exam
            } else {
                $this->errorMessage = 'Exam not found.';
            }
        }
    }

    public function getGradesCount()
    {
        if (!$this->examId) {
            $this->errorMessage = 'Please select an exam to view class analysis.';
            return;
        }

        // Get the exam and associated grading system
        $exam = Exam::with('gradingSystem.gradingRanges')->find($this->examId);

        if (!$exam) {
            $this->errorMessage = 'Exam not found.';
            return;
        }

        // Initialize the grades count for each class
        $classes = MyClass::all();
        foreach ($classes as $class) {
            $this->gradesCount[$class->id] = [
                'class_name' => $class->name,
                'grades' => [
                    'A' => 0,
                    'A-' => 0,
                    'B+' => 0,
                    'B' => 0,
                    'B-' => 0,
                    'C+' => 0,
                    'C' => 0,
                    'C-' => 0,
                    'D+' => 0,
                    'D' => 0,
                    'D-' => 0,
                    'E' => 0,
                    'F' => 0,
                ],
            ];
        }

        // Get exam marks, preload student and class relations
        $examMarks = ExamMarks::where('exam_id', $this->examId)
            ->with(['student.my_class', 'subject']) // Ensure 'my_class' is used here
            ->get();

        // Process the marks and count grades for each class
        foreach ($examMarks as $mark) {
            $student = $mark->student;
            $classId = $student->my_class_id; // Get the class ID of the student
            $subjectId = $mark->subject_id;   // Get the subject ID

            // Get the appropriate grading ranges for this subject or general ones
            $gradingRanges = $exam->gradingSystem->gradingRanges
                ->where('subject_id', $subjectId)
                ->all();

            if (empty($gradingRanges)) {
                $gradingRanges = $exam->gradingSystem->gradingRanges; // Use default ranges if none found for subject
            }

            // Get the grade for the current mark
            $grade = $this->getGrade($mark->marks, $gradingRanges);

            // Increment the count of the grade for the class
            if (isset($this->gradesCount[$classId]['grades'][$grade])) {
                $this->gradesCount[$classId]['grades'][$grade]++;
            }
        }

        if (empty($this->gradesCount)) {
            $this->errorMessage = 'No grades found for the selected exam.';
        }
    }

    /**
     * Determine the grade for the given marks based on the grading ranges.
     */
    public function getGrade($marks, $gradingRanges)
    {
        if ($marks === 'N/A' || $marks === null) {
            return 'N/A'; // Handle undefined marks
        }

        // Loop through the grading ranges and find the grade
        foreach ($gradingRanges as $range) {
            if ($marks >= $range->range_from && $marks <= $range->range_to) {
                return $range->grade;
            }
        }

        return 'F'; // Default grade if no match is found
    }

    public function render()
    {
        return view('livewire.class-analysis', [
            'gradesCount' => $this->gradesCount,
            'errorMessage' => $this->errorMessage,
            'exams' => $this->exams,
        ]);
    }
}
