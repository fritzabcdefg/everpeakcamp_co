<!-- Product Reviews Section -->
<div class="reviews-section mt-5 pt-4 border-top">
    <h3 style="color: var(--primary-green-light); font-weight: 600;" class="mb-4">
        <i class="fas fa-star me-2"></i>Customer Reviews
    </h3>

    @auth
        @if (auth()->user()->role === 'customer')
            @php
                // Check if user has received this product (completed order)
                $hasReceivedProduct = \App\Models\Order::whereHas('orderItems', function ($query) use ($product) {
                    $query->where('product_id', $product->product_id);
                })
                ->where('user_id', auth()->id())
                ->where('status', 'completed')
                ->exists();
            @endphp
            
            @if($hasReceivedProduct)
                <div class="mb-4">
                    <button class="btn btn-primary" onclick="openReviewModal({{ $product->product_id }}, @if($userReview) {!! json_encode($userReview) !!} @else null @endif)">
                        <i class="fas fa-pen me-2"></i>
                        @if($userReview)
                            Edit Your Review
                        @else
                            Write a Review
                        @endif
                    </button>
                </div>
            @else
                <div class="alert alert-info mb-4">
                    <i class="fas fa-info-circle me-2"></i>
                    You can only review products you have received them.
                </div>
            @endif
        @endif
    @else
        <div class="alert alert-info mb-4">
            <i class="fas fa-info-circle me-2"></i>
            <a href="{{ route('login') }}" style="color: var(--primary-green-light); font-weight: 600;">Please log in</a> 
            to post a review. You can only review products you have received.
        </div>
    @endauth

    <div id="reviewsContainer">
        @if($product->reviews->count() > 0)
            <div class="reviews-list">
                @foreach($product->reviews as $review)
                    <div class="review-item card mb-3" id="review-{{ $review->review_id }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="card-title mb-0" style="color: var(--primary-green-light);">{{ $review->user->name }}</h6>
                                    <small class="text-muted">{{ $review->created_at->format('M d, Y') }}</small>
                                </div>
                                <span class="badge bg-warning text-dark">
                                    @for($i = 0; $i < $review->rating; $i++)
                                        ⭐
                                    @endfor
                                    {{ $review->rating }}/5
                                </span>
                            </div>
                            <p class="card-text">{{ $review->comment ?? '<em class="text-muted">No comment provided</em>' }}</p>
                            @auth
                                @if($review->user_id === auth()->id())
                                    <button class="btn btn-sm btn-outline-primary" onclick="editReview({{ $review->review_id }}, {{ $review->rating }}, '{{ addslashes($review->comment) }}')">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                @endif
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-muted text-center">No reviews yet. Be the first to review this product!</p>
        @endif
    </div>
</div>

<!-- Review Modal (from form-modal.blade.php) -->
@include('reviews.form-modal')

<script>
function editReview(reviewId, rating, comment) {
    openReviewModal({{ $product->product_id }}, {
        review_id: reviewId,
        rating: rating,
        comment: comment
    });
}
</script>

<style>
.reviews-section {
    background-color: #f9fafb;
    padding: 2rem;
    border-radius: 0.5rem;
}

.review-item {
    border-left: 4px solid var(--primary-green-light);
    transition: transform 0.2s ease;
}

.review-item:hover {
    transform: translateX(5px);
}

.loading-reviews {
    padding: 3rem 0;
}
</style>
