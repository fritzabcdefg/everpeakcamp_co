@extends('layouts.base')

@section('body')
    <div class="container mt-4">
        @include('layouts.flash-messages')

        <h2><i class="fas fa-shopping-cart"></i> Shopping Cart</h2>

        @if ($cartItems->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; @endphp
                        @foreach ($cartItems as $item)
                            @php $subtotal = $item->product->sell_price * $item->quantity; $total += $subtotal; @endphp
                            <tr>
                                <td>
                                    <strong>{{ $item->product->name }}</strong><br>
                                    <small class="text-muted">#{{ $item->product->product_id }}</small>
                                </td>
                                <td>${{ number_format($item->product->sell_price, 2) }}</td>
                                <td>
                                    <form action="{{ route('cart.update', $item) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PUT')
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" 
                                               class="form-control" style="width: 80px; display:inline-block;">
                                        <button type="submit" class="btn btn-sm btn-info">Update</button>
                                    </form>
                                </td>
                                <td><strong>${{ number_format($subtotal, 2) }}</strong></td>
                                <td>
                                    <form action="{{ route('cart.destroy', $item) }}" method="POST" style="display:inline;">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Remove from cart?')">
                                            <i class="fas fa-trash"></i> Remove
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row mt-4">
                <div class="col-md-8"></div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Cart Summary</h5>
                            <hr>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span>${{ number_format($total, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span>Tax (10%):</span>
                                <span>${{ number_format($total * 0.10, 2) }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <h5>Total:</h5>
                                <h5>${{ number_format($total * 1.10, 2) }}</h5>
                            </div>
                            <a href="{{ route('orders.create') }}" class="btn btn-success btn-block w-100 mb-2">
                                <i class="fas fa-credit-card"></i> Proceed to Checkout
                            </a>
                            <form action="{{ route('cart.clear') }}" method="POST" style="display:inline-block; width:100%;">
                                @csrf
                                <button type="submit" class="btn btn-secondary w-100" onclick="return confirm('Clear cart?')">
                                    <i class="fas fa-trash"></i> Clear Cart
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-info mt-4" role="alert">
                <i class="fas fa-info-circle"></i> Your cart is empty. <a href="{{ route('products.index') }}">Continue shopping</a>
            </div>
        @endif

        <div class="mt-4">
            <a href="{{ route('products.index') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Continue Shopping
            </a>
        </div>
    </div>
@endsection
