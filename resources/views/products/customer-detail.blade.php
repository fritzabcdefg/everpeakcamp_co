@extends('layouts.base')

@section('title', 'Product Details - EverPeak Camp')

@section('content')
<div class="container my-5">
    @include('layouts.flash-messages')

    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color: var(--primary-green-light);">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shop.show') }}" style="color: var(--primary-green-light);">Shop</a></li>
            @if($product->category)
                <li class="breadcrumb-item"><a href="{{ route('shop.show', ['category' => $product->category->category_id]) }}" style="color: var(--primary-green-light);">{{ $product->category->name }}</a></li>
            @endif
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Images -->
        <div class="col-lg-5 mb-4">
            <div class="card rounded-nature shadow-sm border-0">
                <!-- Main Image -->
                <div style="height: 400px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; overflow: hidden; border-radius: 12px;">
                    @if($product->img_path)
                        <img id="mainImage" src="{{ asset('storage/' . $product->img_path) }}" alt="{{ $product->name }}" 
                             style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <i class="fas fa-image fa-5x text-muted"></i>
                    @endif
                </div>

                <!-- Additional Images as Thumbnails -->
                @if($product->images->count() > 0)
                    <div class="card-body pt-3">
                        <div class="row g-2">
                            @foreach($product->images as $image)
                                <div class="col-3">
                                    <img src="{{ asset('storage/' . $image->img_path) }}" alt="Product image" 
                                         class="img-thumbnail rounded cursor-pointer" 
                                         onclick="document.getElementById('mainImage').src = this.src;"
                                         style="cursor: pointer; height: 80px; object-fit: cover;">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Product Information -->
        <div class="col-lg-7">
            <div class="card rounded-nature shadow-sm border-0">
                <div class="card-body p-4">
                    <!-- Product Name & Category -->
                    <h1 class="mb-2" style="color: var(--primary-green-light); font-weight: 700;">{{ $product->name }}</h1>
                    @if($product->category)
                        <span class="badge" style="background-color: var(--primary-green-light);">{{ $product->category->name }}</span>
                    @endif

                    <!-- Rating & Reviews Count -->
                    @php
                        $avgRating = $product->reviews->avg('rating');
                        $reviewCount = $product->reviews->count();
                    @endphp
                    @if($reviewCount > 0)
                        <div class="mt-3 mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-warning text-dark">
                                    @for ($i = 0; $i < round($avgRating); $i++)
                                        ⭐
                                    @endfor
                                    {{ round($avgRating, 1) }}/5
                                </span>
                                <small class="text-muted">({{ $reviewCount }} {{ $reviewCount === 1 ? 'review' : 'reviews' }})</small>
                            </div>
                        </div>
                    @endif

                    <hr>

                    <!-- Price Section -->
                    <div class="mb-4">
                        <p class="text-muted mb-1">Price</p>
                        <h2 style="color: var(--accent-green); font-weight: 700;">₱{{ number_format($product->sell_price, 2) }}</h2>
                        @if($product->cost_price < $product->sell_price)
                            <small class="text-muted">
                                <s>₱{{ number_format($product->cost_price, 2) }}</s>
                            </small>
                        @endif
                    </div>

                    <!-- Stock Status -->
                    <div class="mb-4">
                        @php $totalStock = $product->stock->sum('quantity'); @endphp
                        @if($totalStock > 0)
                            <span class="badge bg-success">In Stock ({{ $totalStock }} available)</span>
                        @else
                            <span class="badge bg-danger">Out of Stock</span>
                        @endif
                    </div>

                    <!-- Add to Cart -->
                    @if($totalStock > 0)
                        <form action="{{ route('cart.store') }}" method="POST" class="mb-4">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                            <div class="input-group mb-3">
                                <input type="number" name="quantity" class="form-control" value="1" min="1" max="{{ $totalStock }}">
                                <button class="btn btn-success" type="submit">
                                    <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                                </button>
                            </div>
                        </form>
                    @else
                        <button class="btn btn-secondary w-100 mb-4" disabled>
                            <i class="fas fa-ban me-2"></i>Out of Stock
                        </button>
                    @endif

                    <!-- Back to Shop -->
                    <a href="{{ route('shop.show') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Shop
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Description -->
    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="card rounded-nature shadow-sm border-0">
                <div class="card-header p-4" style="background: linear-gradient(135deg, var(--primary-green-light) 0%, var(--accent-green) 100%); color: white;">
                    <h5 class="mb-0">
                        <i class="fas fa-align-left me-2"></i>Product Description
                    </h5>
                </div>
                <div class="card-body p-4">
                    <p>{{ $product->description }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="row mt-5">
        <div class="col-lg-12">
            @php
                $userReview = null;
                if (Auth::check()) {
                    $userReview = $product->reviews()->where('user_id', Auth::id())->first();
                }
            @endphp
            
            @include('reviews.display')
        </div>
    </div>
</div>

<style>
.cursor-pointer {
    cursor: pointer;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.cursor-pointer:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}
</style>
@endsection
