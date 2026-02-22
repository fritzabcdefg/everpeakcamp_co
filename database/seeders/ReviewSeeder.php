<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
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

        $comments = [
            'Great quality! Highly recommended for outdoor adventures.',
            'Excellent product, very durable and lightweight.',
            'Perfect for camping trips. Amazing value for money.',
            'Exceeded my expectations. Will buy again!',
            'Outstanding quality and fast shipping.',
            'Best purchase I made for outdoor gear.',
            'Absolutely love it! Perfect fit and functionality.',
            'Quality is top-notch. Worth every penny.',
            'Reliable product that performs as advertised.',
            'Fantastic gear for the price. Highly satisfied.',
        ];

        // Create reviews for products from users
        foreach ($products as $product) {
            $reviewCount = rand(2, 5);
            $selectedUsers = $users->random($reviewCount);

            foreach ($selectedUsers as $user) {
                // Check if user hasn't already reviewed this product
                $existingReview = Review::where('user_id', $user->id)
                    ->where('product_id', $product->product_id)
                    ->first();

                if (!$existingReview) {
                    Review::create([
                        'product_id' => $product->product_id,
                        'user_id' => $user->id,
                        'rating' => rand(3, 5),
                        'comment' => $comments[array_rand($comments)],
                    ]);
                }
            }
        }
    }
}
