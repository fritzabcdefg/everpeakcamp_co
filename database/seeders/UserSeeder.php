<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
        ]);

        // Test user
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password123'),
        ]);

        // Additional users
        User::create([
            'name' => 'Sarah Smith',
            'email' => 'sarah@example.com',
            'password' => bcrypt('password123'),
        ]);

        User::create([
            'name' => 'Mike Johnson',
            'email' => 'mike@example.com',
            'password' => bcrypt('password123'),
        ]);

        User::create([
            'name' => 'Emily Brown',
            'email' => 'emily@example.com',
            'password' => bcrypt('password123'),
        ]);

        // Create 10 more random users using factory
        User::factory(10)->create();
    }
}
