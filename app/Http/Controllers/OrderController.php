<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\CartItem;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $userId = $request->user()?->id ?? auth()->id();
        $cartItems = CartItem::where('user_id', $userId)->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        // default flat shipping fee (can be adjusted in UI)
        $shippingFee = 5.00;

        // try to find a customer record by user email
        $customer = null;
        if ($request->user()?->email) {
            $customer = \App\Models\Customer::where('email', $request->user()->email)->first();
        }

        return view('checkout.index', [
            'cartItems' => $cartItems,
            'shippingFee' => $shippingFee,
            'customer' => $customer,
        ]);
    }

    /**
     * Process checkout from customer's cart: create order + order items and clear cart.
     */
    public function checkout(Request $request)
    {
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
                'status' => 'processing',
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
                \Illuminate\Support\Facades\Mail::to($validated['email'])->send(new \App\Mail\OrderPlaced($order));
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

        $order->update($validated);
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
