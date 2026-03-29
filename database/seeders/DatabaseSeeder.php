<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run seeders in correct order (dependencies first)
        $this->call([
            UserSeeder::class,          // Create users first
            ProductSeeder::class,       // Create categories, products, stock, and gallery images
            // ProductImageSeeder::class,  // DISABLED: ProductSeeder already adds gallery images correctly
            CartItemSeeder::class,      // Create cart items (depends on users and products)
            OrderSeeder::class,         // Create customers and orders (depends on users and products)
            ReviewSeeder::class,        // Create reviews (depends on users and products)
        ]);
    }
}
