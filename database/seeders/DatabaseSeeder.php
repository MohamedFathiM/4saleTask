<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Customer;
use App\Models\Meal;
use App\Models\Table;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Test waiter',
            'email' => 'test@example.com',
        ]);

        Customer::factory()->create([
            'name' => 'Test Customer',
            'phone' => '01015394873',
        ]);

        Table::factory(5)->create();
        Meal::factory(20)->create();
    }
}
