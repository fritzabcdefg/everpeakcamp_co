<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CartItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $products = Product::all();

        if ($users->isEmpty() || $products->isEmpty()) {
            return;
        }

        // Add random cart items for each user
        foreach ($users->take(3) as $user) {
            $randomProducts = $products->random(rand(2, 5));
            
            foreach ($randomProducts as $product) {
                CartItem::create([
                    'user_id' => $user->id,
                    'product_id' => $product->product_id,
                    'quantity' => rand(1, 3),
                ]);
            }
        }
    }
}
