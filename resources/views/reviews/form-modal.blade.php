<!-- Review Modal Form -->
<div class="modal fade" id="reviewModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--primary-green-light) 0%, var(--accent-green) 100%); color: white;">
                <h5 class="modal-title">
                    <i class="fas fa-pen me-2"></i><span id="reviewModalTitle">Write a Review</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="reviewForm" method="POST" action="">
                @csrf
                <input type="hidden" id="reviewMethodInput" name="_method" value="">
                <input type="hidden" id="productIdInput" name="product_id" value="">

                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="form-label">Your Rating <span class="text-danger">*</span></label>
                        <div class="rating-stars" id="ratingStars">
                            <i class="fas fa-star star" data-rating="1" style="cursor: pointer; font-size: 1.5rem; color: #ddd; margin-right: 0.5rem;"></i>
                            <i class="fas fa-star star" data-rating="2" style="cursor: pointer; font-size: 1.5rem; color: #ddd; margin-right: 0.5rem;"></i>
                            <i class="fas fa-star star" data-rating="3" style="cursor: pointer; font-size: 1.5rem; color: #ddd; margin-right: 0.5rem;"></i>
                            <i class="fas fa-star star" data-rating="4" style="cursor: pointer; font-size: 1.5rem; color: #ddd; margin-right: 0.5rem;"></i>
                            <i class="fas fa-star star" data-rating="5" style="cursor: pointer; font-size: 1.5rem; color: #ddd; margin-right: 0.5rem;"></i>
                        </div>
                        <input type="hidden" id="ratingInput" name="rating" value="">
                        <div id="ratingError" class="invalid-feedback d-block"></div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="commentInput" class="form-label">Your Comment</label>
                        <textarea id="commentInput" class="form-control" name="comment" rows="4" 
                                  placeholder="Share your experience with this product..."></textarea>
                        <small class="text-muted d-block mt-1"><span id="charCount">0</span>/1000 characters</small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-2"></i><span id="submitBtnText">Post Review</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('#ratingStars .star');
    const ratingInput = document.getElementById('ratingInput');
    const commentInput = document.getElementById('commentInput');
    const charCount = document.getElementById('charCount');
    const reviewForm = document.getElementById('reviewForm');

    // Star rating functionality
    stars.forEach(star => {
        star.addEventListener('click', function() {
            const rating = this.dataset.rating;
            ratingInput.value = rating;

            stars.forEach((s, index) => {
                if (index < rating) {
                    s.style.color = '#FFD700';
                } else {
                    s.style.color = '#ddd';
                }
            });
        });

        star.addEventListener('mouseover', function() {
            const rating = this.dataset.rating;
            stars.forEach((s, index) => {
                if (index < rating) {
                    s.style.color = '#FFD700';
                } else {
                    s.style.color = '#ddd';
                }
            });
        });
    });

    document.getElementById('ratingStars').addEventListener('mouseout', function() {
        const currentRating = ratingInput.value || 0;
        stars.forEach((s, index) => {
            if (index < currentRating) {
                s.style.color = '#FFD700';
            } else {
                s.style.color = '#ddd';
            }
        });
    });

    // Character counter
    commentInput.addEventListener('input', function() {
        charCount.textContent = this.value.length;
    });

    // Form submission
    reviewForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const rating = ratingInput.value;
        if (!rating) {
            document.getElementById('ratingError').textContent = 'Please select a rating';
            return;
        }

        this.submit();
    });

    // Expose functions globally
    window.openReviewModal = function(productId, existingReview = null) {
        const form = document.getElementById('reviewForm');
        
        if (existingReview) {
            document.getElementById('reviewModalTitle').textContent = 'Update Your Review';
            document.getElementById('submitBtnText').textContent = 'Update Review';
            document.getElementById('reviewMethodInput').value = 'PUT';
            form.action = `/reviews/${existingReview.review_id}`;
            document.getElementById('ratingInput').value = existingReview.rating;
            document.getElementById('commentInput').value = existingReview.comment || '';
            charCount.textContent = (existingReview.comment || '').length;

            // Set star colors
            stars.forEach((s, index) => {
                if (index < existingReview.rating) {
                    s.style.color = '#FFD700';
                } else {
                    s.style.color = '#ddd';
                }
            });
        } else {
            document.getElementById('reviewModalTitle').textContent = 'Write a Review';
            document.getElementById('submitBtnText').textContent = 'Post Review';
            document.getElementById('reviewMethodInput').value = 'POST';
            form.action = '/reviews';
            document.getElementById('ratingInput').value = '';
            document.getElementById('commentInput').value = '';
            charCount.textContent = '0';

            stars.forEach(s => {
                s.style.color = '#ddd';
            });
        }

        document.getElementById('productIdInput').value = productId;
        document.getElementById('ratingError').textContent = '';
        new bootstrap.Modal(document.getElementById('reviewModal')).show();
    };
});
</script>

<style>
.star {
    transition: color 0.2s ease;
}

.rating-stars {
    display: flex;
    gap: 0;
}
</style>
