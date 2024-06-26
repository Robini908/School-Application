<?php

namespace Database\Factories;

use App\Helpers\Qs;
use App\Models\StudentRecord;
use App\Models\Section;
use App\User;
use App\Models\MyClass;


use Illuminate\Database\Eloquent\Factories\Factory;

class StudentRecordFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StudentRecord::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $sections = Section::all();
        $section = $this->faker->randomElement($sections);

        return [
            'session' => Qs::getCurrentSession(), 'user_id' => User::factory(),
            'my_class_id' => MyClass::first()->id,
            'section_id' => Section::first()->id,
            'my_parent_id' => User::factory()->create()->id,
            'my_parent_id' => User::factory()->create()->id,
            'adm_no' => $this->faker->unique()->regexify('[A-Z]{3}/[0-9]{4}/2024'),
            'year_admitted' => 2024,
            'age' => $this->faker->numberBetween(15, 18),
            'first_name' => $this->faker->firstName,
            'middle_name' => $this->faker->lastName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'phone' => $this->faker->phoneNumber,
            'dob' => $this->faker->date(),
            'nal_id' => $this->faker->randomDigitNotNull,
            'state_id' => $this->faker->randomDigitNotNull,
            'lga_id' => $this->faker->randomDigitNotNull,
            'bg_id' => $this->faker->randomDigitNotNull,
            'photo' => null, // Adjust as per your logic
            'parent_first_name' => $this->faker->firstName,
            'parent_middle_name' => $this->faker->lastName,
            'parent_last_name' => $this->faker->lastName,
            'nin' => $this->faker->regexify('[0-9]{10}'),
            'parent_phone' => $this->faker->phoneNumber,
            'parent_email' => $this->faker->safeEmail,
            'password' => bcrypt('password'), // Adjust as per your logic
            'status' => 'pending', // Default status
            'action_done' => false, // Default action_done
        ];
    }
}
