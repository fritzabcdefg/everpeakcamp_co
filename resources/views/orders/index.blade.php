@extends('layouts.base')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header text-white" style="color: var(--cream); background: linear-gradient(135deg, #1a472a 0%, #2d5f3f 100%);">
                        <h4 class="mb-0"><i class="fas fa-receipt"></i> Orders</h4>
                    </div>
                    <div class="card-body">
                        @if ($orders->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Order Date</th>
                                            @if (Auth::check() && Auth::user()->role === 'admin')
                                                <th>Customer</th>
                                            @endif
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
                                                @if (Auth::check() && Auth::user()->role === 'admin')
                                                    <td>{{ $order->customer->name ?? 'N/A' }}<br><small class="text-muted">{{ $order->customer->email ?? 'N/A' }}</small></td>
                                                @endif
                                                @php $rowTotal = $order->orderItems->sum(fn($it) => $it->quantity * $it->unit_price) + ($order->shipping_fee ?? 0); @endphp
                                                <td>₱{{ number_format($rowTotal, 2) }}</td>
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
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <a href="{{ route('orders.show', $order) }}" class="btn btn-info">
                                                            <i class="fas fa-eye"></i> View
                                                        </a>
                                                        @if ($order->status === 'completed' && (!Auth::check() || Auth::user()->role !== 'admin'))
                                                            <a href="{{ route('orders.review', $order) }}" class="btn btn-success">
                                                                <i class="fas fa-star"></i> Review
                                                            </a>
                                                        @endif
                                                    </div>
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
                                <i class="fas fa-info-circle"></i> You have no orders yet. <a href="{{ route('shop.show') }}">Start shopping</a>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('home') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
