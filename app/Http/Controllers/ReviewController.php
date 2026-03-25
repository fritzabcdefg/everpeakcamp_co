<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ReviewController extends Controller
{
    /**
     * Check if user has purchased and received the product (order completed)
     */
    protected function hasPurchasedProduct($userId, $productId)
    {
        return Order::whereHas('orderItems', function ($query) use ($productId) {
            $query->where('product_id', $productId);
        })
        ->where('user_id', $userId)
        ->where('status', 'completed')
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

        return view('reviews.index');
    }

    /**
     * Get reviews data for DataTables (API endpoint)
     */
    public function datatable(Request $request)
    {
        // Admin only
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $query = Review::with(['user', 'product']);

        return DataTables::of($query)
            ->addColumn('product', function ($review) {
                return $review->product?->name ?? 'N/A';
            })
            ->addColumn('customer', function ($review) {
                return $review->user?->name ?? 'N/A';
            })
            ->addColumn('rating', function ($review) {
                $stars = str_repeat('⭐', $review->rating) . ' ' . $review->rating . '/5';
                return '<span class="badge bg-warning text-dark">' . $stars . '</span>';
            })
            ->addColumn('comment', function ($review) {
                return '<small>' . \Illuminate\Support\Str::limit($review->comment ?? '', 50) . '</small>';
            })
            ->addColumn('date', function ($review) {
                return $review->created_at->format('M d, Y');
            })
            ->addColumn('actions', function ($review) {
                $actions = '<form action="' . route('reviews.destroy', $review) . '" method="POST" style="display:inline;">';
                $actions .= '<input type="hidden" name="_method" value="DELETE">';
                $actions .= '<input type="hidden" name="_token" value="' . csrf_token() . '">';
                $actions .= '<button type="submit" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm(\'Are you sure?\')"><i class="fas fa-trash"></i> Delete</button>';
                $actions .= '</form>';
                return $actions;
            })
            ->filterColumn('product', function ($query, $keyword) {
                $query->whereHas('product', function($pq) use ($keyword) {
                    $pq->where('name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('customer', function ($query, $keyword) {
                $query->whereHas('user', function($uq) use ($keyword) {
                    $uq->where('name', 'like', "%{$keyword}%")
                       ->orWhere('email', 'like', "%{$keyword}%");
                })
                ->orWhere('comment', 'like', "%{$keyword}%");
            })
            ->orderBy('created_at', 'desc')
            ->rawColumns(['rating', 'comment', 'actions'])
            ->make(true);
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
            return back()->withErrors(['error' => 'You can only review products you have received (order completed).']);
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
