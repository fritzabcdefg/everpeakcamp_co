@extends('layouts.base')

@section('content')
    <div class="container mt-4">

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h4 class="mb-0"><i class="fas fa-user"></i> User Profile</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <th width="30%">ID:</th>
                                <td>#{{ $user->id }}</td>
                            </tr>
                            <tr>
                                <th>Name:</th>
                                <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>Phone:</th>
                                <td>{{ $user->phone ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Address:</th>
                                <td>{{ $user->address ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Created:</th>
                                <td>{{ $user->created_at->format('M d, Y h:i A') }}</td>
                            </tr>
                        </table>

                        <hr>

                        <h5>Activity Summary</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h3 class="text-primary">{{ $user->orders->count() }}</h3>
                                        <p class="mb-0">Total Orders</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h3 class="text-success">{{ $user->cartItems->count() }}</h3>
                                        <p class="mb-0">Cart Items</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h3 class="text-warning">{{ $user->reviews->count() }}</h3>
                                        <p class="mb-0">Reviews Written</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($user->orders->count() > 0)
                            <hr>
                            <h5>Recent Orders</h5>
                            <div class="table-responsive">
                                <table class="table table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Date</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($user->orders->take(5) as $order)
                                            <tr>
                                                <td>#{{ $order->order_id }}</td>
                                                <td>{{ $order->order_date->format('M d, Y') }}</td>
                                                @php $oTotal = $order->orderItems->sum(fn($it)=>$it->quantity*$it->unit_price) + ($order->shipping_fee ?? 0); @endphp
                                                <td>₱{{ number_format($oTotal, 2) }}</td>
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
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer">
                        @if(Auth::check() && Auth::id() === $user->id)
                            <a href="{{ route('profile.edit') }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        @else
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        @endif
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                        <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline;">
                            @method('DELETE')
                            @csrf
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
