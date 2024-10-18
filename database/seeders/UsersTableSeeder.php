<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Helpers\Qs;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Truncate the users table for a clean slate (uncomment if you need it)
        // DB::table('users')->truncate();

        // Create default users
        $this->createNewUsers();

        // Create 100 users for each user type except teacher
        $this->createManyUsers(100);

        // Create 200 teachers
        $this->createTeachers(200);
    }

    protected function createNewUsers()
    {
        $password = Hash::make('cj'); // Default user password

        $users = [
            [
                'name' => 'CJ Inspired',
                'email' => 'cj@cj.com',
                'username' => 'cj',
                'password' => $password,
                'user_type' => 'super_admin',
                'code' => strtoupper(Str::random(10)),
                'remember_token' => Str::random(10),
            ],
            [
                'name' => 'Admin KORA',
                'email' => 'admin@admin.com',
                'username' => 'admin',
                'password' => $password,
                'user_type' => 'admin',
                'code' => strtoupper(Str::random(10)),
                'remember_token' => Str::random(10),
            ],
            [
                'name' => 'Parent Kaba',
                'email' => 'parent@parent.com',
                'username' => 'parent',
                'password' => $password,
                'user_type' => 'parent',
                'code' => strtoupper(Str::random(10)),
                'remember_token' => Str::random(10),
            ],
            [
                'name' => 'Accountant Jeff',
                'email' => 'accountant@accountant.com',
                'username' => 'accountant',
                'password' => $password,
                'user_type' => 'accountant',
                'code' => strtoupper(Str::random(10)),
                'remember_token' => Str::random(10),
            ],
        ];

        foreach ($users as $user) {
            // Check if email or username already exists
            if (!DB::table('users')->where('email', $user['email'])->orWhere('username', $user['username'])->exists()) {
                DB::table('users')->insert($user);
            }
        }
    }

    protected function createManyUsers(int $count)
    {
        $data = [];
        $userTypes = Qs::getAllUserTypes(['super_admin', 'admin', 'parent', 'accountant', 'librarian', 'student']);

        // Initialize Faker
        $faker = Faker::create();

        foreach ($userTypes as $userType) {
            for ($i = 1; $i <= $count; $i++) {
                $email = strtolower($userType) . $i . '@example.com';
                $username = strtolower($userType) . $i;

                // Check for existing email or username before adding to data array
                if (!DB::table('users')->where('email', $email)->orWhere('username', $username)->exists()) {
                    $data[] = [
                        'name' => ucfirst($userType) . ' ' . $faker->lastName, // Random last name
                        'email' => $email,
                        'user_type' => $userType,
                        'username' => $username,
                        'password' => Hash::make('password'), // Default password
                        'code' => strtoupper(Str::random(10)),
                        'remember_token' => Str::random(10),
                    ];
                }
            }
        }

        // Insert users in chunks to optimize performance
        $chunks = array_chunk($data, 1000); // Insert in batches of 1000
        foreach ($chunks as $chunk) {
            DB::table('users')->insert($chunk);
        }
    }

    protected function createTeachers(int $count)
    {
        $data = [];
        // Initialize Faker
        $faker = Faker::create();

        for ($i = 1; $i <= $count; $i++) {
            $email = 'teacher' . $i . '@example.com';
            $username = 'teacher' . $i;

            // Check for existing email or username before adding to data array
            if (!DB::table('users')->where('email', $email)->orWhere('username', $username)->exists()) {
                $data[] = [
                    'name' => 'Teacher ' . $faker->name, // Random teacher name
                    'email' => $email,
                    'user_type' => 'teacher',
                    'username' => $username,
                    'password' => Hash::make('password'), // Default password
                    'code' => strtoupper(Str::random(10)),
                    'remember_token' => Str::random(10),
                ];
            }
        }

        // Insert teachers in chunks to optimize performance
        $chunks = array_chunk($data, 1000); // Insert in batches of 1000
        foreach ($chunks as $chunk) {
            DB::table('users')->insert($chunk);
        }
    }
}
