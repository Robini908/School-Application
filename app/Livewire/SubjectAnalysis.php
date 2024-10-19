<?php

namespace App\Livewire;

use App\Models\Exam;
use App\Models\MyClass;
use App\Models\Section;
use Livewire\Component;
use App\Models\ExamMarks;
use App\Models\GradingRange;

class SubjectAnalysis extends Component
{
    public $classId;  // Selected class ID
    public $sectionId; // Selected section ID
    public $examId;    // Selected exam ID
    public $sections = []; // Array to hold sections for the selected class
    public $subjects = [];
    public $gradesCount = [];
    public $classes; // List of classes
    public $exams = []; // List of exams for the selected class
    public $errorMessage;

    public function mount()
    {
        $this->classes = MyClass::with('sections')->get(); // Load classes with sections
        $this->sections = [];
        $this->subjects = [];
        $this->gradesCount = [];
    }

    public function updatedClassId()
    {
        $this->sectionId = null; // Reset section ID when class changes
        $this->examId = null;    // Reset exam ID
        $this->sections = [];     // Clear sections
        $this->subjects = [];     // Clear subjects
        $this->gradesCount = [];   // Clear grades count
        $this->errorMessage = null; // Clear error message

        if ($this->classId) {
            // Load sections and exams for the selected class
            $this->sections = MyClass::find($this->classId)->sections;
            $this->exams = MyClass::find($this->classId)->exams;
        }
    }

    public function updatedSectionId()
    {
        // Whenever the section is updated, clear the grades count and error message
        $this->gradesCount = [];
        $this->errorMessage = null;

        // Optionally, you may want to call getGradesCount if needed
        if ($this->examId) {
            $this->getGradesCount(); // Refresh grades count if an exam is already selected
        }
    }

    public function updatedExamId()
    {
        $this->gradesCount = []; // Reset grades count
        $this->errorMessage = null; // Clear error message

        if ($this->examId) {
            $exam = Exam::with('gradingSystem.subjects')->find($this->examId);

            if ($exam) {
                $this->subjects = $exam->gradingSystem->subjects;
                $this->getGradesCount(); // Fetch grades count after selecting the exam
            } else {
                $this->errorMessage = 'Exam not found.';
            }
        }
    }

    public function getGradesCount()
    {
        // Check if the necessary parameters are set
        if (!$this->examId) {
            $this->errorMessage = 'Please select an exam to view subject analysis.';
            return;
        }
    
        if (empty($this->subjects)) {
            $this->errorMessage = 'No subjects found for the selected exam.';
            return;
        }
    
        // Initialize grades count for each subject
        foreach ($this->subjects as $subject) {
            $this->gradesCount[$subject->id] = [
                'subject_name' => $subject->subject_name,
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
    
        // Query to filter exam marks based on selected class, section, and exam
        $examMarksQuery = ExamMarks::where('exam_id', $this->examId)
            ->whereHas('student', function ($query) {
                $query->where('my_class_id', $this->classId);
    
                if ($this->sectionId) {
                    $query->where('section_id', $this->sectionId);
                }
            })
            ->with(['subject', 'student']);
    
        $examMarks = $examMarksQuery->get();
    
        // Retrieve all grading ranges for the selected exam's grading system
        $gradingSystemId = Exam::find($this->examId)->grading_system_id;
        $gradingRanges = GradingRange::where('grading_system_id', $gradingSystemId)->get()->groupBy('subject_id');
    
        // Count grades for each subject based on the grading ranges
        foreach ($examMarks as $mark) {
            $subjectId = $mark->subject_id;
    
            // Get the grade using the grading ranges for the subject
            $grade = $this->getGrade($mark->marks, $gradingRanges[$subjectId] ?? []);
    
            // Increment the grade count for the subject
            if (isset($this->gradesCount[$subjectId]['grades'][$grade])) {
                $this->gradesCount[$subjectId]['grades'][$grade]++;
            }
        }
    
        if (empty($this->gradesCount)) {
            $this->errorMessage = 'No grades found for the selected criteria.';
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
    
        // Iterate through the grading ranges to find the appropriate grade
        foreach ($gradingRanges as $range) {
            if ($marks >= $range->range_from && $marks <= $range->range_to) {
                return $range->grade; // Assuming 'grade' is the column storing grade (A, B, etc.)
            }
        }
    
        return 'F'; // If no range is matched, return a failing grade
    }


    
    



    public function render()
    {
        return view('livewire.subject-analysis', [
            'gradesCount' => $this->gradesCount,
            'errorMessage' => $this->errorMessage,
            'sections' => $this->sections, // Pass sections to the view
        ]);
    }
}
