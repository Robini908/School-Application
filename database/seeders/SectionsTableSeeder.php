<?php

namespace Database\Seeders;

use App\Models\MyClass;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectionsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('sections')->truncate();

        $classes = MyClass::pluck('id')->all();

        $data = [
            ['name' => 'Gold', 'my_class_id' => $classes[0], 'active' => 1],
            ['name' => 'Diamond', 'my_class_id' => $classes[0], 'active' => 0],
            ['name' => 'Silver', 'my_class_id' => $classes[1], 'active' => 1],
            ['name' => 'Lemon', 'my_class_id' => $classes[1], 'active' => 0],
            ['name' => 'Bronze', 'my_class_id' => $classes[2], 'active' => 1]
        ];

        DB::table('sections')->insert($data);
    }
}
