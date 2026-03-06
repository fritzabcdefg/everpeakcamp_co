<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
        ];

        $createdCustomers = collect();
        foreach ($customers as $customer) {
            $createdCustomers->push(Customer::create($customer));
        }

        // Create orders
        foreach ($users->take(5) as $user) {
            for ($i = 0; $i < rand(2, 4); $i++) {
                // create order with random shipping fee and compute items later
                $shippingFee = rand(250, 500); // PHP currency

                $order = Order::create([
                    'user_id' => $user->id,
                    'customer_id' => $createdCustomers->random()->customer_id,
                    'shipping_fee' => $shippingFee,
                    'status' => collect(['pending', 'processing', 'completed', 'cancelled'])->random(),
                    'order_date' => now()->subDays(rand(1, 60)),
                ]);

                // Add random products to order
                $randomProducts = $products->random(rand(1, 3));
                foreach ($randomProducts as $product) {
                    OrderItem::create([
                        'order_id' => $order->order_id,
                        'product_id' => $product->product_id,
                        'quantity' => rand(1, 3),
                        'unit_price' => $product->sell_price,
                    ]);
                }
            }
        }
    }
}
