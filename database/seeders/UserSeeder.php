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

        // Create 40 customer users
        $firstNames = ['Juan', 'Maria', 'Miguel', 'Ana', 'Carlos', 'Rosa', 'Pedro', 'Elena', 'Diego', 'Sofia',
                      'Luis', 'Carmen', 'Jose', 'Lucia', 'Antonio', 'Teresa', 'Miguel', 'Francisca', 'Manuel', 'Isabel',
                      'Jorge', 'Margarita', 'Ricardo', 'Dolores', 'Fernando', 'Pilar', 'Enrique', 'Montse', 'Alberto', 'Consuelo',
                      'Raúl', 'Angeles', 'Hector', 'Remedios', 'Guillermo', 'Virtudes', 'Andres', 'Juana', 'Arturo', 'Amparo'];
        
        $lastNames = ['de la Cruz', 'Santos', 'Reyes', 'Garcia', 'Mendoza', 'Lopez', 'Rodriguez', 'Martinez', 'Fernandez', 'Gonzalez',
                     'Sanchez', 'Perez', 'Torres', 'Ramirez', 'Flores', 'Rivera', 'Cruz', 'Gutierrez', 'Morales', 'Vargas',
                     'Romero', 'Navarro', 'Dominguez', 'Soto', 'Cortez', 'Medina', 'Ramos', 'Delgado', 'Ocasio', 'Rosado'];
        
        for ($i = 0; $i < 40; $i++) {
            $firstName = $firstNames[$i % count($firstNames)];
            $lastName = $lastNames[$i % count($lastNames)];
            
            User::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => strtolower($firstName . '.' . $lastName . ($i + 1) . '@example.com'),
                'role' => 'customer',
                'password' => bcrypt('password123'),
                'phone' => '+63 9' . rand(10, 99) . ' ' . rand(100, 999) . ' ' . rand(1000, 9999),
                'address' => rand(100, 999) . ' ' . $lastName . ' Street, Makati City',
                'email_verified_at' => now(),
            ]);
        }
    }
}
