@extends('layouts.base')

@section('title', 'Everpeak Camp Co - Premium Outdoor & Camping Gear')

@section('content')
<!-- Hero Section -->
<div class="hero-section">
    <div class="container">
        <h1 class="title animate-slide-in-up" style="font-size: 3.5rem; color: var(--cream);">
            <i class="fas fa-campfire me-2" style="color: var(--cream);"></i>Everpeak Camp Co
        </h1>
        <p class="lead animate-slide-in-up">Your trusted source for premium outdoor & camping gear</p>
    </div>
</div>

<!-- Featured Products Section -->
<div class="section">
    <div class="container">
        <div class="section-header">
            <h2><i class="fas fa-star me-2" style="color: var(--terracotta);"></i> 
                @if($searchMethod)
                    Search Results
                @else
                    Featured Products
                @endif
            </h2>
            <p class="section-subtitle">
                @if($searchMethod)
                    <span>Search Method: 
                        <span class="badge" style="background-color: var(--accent-green);">
                            @switch($searchMethod)
                                @case('like')
                                    <i class="fas fa-database me-1"></i> LIKE Query
                                    @break
                                @case('model')
                                    <i class="fas fa-sitemap me-1"></i> Model Search 
                                    @break
                                @case('scout')
                                    <i class="fas fa-search me-1"></i> Laravel Scout 
                                    @break
                            @endswitch
                        </span>
                    </span>
                @else
                    Discover our handpicked selection of premium outdoor gear
                @endif
            </p>
        </div>

        <!-- Search Form with Multiple Methods -->
        <div class="card" style="margin-bottom: 2rem; border: 2px solid var(--accent-green); background: rgba(173, 213, 168, 0.05);">
            <div class="card-body">
                <form method="GET" action="{{ route('home') }}" class="row g-3">
                    <!-- Search Input -->
                    <div class="col-md-5">
                        <label for="searchInput" class="form-label fw-bold">Search Products</label>
                        <input type="text" class="form-control" id="searchInput" name="search" 
                               value="{{ $search }}" placeholder="Search by name or description...">
                    </div>

                    <!-- Search Method Selection -->
                    <div class="col-md-4">
                        <label for="searchType" class="form-label fw-bold">Search Method</label>
                        <select class="form-select" id="searchType" name="search_type">
                            <option value="like" {{ ($searchMethod === 'like') ? 'selected' : '' }}>
                                LIKE Query Search
                            </option>
                            <option value="model" {{ ($searchMethod === 'model') ? 'selected' : '' }}>
                                Model Search Scope
                            </option>
                            <option value="scout" {{ ($searchMethod === 'scout') ? 'selected' : '' }}>
                                Laravel Scout 
                            </option>
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div class="col-md-3 d-flex gap-2 align-items-end">
                        <button type="submit" class="btn btn-success flex-grow-1">
                            <i class="fas fa-search me-1"></i> Search
                        </button>
                        @if($search)
                            <a href="{{ route('home') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> Clear
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Results Info -->
        @if($search)
            <div class="alert alert-info mb-3">
                <i class="fas fa-info-circle me-2"></i>
                <strong>{{ count($products->items()) }}</strong> result(s) found for <strong>"{{ $search }}"</strong>
                using <strong>{{ ucfirst($searchMethod) }} Search</strong>
            </div>
        @endif
        
        <div class="product-grid">
            @forelse($products as $product)
                <div class="product-card animate-fade-in">
                    <!-- Product Image -->
                    <div class="product-image-container">
                        @if($product->img_path)
                            <img src="{{ asset('storage/' . $product->img_path) }}" alt="{{ $product->name }}">
                        @else
                            <i class="fas fa-image fa-3x" style="color: #ddd;"></i>
                        @endif
                        @if($product->cost_price < $product->sell_price)
                            <span class="product-badge">Sale</span>
                        @endif
                    </div>

                    <div class="product-info">
                        <div class="product-category">{{ $product->category->name ?? 'Gear' }}</div>
                        <h5 class="product-name">{{ \Illuminate\Support\Str::limit($product->name, 30) }}</h5>
                        <p class="product-description">{{ \Illuminate\Support\Str::limit($product->description, 60) }}</p>

                        <div class="product-price">
                            <span class="price-current">₱{{ number_format($product->sell_price, 2) }}</span>
                            @if($product->cost_price < $product->sell_price)
                                <span class="price-original">₱{{ number_format($product->cost_price, 2) }}</span>
                            @endif
                        </div>

                        <div class="d-flex flex-column gap-2">
                            <a href="{{ route('products.show', $product->product_id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye me-1"></i> View Details
                            </a>

                                <form action="{{ route('cart.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-sm btn-success w-100">
                                        <i class="fas fa-cart-plus me-1"></i> Add to Cart
                                    </button>
                                </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-center text-muted" style="font-size: 1.1rem; padding: 3rem 0;">
                        <i class="fas fa-leaf me-2" style="color: var(--primary-green-light);"></i>
                        No products available yet. Check back soon!
                    </p>
                </div>
            @endforelse
        </div>

        @if(count($products) > 0)
            <div class="text-center mt-5">
                <a href="{{ route('shop.show') }}" class="btn btn-lg btn-primary">
                    <i class="fas fa-shopping-bag me-2"></i> View All Products
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Featured Categories Section -->
<div class="section" style="background: linear-gradient(135deg, rgba(173, 213, 168, 0.1) 0%, rgba(184, 245, 168, 0.1) 100%);">
    <div class="container">
        <div class="section-header">
            <h2><i class="fas fa-compass me-2" style="color: var(--primary-green-light);"></i> Shop by Category</h2>
            <p class="section-subtitle">Browse through our diverse collection of outdoor gear</p>
        </div>

        <div class="product-grid">
            @forelse($categories as $category)
                <a href="{{ route('shop.show') }}?category[]={{ $category->category_id }}" class="text-decoration-none">
                    <div class="card h-100 animate-fade-in" style="text-align: center; border-top: 4px solid var(--primary-green-light); padding: 2rem 1.5rem;">
                        <div>
                            <i class="fas fa-campground fa-3x mb-3" style="color: var(--accent-green);"></i>
                            <h5 class="card-title" style="color: var(--primary-green-dark);">{{ $category->name }}</h5>
                            <p class="card-text text-muted small">{{ $category->description ?? 'Explore our collection' }}</p>
                            <span class="badge badge-success">Explore Collection</span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-12">
                    <p class="text-center text-muted" style="font-size: 1.1rem; padding: 3rem 0;">
                        No categories available yet.
                    </p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Why Choose Us Section -->
<div class="section">
    <div class="container">
        <div class="section-header">
            <h2><i class="fas fa-check-circle me-2" style="color: var(--accent-green);"></i> Why Choose EverPeak?</h2>
            <p class="section-subtitle">Experience quality, reliability, and exceptional service</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4 col-sm-6 animate-fade-in">
                <div class="card h-100 text-center p-4" style="border-top: 4px solid var(--accent-green);">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">
                        <i class="fas fa-truck" style="color: var(--primary-green-light);"></i>
                    </div>
                    <h5 class="card-title">Lightning Fast Delivery</h5>
                    <p class="card-text">Quick and reliable shipping to your doorstep across the Philippines</p>
                </div>
            </div>

            <div class="col-md-4 col-sm-6 animate-fade-in">
                <div class="card h-100 text-center p-4" style="border-top: 4px solid var(--primary-green-light);">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">
                        <i class="fas fa-lock" style="color: var(--terracotta);"></i>
                    </div>
                    <h5 class="card-title">Secure Payment</h5>
                    <p class="card-text">Your transactions are protected with industry-leading security measures</p>
                </div>
            </div>

            <div class="col-md-4 col-sm-6 animate-fade-in">
                <div class="card h-100 text-center p-4" style="border-top: 4px solid var(--accent-green);">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">
                        <i class="fas fa-undo-alt" style="color: var(--primary-green-light);"></i>
                    </div>
                    <h5 class="card-title">Easy Returns</h5>
                    <p class="card-text">30-day money-back guarantee for your complete peace of mind</p>
                </div>
            </div>

            <div class="col-md-4 col-sm-6 animate-fade-in">
                <div class="card h-100 text-center p-4" style="border-top: 4px solid var(--terracotta);">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">
                        <i class="fas fa-star" style="color: var(--warm-brown);"></i>
                    </div>
                    <h5 class="card-title">Premium Quality</h5>
                    <p class="card-text">Carefully selected products from trusted brands worldwide</p>
                </div>
            </div>

            <div class="col-md-4 col-sm-6 animate-fade-in">
                <div class="card h-100 text-center p-4" style="border-top: 4px solid var(--primary-green-light);">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">
                        <i class="fas fa-headset" style="color: var(--accent-green);"></i>
                    </div>
                    <h5 class="card-title">Expert Support</h5>
                    <p class="card-text">Our knowledgeable team is ready to help you 24/7</p>
                </div>
            </div>

            <div class="col-md-4 col-sm-6 animate-fade-in">
                <div class="card h-100 text-center p-4" style="border-top: 4px solid var(--warm-brown);">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">
                        <i class="fas fa-leaf" style="color: var(--primary-green-dark);"></i>
                    </div>
                    <h5 class="card-title">Eco-Friendly</h5>
                    <p class="card-text">Committed to sustainable practices and environmental responsibility</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media (max-width: 768px) {
        .hero-section {
            padding: 50px 20px;
        }
        
        .section {
            padding: 2rem 0;
        }
    }
</style>

@endsection
