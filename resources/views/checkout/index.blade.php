@extends('layouts.base')

@section('title', 'Checkout - EverPeak Camp Co.')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-md-7">
            <h3 class="mb-3">Confirm Order</h3>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Shipping Address</h5>
                    <form id="checkout-form" action="{{ route('orders.checkout') }}" method="POST">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $customer->name ?? auth()->user()->name ?? '') }}" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $customer->email ?? auth()->user()->email ?? '') }}" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $customer->phone ?? '') }}">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control" rows="3" required>{{ old('address', $customer->address ?? '') }}</textarea>
                        </div>

                        <input type="hidden" name="shipping_fee" id="shipping_fee" value="{{ number_format($shippingFee, 2, '.', '') }}">
                    </form>
                </div>
            </div>

            <a href="{{ route('cart.index') }}" class="btn btn-link">Back to cart</a>
        </div>
        <div class="col-md-5">
            <h5 class="mb-3">Order Summary</h5>
            <div class="card">
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @php $subtotal = 0; @endphp
                        @foreach($cartItems as $ci)
                            @php $line = ($ci->product->sell_price * $ci->quantity); $subtotal += $line; @endphp
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-bold">{{ $ci->product->name }}</div>
                                    <small>${{ number_format($ci->product->sell_price,2) }} × {{ $ci->quantity }}</small>
                                </div>
                                <div>${{ number_format($line,2) }}</div>
                            </li>
                        @endforeach
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Subtotal</span>
                            <strong>${{ number_format($subtotal,2) }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Shipping</span>
                            <strong>${{ number_format($shippingFee,2) }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Total</span>
                            <strong>${{ number_format($subtotal + $shippingFee,2) }}</strong>
                        </li>
                    </ul>

                    <div class="mt-3">
                        <button type="submit" form="checkout-form" class="btn btn-lg btn-success w-100">Place Order</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
