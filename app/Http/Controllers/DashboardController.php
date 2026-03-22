<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display dashboard statistics and charts.
     */
    public function index(Request $request)
    {
        $startDate = $request->get('start_date', now()->subMonths(12)->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->toDateString());

        // Calculate totals for the date range
        $totalRevenue = Order::with('orderItems')
            ->whereBetween('order_date', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->get()
            ->reduce(function ($carry, $o) {
                $subtotal = $o->orderItems->sum(fn($it) => $it->quantity * $it->unit_price);
                return $carry + $subtotal + ($o->shipping_fee ?? 0);
            }, 0);

        // Monthly sales data for bar chart
        $monthlySales = Order::selectRaw('DATE_TRUNC(\'month\', order_date) as month, SUM(CAST(shipping_fee AS NUMERIC)) as monthly_total')
            ->with('orderItems')
            ->whereBetween('order_date', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->groupByRaw('DATE_TRUNC(\'month\', order_date)')
            ->orderBy('month')
            ->get()
            ->map(function($item) {
                $orderTotal = Order::whereBetween('order_date', [
                    $item->month, 
                    \Carbon\Carbon::parse($item->month)->endOfMonth()
                ])
                ->where('status', '!=', 'cancelled')
                ->with('orderItems')
                ->get()
                ->reduce(function ($carry, $o) {
                    $subtotal = $o->orderItems->sum(fn($it) => $it->quantity * $it->unit_price);
                    return $carry + $subtotal + ($o->shipping_fee ?? 0);
                }, 0);
                
                return [
                    'month' => \Carbon\Carbon::parse($item->month)->format('M Y'),
                    'total' => $orderTotal
                ];
            });

        // Ensure chart has at least the current month
        if ($monthlySales->isEmpty()) {
            $monthlySales = collect([
                ['month' => now()->format('M Y'), 'total' => 0]
            ]);
        }

        // Product sales breakdown for pie chart
        $productSales = OrderItem::selectRaw('product_id, SUM(quantity * unit_price) as total_sales, COUNT(*) as order_count')
            ->whereHas('order', function($q) use ($startDate, $endDate) {
                $q->whereBetween('order_date', [$startDate, $endDate])
                  ->where('status', '!=', 'cancelled');
            })
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_sales')
            ->limit(5)
            ->get()
            ->map(function($item) {
                return [
                    'product_name' => $item->product->name ?? 'Unknown',
                    'total_sales' => $item->total_sales,
                    'order_count' => $item->order_count
                ];
            });

        // Overall statistics
        $stats = [
            'total_users' => User::count(),
            'total_products' => Product::count(),
            'total_categories' => Category::count(),
            'total_orders' => Order::whereBetween('order_date', [$startDate, $endDate])->count(),
            'total_revenue' => $totalRevenue,
            'pending_orders' => Order::whereBetween('order_date', [$startDate, $endDate])->where('status', 'pending')->count(),
            'completed_orders' => Order::whereBetween('order_date', [$startDate, $endDate])->where('status', 'completed')->count(),
        ];

        return view('dashboard', [
            'stats' => $stats,
            'monthlySales' => $monthlySales,
            'productSales' => $productSales,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }
}
