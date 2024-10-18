<?php

namespace Database\Seeders;

use App\Models\ClassType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MyClassesTableSeeder extends Seeder
{
    public function run()
    {
        // // Ensure the table is empty before seeding, but this could be optional
        // // If you want to prevent deleting existing classes, comment out the line below.
        // DB::table('my_classes')->truncate(); // More efficient than delete()

        // Get available class types
        $classTypes = ClassType::pluck('id')->all();

        // Use defaults if not enough class types are available
        if (count($classTypes) < 4) {
            $classTypes = array_pad($classTypes, 4, null); // Pad with nulls if less than 4
        }

        // Prepare class data with fallback for class type
        $data = [
            ['name' => 'Form 1', 'class_type_id' => $classTypes[0] ?? null],
            ['name' => 'Form 2', 'class_type_id' => $classTypes[1] ?? null],
            ['name' => 'Form 3', 'class_type_id' => $classTypes[2] ?? null],
            ['name' => 'Form 4', 'class_type_id' => $classTypes[3] ?? null],
        ];

        // Insert class data
        DB::table('my_classes')->insert($data);
    }
}
