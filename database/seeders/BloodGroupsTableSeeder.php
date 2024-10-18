<?php

namespace Database\Seeders;

use App\Models\BloodGroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BloodGroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       

        // Array of blood groups to seed
        $bloodGroups = ['O-', 'O+', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-'];

        // Create blood groups using a loop for compatibility
        $data = [];
        foreach ($bloodGroups as $bg) {
            $data[] = ['name' => $bg];
        }

        // Insert all blood groups in a single query
        BloodGroup::insert($data);
    }
}
