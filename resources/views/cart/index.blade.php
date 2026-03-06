@extends('layouts.base')

@section('title', 'Shopping Cart - EverPeak Camp Co.')

@section('content')
    <!-- Cart Header -->
    <div class="bg-dark text-white py-5" style="background: linear-gradient(135deg, #1a472a 0%, #2d5f3f 100%);">
        <div class="container py-4 text-center">
            <h1 class="display-5 fw-bold mb-2">
                <i class="fas fa-shopping-cart text-warning"></i> Shopping Cart
            </h1>
            <p class="lead text-light">Review and manage your items before checkout</p>
        </div>
    </div>

    <div class="container my-5">
        @include('layouts.flash-messages')

        @if ($cartItems->count() > 0)
            <!-- Cart Items Section -->
            <div class="table-responsive mb-5">
                <table class="table table-hover" style="border-collapse: separate; border-spacing: 0;">
                    <thead style="background: linear-gradient(135deg, #1a472a 0%, #2d5f3f 100%); color: white;">
                        <tr>
                            <th class="py-3"><i class="fas fa-box me-2"></i>Product</th>
                            <th class="text-center py-3"><i class="fas fa-tag me-2"></i>Price</th>
                            <th class="text-center py-3"><i class="fas fa-hashtag me-2"></i>Quantity</th>
                            <th class="text-end py-3"><i class="fas fa-dollar-sign me-2"></i>Subtotal</th>
                            <th class="text-center py-3"><i class="fas fa-cog me-2"></i>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; @endphp
                        @foreach ($cartItems as $item)
                            @php $subtotal = $item->product->sell_price * $item->quantity; $total += $subtotal; @endphp
                            <tr style="border-bottom: 1px solid #e0e0e0; transition: background-color 0.3s;">
                                <td class="py-4">
                                    <div class="d-flex align-items-center">
                                        @if($item->product->img_path)
                                            <img src="{{ asset('storage/' . $item->product->img_path) }}" alt="{{ $item->product->name }}" 
                                                 style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px; margin-right: 15px;">
                                        @else
                                            <div style="width: 60px; height: 60px; background-color: #f0f0f0; border-radius: 8px; margin-right: 15px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <strong class="d-block">{{ $item->product->name }}</strong>
                                            <small class="text-muted">#{{ $item->product->product_id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center py-4 align-middle">
                                    <span class="badge bg-success" style="font-size: 0.95rem; padding: 0.5rem 0.8rem;">
                                        ₱{{ number_format($item->product->sell_price, 2) }}
                                    </span>
                                </td>
                                <td class="text-center py-4 align-middle">
                                    <form action="{{ route('cart.update', $item) }}" method="POST" class="d-inline-block quantity-form">
                                        @csrf
                                        @method('PUT')
                                        <div class="input-group input-group-sm" style="width: 120px;">
                                            <button type="button" class="btn btn-outline-secondary btn-decrement">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" 
                                                   class="form-control text-center quantity-input" style="border-color: #1a472a;">
                                            <button type="button" class="btn btn-outline-secondary btn-increment">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </form>
                                </td>
                                <td class="text-end py-4 align-middle">
                                    <strong style="color: #1a472a; font-size: 1.1rem;">
                                        ₱{{ number_format($subtotal, 2) }}
                                    </strong>
                                </td>
                                <td class="text-center py-4 align-middle">
                                    <form action="{{ route('cart.destroy', $item) }}" method="POST" style="display:inline;">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" 
                                                onclick="return confirm('Remove this item?')" style="width: 40px; height: 40px; padding: 0;">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Cart Summary & Actions -->
            <div class="row g-4 mb-5">
                <div class="col-lg-8">
                    <a href="{{ route('shop.show') }}" class="btn btn-outline-success btn-lg rounded-pill" style="border-width: 2px;">
                        <i class="fas fa-arrow-left me-2"></i> Continue Shopping
                    </a>
                </div>
                <div class="col-lg-4">
                    <div class="card shadow-lg rounded-3" style="border: none; overflow: hidden;">
                        <div style="background: linear-gradient(135deg, #1a472a 0%, #2d5f3f 100%); color: white; padding: 1.5rem;">
                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-receipt me-2"></i>Cart Summary
                            </h5>
                        </div>
                        <div class="card-body" style="padding: 1.5rem;">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Subtotal:</span>
                                <span class="fw-bold">₱{{ number_format($total, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Shipping:</span>
                                @php $shippingFee = 5.00; @endphp
                                <span class="fw-bold text-success">₱{{ number_format($shippingFee, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Tax (10%):</span>
                                <span class="fw-bold text-success">₱{{ number_format($total * 0.10, 2) }}</span>
                            </div>
                            <hr style="border-color: #e0e0e0; margin: 1rem 0;">
                            <div class="d-flex justify-content-between mb-4">
                                <h6 class="mb-0">Total:</h6>
                                <h6 class="mb-0" style="color: #1a472a;">
                                    ₱{{ number_format($total + ($total * 0.10) + $shippingFee, 2) }}
                                </h6>
                            </div>
                            <a href="{{ route('checkout.index') }}" class="btn btn-lg w-100 mb-2 rounded-pill fw-bold" 
                               style="background: linear-gradient(135deg, #1a472a 0%, #2d5f3f 100%); color: white; border: none;">
                                <i class="fas fa-credit-card me-2"></i> Checkout
                            </a>
                            <form action="{{ route('cart.clear') }}" method="POST" style="display:inline-block; width:100%;">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger w-100 rounded-pill fw-bold" 
                                        onclick="return confirm('Are you sure you want to clear your cart?')">
                                    <i class="fas fa-trash-alt me-2"></i> Clear Cart
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty Cart Message -->
            <div class="text-center py-5">
                <div style="margin-bottom: 2rem;">
                    <i class="fas fa-shopping-cart" style="font-size: 4rem; color: #1a472a; opacity: 0.3;"></i>
                </div>
                <h3 class="fw-bold mb-3" style="color: #1a472a;">Your Cart is Empty</h3>
                <p class="text-muted mb-5" style="font-size: 1.1rem;">
                    Looks like you haven't added anything to your cart yet. <br>
                    Explore our amazing collection of outdoor gear and get ready for your next adventure!
                </p>
            </div>

            <!-- Browse Categories Section -->
            <div class="mb-5">
                <h4 class="text-center fw-bold mb-4" style="color: #1a472a;">
                    <i class="fas fa-folder me-2"></i>Browse By Category
                </h4>
                <div class="row g-4">
                    @forelse($categories as $category)
                        <div class="col-md-6 col-lg-3">
                            <a href="{{ route('categories.show', $category->category_id) }}" class="text-decoration-none">
                                <div class="card h-100 shadow-sm hover-scale" style="transition: transform 0.3s ease; border: none; overflow: hidden;">
                                    <div class="card-body text-center py-5" style="background: linear-gradient(135deg, #f5f7fa 0%, #e8eef5 100%);">
                                        <i class="fas fa-campground fa-3x text-success mb-3" style="color: #1a472a;"></i>
                                        <h5 class="card-title fw-bold" style="color: #1a472a;">{{ $category->name }}</h5>
                                        <p class="card-text text-muted small">{{ $category->description ?? 'Explore our collection' }}</p>
                                        <span class="badge bg-success mt-2">
                                            <i class="fas fa-arrow-right me-1"></i>Explore
                                        </span>
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

            <!-- Call to Action -->
            <div class="text-center py-3">
                <a href="{{ route('shop.show') }}" class="btn btn-lg rounded-pill fw-bold" 
                   style="background: linear-gradient(135deg, #1a472a 0%, #2d5f3f 100%); color: white; padding: 0.75rem 2rem;">
                    <i class="fas fa-shopping-bag me-2"></i> Shop All Products
                </a>
            </div>
        @endif
    </div>

    <style>
        tbody tr:hover {
            background-color: #f5f5f5;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        /* hide default number spinner */
        input.quantity-input::-webkit-outer-spin-button,
        input.quantity-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input.quantity-input {
            -moz-appearance: textfield;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.quantity-form').forEach(function(form) {
                var input = form.querySelector('.quantity-input');
                var dec = form.querySelector('.btn-decrement');
                var inc = form.querySelector('.btn-increment');

                dec.addEventListener('click', function() {
                    var val = parseInt(input.value, 10);
                    if (val > 1) {
                        input.value = val - 1;
                        form.submit();
                    }
                });

                inc.addEventListener('click', function() {
                    var val = parseInt(input.value, 10);
                    input.value = val + 1;
                    form.submit();
                });
            });
        });
    </script>
@endsection
