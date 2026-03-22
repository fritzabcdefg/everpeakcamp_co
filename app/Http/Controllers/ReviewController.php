<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Check if user has purchased the product
     */
    protected function hasPurchasedProduct($userId, $productId)
    {
        return Order::whereHas('orderItems', function ($query) use ($productId) {
            $query->where('product_id', $productId);
        })
        ->where('user_id', $userId)
        ->exists();
    }

    /**
     * Show all reviews with datatable
     */
    public function index(Request $request)
    {
        // Admin only
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Unauthorized access.');
        }

        $reviews = Review::with(['user', 'product'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('reviews.index', ['reviews' => $reviews]);
    }

    /**
     * Store a new review
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return back()->withErrors(['error' => 'Please log in to post a review.']);
        }

        $validated = $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $productId = $validated['product_id'];
        $userId = Auth::id();

        // Check if user has purchased this product
        if (!$this->hasPurchasedProduct($userId, $productId)) {
            return back()->withErrors(['error' => 'You can only review products you have purchased.']);
        }

        // Check if user already has a review for this product
        $existingReview = Review::where('product_id', $productId)
            ->where('user_id', $userId)
            ->first();

        if ($existingReview) {
            return back()->withErrors(['error' => 'You have already reviewed this product. Use the update option to modify your review.']);
        }

        // Create new review
        Review::create([
            'product_id' => $productId,
            'user_id' => $userId,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return back()->with('success', 'Review posted successfully!');
    }

    /**
     * Update an existing review
     */
    public function update(Request $request, Review $review)
    {
        if (!Auth::check()) {
            return back()->withErrors(['error' => 'Please log in to update a review.']);
        }

        // Check ownership
        if ($review->user_id !== Auth::id()) {
            return back()->withErrors(['error' => 'You can only update your own reviews.']);
        }

        $validated = $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review->update([
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return back()->with('success', 'Review updated successfully!');
    }

    /**
     * Delete a review (Admin only)
     */
    public function destroy(Request $request, Review $review)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $review->delete();
        return redirect()->route('reviews.index')->with('success', 'Review deleted successfully.');
    }

    /**
     * Show review form for a specific product
     */
    public function create(Product $product)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to post a review.');
        }

        $userId = Auth::id();
        $hasPurchased = $this->hasPurchasedProduct($userId, $product->product_id);

        if (!$hasPurchased) {
            return redirect()->back()->with('error', 'You can only review products you have purchased.');
        }

        $existingReview = Review::where('product_id', $product->product_id)
            ->where('user_id', $userId)
            ->first();

        return view('reviews.form', [
            'product' => $product,
            'review' => $existingReview,
        ]);
    }

    /**
     * Get reviews for a specific product (API)
     */
    public function productReviews(Product $product)
    {
        $reviews = $product->reviews()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'reviews' => $reviews->map(function ($review) {
                return [
                    'review_id' => $review->review_id,
                    'user_name' => $review->user->name,
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                    'date' => $review->created_at->format('M d, Y'),
                    'is_current_user' => $review->user_id === Auth::id(),
                ];
            }),
        ]);
    }
}
