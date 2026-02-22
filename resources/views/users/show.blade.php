@extends('layouts.base')

@section('body')
    <div class="container mt-4">
        @include('layouts.flash-messages')

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
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ $user->email }}</td>
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
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
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
