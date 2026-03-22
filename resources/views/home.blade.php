@extends('layouts.base')

@section('title', 'EverPeak Camp Co. - Premium Outdoor & Camping Gear')

@section('content')
<!-- Hero Section -->
<div class="hero-section">
    <div class="container">
        <h1 class="title animate-slide-in-up" style="font-size: 3.5rem;">
            <i class="fas fa-campfire me-2" style="color: var(--cream);"></i>EverPeak Camp Co.
        </h1>
        <p class="lead animate-slide-in-up">Your trusted source for premium outdoor & camping gear</p>
    </div>
</div>

<!-- Products Section -->
<div class="container my-5">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form action="{{ url()->current() }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-6">
                    <label for="search" class="form-label fw-semibold mb-1">Search products</label>
                    <input
                        type="text"
                        name="search"
                        id="search"
                        class="form-control"
                        placeholder="Search by product name or description"
                        value="{{ $search ?? request('search') }}"
                    >
                </div>
                <div class="col-md-6 d-flex gap-2 align-self-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1"></i> Search
                    </button>
                    @if (!empty($search))
                        <a href="{{ url()->current() }}" class="btn btn-outline-secondary">Clear</a>
                    @endif
                </div>
            </form>

            @if (!empty($search))
                <p class="text-muted mb-0 mt-3">
                    Showing results for: <strong>{{ $search }}</strong>
                </p>
            @endif
        </div>
    </div>

    @if ($products->count() > 0)
        <div class="product-grid">
            @foreach ($products as $product)
                <div class="product-card animate-fade-in">
                    <!-- Product Image -->
                    @if ($product->img_path)
                        <div class="product-image-container">
                            <img src="{{ Storage::url($product->img_path) }}" alt="{{ $product->name }}">
                            @php
                                $stockQuantity = $product->stock->sum('quantity');
                            @endphp
                            @if ($stockQuantity > 0 && ($product->cost_price < $product->sell_price))
                                <span class="product-badge">On Sale</span>
                            @endif
                        </div>
                    @else
                        <div class="product-image-container">
                            <i class="fas fa-image fa-3x" style="color: #ddd;"></i>
                        </div>
                    @endif

                    <!-- Product Info -->
                    <div class="product-info">
                        @if ($product->category)
                            <div class="product-category">{{ $product->category->name }}</div>
                        @endif
                        <h5 class="product-name">{{ $product->name }}</h5>
                        <p class="product-description">{{ \Illuminate\Support\Str::limit($product->description, 80) }}</p>

                        <div class="product-price">
                            <span class="price-current">₱{{ number_format($product->sell_price, 2) }}</span>
                        </div>

                        @php
                            $stockQuantity = $product->stock->sum('quantity');
                        @endphp

                        @if ($stockQuantity > 0)
                            <div class="mb-3" style="padding: 0.6rem 0.8rem; background-color: rgba(92, 184, 92, 0.1); border-radius: 6px; text-align: center;">
                                <span style="color: var(--accent-green); font-weight: 600; font-size: 0.9rem;">
                                    <i class="fas fa-check-circle me-1"></i> {{ $stockQuantity }} in stock
                                </span>
                            </div>
                        @else
                            <div class="mb-3" style="padding: 0.6rem 0.8rem; background-color: rgba(217, 83, 79, 0.1); border-radius: 6px; text-align: center;">
                                <span style="color: var(--danger); font-weight: 600; font-size: 0.9rem;">
                                    <i class="fas fa-times-circle me-1"></i> Out of Stock
                                </span>
                            </div>
                        @endif

                        <!-- Actions -->
                        <div class="d-flex flex-column gap-2">
                            <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye me-1"></i> View Details
                            </a>
                            @if (Auth::check() && $stockQuantity > 0)
                                <form action="{{ route('cart.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-sm btn-success w-100">
                                        <i class="fas fa-shopping-cart me-1"></i> Add to Cart
                                    </button>
                                </form>
                            @elseif (!Auth::check())
                                <a href="{{ route('login') }}" class="btn btn-sm btn-success">
                                    <i class="fas fa-shopping-cart me-1"></i> Add to Cart
                                </a>
                            @else
                                <button class="btn btn-sm btn-secondary w-100" disabled>
                                    <i class="fas fa-shopping-cart me-1"></i> Out of Stock
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if ($products->hasPages())
            <div class="d-flex justify-content-center mt-5">
                {{ $products->links() }}
            </div>
        @endif
    @else
        <div style="text-align: center; padding: 80px 20px;">
            <i class="fas fa-inbox" style="font-size: 4rem; color: #ddd; margin-bottom: 20px; display: block;"></i>
            <h3 style="color: var(--primary-green-dark); font-weight: 600;">
                {{ !empty($search) ? 'No Matching Products Found' : 'No Products Available' }}
            </h3>
            <p style="color: var(--gray-text);">
                {{ !empty($search) ? 'Try a different keyword and search again.' : 'Check back soon for amazing outdoor & camping gear!' }}
            </p>
        </div>
    @endif
</div>

@endsection
