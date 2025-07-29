<?php

namespace Database\Seeders;

use App\Models\{Registration, User};
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name'  => 'Test User',
            'email' => 'test@example.com',
            'password' => 'Password@123',
            'type' => 'supervisor',
        ]);

        User::factory()->create([
            'name'  => 'Instrutor User',
            'email' => 'instructor@example.com',
            'password' => 'Password@123',
            'type' => 'instructor',
        ]);

        Registration::factory(10)->create([
            'user_id' => 1, // Assuming the first user is the supervisor
        ]);
    }
}
