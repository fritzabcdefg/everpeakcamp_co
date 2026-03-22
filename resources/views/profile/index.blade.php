@extends('layouts.base')

@php use Illuminate\Support\Facades\Storage; @endphp

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-white" style="background: linear-gradient(135deg, #1a472a 0%, #2d5f3f 100%);">
                        <h4 class="mb-0"><i class="fas fa-user"></i> My Profile</h4>
                    </div>
                    <div class="card-body">
                        <!-- Profile Photo Section -->
                        <div class="text-center mb-4">
                            @if (Auth::user()->photo)
                                <img src="{{ Storage::url(Auth::user()->photo) }}" alt="Profile Photo" 
                                     class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover; border: 3px solid var(--primary-green-light);">
                            @else
                                <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center" 
                                     style="width: 150px; height: 150px; border: 3px solid var(--primary-green-light);">
                                    <i class="fas fa-user fa-5x text-muted"></i>
                                </div>
                            @endif
                            <h5 class="mt-3" style="color: var(--primary-green-light);">{{ Auth::user()->name }}</h5>
                            @if (Auth::user()->email_verified_at)
                                <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Email Verified</span>
                            @else
                                <span class="badge bg-warning text-dark"><i class="fas fa-exclamation-circle me-1"></i>Email Not Verified</span>
                            @endif
                        </div>

                        <hr>

                        <table class="table table-borderless">
                            <tr>
                                <th width="30%">Name:</th>
                                <td>{{ Auth::user()->name }}</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ Auth::user()->email }}</td>
                            </tr>
                            <tr>
                                <th>Phone:</th>
                                <td>{{ Auth::user()->phone ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Address:</th>
                                <td>{{ Auth::user()->address ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Member Since:</th>
                                <td>{{ Auth::user()->created_at->format('M d, Y') }}</td>
                            </tr>
                        </table>

                        <hr>

                        <h5>Activity Summary</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h3 class="text-primary">{{ Auth::user()->orders->count() }}</h3>
                                        <p class="mb-0">Total Orders</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h3 class="text-success">{{ Auth::user()->cartItems->count() }}</h3>
                                        <p class="mb-0">Cart Items</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h3 class="text-warning">{{ Auth::user()->reviews->count() }}</h3>
                                        <p class="mb-0">Reviews Written</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if (Auth::user()->orders->count() > 0)
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
                                        @foreach (Auth::user()->orders->take(5) as $order)
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
                        <a href="{{ route('profile.edit') }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit Profile
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
