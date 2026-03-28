<?php

namespace App\Http\Controllers;

use App\Mail\OrderPlaced;
use App\Mail\OrderStatusUpdated;
use App\Models\Order;
use App\Models\CartItem;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userId = $request->user()?->id ?? auth()->id();
        $orders = Order::where('user_id', $userId)
            ->with('orderItems.product')
            ->paginate(15);
        return view('orders.index', ['orders' => $orders]);
    }

    /**
     * Get orders data for DataTables (API endpoint)
     */
    public function datatable(Request $request)
    {
        try {
            \Log::info('OrderController::datatable called - User: ' . (auth()->check() ? auth()->user()->id : 'NOT_AUTHENTICATED'));
            
            if (!auth()->check()) {
                \Log::warning('Unauthorized datatable access - user not authenticated');
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            
            if (auth()->user()->role !== 'admin') {
                \Log::warning('Unauthorized datatable access - user not admin');
                return response()->json(['error' => 'Forbidden'], 403);
            }

            $query = Order::with('orderItems.product', 'user')->orderBy('order_date', 'desc');

            return DataTables::of($query)
                ->addColumn('order_id', function ($order) {
                    return '#' . $order->order_id;
                })
                ->addColumn('order_date', function ($order) {
                    return $order->order_date->format('M d, Y');
                })
                ->addColumn('customer_name', function ($order) {
                    return ($order->user ? $order->user->first_name . ' ' . $order->user->last_name : 'N/A');
                })
                ->addColumn('total_amount', function ($order) {
                    $total = $order->orderItems->sum(fn($item) => $item->quantity * $item->unit_price) + ($order->shipping_fee ?? 0);
                    return '₱' . number_format($total, 2);
                })
                ->addColumn('item_count', function ($order) {
                    return $order->orderItems->count() . ' items';
                })
                ->addColumn('status', function ($order) {
                    $statusColors = [
                        'pending' => 'warning text-dark',
                        'processing' => 'info',
                        'completed' => 'success',
                        'cancelled' => 'danger'
                    ];
                    $color = $statusColors[$order->status] ?? 'secondary';
                    return '<span class="badge bg-' . $color . '">' . ucfirst($order->status) . '</span>';
                })
                ->addColumn('actions', function ($order) {
                    return '<a href="' . route('orders.show', $order) . '" class="btn btn-sm btn-info"><i class="fas fa-eye"></i> View</a>';
                })
                ->rawColumns(['status', 'actions'])
                ->make(true);
        } catch (\Exception $e) {
            \Log::error('DataTable error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['error' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('orders.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,customer_id',
            'shipping_fee' => 'required|numeric|min:0',
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $userId = $request->user()?->id ?? auth()->id();
        $validated['user_id'] = $userId;
        $validated['order_date'] = now();

        Order::create($validated);
        return redirect()->route('orders.index')->with('success', 'Order created successfully');
    }

    /**
     * Show checkout confirmation page with order summary and shipping address.
     */
    public function checkoutForm(Request $request)
    {
        // Require authentication
        if (!auth()->check()) {
            return redirect()->route('login')->with('info', 'Please log in to proceed with checkout');
        }

        // Prevent admins from accessing checkout form
        if (auth()->user()->role === 'admin') {
            return redirect()->route('cart.index')->with('error', 'Admins cannot place orders');
        }

        $cartItems = CartItem::where('user_id', auth()->id())->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        // Get authenticated user's profile data
        $user = auth()->user();

        // Check if user has completed their profile
        if (empty($user->phone) || empty($user->address)) {
            return redirect()->route('profile.create')
                         ->with('error', 'Please complete your profile (phone and address) before checkout.');
        }

        // default flat shipping fee (can be adjusted in UI)
        $shippingFee = 5.00;

        return view('checkout.index', [
            'cartItems' => $cartItems,
            'shippingFee' => $shippingFee,
            'user' => $user,
        ]);
    }

    /**
     * Process checkout from customer's cart: create order + order items and clear cart.
     */
    public function checkout(Request $request)
    {
        // Require authentication
        if (!auth()->check()) {
            return redirect()->route('login')->with('info', 'Please log in to proceed with checkout');
        }

        // Prevent admins from placing orders
        if (auth()->user()->role === 'admin') {
            return redirect()->route('cart.index')->with('error', 'Admins cannot place orders');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:50',
            'address' => 'required|string',
            'shipping_fee' => 'required|numeric|min:0',
        ]);

        $userId = auth()->id();
        $cartItems = CartItem::where('user_id', $userId)->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        DB::beginTransaction();
        try {
            // create or update customer record by email
            $customer = \App\Models\Customer::updateOrCreate(
                ['email' => $validated['email']],
                ['name' => $validated['name'], 'phone' => $validated['phone'] ?? null, 'address' => $validated['address']]
            );

            $order = Order::create([
                'user_id' => $userId,
                'customer_id' => $customer->customer_id,
                'shipping_fee' => $validated['shipping_fee'],
                'status' => 'pending',
                'order_date' => now(),
            ]);

            foreach ($cartItems as $ci) {
                OrderItem::create([
                    'order_id' => $order->order_id,
                    'product_id' => $ci->product_id,
                    'quantity' => $ci->quantity,
                    'unit_price' => $ci->product->sell_price ?? 0,
                ]);
            }

            // clear cart
            CartItem::where('user_id', $userId)->delete();

            DB::commit();

            // send confirmation email (Mailtrap credentials should be in env; we send using configured mailer)
            try {
                Mail::to($validated['email'])->send(new OrderPlaced($order));
            } catch (\Exception $mailEx) {
                // swallow mail exceptions for now
            }

            return redirect()->route('orders.show', $order)->with('success', 'Order placed successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('cart.index')->with('error', 'Unable to process order: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load('user', 'customer', 'orderItems.product');
        return view('orders.show', ['order' => $order]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        return view('orders.edit', ['order' => $order]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,customer_id',
            'shipping_fee' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|in:pending,processing,completed,cancelled',
        ]);

        $previousStatus = $order->status;
        $order->update($validated);
        $order->load('customer', 'orderItems.product');

        if (
            array_key_exists('status', $validated) &&
            $validated['status'] !== $previousStatus &&
            !empty($order->customer?->email)
        ) {
            try {
                Mail::to($order->customer->email)->send(new OrderStatusUpdated($order));
            } catch (\Exception $mailEx) {
                // swallow mail exceptions for now
            }
        }

        return redirect()->route('orders.show', $order)->with('success', 'Order updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted successfully');
    }
}
