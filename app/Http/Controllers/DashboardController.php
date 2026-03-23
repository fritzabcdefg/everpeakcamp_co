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

        // Yearly sales data for bar chart
        $yearlySales = Order::selectRaw('YEAR(order_date) as year, SUM(shipping_fee) as shipping_total')
            ->with('orderItems')
            ->whereBetween('order_date', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->groupByRaw('YEAR(order_date)')
            ->orderBy('year')
            ->get()
            ->map(function($item) use ($startDate, $endDate) {
                // Calculate product sales for this year
                $productTotal = Order::whereYear('order_date', $item->year)
                    ->whereBetween('order_date', [$startDate, $endDate])
                    ->where('status', '!=', 'cancelled')
                    ->with('orderItems')
                    ->get()
                    ->reduce(function ($carry, $o) {
                        $subtotal = $o->orderItems->sum(fn($it) => $it->quantity * $it->unit_price);
                        return $carry + $subtotal;
                    }, 0);

                return [
                    'year' => (string)$item->year,
                    'total' => $productTotal + $item->shipping_total
                ];
            });

        // Ensure chart has at least the current year
        if ($yearlySales->isEmpty()) {
            $yearlySales = collect([
                ['year' => now()->format('Y'), 'total' => 0]
            ]);
        }

        // Product sales percentage for pie chart (all products, not just top 5)
        $productSales = OrderItem::selectRaw('product_id, SUM(quantity * unit_price) as total_sales')
            ->whereHas('order', function($q) use ($startDate, $endDate) {
                $q->whereBetween('order_date', [$startDate, $endDate])
                  ->where('status', '!=', 'cancelled');
            })
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_sales')
            ->get();

        // Calculate total sales for percentage calculation
        $totalSalesAmount = $productSales->sum('total_sales');

        $productSales = $productSales->map(function($item) use ($totalSalesAmount) {
            $percentage = $totalSalesAmount > 0 ? ($item->total_sales / $totalSalesAmount) * 100 : 0;
            return [
                'product_name' => $item->product->name ?? 'Unknown',
                'total_sales' => $item->total_sales,
                'percentage' => round($percentage, 1)
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
            'yearlySales' => $yearlySales,
            'productSales' => $productSales,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }
}
