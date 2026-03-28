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
        // Get users and products for relationships
        $users = User::all();
        $products = Product::all();

        if ($users->isEmpty() || $products->isEmpty()) {
            return;
        }

        // Create customers
        $customers = [
            ['name' => 'Juan dela Cruz', 'email' => 'juan.delacruz@example.com', 'phone' => '+63 912 345 6789', 'address' => '123 Makati Ave, Makati City'],
            ['name' => 'Maria Santos', 'email' => 'maria.santos@example.com', 'phone' => '+63 917 654 3210', 'address' => '456 BGC Taguig, Metro Manila'],
            ['name' => 'Miguel Reyes', 'email' => 'miguel.reyes@example.com', 'phone' => '+63 918 765 4321', 'address' => '789 Quezon City, NCR'],
            ['name' => 'Ana García', 'email' => 'ana.garcia@example.com', 'phone' => '+63 919 876 5432', 'address' => '321 Pasig City, NCR'],
            ['name' => 'Carlos Mendoza', 'email' => 'carlos.mendoza@example.com', 'phone' => '+63 920 123 4567', 'address' => '654 Davao City, Mindanao'],
            ['name' => 'Rosa López', 'email' => 'rosa.lopez@example.com', 'phone' => '+63 921 234 5678', 'address' => '987 Cebu City, Visayas'],
        ];

        $createdCustomers = collect();
        foreach ($customers as $customer) {
            $createdCustomers->push(Customer::create($customer));
        }

        $statuses = ['completed', 'completed', 'completed', 'processing', 'pending', 'cancelled'];
        
        // Generate all orders in chronological order (oldest to newest)
        $allOrders = [];
        
        // Create orders from 2024 (full year - Jan to Dec)
        $orders2024 = $this->generateOrdersForYear($users, $products, $createdCustomers, 2024, 1, 1, 12, 31, 10, 14, $statuses);
        $allOrders = array_merge($allOrders, $orders2024);
        
        // Create orders from 2025 (Jan to Mar 26)
        $orders2025 = $this->generateOrdersForYear($users, $products, $createdCustomers, 2025, 1, 1, 3, 26, 8, 12, $statuses);
        $allOrders = array_merge($allOrders, $orders2025);
        
        // Sort all orders by date (oldest to newest)
        usort($allOrders, function($a, $b) {
            return $a['order_date']->timestamp - $b['order_date']->timestamp;
        });
        
        // Create orders in chronological order
        foreach ($allOrders as $orderData) {
            $order = Order::create([
                'user_id' => $orderData['user_id'],
                'customer_id' => $orderData['customer_id'],
                'shipping_fee' => $orderData['shipping_fee'],
                'status' => $orderData['status'],
                'order_date' => $orderData['order_date'],
            ]);

            // Add order items
            foreach ($orderData['items'] as $item) {
                OrderItem::create([
                    'order_id' => $order->order_id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                ]);
            }
        }
    }

    /**
     * Generate orders for a specific month range (returns array instead of creating)
     */
    private function generateOrdersForYear($users, $products, $createdCustomers, $year, $startMonth, $startDay, $endMonth, $endDay, $minOrders, $maxOrders, $statuses)
    {
        $orders = [];
        $startDate = Carbon::create($year, $startMonth, $startDay, 0, 0, 0);
        $endDate = Carbon::create($year, $endMonth, $endDay, 23, 59, 59);
        $daysInRange = $startDate->diffInDays($endDate);
        
        // Filter out admin users - only create orders for customers
        $customerUsers = $users->filter(function($user) {
            return $user->role !== 'admin';
        })->take(5);
        
        // Generate orders per user
        foreach ($customerUsers as $user) {
            $orderCount = rand($minOrders, $maxOrders);
            
            for ($i = 0; $i < $orderCount; $i++) {
                // Random date within the range
                $randomDays = ($daysInRange > 0) ? rand(0, $daysInRange) : 0;
                $randomDate = $startDate->copy()->addDays($randomDays);
                
                $shippingFee = rand(250, 500);
                
                // Generate order items
                $items = [];
                $itemCount = rand(1, 3);
                $randomProducts = $products->random($itemCount);
                
                foreach ($randomProducts as $product) {
                    $items[] = [
                        'product_id' => $product->product_id,
                        'quantity' => rand(1, 3),
                        'unit_price' => $product->sell_price,
                    ];
                }
                
                $orders[] = [
                    'user_id' => $user->id,
                    'customer_id' => $createdCustomers->random()->customer_id,
                    'shipping_fee' => $shippingFee,
                    'status' => $statuses[array_rand($statuses)],
                    'order_date' => $randomDate,
                    'items' => $items,
                ];
            }
        }
        
        return $orders;
    }
}
