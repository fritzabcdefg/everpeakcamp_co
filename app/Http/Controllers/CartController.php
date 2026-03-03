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

        // attempt to find existing cart item for this user/product
        $cartItem = CartItem::firstOrNew([
            'user_id' => $userId,
            'product_id' => $validated['product_id'],
        ]);

        // if exists increment, otherwise set initial quantity
        if ($cartItem->exists) {
            $cartItem->quantity += $validated['quantity'];
        } else {
            $cartItem->quantity = $validated['quantity'];
        }
        $cartItem->save();

        // redirect back instead of jumping to cart index so the user stays on the current page
        return redirect()->back()->with('success', 'Item added to cart');
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
