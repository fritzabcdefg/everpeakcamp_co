<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display dashboard statistics and charts.
     */
    public function index(Request $request)
    {
        // Stats date range (for revenue and pending orders)
        $startDate = $request->get('start_date', now()->subMonths(12)->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->toDateString());

        // Separate date ranges for each chart
        // Daily sales date range
        $dailyStartDate = $request->get('daily_start_date', '2024-01-01');
        $dailyEndDate = $request->get('daily_end_date', now()->toDateString());

        // Yearly sales date range
        $yearlyStartDate = $request->get('yearly_start_date', '2024-01-01');
        $yearlyEndDate = $request->get('yearly_end_date', now()->toDateString());

        // Product sales date range
        $productStartDate = $request->get('product_start_date', '2024-01-01');
        $productEndDate = $request->get('product_end_date', now()->toDateString());

        // Calculate total revenue (all time, no date filter)
        $totalRevenue = Order::with('orderItems')
            ->where('status', '!=', 'cancelled')
            ->get()
            ->reduce(function ($carry, $o) {
                $subtotal = $o->orderItems->sum(fn($it) => $it->quantity * $it->unit_price);
                return $carry + $subtotal + ($o->shipping_fee ?? 0);
            }, 0);

        // Yearly sales data for bar chart (using separate yearly date range)
        $yearlySales = Order::with('orderItems')
            ->whereBetween('order_date', [$yearlyStartDate, $yearlyEndDate])
            ->where('status', '!=', 'cancelled')
            ->get()
            ->groupBy(function($order) {
                return $order->order_date->year;
            })
            ->map(function($yearOrders, $year) {
                $total = $yearOrders->reduce(function ($carry, $o) {
                    $subtotal = $o->orderItems->sum(fn($it) => $it->quantity * $it->unit_price);
                    return $carry + $subtotal + ($o->shipping_fee ?? 0);
                }, 0);
                
                return [
                    'year' => (string)$year,
                    'total' => round($total, 2)
                ];
            })
            ->sortBy('year')
            ->values();

        // Daily sales data for custom date range (using separate daily date range)
        $dailySales = collect();
        
        $startDateObj = Carbon::parse($dailyStartDate);
        $endDateObj = Carbon::parse($dailyEndDate);
        
        for ($date = $startDateObj; $date->lte($endDateObj); $date->addDay()) {
            $orders = Order::whereDate('order_date', $date->toDateString())
                ->where('status', '!=', 'cancelled')
                ->with('orderItems')
                ->get();
            
            $total = $orders->reduce(function ($carry, $o) {
                $subtotal = $o->orderItems->sum(fn($it) => $it->quantity * $it->unit_price);
                return $carry + $subtotal + ($o->shipping_fee ?? 0);
            }, 0);

            // Only add days with sales data to reduce chart clutter
            if ($total > 0) {
                $dailySales->push([
                    'date' => $date->format('M d'),
                    'total' => round($total, 2)
                ]);
            }
        }

        // Ensure chart has data
        if ($dailySales->isEmpty()) {
            $dailySales = collect([
                ['date' => now()->format('M d'), 'total' => 0]
            ]);
        }

        // Ensure chart has at least the current year
        if ($yearlySales->isEmpty()) {
            $yearlySales = collect([
                ['year' => now()->format('Y'), 'total' => 0]
            ]);
        }

        // Product sales percentage for pie chart
        $productSales = OrderItem::with('product')
            ->whereHas('order', function($q) use ($productStartDate, $productEndDate) {
                $q->whereBetween('order_date', [$productStartDate, $productEndDate])
                  ->where('status', '!=', 'cancelled');
            })
            ->get()
            ->groupBy('product_id')
            ->map(function($items) {
                $totalSales = $items->sum(fn($it) => $it->quantity * $it->unit_price);
                $productName = $items->first()->product->name ?? 'Unknown';
                return [
                    'product_name' => $productName,
                    'total_sales' => $totalSales
                ];
            })
            ->sortByDesc(fn($item) => $item['total_sales'])
            ->values();

        // Calculate total sales for percentage calculation
        $totalSalesAmount = $productSales->sum('total_sales');

        $productSales = $productSales->map(function($item) use ($totalSalesAmount) {
            $percentage = $totalSalesAmount > 0 ? ($item['total_sales'] / $totalSalesAmount) * 100 : 0;
            return [
                'product_name' => $item['product_name'],
                'total_sales' => $item['total_sales'],
                'percentage' => round($percentage, 1)
            ];
        });

        // Overall statistics
        $stats = [
            'total_users' => User::count(),
            'total_products' => Product::count(),
            'total_categories' => Category::count(),
            'total_orders' => Order::count(),
            'total_revenue' => $totalRevenue,
            'pending_orders' => Order::where('status', 'pending')->count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
        ];

        return view('dashboard', [
            'stats' => $stats,
            'yearlySales' => $yearlySales,
            'dailySales' => $dailySales,
            'productSales' => $productSales,
            'dailyStartDate' => $dailyStartDate,
            'dailyEndDate' => $dailyEndDate,
            'yearlyStartDate' => $yearlyStartDate,
            'yearlyEndDate' => $yearlyEndDate,
            'productStartDate' => $productStartDate,
            'productEndDate' => $productEndDate,
            // Keep these for backward compatibility
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }
}
