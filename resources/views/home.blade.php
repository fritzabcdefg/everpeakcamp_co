<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>EverPeak Camp Co.</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome (for icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #f8f9fa;
        }
        
        .hero-section {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 60px 0;
            text-align: center;
            margin-bottom: 40px;
        }
        
        .hero-section .title {
            font-size: 64px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .hero-section .lead {
            font-size: 18px;
            color: #ecf0f1;
        }
        
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        
        .product-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        
        .product-card:hover {
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            transform: translateY(-5px);
        }
        
        .product-image {
            width: 100%;
            height: 220px;
            object-fit: cover;
            background-color: #ecf0f1;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .product-body {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        
        .product-category {
            display: inline-block;
            background-color: #3498db;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 10px;
            width: fit-content;
        }
        
        .product-name {
            font-size: 16px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 10px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .product-description {
            font-size: 13px;
            color: #7f8c8d;
            margin-bottom: 15px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            flex-grow: 1;
        }
        
        .product-price {
            font-size: 24px;
            font-weight: 700;
            color: #e74c3c;
            margin-bottom: 15px;
        }
        
        .stock-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .stock-badge.in-stock {
            background-color: #d4edda;
            color: #155724;
        }
        
        .stock-badge.out-of-stock {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .product-actions {
            display: flex;
            gap: 8px;
            margin-top: auto;
        }
        
        .btn-view {
            flex: 1;
            padding: 10px 12px;
            font-size: 13px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            text-align: center;
            transition: background-color 0.3s;
        }
        
        .btn-view:hover {
            background-color: #2980b9;
            color: white;
        }
        
        .btn-cart {
            flex: 1;
            padding: 10px 12px;
            font-size: 13px;
            background-color: #27ae60;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .btn-cart:hover:not(:disabled) {
            background-color: #229954;
        }
        
        .btn-cart:disabled {
            background-color: #95a5a6;
            cursor: not-allowed;
        }
        
        .pagination {
            margin-top: 40px;
            justify-content: center;
        }
        
        .no-products {
            text-align: center;
            padding: 60px 20px;
            color: #7f8c8d;
        }
        
        .no-products i {
            font-size: 48px;
            margin-bottom: 20px;
            color: #bdc3c7;
        }
    </style>
</head>
<body class="antialiased">

    <!-- Include Bootstrap Navbar -->
    @include('layouts.header')

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <h1 class="title">EverPeak Camp Co.</h1>
            <p class="lead">Your trusted source for outdoor & camping gears.</p>
        </div>
    </div>

    <!-- Products Section -->
    <div class="container">
        @if ($products->count() > 0)
            <div class="product-grid">
                @foreach ($products as $product)
                    <div class="product-card">
                        <!-- Product Image -->
                        @if ($product->img_path)
                            <img src="{{ Storage::url($product->img_path) }}" alt="{{ $product->name }}" class="product-image">
                        @else
                            <div class="product-image">
                                <i class="fas fa-image" style="font-size: 48px; color: #bdc3c7;"></i>
                            </div>
                        @endif

                        <!-- Product Info -->
                        <div class="product-body">
                            @if ($product->category)
                                <span class="product-category">{{ $product->category->name }}</span>
                            @endif
                            <h5 class="product-name">{{ $product->name }}</h5>
                            <p class="product-description">{{ $product->description }}</p>
                            
                            <div class="product-price">
                                ₱{{ number_format($product->sell_price, 2) }}
                            </div>

                            @php
                                $stockQuantity = $product->stock->sum('quantity');
                            @endphp

                            @if ($stockQuantity > 0)
                                <span class="stock-badge in-stock">
                                    <i class="fas fa-check-circle"></i> {{ $stockQuantity }} in stock
                                </span>
                            @else
                                <span class="stock-badge out-of-stock">
                                    <i class="fas fa-times-circle"></i> Out of Stock
                                </span>
                            @endif

                            <!-- Actions -->
                            <div class="product-actions">
                                <a href="{{ route('products.show', $product) }}" class="btn-view">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                @if (Auth::check() && $stockQuantity > 0)
                                    <form action="{{ route('cart.store') }}" method="POST" style="flex: 1;">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn-cart w-100">
                                            <i class="fas fa-shopping-cart"></i> Add to Cart
                                        </button>
                                    </form>
                                @elseif (!Auth::check())
                                    <a href="{{ route('login') }}" class="btn-cart" style="text-decoration: none; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-shopping-cart"></i> Add to Cart
                                    </a>
                                @else
                                    <button class="btn-cart" disabled>
                                        <i class="fas fa-shopping-cart"></i> Out of Stock
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if ($products->hasPages())
                <div class="d-flex justify-content-center">
                    {{ $products->links() }}
                </div>
            @endif
        @else
            <div class="no-products">
                <i class="fas fa-inbox"></i>
                <h4>No Products Available</h4>
                <p>Check back soon for amazing outdoor & camping gear!</p>
            </div>
        @endif
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
