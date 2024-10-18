<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExamMarks;
use App\Models\StudentRecord;
use App\Models\Exam;
use App\Models\Subject;
use App\Models\GradingRange;
use Faker\Factory as Faker;

class ExamMarksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        // Get all student, exam, subject, and grading range IDs
        $studentIds = StudentRecord::pluck('id')->toArray();
        $examIds = Exam::pluck('id')->toArray();
        $subjectIds = Subject::pluck('id')->toArray();
        $gradingRangeIds = GradingRange::pluck('id')->toArray();

        foreach ($studentIds as $studentId) {
            foreach ($examIds as $examId) {
                foreach ($subjectIds as $subjectId) {
                    ExamMarks::create([
                        'student_id' => $studentId,
                        'exam_id' => $examId,
                        'subject_id' => $subjectId,
                        'grading_range_id' => $faker->randomElement($gradingRangeIds),
                        'marks' => $faker->numberBetween(0, 100), // Assuming max marks is 100
                    ]);
                }
            }
        }
    }
}
