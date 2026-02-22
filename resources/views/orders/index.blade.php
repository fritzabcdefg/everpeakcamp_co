@extends('layouts.base')

@section('body')
    <div class="container mt-4">
        @include('layouts.flash-messages')

        <h2><i class="fas fa-receipt"></i> Orders</h2>

        @if ($orders->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Order Date</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Items</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td><strong>#{{ $order->order_id }}</strong></td>
                                <td>{{ $order->order_date->format('M d, Y') }}</td>
                                <td>${{ number_format($order->total_amount, 2) }}</td>
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
                                        @default
                                            <span class="badge bg-secondary">{{ $order->status }}</span>
                                    @endswitch
                                </td>
                                <td>{{ $order->orderItems->count() }} items</td>
                                <td>
                                    <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <a href="{{ route('orders.edit', $order) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center">
                {{ $orders->links() }}
            </div>
        @else
            <div class="alert alert-info mt-4" role="alert">
                <i class="fas fa-info-circle"></i> No orders found. <a href="{{ route('products.index') }}">Start shopping</a>
            </div>
        @endif

        <div class="mt-4">
            <a href="{{ route('orders.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create Order
            </a>
        </div>
    </div>
@endsection
