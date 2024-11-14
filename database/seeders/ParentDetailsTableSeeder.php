<?php

namespace Database\Seeders;

use App\Models\ParentDetail;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ParentDetailsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Create 20,000 ParentDetail records
        for ($i = 0; $i < 200; $i++) {
            $parentIdNo = $faker->unique()->numerify('PARENT#####'); // Unique parent ID
            
            // Create ParentDetail
            ParentDetail::create([
                'parent_id_no' => $parentIdNo,
                'parent_first_name' => $faker->firstName,
                'parent_middle_name' => $faker->optional()->firstName,
                'parent_last_name' => $faker->lastName,
                'parent_phone_number' => $faker->phoneNumber,
                'parent_email' => $faker->unique()->safeEmail,
                'parent_password' => bcrypt('password'), // Password hashing
            ]);
        }
    }
}
