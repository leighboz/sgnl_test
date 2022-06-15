<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $gender = $this->faker->randomElement(['male', 'female']);
        return [
            'given_names' => $this->faker->firstName($gender),
            'family_name' => $this->faker->lastName(),
            'date_of_birth' => $this->faker->dateTimeBetween('1920-01-01', '2000-01-01')->format('Y-m-d'),
            'gender' => $gender
        ];
    }
}
