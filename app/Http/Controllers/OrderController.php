<?php

namespace App\Http\Controllers;

use App\Mail\OrderPlaced;
use App\Mail\OrderStatusUpdated;
use App\Models\Order;
use App\Models\CartItem;
use App\Models\OrderItem;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Admins see all orders, customers see only their own
        if ($user && $user->role === 'admin') {
            $orders = Order::with('orderItems.product', 'customer')
                ->orderBy('order_date', 'desc')
                ->paginate(15);
        } else {
            $userId = $request->user()?->id ?? auth()->id();
            $orders = Order::where('user_id', $userId)
                ->with('orderItems.product', 'customer')
                ->orderBy('order_date', 'desc')
                ->paginate(15);
        }
        
        return view('orders.index', ['orders' => $orders]);
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
        $user = auth()->user();
        
        // Prevent admins from accessing checkout
        if ($user && $user->role === 'admin') {
            return redirect()->route('home')->with('error', 'Admins cannot create orders.');
        }

        $userId = $request->user()?->id ?? auth()->id();
        $cartItems = CartItem::where('user_id', $userId)->with('product')->get();

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
        $user = auth()->user();
        
        // Prevent admins from creating orders
        if ($user && $user->role === 'admin') {
            return redirect()->route('home')->with('error', 'Admins cannot create orders.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:50',
            'address' => 'required|string',
            'shipping_fee' => 'required|numeric|min:0',
        ]);

        $userId = $request->user()?->id ?? auth()->id();
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

            // Queue confirmation email for faster checkout
            try {
                Mail::to($validated['email'])->queue(new OrderPlaced($order));
                \Log::info("Order confirmation email queued", [
                    'order_id' => $order->order_id,
                    'customer_email' => $validated['email']
                ]);
            } catch (\Exception $mailEx) {
                \Log::error("Failed to queue order confirmation email", [
                    'order_id' => $order->order_id,
                    'error' => $mailEx->getMessage()
                ]);
                // Don't fail the order for email issues
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
     * Show the review prompt for a completed order.
     */
    public function review(Order $order)
    {
        $user = auth()->user();

        // Prevent admins from reviewing orders
        if (!$user || $user->role === 'admin' || $order->user_id !== $user->id) {
            abort(403);
        }

        if ($order->status !== 'completed') {
            return redirect()->route('orders.index')->with('error', 'Reviews are available only for completed orders.');
        }

        $order->load('orderItems.product');

        $orderItemProductIds = $order->orderItems->pluck('product_id');
        $reviewedProductIds = Review::where('user_id', $user->id)
            ->whereIn('product_id', $orderItemProductIds)
            ->pluck('product_id')
            ->toArray();

        return view('orders.review', [
            'order' => $order,
            'orderItems' => $order->orderItems,
            'reviewedProductIds' => $reviewedProductIds,
        ]);
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
                // Log successful email send
                \Log::info("Order status update email sent", [
                    'order_id' => $order->order_id,
                    'new_status' => $validated['status'],
                    'customer_email' => $order->customer->email
                ]);
            } catch (\Exception $mailEx) {
                // Log email failure but don't fail the update
                \Log::error("Failed to send order status update email", [
                    'order_id' => $order->order_id,
                    'error' => $mailEx->getMessage()
                ]);
            }
        }

        return redirect()->route('orders.show', $order)->with('success', 'Order updated successfully');
    }

    /**
     * Download receipt as PDF.
     */
    public function downloadReceipt(Order $order)
    {
        $user = auth()->user();

        // Ensure user has permission to download this receipt (owner or admin)
        if ($user->role !== 'admin' && $order->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $order->load('orderItems.product', 'customer');
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('emails.receipt_pdf', [
            'order' => $order,
        ]);

        return $pdf->download('receipt-order-' . $order->order_id . '.pdf');
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
