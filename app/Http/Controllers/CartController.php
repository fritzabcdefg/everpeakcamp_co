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
        // For authenticated users, show their database cart
        if (auth()->check()) {
            $cartItems = CartItem::where('user_id', auth()->id())
                ->with('product')
                ->paginate(15);
        } else {
            // For guests, show session-based cart
            $sessionCart = session()->get('guest_cart', []);
            $cartItems = collect($sessionCart)->map(function($item) {
                return (object)[
                    'cart_item_id' => $item['product_id'],
                    'product_id' => $item['product_id'],
                    'product' => Product::find($item['product_id']),
                    'quantity' => $item['quantity']
                ];
            });
            $cartItems = $cartItems->filter(fn($item) => $item->product !== null);
        }
        
        $categories = Category::all();
        return view('cart.index', ['cartItems' => $cartItems, 'categories' => $categories]);
    }

    /**
     * Add item to cart.
     */
    public function store(Request $request)
    {
        // Prevent admins from adding to cart
        if (auth()->check() && auth()->user()->role === 'admin') {
            return redirect()->back()->with('error', 'Admins cannot add items to cart');
        }

        $validated = $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'quantity' => 'required|integer|min:1',
        ]);

        // If authenticated, save to database
        if (auth()->check()) {
            $cartItem = CartItem::firstOrNew([
                'user_id' => auth()->id(),
                'product_id' => $validated['product_id'],
            ]);

            if ($cartItem->exists) {
                $cartItem->quantity += $validated['quantity'];
            } else {
                $cartItem->quantity = $validated['quantity'];
            }
            $cartItem->save();
        } else {
            // For guests, save to session
            $guestCart = session()->get('guest_cart', []);
            $productId = (string)$validated['product_id'];
            
            if (isset($guestCart[$productId])) {
                $guestCart[$productId]['quantity'] += $validated['quantity'];
            } else {
                $guestCart[$productId] = [
                    'product_id' => $productId,
                    'quantity' => $validated['quantity']
                ];
            }
            
            session()->put('guest_cart', $guestCart);
        }

        return redirect()->back()->with('success', 'Item added to cart');
    }

    /**
     * Update cart item quantity.
     */
    public function update(Request $request, $productId)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if (auth()->check()) {
            // Update authenticated user's cart
            $cartItem = CartItem::where('user_id', auth()->id())
                ->where('product_id', $productId)
                ->firstOrFail();
            $cartItem->update($validated);
        } else {
            // Update guest cart in session
            $guestCart = session()->get('guest_cart', []);
            $productId = (string)$productId;
            
            if (isset($guestCart[$productId])) {
                $guestCart[$productId]['quantity'] = $validated['quantity'];
                session()->put('guest_cart', $guestCart);
            }
        }

        return redirect()->route('cart.index')->with('success', 'Cart item updated');
    }

    /**
     * Remove item from cart.
     */
    public function destroy($productId)
    {
        if (auth()->check()) {
            // Delete from authenticated user's cart
            CartItem::where('user_id', auth()->id())
                ->where('product_id', $productId)
                ->delete();
        } else {
            // Delete from guest cart in session
            $guestCart = session()->get('guest_cart', []);
            $productId = (string)$productId;
            
            if (isset($guestCart[$productId])) {
                unset($guestCart[$productId]);
                session()->put('guest_cart', $guestCart);
            }
        }

        return redirect()->route('cart.index')->with('success', 'Item removed from cart');
    }

    /**
     * Clear all cart items for user.
     */
    public function clear(Request $request)
    {
        if (auth()->check()) {
            CartItem::where('user_id', auth()->id())->delete();
        } else {
            session()->forget('guest_cart');
        }
        
        return redirect()->route('cart.index')->with('success', 'Cart cleared');
    }
}
