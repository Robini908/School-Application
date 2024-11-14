<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StudentRecord;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\Dorm;
use App\Models\ParentDetail;
use App\Models\BloodGroup;
use Carbon\Carbon;
use Faker\Factory as Faker;

class StudentRecordSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Define section names
        $sectionNames = ['Yellow', 'Blue', 'Green', 'Purple', 'Violet', 'Orange'];

        // Get existing classes, dorms, parents, and blood groups
        $classes = MyClass::all();
        $dorms = Dorm::all();
        $parents = ParentDetail::pluck('parent_id_no');  // Fetch all parent_id_no
        $bloodGroups = BloodGroup::all();

        // Check if we have enough data to seed
        if ($classes->isEmpty() || $parents->isEmpty() || $bloodGroups->isEmpty()) {
            $this->command->error('No classes, parents, or blood groups found in the database.');
            return;
        }

        // Define total number of students
        $totalStudents = 250;  // Modify as needed
        $studentsPerClass = (int) ($totalStudents / $classes->count());

        // Prepare an array for batch insert
        $studentsData = [];
        $studentCount = 0;

        // Track the count of students assigned to each parent
        $parentStudentCount = [];

        // Loop through each class
        foreach ($classes as $class) {
            // Create sections for the current class if not already created
            foreach ($sectionNames as $sectionName) {
                Section::firstOrCreate([
                    'name' => $sectionName,
                    'my_class_id' => $class->id
                ]);
            }

            // Get sections for the current class
            $sections = Section::where('my_class_id', $class->id)->pluck('id');

            // Distribute students across the class and its sections
            for ($i = 0; $i < $studentsPerClass; $i++) {
                if ($studentCount >= $totalStudents) break; // Limit to totalStudents

                // Randomly select a parent_id_no
                $parentIdNo = null;
                do {
                    $parentIdNo = $parents->random();
                } while (isset($parentStudentCount[$parentIdNo]) && $parentStudentCount[$parentIdNo] >= 2);

                // Increment the count for this parent
                if (!isset($parentStudentCount[$parentIdNo])) {
                    $parentStudentCount[$parentIdNo] = 0;
                }
                $parentStudentCount[$parentIdNo]++;

                $sectionId = $sections->random(); // Randomly select a section from this class
                $dormId = $dorms->isNotEmpty() ? $dorms->random()->id : null;
                $bloodGroupId = $bloodGroups->random()->id;

                $studentsData[] = [
                    'parent_id_no' => $parentIdNo,
                    'my_class_id' => $class->id,
                    'section_id' => $sectionId,
                    'dorm_id' => $dormId,
                    'adm_no' => 'ADM' . str_pad($studentCount + 1, 5, '0', STR_PAD_LEFT),
                    'year_admitted' => Carbon::now()->year,
                    'kcpe' => $faker->numberBetween(250, 500),
                    'first_name' => $faker->firstName,
                    'middle_name' => $faker->optional()->firstName ?? '',
                    'last_name' => $faker->lastName,
                    'email' => $faker->unique()->safeEmail,
                    'gender' => $faker->randomElement(['Male', 'Female']),
                    'phone' => $faker->phoneNumber,
                    'dob' => $faker->date('Y-m-d', '2005-01-01'),
                    'nal_id' => null,
                    'state_id' => null,
                    'lga_id' => null,
                    'town' => $faker->city,
                    'bg_id' => $bloodGroupId,
                    'photo' => null,
                    'status' => 'unverified',
                    'student_password' => bcrypt('password'),
                    'is_suspended' => false, // Default value for new records
                    'suspension_reason' => null,
                    'suspended_by' => null,
                    'notification_content' => null,
                    'suspension_date' => null,
                    'suspension_type' => null,
                    'suspension_end_date' => null,
                    'disapproval_reason' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $studentCount++;

                // Check if we reached the batch size of 1000
                if (count($studentsData) === 1000) {
                    // Insert the current batch
                    StudentRecord::insert($studentsData);
                    // Reset the array for the next batch
                    $studentsData = [];
                }
            }
        }

        // Additional loop to add students if needed
        while ($studentCount < $totalStudents) {
            $class = $classes->random();
            $sections = Section::where('my_class_id', $class->id)->pluck('id');
            $sectionId = $sections->random();
            $dormId = $dorms->isNotEmpty() ? $dorms->random()->id : null;

            // Ensure we do not exceed two students per parent
            $parentIdNo = null;
            do {
                $parentIdNo = $parents->random();
            } while (isset($parentStudentCount[$parentIdNo]) && $parentStudentCount[$parentIdNo] >= 2);

            // Increment the count for this parent
            if (!isset($parentStudentCount[$parentIdNo])) {
                $parentStudentCount[$parentIdNo] = 0;
            }
            $parentStudentCount[$parentIdNo]++;

            $bloodGroupId = $bloodGroups->random()->id;

            $studentsData[] = [
                'parent_id_no' => $parentIdNo,
                'my_class_id' => $class->id,
                'section_id' => $sectionId,
                'dorm_id' => $dormId,
                'adm_no' => 'ADM' . str_pad($studentCount + 1, 5, '0', STR_PAD_LEFT),
                'year_admitted' => Carbon::now()->year,
                'kcpe' => $faker->numberBetween(250, 500),
                'first_name' => $faker->firstName,
                'middle_name' => $faker->optional()->firstName ?? '',
                'last_name' => $faker->lastName,
                'email' => $faker->unique()->safeEmail,
                'gender' => $faker->randomElement(['Male', 'Female']),
                'phone' => $faker->phoneNumber,
                'dob' => $faker->date('Y-m-d', '2005-01-01'),
                'nal_id' => null,
                'state_id' => null,
                'lga_id' => null,
                'town' => $faker->city,
                'bg_id' => $bloodGroupId,
                'photo' => null,
                'status' => 'unverified',
                'student_password' => bcrypt('password'),
                'is_suspended' => false, // Default value for new records
                'suspension_reason' => null,
                'suspended_by' => null,
                'notification_content' => null,
                'suspension_date' => null,
                'suspension_type' => null,
                'suspension_end_date' => null,
                'disapproval_reason' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $studentCount++;

            // Check if we reached the batch size of 1000
            if (count($studentsData) === 1000) {
                // Insert the current batch
                StudentRecord::insert($studentsData);
                // Reset the array for the next batch
                $studentsData = [];
            }
        }

        // Insert any remaining records that didn't fill a complete batch
        if (!empty($studentsData)) {
            StudentRecord::insert($studentsData);
        }
    }
}
