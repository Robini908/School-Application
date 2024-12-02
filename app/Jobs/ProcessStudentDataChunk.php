<?php

namespace App\Jobs;

use App\Models\StudentRecord;
use App\Models\GradingSystem;
use App\Models\Subject;
use App\Models\StudentResult;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessStudentDataChunk implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $exam;
    protected $students;

    public function __construct($exam, $students)
    {
        $this->exam = $exam;
        $this->students = $students;
    }

    public function handle()
    {
        $exam = $this->exam;
        $students = $this->students;
        $studentData = [];

        // Ensure $students is not null and is an array
        if (is_array($students) || is_object($students)) {
            foreach ($students as $student) {
                $studentMarks = [];
                $studentGrades = [];
                $totalMarks = 0;
                $totalPoints = 0;
                $enrolledSubjectIds = $student['subjects'];

                $studentMarks['enrolled_subject_ids'] = $enrolledSubjectIds;

                // Ensure $exam->subjects exists before processing
                if (!empty($exam->subjects)) {
                    foreach ($exam->subjects as $subject) {
                        if (in_array($subject->id, $enrolledSubjectIds)) {
                            $marksValue = $this->getStudentMarks($student, $subject);
                            $marksValue = (int)$marksValue;

                            $gradeData = $this->getGradeData($marksValue, $exam->gradingSystem->id, $subject->id);

                            $studentMarks[$subject->id] = $marksValue;
                            $points = isset($gradeData['points']) ? (int)$gradeData['points'] : 0;
                            $studentGrades[$subject->id] = $points > 0 ? $gradeData['grade'] : '-';

                            $totalMarks += $marksValue;
                            $totalPoints += $points;
                        } else {
                            $studentMarks[$subject->id] = '--';
                            $studentGrades[$subject->id] = '--';
                        }
                    }
                }

                $meanScore = count($enrolledSubjectIds) ? $totalMarks / count($enrolledSubjectIds) : 0;
                $meanGrade = $this->getMeanGrade($totalPoints, $exam->gradingSystem->id);

                $studentResult = [
                    'student_id' => $student['id'],
                    'exam_id' => $exam->id,
                    'student_name' => "{$student['first_name']} {$student['last_name']}",
                    'marks' => $studentMarks,
                    'grades' => $studentGrades,
                    'total_marks' => $totalMarks,
                    'total_points' => $totalPoints,
                    'mean_score' => $meanScore,
                    'mean_grade' => $meanGrade,
                    'stream' => $student['section']['name'] ?? '-',
                ];

                $studentData[] = $studentResult;
            }

            // Store processed data in cache
            $cacheKey = "exam_{$exam->id}_student_data";
            cache()->put($cacheKey, $studentData, now()->addHours(2));

            Log::info("Processed data for exam ID: {$exam->id} and cached successfully.");
        } else {
            Log::error("No students found for processing in exam ID: {$exam->id}");
        }
    }


    // Helper functions to get marks and grade data
    private function getStudentMarks($student, $subject)
    {
        // Implement logic to fetch marks for the student from the database
        return rand(50, 100); // Example return
    }

    private function getGradeData($marks, $gradingSystemId, $subjectId)
    {
        // Implement logic to fetch grade data from the database
        return [
            'points' => rand(1, 5), // Example
            'grade' => 'A' // Example
        ];
    }

    private function getMeanGrade($totalPoints, $gradingSystemId)
    {
        // Logic to determine the mean grade based on points
        return $totalPoints > 20 ? 'A' : 'B'; // Example
    }
}
