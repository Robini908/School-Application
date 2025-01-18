<?php

namespace App\Helpers;

use App\Models\Exam;
use App\Models\Subject;
use App\Models\ExamMarks;
use App\Models\StudentRecord;
use App\Models\GradingGrade;
use App\Models\GradingRange;
use Illuminate\Support\Facades\Log;

class StudentHelper
{
    /**
     * Filter students by transition year.
     *
     * @param int $transitionYear
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getStudentsByTransitionYear(int $transitionYear)
    {
        return StudentRecord::whereHas('transitions', function ($query) use ($transitionYear) {
            $query->where('transition_year', $transitionYear);
        })->get();
    }

    /**
     * Calculate the class position of a student for a given exam.
     *
     * @param StudentRecord $student
     * @param Exam $exam
     * @return int|string
     */
    public static function calculateClassPosition(StudentRecord $student, Exam $exam)
    {
        $students = StudentRecord::where('my_class_id', $student->my_class_id)->get();
        $studentScores = [];

        foreach ($students as $studentRecord) {
            $totalMarks = ExamMarks::where('student_id', $studentRecord->id)
                ->where('exam_id', $exam->id)
                ->sum('marks');

            $studentScores[] = ['student_id' => $studentRecord->id, 'total_marks' => $totalMarks];
        }

        // Sort students by total marks in descending order
        usort($studentScores, function ($a, $b) {
            return $b['total_marks'] <=> $a['total_marks'];
        });

        // Find position of the current student
        foreach ($studentScores as $index => $studentScore) {
            if ($studentScore['student_id'] == $student->id) {
                return $index + 1; // Return position
            }
        }

        return 'N/A';
    }

    /**
     * Calculate the stream position of a student for a given exam.
     *
     * @param StudentRecord $student
     * @param Exam $exam
     * @return int|string
     */
    public static function calculateStreamPosition(StudentRecord $student, Exam $exam)
    {
        $students = StudentRecord::where('section_id', $student->section_id)->get();
        $studentScores = [];

        foreach ($students as $studentRecord) {
            $totalMarks = ExamMarks::where('student_id', $studentRecord->id)
                ->where('exam_id', $exam->id)
                ->sum('marks');

            $studentScores[] = ['student_id' => $studentRecord->id, 'total_marks' => $totalMarks];
        }

        // Sort students by total marks in descending order
        usort($studentScores, function ($a, $b) {
            return $b['total_marks'] <=> $a['total_marks'];
        });

        // Find position of the current student
        foreach ($studentScores as $index => $studentScore) {
            if ($studentScore['student_id'] == $student->id) {
                return $index + 1;
            }
        }

        return 'N/A';
    }

    /**
     * Sort students by total marks, total points, mean score, and mean grade.
     *
     * @param array $studentData
     * @return array
     */
    public static function sortStudents(array $studentData)
    {
        usort($studentData, function ($a, $b) {
            // Primary sorting: Total marks (descending)
            if ($a['total_marks'] !== $b['total_marks']) {
                return $b['total_marks'] <=> $a['total_marks'];
            }

            // Secondary sorting: Total points (descending)
            if ($a['total_points'] !== $b['total_points']) {
                return $b['total_points'] <=> $a['total_points'];
            }

            // Tertiary sorting: Mean score (descending)
            if ($a['mean_score'] !== $b['mean_score']) {
                return $b['mean_score'] <=> $a['mean_score'];
            }

            // Quaternary sorting: Mean grade (ascending, assuming higher grades are better)
            return $a['mean_grade'] <=> $b['mean_grade'];
        });

        return $studentData;
    }

    /**
     * Assign positions to students after sorting.
     *
     * @param array $studentData
     * @return array
     */
    public static function assignPositions(array $studentData)
    {
        $previousTotalMarks = null;
        $previousTotalPoints = null;
        $previousMeanScore = null;
        $previousMeanGrade = null;
        $position = 0;
        $skipPosition = 0;

        foreach ($studentData as $index => &$student) {
            // Check if the current student has the same marks, points, mean score, and mean grade as the previous student
            if (
                $student['total_marks'] === $previousTotalMarks &&
                $student['total_points'] === $previousTotalPoints &&
                $student['mean_score'] === $previousMeanScore &&
                $student['mean_grade'] === $previousMeanGrade
            ) {
                // If tied, assign the same position and increment the skip counter
                $student['position'] = $position;
                $skipPosition++;
            } else {
                // If not tied, update the position and reset the skip counter
                $position += 1 + $skipPosition;
                $student['position'] = $position;
                $skipPosition = 0;
            }

            // Update previous values for the next iteration
            $previousTotalMarks = $student['total_marks'];
            $previousTotalPoints = $student['total_points'];
            $previousMeanScore = $student['mean_score'];
            $previousMeanGrade = $student['mean_grade'];
        }

        return $studentData;
    }

    /**
     * Get the marks for a student in a specific subject.
     *
     * @param StudentRecord $student
     * @param Subject $subject
     * @return int|null
     */
    public static function getStudentMarks(StudentRecord $student, Subject $subject)
    {
        $mark = $student->examMarks->firstWhere('subject_id', $subject->id);
        return $mark ? $mark->marks : null;
    }

    /**
     * Get the special grade for a student in a specific subject.
     *
     * @param StudentRecord $student
     * @param Subject $subject
     * @return string|null
     */
    public static function getStudentSpecialGrade(StudentRecord $student, Subject $subject)
    {
        $mark = $student->examMarks->firstWhere('subject_id', $subject->id);
        return $mark ? $mark->special_grade : null;
    }

     /**
     * Get the mean grade based on total points.
     *
     * @param float $totalPoints
     * @param int $gradingSystemId
     * @return string
     */
    public static function getMeanGrade($totalPoints, $gradingSystemId)
    {
        try {
            $gradeData = GradingGrade::where('grading_system_id', $gradingSystemId)
                ->where('range_from', '<=', $totalPoints)
                ->where('range_to', '>=', $totalPoints)
                ->first();

            return $gradeData ? $gradeData->grade : '--';
        } catch (\Throwable $e) {
            Log::error("Error fetching mean grade: " . $e->getMessage());
            return '--';
        }
    }

    /**
     * Get the grade data for a given marks value, grading system, and subject.
     *
     * @param mixed $marksValue
     * @param int $gradingSystemId
     * @param int $subjectId
     * @return array
     */
    public static function getGradeData($marksValue, $gradingSystemId, $subjectId)
    {
        if ($marksValue === '--' || $marksValue === null) {
            return ['grade' => '--', 'points' => '--']; // Handle undefined marks
        }

        try {
            $gradingRange = GradingRange::where('grading_system_id', $gradingSystemId)
                ->where('subject_id', $subjectId)
                ->where('range_from', '<=', $marksValue)
                ->where('range_to', '>=', $marksValue)
                ->first();

            if (!$gradingRange) {
                Log::warning("No grading range found for Marks: $marksValue, Grading System ID: $gradingSystemId, Subject ID: $subjectId");
                return ['grade' => '--', 'points' => '--'];
            }

            return [
                'grade' => $gradingRange->grade,
                'points' => $gradingRange->gpa ?? '--'
            ];
        } catch (\Throwable $e) {
            Log::error("Error fetching grade data: " . $e->getMessage());
            return ['grade' => '--', 'points' => '--'];
        }
    }

    
}
