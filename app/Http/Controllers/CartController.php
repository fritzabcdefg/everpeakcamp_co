<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Get cart items for the authenticated user.
     */
    public function index(Request $request)
    {
        $userId = $request->user()?->id ?? auth()->id();
        $cartItems = CartItem::where('user_id', $userId)
            ->with('product')
            ->paginate(15);
        $categories = Category::all();
        return view('cart.index', ['cartItems' => $cartItems, 'categories' => $categories]);
    }

    /**
     * Add item to cart.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'quantity' => 'required|integer|min:1',
        ]);

        $userId = $request->user()?->id ?? auth()->id();

        CartItem::updateOrCreate(
            [
                'user_id' => $userId,
                'product_id' => $validated['product_id'],
            ],
            ['quantity' => $validated['quantity']]
        );

        return redirect()->route('cart.index')->with('success', 'Item added to cart');
    }

    /**
     * Update cart item quantity.
     */
    public function update(Request $request, CartItem $cartItem)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem->update($validated);
        return redirect()->route('cart.index')->with('success', 'Cart item updated');
    }

    /**
     * Remove item from cart.
     */
    public function destroy(CartItem $cartItem)
    {
        $cartItem->delete();
        return redirect()->route('cart.index')->with('success', 'Item removed from cart');
    }

    /**
     * Clear all cart items for user.
     */
    public function clear(Request $request)
    {
        $userId = $request->user()?->id ?? auth()->id();
        CartItem::where('user_id', $userId)->delete();
        return redirect()->route('cart.index')->with('success', 'Cart cleared');
    }
}
