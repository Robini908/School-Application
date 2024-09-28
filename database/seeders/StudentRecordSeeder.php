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
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        
        // Define section names
        $sectionNames = ['Yellow', 'Blue', 'Green', 'Purple', 'Violet', 'Orange'];

        // Get existing classes, dorms, parents, and blood groups
        $classes = MyClass::all();
        $dorms = Dorm::all();
        $parents = ParentDetail::all();
        $bloodGroups = BloodGroup::all();

        // Check if we have enough data to seed
        if ($classes->isEmpty() || $parents->isEmpty() || $bloodGroups->isEmpty()) {
            $this->command->error('No classes, parents, or blood groups found in the database.');
            return;
        }

        // We want to create 200 student records, distributed across all classes and sections
        $totalStudents = 200;
        $studentsPerClass = (int) ($totalStudents / $classes->count());

        $studentCount = 0;

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
            $sections = Section::where('my_class_id', $class->id)->get();

            // Distribute students across the class and its sections
            for ($i = 0; $i < $studentsPerClass; $i++) {
                if ($studentCount >= $totalStudents) break; // Limit to 200 students

                $section = $sections->random(); // Randomly select a section from this class
                $dorm = $dorms->isNotEmpty() ? $dorms->random()->id : null;
                $parent = $parents->random();
                $bloodGroup = $bloodGroups->random()->id; // Ensure a valid blood group ID is used

                StudentRecord::create([
                    'parent_id_no' => $parent->parent_id_no,
                    'my_class_id' => $class->id,
                    'section_id' => $section->id,
                    'dorm_id' => $dorm,
                    'adm_no' => 'ADM' . str_pad($studentCount + 1, 3, '0', STR_PAD_LEFT),
                    'year_admitted' => Carbon::now()->year,
                    'kcpe' => $faker->numberBetween(250, 500),
                    'first_name' => $faker->firstName,
                    'middle_name' => $faker->lastName,
                    'last_name' => $faker->lastName,
                    'email' => $faker->unique()->safeEmail,
                    'gender' => $faker->randomElement(['Male', 'Female']),
                    'phone' => $faker->phoneNumber,
                    'dob' => $faker->date('Y-m-d', '2005-01-01'),
                    'nal_id' => null,
                    'state_id' => null,
                    'lga_id' => null,
                    'town' => $faker->city,
                    'bg_id' => $bloodGroup, // Use valid blood group ID
                    'photo' => null,
                    'status' => 'unverified',
                    'student_password' => bcrypt('password'),
                ]);

                $studentCount++;
            }
        }

        // If there are any remaining students, assign them randomly to fill the 200 total
        while ($studentCount < $totalStudents) {
            $class = $classes->random();
            $sections = Section::where('my_class_id', $class->id)->get();
            $section = $sections->random();
            $dorm = $dorms->isNotEmpty() ? $dorms->random()->id : null;
            $parent = $parents->random();
            $bloodGroup = $bloodGroups->random()->id;

            StudentRecord::create([
                'parent_id_no' => $parent->parent_id_no,
                'my_class_id' => $class->id,
                'section_id' => $section->id,
                'dorm_id' => $dorm,
                'adm_no' => 'ADM' . str_pad($studentCount + 1, 3, '0', STR_PAD_LEFT),
                'year_admitted' => Carbon::now()->year,
                'kcpe' => $faker->numberBetween(250, 500),
                'first_name' => $faker->firstName,
                'middle_name' => $faker->lastName,
                'last_name' => $faker->lastName,
                'email' => $faker->unique()->safeEmail,
                'gender' => $faker->randomElement(['Male', 'Female']),
                'phone' => $faker->phoneNumber,
                'dob' => $faker->date('Y-m-d', '2005-01-01'),
                'nal_id' => null,
                'state_id' => null,
                'lga_id' => null,
                'town' => $faker->city,
                'bg_id' => $bloodGroup,
                'photo' => null,
                'status' => 'unverified',
                'student_password' => bcrypt('password'),
            ]);

            $studentCount++;
        }
    }
}
