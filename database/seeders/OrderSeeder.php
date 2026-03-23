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

        // Create customers based on user data from UserSeeder
        $customers = [
            ['name' => 'Lorraine', 'email' => 'lorrainefrancesdesagun19@gmail.com', 'phone' => '555-0001', 'address' => 'Admin Office - Denver, CO'],
            ['name' => 'Fritzie', 'email' => 'fritziecadao@gmail.com', 'phone' => '555-0002', 'address' => 'Admin Office - Denver, CO'],
            ['name' => 'Raymund Turallo', 'email' => 'raymund@gmail.com', 'phone' => null, 'address' => null],
            ['name' => 'Elijah Gallardo', 'email' => 'elijah@gmail.com', 'phone' => null, 'address' => null],
            ['name' => 'Francis Balbin', 'email' => 'francis@gmail.com', 'phone' => null, 'address' => null],
            ['name' => 'Donn Torres', 'email' => 'donn@gmail.com', 'phone' => null, 'address' => null],
        ];

        $createdCustomers = collect();
        foreach ($customers as $customer) {
            $createdCustomers->push(Customer::updateOrCreate(
                ['email' => $customer['email']],
                $customer
            ));
        }

        // Create orders - exclude admin users
        $nonAdminUsers = $users->where('role', '!=', 'admin');
        foreach ($nonAdminUsers->take(4) as $user) {
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
