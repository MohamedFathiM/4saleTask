<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Table;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'table_id' => Table::factory(),
            'customer_id' => Customer::factory(),
            'from_time' => $this->faker->time('H:i'),
            'to_time' => $this->faker->time('H:i'),
            'date' => $this->faker->date('Y-m-d'),
        ];
    }
}
