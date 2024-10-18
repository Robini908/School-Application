<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(BloodGroupsTableSeeder::class);
        $this->call(NationalitiesTableSeeder::class);
        $this->call(StatesTableSeeder::class);
       
        $this->call(SkillsTableSeeder::class);
        $this->call(UserTypesTableSeeder::class);
        $this->call(ClassTypesTableSeeder::class);
        $this->call(MyClassesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(SettingsTableSeeder::class);
        $this->call(DormsTableSeeder::class);
        $this->call(SectionsTableSeeder::class);
        $this->call(StudentRecordSeeder::class);
        $this->call(ExamMarksSeeder::class);
        $this->call(LgasTableSeeder::class);
        $this->call(SubjectCategorySeeder::class); 
        $this->call(SubjectSeeder::class); 
        $this->call(GradingSystemSeeder::class); 
    }
}
