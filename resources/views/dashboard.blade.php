@extends('layouts.base')

@section('content')
    <div class="container mt-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-chart-line"></i> Dashboard</h2>
            <!-- Date Range Filter -->
            <form method="GET" action="{{ route('dashboard') }}" class="d-flex gap-2">
                <input type="date" name="start_date" value="{{ $startDate }}" class="form-control form-control-sm" style="width: 150px;">
                <input type="date" name="end_date" value="{{ $endDate }}" class="form-control form-control-sm" style="width: 150px;">
                <button type="submit" class="btn btn-sm btn-primary" style="min-width: 110px;"> <i class="fas fa-filter me-1"></i> Filter</button>
                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-secondary" style="min-width: 110px;"> <i class="fas fa-redo me-1"></i> Reset</a>
            </form>
        </div>

        <!-- Stats Cards Row with Quick Actions -->
        <div class="row g-4 mb-5">
            <!-- Total Users -->
            <div class="col-md-6 col-lg-3 mb-4">
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
            <div class="col-md-6 col-lg-3 mb-4">
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
            <div class="col-md-6 col-lg-3 mb-4">
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
            <div class="col-md-6 col-lg-3 mb-4">
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
        <div class="row g-4 mb-5">
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

        <!-- Charts Section -->
        <div class="row g-4 mb-5">
            <!-- Yearly Sales Chart -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0" style="color: var(--cream);"><i class="fas fa-chart-bar me-2"></i>Yearly Sales Revenue</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="yearlySalesChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>

            <!-- Product Sales Percentage Pie Chart -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0" style="color: var(--cream);"><i class="fas fa-chart-pie me-2"></i>Product Sales Distribution</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="productSalesChart" width="300" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            try {
                // Yearly Sales Bar Chart
                const yearlySalesCtx = document.getElementById('yearlySalesChart');
                if (yearlySalesCtx) {
                    const yearlySalesData = @json($yearlySales) || [
                        {year: '2025', total: 1000},
                        {year: '2026', total: 1500}
                    ];

                    new Chart(yearlySalesCtx.getContext('2d'), {
                        type: 'bar',
                        data: {
                            labels: yearlySalesData.map(item => item.year),
                            datasets: [{
                                label: 'Revenue (₱)',
                                data: yearlySalesData.map(item => item.total),
                                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return '₱' + value.toLocaleString();
                                        }
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false
                                }
                            }
                        }
                    });
                }

                // Product Sales Percentage Pie Chart
                const productSalesCtx = document.getElementById('productSalesChart');
                if (productSalesCtx) {
                    const productSalesData = @json($productSales) || [
                        {product_name: 'Test Product', percentage: 100}
                    ];

                    new Chart(productSalesCtx.getContext('2d'), {
                        type: 'pie',
                        data: {
                            labels: productSalesData.map(item => item.product_name + ' (' + item.percentage + '%)'),
                            datasets: [{
                                data: productSalesData.map(item => item.percentage),
                                backgroundColor: [
                                    'rgba(255, 99, 132, 0.6)',
                                    'rgba(54, 162, 235, 0.6)',
                                    'rgba(255, 205, 86, 0.6)',
                                    'rgba(75, 192, 192, 0.6)',
                                    'rgba(153, 102, 255, 0.6)',
                                    'rgba(255, 159, 64, 0.6)',
                                    'rgba(201, 203, 207, 0.6)',
                                    'rgba(255, 99, 255, 0.6)',
                                    'rgba(99, 255, 132, 0.6)',
                                    'rgba(132, 99, 255, 0.6)'
                                ],
                                borderColor: [
                                    'rgba(255, 99, 132, 1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 205, 86, 1)',
                                    'rgba(75, 192, 192, 1)',
                                    'rgba(153, 102, 255, 1)',
                                    'rgba(255, 159, 64, 1)',
                                    'rgba(201, 203, 207, 1)',
                                    'rgba(255, 99, 255, 1)',
                                    'rgba(99, 255, 132, 1)',
                                    'rgba(132, 99, 255, 1)'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        boxWidth: 12,
                                        font: {
                                            size: 11
                                        }
                                    }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return context.label + ': ' + context.parsed + '%';
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            } catch (error) {
                console.error('Chart initialization error:', error);
            }
        });
    </script>
@endsection
