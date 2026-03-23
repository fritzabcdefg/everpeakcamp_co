@extends('layouts.base')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0"><i class="fas fa-star"></i> Review Completed Order #{{ $order->order_id }}</h4>
            </div>
            <div class="card-body">
                <p class="mb-3">
                    This order is completed. Please choose the product(s) you want to review below.
                    You can only submit a review for each product once.
                </p>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>Order Details</h6>
                        <table class="table table-sm table-borderless">
                            <tr><th>Order Date:</th><td>{{ $order->order_date->format('M d, Y h:i A') }}</td></tr>
                            <tr><th>Status:</th><td>{{ ucfirst($order->status) }}</td></tr>
                            <tr><th>Items:</th><td>{{ $order->orderItems->count() }} item(s)</td></tr>
                            <tr><th>Total:</th>
                                @php $subtotal = $order->orderItems->sum(fn($it) => $it->quantity * $it->unit_price); $tax = $subtotal * 0.10; $total = $subtotal + $tax + ($order->shipping_fee ?? 0); @endphp
                                <td>₱{{ number_format($total, 2) }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Shipping / Customer</h6>
                        <table class="table table-sm table-borderless">
                            <tr><th>Customer:</th><td>{{ $order->customer->name ?? 'N/A' }}</td></tr>
                            <tr><th>Email:</th><td>{{ $order->customer->email ?? 'N/A' }}</td></tr>
                            <tr><th>Phone:</th><td>{{ $order->customer->phone ?? 'N/A' }}</td></tr>
                            <tr><th>Address:</th><td>{{ $order->customer->address ?? 'N/A' }}</td></tr>
                            <tr><th>Shipping Fee:</th><td>₱{{ number_format($order->shipping_fee ?? 0, 2) }}</td></tr>
                        </table>
                    </div>
                </div>

                <h6>Products in this order</h6>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orderItems as $item)
                                <tr>
                                    <td>{{ $item->product->name ?? 'N/A' }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>₱{{ number_format($item->unit_price, 2) }}</td>
                                    <td>
                                        @if (in_array($item->product_id, $reviewedProductIds))
                                            <span class="badge bg-success">Reviewed</span>
                                        @else
                                            <span class="badge bg-secondary">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if (in_array($item->product_id, $reviewedProductIds))
                                            <a href="{{ route('products.show', $item->product) }}#reviews" class="btn btn-sm btn-outline-secondary">View Reviews</a>
                                        @else
                                            <a href="{{ route('product.review.create', $item->product) }}" class="btn btn-sm btn-primary">Leave Review</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
@endsection
