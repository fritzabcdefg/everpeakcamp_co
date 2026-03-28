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
            'first_name' => 'Lorraine',
            'last_name' => 'Friar',
            'email' => 'lorrainefrancesdesagun19@gmail.com',
            'role' => 'admin',
            'password' => bcrypt('Admin12345'),
            'phone' => '555-0001',
            'address' => 'Admin Office - Denver, CO',
            'email_verified_at' => now(),
        ]);

        // Admin user 2
        User::create([
            'first_name' => 'Fritzie',
            'last_name' => 'Cadao',
            'email' => 'fritziecadao@gmail.com',
            'role' => 'admin',
            'password' => bcrypt('Admin12345'),
            'phone' => '555-0002',
            'address' => 'Admin Office - Denver, CO',
            'email_verified_at' => now(),
        ]);

        // Test users
        User::create([
            'first_name' => 'Raymund',
            'last_name' => 'Turallo',
            'email' => 'raymund@gmail.com',
            'role' => 'customer',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'first_name' => 'Elijah',
            'last_name' => 'Gallardo',
            'email' => 'elijah@gmail.com',
            'role' => 'customer',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'first_name' => 'Francis',
            'last_name' => 'Balbin',
            'email' => 'francis@gmail.com',
            'role' => 'customer',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'first_name' => 'Donn',
            'last_name' => 'Torres',
            'email' => 'donn@gmail.com',
            'role' => 'user',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
        ]);

    }
}
