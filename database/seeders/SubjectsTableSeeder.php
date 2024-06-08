<?php

namespace Database\Seeders;

use App\Models\MyClass;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('subjects')->delete();

        $this->createSubjects();
    }

    protected function createSubjects()
    {
        $subjects = ['English', 'Mathematics'];
        $sub_slug = ['Eng', 'Math'];
        $sub_code = ['231','231'];

        

            $data = [

                [
                    'subject_name' => $subjects[0],
                    'subject_code' => $sub_code[0],
                    'abbreviation' => $sub_slug[0]
                ],

                [
                    'subject_name' => $subjects[0],
                    'subject_code' => $sub_code[0],
                    'abbreviation' => $sub_slug[0]
                ],

            ];

            DB::table('subjects')->insert($data);
        

    }

}
