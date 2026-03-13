@extends('layouts.base')

@section('title', 'Welcome to EverPeak Camp - Outdoor & Camping Gear')

@section('content')
<!-- Hero Section with Animation -->
<div class="hero-section">
    <div class="container py-5 text-center">
        <h1 class="title animate-slide-in-up" style="color: var(--cream);">
            <i class="fas fa-mountain me-2"></i> Welcome to EverPeak Camp
        </h1>
        <p class="lead animate-slide-in-up" style="color: var(--cream);">
            Your Ultimate Destination for Premium Outdoor & Camping Gear
        </p>
        <a href="{{ route('products.index') }}" class="btn btn-lg btn-warning mt-4">
            <i class="fas fa-shopping-bag me-2"></i> Explore Our Collection
        </a>
    </div>
</div>

<!-- Featured Products Section -->
<div class="section">
    <div class="container">
        <div class="section-header">
            <h2><i class="fas fa-star me-2" style="color: var(--terracotta);"></i> Featured Products</h2>
            <p class="section-subtitle">Discover our handpicked selection of premium outdoor gear</p>
        </div>
        
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

                            @auth
                                <form action="{{ route('cart.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-sm btn-success w-100">
                                        <i class="fas fa-cart-plus me-1"></i> Add to Cart
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-sm btn-success">
                                    <i class="fas fa-cart-plus me-1"></i> Add to Cart
                                </a>
                            @endauth
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
                <a href="{{ route('categories.show', $category->category_id) }}" class="text-decoration-none">
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
 