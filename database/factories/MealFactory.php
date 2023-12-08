<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Meal>
 */
class MealFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'price' => $this->faker->randomNumber(3, true),
            'available_quantity' => $this->faker->numberBetween(1, 10),
            'description' => $this->faker->text,
            'discount' => $this->faker->numberBetween(1, 100),
        ];
    }
}
