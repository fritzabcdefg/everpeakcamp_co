@extends('layouts.base')

@section('title', 'Welcome to EverPeak Camp - Outdoor & Camping Gear')

@section('content')
<!-- Hero Section -->
<div class="bg-dark text-white py-5" style="background: linear-gradient(135deg, #1a472a 0%, #2d5f3f 100%);">
    <div class="container py-5 text-center">
        <h1 class="display-4 fw-bold mb-3">
            <i class="fas fa-mountain"></i> Welcome to EverPeak Camp
        </h1>
        <p class="lead mb-4">Your Ultimate Destination for Outdoor & Camping Gear</p>
        <a href="{{ route('products.index') }}" class="btn btn-lg btn-warning">
            <i class="fas fa-shopping-bag"></i> Shop Now
        </a>
    </div>
</div>

<!-- Featured Products Section -->
<div class="container my-5">
    <h2 class="text-center mb-4 fw-bold">Featured Products</h2>
    <div class="row g-4">
        @forelse($products as $product)
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 shadow-sm" style="transition: transform 0.3s ease;">
                    <!-- Product Image -->
                    <div style="height: 200px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                        @if($product->img_path)
                            <img src="{{ asset('storage/' . $product->img_path) }}" alt="{{ $product->name }}" 
                                 style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <i class="fas fa-image fa-3x text-muted"></i>
                        @endif
                    </div>

                    <div class="card-body d-flex flex-column">
                        <h6 class="card-subtitle text-muted mb-2">{{ $product->category->name ?? 'Uncategorized' }}</h6>
                        <h5 class="card-title">{{ \Illuminate\Support\Str::limit($product->name, 30) }}</h5>
                        <p class="card-text text-muted small flex-grow-1">{{ \Illuminate\Support\Str::limit($product->description, 60) }}</p>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <span class="h5 text-success fw-bold">${{ number_format($product->sell_price, 2) }}</span>
                                @if($product->cost_price < $product->sell_price)
                                    <span class="text-muted text-decoration-line-through small" style="font-size: 0.9rem;">
                                        ${{ number_format($product->cost_price, 2) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="mt-3">
                            <a href="{{ route('products.show', $product->product_id) }}" class="btn btn-sm btn-outline-primary w-100" style="font-size: 0.85rem;">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                        </div>

                        @auth
                            <form action="{{ route('cart.store') }}" method="POST" class="mt-2">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-sm btn-success w-100">
                                    <i class="fas fa-cart-plus"></i> Add to Cart
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-sm btn-success w-100 mt-2">
                                <i class="fas fa-cart-plus"></i> Add to Cart
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p class="text-center text-muted">No products available yet. Check back soon!</p>
            </div>
        @endforelse
    </div>

    @if(count($products) > 0)
        <div class="text-center mt-5">
            <a href="{{ route('shop.show') }}" class="btn btn-lg rounded-pill fw-bold" 
               style="background: linear-gradient(135deg, #1a472a 0%, #2d5f3f 100%); color: white; padding: 0.75rem 2rem;">
                <i class="fas fa-shopping-bag me-2"></i> View All Products
            </a>
        </div>
    @endif
</div>

<!-- Featured Categories Section -->
<div class="container my-5">
    <h2 class="text-center mb-4 fw-bold">Shop by Category</h2>
    <div class="row g-4">
        @forelse($categories as $category)
            <div class="col-md-6 col-lg-3">
                <a href="{{ route('categories.show', $category->category_id) }}" class="text-decoration-none">
                    <div class="card h-100 shadow-sm hover-scale" style="transition: transform 0.3s ease;">
                        <div class="card-body text-center">
                            <i class="fas fa-campground fa-3x text-success mb-3"></i>
                            <h5 class="card-title">{{ $category->name }}</h5>
                            <p class="card-text text-muted small">{{ $category->description ?? 'Explore our collection' }}</p>
                            <span class="badge bg-success">Explore</span>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12">
                <p class="text-center text-muted">No categories available yet.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Info Section -->
<div class="bg-light py-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-4 mb-3">
                <i class="fas fa-shipping-fast fa-2x text-success mb-2"></i>
                <h5>Fast Shipping</h5>
                <p class="text-muted">Quick delivery to your doorstep</p>
            </div>
            <div class="col-md-4 mb-3">
                <i class="fas fa-shield-alt fa-2x text-success mb-2"></i>
                <h5>Secure Payment</h5>
                <p class="text-muted">Your transactions are safe and secure</p>
            </div>
            <div class="col-md-4 mb-3">
                <i class="fas fa-undo fa-2x text-success mb-2"></i>
                <h5>Easy Returns</h5>
                <p class="text-muted">30-day money back guarantee</p>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-scale:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15) !important;
    }
    
    .card {
        border: none;
        overflow: hidden;
    }
    
    .card:hover {
        box-shadow: 0 8px 20px rgba(0,0,0,0.1) !important;
    }
</style>
@endsection
 