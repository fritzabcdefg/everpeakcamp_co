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
            'phone' => '555-0001',
            'address' => 'Admin Office - Denver, CO',
            'email_verified_at' => now(),
        ]);

        // Admin user 2
        User::create([
            'name' => 'Fritzie',
            'email' => 'fritziecadao@gmail.com',
            'role' => 'admin',
            'password' => bcrypt('Admin12345'),
            'phone' => '555-0002',
            'address' => 'Admin Office - Denver, CO',
            'email_verified_at' => now(),
        ]);

        // Test users
        User::create([
            'name' => 'Raymund Turallo',
            'email' => 'raymund@gmail.com',
            'role' => 'customer',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Elijah Gallardo',
            'email' => 'elijah@gmail.com',
            'role' => 'customer',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Francis Balbin',
            'email' => 'francis@gmail.com',
            'role' => 'customer',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Donn Torres',
            'email' => 'donn@gmail.com',
            'role' => 'user',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
        ]);

    }
}
