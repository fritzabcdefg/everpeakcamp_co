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
                'products/6person tent.png',
                'products/6person tent 2.png',
            ],
            [
                'products/sleeping bag.png',
                'products/sleeping bag 2.png',
            ],
            [
                'products/backpack.png',
                'products/backpack2.png',
            ],
            [
                'products/camping stove.png',
                'products/camping stove 2.png',
            ],
            [
                'products/LED Camping Lantern.png',
                'products/LED Camping Lanteren 2.png',
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
