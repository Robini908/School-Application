<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GradingSystem;
use App\Models\Subject; // Import the Subject model
use Faker\Factory as Faker;

class GradingSystemSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create(); // Create a Faker instance

        // Retrieve all existing subjects
        $subjects = Subject::all();

        // Check if there are subjects to associate
        if ($subjects->isEmpty()) {
            throw new \Exception("No subjects available to associate with grading systems.");
        }

        // Generate 50 grading systems
        for ($i = 0; $i < 50; $i++) {
            // Create a new grading system
            $gradingSystem = GradingSystem::create([
                'name' => ucfirst($faker->word) . ' Grading System', // Random exam name + ' Grading System'
                'description' => 'Short description', // Short description
                'effective_date' => $faker->dateTimeThisDecade(), // Random effective date in the last decade
                'rules' => $this->generateGradingRules(), // Generate random grading rules
            ]);

            // Attach all subjects to the grading system in the pivot table
            $gradingSystem->subjects()->attach($subjects->pluck('id')->toArray());
        }
    }

    // Function to generate random grading rules
    private function generateGradingRules()
    {
        // Create an array of grading rules
        $rules = [
            'Exam irregularity is punishable.',
            'Each student must complete all assessments.',
            'Grades will be finalized at the end of each term.',
            'Late submissions will incur penalties.',
            'No cheating is tolerated.',
            
        ];

        // Randomly select 5 unique rules from the array
        return implode(', ', array_rand(array_flip($rules), 5));
    }
}
