<?php
namespace Database\Seeders;

use App\Models\Dorm;
use App\User; 
use App\Models\StudentRecord;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DormsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $teachers = User::where('user_type', 'teacher')->pluck('id')->toArray();
        $students = StudentRecord::pluck('id')->toArray();

        // Check if there are teachers and students
        if (empty($teachers)) {
            die('No teachers found in the users table.');
        }
        
        if (empty($students)) {
            die('No students found in the student records.');
        }

        for ($i = 0; $i < 20; $i++) {
            $numberOfStudents = rand(30, 100); // Set a maximum limit for students in the dorm

            // Assign a random teacher as the dorm master
            $dormMasterId = $faker->randomElement($teachers);

            // Debug output
            echo "Creating dorm with dormMasterId: $dormMasterId\n"; // Debug line

            $dorm = Dorm::create([
                'name' => $faker->company . ' Dormitory',
                'capacity' => $numberOfStudents, // Set capacity to the number of students
                'user_id' => $dormMasterId, // Assign the teacher as the user_id
                'dorm_master_id' => $dormMasterId, // Add dorm_master_id for the teacher
                'session' => $faker->year($max = 'now'),
            ]);

            // Ensure that we don't assign more students than the capacity
            $assignedStudents = $faker->randomElements($students, min($numberOfStudents, count($students)));

            foreach ($assignedStudents as $studentId) {
                // Update the student record to link to the dorm
                StudentRecord::where('id', $studentId)->update(['dorm_id' => $dorm->id]);
            }
        }
    }
}
