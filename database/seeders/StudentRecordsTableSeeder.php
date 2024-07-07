<?php

namespace Database\Seeders;

use App\Models\Section;
use App\Models\StudentRecord;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentRecordsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create a single student record
        $this->createStudentRecord();

        // Create multiple student records
        $this->createManyStudentRecords(9); // Adjust count as needed
    }

    protected function createManyStudentRecords(int $count)
    {
        $sections = Section::all();

        foreach ($sections as $section) {
            // Create student records using the factory
            StudentRecord::factory()
                ->count($count)
                ->create([
                    'my_class_id' => $section->my_class_id,
                    'section_id' => $section->id,
                    'user_id' => function () {
                        return User::factory()->create()->id;
                    },
                ]);
        }
    }

    protected function createStudentRecord()
    {
        $section = Section::first();

        // Generate a unique username using timestamp
        $username = 'student_' . time();

        // Generate a unique email using timestamp
        $email = 'student_' . time() . '@student.com';

        // Create a single student record
        $user = User::factory()->create([
            'name' => 'Student CJ',
            'user_type' => 'student',
            'username' => $username,  // Use the generated unique username
            'password' => Hash::make('cj'),
            'email' => $email,  // Use the generated unique email
        ]);

        StudentRecord::factory()->create([
            'my_class_id' => $section->my_class_id,
            'user_id' => $user->id,
            'section_id' => $section->id,
        ]);
    }
}
