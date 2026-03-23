@extends('layouts.base')

@section('title', 'Manage Reviews - EverPeak Camp')

@section('content')
<div class="container my-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 style="color: var(--primary-green-light); font-weight: 600;">
                <i class="fas fa-star me-2"></i>Manage Customer Reviews
            </h2>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <strong>Error!</strong>
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-nature rounded-nature">
        <div class="card-body p-4">
            @if ($reviews->count() > 0)
                <table class="table table-striped table-hover">
                    <thead style="background-color: rgba(76, 175, 80, 0.1);">
                        <tr>
                            <th>Product</th>
                            <th>Customer</th>
                            <th>Rating</th>
                            <th>Comment</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reviews as $review)
                            <tr>
                                <td>
                                    <strong>{{ $review->product->name ?? 'N/A' }}</strong>
                                </td>
                                <td>{{ $review->user->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-warning text-dark">
                                        @for ($i = 0; $i < $review->rating; $i++)
                                            ⭐
                                        @endfor
                                        {{ $review->rating }}/5
                                    </span>
                                </td>
                                <td>
                                    <small>{{ Str::limit($review->comment ?? '', 50) }}</small>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $review->created_at->format('M d, Y') }}</small>
                                </td>
                                <td>
                                    <form action="{{ route('reviews.destroy', $review) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this review?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete Review">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $reviews->links() }}
                </div>
            @else
                <p class="text-center text-muted py-5">
                    <i class="fas fa-inbox fa-3x mb-3"></i><br>
                    No reviews found.
                </p>
            @endif
        </div>
    </div>
</div>
@endsection
