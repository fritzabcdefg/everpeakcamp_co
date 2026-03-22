<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class ProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();

        if ($products->isEmpty()) {
            return;
        }

        $imageSets = [
            [
                'products/tent-dome-main.jpg',
                'products/tent-dome-side.jpg',
            ],
            [
                'products/sleeping-bag-main.jpg',
                'products/sleeping-bag-packed.jpg',
            ],
            [
                'products/backpack-main.jpg',
                'products/backpack-detail.jpg',
            ],
            [
                'products/stove-main.jpg',
                'products/stove-in-use.jpg',
            ],
            [
                'products/lantern-main.jpg',
                'products/lantern-night.jpg',
            ],
        ];

        foreach ($products as $index => $product) {
            $paths = $imageSets[$index % count($imageSets)];

            foreach ($paths as $path) {
                ProductImage::create([
                    'product_id' => $product->product_id,
                    'img_path' => $path,
                ]);
            }
        }
    }
}
