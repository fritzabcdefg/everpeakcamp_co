@extends('layouts.base')

@section('body')
    <div class="container mt-4">
        @include('layouts.flash-messages')

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h4 class="mb-0"><i class="fas fa-receipt"></i> Order #{{ $order->order_id }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6>Order Information</h6>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <th>Order ID:</th>
                                        <td>#{{ $order->order_id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Order Date:</th>
                                        <td>{{ $order->order_date->format('M d, Y h:i A') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status:</th>
                                        <td>
                                            @switch($order->status)
                                                @case('pending')
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                    @break
                                                @case('processing')
                                                    <span class="badge bg-info">Processing</span>
                                                    @break
                                                @case('completed')
                                                    <span class="badge bg-success">Completed</span>
                                                    @break
                                                @case('cancelled')
                                                    <span class="badge bg-danger">Cancelled</span>
                                                    @break
                                            @endswitch
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6>Customer Information</h6>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <th>Customer:</th>
                                        <td>{{ $order->customer->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td>{{ $order->customer->email ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Phone:</th>
                                        <td>{{ $order->customer->phone ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Address:</th>
                                        <td>{{ $order->customer->address ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <hr>

                        <h6>Order Items</h6>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Qty</th>
                                        <th>Unit Price</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($order->orderItems as $item)
                                        <tr>
                                            <td>
                                                <strong>{{ $item->product->name }}</strong><br>
                                                <small class="text-muted">#{{ $item->product->product_id }}</small>
                                            </td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>₱{{ number_format($item->unit_price, 2) }}</td>
                                            <td>₱{{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">No items in this order</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6"></div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        @php
                                            $subt = $order->orderItems->sum(fn($it) => $it->quantity * $it->unit_price);
                                            $tax = $subt * 0.10;
                                            $total = $subt + $tax + ($order->shipping_fee ?? 0);
                                        @endphp
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Subtotal:</span>
                                            <span>₱{{ number_format($subt, 2) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-3">
                                            <span>Tax (10%):</span>
                                            <span>₱{{ number_format($tax, 2) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-3">
                                            <span>Shipping:</span>
                                            <span>₱{{ number_format($order->shipping_fee ?? 0, 2) }}</span>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between">
                                            <h5>Total:</h5>
                                            <h5>₱{{ number_format($total, 2) }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('orders.edit', $order) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
