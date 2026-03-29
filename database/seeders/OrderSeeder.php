<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample customers for orders
        $customers = [
            Customer::create(['name' => 'Juan dela Cruz', 'email' => 'juan@example.com', 'phone' => '555-1001', 'address' => '123 Main St']),
            Customer::create(['name' => 'Maria Santos', 'email' => 'maria@example.com', 'phone' => '555-1002', 'address' => '456 Oak Ave']),
            Customer::create(['name' => 'Miguel Reyes', 'email' => 'miguel@example.com', 'phone' => '555-1003', 'address' => '789 Pine Rd']),
            Customer::create(['name' => 'Ana García', 'email' => 'ana@example.com', 'phone' => '555-1004', 'address' => '321 Elm St']),
            Customer::create(['name' => 'Carlos Mendoza', 'email' => 'carlos@example.com', 'phone' => '555-1005', 'address' => '654 Cedar Ln']),
        ];

        // Get all products (excluding, make sure at least some exist)
        $products = Product::all();
        if ($products->isEmpty()) {
            echo "⚠️  No products found! Orders cannot be created without products.\n";
            return;
        }

        // Get all customer users (role = 'customer')
        $customerUsers = User::where('role', 'customer')->get();
        if ($customerUsers->isEmpty()) {
            echo "⚠️  No customer users found! Orders cannot be created without users.\n";
            return;
        }

        $statuses = ['completed', 'completed', 'completed', 'processing', 'pending', 'cancelled'];
        $orderCount = 0;
        
        // Create orders for each customer user
        foreach ($customerUsers as $user) {
            // Each user gets 8-14 orders over 2 years
            $ordersPerUser = rand(8, 14);
            
            for ($i = 0; $i < $ordersPerUser; $i++) {
                // Random date from Jan 1, 2024 to March 29, 2026
                $startDate = Carbon::create(2024, 1, 1);
                $endDate = Carbon::create(2026, 3, 29);
                $daysRange = abs($endDate->diffInDays($startDate));
                $randomDays = rand(0, $daysRange);
                $orderDate = $startDate->copy()->addDays($randomDays);
                
                $shippingFee = rand(250, 500);
                $randomCustomer = $customers[array_rand($customers)];
                
                // Create order
                try {
                    $order = Order::create([
                        'user_id' => $user->id,
                        'customer_id' => $randomCustomer->customer_id,
                        'shipping_fee' => $shippingFee,
                        'status' => $statuses[array_rand($statuses)],
                        'order_date' => $orderDate,
                    ]);

                    // Add 1-3 random products to this order
                    $itemCount = rand(1, 3);
                    $randomProducts = $products->random(min($itemCount, $products->count()));
                    
                    foreach ($randomProducts as $product) {
                        OrderItem::create([
                            'order_id' => $order->order_id,
                            'product_id' => $product->product_id,
                            'quantity' => rand(1, 3),
                            'unit_price' => $product->sell_price,
                        ]);
                    }
                    
                    $orderCount++;
                } catch (\Exception $e) {
                    echo "Error creating order for user {$user->id}: " . $e->getMessage() . "\n";
                }
            }
        }
        
        echo "✅ Successfully seeded {$orderCount} orders for " . $customerUsers->count() . " users!\n";
    }
}
