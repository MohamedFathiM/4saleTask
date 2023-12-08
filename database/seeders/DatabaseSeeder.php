<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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
            'name' => 'Test Customer',
            'email' => 'test@example.com',
        ]);

        $user->customer()->create([
            'name' => 'Test Customer',
            'phone' => '01015394873',
        ]);
    }
}
