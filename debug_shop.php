<?php
require 'vendor/autoload.php';
require 'bootstrap/app.php';

$totalProducts = \App\Models\Product::count();
$productsWithCategory = \App\Models\Product::whereNotNull('category_id')->count();
$noCategory = \App\Models\Product::whereNull('category_id')->count();
$categories = \App\Models\Category::all();

echo "Total products: $totalProducts\n";
echo "Products with category: $productsWithCategory\n";
echo "Products without category: $noCategory\n";
echo "Total categories: " . $categories->count() . "\n\n";
echo "Categories:\n";
foreach ($categories as $cat) {
    $count = \App\Models\Product::where('category_id', $cat->category_id)->count();
    echo "  ID: {$cat->category_id}, Name: {$cat->name} - $count products\n";
}

// Check first 5 products
echo "\nFirst 5 products:\n";
$products = \App\Models\Product::limit(5)->get(['product_id', 'name', 'category_id']);
foreach ($products as $prod) {
    echo "  ID: {$prod->product_id}, Name: {$prod->name}, Category: {$prod->category_id}\n";
}
