@extends('layouts.base')

@section('title', 'Shopping Cart - Everpeak Camp Co')

@section('content')
    <!-- Cart Header -->
    <div class="hero-section">
        <div class="container text-center">
            <h1 class="title animate-slide-in-up" style="font-size: 2.5rem; margin-bottom: 1rem;">
                <i class="fas fa-shopping-cart me-2"></i> Shopping Cart
            </h1>
            <p class="lead animate-slide-in-up">Review and manage your items before checkout</p>
        </div>
    </div>

    <div class="container my-5">
        
        @if ($cartItems->count() > 0)
            <!-- Cart Items Section -->
            <div class="table-responsive mb-5">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="py-3"><i class="fas fa-box me-2"></i>Product</th>
                            <th class="text-center py-3"><i class="fas fa-tag me-2"></i>Price</th>
                            <th class="text-center py-3"><i class="fas fa-hashtag me-2"></i>Quantity</th>
                            <th class="text-end py-3"><i class="fas fa-circle-notch me-2"></i>Subtotal</th>
                            <th class="text-center py-3"><i class="fas fa-cog me-2"></i>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; @endphp
                        @foreach ($cartItems as $item)
                            @php $subtotal = $item->product->sell_price * $item->quantity; $total += $subtotal; @endphp
                            <tr style="border-bottom: 1px solid var(--border-color);">
                                <td class="py-4">
                                    <div class="d-flex align-items-center">
                                        @if($item->product->img_path)
                                            <img src="{{ asset('storage/' . $item->product->img_path) }}" alt="{{ $item->product->name }}" 
                                                 style="width: 60px; height: 60px; object-fit: cover; border radius: 8px; margin-right: 15px;">
                                        @else
                                            <div style="width: 60px; height: 60px; background-color: var(--off-white); border-radius: 8px; margin-right: 15px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-image" style="color: #ddd;"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <strong class="d-block" style="color: var(--dark-text);">{{ $item->product->name }}</strong>
                                            <small class="text-muted">#{{ $item->product->product_id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center py-4 align-middle">
                                    <span class="badge" style="background: linear-gradient(135deg, var(--primary-green-light) 0%, var(--accent-green) 100%); font-size: 0.95rem; padding: 0.5rem 0.8rem;">
                                        ₱{{ number_format($item->product->sell_price, 2) }}
                                    </span>
                                </td>
                                <td class="text-center py-4 align-middle">
                                    <form action="{{ route('cart.update', $item->product_id) }}" method="POST" class="d-inline-block quantity-form">
                                        @csrf
                                        @method('PUT')
                                        <div class="input-group input-group-sm" style="width: 120px;">
                                            <button type="button" class="btn btn-outline-secondary btn-decrement" style="border-color: var(--border-color);">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input type="number" name="quantity" value="{{ $item->quantity }}" 
                                                   class="form-control text-center quantity-input" style="border-color: var(--border-color);">
                                            <button type="button" class="btn btn-outline-secondary btn-increment" style="border-color: var(--border-color);">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </form>
                                </td>
                                <td class="text-end py-4 align-middle">
                                    <strong style="color: var(--accent-green); font-size: 1.1rem;">
                                        ₱{{ number_format($subtotal, 2) }}
                                    </strong>
                                </td>
                                <td class="text-center py-4 align-middle">
                                    <form action="{{ route('cart.destroy', $item->product_id) }}" method="POST" style="display:inline;">
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
                    <a href="{{ route('shop.show') }}" class="btn btn-primary btn-lg rounded-pill">
                        <i class="fas fa-arrow-left me-2"></i> Continue Shopping
                    </a>
                </div>
                <div class="col-lg-4">
                    <div class="card shadow-nature rounded-nature">
                        <div style="background: linear-gradient(135deg, var(--primary-green-light) 0%, var(--accent-green) 100%); color: white; padding: 1.5rem;">
                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-receipt me-2"></i>Order Summary
                            </h5>
                        </div>
                        <div class="card-body" style="padding: 1.5rem;">
                            <div class="d-flex justify-content-between mb-3" style="border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem;">
                                <span class="text-muted">Subtotal:</span>
                                <span class="fw-bold">₱{{ number_format($total, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3" style="border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem;">
                                <span class="text-muted">Shipping:</span>
                                @php $shippingFee = 5.00; @endphp
                                <span class="fw-bold" style="color: var(--accent-green);">₱{{ number_format($shippingFee, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-4" style="border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem;">
                                <span class="text-muted">Tax (10%):</span>
                                <span class="fw-bold" style="color: var(--accent-green);">₱{{ number_format($total * 0.10, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-4">
                                <h6 class="mb-0">Total:</h6>
                                <h6 class="mb-0" style="color: var(--accent-green); font-weight: 700;">
                                    ₱{{ number_format($total + ($total * 0.10) + $shippingFee, 2) }}
                                </h6>
                            </div>
                            <a href="{{ route('checkout.index') }}" class="btn btn-success w-100 mb-2 rounded-pill fw-bold">
                                <i class="fas fa-credit-card me-2"></i> Proceed to Checkout
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
                    <i class="fas fa-shopping-cart" style="font-size: 4rem; color: var(--primary-green-light); opacity: 0.3;"></i>
                </div>
                <h3 class="fw-bold mb-3" style="color: var(--primary-green-dark);">Your Cart is Empty</h3>
                <p class="text-muted mb-5" style="font-size: 1.1rem;">
                    Looks like you haven't added anything to your cart yet. <br>
                    Explore our amazing collection of outdoor gear and get ready for your next adventure!
                </p>
            </div>
            <!-- Call to Action -->
            <div class="text-center py-3">
                <a href="{{ route('shop.show') }}" class="btn btn-primary btn-lg rounded-pill">
                    <i class="fas fa-shopping-bag me-2"></i> Shop All Products
                </a>
            </div>
        @endif
    </div>

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
