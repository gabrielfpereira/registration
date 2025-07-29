<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FirstUserSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Here you can create the first user or any initial data you need.
        // For example:
        \App\Models\User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@soamar.com',
            'password' => bcrypt('Password@123'),
            'type'     => 'supervisor',
        ]);
    }
}
