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
        // Admin user 1
        User::create([
            'name' => 'Lorraine',
            'email' => 'lorrainefrancesdesagun19@gmail.com',
            'role' => 'admin',
            'password' => bcrypt('Admin12345'),
        ]);

        // Admin user 2
        User::create([
            'name' => 'Fritzie',
            'email' => 'fritziecadao@gmail.com',
            'role' => 'admin',
            'password' => bcrypt('Admin12345'),
        ]);

        // Test users
        User::create([
            'name' => 'Raymund Turallo',
            'email' => 'raymund@example.com',
            'role' => 'user',
            'password' => bcrypt('password123'),
        ]);

        User::create([
            'name' => 'Elijah Gallardo',
            'email' => 'elijah@example.com',
            'role' => 'user',
            'password' => bcrypt('password123'),
        ]);

        User::create([
            'name' => 'Francis Balbin',
            'email' => 'francis@example.com',
            'role' => 'user',
            'password' => bcrypt('password123'),
        ]);

        User::create([
            'name' => 'Donn Torres',
            'email' => 'donn@example.com',
            'role' => 'user',
            'password' => bcrypt('password123'),
        ]);

    }
}
