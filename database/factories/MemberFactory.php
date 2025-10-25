<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Member>
 */
class MemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'created_by' => 1,
            'member_code' => 'M-' . $this->faker->date('ymd') . '-' . $this->faker->ean8(),
            'name' => $this->faker->name(),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'birth_date' => $this->faker->date(),
            'address' => $this->faker->address(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'join_date' => $this->faker->date(),
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}
