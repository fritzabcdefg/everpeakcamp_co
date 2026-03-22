@extends('layouts.base')

@section('content')
    <div class="container mt-4">
        @include('layouts.flash-messages')

        <div class="d-flex justify-content-between align-items-center mb-5">
            <h2><i class="fas fa-chart-line"></i> Dashboard</h2>
        </div>

        <!-- Stats Cards Row with Quick Actions -->
        <div class="row g-3 mb-5">
            <!-- Total Users -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted small mb-2">Total Users</p>
                                <h3 class="mb-0">{{ $stats['total_users'] }}</h3>
                            </div>
                            <div class="bg-primary bg-opacity-10 p-3 rounded">
                                <i class="fas fa-users text-primary fa-lg"></i>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('users.index') }}" class="card-footer bg-transparent border-top text-center text-decoration-none text-primary small fw-semibold">
                        View Users <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <!-- Quick Action for Users -->
                <a href="{{ route('users.create') }}" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2 mt-2">
                    <i class="fas fa-plus"></i> Add User
                </a>
            </div>

            <!-- Total Products -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted small mb-2">Total Products</p>
                                <h3 class="mb-0">{{ $stats['total_products'] }}</h3>
                            </div>
                            <div class="bg-success bg-opacity-10 p-3 rounded">
                                <i class="fas fa-box text-success fa-lg"></i>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('products.index') }}" class="card-footer bg-transparent border-top text-center text-decoration-none text-success small fw-semibold">
                        View Products <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <!-- Quick Action for Products -->
                <a href="{{ route('products.create') }}" class="btn btn-success w-100 d-flex align-items-center justify-content-center gap-2 mt-2">
                    <i class="fas fa-plus"></i> Add Product
                </a>
            </div>

            <!-- Total Categories -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted small mb-2">Total Categories</p>
                                <h3 class="mb-0">{{ $stats['total_categories'] }}</h3>
                            </div>
                            <div class="bg-info bg-opacity-10 p-3 rounded">
                                <i class="fas fa-list text-info fa-lg"></i>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('categories.index') }}" class="card-footer bg-transparent border-top text-center text-decoration-none text-info small fw-semibold">
                        View Categories <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <!-- Quick Action for Categories -->
                <a href="{{ route('categories.create') }}" class="btn btn-info w-100 d-flex align-items-center justify-content-center gap-2 mt-2">
                    <i class="fas fa-plus"></i> Add Category
                </a>
            </div>

            <!-- Total Orders -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted small mb-2">Total Orders</p>
                                <h3 class="mb-0">{{ $stats['total_orders'] }}</h3>
                            </div>
                            <div class="bg-warning bg-opacity-10 p-3 rounded">
                                <i class="fas fa-shopping-cart text-warning fa-lg"></i>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('orders.index') }}" class="card-footer bg-transparent border-top text-center text-decoration-none text-warning small fw-semibold">
                        View Orders <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <!-- Quick Action for Orders -->
                <a href="{{ route('orders.create') }}" class="btn btn-warning w-100 d-flex align-items-center justify-content-center gap-2 mt-2">
                    <i class="fas fa-plus"></i> Create Order
                </a>
            </div>
        </div>

        <!-- Revenue & Pending Orders Row -->
        <div class="row g-3 mb-5">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0"><i class="fas fa-dollar-sign text-success"></i> Total Revenue</h5>
                        </div>
                        <h2 class="text-success mb-2">₱{{ number_format($stats['total_revenue'] ?? 0, 2) }}</h2>
                        <p class="text-muted small mb-0">From all completed and pending orders</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0"><i class="fas fa-clock text-danger"></i> Pending Orders</h5>
                        </div>
                        <h2 class="text-danger mb-2">{{ $stats['pending_orders'] }}</h2>
                        <p class="text-muted small mb-0">Orders waiting to be processed</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
