<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\Stock;
use App\Models\ProductImage;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Categories with descriptions
        $categories = [
            ['name' => 'Tents', 'description' => 'Camping tents and shelters for outdoor adventures'],
            ['name' => 'Sleeping Gear', 'description' => 'Sleeping bags, pads, and pillows for comfortable rest'],
            ['name' => 'Backpacks', 'description' => 'High-quality backpacks for hiking and camping'],
            ['name' => 'Cooking Equipment', 'description' => 'Portable stoves, cookware, and utensils'],
            ['name' => 'Lighting', 'description' => 'Lanterns, flashlights, and headlamps'],
            ['name' => 'Clothing', 'description' => 'Weather-resistant clothing for outdoor activities'],
            ['name' => 'Navigation', 'description' => 'Maps, compasses, and GPS devices'],
            ['name' => 'First Aid', 'description' => 'First aid kits and emergency supplies'],
        ];

        // Insert categories
        $createdCategories = collect();
        foreach ($categories as $category) {
            $createdCategories->push(Category::create($category));
        }

        // Products linked to categories with image paths
        $products = [
            [
                'name' => 'Family Camping Tent 6-Person',
                'description' => 'Spacious 6-person dome tent with waterproof coating.',
                'cost_price' => 4400.00,
                'sell_price' => 8250.00,
                'category_id' => $createdCategories[0]->category_id,
                'img_path' => 'products/6person tent.png',
                'gallery_images' => [
                    'products/6person tent 2.png',
                ],
            ],
            [
                'name' => 'Winter Sleeping Bag -15°C',
                'description' => 'Premium insulated sleeping bag rated for -15°C.',
                'cost_price' => 6600.00,
                'sell_price' => 13750.00,
                'category_id' => $createdCategories[1]->category_id,
                'img_path' => 'products/sleeping bag.png',
                'gallery_images' => [
                    'products/sleeping bag 2.png',
                ],
            ],
            [
                'name' => 'Day Hike Backpack 25L',
                'description' => '25-liter capacity backpack perfect for day hikes.',
                'cost_price' => 2750.00,
                'sell_price' => 6600.00,
                'category_id' => $createdCategories[2]->category_id,
                'img_path' => 'products/backpack.png',
                'gallery_images' => [
                    'products/backpack2.png',
                ],
            ],
            [
                'name' => 'Portable Camping Stove',
                'description' => 'Lightweight stove that runs on propane cartridges.',
                'cost_price' => 1650.00,
                'sell_price' => 3300.00,
                'category_id' => $createdCategories[3]->category_id,
                'img_path' => 'products/camping stove.png',
                'gallery_images' => [
                    'products/camping stove 2.png',
                ],
            ],
            [
                'name' => 'LED Camping Lantern',
                'description' => 'Rechargeable LED lantern with adjustable brightness.',
                'cost_price' => 1375.00,
                'sell_price' => 3300.00,
                'category_id' => $createdCategories[4]->category_id,
                'img_path' => 'products/LED Camping Lantern.png',
                'gallery_images' => [
                    'products/LED Camping Lanteren 2.png',
                ],
            ],
            // Clothing Products
            [
                'name' => 'Thermal Base Layer Set',
                'description' => 'Lightweight thermal base layers for temperature regulation.',
                'cost_price' => 1100.00,
                'sell_price' => 2500.00,
                'category_id' => $createdCategories[5]->category_id,
                'img_path' => 'products/thermal base layer.png',
                'gallery_images' => [
                    'products/thermal base layer 2.png',
                    'products/thermal base layer 3.png',
                ],
            ],
            [
                'name' => 'Insulated Hiking Boots',
                'description' => 'Waterproof insulated boots for cold weather hiking.',
                'cost_price' => 3300.00,
                'sell_price' => 6600.00,
                'category_id' => $createdCategories[5]->category_id,
                'img_path' => 'products/hiking boots.png',
                'gallery_images' => [
                    'products/hiking boots 2.png',
                ],
            ],
            [
                'name' => 'Windproof Fleece Jacket',
                'description' => 'Lightweight fleece jacket with wind-resistant outer shell.',
                'cost_price' => 1650.00,
                'sell_price' => 3850.00,
                'category_id' => $createdCategories[5]->category_id,
                'img_path' => 'products/fleece jacket.png',
                'gallery_images' => [
                    'products/fleece jacket 2.png',
                ],
            ],
            // Navigation Products
            [
                'name' => 'Magnetic Compass',
                'description' => 'Professional magnetic compass with adjustable declination.',
                'cost_price' => 550.00,
                'sell_price' => 1375.00,
                'category_id' => $createdCategories[6]->category_id,
                'img_path' => 'products/magnetic compass.png',
                'gallery_images' => [
                    'products/magnetic compass 2.png',
                ],
            ],
            [
                'name' => 'GPS Navigation Device',
                'description' => 'Portable GPS device with detailed trail mapping.',
                'cost_price' => 4950.00,
                'sell_price' => 11000.00,
                'category_id' => $createdCategories[6]->category_id,
                'img_path' => 'products/gps device.png',
                'gallery_images' => [
                    'products/gps device 2.png',
                ],
            ],
            // First Aid Products
            [
                'name' => 'Complete First Aid Kit',
                'description' => 'Comprehensive first aid kit with bandages, gauze, and medications.',
                'cost_price' => 1100.00,
                'sell_price' => 2750.00,
                'category_id' => $createdCategories[7]->category_id,
                'img_path' => 'products/first aid kit.png',
                'gallery_images' => [
                    'products/first aid kit 2.png',
                ],
            ],
            [
                'name' => '7 in 1 Emergency Whistle',
                'description' => 'High-pitched emergency whistle audible from great distances with multiple functions.',
                'cost_price' => 165.00,
                'sell_price' => 550.00,
                'category_id' => $createdCategories[7]->category_id,
                'img_path' => 'products/emergency whistle.png',
                'gallery_images' => [
                    'products/emergency whistle 2.png',
                ],
            ],
        ];

        foreach ($products as $productData) {
            $galleryImages = $productData['gallery_images'] ?? [];
            unset($productData['gallery_images']);

            $createdProduct = Product::create($productData);

            Stock::create([
                'product_id' => $createdProduct->product_id,
                'quantity' => rand(5, 50),
                'warehouse_location' => 'Warehouse ' . chr(65 + rand(0, 3)),
            ]);

            foreach ($galleryImages as $imagePath) {
                ProductImage::create([
                    'product_id' => $createdProduct->product_id,
                    'img_path' => $imagePath,
                ]);
            }
        }
    }
}
