<?php
namespace Database\Seeders;

use App\Models\ClassType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MyClassesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('my_classes')->delete();
        $ct = ClassType::pluck('id')->all();

        // Check available class types and use defaults if not enough
        if (count($ct) < 4) {
            $ct = array_pad($ct, 4, null); // Pad with nulls if less than 4
        }

        $data = [
            ['name' => 'Form 1', 'class_type_id' => $ct[2] ?? null],
            ['name' => 'Form 2', 'class_type_id' => $ct[2] ?? null],
            ['name' => 'Form 3', 'class_type_id' => $ct[2] ?? null],
            ['name' => 'Form 4', 'class_type_id' => $ct[3] ?? null],
        ];

        DB::table('my_classes')->insert($data);
    }
}
