@extends('layouts.base')

@section('body')
    <div class="container-fluid mt-4">
        @include('layouts.flash-messages')

        <h1 class="mb-4"><i class="fas fa-chart-line"></i> Dashboard - Outdoor & Camping Gears Shop</h1>

        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">Total Users</h6>
                                <h2 class="mb-0">{{ $stats['total_users'] }}</h2>
                            </div>
                            <i class="fas fa-users fa-3x opacity-50"></i>
                        </div>
                    </div>
                    <a href="{{ route('users.index') }}" class="card-footer text-white text-decoration-none">
                        View Users <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">Total Products</h6>
                                <h2 class="mb-0">{{ $stats['total_products'] }}</h2>
                            </div>
                            <i class="fas fa-box fa-3x opacity-50"></i>
                        </div>
                    </div>
                    <a href="{{ route('products.index') }}" class="card-footer text-white text-decoration-none">
                        View Products <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">Total Categories</h6>
                                <h2 class="mb-0">{{ $stats['total_categories'] }}</h2>
                            </div>
                            <i class="fas fa-list fa-3x opacity-50"></i>
                        </div>
                    </div>
                    <a href="{{ route('categories.index') }}" class="card-footer text-white text-decoration-none">
                        View Categories <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card bg-warning text-dark">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">Total Orders</h6>
                                <h2 class="mb-0">{{ $stats['total_orders'] }}</h2>
                            </div>
                            <i class="fas fa-shopping-cart fa-3x opacity-50"></i>
                        </div>
                    </div>
                    <a href="{{ route('orders.index') }}" class="card-footer text-dark text-decoration-none">
                        View Orders <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0"><i class="fas fa-dollar-sign"></i> Total Revenue</h5>
                    </div>
                    <div class="card-body">
                        <h2 class="text-success">${{ number_format($stats['total_revenue'] ?? 0, 2) }}</h2>
                        <p class="text-muted mb-0">From all completed and pending orders</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="fas fa-clock"></i> Pending Orders</h5>
                    </div>
                    <div class="card-body">
                        <h2>{{ $stats['pending_orders'] }}</h2>
                        <p class="text-muted mb-0">Orders waiting to be processed</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="fas fa-tasks"></i> Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('products.create') }}" class="btn btn-primary btn-block w-100">
                                    <i class="fas fa-plus"></i> Add New Product
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('categories.create') }}" class="btn btn-success btn-block w-100">
                                    <i class="fas fa-plus"></i> Add New Category
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('users.create') }}" class="btn btn-info btn-block w-100">
                                    <i class="fas fa-plus"></i> Add New User
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('orders.create') }}" class="btn btn-warning btn-block w-100">
                                    <i class="fas fa-plus"></i> Create Order
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
