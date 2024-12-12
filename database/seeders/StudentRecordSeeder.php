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

        // Corrected unique section names
        $sectionNames = ['Gold', 'Diamond', 'Silver', 'Lemon', 'Bronze'];

        // Get existing classes, dorms, parents, and blood groups
        $classes = MyClass::all();
        $dorms = Dorm::all();
        $parents = ParentDetail::pluck('parent_id_no');
        $bloodGroups = BloodGroup::all();

        if ($classes->isEmpty() || $parents->isEmpty() || $bloodGroups->isEmpty()) {
            $this->command->error('No classes, parents, or blood groups found in the database.');
            return;
        }

        $totalStudents = 250;
        $studentsPerClass = (int) ($totalStudents / $classes->count());

        $studentsData = [];
        $studentCount = 0;

        $parentStudentCount = [];

        foreach ($classes as $class) {
            foreach ($sectionNames as $sectionName) {
                Section::firstOrCreate([
                    'name' => $sectionName,
                    'my_class_id' => $class->id
                ]);
            }

            $sections = Section::where('my_class_id', $class->id)->pluck('id');

            for ($i = 0; $i < $studentsPerClass; $i++) {
                if ($studentCount >= $totalStudents) break;

                $parentIdNo = $parents->random();
                while (isset($parentStudentCount[$parentIdNo]) && $parentStudentCount[$parentIdNo] >= 2) {
                    $parentIdNo = $parents->random();
                }

                $parentStudentCount[$parentIdNo] = ($parentStudentCount[$parentIdNo] ?? 0) + 1;

                $studentsData[] = [
                    'parent_id_no' => $parentIdNo,
                    'my_class_id' => $class->id,
                    'section_id' => $sections->random(),
                    'dorm_id' => $dorms->isNotEmpty() ? $dorms->random()->id : null,
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
                    'bg_id' => $bloodGroups->random()->id,
                    'photo' => null,
                    'status' => 'unverified',
                    'student_password' => bcrypt('password'),
                    'is_suspended' => false,
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

                if (count($studentsData) === 1000) {
                    StudentRecord::insert($studentsData);
                    $studentsData = [];
                }
            }
        }

        if (!empty($studentsData)) {
            StudentRecord::insert($studentsData);
        }
    }
}
