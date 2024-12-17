<?php

namespace App\Livewire;

use App\Models\Exam;
use Livewire\Component;
use App\Models\GradingGrade;

class ClassAnalysis extends Component
{
    public $examId;
    public $averageMeanScore;
    public $gradesCount = [];
    public $errorMessage = null;
    public $exams = [];
    public $className; // Variable for class name
    public $examName; // Variable for exam name

    public function mount()
    {
        $this->exams = Exam::all();
    }

    public function getGradesCount()
    {
        // Validate exam selection
        if (!$this->examId) {
            $this->errorMessage = 'Please select an exam to view class analysis.';
            return;
        }
    
        $exam = Exam::with(['gradingSystem.gradingRanges', 'studentResults.student.my_class', 'studentResults.student.section'])
            ->find($this->examId);
    
        // Validate exam and grading system
        if (!$exam || !$exam->gradingSystem || $exam->gradingSystem->gradingRanges->isEmpty()) {
            $this->errorMessage = 'No grading system or ranges defined for this exam.';
            return;
        }
    
        $studentResults = $exam->studentResults;
    
        // Handle case where no student results are found
        if ($studentResults->isEmpty()) {
            // Update the error message to include the exam name
            $this->errorMessage = 'Error! No student results found for the exam: ' . $exam->name . '.';
            return;
        }
    
        // Initialize grades count
        $this->gradesCount = [];
        $totalMeanScores = []; // To store mean scores for average calculation
    
        // Process each student's result
        foreach ($studentResults as $result) {
            $student = $result->student;
            $classId = $student->my_class->id;
            $sectionId = $student->section->id;
    
            // Initialize class and section if not already set
            if (!isset($this->gradesCount[$classId])) {
                $this->gradesCount[$classId] = [
                    'class_name' => $student->my_class->name,
                    'sections' => [],
                ];
            }
    
            if (!isset($this->gradesCount[$classId]['sections'][$sectionId])) {
                $this->gradesCount[$classId]['sections'][$sectionId] = [
                    'section_name' => $student->section->name,
                    'grades' => array_fill_keys(['A', 'A-', 'B+', 'B', 'B-', 'C+', 'C', 'C-', 'D+', 'D', 'D-', 'E', 'F'], 0),
                    'student_count' => 0, // Count of students in the section
                    'total_score' => 0, // Total score to calculate mean
                    'mean_score' => 0, // To store the mean score for the section
                ];
            }
    
            // Increment student count for section
            $this->gradesCount[$classId]['sections'][$sectionId]['student_count']++;
    
            // Assuming each student result has a score field
            $totalScore = $result->score; // Adjust this if your score field is named differently
    
            // Ensure totalScore is a number
            if (is_numeric($totalScore)) {
                $this->gradesCount[$classId]['sections'][$sectionId]['total_score'] += $totalScore;
            }
    
            // Get the mean grade for the student
            $meanGrade = $result->mean_grade; // Assuming this contains the mean grade directly
    
            // Increment the corresponding grade count
            if (array_key_exists($meanGrade, $this->gradesCount[$classId]['sections'][$sectionId]['grades'])) {
                $this->gradesCount[$classId]['sections'][$sectionId]['grades'][$meanGrade]++;
            }
        }
    
        // Calculate mean score for each section
        foreach ($this->gradesCount as &$classData) {
            foreach ($classData['sections'] as &$sectionData) {
                if ($sectionData['student_count'] > 0) {
                    $sectionData['mean_score'] = $sectionData['total_score'] / $sectionData['student_count'];
                    $totalMeanScores[] = $sectionData['mean_score']; // Add to total mean scores for averaging
                } else {
                    $sectionData['mean_score'] = 0; // No students, mean score is 0
                }
            }
        }
    
        // Calculate overall average mean score for each class
        if (count($totalMeanScores) > 0) {
            $this->averageMeanScore = array_sum($totalMeanScores) / count($totalMeanScores);
        } else {
            $this->averageMeanScore = 0; // No scores to average
        }
    
        // Set the exam name and class name
        $this->examName = $exam->name; // Set the selected exam name
        $this->className = $this->gradesCount ? reset($this->gradesCount)['class_name'] : ''; // Set the first class name
    
        // Clear the error message if data is found
        $this->errorMessage = null;
    }


    // public function getMeanGrade($totalPoints, $gradingSystemId)
    // {
    //     // Find the corresponding grade for the total points based on the grading system
    //     $gradeData = GradingGrade::where('grading_system_id', $gradingSystemId)
    //         ->where('range_from', '<=', $totalPoints) // Updated column name
    //         ->where('range_to', '>=', $totalPoints)   // Updated column name
    //         ->first();

    //     return $gradeData ? $gradeData->grade : '-'; // Return '-' if no grade found
    // }
    

    public function render()
    {
        return view('livewire.class-analysis');
    }
}
