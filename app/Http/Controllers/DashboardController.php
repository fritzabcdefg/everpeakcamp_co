<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display dashboard statistics.
     */
    public function index()
    {
        $totalRevenue = Order::with('orderItems')->get()->reduce(function ($carry, $o) {
            $subtotal = $o->orderItems->sum(fn($it) => $it->quantity * $it->unit_price);
            return $carry + $subtotal + ($o->shipping_fee ?? 0);
        }, 0);

        $stats = [
            'total_users' => User::count(),
            'total_products' => Product::count(),
            'total_categories' => Category::count(),
            'total_orders' => Order::count(),
            'total_revenue' => $totalRevenue,
            'pending_orders' => Order::where('status', 'pending')->count(),
        ];

        return view('dashboard', ['stats' => $stats]);
    }
}
